<?php
    require_once './import/histories.php';
    $hm   = new HistoriesManager();
    $uuid = $_GET['uuid'] ?? '';
    $hist = $hm->getHistoryByUUID($uuid);

    if (!$hist) {
        echo '<div class="user-notice user-notice--error">Histoire introuvable.</div>';
        return;
    }

    $levelStigmata    = $hm->getLevelStigmata($uuid);
    $levelStats       = $hm->getLevelStats($uuid);
    $availStigmata    = $hm->getAvailableStigmata();
    $validTabs        = ['hist-info', 'hist-niveaux'];
    $activeTab        = in_array($_GET['tab'] ?? '', $validTabs) ? $_GET['tab'] : 'hist-info';
    $fd               = $formData ?? [];
    $baseUrl          = 'jdr-params?page=histories&sub-page=modify&uuid=' . urlencode($uuid);
    $rangColor        = HistoriesManager::RANG_COLORS[$hist['rang']] ?? '#89CEFF';

    $stigmataRangColors = [
        'E' => '#7F8C8D', 'D' => '#27AE60', 'C' => '#2980B9',
        'B' => '#8E44AD', 'A' => '#E67E22', 'S' => '#E74C3C',
        'SS' => '#C0392B', 'SSS' => '#FFD700',
    ];
    $statMeta = HistoriesManager::STAT_TYPES;

    function hhv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }

    $totalItems = 0;
    foreach ($levelStigmata as $lvl) $totalItems += count($lvl);
    foreach ($levelStats    as $lvl) $totalItems += count($lvl);
?>

<?php if ($notice ?? ''): ?>
<div class="user-notice" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-check"></i> <?php echo $notice; ?>
</div>
<?php endif; ?>
<?php if ($formError ?? null): ?>
<div class="user-notice user-notice--error" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-xmark"></i> <?php echo htmlspecialchars($formError); ?>
</div>
<?php endif; ?>

<div class="orv-buttons" style="justify-content:flex-end;">
    <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=histories&sub-page=list';">
        <i class="fa-solid fa-arrow-left"></i>
        <span style="font-size:18px;">Retour à la liste</span>
    </button>
</div>

<div class="orv-menu">
    <div class="orv-menu_header">
        &lt;<?php echo hhv($hist['titre']); ?>&gt;
        <span style="display:inline-block; margin-left:12px; padding:2px 10px; border-radius:12px; font-size:10px; font-weight:700;
                     background:<?php echo $rangColor; ?>22; color:<?php echo $rangColor; ?>;
                     border:1px solid <?php echo $rangColor; ?>55; vertical-align:middle; white-space:nowrap;">
            <?php echo hhv($hist['rang']); ?>
        </span>
        <?php if ($hist['statut'] === 'brisee'): ?>
        <span style="display:inline-block; margin-left:8px; padding:2px 10px; border-radius:12px; font-size:10px; font-weight:700;
                     background:rgba(239,83,80,0.15); color:#EF5350; border:1px solid rgba(239,83,80,0.4); vertical-align:middle;">
            Brisée — <?php echo (int)$hist['completion_pct']; ?>%
        </span>
        <?php endif; ?>
    </div>

    <!-- Onglets -->
    <div class="session-tabs" style="padding:0 24px;">
        <button type="button" class="session-tab <?php echo $activeTab==='hist-info'    ? 'button-actif':''; ?>" data-target="hist-info">
            <i class="fa-solid fa-scroll"></i> Informations
        </button>
        <button type="button" class="session-tab <?php echo $activeTab==='hist-niveaux' ? 'button-actif':''; ?>" data-target="hist-niveaux">
            <i class="fa-solid fa-layer-group"></i> Niveaux &amp; Stigmates
            <?php if ($totalItems > 0): ?>
            <span style="margin-left:6px; background:rgba(137,206,255,0.15); padding:1px 7px; border-radius:10px; font-size:11px;">
                <?php echo $totalItems; ?>
            </span>
            <?php endif; ?>
        </button>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════ -->
    <!-- ONGLET : Informations                                          -->
    <!-- ═══════════════════════════════════════════════════════════════ -->
    <div id="hist-info" class="tab-content" style="<?php echo $activeTab!=='hist-info' ? 'display:none;':''; ?>">
        <form method="POST" action="<?php echo $baseUrl; ?>&tab=hist-info">
            <input type="hidden" name="action" value="update">

            <div style="padding:16px 24px 8px; display:flex; justify-content:flex-end; gap:10px;">
                <button type="submit" class="orv-edit">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span style="font-size:16px;">Sauvegarder</span>
                </button>
            </div>

            <div class="orv-menu_container">

                <!-- Identité -->
                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Identité :</h2>
                        <cite>Titre, rang et classification.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">

                            <div class="orv-menu_form-input" style="width:320px;">
                                <label>Titre <span style="color:#FF6B7A;">*</span></label>
                                <input type="text" name="titre"
                                       value="<?php echo hhv(!empty($fd) ? ($fd['titre'] ?? '') : $hist['titre']); ?>"
                                       required style="width:320px;">
                            </div>

                            <div class="orv-menu_form-input" style="width:220px;">
                                <label>Rang</label>
                                <select name="rang" id="hist_rang_modify" style="width:220px;"
                                        onchange="syncHistRang(this.value, 'modify')">
                                    <?php
                                        $currentRang = !empty($fd) ? ($fd['rang'] ?? $hist['rang']) : $hist['rang'];
                                    ?>
                                    <?php foreach (HistoriesManager::RANGS as $r): ?>
                                    <?php $rc = HistoriesManager::RANG_COLORS[$r] ?? '#89CEFF'; ?>
                                    <option value="<?php echo hhv($r); ?>"
                                        <?php echo $currentRang === $r ? 'selected' : ''; ?>
                                        style="color:<?php echo $rc; ?>">
                                        <?php echo hhv($r); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="orv-menu_form-input" id="ownership_wrap_modify"
                                 style="width:180px; <?php echo $hist['rang'] !== 'Histoire Gigantesque' ? 'display:none;' : ''; ?>">
                                <label>Fraction de propriété (%)</label>
                                <input type="number" name="ownership_pct" min="1" max="100"
                                       value="<?php echo (int)(!empty($fd) ? ($fd['ownership_pct'] ?? $hist['ownership_pct']) : $hist['ownership_pct']); ?>"
                                       style="width:180px;">
                            </div>

                            <div class="orv-menu_form-input" style="width:120px;">
                                <label>Niveau actuel</label>
                                <select name="niveau_actuel" style="width:120px;">
                                    <?php
                                        $currentNiveau = (int)(!empty($fd) ? ($fd['niveau_actuel'] ?? $hist['niveau_actuel']) : $hist['niveau_actuel']);
                                    ?>
                                    <?php for ($n = 1; $n <= 10; $n++): ?>
                                    <option value="<?php echo $n; ?>" <?php echo $currentNiveau === $n ? 'selected' : ''; ?>>
                                        Niveau <?php echo $n; ?>
                                    </option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <div style="display:flex; align-items:center; gap:24px; flex-wrap:wrap; padding-top:22px;">
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <input type="checkbox" name="is_active" id="is_active_hist" value="1"
                                        <?php echo (!empty($fd) ? !empty($fd['is_active']) : $hist['is_active']) ? 'checked' : ''; ?>>
                                    <label for="is_active_hist" style="cursor:pointer;">Histoire active</label>
                                </div>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <input type="checkbox" name="is_perso" id="is_perso_hist" value="1"
                                        <?php echo (!empty($fd) ? !empty($fd['is_perso']) : $hist['is_perso']) ? 'checked' : ''; ?>>
                                    <label for="is_perso_hist" style="cursor:pointer;">Histoire du personnage</label>
                                </div>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <input type="checkbox" name="is_fondatrice" id="is_fondatrice_hist" value="1"
                                        <?php echo (!empty($fd) ? !empty($fd['is_fondatrice']) : $hist['is_fondatrice']) ? 'checked' : ''; ?>>
                                    <label for="is_fondatrice_hist" style="cursor:pointer;">Histoire fondatrice</label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <hr>

                <!-- Statut -->
                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Statut :</h2>
                        <cite>L'histoire est-elle complète ou brisée ?</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">

                            <div class="orv-menu_form-input" style="width:180px;">
                                <label>Statut</label>
                                <?php $currentStatut = !empty($fd) ? ($fd['statut'] ?? $hist['statut']) : $hist['statut']; ?>
                                <select name="statut" id="hist_statut_modify" style="width:180px;"
                                        onchange="syncHistStatut(this.value, 'modify')">
                                    <option value="complete" <?php echo $currentStatut === 'complete' ? 'selected' : ''; ?>>Complète</option>
                                    <option value="brisee"   <?php echo $currentStatut === 'brisee'   ? 'selected' : ''; ?>>Brisée</option>
                                </select>
                            </div>

                            <div class="orv-menu_form-input" id="completion_wrap_modify"
                                 style="width:180px; <?php echo $currentStatut !== 'brisee' ? 'display:none;' : ''; ?>">
                                <label>Complétion (%)</label>
                                <?php $currentPct = (int)(!empty($fd) ? ($fd['completion_pct'] ?? $hist['completion_pct']) : $hist['completion_pct']); ?>
                                <input type="number" name="completion_pct" min="1" max="99"
                                       value="<?php echo $currentPct; ?>" style="width:180px;">
                                <small style="color:rgba(137,206,255,0.4); font-size:11px;">Entre 1% et 99%</small>
                            </div>

                        </div>
                    </div>
                </div>

                <hr>

                <!-- Description -->
                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Description :</h2>
                        <cite>Résumé et lore de l'histoire.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:100%; max-width:700px;">
                                <label>Description générale</label>
                                <textarea name="description" rows="7" style="width:100%;"><?php echo hhv(!empty($fd) ? ($fd['description'] ?? '') : $hist['description']); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════ -->
    <!-- ONGLET : Niveaux & Stigmates                                   -->
    <!-- ═══════════════════════════════════════════════════════════════ -->
    <div id="hist-niveaux" class="tab-content" style="<?php echo $activeTab!=='hist-niveaux' ? 'display:none;':''; ?>">
        <div class="orv-menu_container">

            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(340px, 1fr)); gap:16px;">
                <?php for ($lvl = 1; $lvl <= 10; $lvl++): ?>
                <?php
                    $stigsAtLevel  = $levelStigmata[$lvl] ?? [];
                    $statsAtLevel  = $levelStats[$lvl]    ?? [];
                    $isCurrentLvl  = (int)$hist['niveau_actuel'] === $lvl;
                    $lvlColor      = $isCurrentLvl ? '#FFD700' : 'rgba(137,206,255,0.3)';
                ?>
                <div style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.06);
                            border-top:3px solid <?php echo $lvlColor; ?>; border-radius:8px; padding:14px;">

                    <!-- En-tête du niveau -->
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
                        <span style="font-size:14px; font-weight:700; color:<?php echo $lvlColor; ?>;">
                            <i class="fa-solid fa-layer-group"></i> Niveau <?php echo $lvl; ?>
                        </span>
                        <?php if ($isCurrentLvl): ?>
                        <span style="font-size:10px; padding:2px 8px; border-radius:10px; font-weight:600;
                                     background:rgba(255,215,0,0.15); color:#FFD700; border:1px solid rgba(255,215,0,0.3);">
                            Niveau actuel
                        </span>
                        <?php endif; ?>
                    </div>

                    <!-- ─── Statistiques ─── -->
                    <div style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.08em;
                                color:rgba(137,206,255,0.4); margin-bottom:6px;">
                        <i class="fa-solid fa-chart-line"></i> Statistiques
                    </div>

                    <?php if (!empty($statsAtLevel)): ?>
                    <div style="margin-bottom:8px; display:flex; flex-direction:column; gap:4px;">
                        <?php foreach ($statsAtLevel as $st): ?>
                        <?php
                            $sm    = $statMeta[$st['stat_type']] ?? ['label' => $st['stat_type'], 'icon' => 'fa-star', 'color' => '#89CEFF'];
                            $val   = (int)$st['valeur'];
                            $sign  = $val >= 0 ? '+' : '';
                            $vCol  = $val >= 0 ? '#2ECC71' : '#E74C3C';
                        ?>
                        <div style="display:flex; align-items:center; justify-content:space-between;
                                    background:rgba(255,255,255,0.03); border-radius:5px; padding:4px 8px;">
                            <span style="font-size:12px; display:flex; align-items:center; gap:6px;">
                                <i class="fa-solid <?php echo $sm['icon']; ?>" style="color:<?php echo $sm['color']; ?>; font-size:11px;"></i>
                                <span style="color:<?php echo $sm['color']; ?>; font-weight:600;"><?php echo htmlspecialchars($sm['label']); ?></span>
                                <span style="color:<?php echo $vCol; ?>; font-weight:700;"><?php echo $sign . $val; ?></span>
                            </span>
                            <form method="POST" action="<?php echo $baseUrl; ?>&tab=hist-niveaux" style="margin:0;">
                                <input type="hidden" name="action" value="remove_level_stat">
                                <input type="hidden" name="stat_id" value="<?php echo (int)$st['id']; ?>">
                                <button type="submit" style="background:none; border:none; cursor:pointer;
                                                             color:rgba(255,100,100,0.5); font-size:13px; padding:2px 4px;"
                                        title="Supprimer" onclick="return confirm('Supprimer cette statistique ?')">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Formulaire ajout stat -->
                    <form method="POST" action="<?php echo $baseUrl; ?>&tab=hist-niveaux"
                          style="display:flex; gap:5px; align-items:center; margin-bottom:12px;">
                        <input type="hidden" name="action" value="add_level_stat">
                        <input type="hidden" name="niveau" value="<?php echo $lvl; ?>">
                        <select name="stat_type" style="flex:1; font-size:12px; min-width:0;">
                            <?php foreach ($statMeta as $stKey => $stDef): ?>
                            <option value="<?php echo $stKey; ?>"><?php echo htmlspecialchars($stDef['label']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" name="stat_valeur" placeholder="ex: 10" required
                               style="width:72px; font-size:12px; text-align:center;"
                               title="Positif = bonus, négatif = malus">
                        <button type="submit" class="orv-edit" style="font-size:12px; padding:5px 10px; flex-shrink:0;">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </form>

                    <!-- ─── Stigmates (optionnel) ─── -->
                    <div style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.08em;
                                color:rgba(137,206,255,0.4); margin-bottom:6px; border-top:1px solid rgba(255,255,255,0.05); padding-top:10px;">
                        <i class="fa-solid fa-copy"></i> Stigmates
                        <span style="font-weight:400; font-style:italic; text-transform:none; letter-spacing:0; opacity:0.6;">(optionnel)</span>
                    </div>

                    <?php if (!empty($stigsAtLevel)): ?>
                    <div style="margin-bottom:8px; display:flex; flex-direction:column; gap:4px;">
                        <?php foreach ($stigsAtLevel as $s): ?>
                        <?php $sc = $stigmataRangColors[$s['stigma_rang']] ?? '#89CEFF'; ?>
                        <div style="display:flex; align-items:center; justify-content:space-between;
                                    background:rgba(255,255,255,0.03); border-radius:5px; padding:4px 8px;">
                            <span style="font-size:12px;">
                                <?php echo hhv($s['stigma_nom']); ?>
                                <span style="font-size:10px; margin-left:5px; padding:1px 5px; border-radius:8px;
                                             background:<?php echo $sc; ?>22; color:<?php echo $sc; ?>; border:1px solid <?php echo $sc; ?>44;">
                                    <?php echo hhv($s['stigma_rang']); ?>
                                </span>
                            </span>
                            <form method="POST" action="<?php echo $baseUrl; ?>&tab=hist-niveaux" style="margin:0;">
                                <input type="hidden" name="action" value="remove_level_stigma">
                                <input type="hidden" name="relation_id" value="<?php echo (int)$s['id']; ?>">
                                <button type="submit" style="background:none; border:none; cursor:pointer;
                                                             color:rgba(255,100,100,0.5); font-size:13px; padding:2px 4px;"
                                        title="Retirer" onclick="return confirm('Retirer ce stigmate du niveau <?php echo $lvl; ?> ?')">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($availStigmata)): ?>
                    <form method="POST" action="<?php echo $baseUrl; ?>&tab=hist-niveaux"
                          style="display:flex; gap:5px; align-items:center;">
                        <input type="hidden" name="action" value="add_level_stigma">
                        <input type="hidden" name="niveau" value="<?php echo $lvl; ?>">
                        <select name="stigma_uuid" style="flex:1; font-size:12px; min-width:0;">
                            <option value="">— Choisir un stigmate —</option>
                            <?php foreach ($availStigmata as $as): ?>
                            <option value="<?php echo hhv($as['uuid']); ?>">
                                <?php echo hhv($as['nom']); ?> (<?php echo hhv($as['rang']); ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="orv-edit" style="font-size:12px; padding:5px 10px; flex-shrink:0;">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </form>
                    <?php else: ?>
                    <div style="font-size:11px; color:rgba(137,206,255,0.2); font-style:italic;">
                        Aucun stigmate disponible
                    </div>
                    <?php endif; ?>

                </div>
                <?php endfor; ?>
            </div>

        </div>
    </div>
</div>

<script>
document.querySelectorAll('.session-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        const target = btn.dataset.target;
        document.querySelectorAll('.tab-content').forEach(tc => tc.style.display = 'none');
        document.querySelectorAll('.session-tab').forEach(b => b.classList.remove('button-actif'));
        document.getElementById(target).style.display = '';
        btn.classList.add('button-actif');
    });
});

function syncHistRang(rang, suffix) {
    const wrap = document.getElementById('ownership_wrap_' + suffix);
    if (wrap) wrap.style.display = (rang === 'Histoire Gigantesque') ? '' : 'none';
}
function syncHistStatut(statut, suffix) {
    const wrap = document.getElementById('completion_wrap_' + suffix);
    if (wrap) wrap.style.display = (statut === 'brisee') ? '' : 'none';
}
</script>
