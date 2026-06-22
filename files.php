<?php
    require_once("./import/base.php");
    require_once("./import/characters.php");

    $bases = new WebPageBases();
    $user  = $bases->getCurrentUser();
    $cm    = new CharactersManager();

    $notice    = htmlspecialchars($_GET['notice'] ?? '');
    $charUuid  = $_GET['char'] ?? '';
    $validTabs = ['info-perso', 'inv-perso', 'gest-perso', 'baluch-dokka', 'edit-perso'];
    $activeTab = in_array($_GET['tab'] ?? '', $validTabs) ? $_GET['tab'] : 'info-perso';

    function hv($v): string { return htmlspecialchars((string)($v ?? '')); }

    // ── POST handlers (PRG) ──────────────────────────────────────────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action']    ?? '';
        $pChar  = $_POST['char_uuid'] ?? '';
        $pTab   = in_array($_POST['back_tab'] ?? '', $validTabs) ? $_POST['back_tab'] : 'info-perso';
        $back   = 'files?char=' . urlencode($pChar) . '&tab=' . urlencode($pTab);

        if ($action === 'create_character') {
            $result = $cm->createCharacter([
                'user_uuid' => $user['uuid'],
                'nom'       => trim($_POST['nom']    ?? ''),
                'prenom'    => trim($_POST['prenom'] ?? ''),
            ]);
            if ($result['success']) {
                header('Location: files?char=' . urlencode($result['uuid']) . '&tab=info-perso&notice=' . rawurlencode('Fiche créée !'));
            } else {
                header('Location: files?notice=' . rawurlencode($result['message']));
            }
            exit;
        }

        if ($action === 'adjust_hp_mp') {
            $result = $cm->adjustHpMp(
                $pChar, $user['uuid'],
                max(0, (int)($_POST['add_hp']    ?? 0)),
                max(0, (int)($_POST['remove_hp'] ?? 0)),
                max(0, (int)($_POST['add_mp']    ?? 0)),
                max(0, (int)($_POST['remove_mp'] ?? 0))
            );
            header('Location: ' . $back . '&notice=' . rawurlencode($result['message']));
            exit;
        }

        if ($action === 'upgrade_stats') {
            $result = $cm->upgradeStats($pChar, $user['uuid'], [
                'vitalite' => $_POST['up_vitalite'] ?? 0,
                'force'    => $_POST['up_force']    ?? 0,
                'agilite'  => $_POST['up_agilite']  ?? 0,
                'mana'     => $_POST['up_mana']     ?? 0,
            ]);
            header('Location: ' . $back . '&notice=' . rawurlencode($result['message']));
            exit;
        }

        if ($action === 'update_info') {
            $result = $cm->updatePlayerInfo($pChar, $user['uuid'], $_POST);
            header('Location: files?char=' . urlencode($pChar) . '&tab=edit-perso&notice=' . rawurlencode($result['message']));
            exit;
        }

        if ($action === 'buy_item') {
            $result = $cm->buyFromShop($pChar, $user['uuid'], (int)($_POST['listing_id'] ?? 0), max(1, (int)($_POST['qty'] ?? 1)));
            header('Location: files?char=' . urlencode($pChar) . '&tab=baluch-dokka&notice=' . rawurlencode($result['message']));
            exit;
        }

        if ($action === 'upgrade_shop_rank') {
            $result = $cm->upgradeShopRank($pChar, $user['uuid']);
            header('Location: files?char=' . urlencode($pChar) . '&tab=baluch-dokka&notice=' . rawurlencode($result['message']));
            exit;
        }
    }

    // ── Chargement des données ───────────────────────────────────────────────────
    $userChars = $cm->getCharactersForUser($user['uuid']);
    $char      = null;
    $relations = null;

    if ($charUuid && isset($userChars[$charUuid])) {
        $char      = $cm->getCharacterFull($charUuid);
        $relations = $cm->getCharacterRelations($charUuid);
    } elseif (!empty($userChars)) {
        $charUuid  = array_key_first($userChars);
        $char      = $cm->getCharacterFull($charUuid);
        $relations = $cm->getCharacterRelations($charUuid);
    }

    // Stats dérivées
    $vitLvl = $frcLvl = $agiLvl = $mnaLvl = 0;
    $hpMax = $mpMax = $hpAct = $mpAct = 0;
    $hpPct = $mpPct = 0;
    $deplacement = $sautElan = $sautSansElan = $sautHauteur = 0;
    $charCoins = 0;
    $statsJson = '{}';

    if ($char) {
        $vitLvl       = (int)$char['vitalite'];
        $frcLvl       = (int)$char['force'];
        $agiLvl       = (int)$char['agilite'];
        $mnaLvl       = (int)$char['mana'];
        $hpMax        = CharactersManager::calcHpMax($vitLvl);
        $mpMax        = CharactersManager::calcMpMax($mnaLvl);
        $hpAct        = min($hpMax, (int)($char['hp_actuel'] ?? $hpMax));
        $mpAct        = min($mpMax, (int)($char['mp_actuel'] ?? $mpMax));
        $deplacement  = CharactersManager::calcMovement($agiLvl);
        $sautElan     = CharactersManager::calcJumpRunning($frcLvl, $agiLvl);
        $sautSansElan = CharactersManager::calcJumpStanding($frcLvl, $agiLvl);
        $sautHauteur  = CharactersManager::calcJumpHeight($frcLvl, $agiLvl);
        $hpPct        = $hpMax > 0 ? round($hpAct / $hpMax * 100) : 0;
        $mpPct        = $mpMax > 0 ? round($mpAct / $mpMax * 100) : 0;
        $charCoins    = (int)$char['coins'];
        $statsJson    = json_encode([
            'vitalite' => $vitLvl, 'force' => $frcLvl,
            'agilite'  => $agiLvl, 'mana'  => $mnaLvl,
        ]);

        $equippedSlots = ['arme' => null, 'casque' => null, 'plastron' => null,
                          'gants' => null, 'jambieres' => null, 'bottes' => null, 'accessoire' => null];
        foreach ($relations['equipped'] as $e) {
            if (isset($equippedSlots[$e['slot'] ?? ''])) $equippedSlots[$e['slot']] = $e;
        }
    }

    // Shop (uniquement si baluchon activé)
    $shopListings = [];
    $shopVedettes = [];
    if ($char && !empty($char['has_dokkaebi_bag'])) {
        require_once './import/dokkaebi_shop.php';
        $shopListings = (new DokkaebiShopManager())->getShopListings();
        foreach ($shopListings as $sl) {
            if ($sl['is_vedette']) $shopVedettes[] = $sl;
        }
    }

    $slotDef = [
        'arme'       => ['img' => 'sword',      'label' => 'Arme'],
        'casque'     => ['img' => 'helmet',     'label' => 'Casque'],
        'plastron'   => ['img' => 'chestplate', 'label' => 'Plastron'],
        'gants'      => ['img' => 'gants',      'label' => 'Gants'],
        'jambieres'  => ['img' => 'legging',    'label' => 'Jambières'],
        'bottes'     => ['img' => 'boots',      'label' => 'Bottes'],
        'accessoire' => ['img' => 'star',       'label' => 'Accessoire'],
    ];
?>
<?php $bases->header(); ?>
<body>
    <?php $bases->navigation(); ?>
    <section class="container container-menus">

        <!-- ═══════════════════════ SIDEBAR GAUCHE ═══════════════════════ -->
        <section class="left-menu">
            <button class="left-menu_button"
                    onclick="document.getElementById('create-char-modal').style.display='flex'">
                <i class="fa-solid fa-plus"></i> Créer une nouvelle fiche
            </button>
            <?php if (empty($userChars)): ?>
            <div style="padding:20px 12px; color:rgba(137,206,255,0.4); font-size:12px; text-align:center;">
                <i class="fa-solid fa-scroll" style="font-size:24px; margin-bottom:10px; display:block;"></i>
                Aucune fiche.<br>Contactez votre MJ.
            </div>
            <?php else: ?>
            <?php foreach ($userChars as $c): ?>
            <?php
                $isActive  = (bool)$c['is_active'];
                $isCurrent = ($c['uuid'] === $charUuid);
                $cls = 'left-menu_page';
                if ($isCurrent) $cls .= ' left-menu_actif';
                if (!$isActive) $cls .= ' left-menu_dead';
            ?>
            <a href="files?char=<?php echo hv($c['uuid']); ?>" class="<?php echo $cls; ?>">
                <div class="left-menu_page_text">
                    <?php echo hv(trim($c['prenom'] . ' ' . $c['nom'])); ?>
                </div>
                <div class="left-menu_page_text">
                    <?php echo $c['titre_affiche'] ? hv($c['titre_affiche']) : ($isActive ? 'Actif' : 'Inactif'); ?>
                </div>
            </a>
            <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <!-- ═══════════════════════ CONTENU PRINCIPAL ════════════════════ -->
        <section class="right-menu">

            <?php if ($notice): ?>
            <div class="user-notice" style="margin:0 0 12px;">
                <i class="fa-solid fa-circle-check"></i> <?php echo $notice; ?>
            </div>
            <?php endif; ?>

            <?php if (!$char): ?>
            <!-- Aucun personnage -->
            <div class="orv-menu">
                <div class="orv-menu_container" style="text-align:center; padding:80px 20px; color:rgba(137,206,255,0.35);">
                    <i class="fa-solid fa-scroll" style="font-size:56px; margin-bottom:20px; display:block;"></i>
                    <div style="font-size:16px; margin-bottom:8px;">Aucune fiche de personnage disponible.</div>
                    <div style="font-size:12px;">Créez-en une ou contactez votre Maître du Jeu.</div>
                </div>
            </div>

            <?php else: ?>

            <!-- Popup compétence/attribut/stigmate -->
            <div class="data-popup hidden" id="skill-popup">
                <div class="data-popup_popup">
                    <div class="data-popup_popup-header">
                        <span id="popup-title">Information</span>
                        <button class="button-close data-popup_close-position"
                                onclick="document.getElementById('skill-popup').classList.add('hidden');">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="data-popup_popup-body_grid">
                        <div class="data-popup_popup-body_content">
                            <b>Nom :</b> <span id="popup-nom">—</span><br><br>
                            <b>Description :</b><br>
                            <span id="popup-desc" style="white-space:pre-wrap;">—</span>
                        </div>
                        <div class="data-popup_popup-body_content" id="popup-extra"></div>
                    </div>
                </div>
            </div>

            <div class="menu-right-container">

                <!-- Boutons de navigation -->
                <div class="orv-buttons">
                    <button class="orv-edit <?php echo $activeTab==='edit-perso' ? 'button-actif':''; ?>"
                            data-target="edit-perso" title="Modifier la fiche">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button class="<?php echo $activeTab==='info-perso' ? 'button-actif':''; ?>"
                            data-target="info-perso">
                        Votre fiche de personnage
                    </button>
                    <button class="<?php echo $activeTab==='inv-perso' ? 'button-actif':''; ?>"
                            data-target="inv-perso">
                        Inventaire du personnage
                    </button>
                    <button class="<?php echo $activeTab==='gest-perso' ? 'button-actif':''; ?>"
                            data-target="gest-perso">
                        Gestion du personnage
                    </button>
                    <button class="<?php echo $activeTab==='baluch-dokka' ? 'button-actif':''; ?>"
                            data-target="baluch-dokka">
                        Baluchon du Dokkaebi
                    </button>
                </div>

                <!-- ════════════ ONGLET : Fiche de personnage ════════════ -->
                <div class="orv-menu <?php echo $activeTab !== 'info-perso' ? 'hidden' : ''; ?>" id="info-perso">
                    <div class="orv-menu_header">
                        <i class="fa-solid fa-id-card" style="margin-right:8px; color:rgba(137,206,255,0.6);"></i>
                        <?php echo hv(trim($char['prenom'] . ' ' . $char['nom'])); ?>
                        <?php if ($char['titre_affiche']): ?>
                        <span style="font-size:12px; color:rgba(255,215,0,0.7); margin-left:8px;">
                            — <?php echo hv($char['titre_affiche']); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="orv-menu_double-grid">
                        <!-- Colonne gauche : infos générales -->
                        <div class="orv-menu_stats-general">
                            <?php if ($char['apparence_image']): ?>
                            <img src="<?php echo hv($char['apparence_image']); ?>" alt="Apparence"
                                 style="width:100%; max-height:220px; object-fit:cover; border-radius:8px; margin-bottom:14px; border:1px solid rgba(55,174,254,0.2);">
                            <?php endif; ?>
                            <b>Nom :</b> <?php echo hv($char['prenom'] . ' ' . $char['nom']); ?><br>
                            <?php if ($char['race']): ?><b>Race :</b> <?php echo hv($char['race']); ?><br><?php endif; ?>
                            <?php if ($char['nationalite']): ?><b>Nationalité :</b> <?php echo hv($char['nationalite']); ?><br><?php endif; ?>
                            <?php if ($char['metier']): ?><b>Métier :</b> <?php echo hv($char['metier']); ?><br><?php endif; ?>
                            <?php if ($char['constellation_nom']): ?>
                            <b>Constellation sponsor :</b> <?php echo hv($char['constellation_nom']); ?><br>
                            <?php endif; ?>
                            <?php if ($char['psyche']): ?>
                            <br><b>Psyché :</b><br><?php echo nl2br(hv($char['psyche'])); ?><br>
                            <?php endif; ?>
                            <?php if ($char['vertu']): ?><b>Vertu :</b> <?php echo hv($char['vertu']); ?><br><?php endif; ?>
                            <?php if ($char['vice']): ?><b>Vice :</b> <?php echo hv($char['vice']); ?><br><?php endif; ?>
                            <?php if ($char['apparence_description']): ?>
                            <br><b>Apparence :</b><br><?php echo nl2br(hv($char['apparence_description'])); ?><br>
                            <?php endif; ?>
                            <?php if ($char['histoire']): ?>
                            <br><b>Histoire :</b><br><?php echo nl2br(hv($char['histoire'])); ?><br>
                            <?php endif; ?>
                            <?php if ($char['estimation_generale']): ?>
                            <br><b>Estimation générale :</b><br><?php echo nl2br(hv($char['estimation_generale'])); ?>
                            <?php endif; ?>
                        </div>
                        <!-- Colonne droite : stats -->
                        <div class="orv-menu_stats-personnals">
                            <div class="orv-menu_stats-personnals_content">
                                <b>Coins en banque :</b> <?php echo number_format($charCoins, 0, ',', ' '); ?> C<br>
                                <br>
                                <b>Compétences générales :</b><br>
                                [Vitalité : Niveau <?php echo $vitLvl; ?>],<br>
                                [Force physique : Niveau <?php echo $frcLvl; ?>],<br>
                                [Agilité : Niveau <?php echo $agiLvl; ?>],<br>
                                [Pouvoir magique : Niveau <?php echo $mnaLvl; ?>]<br>
                                <br>
                                [HP : <?php echo $hpAct; ?>/<?php echo $hpMax; ?>],
                                [MP : <?php echo $mpAct; ?>/<?php echo $mpMax; ?>],
                                [Déplacement : <?php echo $deplacement; ?>m]<br>
                                <br>
                                <b>Distance de saut :</b>
                                [Avec élan : <?php echo $sautElan; ?>m],
                                [Sans élan : <?php echo $sautSansElan; ?>m],
                                [Hauteur : <?php echo $sautHauteur; ?>cm]
                            </div>

                            <!-- Attributs -->
                            <div class="orv-menu_stats_list" style="margin-top:14px;">
                                <div class="orv-menu_stats_header">Attributs personnels :</div>
                                <div class="orv-menu_stats_list_buttons">
                                    <?php if (!empty($relations['attributes'])): ?>
                                    <?php foreach ($relations['attributes'] as $a): ?>
                                    <div class="orv-menu_stats_info" style="cursor:pointer;"
                                         onclick="showPopup(<?php echo htmlspecialchars(json_encode($a['entity_nom']), ENT_QUOTES); ?>, '', '')">
                                        <?php echo hv($a['entity_nom']); ?>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <span style="color:rgba(137,206,255,0.35); font-size:12px; font-style:italic;">Aucun attribut</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Compétences -->
                            <div class="orv-menu_stats_list" style="margin-top:14px;">
                                <div class="orv-menu_stats_header">Compétences spéciales :</div>
                                <div class="orv-menu_stats_list_buttons">
                                    <?php if (!empty($relations['skills'])): ?>
                                    <?php foreach ($relations['skills'] as $s): ?>
                                    <div class="orv-menu_stats_info" style="cursor:pointer;"
                                         onclick="showPopup(<?php echo htmlspecialchars(json_encode($s['entity_nom']), ENT_QUOTES); ?>, '', '')">
                                        <?php echo hv($s['entity_nom']); ?>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <span style="color:rgba(137,206,255,0.35); font-size:12px; font-style:italic;">Aucune compétence</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Stigmates -->
                            <div class="orv-menu_stats_list" style="margin-top:14px;">
                                <div class="orv-menu_stats_header">Stigmates :</div>
                                <div class="orv-menu_stats_list_buttons">
                                    <?php if (!empty($relations['stigmata'])): ?>
                                    <?php foreach ($relations['stigmata'] as $st): ?>
                                    <div class="orv-menu_stats_info" style="cursor:pointer;"
                                         onclick="showPopup(<?php echo htmlspecialchars(json_encode($st['entity_nom']), ENT_QUOTES); ?>, '', '')">
                                        <?php echo hv($st['entity_nom']); ?>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <span style="color:rgba(137,206,255,0.35); font-size:12px; font-style:italic;">Aucun stigmate</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ════════════ ONGLET : Inventaire ════════════ -->
                <div class="orv-menu <?php echo $activeTab !== 'inv-perso' ? 'hidden' : ''; ?>" id="inv-perso">
                    <div class="orv-menu_header">
                        <i class="fa-solid fa-bag-shopping" style="margin-right:8px; color:rgba(137,206,255,0.6);"></i>
                        Inventaire du personnage
                    </div>
                    <div class="orv-menu_double-grid">
                        <!-- Éléments équipés -->
                        <div class="orv-menu_equipement">
                            <div class="orv-menu_equipement_title"><h2>Éléments équipés</h2></div>
                            <?php foreach ($slotDef as $slot => $def): ?>
                            <?php $eItem = ($equippedSlots ?? [])[$slot] ?? null; ?>
                            <div class="orv-menu_equipement-item"
                                 <?php if ($eItem): ?>
                                 style="cursor:pointer;"
                                 onclick="showItemPopup(<?php echo htmlspecialchars(json_encode([
                                     'nom'  => $eItem['entity_nom'],
                                     'type' => $eItem['entity_type'] ?? '',
                                     'rang' => $eItem['entity_rang'] ?? '',
                                     'desc' => $eItem['entity_desc'] ?? '',
                                     'slot' => $slot,
                                 ]), ENT_QUOTES); ?>)"
                                 <?php endif; ?>>
                                <div class="orv-menu_content-center">
                                    <img src="./img/icons/<?php echo $def['img']; ?>.svg" width="40px">
                                </div>
                                <div class="orv-menu_content-center">
                                    <?php if ($eItem): ?>
                                    <h3><?php echo hv($eItem['entity_nom']); ?></h3>
                                    <?php else: ?>
                                    <h3 style="color:rgba(137,206,255,0.3); font-style:italic;">
                                        <?php echo $def['label']; ?> — Vide
                                    </h3>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- Inventaire (poches) -->
                        <div class="orv-menu_stats-personnals">
                            <div class="orv-menu_equipement_title"><h2>Vos poches</h2></div>
                            <?php if (!empty($relations['inventory'])): ?>
                            <?php foreach ($relations['inventory'] as $inv): ?>
                            <div class="orv-menu_equipement-item" style="cursor:pointer;"
                                 onclick="showItemPopup(<?php echo htmlspecialchars(json_encode([
                                     'nom'  => $inv['entity_nom'],
                                     'type' => $inv['entity_type'] ?? '',
                                     'rang' => $inv['entity_rang'] ?? '',
                                     'desc' => $inv['entity_desc'] ?? '',
                                     'qty'  => (int)$inv['quantity'],
                                 ]), ENT_QUOTES); ?>)">
                                <div class="orv-menu_content-center">
                                    <i class="fa-solid fa-box-open" style="font-size:28px; color:rgba(137,206,255,0.5);"></i>
                                </div>
                                <div class="orv-menu_content-center">
                                    <h3><?php echo hv($inv['entity_nom']); ?>
                                        <?php if ((int)$inv['quantity'] > 1): ?>
                                        <span style="color:rgba(137,206,255,0.5); font-size:12px;">×<?php echo (int)$inv['quantity']; ?></span>
                                        <?php endif; ?>
                                    </h3>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <div style="padding:24px 16px; color:rgba(137,206,255,0.35); font-style:italic; font-size:13px;">
                                Inventaire vide.
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- ════════════ ONGLET : Gestion ════════════ -->
                <div class="orv-menu <?php echo $activeTab !== 'gest-perso' ? 'hidden' : ''; ?>" id="gest-perso">
                    <div class="orv-menu_header">
                        <i class="fa-solid fa-sliders" style="margin-right:8px; color:rgba(137,206,255,0.6);"></i>
                        Fenêtre de gestion du personnage
                    </div>
                    <div class="orv-menu_container" style="padding:0;">

                        <!-- Bandeau coins -->
                        <div style="display:flex; align-items:center; justify-content:flex-end; padding:16px 24px 0;">
                            <div style="display:flex; align-items:center; gap:8px; background:rgba(255,215,0,0.07); border:1px solid rgba(255,215,0,0.2); border-radius:20px; padding:6px 16px;">
                                <i class="fa-solid fa-coins" style="color:#FFD700; font-size:14px;"></i>
                                <span style="font-size:13px; font-weight:700; color:#FFD97E;"><?php echo number_format($charCoins, 0, ',', ' '); ?> C</span>
                                <span style="font-size:11px; color:rgba(137,206,255,0.4);">en banque</span>
                            </div>
                        </div>

                        <!-- ── Section PV / PM ── -->
                        <form method="POST" action="files">
                            <input type="hidden" name="action"    value="adjust_hp_mp">
                            <input type="hidden" name="char_uuid" value="<?php echo hv($charUuid); ?>">
                            <input type="hidden" name="back_tab"  value="gest-perso">

                            <div style="padding:20px 24px;">
                                <div style="font-size:13px; font-weight:700; color:rgba(137,206,255,0.55); letter-spacing:.08em; text-transform:uppercase; margin-bottom:14px;">
                                    <i class="fa-solid fa-heart-pulse" style="margin-right:6px;"></i>Gestion des PV / PM
                                </div>
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">

                                    <!-- Carte PV -->
                                    <div style="background:rgba(239,83,80,0.06); border:1px solid rgba(239,83,80,0.18); border-radius:14px; padding:18px;">
                                        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                                            <div style="display:flex; align-items:center; gap:8px;">
                                                <i class="fa-solid fa-heart" style="color:#EF5350; font-size:16px;"></i>
                                                <span style="font-weight:600; font-size:14px;">Points de Vie</span>
                                            </div>
                                            <span style="font-size:20px; font-weight:800; color:#EF5350; letter-spacing:-.5px;">
                                                <?php echo $hpAct; ?><span style="font-size:13px; color:rgba(217,255,255,0.4); font-weight:400;"> / <?php echo $hpMax; ?></span>
                                            </span>
                                        </div>
                                        <div style="height:14px; background:rgba(0,0,0,0.25); border-radius:7px; overflow:hidden; margin-bottom:16px;">
                                            <div style="height:100%; width:<?php echo $hpPct; ?>%; background:linear-gradient(90deg,#c62828,#ef5350); border-radius:7px; box-shadow:0 0 10px rgba(239,83,80,0.4); transition:width .4s;"></div>
                                        </div>
                                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                                            <div>
                                                <label style="font-size:11px; color:rgba(102,187,106,0.8); font-weight:600; display:block; margin-bottom:5px;">
                                                    <i class="fa-solid fa-plus"></i> Ajouter
                                                </label>
                                                <input type="number" name="add_hp" min="0" value="0"
                                                       style="width:100%; box-sizing:border-box;">
                                            </div>
                                            <div>
                                                <label style="font-size:11px; color:rgba(239,83,80,0.8); font-weight:600; display:block; margin-bottom:5px;">
                                                    <i class="fa-solid fa-minus"></i> Retirer
                                                </label>
                                                <input type="number" name="remove_hp" min="0" value="0"
                                                       style="width:100%; box-sizing:border-box;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Carte PM -->
                                    <div style="background:rgba(66,165,245,0.06); border:1px solid rgba(66,165,245,0.18); border-radius:14px; padding:18px;">
                                        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                                            <div style="display:flex; align-items:center; gap:8px;">
                                                <i class="fa-solid fa-droplet" style="color:#42A5F5; font-size:16px;"></i>
                                                <span style="font-weight:600; font-size:14px;">Points de Mana</span>
                                            </div>
                                            <span style="font-size:20px; font-weight:800; color:#42A5F5; letter-spacing:-.5px;">
                                                <?php echo $mpAct; ?><span style="font-size:13px; color:rgba(217,255,255,0.4); font-weight:400;"> / <?php echo $mpMax; ?></span>
                                            </span>
                                        </div>
                                        <div style="height:14px; background:rgba(0,0,0,0.25); border-radius:7px; overflow:hidden; margin-bottom:16px;">
                                            <div style="height:100%; width:<?php echo $mpPct; ?>%; background:linear-gradient(90deg,#1565c0,#42a5f5); border-radius:7px; box-shadow:0 0 10px rgba(66,165,245,0.4); transition:width .4s;"></div>
                                        </div>
                                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                                            <div>
                                                <label style="font-size:11px; color:rgba(102,187,106,0.8); font-weight:600; display:block; margin-bottom:5px;">
                                                    <i class="fa-solid fa-plus"></i> Ajouter
                                                </label>
                                                <input type="number" name="add_mp" min="0" value="0"
                                                       style="width:100%; box-sizing:border-box;">
                                            </div>
                                            <div>
                                                <label style="font-size:11px; color:rgba(66,165,245,0.8); font-weight:600; display:block; margin-bottom:5px;">
                                                    <i class="fa-solid fa-minus"></i> Retirer
                                                </label>
                                                <input type="number" name="remove_mp" min="0" value="0"
                                                       style="width:100%; box-sizing:border-box;">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div style="display:flex; justify-content:flex-end; margin-top:14px;">
                                    <button type="submit" class="orv-edit">
                                        <i class="fa-solid fa-check"></i>
                                        <span style="font-size:15px;">Valider</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div style="height:1px; background:rgba(55,174,254,0.1); margin:0 24px;"></div>

                        <!-- ── Section Upgrade stats ── -->
                        <form method="POST" action="files">
                            <input type="hidden" name="action"    value="upgrade_stats">
                            <input type="hidden" name="char_uuid" value="<?php echo hv($charUuid); ?>">
                            <input type="hidden" name="back_tab"  value="gest-perso">

                            <div style="padding:20px 24px 24px;">
                                <div style="font-size:13px; font-weight:700; color:rgba(137,206,255,0.55); letter-spacing:.08em; text-transform:uppercase; margin-bottom:14px;">
                                    <i class="fa-solid fa-arrow-trend-up" style="margin-right:6px;"></i>Augmenter les compétences
                                </div>
                                <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:16px;">

                                    <?php
                                    $statCards = [
                                        ['id'=>'up_vitalite','name'=>'up_vitalite','label'=>'Force vitale',  'icon'=>'fa-heart',            'color'=>'#EF5350','bg'=>'rgba(239,83,80,0.06)','border'=>'rgba(239,83,80,0.18)','lvl'=>$vitLvl],
                                        ['id'=>'up_force',   'name'=>'up_force',   'label'=>'Force physique','icon'=>'fa-hand-fist',         'color'=>'#EF9A9A','bg'=>'rgba(239,154,154,0.06)','border'=>'rgba(239,154,154,0.18)','lvl'=>$frcLvl],
                                        ['id'=>'up_agilite', 'name'=>'up_agilite', 'label'=>'Agilité',       'icon'=>'fa-bolt',              'color'=>'#66BB6A','bg'=>'rgba(102,187,106,0.06)','border'=>'rgba(102,187,106,0.18)','lvl'=>$agiLvl],
                                        ['id'=>'up_mana',    'name'=>'up_mana',    'label'=>'Force magique', 'icon'=>'fa-fire-flame-curved', 'color'=>'#AB47BC','bg'=>'rgba(171,71,188,0.06)','border'=>'rgba(171,71,188,0.18)','lvl'=>$mnaLvl],
                                    ];
                                    foreach ($statCards as $sc):
                                    ?>
                                    <div style="background:<?php echo $sc['bg']; ?>; border:1px solid <?php echo $sc['border']; ?>; border-radius:14px; padding:18px 14px; text-align:center;">
                                        <i class="fa-solid <?php echo $sc['icon']; ?>" style="color:<?php echo $sc['color']; ?>; font-size:26px; margin-bottom:10px; display:block;
                                               filter:drop-shadow(0 0 6px <?php echo $sc['color']; ?>55);"></i>
                                        <div style="font-size:12px; font-weight:700; color:rgba(217,255,255,0.85); margin-bottom:6px;"><?php echo $sc['label']; ?></div>
                                        <div style="display:inline-block; background:rgba(255,255,255,0.06); border-radius:20px; padding:2px 10px; font-size:11px; color:<?php echo $sc['color']; ?>; font-weight:700; margin-bottom:12px;">
                                            Niv. <?php echo $sc['lvl']; ?>
                                        </div>
                                        <div>
                                            <label style="font-size:10px; color:rgba(137,206,255,0.4); display:block; margin-bottom:5px;">Niveaux à ajouter</label>
                                            <input type="number" name="<?php echo $sc['name']; ?>" id="<?php echo $sc['id']; ?>"
                                                   min="0" value="0" style="width:100%; box-sizing:border-box; text-align:center;"
                                                   oninput="updateTotalCost()">
                                        </div>
                                    </div>
                                    <?php endforeach; ?>

                                </div>

                                <!-- Bandeau coût total -->
                                <div style="background:rgba(255,215,0,0.05); border:1px solid rgba(255,215,0,0.15); border-radius:12px; padding:14px 20px; display:flex; align-items:center; justify-content:space-between; gap:16px;">
                                    <div>
                                        <div style="font-size:10px; color:rgba(137,206,255,0.4); text-transform:uppercase; letter-spacing:.06em; margin-bottom:4px;">Coût total</div>
                                        <div style="font-size:26px; font-weight:800; color:#FFD97E; letter-spacing:-.5px;" id="total-cost-display">0 C</div>
                                        <div id="coins-warning" style="display:none; color:#EF5350; font-size:11px; margin-top:3px;">
                                            <i class="fa-solid fa-triangle-exclamation"></i> Coins insuffisants
                                        </div>
                                    </div>
                                    <button type="submit" class="orv-edit" style="flex-shrink:0;">
                                        <i class="fa-solid fa-arrow-up"></i>
                                        <span style="font-size:15px;">Améliorer</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

                <!-- ════════════ ONGLET : Baluchon du Dokkaebi ════════════ -->
                <div class="orv-menu <?php echo $activeTab !== 'baluch-dokka' ? 'hidden' : ''; ?>"
                     id="baluch-dokka"
                     data-rank="<?php echo htmlspecialchars($char['shop_rang'] ?? 'iron', ENT_QUOTES); ?>">

                <?php if (empty($char['has_dokkaebi_bag'])): ?>
                <!-- Accès non activé -->
                <div class="orv-menu_container" style="text-align:center; padding:70px 20px; color:rgba(137,206,255,0.35);">
                    <i class="fa-solid fa-lock" style="font-size:52px; margin-bottom:18px; display:block; color:rgba(55,174,254,0.2);"></i>
                    <div style="font-size:16px; margin-bottom:8px; color:rgba(137,206,255,0.55);">Baluchon du Dokkaebi verrouillé</div>
                    <div style="font-size:12px;">Votre Maître du Jeu peut vous donner accès à ce shop.</div>
                </div>

                <?php else:
                    $shopRank   = $char['shop_rang'] ?? 'iron';
                    $rankOrder  = ['iron'=>0,'gold'=>1,'platinium'=>2,'diamond'=>3,'constellation'=>4];
                    $rankLevel  = $rankOrder[$shopRank] ?? 0;
                    $rankColors = ['iron'=>'#9E9E9E','gold'=>'#FFD700','platinium'=>'00BCD4','diamond'=>'#E91E63','constellation'=>'#AA00FF'];
                    $rankLabels = ['iron'=>'Iron','gold'=>'Gold','platinium'=>'Platinium','diamond'=>'Diamond','constellation'=>'Constellation'];
                    $rankColor  = $rankColors[$shopRank] ?? '#9E9E9E';
                    $rankLabel  = $rankLabels[$shopRank] ?? 'Iron';
                    $charName   = trim($char['prenom'] . ' ' . $char['nom']);
                ?>

                    <!-- ── Sidebar ──────────────────────────────────────────────── -->
                    <div class="shop-sidebar" id="shop-sidebar">
                        <div class="shop-nav-label">Navigation</div>
                        <nav class="shop-nav">

                            <a href="#" class="shop-nav-item shop-nav-active" data-shop-page="accueil">
                                <i class="fa-solid fa-house"></i> Accueil
                            </a>

                            <?php
                            $navItems = [
                                ['page'=>'articles-vip',    'icon'=>'fa-crown',                    'label'=>'Articles VIP',              'req'=>3, 'reqLabel'=>'Diamond',      'reqColor'=>'#E91E63'],
                                ['page'=>'mes-vedettes',    'icon'=>'fa-star',                     'label'=>'Vos articles en vedette',   'req'=>1, 'reqLabel'=>'Gold',         'reqColor'=>'#FFD700'],
                                ['page'=>'offres-une',      'icon'=>'fa-fire',                     'label'=>'Les offres à la une',       'req'=>1, 'reqLabel'=>'Gold',         'reqColor'=>'#FFD700'],
                                ['page'=>'recherche-offre', 'icon'=>'fa-magnifying-glass-dollar',  'label'=>'Recherche d\'offre',        'req'=>2, 'reqLabel'=>'Platinium',    'reqColor'=>'#00BCD4'],
                                ['page'=>'dokkaebi-tv',     'icon'=>'fa-tv',                       'label'=>'Dokkaebi TV',               'req'=>4, 'reqLabel'=>'Constellation','reqColor'=>'#AA00FF'],
                                ['page'=>'mon-profil',      'icon'=>'fa-user',                     'label'=>'Votre profil',              'req'=>0, 'reqLabel'=>'',            'reqColor'=>''],
                                ['page'=>'parametres',      'icon'=>'fa-gear',                     'label'=>'Paramètres',                'req'=>0, 'reqLabel'=>'',            'reqColor'=>''],
                            ];
                            foreach ($navItems as $ni):
                                $locked = $rankLevel < $ni['req'];
                            ?>
                            <a href="#" class="shop-nav-item<?php echo $locked ? ' shop-nav-locked':''; ?>"
                               data-shop-page="<?php echo $ni['page']; ?>"
                               style="<?php echo $locked ? 'opacity:.5;':''; ?>">
                                <i class="fa-solid <?php echo $ni['icon']; ?>"></i>
                                <?php echo $ni['label']; ?>
                                <?php if ($locked): ?>
                                <span style="margin-left:auto; font-size:9px; font-weight:700; padding:2px 6px;
                                             border-radius:8px; letter-spacing:.04em;
                                             background:<?php echo $ni['reqColor']; ?>22;
                                             color:<?php echo $ni['reqColor']; ?>;
                                             border:1px solid <?php echo $ni['reqColor']; ?>55;">
                                    <i class="fa-solid fa-lock" style="font-size:8px;"></i>
                                    <?php echo $ni['reqLabel']; ?>
                                </span>
                                <?php endif; ?>
                            </a>
                            <?php endforeach; ?>

                        </nav>
                    </div>
                    <div class="shop-sidebar-overlay" id="shop-sidebar-overlay"></div>

                    <!-- ── Header ───────────────────────────────────────────────── -->
                    <div class="shop-header">
                        <button class="shop-hamburger" id="shop-hamburger" aria-label="Menu boutique">
                            <span></span><span></span><span></span>
                        </button>
                        <div class="shop-header-title" id="shop-page-title">Baluchon des Dokkaebi</div>
                        <div class="shop-header-user">
                            <div class="shop-header-user-info">
                                <div class="shop-header-user-name"><?php echo hv($charName); ?></div>
                                <div class="shop-header-user-rank" style="color:<?php echo $rankColor; ?>;">
                                    Rang <?php echo $rankLabel; ?>
                                </div>
                            </div>
                            <div class="shop-header-avatar">
                                <?php if ($char['apparence_image']): ?>
                                <img src="<?php echo hv($char['apparence_image']); ?>" alt=""
                                     style="width:100%; height:100%; object-fit:cover;">
                                <?php else: ?>
                                <i class="fa-solid fa-user"></i>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- ── Layout (toutes les pages) ────────────────────────────── -->
                    <div class="shop-layout">

                        <!-- ═══ PAGE : Accueil ═══ -->
                        <div class="shop-page" id="sp-accueil">

                            <div class="shop-profile-card">
                                <div class="shop-profile-avatar">
                                    <?php if ($char['apparence_image']): ?>
                                    <img src="<?php echo hv($char['apparence_image']); ?>" alt=""
                                         style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                                    <?php else: ?>
                                    <i class="fa-solid fa-user"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="shop-profile-info">
                                    <div class="shop-profile-name"><?php echo hv($charName); ?></div>
                                    <div class="shop-profile-rank" style="color:<?php echo $rankColor; ?>;">
                                        Rang <?php echo $rankLabel; ?>
                                    </div>
                                </div>
                                <div class="shop-profile-stats">
                                    <div class="shop-profile-stat">
                                        <span class="shop-stat-value"><?php echo number_format($charCoins, 0, ',', ' '); ?></span>
                                        <span class="shop-stat-label">Coins</span>
                                    </div>
                                    <div class="shop-profile-stat">
                                        <span class="shop-stat-value" style="color:<?php echo $rankColor; ?>;"><?php echo $rankLabel; ?></span>
                                        <span class="shop-stat-label">Rang</span>
                                    </div>
                                    <button class="shop-profile-cart-btn">
                                        <i class="fa-solid fa-bag-shopping"></i> Voir le panier
                                    </button>
                                </div>
                            </div>

                            <?php if (empty($shopListings)): ?>
                            <div style="text-align:center; padding:48px 20px; color:rgba(137,206,255,0.35);">
                                <i class="fa-solid fa-box-open" style="font-size:40px; margin-bottom:14px; display:block;"></i>
                                <div>Aucun article disponible pour le moment.</div>
                            </div>
                            <?php else: ?>

                            <?php if (!empty($shopVedettes)): ?>
                            <div>
                                <div class="shop-section-title">Objets en vedette</div>
                                <div class="shop-featured-grid">
                                    <?php foreach ($shopVedettes as $lst): ?>
                                    <?php
                                        $itLabel = DokkaebiShopManager::ITEM_TYPE_LABELS[$lst['item_type']] ?? $lst['item_type'];
                                        $desc    = trim($lst['item_description'] ?? '');
                                        $canBuy  = $charCoins >= (int)$lst['prix'];
                                        $stockOk = $lst['quantite'] === null || (int)$lst['quantite'] > 0;
                                    ?>
                                    <?php $featData = htmlspecialchars(json_encode([
                                        'nom'      => $lst['nom'],
                                        'type'     => $lst['item_type'] ?? '',
                                        'rang'     => $lst['item_rang'] ?? '',
                                        'desc'     => $lst['item_description'] ?? '',
                                        'prix'     => number_format((int)$lst['prix'], 0, ',', ' ') . ' C',
                                        'stock'    => $lst['quantite'] === null ? 'Illimité' : (string)(int)$lst['quantite'],
                                        'vendeur'  => $lst['vendeur'] ?? '',
                                        'rang_min' => $lst['rang_min'] ?? 'iron',
                                    ]), ENT_QUOTES); ?>
                                    <div class="shop-featured-card" style="cursor:pointer;"
                                         onclick="showItemPopup(<?php echo $featData; ?>)">
                                        <div class="shop-card-category"><?php echo hv($itLabel); ?></div>
                                        <div class="shop-card-name"><?php echo hv($lst['nom']); ?></div>
                                        <?php if ($desc): ?>
                                        <div class="shop-card-attr-label"><?php echo hv(mb_strlen($desc) > 40 ? mb_substr($desc, 0, 40) . '…' : $desc); ?></div>
                                        <?php endif; ?>
                                        <?php if ($stockOk): ?>
                                        <form method="POST" action="files" style="margin:0;" onclick="event.stopPropagation();">
                                            <input type="hidden" name="action"     value="buy_item">
                                            <input type="hidden" name="char_uuid"  value="<?php echo hv($charUuid); ?>">
                                            <input type="hidden" name="back_tab"   value="baluch-dokka">
                                            <input type="hidden" name="listing_id" value="<?php echo (int)$lst['id']; ?>">
                                            <input type="hidden" name="qty"        value="1">
                                            <button type="submit" class="shop-card-price"
                                                    <?php echo !$canBuy ? 'disabled style="opacity:.45; cursor:not-allowed;"' : ''; ?>>
                                                <?php echo number_format((int)$lst['prix'], 0, ',', ' '); ?> C
                                            </button>
                                        </form>
                                        <?php else: ?>
                                        <button class="shop-card-price" disabled style="opacity:.45; cursor:not-allowed;">
                                            Rupture de stock
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php for ($p = count($shopVedettes); $p < 6; $p++): ?>
                                    <div class="shop-featured-card" style="opacity:.25; pointer-events:none;">
                                        <div class="shop-card-category" style="background:rgba(255,255,255,0.04); width:60%; height:11px; border-radius:4px;"></div>
                                        <div class="shop-card-name"    style="background:rgba(255,255,255,0.06); width:80%; height:16px; border-radius:4px; margin:10px 0;"></div>
                                        <div class="shop-card-attr-label" style="background:rgba(255,255,255,0.04); width:90%; height:10px; border-radius:4px;"></div>
                                        <div class="shop-card-price" style="margin-top:auto; background:rgba(255,255,255,0.04); color:transparent; cursor:default;">—</div>
                                    </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div>
                                <div class="shop-section-title">Catalogue général</div>
                                <div class="shop-catalog-layout">
                                    <div class="shop-catalog-grid" id="shop-catalog-grid">
                                        <?php foreach ($shopListings as $lst): ?>
                                        <?php
                                            $itLabel = DokkaebiShopManager::ITEM_TYPE_LABELS[$lst['item_type']] ?? $lst['item_type'];
                                            $canBuy  = $charCoins >= (int)$lst['prix'];
                                            $stockOk = $lst['quantite'] === null || (int)$lst['quantite'] > 0;
                                        ?>
                                        <?php $catData = htmlspecialchars(json_encode([
                                            'nom'     => $lst['nom'],
                                            'type'    => $lst['item_type'] ?? '',
                                            'rang'    => $lst['item_rang'] ?? '',
                                            'desc'    => $lst['item_description'] ?? '',
                                            'prix'    => number_format((int)$lst['prix'], 0, ',', ' ') . ' C',
                                            'stock'   => $lst['quantite'] === null ? 'Illimité' : (string)(int)$lst['quantite'],
                                            'vendeur' => $lst['vendeur'] ?? '',
                                        ]), ENT_QUOTES); ?>
                                        <div class="shop-catalog-card" style="cursor:pointer;"
                                             data-nom="<?php echo htmlspecialchars(strtolower($lst['nom']), ENT_QUOTES); ?>"
                                             onclick="showItemPopup(<?php echo $catData; ?>)">
                                            <div class="shop-catalog-card-subtitle"><?php echo hv($itLabel); ?></div>
                                            <div class="shop-catalog-card-name"><?php echo hv($lst['nom']); ?></div>
                                            <?php if ($stockOk): ?>
                                            <form method="POST" action="files" style="margin:0;" onclick="event.stopPropagation();">
                                                <input type="hidden" name="action"     value="buy_item">
                                                <input type="hidden" name="char_uuid"  value="<?php echo hv($charUuid); ?>">
                                                <input type="hidden" name="back_tab"   value="baluch-dokka">
                                                <input type="hidden" name="listing_id" value="<?php echo (int)$lst['id']; ?>">
                                                <input type="hidden" name="qty"        value="1">
                                                <button type="submit" class="shop-catalog-card-price"
                                                        <?php echo !$canBuy ? 'disabled style="opacity:.45; cursor:not-allowed;"' : ''; ?>>
                                                    <?php echo number_format((int)$lst['prix'], 0, ',', ' '); ?> C
                                                </button>
                                            </form>
                                            <?php else: ?>
                                            <div class="shop-catalog-card-price" style="opacity:.45;">Rupture</div>
                                            <?php endif; ?>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php for ($p = count($shopListings); $p < 8; $p++): ?>
                                        <div class="shop-catalog-card" style="opacity:.2; pointer-events:none;">
                                            <div class="shop-catalog-card-subtitle" style="background:rgba(255,255,255,0.05); width:55%; height:10px; border-radius:3px;"></div>
                                            <div class="shop-catalog-card-name"     style="background:rgba(255,255,255,0.07); width:80%; height:14px; border-radius:3px; margin:8px 0;"></div>
                                            <div class="shop-catalog-card-price" style="background:rgba(255,255,255,0.04); color:transparent; cursor:default;">—</div>
                                        </div>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="shop-catalog-actions">
                                        <button class="shop-action-btn">Panier</button>
                                        <button class="shop-action-btn">Plus</button>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                        </div><!-- /#sp-accueil -->

                        <?php
                        // Helper : page verrouillée générique
                        function shopLockedPage(string $icon, string $title, string $desc, string $reqLabel, string $reqColor): void { ?>
                        <div style="text-align:center; padding:70px 30px;">
                            <div style="font-size:52px; margin-bottom:18px; opacity:.25;">
                                <i class="fa-solid <?php echo $icon; ?>"></i>
                            </div>
                            <div style="font-size:18px; font-weight:700; color:rgba(217,255,255,0.7); margin-bottom:8px;"><?php echo $title; ?></div>
                            <div style="font-size:13px; color:rgba(137,206,255,0.4); margin-bottom:20px; max-width:340px; margin-left:auto; margin-right:auto;"><?php echo $desc; ?></div>
                            <div style="display:inline-flex; align-items:center; gap:8px; padding:8px 20px;
                                        border-radius:20px; font-size:12px; font-weight:700;
                                        background:<?php echo $reqColor; ?>22; color:<?php echo $reqColor; ?>;
                                        border:1px solid <?php echo $reqColor; ?>55;">
                                <i class="fa-solid fa-lock"></i>
                                Rang <?php echo $reqLabel; ?> requis
                            </div>
                        </div>
                        <?php } ?>

                        <!-- ═══ PAGE : Articles VIP ═══ -->
                        <div class="shop-page" id="sp-articles-vip" style="display:none;">
                            <?php if ($rankLevel < 3): shopLockedPage('fa-crown','Articles VIP','Les articles les plus rares et exclusifs du Dokkaebi, réservés à l\'élite des Incarnations.','Diamond','#E91E63');
                            else: ?>
                            <div class="shop-section-title">Articles VIP — Diamond exclusif</div>
                            <div style="padding:20px; color:rgba(137,206,255,0.4); text-align:center;">
                                Aucun article VIP disponible pour le moment.
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- ═══ PAGE : Vos articles en vedette ═══ -->
                        <div class="shop-page" id="sp-mes-vedettes" style="display:none;">
                            <?php if ($rankLevel < 1): shopLockedPage('fa-star','Vos articles en vedette','Mettez vos propres articles en avant et attirez les acheteurs depuis votre profil.','Gold','#FFD700');
                            else: ?>
                            <div class="shop-section-title">Vos articles en vedette</div>
                            <div style="padding:20px; color:rgba(137,206,255,0.4); text-align:center;">
                                Vous n'avez aucun article en vedette pour le moment.
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- ═══ PAGE : Les offres à la une ═══ -->
                        <div class="shop-page" id="sp-offres-une" style="display:none;">
                            <?php if ($rankLevel < 1): shopLockedPage('fa-fire','Les offres à la une','Découvrez les meilleures offres sélectionnées pour vous parmi les Incarnations de votre rang.','Gold','#FFD700');
                            else: ?>
                            <div class="shop-section-title">Les offres à la une</div>
                            <div style="padding:20px; color:rgba(137,206,255,0.4); text-align:center;">
                                Aucune offre à la une pour le moment.
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- ═══ PAGE : Recherche d'offre ═══ -->
                        <div class="shop-page" id="sp-recherche-offre" style="display:none;">
                            <?php if ($rankLevel < 2): shopLockedPage('fa-magnifying-glass-dollar','Recherche d\'offre','Publiez ce que vous recherchez ou répondez aux offres d\'achat des autres Incarnations.','Platinium','#00BCD4');
                            else: ?>
                            <div class="shop-section-title">Recherche d'offre</div>
                            <div style="padding:20px; color:rgba(137,206,255,0.4); text-align:center;">
                                Aucune offre de recherche publiée.
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- ═══ PAGE : Dokkaebi TV ═══ -->
                        <div class="shop-page" id="sp-dokkaebi-tv" style="display:none;">
                            <?php if ($rankLevel < 4): ?>
                            <div style="text-align:center; padding:50px 30px;">
                                <div style="font-size:52px; margin-bottom:18px;">
                                    <i class="fa-solid fa-tv" style="opacity:.2;"></i>
                                </div>
                                <div style="font-size:18px; font-weight:700; color:rgba(217,255,255,0.7); margin-bottom:8px;">Dokkaebi TV</div>
                                <div style="font-size:13px; color:rgba(137,206,255,0.4); margin-bottom:20px; max-width:360px; margin-left:auto; margin-right:auto;">
                                    La chaîne de streaming des Dokkaebi. Seules les Constellations peuvent accéder à ce service privilégié.
                                </div>
                                <div style="display:inline-flex; align-items:center; gap:8px; padding:8px 20px;
                                            border-radius:20px; font-size:12px; font-weight:700;
                                            background:rgba(170,0,255,0.12); color:#CC88FF;
                                            border:1px solid rgba(170,0,255,0.35);">
                                    <i class="fa-solid fa-lock"></i> Rang Constellation requis
                                </div>
                            </div>
                            <?php else: ?>
                            <div style="background:#0a0a1a; border-radius:12px; overflow:hidden; margin:16px;">
                                <div style="background:linear-gradient(135deg,#1a0a2e,#0d1b3e); padding:20px; text-align:center; border-bottom:1px solid rgba(170,0,255,0.2);">
                                    <div style="font-size:22px; font-weight:800; color:#CC88FF; margin-bottom:6px;">
                                        <i class="fa-solid fa-tv"></i> DOKKAEBI TV
                                    </div>
                                    <div style="font-size:12px; color:rgba(170,0,255,0.6);">EN DIRECT</div>
                                </div>
                                <div style="padding:24px; color:rgba(217,255,255,0.6); font-style:italic; text-align:center; line-height:1.8;">
                                    <i class="fa-solid fa-circle" style="color:#EF5350; font-size:8px; margin-right:6px; animation:pulse 1s infinite;"></i>
                                    <?php echo hv($charName); ?> regarde tranquillement la chaîne Dokkaebi TV depuis son baluchon...<br><br>
                                    <span style="font-size:12px; color:rgba(170,0,255,0.5);">« Bienvenue sur Dokkaebi TV, votre source d'informations du Système ! »</span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- ═══ PAGE : Votre profil ═══ -->
                        <div class="shop-page" id="sp-mon-profil" style="display:none;">

                            <!-- Titre + recherche -->
                            <div style="display:flex; align-items:flex-start; justify-content:space-between; padding:0 4px 20px; gap:16px;">
                                <div style="font-size:32px; font-weight:900; color:#fff; line-height:1;">Votre Profil</div>
                                <div style="display:flex; gap:0; margin-top:6px;">
                                    <input type="text" placeholder="Rechercher..."
                                           style="border-radius:8px 0 0 8px; border-right:none; padding:8px 14px; width:180px; box-sizing:border-box;">
                                    <button style="background:rgba(55,174,254,0.15); border:2px solid rgba(55,174,254,0.3); border-left:none; border-radius:0 8px 8px 0; padding:8px 14px; cursor:pointer; color:rgba(137,206,255,0.8); -webkit-text-fill-color:rgba(137,206,255,0.8);">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Carte profil principale -->
                            <div style="display:flex; gap:22px; align-items:flex-start; padding:0 4px 20px;">

                                <!-- Avatar -->
                                <div style="flex-shrink:0; width:160px; height:200px; border-radius:12px; overflow:hidden;
                                            background:rgba(55,174,254,0.06); border:1px solid rgba(55,174,254,0.15);">
                                    <?php if ($char['apparence_image']): ?>
                                    <img src="<?php echo hv($char['apparence_image']); ?>" alt=""
                                         style="width:100%; height:100%; object-fit:cover;">
                                    <?php else: ?>
                                    <div style="display:flex; align-items:center; justify-content:center; height:100%; color:rgba(55,174,254,0.2); font-size:56px;">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Infos -->
                                <div style="flex:1; min-width:0;">

                                    <!-- Tags ligne 1 -->
                                    <div style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:8px;">
                                        <span style="padding:4px 12px; border-radius:6px; font-size:12px; font-weight:600;
                                                     border:1px solid rgba(217,255,255,0.25); color:rgba(217,255,255,0.85);">
                                            Incarnation
                                        </span>
                                        <?php if (!empty($char['constellation_nom'])): ?>
                                        <span style="padding:4px 12px; border-radius:6px; font-size:12px; font-weight:600;
                                                     border:1px solid rgba(217,255,255,0.25); color:rgba(217,255,255,0.85);">
                                            Incarnation Sponsorisée
                                        </span>
                                        <?php endif; ?>
                                        <?php if ($rankLevel >= 1): ?>
                                        <span style="padding:4px 12px; border-radius:6px; font-size:12px; font-weight:700;
                                                     background:<?php echo $rankColor; ?>22; color:<?php echo $rankColor; ?>;
                                                     border:1px solid <?php echo $rankColor; ?>55;">
                                            Rang <?php echo $rankLabel; ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Tags ligne 2 -->
                                    <div style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:12px;">
                                        <?php if ($char['race']): ?>
                                        <span style="padding:4px 12px; border-radius:6px; font-size:12px; font-weight:700;
                                                     background:rgba(239,83,80,0.15); color:#EF5350; border:1px solid rgba(239,83,80,0.35);">
                                            Race &lt;<?php echo hv($char['race']); ?>&gt;
                                        </span>
                                        <?php endif; ?>
                                        <?php if ($char['titre_affiche']): ?>
                                        <span style="padding:4px 12px; border-radius:6px; font-size:12px; font-weight:600;
                                                     border:1px solid rgba(217,255,255,0.2); color:rgba(217,255,255,0.7);">
                                            "<?php echo hv($char['titre_affiche']); ?>"
                                        </span>
                                        <?php endif; ?>
                                        <?php if ($char['metier']): ?>
                                        <span style="padding:4px 12px; border-radius:6px; font-size:12px; font-weight:600;
                                                     background:rgba(255,215,0,0.1); color:#FFD700; border:1px solid rgba(255,215,0,0.3);">
                                            <?php echo hv($char['metier']); ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Nom principal -->
                                    <div style="font-size:20px; font-weight:800; color:#fff; margin-bottom:4px;">
                                        <?php echo hv($charName); ?>
                                        <?php if ($char['constellation_nom']): ?>
                                        <span style="font-size:13px; color:rgba(137,206,255,0.45); font-weight:400; margin-left:8px;">
                                            &lt;<?php echo hv($char['constellation_nom']); ?>&gt;
                                        </span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Abonnés / Suivis -->
                                    <div style="font-size:12px; color:rgba(137,206,255,0.5); margin-bottom:12px;">
                                        <span style="color:rgba(217,255,255,0.7); font-weight:600;">0</span> Abonnés &nbsp;·&nbsp;
                                        <span style="color:rgba(217,255,255,0.7); font-weight:600;">0</span> Suivis
                                    </div>

                                    <!-- Bio -->
                                    <?php $bio = $char['estimation_generale'] ?: $char['psyche'] ?: ''; ?>
                                    <?php if ($bio): ?>
                                    <div style="font-size:12px; color:rgba(137,206,255,0.6); line-height:1.6; margin-bottom:14px;
                                                max-height:72px; overflow:hidden; position:relative;">
                                        "<?php echo hv($bio); ?>"
                                    </div>
                                    <?php endif; ?>

                                    <!-- Boutons d'action -->
                                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                        <button style="padding:9px 20px; border-radius:8px; font-size:12px; font-weight:700;
                                                       background:rgba(0,188,212,0.15); color:#fff; -webkit-text-fill-color:#fff;
                                                       border:1px solid rgba(0,188,212,0.35); cursor:pointer; letter-spacing:.04em;">
                                            S'ABONNER
                                        </button>
                                        <button style="padding:9px 20px; border-radius:8px; font-size:12px; font-weight:700;
                                                       background:rgba(0,188,212,0.15); color:#fff; -webkit-text-fill-color:#fff;
                                                       border:1px solid rgba(0,188,212,0.35); cursor:pointer; letter-spacing:.04em;">
                                            MESSAGES PRIVÉES
                                        </button>
                                        <button style="padding:9px 20px; border-radius:8px; font-size:12px; font-weight:700;
                                                       background:rgba(239,83,80,0.12); color:#fff; -webkit-text-fill-color:#fff;
                                                       border:1px solid rgba(239,83,80,0.3); cursor:pointer; letter-spacing:.04em;">
                                            SIGNALER
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Onglets activité -->
                            <div style="display:flex; gap:0; border-bottom:1px solid rgba(55,174,254,0.15); margin-bottom:16px;">
                                <?php
                                $profileTabs = ['Les derniers achats','Les dernières ventes','Objets recherchés','Offres d\'achat publiées'];
                                foreach ($profileTabs as $i => $pt):
                                ?>
                                <button onclick="switchProfileTab(this, 'ptab-<?php echo $i; ?>')"
                                        class="profile-tab-btn <?php echo $i===0?'profile-tab-active':''; ?>"
                                        style="padding:10px 16px; font-size:12px; background:none; border:none; cursor:pointer;
                                               border-bottom:2px solid <?php echo $i===0?'rgba(55,174,254,0.8)':'transparent'; ?>;
                                               color:<?php echo $i===0?'rgba(137,206,255,0.9)':'rgba(137,206,255,0.4)'; ?>;
                                               -webkit-text-fill-color:<?php echo $i===0?'rgba(137,206,255,0.9)':'rgba(137,206,255,0.4)'; ?>;
                                               font-weight:<?php echo $i===0?'700':'400'; ?>; transition:all .2s; white-space:nowrap;">
                                    <?php echo $pt; ?>
                                </button>
                                <?php endforeach; ?>
                            </div>

                            <!-- Contenu onglets activité -->
                            <?php foreach ($profileTabs as $i => $pt): ?>
                            <div id="ptab-<?php echo $i; ?>" style="display:<?php echo $i===0?'grid':'none'; ?>;
                                 grid-template-columns:1fr 1fr; gap:10px;">
                                <?php for ($c = 0; $c < 6; $c++): ?>
                                <div style="background:rgba(55,174,254,0.04); border:1px solid rgba(55,174,254,0.12);
                                            border-radius:10px; padding:16px 18px; display:flex; flex-direction:column; gap:4px;">
                                    <div style="font-size:11px; font-weight:700; color:rgba(137,206,255,0.4); text-transform:uppercase; letter-spacing:.06em;">Type d'article</div>
                                    <div style="font-size:14px; font-weight:700; color:rgba(217,255,255,0.8);">Nom de l'article</div>
                                    <div style="font-size:12px; color:rgba(137,206,255,0.5);">Détails de la transaction</div>
                                    <div style="font-size:16px; font-weight:800; color:#FFD97E; text-align:right; margin-top:6px;">
                                        2 500 C
                                    </div>
                                </div>
                                <?php endfor; ?>
                            </div>
                            <?php endforeach; ?>

                        </div><!-- /#sp-mon-profil -->

                        <!-- ═══ PAGE : Paramètres ═══ -->
                        <div class="shop-page" id="sp-parametres" style="display:none;">
                            <div class="shop-section-title">Paramètres du shop</div>
                            <div style="padding:4px; display:flex; flex-direction:column; gap:10px;">

                                <div style="background:rgba(55,174,254,0.04); border:1px solid rgba(55,174,254,0.1);
                                            border-radius:12px; padding:18px 20px;
                                            display:flex; align-items:center; justify-content:space-between; gap:16px;">
                                    <div>
                                        <div style="font-size:14px; font-weight:600; color:rgba(217,255,255,0.85); margin-bottom:3px;">
                                            <i class="fa-solid fa-eye" style="margin-right:6px; color:rgba(55,174,254,0.6);"></i>
                                            Profil public
                                        </div>
                                        <div style="font-size:12px; color:rgba(137,206,255,0.4);">
                                            Rend votre profil visible par les autres Incarnations.
                                        </div>
                                    </div>
                                    <label style="position:relative; display:inline-block; width:44px; height:24px; flex-shrink:0;">
                                        <input type="checkbox" checked style="opacity:0; width:0; height:0;">
                                        <span style="position:absolute; inset:0; background:rgba(55,174,254,0.3); border-radius:12px; border:1px solid rgba(55,174,254,0.4); cursor:pointer;"></span>
                                    </label>
                                </div>

                                <div style="background:rgba(55,174,254,0.04); border:1px solid rgba(55,174,254,0.1);
                                            border-radius:12px; padding:18px 20px;
                                            display:flex; align-items:center; justify-content:space-between; gap:16px;">
                                    <div>
                                        <div style="font-size:14px; font-weight:600; color:rgba(217,255,255,0.85); margin-bottom:3px;">
                                            <i class="fa-solid fa-bell" style="margin-right:6px; color:rgba(55,174,254,0.6);"></i>
                                            Notifications
                                        </div>
                                        <div style="font-size:12px; color:rgba(137,206,255,0.4);">
                                            Reçoit des alertes quand une offre vous correspond.
                                        </div>
                                    </div>
                                    <label style="position:relative; display:inline-block; width:44px; height:24px; flex-shrink:0;">
                                        <input type="checkbox" style="opacity:0; width:0; height:0;">
                                        <span style="position:absolute; inset:0; background:rgba(255,255,255,0.05); border-radius:12px; border:1px solid rgba(255,255,255,0.1); cursor:pointer;"></span>
                                    </label>
                                </div>

                                <div style="background:rgba(55,174,254,0.04); border:1px solid rgba(55,174,254,0.1);
                                            border-radius:12px; padding:18px 20px;
                                            display:flex; align-items:center; justify-content:space-between; gap:16px;">
                                    <div>
                                        <div style="font-size:14px; font-weight:600; color:rgba(217,255,255,0.85); margin-bottom:3px;">
                                            <i class="fa-solid fa-lock" style="margin-right:6px; color:rgba(55,174,254,0.6);"></i>
                                            Masquer les transactions
                                        </div>
                                        <div style="font-size:12px; color:rgba(137,206,255,0.4);">
                                            Cache votre historique d'achats/ventes sur votre profil public.
                                        </div>
                                    </div>
                                    <label style="position:relative; display:inline-block; width:44px; height:24px; flex-shrink:0;">
                                        <input type="checkbox" style="opacity:0; width:0; height:0;">
                                        <span style="position:absolute; inset:0; background:rgba(255,255,255,0.05); border-radius:12px; border:1px solid rgba(255,255,255,0.1); cursor:pointer;"></span>
                                    </label>
                                </div>

                            </div>
                        </div><!-- /#sp-parametres -->

                    </div><!-- /.shop-layout -->

                    <!-- ── Barre basse ────────────────────────────────────────── -->
                    <div class="shop-footer-bar">
                        <div class="shop-coins-display">
                            COINS : <?php echo number_format($charCoins, 0, ',', ' '); ?> C
                        </div>
                        <?php
                        $upgradeNext = [
                            'iron'      => ['label' => 'GOLD',      'prix' => '5 000'],
                            'gold'      => ['label' => 'PLATINIUM', 'prix' => '100 000'],
                            'platinium' => ['label' => 'DIAMOND',   'prix' => '250 000'],
                        ];
                        if (isset($upgradeNext[$shopRank])):
                            $up = $upgradeNext[$shopRank];
                        ?>
                        <form method="POST" action="files" style="margin:0; flex-shrink:0;">
                            <input type="hidden" name="action"    value="upgrade_shop_rank">
                            <input type="hidden" name="char_uuid" value="<?php echo hv($charUuid); ?>">
                            <input type="hidden" name="back_tab"  value="baluch-dokka">
                            <button type="submit" class="shop-upgrade-btn">
                                UPGRADE TO <?php echo $up['label']; ?> — <?php echo $up['prix']; ?> C
                            </button>
                        </form>
                        <?php endif; ?>
                        <input class="shop-search-input" type="text" id="shop-footer-search"
                               placeholder="Rechercher un item..."
                               oninput="filterShopCatalog(this.value)">
                        <button class="shop-search-btn" onclick="filterShopCatalog(document.getElementById('shop-footer-search').value)">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>

                <?php endif; ?>
                </div><!-- /#baluch-dokka -->

                <!-- ════════════ ONGLET : Modifier la fiche ════════════ -->
                <div class="orv-menu <?php echo $activeTab !== 'edit-perso' ? 'hidden' : ''; ?>" id="edit-perso">
                    <div class="orv-menu_header">
                        <i class="fa-solid fa-pen-to-square" style="margin-right:8px; color:rgba(137,206,255,0.6);"></i>
                        Modifier la fiche
                    </div>
                    <div class="orv-menu_container">
                        <form method="POST" action="files">
                            <input type="hidden" name="action"    value="update_info">
                            <input type="hidden" name="char_uuid" value="<?php echo hv($charUuid); ?>">
                            <input type="hidden" name="back_tab"  value="edit-perso">

                            <div class="orv-menu_form">
                                <div>
                                    <h2 class="classic-title">Informations générales :</h2>
                                    <cite>Identité civile de votre personnage.</cite>
                                </div>
                                <div class="orv-menu_form-elements">
                                    <div class="orv-menu_form-inputs-list">
                                        <div class="orv-menu_form-input" style="width:220px;">
                                            <label>Nom <span style="color:#FF6B7A;">*</span></label>
                                            <input type="text" name="nom"
                                                   value="<?php echo hv($char['nom']); ?>" required style="width:220px;">
                                        </div>
                                        <div class="orv-menu_form-input" style="width:220px;">
                                            <label>Prénom</label>
                                            <input type="text" name="prenom"
                                                   value="<?php echo hv($char['prenom']); ?>" style="width:220px;">
                                        </div>
                                        <div class="orv-menu_form-input" style="width:200px;">
                                            <label>Nationalité</label>
                                            <input type="text" name="nationalite"
                                                   value="<?php echo hv($char['nationalite']); ?>" style="width:200px;">
                                        </div>
                                        <div class="orv-menu_form-input" style="width:200px;">
                                            <label>Métier</label>
                                            <input type="text" name="metier"
                                                   value="<?php echo hv($char['metier']); ?>" style="width:200px;">
                                        </div>
                                    </div>
                                    <div class="orv-menu_form-valid">
                                        <button type="submit" class="orv-edit">
                                            <i class="fa-solid fa-check"></i>
                                            <span style="font-size:17px;">Sauvegarder</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="orv-menu_form">
                                <div>
                                    <h2 class="classic-title">Psychologie :</h2>
                                    <cite>Comment est votre personnage ?</cite>
                                </div>
                                <div class="orv-menu_form-elements">
                                    <div class="orv-menu_form-inputs-list">
                                        <div class="orv-menu_form-input" style="width:325px;">
                                            <label>Psyché</label>
                                            <textarea name="psyche" rows="5" style="width:325px;"><?php echo hv($char['psyche']); ?></textarea>
                                        </div>
                                        <div class="orv-menu_form-input" style="width:260px;">
                                            <label>Vertu</label>
                                            <textarea name="vertu" rows="5" style="width:260px;"><?php echo hv($char['vertu']); ?></textarea>
                                        </div>
                                        <div class="orv-menu_form-input" style="width:260px;">
                                            <label>Vice</label>
                                            <textarea name="vice" rows="5" style="width:260px;"><?php echo hv($char['vice']); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="orv-menu_form-valid">
                                        <button type="submit" class="orv-edit">
                                            <i class="fa-solid fa-check"></i>
                                            <span style="font-size:17px;">Sauvegarder</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="orv-menu_form">
                                <div>
                                    <h2 class="classic-title">Histoire :</h2>
                                    <cite>Qu'a-t-il vécu ?</cite>
                                </div>
                                <div class="orv-menu_form-elements">
                                    <div class="orv-menu_form-inputs-list">
                                        <textarea name="histoire" style="width:calc(100% - 20px); height:200px;"><?php echo hv($char['histoire']); ?></textarea>
                                    </div>
                                    <div class="orv-menu_form-valid" style="padding-top:0;">
                                        <button type="submit" class="orv-edit">
                                            <i class="fa-solid fa-check"></i>
                                            <span style="font-size:17px;">Sauvegarder</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="orv-menu_form">
                                <div>
                                    <h2 class="classic-title">Apparence :</h2>
                                    <cite>Description physique du personnage.</cite>
                                </div>
                                <div class="orv-menu_form-elements">
                                    <div class="orv-menu_form-inputs-list">
                                        <div class="orv-menu_form-input" style="width:420px;">
                                            <textarea name="apparence_description" rows="5" style="width:420px;"><?php echo hv($char['apparence_description']); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="orv-menu_form-valid">
                                        <button type="submit" class="orv-edit">
                                            <i class="fa-solid fa-check"></i>
                                            <span style="font-size:17px;">Sauvegarder</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div><!-- /.menu-right-container -->
            <?php endif; ?>

            <?php $bases->footer(); ?>
        </section>
    </section>

    <!-- ════════════ MODAL : Créer une fiche ════════════ -->
    <div id="create-char-modal"
         style="display:none; position:fixed; inset:0; z-index:9999;
                background:rgba(0,0,0,0.7); align-items:center; justify-content:center;"
         onclick="if(event.target===this) this.style.display='none';">
        <div style="background:rgba(8,16,40,0.98); border:1px solid rgba(55,174,254,0.3);
                    border-radius:14px; padding:32px; width:min(420px,90vw); backdrop-filter:blur(12px);">
            <div style="font-size:17px; font-weight:700; color:rgba(217,255,255,0.9); margin-bottom:20px;">
                <i class="fa-solid fa-scroll" style="margin-right:8px; color:rgba(55,174,254,0.7);"></i>
                Créer une nouvelle fiche
            </div>
            <form method="POST" action="files">
                <input type="hidden" name="action" value="create_character">
                <div style="display:flex; flex-direction:column; gap:14px; margin-bottom:24px;">
                    <div>
                        <label style="display:block; font-size:12px; color:rgba(137,206,255,0.7); margin-bottom:6px;">
                            Nom <span style="color:#FF6B7A;">*</span>
                        </label>
                        <input type="text" name="nom" required
                               style="width:100%; box-sizing:border-box;"
                               placeholder="Nom de famille">
                    </div>
                    <div>
                        <label style="display:block; font-size:12px; color:rgba(137,206,255,0.7); margin-bottom:6px;">
                            Prénom
                        </label>
                        <input type="text" name="prenom"
                               style="width:100%; box-sizing:border-box;"
                               placeholder="Prénom du personnage">
                    </div>
                </div>
                <div style="display:flex; gap:10px; justify-content:flex-end;">
                    <button type="button" class="orv-edit"
                            style="color:#fff;"
                            onclick="document.getElementById('create-char-modal').style.display='none'">
                        Annuler
                    </button>
                    <button type="submit" class="orv-edit">
                        <i class="fa-solid fa-plus"></i> Créer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // ── Navigation pages shop ─────────────────────────────────────────────────
    var shopPageTitles = {
        'accueil':         'Baluchon des Dokkaebi',
        'articles-vip':    'Articles VIP',
        'mes-vedettes':    'Vos articles en vedette',
        'offres-une':      'Les offres à la une',
        'recherche-offre': 'Recherche d\'offre',
        'dokkaebi-tv':     'Dokkaebi TV',
        'mon-profil':      'Votre Profil',
        'parametres':      'Paramètres',
    };

    document.querySelectorAll('.shop-nav-item[data-shop-page]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var page = this.getAttribute('data-shop-page');
            document.querySelectorAll('.shop-nav-item').forEach(function(l) { l.classList.remove('shop-nav-active'); });
            this.classList.add('shop-nav-active');
            document.querySelectorAll('.shop-page').forEach(function(p) { p.style.display = 'none'; });
            var target = document.getElementById('sp-' + page);
            if (target) target.style.display = '';
            var titleEl = document.getElementById('shop-page-title');
            if (titleEl) titleEl.textContent = shopPageTitles[page] || 'Baluchon des Dokkaebi';
            var overlay = document.getElementById('shop-sidebar-overlay');
            if (overlay && overlay.classList.contains('open')) {
                document.getElementById('shop-sidebar').classList.remove('open');
                overlay.classList.remove('open');
                document.getElementById('shop-hamburger').classList.remove('open');
            }
        });
    });

    // ── Onglets de la page Votre Profil ──────────────────────────────────────
    function switchProfileTab(btn, tabId) {
        document.querySelectorAll('.profile-tab-btn').forEach(function(b) {
            b.style.borderBottomColor = 'transparent';
            b.style.color = 'rgba(137,206,255,0.4)';
            b.style.webkitTextFillColor = 'rgba(137,206,255,0.4)';
            b.style.fontWeight = '400';
        });
        btn.style.borderBottomColor = 'rgba(55,174,254,0.8)';
        btn.style.color = 'rgba(137,206,255,0.9)';
        btn.style.webkitTextFillColor = 'rgba(137,206,255,0.9)';
        btn.style.fontWeight = '700';
        for (var i = 0; i < 4; i++) {
            var t = document.getElementById('ptab-' + i);
            if (t) t.style.display = 'none';
        }
        var active = document.getElementById(tabId);
        if (active) active.style.display = 'grid';
    }

    // ── Filtre catalogue Dokkaebi ─────────────────────────────────────────────
    function filterShopCatalog(query) {
        var q = query.toLowerCase().trim();
        var cards = document.querySelectorAll('#shop-catalog-grid .shop-catalog-card');
        cards.forEach(function(card) {
            card.style.display = (!q || card.dataset.nom.indexOf(q) !== -1) ? '' : 'none';
        });
    }

    // ── Popup item (inventaire & dokkaebi bag) ───────────────────────────────
    var _itemTypeColors = {
        equipement:'#42A5F5', arme:'#EF5350', objet:'#26C6DA',
        consommable:'#66BB6A', objet_cle:'#FFD700', materiaux:'#FFA726',
        livre_competence:'#AB47BC'
    };
    var _itemTypeLabels = {
        equipement:'Équipement', arme:'Arme', objet:'Objet',
        consommable:'Consommable', objet_cle:'Objet clé',
        materiaux:'Matériaux', livre_competence:'Livre de compétence'
    };
    var _rangColors = {
        F:'#9E9E9E', E:'#78909C', D:'#27AE60', C:'#2980B9', B:'#8E44AD',
        A:'#E67E22', S:'#E74C3C', SS:'#C0392B', SSS:'#FFD700',
        legendaire:'#FF6D00', artefact_etoile:'#FF4081', mythique:'#AA00FF'
    };
    var _slotLabels = {
        arme:'Arme', casque:'Casque', plastron:'Plastron',
        gants:'Gants', jambieres:'Jambières', bottes:'Bottes', accessoire:'Accessoire'
    };

    function showItemPopup(data) {
        if (typeof data === 'string') { try { data = JSON.parse(data); } catch(e) { return; } }

        var extra = '<div style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:10px;">';

        // Badge type
        var typeKey   = (data.type || '').trim();
        var typeColor = _itemTypeColors[typeKey] || '#89ceff';
        var typeLabel = _itemTypeLabels[typeKey] || typeKey;
        if (typeLabel) {
            extra += '<span style="padding:3px 12px; border-radius:12px; font-size:11px; font-weight:700;'
                   + 'background:' + typeColor + '22; color:' + typeColor + '; border:1px solid ' + typeColor + '55;">'
                   + typeLabel + '</span>';
        }

        // Badge rang
        var rang = (data.rang || '').trim();
        if (rang) {
            var rangColor = _rangColors[rang] || '#89ceff';
            extra += '<span style="padding:3px 12px; border-radius:12px; font-size:11px; font-weight:700;'
                   + 'background:' + rangColor + '22; color:' + rangColor + '; border:1px solid ' + rangColor + '55;">'
                   + 'Rang ' + rang.toUpperCase() + '</span>';
        }
        extra += '</div>';

        // Infos supplémentaires (shop)
        if (data.prix || data.stock || data.vendeur) {
            extra += '<div style="display:flex; flex-wrap:wrap; gap:8px; margin-top:4px;">';
            if (data.prix) {
                extra += '<div style="background:rgba(255,215,0,0.08); border:1px solid rgba(255,215,0,0.25);'
                       + 'border-radius:8px; padding:5px 14px; font-size:13px; color:#FFD97E;">'
                       + '<i class="fa-solid fa-coins" style="margin-right:5px;"></i>' + data.prix + '</div>';
            }
            if (data.stock) {
                extra += '<div style="background:rgba(137,206,255,0.07); border:1px solid rgba(137,206,255,0.2);'
                       + 'border-radius:8px; padding:5px 14px; font-size:13px; color:rgba(137,206,255,0.7);">'
                       + 'Stock&nbsp;: ' + data.stock + '</div>';
            }
            if (data.vendeur) {
                extra += '<div style="background:rgba(137,206,255,0.07); border:1px solid rgba(137,206,255,0.2);'
                       + 'border-radius:8px; padding:5px 14px; font-size:13px; color:rgba(137,206,255,0.7);">'
                       + 'Vendeur&nbsp;: ' + data.vendeur + '</div>';
            }
            extra += '</div>';
        }

        // Quantité (inventaire)
        if (data.qty && data.qty > 1) {
            extra += '<div style="margin-top:8px; color:rgba(137,206,255,0.6); font-size:13px;">'
                   + 'Quantité&nbsp;: ×' + data.qty + '</div>';
        }

        // Emplacement (équipé)
        if (data.slot) {
            extra += '<div style="margin-top:8px; color:rgba(137,206,255,0.6); font-size:13px;">'
                   + '<i class="fa-solid fa-shield-halved" style="margin-right:5px;"></i>'
                   + 'Emplacement&nbsp;: ' + (_slotLabels[data.slot] || data.slot) + '</div>';
        }

        showPopup(data.nom || '—', data.desc || '', extra);
    }

    // ── Popup compétence ─────────────────────────────────────────────────────
    function showPopup(nom, desc, extra) {
        document.getElementById('popup-title').textContent = nom;
        document.getElementById('popup-nom').textContent   = nom;
        document.getElementById('popup-desc').textContent  = desc || '—';
        document.getElementById('popup-extra').innerHTML   = extra || '';
        document.getElementById('skill-popup').classList.remove('hidden');
    }

    // ── Calcul coût upgrade stats ─────────────────────────────────────────────
    var charStats    = <?php echo $statsJson; ?>;
    var currentCoins = <?php echo $charCoins; ?>;

    function statLevelCost(n) {
        if (n < 100) return 300 + 100 * Math.floor(n / 10);
        return Math.round(1200 * Math.pow(1.03, n - 99));
    }

    function updateTotalCost() {
        var statKeys = ['vitalite', 'force', 'agilite', 'mana'];
        var inputIds = ['up_vitalite', 'up_force', 'up_agilite', 'up_mana'];
        var total = 0;

        statKeys.forEach(function(k, i) {
            var inc = parseInt(document.getElementById(inputIds[i]).value) || 0;
            if (inc < 0) { document.getElementById(inputIds[i]).value = 0; inc = 0; }
            var cur = charStats[k] || 0;
            for (var j = 0; j < inc; j++) {
                total += statLevelCost(cur + j);
            }
        });

        document.getElementById('total-cost-display').textContent = total.toLocaleString('fr-FR') + ' C';
        var warning = document.getElementById('coins-warning');
        warning.style.display = (total > 0 && total > currentCoins) ? 'inline' : 'none';
    }
    </script>

</body>
</html>
