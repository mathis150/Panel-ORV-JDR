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

    // ── Compétences ───────────────────────────────────────────────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $currentPage === 'capacities') {
        require_once './import/capacities.php';
        $capm = new CapacitiesManager();
        $uuid = $_GET['uuid'] ?? '';
        $tab  = $_GET['tab']  ?? 'cap-info';
        $backModify = 'jdr-params?page=capacities&sub-page=modify&uuid=' . rawurlencode($uuid) . '&tab=' . rawurlencode($tab);

        switch ($currentSubPage) {

            case 'create':
                $result = $capm->createCapacity($_POST);
                if ($result['success']) {
                    header('Location: jdr-params?page=capacities&sub-page=modify&uuid=' . rawurlencode($result['uuid']) . '&tab=cap-info&notice=' . rawurlencode($result['message']));
                    exit;
                }
                $formError = $result['message'];
                $formData  = $_POST;
                break;

            case 'modify':
                $action = $_POST['action'] ?? 'update';

                if ($action === 'update') {
                    $result = $capm->updateCapacity($uuid, $_POST);
                    if ($result['success']) {
                        header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                        exit;
                    }
                    $formError = $result['message'];
                    $formData  = $_POST;
                    break;
                }

                if ($action === 'add_levelup_condition') {
                    $valeur = isset($_POST['condition_valeur']) && $_POST['condition_valeur'] !== '' ? (int)$_POST['condition_valeur'] : null;
                    $result = $capm->addLevelupCondition(
                        $uuid,
                        (int)($_POST['niveau_cible']          ?? 2),
                        $_POST['condition_type']               ?? 'usages',
                        $_POST['condition_description']        ?? '',
                        $valeur
                    );
                    header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                    exit;
                }
                if ($action === 'delete_levelup_condition') {
                    $capm->deleteLevelupCondition((int)($_POST['condition_id'] ?? 0), $uuid);
                    header('Location: ' . $backModify . '&notice=' . rawurlencode('Condition supprimée.'));
                    exit;
                }

                if ($action === 'add_effect') {
                    $duree  = isset($_POST['effect_duree']) && $_POST['effect_duree'] !== '' ? (int)$_POST['effect_duree'] : null;
                    $result = $capm->addEffect(
                        $uuid,
                        $_POST['effect_nom']         ?? '',
                        $_POST['effect_categorie']   ?? 'stat',
                        $_POST['effect_sous_type']   ?? 'force',
                        $_POST['effect_valeur']      ?? '',
                        $_POST['effect_type_duree']  ?? 'tours',
                        $duree,
                        $_POST['effect_description'] ?? ''
                    );
                    header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                    exit;
                }
                if ($action === 'delete_effect') {
                    $capm->deleteEffect((int)($_POST['effect_id'] ?? 0), $uuid);
                    header('Location: ' . $backModify . '&notice=' . rawurlencode('Effet supprimé.'));
                    exit;
                }
                break;

            case 'delete':
                $capm->deleteCapacity($uuid);
                header('Location: jdr-params?page=capacities&sub-page=list&notice=' . rawurlencode('Compétence supprimée.'));
                exit;
        }
    }

    // ── Stigmates ─────────────────────────────────────────────────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $currentPage === 'stigmata') {
        require_once './import/stigmates.php';
        $sm   = new StigmataManager();
        $uuid = $_GET['uuid'] ?? '';
        $tab  = $_GET['tab']  ?? 'stig-info';
        $backModify = 'jdr-params?page=stigmata&sub-page=modify&uuid=' . rawurlencode($uuid) . '&tab=' . rawurlencode($tab);

        switch ($currentSubPage) {

            case 'create':
                $result = $sm->createStigma($_POST);
                if ($result['success']) {
                    header('Location: jdr-params?page=stigmata&sub-page=modify&uuid=' . rawurlencode($result['uuid']) . '&tab=stig-info&notice=' . rawurlencode($result['message']));
                    exit;
                }
                $formError = $result['message'];
                $formData  = $_POST;
                break;

            case 'modify':
                $action = $_POST['action'] ?? 'update';

                if ($action === 'update') {
                    $result = $sm->updateStigma($uuid, $_POST);
                    if ($result['success']) {
                        header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                        exit;
                    }
                    $formError = $result['message'];
                    $formData  = $_POST;
                    break;
                }

                if ($action === 'add_effect') {
                    $duree  = isset($_POST['effect_duree']) && $_POST['effect_duree'] !== '' ? (int)$_POST['effect_duree'] : null;
                    $result = $sm->addEffect(
                        $uuid,
                        $_POST['effect_nom']         ?? '',
                        $_POST['effect_categorie']   ?? 'stat',
                        $_POST['effect_sous_type']   ?? 'force',
                        $_POST['effect_valeur']      ?? '',
                        $_POST['effect_type_duree']  ?? 'tours',
                        $duree,
                        $_POST['effect_description'] ?? ''
                    );
                    header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                    exit;
                }
                if ($action === 'delete_effect') {
                    $sm->deleteEffect((int)($_POST['effect_id'] ?? 0), $uuid);
                    header('Location: ' . $backModify . '&notice=' . rawurlencode('Effet supprimé.'));
                    exit;
                }

                if ($action === 'add_levelup_condition') {
                    $valeur = isset($_POST['condition_valeur']) && $_POST['condition_valeur'] !== '' ? (int)$_POST['condition_valeur'] : null;
                    $result = $sm->addLevelupCondition(
                        $uuid,
                        (int)($_POST['niveau_cible']       ?? 2),
                        $_POST['condition_type']            ?? 'usages',
                        $_POST['condition_description']     ?? '',
                        $valeur
                    );
                    header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                    exit;
                }
                if ($action === 'delete_levelup_condition') {
                    $sm->deleteLevelupCondition((int)($_POST['condition_id'] ?? 0), $uuid);
                    header('Location: ' . $backModify . '&notice=' . rawurlencode('Condition supprimée.'));
                    exit;
                }

                break;

            case 'delete':
                $sm->deleteStigma($uuid);
                header('Location: jdr-params?page=stigmata&sub-page=list&notice=' . rawurlencode('Stigmate supprimé.'));
                exit;
        }
    }

    // ── Histoires ─────────────────────────────────────────────────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $currentPage === 'histories') {
        require_once './import/histories.php';
        $hm   = new HistoriesManager();
        $uuid = $_GET['uuid'] ?? '';
        $tab  = $_GET['tab']  ?? 'hist-info';
        $backModify = 'jdr-params?page=histories&sub-page=modify&uuid=' . rawurlencode($uuid) . '&tab=' . rawurlencode($tab);

        switch ($currentSubPage) {

            case 'create':
                $result = $hm->createHistory($_POST);
                if ($result['success']) {
                    header('Location: jdr-params?page=histories&sub-page=modify&uuid=' . rawurlencode($result['uuid']) . '&tab=hist-info&notice=' . rawurlencode($result['message']));
                    exit;
                }
                $formError = $result['message'];
                $formData  = $_POST;
                break;

            case 'modify':
                $action = $_POST['action'] ?? 'update';

                if ($action === 'update') {
                    $result = $hm->updateHistory($uuid, $_POST);
                    if ($result['success']) {
                        header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                        exit;
                    }
                    $formError = $result['message'];
                    $formData  = $_POST;
                    break;
                }

                if ($action === 'add_level_stat') {
                    $result = $hm->addLevelStat(
                        $uuid,
                        (int)($_POST['niveau']      ?? 1),
                        $_POST['stat_type']          ?? 'force',
                        (int)($_POST['stat_valeur']  ?? 0)
                    );
                    header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                    exit;
                }
                if ($action === 'remove_level_stat') {
                    $hm->removeLevelStat((int)($_POST['stat_id'] ?? 0), $uuid);
                    header('Location: ' . $backModify . '&notice=' . rawurlencode('Statistique supprimée.'));
                    exit;
                }

                if ($action === 'add_level_stigma') {
                    $stigmaUuid = $_POST['stigma_uuid'] ?? '';
                    if (empty($stigmaUuid)) {
                        header('Location: ' . $backModify . '&notice=' . rawurlencode('Aucun stigmate sélectionné.'));
                        exit;
                    }
                    $result = $hm->addLevelStigma($uuid, (int)($_POST['niveau'] ?? 1), $stigmaUuid);
                    header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                    exit;
                }
                if ($action === 'remove_level_stigma') {
                    $hm->removeLevelStigma((int)($_POST['relation_id'] ?? 0), $uuid);
                    header('Location: ' . $backModify . '&notice=' . rawurlencode('Stigmate retiré.'));
                    exit;
                }
                break;

            case 'delete':
                $hm->deleteHistory($uuid);
                header('Location: jdr-params?page=histories&sub-page=list&notice=' . rawurlencode('Histoire supprimée.'));
                exit;
        }
    }

    // ── Attributs ─────────────────────────────────────────────────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $currentPage === 'attributes') {
        require_once './import/attributs.php';
        $am   = new AttributesManager();
        $uuid = $_GET['uuid'] ?? '';
        $tab  = $_GET['tab']  ?? 'attr-info';
        $backModify = 'jdr-params?page=attributes&sub-page=modify&uuid=' . rawurlencode($uuid) . '&tab=' . rawurlencode($tab);

        switch ($currentSubPage) {

            case 'create':
                $result = $am->createAttribute($_POST);
                if ($result['success']) {
                    header('Location: jdr-params?page=attributes&sub-page=modify&uuid=' . rawurlencode($result['uuid']) . '&tab=attr-info&notice=' . rawurlencode($result['message']));
                    exit;
                }
                $formError = $result['message'];
                $formData  = $_POST;
                break;

            case 'modify':
                $action = $_POST['action'] ?? 'update';

                if ($action === 'update') {
                    $result = $am->updateAttribute($uuid, $_POST);
                    if ($result['success']) {
                        header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                        exit;
                    }
                    $formError = $result['message'];
                    $formData  = $_POST;
                    break;
                }

                if ($action === 'add_effect') {
                    $duree  = isset($_POST['effect_duree']) && $_POST['effect_duree'] !== '' ? (int)$_POST['effect_duree'] : null;
                    $result = $am->addEffect(
                        $uuid,
                        $_POST['effect_nom']         ?? '',
                        $_POST['effect_categorie']   ?? 'stat',
                        $_POST['effect_sous_type']   ?? 'force',
                        $_POST['effect_valeur']      ?? '',
                        $_POST['effect_type_duree']  ?? 'passif',
                        $duree,
                        $_POST['effect_description'] ?? ''
                    );
                    header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                    exit;
                }
                if ($action === 'delete_effect') {
                    $am->deleteEffect((int)($_POST['effect_id'] ?? 0), $uuid);
                    header('Location: ' . $backModify . '&notice=' . rawurlencode('Effet supprimé.'));
                    exit;
                }
                break;

            case 'delete':
                $am->deleteAttribute($uuid);
                header('Location: jdr-params?page=attributes&sub-page=list&notice=' . rawurlencode('Attribut supprimé.'));
                exit;
        }
    }

    // ── Constellations ────────────────────────────────────────────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $currentPage === 'constellations') {
        require_once './import/constellations.php';
        $cm   = new ConstellationsManager();
        $uuid = $_GET['uuid'] ?? '';
        $tab  = $_GET['tab']  ?? 'cc-identity';
        $backModify = 'jdr-params?page=constellations&sub-page=modify&uuid=' . rawurlencode($uuid) . '&tab=' . rawurlencode($tab);

        switch ($currentSubPage) {

            case 'create':
                $result = $cm->createConstellation($_POST);
                if ($result['success']) {
                    header('Location: jdr-params?page=constellations&sub-page=modify&uuid=' . rawurlencode($result['uuid']) . '&tab=cc-identity&notice=' . rawurlencode($result['message']));
                    exit;
                }
                $formError = $result['message'];
                $formData  = $_POST;
                break;

            case 'modify':
                $action = $_POST['action'] ?? 'update';

                if ($action === 'update') {
                    $result = $cm->updateConstellation($uuid, $_POST);
                    if ($result['success']) {
                        header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                        exit;
                    }
                    $formError = $result['message'];
                    $formData  = $_POST;
                    break;
                }

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
                break;

            case 'delete':
                $cm->deleteConstellation($uuid);
                header('Location: jdr-params?page=constellations&sub-page=list&notice=' . rawurlencode('Constellation supprimée.'));
                exit;
        }
    }

    // ── Monstres ──────────────────────────────────────────────────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $currentPage === 'monsters') {
        require_once './import/monsters.php';
        $mm   = new MonstersManager();
        $uuid = $_GET['uuid'] ?? '';
        $tab  = $_GET['tab']  ?? 'mm-identity';
        $backModify = 'jdr-params?page=monsters&sub-page=modify&uuid=' . rawurlencode($uuid) . '&tab=' . rawurlencode($tab);

        switch ($currentSubPage) {

            case 'create':
                $result = $mm->createMonster($_POST);
                if ($result['success']) {
                    header('Location: jdr-params?page=monsters&sub-page=modify&uuid=' . rawurlencode($result['uuid']) . '&tab=mm-identity&notice=' . rawurlencode($result['message']));
                    exit;
                }
                $formError = $result['message'];
                $formData  = $_POST;
                break;

            case 'modify':
                $action = $_POST['action'] ?? 'update';

                if ($action === 'update') {
                    $result = $mm->updateMonster($uuid, $_POST);
                    if ($result['success']) {
                        header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                        exit;
                    }
                    $formError = $result['message'];
                    $formData  = $_POST;
                    break;
                }

                if ($action === 'add_equipped') {
                    $result = $mm->addEquipped($uuid, $_POST['item_uuid'] ?? '', $_POST['slot'] ?? '', (int)($_POST['quantity'] ?? 1));
                    header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                    exit;
                }
                if ($action === 'update_equipped_qty') {
                    $mm->updateEquippedQuantity((int)($_POST['relation_id'] ?? 0), $uuid, (int)($_POST['quantity'] ?? 1));
                    header('Location: ' . $backModify . '&notice=' . rawurlencode('Quantité mise à jour.'));
                    exit;
                }
                if ($action === 'remove_equipped') {
                    $mm->removeEquipped((int)($_POST['relation_id'] ?? 0), $uuid);
                    header('Location: ' . $backModify . '&notice=' . rawurlencode('Élément déséquipé.'));
                    exit;
                }
                break;

            case 'delete':
                $mm->deleteMonster($uuid);
                header('Location: jdr-params?page=monsters&sub-page=list&notice=' . rawurlencode('Monstre supprimé.'));
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
