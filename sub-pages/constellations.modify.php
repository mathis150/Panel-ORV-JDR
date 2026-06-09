<?php
    require_once './import/constellations.php';
    $cm   = new ConstellationsManager();
    $uuid = $_GET['uuid'] ?? '';
    $c    = $cm->getConstellationByUUID($uuid);

    if (!$c) {
        echo '<div class="user-notice user-notice--error">Constellation introuvable.</div>';
        return;
    }

    $relations     = $cm->getConstellationRelations($uuid);
    $availAttrs    = $cm->getAvailableAttributes();
    $availSkills   = $cm->getAvailableSkills();
    $availStigmata = $cm->getAvailableStigmata();

    $validTabs = ['cc-identity','cc-personality','cc-story','cc-stats','cc-titles','cc-capacites'];
    $activeTab = in_array($_GET['tab'] ?? '', $validTabs) ? $_GET['tab'] : 'cc-identity';
    $fd        = $formData ?? [];

    $baseUrl = 'jdr-params?page=constellations&sub-page=modify&uuid=' . urlencode($uuid);

    function chv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }

    $rangOpts = [
        'mythique'        => 'Mythique',
        'legendaire'      => 'Légendaire',
        'historique_haut' => 'Historique haut-rang',
        'historique_bas'  => 'Historique bas-rang',
    ];
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
    <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=constellations&sub-page=list';">
        <i class="fa-solid fa-arrow-left"></i>
        <span style="font-size:18px;">Retour à la liste</span>
    </button>
</div>

<div class="orv-menu">
    <div class="orv-menu_header">&lt;<?php echo chv($c['nom']); ?>&gt;</div>

    <!-- Onglets -->
    <div class="session-tabs" style="padding:0 24px;">
        <button type="button" class="session-tab <?php echo $activeTab==='cc-identity'    ? 'button-actif':''; ?>" data-target="cc-identity">
            <i class="fa-solid fa-id-card"></i> Identité
        </button>
        <button type="button" class="session-tab <?php echo $activeTab==='cc-personality' ? 'button-actif':''; ?>" data-target="cc-personality">
            <i class="fa-solid fa-brain"></i> Psychologie
        </button>
        <button type="button" class="session-tab <?php echo $activeTab==='cc-story'       ? 'button-actif':''; ?>" data-target="cc-story">
            <i class="fa-solid fa-scroll"></i> Récit
        </button>
        <button type="button" class="session-tab <?php echo $activeTab==='cc-stats'       ? 'button-actif':''; ?>" data-target="cc-stats">
            <i class="fa-solid fa-chart-simple"></i> Stats JDR
        </button>
        <button type="button" class="session-tab <?php echo $activeTab==='cc-titles'      ? 'button-actif':''; ?>" data-target="cc-titles">
            <i class="fa-solid fa-crown"></i> Titres
        </button>
        <button type="button" class="session-tab <?php echo $activeTab==='cc-capacites'   ? 'button-actif':''; ?>" data-target="cc-capacites">
            <i class="fa-solid fa-bolt"></i> Capacités
        </button>
    </div>

    <div class="orv-menu_container">

        <!-- ══════════════════════════════════════════════════════
             Onglet Identité
        ══════════════════════════════════════════════════════ -->
        <div id="cc-identity" class="session-tab-panel <?php echo $activeTab!=='cc-identity' ? 'hidden':''; ?>">
            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cc-identity">
                <input type="hidden" name="action" value="update">

                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Informations générales :</h2>
                        <cite>Identité et classification de la constellation.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:260px;">
                                <label>Nom <span style="color:#FF6B7A;">*</span></label>
                                <input type="text" name="nom" value="<?php echo chv($fd['nom'] ?? $c['nom']); ?>" required style="width:260px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:240px;">
                                <label>Identité</label>
                                <input type="text" name="identite" value="<?php echo chv($fd['identite'] ?? $c['identite']); ?>" style="width:240px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:220px;">
                                <label>Provenance</label>
                                <input type="text" name="provenance" value="<?php echo chv($fd['provenance'] ?? $c['provenance']); ?>" style="width:220px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:200px;">
                                <label>Rang</label>
                                <select name="rang" style="width:200px;">
                                    <?php
                                    $selRang = $fd['rang'] ?? $c['rang'];
                                    foreach ($rangOpts as $val => $lbl):
                                    ?>
                                    <option value="<?php echo $val; ?>" <?php echo $selRang === $val ? 'selected' : ''; ?>>
                                        <?php echo $lbl; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="orv-menu_form-input" style="width:200px;">
                                <label>Race</label>
                                <input type="text" name="race" value="<?php echo chv($fd['race'] ?? $c['race']); ?>" style="width:200px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:220px;">
                                <label>Nébuleuse</label>
                                <input type="text" name="nebuleuse" value="<?php echo chv($fd['nebuleuse'] ?? $c['nebuleuse']); ?>" style="width:220px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:160px;">
                                <label>Statut</label>
                                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; margin-top:8px;">
                                    <input type="checkbox" name="is_active" value="1" <?php echo ($fd['is_active'] ?? $c['is_active']) ? 'checked' : ''; ?>>
                                    <span style="font-size:13px;">Active</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transmet les champs des autres onglets pour ne pas les écraser -->
                <input type="hidden" name="psyche"              value="<?php echo chv($c['psyche']); ?>">
                <input type="hidden" name="vertu"               value="<?php echo chv($c['vertu']); ?>">
                <input type="hidden" name="vice"                value="<?php echo chv($c['vice']); ?>">
                <input type="hidden" name="vitalite"            value="<?php echo (int)$c['vitalite']; ?>">
                <input type="hidden" name="force"               value="<?php echo (int)$c['force']; ?>">
                <input type="hidden" name="agilite"             value="<?php echo (int)$c['agilite']; ?>">
                <input type="hidden" name="mana"                value="<?php echo (int)$c['mana']; ?>">
                <input type="hidden" name="coins"               value="<?php echo (int)$c['coins']; ?>">
                <input type="hidden" name="histoire"            value="<?php echo chv($c['histoire']); ?>">
                <input type="hidden" name="estimation_generale" value="<?php echo chv($c['estimation_generale']); ?>">

                <div class="orv-menu_form-valid" style="padding-top:16px;">
                    <button type="submit" class="orv-edit">
                        <i class="fa-solid fa-check"></i>
                        <span style="font-size:18px;">Enregistrer</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- ══════════════════════════════════════════════════════
             Onglet Psychologie
        ══════════════════════════════════════════════════════ -->
        <div id="cc-personality" class="session-tab-panel <?php echo $activeTab!=='cc-personality' ? 'hidden':''; ?>">
            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cc-personality">
                <input type="hidden" name="action"      value="update">
                <input type="hidden" name="nom"         value="<?php echo chv($c['nom']); ?>">
                <input type="hidden" name="identite"    value="<?php echo chv($c['identite']); ?>">
                <input type="hidden" name="provenance"  value="<?php echo chv($c['provenance']); ?>">
                <input type="hidden" name="rang"        value="<?php echo chv($c['rang']); ?>">
                <input type="hidden" name="race"        value="<?php echo chv($c['race']); ?>">
                <input type="hidden" name="nebuleuse"   value="<?php echo chv($c['nebuleuse']); ?>">
                <?php if ($c['is_active']): ?><input type="hidden" name="is_active" value="1"><?php endif; ?>
                <input type="hidden" name="vitalite"            value="<?php echo (int)$c['vitalite']; ?>">
                <input type="hidden" name="force"               value="<?php echo (int)$c['force']; ?>">
                <input type="hidden" name="agilite"             value="<?php echo (int)$c['agilite']; ?>">
                <input type="hidden" name="mana"                value="<?php echo (int)$c['mana']; ?>">
                <input type="hidden" name="coins"               value="<?php echo (int)$c['coins']; ?>">
                <input type="hidden" name="histoire"            value="<?php echo chv($c['histoire']); ?>">
                <input type="hidden" name="estimation_generale" value="<?php echo chv($c['estimation_generale']); ?>">

                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Psychologie :</h2>
                        <cite>Comment est votre constellation ?</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:300px;">
                                <label>Psyché</label>
                                <textarea name="psyche" rows="5" style="width:300px;"><?php echo chv($fd['psyche'] ?? $c['psyche']); ?></textarea>
                            </div>
                            <div class="orv-menu_form-input" style="width:260px;">
                                <label>Vertu</label>
                                <textarea name="vertu" rows="5" style="width:260px;"><?php echo chv($fd['vertu'] ?? $c['vertu']); ?></textarea>
                            </div>
                            <div class="orv-menu_form-input" style="width:260px;">
                                <label>Vice</label>
                                <textarea name="vice" rows="5" style="width:260px;"><?php echo chv($fd['vice'] ?? $c['vice']); ?></textarea>
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
             Onglet Récit
        ══════════════════════════════════════════════════════ -->
        <div id="cc-story" class="session-tab-panel <?php echo $activeTab!=='cc-story' ? 'hidden':''; ?>">
            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cc-story">
                <input type="hidden" name="action"      value="update">
                <input type="hidden" name="nom"         value="<?php echo chv($c['nom']); ?>">
                <input type="hidden" name="identite"    value="<?php echo chv($c['identite']); ?>">
                <input type="hidden" name="provenance"  value="<?php echo chv($c['provenance']); ?>">
                <input type="hidden" name="rang"        value="<?php echo chv($c['rang']); ?>">
                <input type="hidden" name="race"        value="<?php echo chv($c['race']); ?>">
                <input type="hidden" name="nebuleuse"   value="<?php echo chv($c['nebuleuse']); ?>">
                <?php if ($c['is_active']): ?><input type="hidden" name="is_active" value="1"><?php endif; ?>
                <input type="hidden" name="psyche"   value="<?php echo chv($c['psyche']); ?>">
                <input type="hidden" name="vertu"    value="<?php echo chv($c['vertu']); ?>">
                <input type="hidden" name="vice"     value="<?php echo chv($c['vice']); ?>">
                <input type="hidden" name="vitalite" value="<?php echo (int)$c['vitalite']; ?>">
                <input type="hidden" name="force"    value="<?php echo (int)$c['force']; ?>">
                <input type="hidden" name="agilite"  value="<?php echo (int)$c['agilite']; ?>">
                <input type="hidden" name="mana"     value="<?php echo (int)$c['mana']; ?>">
                <input type="hidden" name="coins"    value="<?php echo (int)$c['coins']; ?>">

                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Récit :</h2>
                        <cite>Histoire et estimation générale.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:100%; max-width:700px;">
                                <label>Histoire</label>
                                <textarea name="histoire" rows="7" style="width:100%;"><?php echo chv($fd['histoire'] ?? $c['histoire']); ?></textarea>
                            </div>
                            <div class="orv-menu_form-input" style="width:100%; max-width:700px;">
                                <label>Estimation générale</label>
                                <textarea name="estimation_generale" rows="4" style="width:100%;"><?php echo chv($fd['estimation_generale'] ?? $c['estimation_generale']); ?></textarea>
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
             Onglet Stats JDR
        ══════════════════════════════════════════════════════ -->
        <div id="cc-stats" class="session-tab-panel <?php echo $activeTab!=='cc-stats' ? 'hidden':''; ?>">
            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cc-stats">
                <input type="hidden" name="action"      value="update">
                <input type="hidden" name="nom"         value="<?php echo chv($c['nom']); ?>">
                <input type="hidden" name="identite"    value="<?php echo chv($c['identite']); ?>">
                <input type="hidden" name="provenance"  value="<?php echo chv($c['provenance']); ?>">
                <input type="hidden" name="rang"        value="<?php echo chv($c['rang']); ?>">
                <input type="hidden" name="race"        value="<?php echo chv($c['race']); ?>">
                <input type="hidden" name="nebuleuse"   value="<?php echo chv($c['nebuleuse']); ?>">
                <?php if ($c['is_active']): ?><input type="hidden" name="is_active" value="1"><?php endif; ?>
                <input type="hidden" name="psyche"              value="<?php echo chv($c['psyche']); ?>">
                <input type="hidden" name="vertu"               value="<?php echo chv($c['vertu']); ?>">
                <input type="hidden" name="vice"                value="<?php echo chv($c['vice']); ?>">
                <input type="hidden" name="histoire"            value="<?php echo chv($c['histoire']); ?>">
                <input type="hidden" name="estimation_generale" value="<?php echo chv($c['estimation_generale']); ?>">

                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Statistiques JDR :</h2>
                        <cite>Valeurs de base de la constellation.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:140px;">
                                <label>Force vitale</label>
                                <input type="number" name="vitalite" value="<?php echo (int)($fd['vitalite'] ?? $c['vitalite']); ?>" min="0" style="width:140px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:140px;">
                                <label>Force physique</label>
                                <input type="number" name="force" value="<?php echo (int)($fd['force'] ?? $c['force']); ?>" min="0" style="width:140px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:140px;">
                                <label>Agilité</label>
                                <input type="number" name="agilite" value="<?php echo (int)($fd['agilite'] ?? $c['agilite']); ?>" min="0" style="width:140px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:160px;">
                                <label>Puissance magique</label>
                                <input type="number" name="mana" value="<?php echo (int)($fd['mana'] ?? $c['mana']); ?>" min="0" style="width:160px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:160px;">
                                <label>Argent <small style="opacity:0.5;">(coins)</small></label>
                                <input type="number" name="coins" value="<?php echo (int)($fd['coins'] ?? $c['coins']); ?>" min="0" style="width:160px;">
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
             Onglet Titres
        ══════════════════════════════════════════════════════ -->
        <div id="cc-titles" class="session-tab-panel <?php echo $activeTab!=='cc-titles' ? 'hidden':''; ?>">

            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title"><i class="fa-solid fa-crown"></i> Titres :</h2>
                    <cite>Ajoutez un titre affiché à la constellation.</cite>
                </div>
                <form method="POST" action="<?php echo $baseUrl; ?>&tab=cc-titles">
                    <input type="hidden" name="action" value="add_title">
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list" style="align-items:flex-end;">
                            <div class="orv-menu_form-input" style="width:300px;">
                                <label>Nouveau titre</label>
                                <input type="text" name="title" placeholder="ex: Gardienne des étoiles" style="width:300px;">
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

            <?php if (!empty($relations['titles'])): ?>
            <table style="margin-top:8px;">
                <thead><tr><td>Titre</td><td style="text-align:center; width:100px;">Affiché</td><td width="60px">Retirer</td></tr></thead>
                <tbody>
                    <?php foreach ($relations['titles'] as $t): ?>
                    <tr>
                        <td><?php echo chv($t['title']); ?></td>
                        <td style="text-align:center;">
                            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cc-titles" style="display:inline;">
                                <input type="hidden" name="action"   value="set_displayed_title">
                                <input type="hidden" name="title_id" value="<?php echo (int)$t['id']; ?>">
                                <button type="submit" style="background:none; border:none; cursor:pointer; font-size:16px; color:<?php echo $t['is_displayed'] ? '#FFD97E' : 'rgba(137,206,255,0.3)'; ?>;">
                                    <i class="fa-solid fa-star"></i>
                                </button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cc-titles" style="display:inline;" onsubmit="return confirm('Supprimer ce titre ?');">
                                <input type="hidden" name="action"   value="delete_title">
                                <input type="hidden" name="title_id" value="<?php echo (int)$t['id']; ?>">
                                <button type="submit" style="background:none; border:none; color:#FF6B7A; cursor:pointer; font-size:16px;">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div style="padding:12px 0 20px; color:rgba(137,206,255,0.35); font-size:12px;">Aucun titre ajouté.</div>
            <?php endif; ?>

        </div>

        <!-- ══════════════════════════════════════════════════════
             Onglet Capacités (Attributs / Compétences / Stigmates)
        ══════════════════════════════════════════════════════ -->
        <div id="cc-capacites" class="session-tab-panel <?php echo $activeTab!=='cc-capacites' ? 'hidden':''; ?>">

            <?php
            $sections = [
                'attributes' => [
                    'label'         => 'Attributs',
                    'icon'          => 'fa-address-card',
                    'action_add'    => 'add_attribute',
                    'action_lock'   => 'toggle_attribute_lock',
                    'action_remove' => 'remove_attribute',
                    'available'     => $availAttrs,
                    'data'          => $relations['attributes'],
                    'uuid_field'    => 'attribute_uuid',
                ],
                'skills' => [
                    'label'         => 'Compétences Spéciales',
                    'icon'          => 'fa-bolt',
                    'action_add'    => 'add_skill',
                    'action_lock'   => 'toggle_skill_lock',
                    'action_remove' => 'remove_skill',
                    'available'     => $availSkills,
                    'data'          => $relations['skills'],
                    'uuid_field'    => 'skill_uuid',
                ],
                'stigmata' => [
                    'label'         => 'Stigmates',
                    'icon'          => 'fa-copy',
                    'action_add'    => 'add_stigma',
                    'action_lock'   => 'toggle_stigma_lock',
                    'action_remove' => 'remove_stigma',
                    'available'     => $availStigmata,
                    'data'          => $relations['stigmata'],
                    'uuid_field'    => 'stigma_uuid',
                ],
            ];
            foreach ($sections as $key => $sec):
            ?>

            <div class="orv-menu_form" style="margin-top:<?php echo $key === 'attributes' ? '0' : '24px'; ?>">
                <div>
                    <h2 class="classic-title"><i class="fa-solid <?php echo $sec['icon']; ?>"></i> <?php echo $sec['label']; ?> :</h2>
                    <cite>Les éléments verrouillés <i class="fa-solid fa-lock"></i> ne peuvent pas être retirés par le joueur.</cite>
                </div>

                <?php if (!empty($sec['available'])): ?>
                <form method="POST" action="<?php echo $baseUrl; ?>&tab=cc-capacites">
                    <input type="hidden" name="action" value="<?php echo $sec['action_add']; ?>">
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list" style="align-items:flex-end;">
                            <div class="orv-menu_form-input" style="width:280px;">
                                <label>Ajouter</label>
                                <select name="entity_uuid" style="width:280px;">
                                    <option value="">— Choisir —</option>
                                    <?php foreach ($sec['available'] as $av): ?>
                                    <option value="<?php echo chv($av['uuid']); ?>"><?php echo chv($av['nom']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="orv-menu_form-valid">
                            <button type="submit" class="orv-edit" style="padding:8px 16px;">
                                <i class="fa-solid fa-plus"></i> Ajouter
                            </button>
                        </div>
                    </div>
                </form>
                <?php else: ?>
                <p style="color:rgba(137,206,255,0.4); font-size:12px; margin:8px 0;">
                    <i class="fa-solid fa-circle-info"></i> Aucune entité disponible — créez-en dans la section correspondante.
                </p>
                <?php endif; ?>
            </div>

            <?php if (!empty($sec['data'])): ?>
            <table style="margin-top:8px; margin-bottom:20px;">
                <thead><tr><td>Nom</td><td width="80px" style="text-align:center;">Verrouillé</td><td width="60px">Retirer</td></tr></thead>
                <tbody>
                    <?php foreach ($sec['data'] as $row): ?>
                    <tr>
                        <td><?php echo chv($row['entity_nom'] ?? $row[$sec['uuid_field']]); ?></td>
                        <td style="text-align:center;">
                            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cc-capacites" style="display:inline;">
                                <input type="hidden" name="action"      value="<?php echo $sec['action_lock']; ?>">
                                <input type="hidden" name="relation_id" value="<?php echo (int)$row['id']; ?>">
                                <button type="submit" style="background:none; border:none; cursor:pointer; font-size:16px; color:<?php echo $row['is_locked'] ? '#FFD97E' : 'rgba(137,206,255,0.3)'; ?>;" title="<?php echo $row['is_locked'] ? 'Déverrouiller' : 'Verrouiller'; ?>">
                                    <i class="fa-solid fa-<?php echo $row['is_locked'] ? 'lock' : 'lock-open'; ?>"></i>
                                </button>
                            </form>
                        </td>
                        <td>
                            <?php if (!$row['is_locked']): ?>
                            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cc-capacites" style="display:inline;" onsubmit="return confirm('Retirer cet élément ?');">
                                <input type="hidden" name="action"      value="<?php echo $sec['action_remove']; ?>">
                                <input type="hidden" name="relation_id" value="<?php echo (int)$row['id']; ?>">
                                <button type="submit" style="background:none; border:none; color:#FF6B7A; cursor:pointer; font-size:16px;">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>
                            <?php else: ?>
                            <span style="color:rgba(137,206,255,0.2); font-size:13px;">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div style="padding:12px 0 20px; color:rgba(137,206,255,0.35); font-size:12px;">Aucun élément ajouté.</div>
            <?php endif; ?>

            <?php if ($key !== 'stigmata'): ?><hr><?php endif; ?>
            <?php endforeach; ?>

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
</script>
