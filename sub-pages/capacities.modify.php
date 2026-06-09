<?php
    require_once './import/capacities.php';
    $cm   = new CapacitiesManager();
    $uuid = $_GET['uuid'] ?? '';
    $cap  = $cm->getCapacityByUUID($uuid);

    if (!$cap) {
        echo '<div class="user-notice user-notice--error">Compétence introuvable.</div>';
        return;
    }

    $effects    = $cm->getEffects($uuid);
    $conditions = $cm->getCapacityLevelupConditions($uuid);
    $histories  = $cm->getAvailableHistories();

    $validTabs = ['cap-info', 'cap-visuels', 'cap-effets', 'cap-levelup'];
    $activeTab = in_array($_GET['tab'] ?? '', $validTabs) ? $_GET['tab'] : 'cap-info';
    $fd        = $formData ?? [];

    $baseUrl = 'jdr-params?page=capacities&sub-page=modify&uuid=' . urlencode($uuid);

    function cahv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }

    $rangColors = [
        'E' => '#7F8C8D', 'D' => '#27AE60', 'C'  => '#2980B9',
        'B' => '#8E44AD', 'A' => '#E67E22', 'S'  => '#E74C3C',
        'SS' => '#C0392B', 'SSS' => '#FFD700',
    ];
    $currentRangColor = $rangColors[$cap['rang']] ?? '#89CEFF';
    $actCats = CapacitiesManager::ACTIVATION_CATEGORIES;
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
    <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=capacities&sub-page=list';">
        <i class="fa-solid fa-arrow-left"></i>
        <span style="font-size:18px;">Retour à la liste</span>
    </button>
</div>

<div class="orv-menu">
    <div class="orv-menu_header">
        &lt;<?php echo cahv($cap['nom']); ?>&gt;
        <span style="display:inline-block; margin-left:12px; padding:2px 10px; border-radius:12px; font-size:11px; font-weight:700;
                     background:<?php echo $currentRangColor; ?>22; color:<?php echo $currentRangColor; ?>;
                     border:1px solid <?php echo $currentRangColor; ?>55; vertical-align:middle;">
            Rang <?php echo cahv($cap['rang']); ?>
        </span>
    </div>

    <!-- Onglets -->
    <div class="session-tabs" style="padding:0 24px;">
        <button type="button" class="session-tab <?php echo $activeTab==='cap-info'    ? 'button-actif':''; ?>" data-target="cap-info">
            <i class="fa-solid fa-id-card"></i> Informations
        </button>
        <button type="button" class="session-tab <?php echo $activeTab==='cap-visuels' ? 'button-actif':''; ?>" data-target="cap-visuels">
            <i class="fa-solid fa-eye"></i> Effets visuels
        </button>
        <button type="button" class="session-tab <?php echo $activeTab==='cap-effets'  ? 'button-actif':''; ?>" data-target="cap-effets">
            <i class="fa-solid fa-wand-magic-sparkles"></i> Effets
            <?php if (!empty($effects)): ?>
            <span style="margin-left:6px; background:rgba(137,206,255,0.15); padding:1px 7px; border-radius:10px; font-size:11px;">
                <?php echo count($effects); ?>
            </span>
            <?php endif; ?>
        </button>
        <button type="button" class="session-tab <?php echo $activeTab==='cap-levelup' ? 'button-actif':''; ?>" data-target="cap-levelup">
            <i class="fa-solid fa-arrow-up"></i> Level-up
        </button>
    </div>

    <div class="orv-menu_container">

        <!-- ══════════════════════════════════════════════════════
             Onglet Informations
        ══════════════════════════════════════════════════════ -->
        <div id="cap-info" class="session-tab-panel <?php echo $activeTab!=='cap-info' ? 'hidden':''; ?>">
            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cap-info">
                <input type="hidden" name="action" value="update">

                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Informations générales :</h2>
                        <cite>Identité et classification de la compétence.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:280px;">
                                <label>Nom <span style="color:#FF6B7A;">*</span></label>
                                <input type="text" name="nom" value="<?php echo cahv($fd['nom'] ?? $cap['nom']); ?>" required style="width:280px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:180px;">
                                <label>Rang</label>
                                <select name="rang" style="width:180px;">
                                    <?php foreach (CapacitiesManager::RANGS as $r): ?>
                                    <option value="<?php echo $r; ?>" <?php echo ($fd['rang'] ?? $cap['rang']) === $r ? 'selected' : ''; ?>>
                                        Rang <?php echo $r; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="orv-menu_form-input" style="width:260px;">
                                <label>Histoire liée</label>
                                <?php if (!empty($histories)): ?>
                                <select name="histoire_uuid" style="width:260px;">
                                    <option value="">— Aucune —</option>
                                    <?php foreach ($histories as $h): ?>
                                    <option value="<?php echo cahv($h['uuid']); ?>"
                                        <?php echo ($fd['histoire_uuid'] ?? $cap['histoire_uuid']) === $h['uuid'] ? 'selected' : ''; ?>>
                                        <?php echo cahv($h['titre']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php else: ?>
                                <input type="hidden" name="histoire_uuid" value="<?php echo cahv($cap['histoire_uuid']); ?>">
                                <input type="text" value="<?php echo $cap['histoire_uuid'] ? cahv($cap['histoire_uuid']) : 'Aucune histoire disponible'; ?>" style="width:260px;" disabled>
                                <?php endif; ?>
                            </div>
                            <div class="orv-menu_form-input" style="width:160px;">
                                <label>Statut</label>
                                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; margin-top:8px;">
                                    <input type="checkbox" name="is_active" value="1" <?php echo ($fd['is_active'] ?? $cap['is_active']) ? 'checked' : ''; ?>>
                                    <span style="font-size:13px;">Active</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Description :</h2>
                        <cite>Présentation générale de la compétence.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:100%; max-width:700px;">
                                <label>Description générale</label>
                                <textarea name="description" rows="6" style="width:100%;"><?php echo cahv($fd['description'] ?? $cap['description']); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="effets_visuels" value="<?php echo cahv($cap['effets_visuels']); ?>">

                <div class="orv-menu_form-valid" style="padding-top:16px;">
                    <button type="submit" class="orv-edit">
                        <i class="fa-solid fa-check"></i>
                        <span style="font-size:18px;">Enregistrer</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- ══════════════════════════════════════════════════════
             Onglet Effets visuels
        ══════════════════════════════════════════════════════ -->
        <div id="cap-visuels" class="session-tab-panel <?php echo $activeTab!=='cap-visuels' ? 'hidden':''; ?>">
            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cap-visuels">
                <input type="hidden" name="action"       value="update">
                <input type="hidden" name="nom"          value="<?php echo cahv($cap['nom']); ?>">
                <input type="hidden" name="rang"         value="<?php echo cahv($cap['rang']); ?>">
                <input type="hidden" name="histoire_uuid" value="<?php echo cahv($cap['histoire_uuid']); ?>">
                <?php if ($cap['is_active']): ?><input type="hidden" name="is_active" value="1"><?php endif; ?>
                <input type="hidden" name="description"  value="<?php echo cahv($cap['description']); ?>">

                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Effets visuels :</h2>
                        <cite>Description de ce que la compétence produit visuellement lors de son activation.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:100%; max-width:700px;">
                                <label>Description des effets visuels</label>
                                <textarea name="effets_visuels" rows="8" style="width:100%;"><?php echo cahv($fd['effets_visuels'] ?? $cap['effets_visuels']); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="orv-menu_form-valid" style="padding-top:16px;">
                    <button type="submit" class="orv-edit">
                        <i class="fa-solid fa-check"></i>
                        <span style="font-size:18px;">Enregistrer</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- ══════════════════════════════════════════════════════
             Onglet Effets (système unifié)
        ══════════════════════════════════════════════════════ -->
        <div id="cap-effets" class="session-tab-panel <?php echo $activeTab!=='cap-effets' ? 'hidden':''; ?>">

            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title"><i class="fa-solid fa-wand-magic-sparkles"></i> Effets :</h2>
                    <cite>Dégâts, soins, statistiques temporaires, statuts, protections, contrôles et effets passifs de cette compétence.</cite>
                </div>
                <form method="POST" action="<?php echo $baseUrl; ?>&tab=cap-effets">
                    <input type="hidden" name="action" value="add_effect">
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list" style="align-items:flex-end; flex-wrap:wrap;">

                            <div class="orv-menu_form-input" style="width:230px;">
                                <label>Nom de l'effet <span style="color:#FF6B7A;">*</span></label>
                                <input type="text" name="effect_nom" placeholder="ex: Frappe de feu" style="width:230px;" required>
                            </div>
                            <div class="orv-menu_form-input" style="width:155px;">
                                <label>Catégorie</label>
                                <select name="effect_categorie" id="cap-act-categorie" style="width:155px;"
                                        onchange="syncActCat('cap')">
                                    <?php foreach ($actCats as $ckey => $cat): ?>
                                    <option value="<?php echo $ckey; ?>"><?php echo $cat['label']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="orv-menu_form-input" style="width:170px;">
                                <label>Type</label>
                                <select name="effect_sous_type" id="cap-act-sous-type" style="width:170px;">
                                    <?php $firstCat = reset($actCats); foreach ($firstCat['sous_types'] as $stKey => $st): ?>
                                    <option value="<?php echo $stKey; ?>"><?php echo $st['label']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="orv-menu_form-input" style="width:190px;" id="cap-act-valeur-wrap">
                                <label id="cap-act-valeur-label"><?php echo $firstCat['valeur_label']; ?></label>
                                <input type="text" name="effect_valeur" id="cap-act-valeur"
                                       placeholder="<?php echo htmlspecialchars($firstCat['valeur_placeholder']); ?>"
                                       style="width:190px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:185px;">
                                <label>Durée</label>
                                <select name="effect_type_duree" id="cap-act-type-duree" style="width:185px;"
                                        onchange="syncActDuree('cap-act-tours-wrap', this.value)">
                                    <option value="tours">Temporaire (X tours)</option>
                                    <option value="passif">Passif (permanent)</option>
                                    <option value="instantane">Instantané</option>
                                </select>
                            </div>
                            <div class="orv-menu_form-input" style="width:115px;" id="cap-act-tours-wrap">
                                <label>Nb de tours</label>
                                <input type="number" name="effect_duree" min="1" placeholder="ex: 3" style="width:115px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:100%; max-width:600px;">
                                <label>Notes supplémentaires (optionnel)</label>
                                <textarea name="effect_description" rows="2" style="width:100%;" placeholder="Conditions de déclenchement, portée, interactions…"></textarea>
                            </div>

                        </div>
                        <div class="orv-menu_form-valid">
                            <button type="submit" class="orv-edit" style="padding:8px 16px;">
                                <i class="fa-solid fa-plus"></i> Ajouter l'effet
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <?php if (!empty($effects)): ?>
            <div style="margin-top:16px; display:grid; grid-template-columns:repeat(auto-fill,minmax(240px,1fr)); gap:12px;">
                <?php foreach ($effects as $eff):
                    $cat  = $actCats[$eff['categorie']] ?? $actCats['stat'];
                    $st   = $cat['sous_types'][$eff['sous_type']] ?? array_values($cat['sous_types'])[0];
                    $eId  = (int)$eff['id'];
                ?>
                <div style="background:rgba(137,206,255,0.04); border:1px solid <?php echo $st['color']; ?>55;
                            border-radius:10px; overflow:hidden; position:relative;">
                    <!-- En-tête -->
                    <div style="padding:10px 36px 10px 14px; background:<?php echo $st['color']; ?>18;
                                border-bottom:1px solid <?php echo $st['color']; ?>33;
                                display:flex; align-items:center; gap:8px;">
                        <i class="fa-solid <?php echo $cat['icon']; ?>" style="color:<?php echo $cat['color']; ?>; font-size:12px;"></i>
                        <span style="font-size:10px; color:<?php echo $cat['color']; ?>; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;"><?php echo $cat['label']; ?></span>
                        <span style="margin-left:4px; font-size:10px; padding:1px 6px; border-radius:8px;
                                     background:<?php echo $st['color']; ?>25; color:<?php echo $st['color']; ?>;">
                            <?php echo cahv($st['label']); ?>
                        </span>
                    </div>
                    <!-- Corps -->
                    <div style="padding:12px 14px;">
                        <!-- Nom de l'effet -->
                        <div style="font-size:14px; font-weight:700; color:#EAEAEA; margin-bottom:6px;">
                            <?php echo cahv($eff['nom']); ?>
                        </div>
                        <!-- Valeur + icône -->
                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:4px;">
                            <div style="width:34px; height:34px; border-radius:50%; background:<?php echo $st['color']; ?>22;
                                        display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <i class="fa-solid <?php echo $st['icon']; ?>" style="color:<?php echo $st['color']; ?>; font-size:14px;"></i>
                            </div>
                            <div style="font-size:16px; font-weight:700; color:#EAEAEA; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                <?php if ($eff['categorie'] === 'stat'): ?>
                                    <span style="color:<?php echo (int)$eff['valeur'] >= 0 ? '#2ECC71' : '#E74C3C'; ?>">
                                        <?php echo (int)$eff['valeur'] >= 0 ? '+' : ''; ?><?php echo cahv($eff['valeur']); ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color:<?php echo $st['color']; ?>"><?php echo cahv($eff['valeur']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- Durée -->
                        <div style="font-size:11px; color:rgba(137,206,255,0.4); margin-bottom:4px;">
                            <?php if ($eff['type_duree'] === 'passif'): ?>
                                <i class="fa-solid fa-infinity"></i> Passif
                            <?php elseif ($eff['type_duree'] === 'instantane'): ?>
                                <i class="fa-solid fa-bolt"></i> Instantané
                            <?php else: ?>
                                <i class="fa-solid fa-clock"></i> <?php echo (int)$eff['duree']; ?> tour<?php echo (int)$eff['duree'] > 1 ? 's' : ''; ?>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($eff['description'])): ?>
                        <div style="font-size:11px; color:rgba(137,206,255,0.45); font-style:italic; border-top:1px solid rgba(137,206,255,0.08); padding-top:6px; margin-top:4px;">
                            <?php echo cahv($eff['description']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <!-- Supprimer -->
                    <form method="POST" action="<?php echo $baseUrl; ?>&tab=cap-effets"
                          style="position:absolute; top:6px; right:8px;" onsubmit="return confirm('Supprimer cet effet ?');">
                        <input type="hidden" name="action"    value="delete_effect">
                        <input type="hidden" name="effect_id" value="<?php echo $eId; ?>">
                        <button type="submit" style="background:none; border:none; color:#FF6B7A; cursor:pointer; font-size:13px; padding:2px;">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div style="padding:24px 0; color:rgba(137,206,255,0.35); font-size:13px; text-align:center;">
                <i class="fa-solid fa-wand-magic-sparkles" style="font-size:28px; display:block; margin-bottom:8px; opacity:0.3;"></i>
                Aucun effet défini.
            </div>
            <?php endif; ?>

        </div>

        <!-- ══════════════════════════════════════════════════════
             Onglet Level-up
        ══════════════════════════════════════════════════════ -->
        <div id="cap-levelup" class="session-tab-panel <?php echo $activeTab!=='cap-levelup' ? 'hidden':''; ?>">

            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title"><i class="fa-solid fa-arrow-up"></i> Conditions de Level-up :</h2>
                    <cite>Définissez les conditions requises pour passer à chaque niveau (2 → 10). Niveau max : 10.</cite>
                </div>
                <form method="POST" action="<?php echo $baseUrl; ?>&tab=cap-levelup">
                    <input type="hidden" name="action" value="add_levelup_condition">
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list" style="align-items:flex-end;">
                            <div class="orv-menu_form-input" style="width:130px;">
                                <label>Niveau cible <span style="color:#FF6B7A;">*</span></label>
                                <select name="niveau_cible" style="width:130px;">
                                    <?php for ($i = 2; $i <= 10; $i++): ?>
                                    <option value="<?php echo $i; ?>">Niveau <?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="orv-menu_form-input" style="width:160px;">
                                <label>Type</label>
                                <select name="condition_type" style="width:160px;" id="levelup-type-select">
                                    <option value="usages">Nombre d'usages</option>
                                    <option value="personnalise">Personnalisé</option>
                                </select>
                            </div>
                            <div class="orv-menu_form-input" style="width:280px;">
                                <label>Description <span style="color:#FF6B7A;">*</span></label>
                                <input type="text" name="condition_description" placeholder="ex: Utiliser 50 fois" style="width:280px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:110px;" id="levelup-valeur-wrap">
                                <label>Valeur</label>
                                <input type="number" name="condition_valeur" min="1" placeholder="ex: 50" style="width:110px;">
                            </div>
                        </div>
                        <div class="orv-menu_form-valid">
                            <button type="submit" class="orv-edit" style="padding:8px 16px;">
                                <i class="fa-solid fa-plus"></i> Ajouter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <?php for ($lvl = 2; $lvl <= 10; $lvl++): ?>
            <?php $lvlConditions = $conditions[$lvl] ?? []; ?>
            <div style="margin-top:20px;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                    <span style="font-size:13px; font-weight:700; color:#89CEFF;">Niveau <?php echo $lvl; ?></span>
                    <?php if (empty($lvlConditions)): ?>
                    <span style="font-size:11px; color:rgba(137,206,255,0.3); font-style:italic;">Aucune condition définie</span>
                    <?php endif; ?>
                </div>
                <?php if (!empty($lvlConditions)): ?>
                <table>
                    <thead>
                        <tr>
                            <td>Type</td>
                            <td>Description</td>
                            <td style="text-align:center; width:80px;">Valeur</td>
                            <td width="50px"></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lvlConditions as $cond): ?>
                        <tr>
                            <td>
                                <?php if ($cond['type'] === 'usages'): ?>
                                <span style="font-size:11px; padding:2px 8px; border-radius:10px; background:rgba(41,128,185,0.2); color:#5DADE2;">
                                    <i class="fa-solid fa-rotate-right"></i> Usages
                                </span>
                                <?php else: ?>
                                <span style="font-size:11px; padding:2px 8px; border-radius:10px; background:rgba(142,68,173,0.2); color:#BB8FCE;">
                                    <i class="fa-solid fa-star"></i> Personnalisé
                                </span>
                                <?php endif; ?>
                            </td>
                            <td style="font-size:13px;"><?php echo cahv($cond['description']); ?></td>
                            <td style="text-align:center; font-size:13px; color:rgba(137,206,255,0.7);">
                                <?php echo $cond['valeur'] !== null ? (int)$cond['valeur'] : '—'; ?>
                            </td>
                            <td>
                                <form method="POST" action="<?php echo $baseUrl; ?>&tab=cap-levelup" style="display:inline;" onsubmit="return confirm('Supprimer cette condition ?');">
                                    <input type="hidden" name="action"       value="delete_levelup_condition">
                                    <input type="hidden" name="condition_id" value="<?php echo (int)$cond['id']; ?>">
                                    <button type="submit" style="background:none; border:none; color:#FF6B7A; cursor:pointer; font-size:15px;">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
            <?php if ($lvl < 10): ?>
            <hr style="margin:16px 0; border-color:rgba(137,206,255,0.07);">
            <?php endif; ?>
            <?php endfor; ?>

        </div>

    </div>
</div>

<script>
document.querySelectorAll('.session-tab[data-target]').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.session-tab-panel').forEach(p => p.classList.add('hidden'));
        document.querySelectorAll('.session-tab').forEach(b => b.classList.remove('button-actif'));
        document.getElementById(btn.dataset.target).classList.remove('hidden');
        btn.classList.add('button-actif');
    });
});

const typeSelect = document.getElementById('levelup-type-select');
const valeurWrap = document.getElementById('levelup-valeur-wrap');
if (typeSelect && valeurWrap) {
    typeSelect.addEventListener('change', () => {
        valeurWrap.style.opacity = typeSelect.value === 'usages' ? '1' : '0.5';
    });
}

const ACT_CATS = <?php echo json_encode(array_map(function($c) {
    return [
        'label'              => $c['label'],
        'duree_default'      => $c['duree_default'],
        'valeur_label'       => $c['valeur_label'],
        'valeur_placeholder' => $c['valeur_placeholder'],
        'sous_types'         => array_map(fn($s) => ['label' => $s['label']], $c['sous_types']),
    ];
}, CapacitiesManager::ACTIVATION_CATEGORIES), JSON_UNESCAPED_UNICODE); ?>;

function syncActCat(prefix) {
    const catSel   = document.getElementById(prefix + '-act-categorie');
    const stSel    = document.getElementById(prefix + '-act-sous-type');
    const valLabel = document.getElementById(prefix + '-act-valeur-label');
    const valInput = document.getElementById(prefix + '-act-valeur');
    const dureeSel = document.getElementById(prefix + '-act-type-duree');
    if (!catSel || !stSel) return;
    const cat = ACT_CATS[catSel.value];
    if (!cat) return;
    stSel.innerHTML = '';
    for (const [key, st] of Object.entries(cat.sous_types)) {
        const o = document.createElement('option');
        o.value = key; o.textContent = st.label; stSel.appendChild(o);
    }
    if (valLabel) valLabel.textContent = cat.valeur_label;
    if (valInput) valInput.placeholder = cat.valeur_placeholder;
    if (dureeSel) {
        dureeSel.value = cat.duree_default;
        syncActDuree(prefix + '-act-tours-wrap', cat.duree_default);
    }
}

function syncActDuree(wrapId, value) {
    const wrap = document.getElementById(wrapId);
    if (wrap) wrap.style.display = value === 'tours' ? '' : 'none';
}

(function() {
    const sel = document.getElementById('cap-act-categorie');
    if (sel) syncActCat('cap');
})();
</script>
