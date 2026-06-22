<?php
    require_once './import/modifiers.php';
    $mm       = new ModifiersManager();
    $uuid     = $_GET['uuid'] ?? '';
    $modifier = $mm->getModifierByUUID($uuid);

    if (!$modifier) {
        echo '<div class="user-notice user-notice--error">Modificateur introuvable.</div>';
        return;
    }

    $stats     = $mm->getModifierStats($uuid);
    $effects   = $mm->getModifierEffects($uuid);
    $histories = $mm->getAvailableHistories();
    $statMeta  = ModifiersManager::STAT_TYPES;
    $validTabs = ['mod-info', 'mod-bonuses'];
    $activeTab = in_array($_GET['tab'] ?? '', $validTabs) ? $_GET['tab'] : 'mod-info';
    $fd        = $formData ?? [];
    $baseUrl   = 'jdr-params?page=modifiers&sub-page=modify&uuid=' . urlencode($uuid);
    $typeColor = ModifiersManager::TYPE_COLORS[$modifier['type']] ?? '#89CEFF';
    $typeLabel = ModifiersManager::TYPE_LABELS[$modifier['type']] ?? $modifier['type'];
    $bonusCount = count($stats) + count($effects);

    function mmhv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }
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
    <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=modifiers&sub-page=list';">
        <i class="fa-solid fa-arrow-left"></i>
        <span style="font-size:18px;">Retour à la liste</span>
    </button>
</div>

<div class="orv-menu">
    <div class="orv-menu_header">
        &lt;<?php echo mmhv($modifier['nom']); ?>&gt;
        <span style="display:inline-block; margin-left:12px; padding:2px 10px; border-radius:12px; font-size:10px; font-weight:700;
                     background:<?php echo $typeColor; ?>22; color:<?php echo $typeColor; ?>;
                     border:1px solid <?php echo $typeColor; ?>55; vertical-align:middle; white-space:nowrap;">
            <?php echo mmhv($typeLabel); ?>
        </span>
    </div>

    <!-- Onglets -->
    <div class="session-tabs" style="padding:0 24px;">
        <button type="button" class="session-tab <?php echo $activeTab === 'mod-info'    ? 'button-actif' : ''; ?>" data-target="mod-info">
            <i class="fa-solid fa-sliders"></i> Informations
        </button>
        <button type="button" class="session-tab <?php echo $activeTab === 'mod-bonuses' ? 'button-actif' : ''; ?>" data-target="mod-bonuses">
            <i class="fa-solid fa-star"></i> Stats &amp; Effets
            <?php if ($bonusCount > 0): ?>
            <span style="margin-left:6px; background:rgba(137,206,255,0.15); padding:1px 7px; border-radius:10px; font-size:11px;">
                <?php echo $bonusCount; ?>
            </span>
            <?php endif; ?>
        </button>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════ -->
    <!-- ONGLET : Informations                                          -->
    <!-- ═══════════════════════════════════════════════════════════════ -->
    <div id="mod-info" class="tab-content" style="<?php echo $activeTab !== 'mod-info' ? 'display:none;' : ''; ?>">
        <form method="POST" action="<?php echo $baseUrl; ?>&tab=mod-info">
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
                        <cite>Nom et type du modificateur.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">

                            <div class="orv-menu_form-input" style="width:340px;">
                                <label>Nom <span style="color:#FF6B7A;">*</span></label>
                                <input type="text" name="nom"
                                       value="<?php echo mmhv(!empty($fd) ? ($fd['nom'] ?? '') : $modifier['nom']); ?>"
                                       required style="width:340px;">
                            </div>

                            <div class="orv-menu_form-input" style="width:240px;">
                                <label>Type</label>
                                <?php $currentType = !empty($fd) ? ($fd['type'] ?? $modifier['type']) : $modifier['type']; ?>
                                <select name="type" style="width:240px;">
                                    <?php foreach (ModifiersManager::TYPES as $t): ?>
                                    <option value="<?php echo mmhv($t); ?>" <?php echo $currentType === $t ? 'selected' : ''; ?>>
                                        <?php echo mmhv(ModifiersManager::TYPE_LABELS[$t]); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div style="display:flex; align-items:center; gap:8px; padding-top:22px;">
                                <input type="checkbox" name="is_active" id="mod_is_active" value="1"
                                    <?php echo (!empty($fd) ? !empty($fd['is_active']) : $modifier['is_active']) ? 'checked' : ''; ?>>
                                <label for="mod_is_active" style="cursor:pointer;">Modificateur actif</label>
                            </div>

                        </div>
                    </div>
                </div>

                <hr>

                <!-- Histoire liée -->
                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Histoire liée :</h2>
                        <cite>L'histoire à laquelle ce modificateur est rattaché.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:380px;">
                                <label>Histoire</label>
                                <?php $currentHist = !empty($fd) ? ($fd['histoire_uuid'] ?? $modifier['histoire_uuid']) : $modifier['histoire_uuid']; ?>
                                <select name="histoire_uuid" style="width:380px;">
                                    <option value="">— Aucune histoire —</option>
                                    <?php foreach ($histories as $h): ?>
                                    <option value="<?php echo mmhv($h['uuid']); ?>"
                                        <?php echo $currentHist === $h['uuid'] ? 'selected' : ''; ?>>
                                        <?php echo mmhv($h['titre']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Description -->
                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Description :</h2>
                        <cite>Description du modificateur (optionnel).</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:100%; max-width:700px;">
                                <label>Description</label>
                                <textarea name="description" rows="6" style="width:100%;"><?php echo mmhv(!empty($fd) ? ($fd['description'] ?? '') : $modifier['description']); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════ -->
    <!-- ONGLET : Stats & Effets                                        -->
    <!-- ═══════════════════════════════════════════════════════════════ -->
    <div id="mod-bonuses" class="tab-content" style="<?php echo $activeTab !== 'mod-bonuses' ? 'display:none;' : ''; ?>">
        <div class="orv-menu_container">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">

                <!-- ─── Colonne : Statistiques ─── -->
                <div>
                    <div style="font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:.08em;
                                color:rgba(137,206,255,0.5); margin-bottom:14px; padding-bottom:8px;
                                border-bottom:1px solid rgba(137,206,255,0.12);">
                        <i class="fa-solid fa-chart-line"></i> Statistiques
                    </div>

                    <?php if (!empty($stats)): ?>
                    <div style="display:flex; flex-direction:column; gap:6px; margin-bottom:16px;">
                        <?php foreach ($stats as $st): ?>
                        <?php
                            $sm   = $statMeta[$st['stat_type']] ?? ['label' => $st['stat_type'], 'icon' => 'fa-star', 'color' => '#89CEFF'];
                            $val  = (int)$st['valeur'];
                            $sign = $val >= 0 ? '+' : '';
                            $vCol = $val >= 0 ? '#2ECC71' : '#E74C3C';
                        ?>
                        <div style="display:flex; align-items:center; justify-content:space-between;
                                    background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.05);
                                    border-radius:6px; padding:6px 10px;">
                            <span style="font-size:13px; display:flex; align-items:center; gap:8px;">
                                <i class="fa-solid <?php echo $sm['icon']; ?>" style="color:<?php echo $sm['color']; ?>;"></i>
                                <span style="color:<?php echo $sm['color']; ?>; font-weight:600;"><?php echo mmhv($sm['label']); ?></span>
                                <span style="color:<?php echo $vCol; ?>; font-weight:700;"><?php echo $sign . $val; ?></span>
                            </span>
                            <form method="POST" action="<?php echo $baseUrl; ?>&tab=mod-bonuses" style="margin:0;">
                                <input type="hidden" name="action"  value="remove_stat">
                                <input type="hidden" name="stat_id" value="<?php echo (int)$st['id']; ?>">
                                <button type="submit"
                                        style="background:none; border:none; cursor:pointer; color:rgba(255,100,100,0.5); font-size:14px; padding:2px 4px;"
                                        title="Supprimer" onclick="return confirm('Supprimer cette statistique ?')">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div style="font-size:12px; color:rgba(137,206,255,0.25); font-style:italic; margin-bottom:14px;">
                        Aucune statistique pour l'instant.
                    </div>
                    <?php endif; ?>

                    <!-- Formulaire ajout stat -->
                    <form method="POST" action="<?php echo $baseUrl; ?>&tab=mod-bonuses"
                          style="display:flex; gap:6px; align-items:center;">
                        <input type="hidden" name="action" value="add_stat">
                        <select name="stat_type" style="flex:1; font-size:12px; min-width:0;">
                            <?php foreach ($statMeta as $stKey => $stDef): ?>
                            <option value="<?php echo $stKey; ?>"><?php echo mmhv($stDef['label']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" name="stat_valeur" placeholder="ex: 10" required
                               style="width:78px; font-size:12px; text-align:center;"
                               title="Positif = bonus, négatif = malus">
                        <button type="submit" class="orv-edit" style="font-size:12px; padding:5px 10px; flex-shrink:0;">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </form>
                </div>

                <!-- ─── Colonne : Effets ─── -->
                <div>
                    <div style="font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:.08em;
                                color:rgba(137,206,255,0.5); margin-bottom:14px; padding-bottom:8px;
                                border-bottom:1px solid rgba(137,206,255,0.12);">
                        <i class="fa-solid fa-wand-sparkles"></i> Effets
                    </div>

                    <?php if (!empty($effects)): ?>
                    <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:16px;">
                        <?php foreach ($effects as $ef): ?>
                        <div style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.05);
                                    border-radius:6px; padding:8px 10px;">
                            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:4px;">
                                <span style="font-size:13px; font-weight:700; color:rgba(217,255,255,0.85);">
                                    <?php echo mmhv($ef['nom']); ?>
                                </span>
                                <form method="POST" action="<?php echo $baseUrl; ?>&tab=mod-bonuses" style="margin:0;">
                                    <input type="hidden" name="action"    value="remove_effect">
                                    <input type="hidden" name="effect_id" value="<?php echo (int)$ef['id']; ?>">
                                    <button type="submit"
                                            style="background:none; border:none; cursor:pointer; color:rgba(255,100,100,0.5); font-size:14px; padding:2px 4px;"
                                            title="Supprimer" onclick="return confirm('Supprimer cet effet ?')">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </form>
                            </div>
                            <?php if ($ef['description']): ?>
                            <div style="font-size:12px; color:rgba(137,206,255,0.55); line-height:1.5;">
                                <?php echo mmhv($ef['description']); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div style="font-size:12px; color:rgba(137,206,255,0.25); font-style:italic; margin-bottom:14px;">
                        Aucun effet pour l'instant.
                    </div>
                    <?php endif; ?>

                    <!-- Formulaire ajout effet -->
                    <form method="POST" action="<?php echo $baseUrl; ?>&tab=mod-bonuses"
                          style="display:flex; flex-direction:column; gap:6px;">
                        <input type="hidden" name="action" value="add_effect">
                        <input type="text" name="effect_nom" placeholder="Nom de l'effet *" required
                               style="font-size:12px;">
                        <textarea name="effect_description" placeholder="Description (optionnel)"
                                  rows="3" style="font-size:12px; resize:vertical;"></textarea>
                        <div style="display:flex; justify-content:flex-end;">
                            <button type="submit" class="orv-edit" style="font-size:12px; padding:5px 12px;">
                                <i class="fa-solid fa-plus"></i> Ajouter l'effet
                            </button>
                        </div>
                    </form>
                </div>

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
</script>
