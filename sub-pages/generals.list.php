<?php
    require_once './import/generals.php';
    $gm  = new GeneralsManager();
    $cfg = $gm->getAll();
    function glhv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }

    $appStatus    = $cfg['app_status']      ?? 'active';
    $stColor      = GeneralsManager::STATUS_COLORS[$appStatus]  ?? '#9E9E9E';
    $stLabel      = GeneralsManager::STATUS_LABELS[$appStatus]  ?? $appStatus;
    $stIcon       = GeneralsManager::STATUS_ICONS[$appStatus]   ?? 'fa-circle';
    $annActive    = $cfg['announce_active'] === '1';
    $annType      = $cfg['announce_type']   ?? 'info';
    $annColor     = GeneralsManager::ANNOUNCE_TYPES[$annType]['color'] ?? '#42A5F5';
    $annIcon      = GeneralsManager::ANNOUNCE_TYPES[$annType]['icon']  ?? 'fa-circle-info';
?>

<?php if ($notice ?? ''): ?>
<div class="user-notice" style="width:min(1225px,calc(100% - 40px)); margin-top:16px; margin-bottom:0;">
    <i class="fa-solid fa-circle-check"></i> <?php echo $notice ?? ''; ?>
</div>
<?php endif; ?>

<!-- ── Résumé de l'état actuel ──────────────────────────────────────── -->
<div style="width:min(1225px,calc(100% - 40px)); margin-top:20px; margin-bottom:0;
            display:grid; grid-template-columns:repeat(4,1fr); gap:12px;">

    <!-- Statut app -->
    <div style="background:rgba(3,8,22,0.6); border:1px solid rgba(255,255,255,0.06);
                border-top:3px solid <?php echo $stColor; ?>;
                border-radius:10px; padding:16px 18px; position:relative; overflow:hidden;">
        <i class="fa-solid <?php echo $stIcon; ?>"
           style="position:absolute; right:14px; bottom:10px; font-size:36px; color:<?php echo $stColor; ?>; opacity:.07;"></i>
        <div style="font-size:11px; color:rgba(137,206,255,0.4); margin-bottom:6px; text-transform:uppercase; letter-spacing:.06em;">
            Statut application
        </div>
        <div style="display:flex; align-items:center; gap:7px;">
            <span style="width:8px; height:8px; border-radius:50%; background:<?php echo $stColor; ?>;
                         box-shadow:0 0 8px <?php echo $stColor; ?>88; flex-shrink:0;"></span>
            <span style="font-size:15px; font-weight:700; color:<?php echo $stColor; ?>;">
                <?php echo glhv($stLabel); ?>
            </span>
        </div>
    </div>

    <!-- Annonce -->
    <div style="background:rgba(3,8,22,0.6); border:1px solid rgba(255,255,255,0.06);
                border-top:3px solid <?php echo $annActive ? $annColor : '#9E9E9E'; ?>;
                border-radius:10px; padding:16px 18px; position:relative; overflow:hidden;">
        <i class="fa-solid <?php echo $annActive ? $annIcon : 'fa-bell-slash'; ?>"
           style="position:absolute; right:14px; bottom:10px; font-size:36px;
                  color:<?php echo $annActive ? $annColor : '#9E9E9E'; ?>; opacity:.07;"></i>
        <div style="font-size:11px; color:rgba(137,206,255,0.4); margin-bottom:6px; text-transform:uppercase; letter-spacing:.06em;">
            Annonce système
        </div>
        <div style="display:flex; align-items:center; gap:7px;">
            <span style="width:8px; height:8px; border-radius:50%;
                         background:<?php echo $annActive ? $annColor : 'rgba(158,158,158,0.4)'; ?>;
                         <?php echo $annActive ? 'box-shadow:0 0 8px ' . $annColor . '88;' : ''; ?>
                         flex-shrink:0;"></span>
            <span style="font-size:15px; font-weight:700; color:<?php echo $annActive ? $annColor : 'rgba(137,206,255,0.4)'; ?>;">
                <?php echo $annActive ? 'Active' : 'Désactivée'; ?>
            </span>
        </div>
    </div>

    <!-- Session -->
    <div style="background:rgba(3,8,22,0.6); border:1px solid rgba(255,255,255,0.06);
                border-top:3px solid #AB47BC; border-radius:10px; padding:16px 18px;
                position:relative; overflow:hidden;">
        <i class="fa-solid fa-dice-d20"
           style="position:absolute; right:14px; bottom:10px; font-size:36px; color:#AB47BC; opacity:.07;"></i>
        <div style="font-size:11px; color:rgba(137,206,255,0.4); margin-bottom:6px; text-transform:uppercase; letter-spacing:.06em;">
            Session actuelle
        </div>
        <div style="font-size:22px; font-weight:800; color:rgba(217,255,255,0.9); line-height:1;">
            #<?php echo glhv($cfg['world_session'] ?: '—'); ?>
        </div>
        <?php if ($cfg['world_season']): ?>
        <div style="font-size:11px; color:rgba(137,206,255,0.45); margin-top:3px;">
            <?php echo glhv($cfg['world_season']); ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Monnaie -->
    <div style="background:rgba(3,8,22,0.6); border:1px solid rgba(255,255,255,0.06);
                border-top:3px solid #FFD700; border-radius:10px; padding:16px 18px;
                position:relative; overflow:hidden;">
        <i class="fa-solid fa-coins"
           style="position:absolute; right:14px; bottom:10px; font-size:36px; color:#FFD700; opacity:.07;"></i>
        <div style="font-size:11px; color:rgba(137,206,255,0.4); margin-bottom:6px; text-transform:uppercase; letter-spacing:.06em;">
            Monnaie
        </div>
        <div style="font-size:20px; font-weight:800; color:#F5D020; line-height:1;">
            <?php echo glhv($cfg['eco_currency'] ?: 'Coins'); ?>
            <span style="font-size:14px; margin-left:4px; color:rgba(245,208,32,0.55);">
                (<?php echo glhv($cfg['eco_symbol'] ?: 'C'); ?>)
            </span>
        </div>
        <div style="font-size:11px; color:rgba(137,206,255,0.4); margin-top:3px;">
            <?php echo (int)($cfg['eco_start_coins'] ?? 0); ?> C au départ
        </div>
    </div>

</div>

<!-- ── Formulaire principal ──────────────────────────────────────────── -->
<form method="POST" action="jdr-params?page=generals&sub-page=list">

    <div class="orv-menu">
        <div class="orv-menu_header" style="padding:0 20px; justify-content:space-between; display:flex; align-items:center;">
            <span>
                <i class="fa-solid fa-gear" style="margin-right:8px; opacity:.6;"></i>
                Paramètres généraux
            </span>
            <button type="submit" class="orv-edit" style="font-size:13px; padding:7px 18px;">
                <i class="fa-solid fa-floppy-disk"></i> Sauvegarder
            </button>
        </div>
        <div class="orv-menu_container">

            <!-- ════════════ IDENTITÉ ════════════ -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">
                        <i class="fa-solid fa-id-card" style="color:#42A5F5; margin-right:6px;"></i>
                        Identité de l'application
                    </h2>
                    <cite>Nom et présentation affichés sur l'interface.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">

                        <div class="orv-menu_form-input" style="width:340px;">
                            <label>Nom de l'application</label>
                            <input type="text" name="app_name"
                                   value="<?php echo glhv($cfg['app_name']); ?>"
                                   placeholder="Solo Leveling JDR" style="width:340px;">
                        </div>

                        <div class="orv-menu_form-input" style="width:400px;">
                            <label>Sous-titre / description</label>
                            <input type="text" name="app_subtitle"
                                   value="<?php echo glhv($cfg['app_subtitle']); ?>"
                                   placeholder="Système de gestion de jeu de rôle" style="width:400px;">
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <!-- ════════════ STATUT ════════════ -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">
                        <i class="fa-solid fa-circle-half-stroke" style="color:<?php echo $stColor; ?>; margin-right:6px;"></i>
                        Statut de l'application
                    </h2>
                    <cite>Contrôle l'accès général à l'application.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list" style="flex-direction:column; gap:14px; align-items:flex-start;">

                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            <?php foreach (GeneralsManager::STATUS_LABELS as $sv => $sl):
                                $sc = GeneralsManager::STATUS_COLORS[$sv];
                                $si = GeneralsManager::STATUS_ICONS[$sv];
                                $checked = $appStatus === $sv;
                            ?>
                            <label style="display:flex; align-items:center; gap:8px; padding:9px 16px;
                                          border-radius:8px; cursor:pointer; transition:all .15s;
                                          background:<?php echo $checked ? $sc . '18' : 'rgba(3,8,22,0.5)'; ?>;
                                          border:1px solid <?php echo $checked ? $sc . '60' : 'rgba(255,255,255,0.08)'; ?>;">
                                <input type="radio" name="app_status" value="<?php echo glhv($sv); ?>"
                                       <?php echo $checked ? 'checked' : ''; ?>
                                       style="accent-color:<?php echo $sc; ?>;">
                                <i class="fa-solid <?php echo $si; ?>" style="color:<?php echo $sc; ?>; font-size:13px;"></i>
                                <span style="font-size:13px; font-weight:600; color:<?php echo $checked ? $sc : 'rgba(137,206,255,0.6)'; ?>;">
                                    <?php echo glhv($sl); ?>
                                </span>
                            </label>
                            <?php endforeach; ?>
                        </div>

                        <div class="orv-menu_form-input" style="width:100%; max-width:540px;">
                            <label>Message affiché si non actif</label>
                            <input type="text" name="app_status_msg"
                                   value="<?php echo glhv($cfg['app_status_msg']); ?>"
                                   placeholder="ex: Maintenance en cours, retour prévu à 20h"
                                   style="width:100%;">
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <!-- ════════════ ANNONCE SYSTÈME ════════════ -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">
                        <i class="fa-solid fa-bullhorn" style="color:#E67E22; margin-right:6px;"></i>
                        Annonce système
                    </h2>
                    <cite>Bandeau d'information affiché à tous les utilisateurs.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list" style="flex-direction:column; gap:14px; align-items:flex-start;">

                        <div style="display:flex; align-items:center; gap:8px;">
                            <input type="checkbox" name="announce_active" id="ann_active" value="1"
                                   <?php echo $annActive ? 'checked' : ''; ?>>
                            <label for="ann_active" style="cursor:pointer; font-size:13px; font-weight:600;">
                                <i class="fa-solid fa-bell" style="color:#E67E22;"></i>
                                Activer l'annonce
                            </label>
                        </div>

                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            <?php foreach (GeneralsManager::ANNOUNCE_TYPES as $tv => $tdata): ?>
                            <label style="display:flex; align-items:center; gap:7px; padding:7px 14px;
                                          border-radius:8px; cursor:pointer;
                                          background:<?php echo $annType === $tv ? $tdata['color'] . '18' : 'rgba(3,8,22,0.5)'; ?>;
                                          border:1px solid <?php echo $annType === $tv ? $tdata['color'] . '50' : 'rgba(255,255,255,0.08)'; ?>;">
                                <input type="radio" name="announce_type" value="<?php echo glhv($tv); ?>"
                                       <?php echo $annType === $tv ? 'checked' : ''; ?>
                                       style="accent-color:<?php echo $tdata['color']; ?>;">
                                <i class="fa-solid <?php echo $tdata['icon']; ?>"
                                   style="color:<?php echo $tdata['color']; ?>; font-size:12px;"></i>
                                <span style="font-size:12px; font-weight:600;
                                             color:<?php echo $annType === $tv ? $tdata['color'] : 'rgba(137,206,255,0.5)'; ?>;">
                                    <?php echo glhv($tdata['label']); ?>
                                </span>
                            </label>
                            <?php endforeach; ?>
                        </div>

                        <div class="orv-menu_form-input" style="width:100%; max-width:600px;">
                            <label>Texte de l'annonce</label>
                            <textarea name="announce_text" rows="3"
                                      placeholder="ex: Nouvelle session ce vendredi à 20h — Arc du Roi des Ombres"
                                      style="width:100%; resize:vertical;"><?php echo glhv($cfg['announce_text']); ?></textarea>
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <!-- ════════════ UNIVERS ════════════ -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">
                        <i class="fa-solid fa-earth-americas" style="color:#AB47BC; margin-right:6px;"></i>
                        Univers & Campagne
                    </h2>
                    <cite>Contexte narratif de la campagne en cours.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">

                        <div class="orv-menu_form-input" style="width:280px;">
                            <label>Arc narratif actuel</label>
                            <input type="text" name="world_arc"
                                   value="<?php echo glhv($cfg['world_arc']); ?>"
                                   placeholder="ex: Arc du Roi des Ombres" style="width:280px;">
                        </div>

                        <div class="orv-menu_form-input" style="width:180px;">
                            <label>Saison</label>
                            <input type="text" name="world_season"
                                   value="<?php echo glhv($cfg['world_season']); ?>"
                                   placeholder="ex: Saison 1" style="width:180px;">
                        </div>

                        <div class="orv-menu_form-input" style="width:120px;">
                            <label>N° de session</label>
                            <input type="number" name="world_session" min="1"
                                   value="<?php echo glhv($cfg['world_session']); ?>"
                                   style="width:120px;">
                        </div>

                        <div class="orv-menu_form-input" style="width:240px;">
                            <label>Date in-game</label>
                            <input type="text" name="world_date"
                                   value="<?php echo glhv($cfg['world_date']); ?>"
                                   placeholder="ex: An 2, mois des Cendres" style="width:240px;">
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <!-- ════════════ ÉCONOMIE ════════════ -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">
                        <i class="fa-solid fa-coins" style="color:#FFD700; margin-right:6px;"></i>
                        Économie
                    </h2>
                    <cite>Monnaie du jeu et dotation de départ.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">

                        <div class="orv-menu_form-input" style="width:200px;">
                            <label>Nom de la monnaie</label>
                            <input type="text" name="eco_currency"
                                   value="<?php echo glhv($cfg['eco_currency']); ?>"
                                   placeholder="Coins" style="width:200px;">
                        </div>

                        <div class="orv-menu_form-input" style="width:100px;">
                            <label>Symbole</label>
                            <input type="text" name="eco_symbol" maxlength="5"
                                   value="<?php echo glhv($cfg['eco_symbol']); ?>"
                                   placeholder="C" style="width:100px;">
                        </div>

                        <div class="orv-menu_form-input" style="width:180px;">
                            <label>Monnaie de départ</label>
                            <input type="number" name="eco_start_coins" min="0"
                                   value="<?php echo glhv($cfg['eco_start_coins']); ?>"
                                   placeholder="0" style="width:180px;">
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <!-- ════════════ RÈGLES ════════════ -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">
                        <i class="fa-solid fa-scale-balanced" style="color:#26C6DA; margin-right:6px;"></i>
                        Règles & Configuration
                    </h2>
                    <cite>Règles maison et limites de la partie.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list" style="flex-direction:column; gap:18px; align-items:flex-start;">

                        <div style="display:flex; gap:24px; align-items:flex-end; flex-wrap:wrap;">

                            <div class="orv-menu_form-input" style="width:140px;">
                                <label>Joueurs maximum</label>
                                <input type="number" name="rules_max_players" min="1" max="99"
                                       value="<?php echo glhv($cfg['rules_max_players']); ?>"
                                       style="width:140px;">
                            </div>

                            <div style="display:flex; align-items:center; gap:8px; padding-bottom:4px;">
                                <input type="checkbox" name="rules_pvp" id="rules_pvp" value="1"
                                       <?php echo ($cfg['rules_pvp'] ?? '0') === '1' ? 'checked' : ''; ?>>
                                <label for="rules_pvp" style="cursor:pointer; font-size:13px; font-weight:600;">
                                    <i class="fa-solid fa-swords" style="color:#EF5350;"></i>
                                    PvP autorisé
                                </label>
                            </div>

                        </div>

                        <div class="orv-menu_form-input" style="width:100%; max-width:640px;">
                            <label>Notes & Règles maison</label>
                            <textarea name="rules_notes" rows="5"
                                      placeholder="Notez ici vos règles maison, house rules, avertissements..."
                                      style="width:100%; resize:vertical;"><?php echo glhv($cfg['rules_notes']); ?></textarea>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Bouton bas -->
            <div style="display:flex; justify-content:flex-end; padding:16px 20px 4px;">
                <button type="submit" class="orv-edit">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span style="font-size:16px;">Sauvegarder tous les paramètres</span>
                </button>
            </div>

        </div>
    </div>

</form>
