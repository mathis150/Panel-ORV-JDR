<?php
    // L'instanciation de WebPageBases vérifie la session avant tout
    require_once "./import/base.php";
    $bases = new WebPageBases();

    $currentPage    = $_GET['page']     ?? 'users';
    $currentSubPage = $_GET['sub-page'] ?? 'list';
    $formError      = null;
    $formData       = [];
    $notice         = htmlspecialchars($_GET['notice'] ?? '');

    // ── Gestion des formulaires POST ──────────────────────────────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $currentPage === 'users') {
        require_once './import/users.php';
        $um   = new UsersManager();
        $uuid = $_GET['uuid'] ?? '';

        switch ($currentSubPage) {

            case 'create':
                $result = $um->createUser(
                    trim($_POST['pseudo']       ?? ''),
                    trim($_POST['email']        ?? ''),
                    $_POST['role']              ?? 'player',
                    trim($_POST['display_name'] ?? ''),
                    trim($_POST['mj_notes']     ?? '')
                );
                if ($result['success']) {
                    header('Location: jdr-params?page=users&sub-page=list&notice=' . rawurlencode($result['message']));
                    exit;
                }
                $formError = $result['message'];
                $formData  = $_POST;
                break;

            case 'modify':
                if (($_POST['action'] ?? '') === 'reset_password') {
                    $result = $um->resetPassword($uuid);
                    header('Location: jdr-params?page=users&sub-page=modify&uuid=' . rawurlencode($uuid) . '&notice=' . rawurlencode($result['message']));
                    exit;
                }
                $result = $um->updateUser(
                    $uuid,
                    trim($_POST['pseudo']       ?? ''),
                    trim($_POST['display_name'] ?? ''),
                    trim($_POST['email']        ?? ''),
                    $_POST['role']              ?? 'player',
                    trim($_POST['mj_notes']     ?? ''),
                    isset($_POST['is_active'])
                );
                if ($result['success']) {
                    header('Location: jdr-params?page=users&sub-page=modify&uuid=' . rawurlencode($uuid) . '&notice=' . rawurlencode($result['message']));
                    exit;
                }
                $formError = $result['message'];
                $formData  = $_POST;
                break;

            case 'delete':
                $um->deleteUser($uuid);
                header('Location: jdr-params?page=users&sub-page=list&notice=' . rawurlencode('Utilisateur supprimé.'));
                exit;
        }
    }

    // ── Personnages ───────────────────────────────────────────────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $currentPage === 'characters') {
        require_once './import/characters.php';
        $cm     = new CharactersManager();
        $uuid   = $_GET['uuid'] ?? '';
        $action = $_POST['action'] ?? 'update';
        $tab    = $_GET['tab']  ?? 'cm-identity';
        $backModify = 'jdr-params?page=characters&sub-page=modify&uuid=' . rawurlencode($uuid) . '&tab=' . rawurlencode($tab);

        switch ($currentSubPage) {

            case 'create':
                // Gestion upload image
                $imagePath = null;
                if (!empty($_FILES['apparence_image']['tmp_name'])) {
                    $uploadDir = __DIR__ . '/img/characters/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    $ext = strtolower(pathinfo($_FILES['apparence_image']['name'], PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg','jpeg','png','gif','webp'], true)) {
                        $fname = uniqid('char_', true) . '.' . $ext;
                        if (move_uploaded_file($_FILES['apparence_image']['tmp_name'], $uploadDir . $fname)) {
                            $imagePath = './img/characters/' . $fname;
                        }
                    }
                }
                $result = $cm->createCharacter(array_merge($_POST, ['apparence_image' => $imagePath]));
                if ($result['success']) {
                    header('Location: jdr-params?page=characters&sub-page=modify&uuid=' . rawurlencode($result['uuid']) . '&tab=cm-identity&notice=' . rawurlencode($result['message']));
                    exit;
                }
                $formError = $result['message'];
                $formData  = $_POST;
                break;

            case 'modify':
                // Actions sur les champs principaux
                if ($action === 'update') {
                    // Gestion upload image (conserve l'existante si pas de nouveau fichier)
                    $char      = $cm->getCharacterByUUID($uuid);
                    $imagePath = $char['apparence_image'] ?? null;
                    if (!empty($_FILES['apparence_image']['tmp_name'])) {
                        $uploadDir = __DIR__ . '/img/characters/';
                        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                        $ext = strtolower(pathinfo($_FILES['apparence_image']['name'], PATHINFO_EXTENSION));
                        if (in_array($ext, ['jpg','jpeg','png','gif','webp'], true)) {
                            $fname = $uuid . '_' . time() . '.' . $ext;
                            if (move_uploaded_file($_FILES['apparence_image']['tmp_name'], $uploadDir . $fname)) {
                                $imagePath = './img/characters/' . $fname;
                            }
                        }
                    }
                    $result = $cm->updateCharacter($uuid, array_merge($_POST, ['apparence_image' => $imagePath]));
                    if ($result['success']) {
                        header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                        exit;
                    }
                    $formError = $result['message'];
                    $formData  = $_POST;
                    break;
                }

                // ── Titres ──
                if ($action === 'add_title') {
                    $result = $cm->addTitle($uuid, $_POST['title'] ?? '');
                    header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                    exit;
                }
                if ($action === 'set_displayed_title') {
                    $cm->setDisplayedTitle($uuid, (int)($_POST['title_id'] ?? 0));
                    header('Location: ' . $backModify . '&notice=' . rawurlencode('Titre affiché mis à jour.'));
                    exit;
                }
                if ($action === 'delete_title') {
                    $cm->deleteTitle((int)($_POST['title_id'] ?? 0), $uuid);
                    header('Location: ' . $backModify . '&notice=' . rawurlencode('Titre supprimé.'));
                    exit;
                }

                // ── Attributs ──
                if ($action === 'add_attribute') {
                    $result = $cm->addAttribute($uuid, $_POST['entity_uuid'] ?? '');
                    header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                    exit;
                }
                if ($action === 'toggle_attribute_lock') {
                    $cm->toggleAttributeLock((int)($_POST['relation_id'] ?? 0), $uuid);
                    header('Location: ' . $backModify);
                    exit;
                }
                if ($action === 'remove_attribute') {
                    $cm->removeAttribute((int)($_POST['relation_id'] ?? 0), $uuid);
                    header('Location: ' . $backModify . '&notice=' . rawurlencode('Attribut retiré.'));
                    exit;
                }

                // ── Compétences ──
                if ($action === 'add_skill') {
                    $result = $cm->addSkill($uuid, $_POST['entity_uuid'] ?? '');
                    header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                    exit;
                }
                if ($action === 'toggle_skill_lock') {
                    $cm->toggleSkillLock((int)($_POST['relation_id'] ?? 0), $uuid);
                    header('Location: ' . $backModify);
                    exit;
                }
                if ($action === 'remove_skill') {
                    $cm->removeSkill((int)($_POST['relation_id'] ?? 0), $uuid);
                    header('Location: ' . $backModify . '&notice=' . rawurlencode('Compétence retirée.'));
                    exit;
                }

                // ── Stigmates ──
                if ($action === 'add_stigma') {
                    $result = $cm->addStigma($uuid, $_POST['entity_uuid'] ?? '');
                    header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                    exit;
                }
                if ($action === 'toggle_stigma_lock') {
                    $cm->toggleStigmaLock((int)($_POST['relation_id'] ?? 0), $uuid);
                    header('Location: ' . $backModify);
                    exit;
                }
                if ($action === 'remove_stigma') {
                    $cm->removeStigma((int)($_POST['relation_id'] ?? 0), $uuid);
                    header('Location: ' . $backModify . '&notice=' . rawurlencode('Stigmate retiré.'));
                    exit;
                }

                // ── Inventaire ──
                if ($action === 'add_inventory') {
                    $result = $cm->addInventoryItem($uuid, $_POST['item_uuid'] ?? '', (int)($_POST['quantity'] ?? 1));
                    header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                    exit;
                }
                if ($action === 'update_inventory_qty') {
                    $cm->updateInventoryQuantity((int)($_POST['relation_id'] ?? 0), $uuid, (int)($_POST['quantity'] ?? 1));
                    header('Location: ' . $backModify . '&notice=' . rawurlencode('Quantité mise à jour.'));
                    exit;
                }
                if ($action === 'remove_inventory') {
                    $cm->removeInventoryItem((int)($_POST['relation_id'] ?? 0), $uuid);
                    header('Location: ' . $backModify . '&notice=' . rawurlencode('Objet retiré de l\'inventaire.'));
                    exit;
                }

                // ── Équipement ──
                if ($action === 'add_equipped') {
                    $result = $cm->addEquipped($uuid, $_POST['item_uuid'] ?? '', $_POST['slot'] ?? '');
                    header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                    exit;
                }
                if ($action === 'remove_equipped') {
                    $cm->removeEquipped((int)($_POST['relation_id'] ?? 0), $uuid);
                    header('Location: ' . $backModify . '&notice=' . rawurlencode('Élément déséquipé.'));
                    exit;
                }
                break;

            case 'delete':
                $cm->deleteCharacter($uuid);
                header('Location: jdr-params?page=characters&sub-page=list&notice=' . rawurlencode('Personnage supprimé.'));
                exit;
        }
    }

?>
    <?php $bases->header(); ?>
    <body>
        <?php $bases->navigation(); ?>
        <section class="container container-menus">
            <section class="left-menu">
                <a href="jdr-params?page=users&sub-page=list" class="left-menu_page <?php if($currentPage == 'users') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des utilisateurs</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-user"></i></div>
                </a>
                <a href="jdr-params?page=characters&sub-page=list" class="left-menu_page <?php if($currentPage == 'characters') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des personnages</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-user-injured"></i></div>
                </a>
                <a href="jdr-params?page=monsters&sub-page=list" class="left-menu_page <?php if($currentPage == 'monsters') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des monstres</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-dragon"></i></div>
                </a>
                <a href="jdr-params?page=constellations&sub-page=list" class="left-menu_page <?php if($currentPage == 'constellations') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des Constellations</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-user-astronaut"></i></div>
                </a>
                <a href="jdr-params?page=capacities&sub-page=list" class="left-menu_page <?php if($currentPage == 'capacities') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des compétences</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-newspaper"></i></div>
                </a>
                <a href="jdr-params?page=stigmata&sub-page=list" class="left-menu_page <?php if($currentPage == 'stigmata') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des stigmates</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-copy"></i></div>
                </a>
                <a href="jdr-params?page=attributes&sub-page=list" class="left-menu_page <?php if($currentPage == 'attributes') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des attributs</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-address-card"></i></div>
                </a>
                <a href="jdr-params?page=histories&sub-page=list" class="left-menu_page <?php if($currentPage == 'histories') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des histoires</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-scroll"></i></div>
                </a>
                <a href="jdr-params?page=modifiers&sub-page=list" class="left-menu_page <?php if($currentPage == 'modifiers') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des modifieurs</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-id-card"></i></div>
                </a>
                <a href="jdr-params?page=scenarios&sub-page=list" class="left-menu_page <?php if($currentPage == 'scenarios') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des scénarios</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-sign-hanging"></i></div>
                </a>
                <a href="jdr-params?page=items&sub-page=list" class="left-menu_page <?php if($currentPage == 'items') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des objets</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-flask"></i></div>
                </a>
                <a href="jdr-params?page=dokkaebi_bag&sub-page=list" class="left-menu_page <?php if($currentPage == 'dokkaebi_bag') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion du baluchon du Dokkaebi</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-bag-shopping"></i></div>
                </a>
                <a href="jdr-params?page=generals&sub-page=list" class="left-menu_page <?php if($currentPage == 'generals') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Paramètres généraux</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-gear"></i></div>
                </a>
            </section>
            <section class="right-menu">
                <div class="menu-right-container">
                    <?php require_once "./sub-pages/" . $currentPage . "." . $currentSubPage . ".php"; ?>
                </div>
                <?php $bases->footer(); ?>
            </section>
        </section>
    </body>
</html>
