<?php
    require_once './import/attributs.php';
    $am   = new AttributesManager();
    $uuid = $_GET['uuid'] ?? '';
    $attr = $am->getAttributeByUUID($uuid);

    if (!$attr) {
        echo '<div class="user-notice user-notice--error">Attribut introuvable.</div>';
        return;
    }

    $effects   = $am->getEffects($uuid);
    $validTabs = ['attr-info', 'attr-effets'];
    $activeTab = in_array($_GET['tab'] ?? '', $validTabs) ? $_GET['tab'] : 'attr-info';
    $fd        = $formData ?? [];

    $baseUrl   = 'jdr-params?page=attributes&sub-page=modify&uuid=' . urlencode($uuid);
    $rangColor = AttributesManager::RANG_COLORS[$attr['rang']] ?? '#89CEFF';
    $actCats   = AttributesManager::ACTIVATION_CATEGORIES;

    function aahv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }
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
    <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=attributes&sub-page=list';">
        <i class="fa-solid fa-arrow-left"></i>
        <span style="font-size:18px;">Retour à la liste</span>
    </button>
</div>

<div class="orv-menu">
    <div class="orv-menu_header">
        &lt;<?php echo aahv($attr['nom']); ?>&gt;
        <span style="display:inline-block; margin-left:12px; padding:2px 10px; border-radius:12px; font-size:11px; font-weight:700;
                     background:<?php echo $rangColor; ?>22; color:<?php echo $rangColor; ?>;
                     border:1px solid <?php echo $rangColor; ?>55; vertical-align:middle;">
            <?php echo aahv($attr['rang']); ?>
        </span>
    </div>

    <!-- Onglets -->
    <div class="session-tabs" style="padding:0 24px;">
        <button type="button" class="session-tab <?php echo $activeTab==='attr-info'   ? 'button-actif':''; ?>" data-target="attr-info">
            <i class="fa-solid fa-id-card"></i> Informations
        </button>
        <button type="button" class="session-tab <?php echo $activeTab==='attr-effets' ? 'button-actif':''; ?>" data-target="attr-effets">
            <i class="fa-solid fa-wand-magic-sparkles"></i> Effets
            <?php if (!empty($effects)): ?>
            <span style="margin-left:6px; background:rgba(137,206,255,0.15); padding:1px 7px; border-radius:10px; font-size:11px;">
                <?php echo count($effects); ?>
            </span>
            <?php endif; ?>
        </button>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════ -->
    <!-- ONGLET : Informations                                          -->
    <!-- ═══════════════════════════════════════════════════════════════ -->
    <div id="attr-info" class="tab-content" style="<?php echo $activeTab!=='attr-info' ? 'display:none;':''; ?>">
        <form method="POST" action="<?php echo $baseUrl; ?>&tab=attr-info">
            <input type="hidden" name="action" value="update">

            <div style="padding:16px 24px 8px; display:flex; justify-content:flex-end; gap:10px;">
                <button type="submit" class="orv-edit">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span style="font-size:16px;">Sauvegarder</span>
                </button>
            </div>

            <div class="orv-menu_container">
                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Informations générales :</h2>
                        <cite>Nom, rang et statut de l'attribut.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:280px;">
                                <label>Nom <span style="color:#FF6B7A;">*</span></label>
                                <input type="text" name="nom" value="<?php echo aahv(!empty($fd) ? ($fd['nom'] ?? '') : $attr['nom']); ?>" required style="width:280px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:200px;">
                                <label>Rang <span style="color:#FF6B7A;">*</span></label>
                                <select name="rang" style="width:200px;">
                                    <?php
                                        $currentRang = !empty($fd) ? ($fd['rang'] ?? $attr['rang']) : $attr['rang'];
                                    ?>
                                    <?php foreach (AttributesManager::RANGS as $r): ?>
                                    <?php $rc = AttributesManager::RANG_COLORS[$r] ?? '#89CEFF'; ?>
                                    <option value="<?php echo aahv($r); ?>" <?php echo $currentRang === $r ? 'selected' : ''; ?>>
                                        <?php echo aahv($r); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="orv-menu_form-input" style="display:flex; flex-direction:row; align-items:center; gap:8px; padding-top:22px;">
                                <input type="checkbox" name="is_active" id="is_active_attr" value="1"
                                    <?php echo (!empty($fd) ? !empty($fd['is_active']) : $attr['is_active']) ? 'checked' : ''; ?>>
                                <label for="is_active_attr" style="cursor:pointer;">Attribut actif</label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Description :</h2>
                        <cite>Présentation générale de l'attribut.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:100%; max-width:700px;">
                                <label>Description générale</label>
                                <textarea name="description" rows="6" style="width:100%;"><?php echo aahv(!empty($fd) ? ($fd['description'] ?? '') : $attr['description']); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════ -->
    <!-- ONGLET : Effets                                                -->
    <!-- ═══════════════════════════════════════════════════════════════ -->
    <div id="attr-effets" class="tab-content" style="<?php echo $activeTab!=='attr-effets' ? 'display:none;':''; ?>">
        <div class="orv-menu_container">

            <!-- Formulaire d'ajout -->
            <form method="POST" action="<?php echo $baseUrl; ?>&tab=attr-effets">
                <input type="hidden" name="action" value="add_effect">

                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Ajouter un effet :</h2>
                        <cite>Définissez l'effet conféré par cet attribut.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">

                            <div class="orv-menu_form-input" style="width:260px;">
                                <label>Nom de l'effet <span style="color:#FF6B7A;">*</span></label>
                                <input type="text" name="effect_nom" placeholder="ex: Aura de force" style="width:260px;" required>
                            </div>

                            <div class="orv-menu_form-input" style="width:200px;">
                                <label>Catégorie</label>
                                <select name="effect_categorie" id="attr_effect_cat" style="width:200px;"
                                        onchange="syncActCat('attr')">
                                    <?php foreach ($actCats as $catKey => $cat): ?>
                                    <option value="<?php echo $catKey; ?>">
                                        <?php echo htmlspecialchars($cat['label']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="orv-menu_form-input" style="width:200px;" id="attr_sous_type_wrap">
                                <label id="attr_sous_type_label">Type</label>
                                <select name="effect_sous_type" id="attr_effect_sous_type" style="width:200px;">
                                    <?php foreach (array_keys($actCats['stat']['sous_types']) as $stKey): ?>
                                    <option value="<?php echo $stKey; ?>"><?php echo htmlspecialchars($actCats['stat']['sous_types'][$stKey]['label']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="orv-menu_form-input" style="width:220px;" id="attr_valeur_wrap">
                                <label id="attr_valeur_label"><?php echo htmlspecialchars($actCats['stat']['valeur_label']); ?></label>
                                <input type="text" name="effect_valeur" id="attr_effect_valeur"
                                       placeholder="<?php echo htmlspecialchars($actCats['stat']['valeur_placeholder']); ?>"
                                       style="width:220px;">
                            </div>

                            <div class="orv-menu_form-input" style="width:170px;">
                                <label>Durée</label>
                                <select name="effect_type_duree" id="attr_effect_type_duree" style="width:170px;"
                                        onchange="syncActDuree('attr_duree_wrap', this.value)">
                                    <option value="passif">Passif (permanent)</option>
                                    <option value="instantane">Instantané</option>
                                    <option value="tours">Nombre de tours</option>
                                </select>
                            </div>

                            <div class="orv-menu_form-input" style="width:100px;" id="attr_duree_wrap" style="display:none;">
                                <label>Tours</label>
                                <input type="number" name="effect_duree" min="1" placeholder="ex: 3" style="width:100px;">
                            </div>

                        </div>

                        <div class="orv-menu_form-inputs-list" style="margin-top:10px;">
                            <div class="orv-menu_form-input" style="width:100%; max-width:600px;">
                                <label>Notes (optionnel)</label>
                                <textarea name="effect_description" rows="2" style="width:100%;" placeholder="Précisions sur l'effet…"></textarea>
                            </div>
                        </div>

                        <div style="margin-top:14px;">
                            <button type="submit" class="orv-edit" style="font-size:13px; padding:7px 18px;">
                                <i class="fa-solid fa-plus"></i> Ajouter l'effet
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <?php if (!empty($effects)): ?>
            <hr>
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Effets de l'attribut :</h2>
                    <cite><?php echo count($effects); ?> effet<?php echo count($effects) > 1 ? 's' : ''; ?> défini<?php echo count($effects) > 1 ? 's' : ''; ?>.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div style="display:flex; flex-direction:column; gap:10px; width:100%;">
                        <?php foreach ($effects as $fx): ?>
                        <?php
                            $fxCat    = $actCats[$fx['categorie']] ?? $actCats['stat'];
                            $fxSt     = $fxCat['sous_types'][$fx['sous_type']] ?? array_values($fxCat['sous_types'])[0];
                            $fxColor  = $fxCat['color'];
                            $fxStColor = $fxSt['color'];
                        ?>
                        <div style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.08);
                                    border-left:3px solid <?php echo $fxColor; ?>; border-radius:6px; padding:12px 16px;
                                    display:flex; align-items:flex-start; justify-content:space-between; gap:12px;">
                            <div style="flex:1; min-width:0;">
                                <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px; flex-wrap:wrap;">
                                    <strong style="font-size:14px;"><?php echo aahv($fx['nom']); ?></strong>
                                    <span style="font-size:11px; padding:2px 8px; border-radius:10px; font-weight:600;
                                                 background:<?php echo $fxColor; ?>22; color:<?php echo $fxColor; ?>;
                                                 border:1px solid <?php echo $fxColor; ?>44;">
                                        <i class="fa-solid <?php echo htmlspecialchars($fxCat['icon']); ?>"></i>
                                        <?php echo htmlspecialchars($fxCat['label']); ?>
                                    </span>
                                    <span style="font-size:11px; padding:2px 8px; border-radius:10px; font-weight:600;
                                                 background:<?php echo $fxStColor; ?>22; color:<?php echo $fxStColor; ?>;
                                                 border:1px solid <?php echo $fxStColor; ?>44;">
                                        <i class="fa-solid <?php echo htmlspecialchars($fxSt['icon']); ?>"></i>
                                        <?php echo htmlspecialchars($fxSt['label']); ?>
                                    </span>
                                </div>
                                <?php if ($fx['valeur'] !== ''): ?>
                                <div style="font-size:13px; color:<?php echo $fxStColor; ?>; margin-bottom:4px;">
                                    <i class="fa-solid fa-arrow-right" style="font-size:10px; opacity:0.6;"></i>
                                    <?php echo aahv($fx['valeur']); ?>
                                </div>
                                <?php endif; ?>
                                <div style="font-size:12px; color:rgba(137,206,255,0.5);">
                                    <?php if ($fx['type_duree'] === 'passif'): ?>
                                    <i class="fa-solid fa-infinity"></i> Passif (permanent)
                                    <?php elseif ($fx['type_duree'] === 'instantane'): ?>
                                    <i class="fa-solid fa-bolt"></i> Instantané
                                    <?php else: ?>
                                    <i class="fa-solid fa-clock"></i>
                                    <?php echo $fx['duree'] ? htmlspecialchars($fx['duree']) . ' tour' . ($fx['duree'] > 1 ? 's' : '') : '? tours'; ?>
                                    <?php endif; ?>
                                </div>
                                <?php if ($fx['description']): ?>
                                <div style="margin-top:6px; font-size:12px; color:rgba(137,206,255,0.6); font-style:italic;">
                                    <?php echo aahv($fx['description']); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <form method="POST" action="<?php echo $baseUrl; ?>&tab=attr-effets" style="flex-shrink:0;">
                                <input type="hidden" name="action" value="delete_effect">
                                <input type="hidden" name="effect_id" value="<?php echo (int)$fx['id']; ?>">
                                <button type="submit" style="background:none; border:none; cursor:pointer;
                                                             color:rgba(255,100,100,0.5); font-size:16px; padding:4px 6px;"
                                        title="Supprimer cet effet"
                                        onclick="return confirm('Supprimer cet effet ?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div style="padding:30px; text-align:center; color:rgba(137,206,255,0.3); font-size:13px;">
                <i class="fa-solid fa-wand-magic-sparkles" style="font-size:22px; display:block; margin-bottom:8px;"></i>
                Aucun effet défini. Utilisez le formulaire ci-dessus pour en ajouter.
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script>
const ATTR_ACT_CATS = <?php echo json_encode(array_map(function($cat) {
    return [
        'label'              => $cat['label'],
        'icon'               => $cat['icon'],
        'color'              => $cat['color'],
        'duree_default'      => $cat['duree_default'],
        'valeur_label'       => $cat['valeur_label'],
        'valeur_placeholder' => $cat['valeur_placeholder'],
        'sous_types'         => $cat['sous_types'],
    ];
}, $actCats), JSON_UNESCAPED_UNICODE); ?>;

// Shared tab-switching (same pattern as other pages)
document.querySelectorAll('.session-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        const target = btn.dataset.target;
        document.querySelectorAll('.tab-content').forEach(tc => tc.style.display = 'none');
        document.querySelectorAll('.session-tab').forEach(b => b.classList.remove('button-actif'));
        document.getElementById(target).style.display = '';
        btn.classList.add('button-actif');
    });
});

function syncActCat(prefix) {
    const catKey   = document.getElementById(prefix + '_effect_cat').value;
    const cat      = ATTR_ACT_CATS[catKey];
    if (!cat) return;

    // Sous-types
    const stSel = document.getElementById(prefix + '_effect_sous_type');
    stSel.innerHTML = '';
    for (const [stKey, st] of Object.entries(cat.sous_types)) {
        const opt = document.createElement('option');
        opt.value       = stKey;
        opt.textContent = st.label;
        stSel.appendChild(opt);
    }

    // Valeur label/placeholder
    document.getElementById(prefix + '_valeur_label').textContent  = cat.valeur_label;
    document.getElementById(prefix + '_effect_valeur').placeholder  = cat.valeur_placeholder;

    // Durée default
    const durSel = document.getElementById(prefix + '_effect_type_duree');
    durSel.value = cat.duree_default;
    syncActDuree(prefix + '_duree_wrap', cat.duree_default);
}

function syncActDuree(wrapId, value) {
    const wrap = document.getElementById(wrapId);
    if (!wrap) return;
    wrap.style.display = value === 'tours' ? '' : 'none';
}

// Init
syncActCat('attr');
syncActDuree('attr_duree_wrap', document.getElementById('attr_effect_type_duree').value);
</script>
