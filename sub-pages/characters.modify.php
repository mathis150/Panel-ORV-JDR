<?php
    require_once './import/characters.php';
    $cm   = new CharactersManager();
    $uuid = $_GET['uuid'] ?? '';
    $char = $cm->getCharacterByUUID($uuid);

    if (!$char) {
        echo '<div class="user-notice user-notice--error">Personnage introuvable.</div>';
        return;
    }

    $relations     = $cm->getCharacterRelations($uuid);
    $users         = $cm->getAvailableUsers();
    $constellations = $cm->getAvailableConstellations();
    $availAttrs    = $cm->getAvailableAttributes();
    $availSkills   = $cm->getAvailableSkills();
    $availStigmata = $cm->getAvailableStigmata();
    $availItems    = $cm->getAvailableItems();

    $validTabs  = ['cm-identity','cm-personality','cm-story','cm-stats','cm-titles','cm-capacites','cm-inventory'];
    $activeTab  = in_array($_GET['tab'] ?? '', $validTabs) ? $_GET['tab'] : 'cm-identity';
    $fd         = $formData ?? [];

    $baseUrl = 'jdr-params?page=characters&sub-page=modify&uuid=' . urlencode($uuid);

    function hv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }
    function sel(mixed $a, mixed $b): string { return $a == $b ? 'selected' : ''; }
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
    <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=characters&sub-page=list';">
        <i class="fa-solid fa-arrow-left"></i>
        <span style="font-size:18px;">Retour à la liste</span>
    </button>
</div>

<div class="orv-menu">
    <div class="orv-menu_header">&lt;<?php echo hv($char['prenom'] . ' ' . $char['nom']); ?>&gt;</div>

    <!-- Onglets -->
    <div class="session-tabs" style="padding:0 24px;">
        <button type="button" class="session-tab <?php echo $activeTab==='cm-identity'    ? 'button-actif':''; ?>" data-target="cm-identity">
            <i class="fa-solid fa-id-card"></i> Identité
        </button>
        <button type="button" class="session-tab <?php echo $activeTab==='cm-personality' ? 'button-actif':''; ?>" data-target="cm-personality">
            <i class="fa-solid fa-brain"></i> Personnalité
        </button>
        <button type="button" class="session-tab <?php echo $activeTab==='cm-story'       ? 'button-actif':''; ?>" data-target="cm-story">
            <i class="fa-solid fa-scroll"></i> Récit
        </button>
        <button type="button" class="session-tab <?php echo $activeTab==='cm-stats'       ? 'button-actif':''; ?>" data-target="cm-stats">
            <i class="fa-solid fa-chart-simple"></i> Stats JDR
        </button>
        <button type="button" class="session-tab <?php echo $activeTab==='cm-titles'      ? 'button-actif':''; ?>" data-target="cm-titles">
            <i class="fa-solid fa-crown"></i> Titres
        </button>
        <button type="button" class="session-tab <?php echo $activeTab==='cm-capacites'   ? 'button-actif':''; ?>" data-target="cm-capacites">
            <i class="fa-solid fa-bolt"></i> Capacités
        </button>
        <button type="button" class="session-tab <?php echo $activeTab==='cm-inventory'   ? 'button-actif':''; ?>" data-target="cm-inventory">
            <i class="fa-solid fa-bag-shopping"></i> Inventaire
        </button>
    </div>

    <div class="orv-menu_container">

        <!-- ══════════════════════════════════════════════════════
             Onglet Identité
        ══════════════════════════════════════════════════════ -->
        <div id="cm-identity" class="session-tab-panel <?php echo $activeTab!=='cm-identity' ? 'hidden':''; ?>">
            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cm-identity" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update">
                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Informations générales :</h2>
                        <cite>Identité et rattachement du personnage.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:220px;">
                                <label>Nom <span style="color:#FF6B7A;">*</span></label>
                                <input type="text" name="nom" value="<?php echo hv($fd['nom'] ?? $char['nom']); ?>" required style="width:220px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:220px;">
                                <label>Prénom</label>
                                <input type="text" name="prenom" value="<?php echo hv($fd['prenom'] ?? $char['prenom']); ?>" style="width:220px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:200px;">
                                <label>Race</label>
                                <input type="text" name="race" value="<?php echo hv($fd['race'] ?? $char['race']); ?>" style="width:200px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:200px;">
                                <label>Nationalité</label>
                                <input type="text" name="nationalite" value="<?php echo hv($fd['nationalite'] ?? $char['nationalite']); ?>" style="width:200px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:200px;">
                                <label>Métier</label>
                                <input type="text" name="metier" value="<?php echo hv($fd['metier'] ?? $char['metier']); ?>" style="width:200px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:240px;">
                                <label>Joueur associé</label>
                                <select name="user_uuid" style="width:240px;">
                                    <option value="">— Aucun —</option>
                                    <?php foreach ($users as $u): ?>
                                    <option value="<?php echo hv($u['uuid']); ?>" <?php echo sel($fd['user_uuid'] ?? $char['user_uuid'], $u['uuid']); ?>>
                                        <?php echo hv($u['display_name'] ?: $u['pseudonyme']); ?> (@<?php echo hv($u['pseudonyme']); ?>)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="orv-menu_form-input" style="width:240px;">
                                <label>Constellation Sponsor</label>
                                <?php if (!empty($constellations)): ?>
                                <select name="constellation_sponsor_uuid" style="width:240px;">
                                    <option value="">— Aucune —</option>
                                    <?php foreach ($constellations as $con): ?>
                                    <option value="<?php echo hv($con['uuid']); ?>" <?php echo sel($fd['constellation_sponsor_uuid'] ?? $char['constellation_sponsor_uuid'], $con['uuid']); ?>>
                                        <?php echo hv($con['nom']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php else: ?>
                                <input type="text" name="constellation_sponsor_uuid"
                                       value="<?php echo hv($fd['constellation_sponsor_uuid'] ?? $char['constellation_sponsor_uuid']); ?>"
                                       placeholder="UUID constellation" style="width:240px;">
                                <?php endif; ?>
                            </div>
                            <div class="orv-menu_form-input" style="width:240px;">
                                <label>Statut</label>
                                <div style="display:flex; align-items:center; gap:10px; margin-top:6px;">
                                    <label class="important-white checkbox" style="margin:0;">
                                        <input type="checkbox" name="is_active" <?php echo $char['is_active'] ? 'checked' : ''; ?>>
                                        <span class="checkmark"></span>
                                    </label>
                                    <span style="font-size:13px; color:rgba(137,206,255,0.7);">Personnage actif</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="orv-menu_form-valid">
                        <button type="submit" class="orv-edit">
                            <i class="fa-solid fa-check"></i>
                            <span style="font-size:17px;">Sauvegarder</span>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Baluchon du Dokkaebi (form séparé pour éviter l'imbrication) -->
            <hr style="margin:0 24px;">
            <div class="orv-menu_form" style="padding:20px 24px;">
                <div>
                    <h2 class="classic-title">Baluchon du Dokkaebi :</h2>
                    <cite>Donne accès au shop du Dokkaebi dans l'espace joueur.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div style="display:flex; align-items:center; gap:14px; padding-top:4px;">
                        <?php if (!empty($char['has_dokkaebi_bag'])): ?>
                        <span style="display:inline-flex; align-items:center; gap:7px; padding:5px 14px;
                                     border-radius:8px; background:rgba(46,204,113,0.14);
                                     border:1px solid rgba(46,204,113,0.35); color:#2ECC71; font-size:13px; font-weight:600;">
                            <i class="fa-solid fa-bag-shopping"></i> Actif
                        </span>
                        <?php else: ?>
                        <span style="display:inline-flex; align-items:center; gap:7px; padding:5px 14px;
                                     border-radius:8px; background:rgba(255,255,255,0.04);
                                     border:1px solid rgba(255,255,255,0.1); color:rgba(137,206,255,0.4); font-size:13px;">
                            <i class="fa-solid fa-bag-shopping"></i> Inactif
                        </span>
                        <?php endif; ?>
                        <form method="POST" action="<?php echo $baseUrl; ?>&tab=cm-identity" style="margin:0;">
                            <input type="hidden" name="action" value="toggle_dokkaebi_bag">
                            <button type="submit" class="orv-edit" style="font-size:13px; padding:7px 16px;">
                                <?php if (!empty($char['has_dokkaebi_bag'])): ?>
                                <i class="fa-solid fa-lock"></i> Désactiver
                                <?php else: ?>
                                <i class="fa-solid fa-unlock"></i> Activer
                                <?php endif; ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════
             Onglet Personnalité
        ══════════════════════════════════════════════════════ -->
        <div id="cm-personality" class="session-tab-panel <?php echo $activeTab!=='cm-personality' ? 'hidden':''; ?>">
            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cm-personality" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="nom"     value="<?php echo hv($char['nom']); ?>">
                <input type="hidden" name="prenom"  value="<?php echo hv($char['prenom']); ?>">
                <input type="hidden" name="is_active" value="<?php echo $char['is_active'] ? '1' : ''; ?>">
                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Psychologie :</h2>
                        <cite>Comment est votre personnage intérieurement ?</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:320px;">
                                <label>Psyché</label>
                                <textarea name="psyche" rows="5" style="width:320px;"><?php echo hv($char['psyche']); ?></textarea>
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
                    </div>
                </div>
                <hr>
                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Apparence :</h2>
                        <cite>Description physique et illustration du personnage.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:420px;">
                                <label>Description de l'apparence</label>
                                <textarea name="apparence_description" rows="5" style="width:420px;"><?php echo hv($char['apparence_description']); ?></textarea>
                            </div>
                            <div class="orv-menu_form-input" style="width:240px;">
                                <label>Image du personnage</label>
                                <?php if ($char['apparence_image']): ?>
                                <div style="margin-bottom:8px;">
                                    <img src="<?php echo hv($char['apparence_image']); ?>" alt="Apparence" style="max-width:200px; max-height:200px; border-radius:8px; border:1px solid rgba(55,174,254,0.3);">
                                </div>
                                <?php endif; ?>
                                <input type="file" name="apparence_image" accept="image/*" style="width:240px;">
                                <?php if ($char['apparence_image']): ?>
                                <small style="color:rgba(137,206,255,0.4); font-size:11px;">Laisser vide pour conserver l'image actuelle.</small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="orv-menu_form-valid">
                        <button type="submit" class="orv-edit">
                            <i class="fa-solid fa-check"></i>
                            <span style="font-size:17px;">Sauvegarder</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- ══════════════════════════════════════════════════════
             Onglet Récit
        ══════════════════════════════════════════════════════ -->
        <div id="cm-story" class="session-tab-panel <?php echo $activeTab!=='cm-story' ? 'hidden':''; ?>">
            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cm-story">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="nom"     value="<?php echo hv($char['nom']); ?>">
                <input type="hidden" name="prenom"  value="<?php echo hv($char['prenom']); ?>">
                <input type="hidden" name="is_active" value="<?php echo $char['is_active'] ? '1' : ''; ?>">
                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Histoire :</h2>
                        <cite>Qu'a vécu ce personnage avant le début de la campagne ?</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <textarea name="histoire" rows="10" style="width:calc(100% - 20px);"><?php echo hv($char['histoire']); ?></textarea>
                    </div>
                </div>
                <hr>
                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Estimation générale :</h2>
                        <cite>Appréciation globale du MJ sur le personnage.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <textarea name="estimation_generale" rows="6" style="width:calc(100% - 20px);"><?php echo hv($char['estimation_generale']); ?></textarea>
                    </div>
                    <div class="orv-menu_form-valid">
                        <button type="submit" class="orv-edit">
                            <i class="fa-solid fa-check"></i>
                            <span style="font-size:17px;">Sauvegarder</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- ══════════════════════════════════════════════════════
             Onglet Stats JDR
        ══════════════════════════════════════════════════════ -->
        <div id="cm-stats" class="session-tab-panel <?php echo $activeTab!=='cm-stats' ? 'hidden':''; ?>">
            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cm-stats">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="nom"     value="<?php echo hv($char['nom']); ?>">
                <input type="hidden" name="prenom"  value="<?php echo hv($char['prenom']); ?>">
                <input type="hidden" name="is_active" value="<?php echo $char['is_active'] ? '1' : ''; ?>">
                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Statistiques de combat :</h2>
                        <cite>Valeurs numériques du personnage en jeu.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:160px;">
                                <label>Vitalité</label>
                                <input type="number" name="vitalite" value="<?php echo (int)$char['vitalite']; ?>" min="0" style="width:160px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:160px;">
                                <label>Force</label>
                                <input type="number" name="force" value="<?php echo (int)$char['force']; ?>" min="0" style="width:160px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:160px;">
                                <label>Agilité</label>
                                <input type="number" name="agilite" value="<?php echo (int)$char['agilite']; ?>" min="0" style="width:160px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:160px;">
                                <label>Mana</label>
                                <input type="number" name="mana" value="<?php echo (int)$char['mana']; ?>" min="0" style="width:160px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:180px;">
                                <label>Coins <small style="opacity:0.5;">(banque)</small></label>
                                <input type="number" name="coins" value="<?php echo (int)$char['coins']; ?>" min="0" style="width:180px;">
                            </div>
                        </div>
                    </div>
                    <div class="orv-menu_form-valid">
                        <button type="submit" class="orv-edit">
                            <i class="fa-solid fa-check"></i>
                            <span style="font-size:17px;">Sauvegarder</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- ══════════════════════════════════════════════════════
             Onglet Titres
        ══════════════════════════════════════════════════════ -->
        <div id="cm-titles" class="session-tab-panel <?php echo $activeTab!=='cm-titles' ? 'hidden':''; ?>">
            <!-- Ajouter un titre -->
            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cm-titles">
                <input type="hidden" name="action" value="add_title">
                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Titres du personnage :</h2>
                        <cite>Un seul titre peut être affiché à la fois. Cochez <i class="fa-solid fa-star"></i> pour le définir comme titre affiché.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list" style="align-items:flex-end;">
                            <div class="orv-menu_form-input" style="width:340px;">
                                <label>Nouveau titre</label>
                                <input type="text" name="title" placeholder="Ex : Lecteur, Combattant de la Tour..." required style="width:340px;">
                            </div>
                        </div>
                    </div>
                    <div class="orv-menu_form-valid">
                        <button type="submit" class="orv-edit">
                            <i class="fa-solid fa-plus"></i>
                            <span style="font-size:17px;">Ajouter</span>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Liste des titres -->
            <?php if (!empty($relations['titles'])): ?>
            <table style="margin-top:10px;">
                <thead>
                    <tr>
                        <td>Titre</td>
                        <td width="120px" style="text-align:center;">Affiché</td>
                        <td width="60px">Actions</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($relations['titles'] as $t): ?>
                    <tr>
                        <td><?php echo hv($t['title']); ?></td>
                        <td style="text-align:center;">
                            <?php if ($t['is_displayed']): ?>
                            <span style="color:#FFD97E;"><i class="fa-solid fa-star"></i></span>
                            <?php else: ?>
                            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cm-titles" style="display:inline;">
                                <input type="hidden" name="action" value="set_displayed_title">
                                <input type="hidden" name="title_id" value="<?php echo (int)$t['id']; ?>">
                                <button type="submit" style="background:none; border:none; color:rgba(137,206,255,0.3); cursor:pointer; font-size:16px;" title="Définir comme titre affiché">
                                    <i class="fa-regular fa-star"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cm-titles" style="display:inline;" onsubmit="return confirm('Supprimer ce titre ?');">
                                <input type="hidden" name="action" value="delete_title">
                                <input type="hidden" name="title_id" value="<?php echo (int)$t['id']; ?>">
                                <button type="submit" style="background:none; border:none; color:#FF6B7A; cursor:pointer; font-size:16px;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div style="padding:24px; text-align:center; color:rgba(137,206,255,0.4); font-size:13px;">Aucun titre pour l'instant.</div>
            <?php endif; ?>
        </div>

        <!-- ══════════════════════════════════════════════════════
             Onglet Capacités (Attributs / Compétences / Stigmates)
        ══════════════════════════════════════════════════════ -->
        <div id="cm-capacites" class="session-tab-panel <?php echo $activeTab!=='cm-capacites' ? 'hidden':''; ?>">

            <?php
            $sections = [
                'attributes' => [
                    'label'   => 'Attributs',
                    'icon'    => 'fa-address-card',
                    'action_add'    => 'add_attribute',
                    'action_lock'   => 'toggle_attribute_lock',
                    'action_remove' => 'remove_attribute',
                    'id_field'      => 'id',
                    'available'     => $availAttrs,
                    'data'          => $relations['attributes'],
                ],
                'skills' => [
                    'label'   => 'Compétences Spéciales',
                    'icon'    => 'fa-bolt',
                    'action_add'    => 'add_skill',
                    'action_lock'   => 'toggle_skill_lock',
                    'action_remove' => 'remove_skill',
                    'id_field'      => 'id',
                    'available'     => $availSkills,
                    'data'          => $relations['skills'],
                ],
                'stigmata' => [
                    'label'   => 'Stigmates',
                    'icon'    => 'fa-copy',
                    'action_add'    => 'add_stigma',
                    'action_lock'   => 'toggle_stigma_lock',
                    'action_remove' => 'remove_stigma',
                    'id_field'      => 'id',
                    'available'     => $availStigmata,
                    'data'          => $relations['stigmata'],
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
                <form method="POST" action="<?php echo $baseUrl; ?>&tab=cm-capacites">
                    <input type="hidden" name="action" value="<?php echo $sec['action_add']; ?>">
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list" style="align-items:flex-end;">
                            <div class="orv-menu_form-input" style="width:280px;">
                                <label>Ajouter</label>
                                <select name="entity_uuid" style="width:280px;">
                                    <option value="">— Choisir —</option>
                                    <?php foreach ($sec['available'] as $av): ?>
                                    <option value="<?php echo hv($av['uuid']); ?>"><?php echo hv($av['nom']); ?></option>
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
                        <td><?php echo hv($row['entity_nom'] ?? $row[$key === 'attributes' ? 'attribute_uuid' : ($key === 'skills' ? 'skill_uuid' : 'stigma_uuid')]); ?></td>
                        <td style="text-align:center;">
                            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cm-capacites" style="display:inline;">
                                <input type="hidden" name="action" value="<?php echo $sec['action_lock']; ?>">
                                <input type="hidden" name="relation_id" value="<?php echo (int)$row['id']; ?>">
                                <button type="submit" style="background:none; border:none; cursor:pointer; font-size:16px; color:<?php echo $row['is_locked'] ? '#FFD97E' : 'rgba(137,206,255,0.3)'; ?>;" title="<?php echo $row['is_locked'] ? 'Déverrouiller' : 'Verrouiller'; ?>">
                                    <i class="fa-solid fa-<?php echo $row['is_locked'] ? 'lock' : 'lock-open'; ?>"></i>
                                </button>
                            </form>
                        </td>
                        <td>
                            <?php if (!$row['is_locked']): ?>
                            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cm-capacites" style="display:inline;" onsubmit="return confirm('Retirer cet élément ?');">
                                <input type="hidden" name="action" value="<?php echo $sec['action_remove']; ?>">
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

        <!-- ══════════════════════════════════════════════════════
             Onglet Inventaire
        ══════════════════════════════════════════════════════ -->
        <div id="cm-inventory" class="session-tab-panel <?php echo $activeTab!=='cm-inventory' ? 'hidden':''; ?>">

            <!-- Inventaire global -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Inventaire :</h2>
                    <cite>Objets portés par le personnage avec leurs quantités.</cite>
                </div>

                <?php if (!empty($availItems)): ?>
                <form method="POST" action="<?php echo $baseUrl; ?>&tab=cm-inventory">
                    <input type="hidden" name="action" value="add_inventory">
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list" style="align-items:flex-end;">
                            <div class="orv-menu_form-input" style="width:260px;">
                                <label>Objet</label>
                                <select name="item_uuid" style="width:260px;">
                                    <option value="">— Choisir —</option>
                                    <?php foreach ($availItems as $it): ?>
                                    <option value="<?php echo hv($it['uuid']); ?>"><?php echo hv($it['nom']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="orv-menu_form-input" style="width:100px;">
                                <label>Quantité</label>
                                <input type="number" name="quantity" value="1" min="1" style="width:100px;">
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
                    <i class="fa-solid fa-circle-info"></i> Aucun objet disponible — créez-en dans "Gestion des objets".
                </p>
                <?php endif; ?>
            </div>

            <?php if (!empty($relations['inventory'])): ?>
            <table style="margin-top:8px;">
                <thead><tr><td>Objet</td><td width="140px">Quantité</td><td width="60px">Retirer</td></tr></thead>
                <tbody>
                    <?php foreach ($relations['inventory'] as $inv): ?>
                    <tr>
                        <td><?php echo hv($inv['entity_nom'] ?? $inv['item_uuid']); ?></td>
                        <td>
                            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cm-inventory" style="display:flex; gap:6px; align-items:center;">
                                <input type="hidden" name="action" value="update_inventory_qty">
                                <input type="hidden" name="relation_id" value="<?php echo (int)$inv['id']; ?>">
                                <input type="number" name="quantity" value="<?php echo (int)$inv['quantity']; ?>" min="0" style="width:70px;">
                                <button type="submit" style="background:none; border:none; color:var(--c-accent); cursor:pointer; font-size:16px;" title="Mettre à jour">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cm-inventory" style="display:inline;" onsubmit="return confirm('Retirer cet objet ?');">
                                <input type="hidden" name="action" value="remove_inventory">
                                <input type="hidden" name="relation_id" value="<?php echo (int)$inv['id']; ?>">
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
            <div style="padding:12px 0; color:rgba(137,206,255,0.35); font-size:12px;">Inventaire vide.</div>
            <?php endif; ?>

            <hr>

            <!-- Éléments équipés -->
            <div class="orv-menu_form" style="margin-top:0;">
                <div>
                    <h2 class="classic-title">Éléments équipés :</h2>
                    <cite>Objets actuellement portés sur le personnage (arme, armure, accessoires…).</cite>
                </div>

                <?php if (!empty($availItems)): ?>
                <form method="POST" action="<?php echo $baseUrl; ?>&tab=cm-inventory">
                    <input type="hidden" name="action" value="add_equipped">
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list" style="align-items:flex-end;">
                            <div class="orv-menu_form-input" style="width:260px;">
                                <label>Objet</label>
                                <select name="item_uuid" style="width:260px;">
                                    <option value="">— Choisir —</option>
                                    <?php foreach ($availItems as $it): ?>
                                    <option value="<?php echo hv($it['uuid']); ?>"><?php echo hv($it['nom']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="orv-menu_form-input" style="width:180px;">
                                <label>Emplacement</label>
                                <select name="slot" style="width:180px;">
                                    <option value="">— Aucun —</option>
                                    <option value="Arme principale">Arme principale</option>
                                    <option value="Arme secondaire">Arme secondaire</option>
                                    <option value="Armure">Armure</option>
                                    <option value="Casque">Casque</option>
                                    <option value="Gants">Gants</option>
                                    <option value="Bottes">Bottes</option>
                                    <option value="Accessoire 1">Accessoire 1</option>
                                    <option value="Accessoire 2">Accessoire 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="orv-menu_form-valid">
                            <button type="submit" class="orv-edit" style="padding:8px 16px;">
                                <i class="fa-solid fa-plus"></i> Équiper
                            </button>
                        </div>
                    </div>
                </form>
                <?php endif; ?>
            </div>

            <?php if (!empty($relations['equipped'])): ?>
            <table style="margin-top:8px;">
                <thead><tr><td>Objet</td><td>Emplacement</td><td width="60px">Retirer</td></tr></thead>
                <tbody>
                    <?php foreach ($relations['equipped'] as $eq): ?>
                    <tr>
                        <td><?php echo hv($eq['entity_nom'] ?? $eq['item_uuid']); ?></td>
                        <td style="font-size:12px; color:rgba(137,206,255,0.6);"><?php echo hv($eq['slot'] ?? '—'); ?></td>
                        <td>
                            <form method="POST" action="<?php echo $baseUrl; ?>&tab=cm-inventory" style="display:inline;" onsubmit="return confirm('Déséquiper cet élément ?');">
                                <input type="hidden" name="action" value="remove_equipped">
                                <input type="hidden" name="relation_id" value="<?php echo (int)$eq['id']; ?>">
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
            <div style="padding:12px 0; color:rgba(137,206,255,0.35); font-size:12px;">Aucun élément équipé.</div>
            <?php endif; ?>

        </div>
    </div>
</div>
