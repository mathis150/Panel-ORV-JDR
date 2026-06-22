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

    // ── Modificateurs ─────────────────────────────────────────────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $currentPage === 'modifiers') {
        require_once './import/modifiers.php';
        $mm   = new ModifiersManager();
        $uuid = $_GET['uuid'] ?? '';
        $tab  = $_GET['tab']  ?? 'mod-info';
        $backModify = 'jdr-params?page=modifiers&sub-page=modify&uuid=' . rawurlencode($uuid) . '&tab=' . rawurlencode($tab);

        switch ($currentSubPage) {

            case 'create':
                $result = $mm->createModifier($_POST);
                if ($result['success']) {
                    header('Location: jdr-params?page=modifiers&sub-page=modify&uuid=' . rawurlencode($result['uuid']) . '&tab=mod-info&notice=' . rawurlencode($result['message']));
                    exit;
                }
                $formError = $result['message'];
                $formData  = $_POST;
                break;

            case 'modify':
                $action = $_POST['action'] ?? 'update';

                if ($action === 'update') {
                    $result = $mm->updateModifier($uuid, $_POST);
                    if ($result['success']) {
                        header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                        exit;
                    }
                    $formError = $result['message'];
                    $formData  = $_POST;
                    break;
                }

                if ($action === 'add_stat') {
                    $result = $mm->addModifierStat(
                        $uuid,
                        $_POST['stat_type']   ?? 'force',
                        (int)($_POST['stat_valeur'] ?? 0)
                    );
                    header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                    exit;
                }
                if ($action === 'remove_stat') {
                    $mm->removeModifierStat((int)($_POST['stat_id'] ?? 0), $uuid);
                    header('Location: ' . $backModify . '&notice=' . rawurlencode('Statistique supprimée.'));
                    exit;
                }

                if ($action === 'add_effect') {
                    $result = $mm->addModifierEffect(
                        $uuid,
                        $_POST['effect_nom']         ?? '',
                        $_POST['effect_description'] ?? ''
                    );
                    header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                    exit;
                }
                if ($action === 'remove_effect') {
                    $mm->removeModifierEffect((int)($_POST['effect_id'] ?? 0), $uuid);
                    header('Location: ' . $backModify . '&notice=' . rawurlencode('Effet supprimé.'));
                    exit;
                }
                break;

            case 'delete':
                $mm->deleteModifier($uuid);
                header('Location: jdr-params?page=modifiers&sub-page=list&notice=' . rawurlencode('Modificateur supprimé.'));
                exit;
        }
    }

    // ── Scénarios ─────────────────────────────────────────────────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $currentPage === 'scenarios') {
        require_once './import/scenarios.php';
        $sm   = new ScenariosManager();
        $uuid = $_GET['uuid'] ?? '';
        $tab  = $_GET['tab']  ?? 'sc-info';
        $backModify = 'jdr-params?page=scenarios&sub-page=modify&uuid=' . rawurlencode($uuid) . '&tab=' . rawurlencode($tab);

        switch ($currentSubPage) {

            case 'create':
                $result = $sm->createScenario($_POST);
                if ($result['success']) {
                    header('Location: jdr-params?page=scenarios&sub-page=modify&uuid=' . rawurlencode($result['uuid']) . '&tab=sc-info&notice=' . rawurlencode($result['message']));
                    exit;
                }
                $formError = $result['message'];
                $formData  = $_POST;
                break;

            case 'modify':
                $action = $_POST['action'] ?? 'update';

                if ($action === 'update') {
                    $result = $sm->updateScenario($uuid, $_POST);
                    if ($result['success']) {
                        header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                        exit;
                    }
                    $formError = $result['message'];
                    $formData  = $_POST;
                    break;
                }

                if ($action === 'add_reward') {
                    $qty = is_numeric($_POST['reward_quantite'] ?? '') && (int)$_POST['reward_quantite'] > 0
                           ? (int)$_POST['reward_quantite'] : null;
                    $result = $sm->addScenarioReward(
                        $uuid,
                        $_POST['reward_type']    ?? 'autre',
                        $_POST['reward_nom']     ?? '',
                        $qty
                    );
                    header('Location: ' . $backModify . '&notice=' . rawurlencode($result['message']));
                    exit;
                }
                if ($action === 'remove_reward') {
                    $sm->removeScenarioReward((int)($_POST['reward_id'] ?? 0), $uuid);
                    header('Location: ' . $backModify . '&notice=' . rawurlencode('Récompense supprimée.'));
                    exit;
                }
                break;

            case 'delete':
                $sm->deleteScenario($uuid);
                header('Location: jdr-params?page=scenarios&sub-page=list&notice=' . rawurlencode('Scénario supprimé.'));
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
                    $result = $cm->updateCharacter($uuid, array_merge($char, $_POST, ['apparence_image' => $imagePath]));
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

                // ── Baluchon du Dokkaebi ──
                if ($action === 'toggle_dokkaebi_bag') {
                    $current = $cm->getCharacterByUUID($uuid);
                    if ($current) {
                        $cm->setDokkaebiBag($uuid, !(bool)$current['has_dokkaebi_bag']);
                        $msg = $current['has_dokkaebi_bag'] ? 'Baluchon désactivé.' : 'Baluchon activé.';
                    } else {
                        $msg = 'Personnage introuvable.';
                    }
                    header('Location: ' . $backModify . '&notice=' . rawurlencode($msg));
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

    // ── Objets ────────────────────────────────────────────────────────────────────
    if ($currentPage === 'items') {
        require_once './import/items.php';
        $im   = new ItemsManager();
        $uuid = $_GET['uuid'] ?? '';
        $tab  = $_GET['tab']  ?? 'item-info';
        $backModify = 'jdr-params?page=items&sub-page=modify&uuid=' . rawurlencode($uuid) . '&tab=' . rawurlencode($tab);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $currentSubPage === 'list') {
            if (($_POST['action'] ?? '') === 'toggle_active') {
                $tUuid = $_POST['uuid'] ?? '';
                $t = $im->getItemByUUID($tUuid);
                if ($t) {
                    $im->updateItem($tUuid, array_merge((array)$t, ['is_active' => $t['is_active'] ? 0 : 1]));
                }
                $srch = trim($_POST['search'] ?? '');
                header('Location: jdr-params?page=items&sub-page=list' . ($srch !== '' ? '&search=' . rawurlencode($srch) : ''));
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($currentSubPage) {
                case 'create':
                    $result = $im->createItem($_POST);
                    if ($result['success']) {
                        header('Location: jdr-params?page=items&sub-page=modify&uuid=' . rawurlencode($result['uuid']) . '&tab=item-info&notice=' . rawurlencode($result['message']));
                        exit;
                    }
                    $formError = $result['message'];
                    $formData  = $_POST;
                    break;

                case 'modify':
                    $action = $_POST['action'] ?? '';
                    if ($action === 'update') {
                        $im->updateItem($uuid, $_POST);
                        header('Location: ' . $backModify . '&notice=' . rawurlencode('Objet mis à jour.'));
                        exit;
                    }
                    if ($action === 'add_stat') {
                        $statType = $_POST['stat_type'] ?? '';
                        $valeur   = (int)($_POST['valeur'] ?? 0);
                        if (in_array($statType, ItemsManager::STAT_TYPES) && $valeur > 0) {
                            $im->addItemStat($uuid, $statType, $valeur);
                        }
                        header('Location: ' . $backModify . '&notice=' . rawurlencode('Statistique ajoutée.'));
                        exit;
                    }
                    if ($action === 'remove_stat') {
                        $im->removeItemStat((int)($_POST['stat_id'] ?? 0));
                        header('Location: ' . $backModify . '&notice=' . rawurlencode('Statistique retirée.'));
                        exit;
                    }
                    break;

                case 'delete':
                    $nom = $im->getItemByUUID($uuid)['nom'] ?? 'Objet';
                    $im->deleteItem($uuid);
                    header('Location: jdr-params?page=items&sub-page=list&notice=' . rawurlencode('« ' . $nom . ' » supprimé.'));
                    exit;
            }
        }
    }

    // ── Dokkaebi Shop ─────────────────────────────────────────────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $currentPage === 'dokkaebi_bag') {
        require_once './import/dokkaebi_shop.php';
        $dsm    = new DokkaebiShopManager();
        $id     = (int)($_GET['id'] ?? 0);
        $search = trim($_POST['search'] ?? '');
        $backList = 'jdr-params?page=dokkaebi_bag&sub-page=list' . ($search !== '' ? '&search=' . rawurlencode($search) : '');

        switch ($currentSubPage) {

            case 'create':
                $result = $dsm->addToShop($_POST['item_uuid'] ?? '', $_POST);
                if ($result['success']) {
                    header('Location: jdr-params?page=dokkaebi_bag&sub-page=list&notice=' . rawurlencode($result['message']));
                    exit;
                }
                $formError = $result['message'];
                $formData  = $_POST;
                break;

            case 'list':
                $action = $_POST['action'] ?? '';
                $lid    = (int)($_POST['id'] ?? 0);
                if ($action === 'toggle_vedette') {
                    $dsm->toggleVedette($lid);
                    header('Location: ' . $backList);
                    exit;
                }
                if ($action === 'toggle_actif') {
                    $dsm->toggleActif($lid);
                    header('Location: ' . $backList);
                    exit;
                }
                break;

            case 'modify':
                $result = $dsm->updateShopListing($id, $_POST);
                header('Location: jdr-params?page=dokkaebi_bag&sub-page=modify&id=' . $id . '&notice=' . rawurlencode($result['message']));
                exit;

            case 'delete':
                $dsm->removeFromShop($id);
                header('Location: jdr-params?page=dokkaebi_bag&sub-page=list&notice=' . rawurlencode('Objet retiré du shop.'));
                exit;
        }
    }

    // ── Paramètres généraux ───────────────────────────────────────────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $currentPage === 'generals') {
        require_once './import/generals.php';
        $gm = new GeneralsManager();

        $gm->setMany([
            'app_name'          => trim($_POST['app_name']       ?? ''),
            'app_subtitle'      => trim($_POST['app_subtitle']   ?? ''),
            'app_status'        => in_array($_POST['app_status'] ?? '', ['active','maintenance','pause'])
                                       ? $_POST['app_status'] : 'active',
            'app_status_msg'    => trim($_POST['app_status_msg'] ?? ''),

            'announce_active'   => isset($_POST['announce_active'])  ? '1' : '0',
            'announce_type'     => array_key_exists($_POST['announce_type'] ?? '', GeneralsManager::ANNOUNCE_TYPES)
                                       ? $_POST['announce_type'] : 'info',
            'announce_text'     => trim($_POST['announce_text']  ?? ''),

            'world_arc'         => trim($_POST['world_arc']      ?? ''),
            'world_season'      => trim($_POST['world_season']   ?? ''),
            'world_session'     => (string)max(1, (int)($_POST['world_session'] ?? 1)),
            'world_date'        => trim($_POST['world_date']     ?? ''),

            'eco_currency'      => trim($_POST['eco_currency']   ?? '') ?: 'Coins',
            'eco_symbol'        => trim($_POST['eco_symbol']     ?? '') ?: 'C',
            'eco_start_coins'   => (string)max(0, (int)($_POST['eco_start_coins'] ?? 0)),

            'rules_max_players' => (string)max(1, (int)($_POST['rules_max_players'] ?? 6)),
            'rules_pvp'         => isset($_POST['rules_pvp']) ? '1' : '0',
            'rules_notes'       => trim($_POST['rules_notes']    ?? ''),
        ]);

        header('Location: jdr-params?page=generals&sub-page=list&notice=' . rawurlencode('Paramètres sauvegardés avec succès.'));
        exit;
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
