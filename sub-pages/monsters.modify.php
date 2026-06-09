<?php
    require_once './import/monsters.php';
    $mm      = new MonstersManager();
    $uuid    = $_GET['uuid'] ?? '';
    $monster = $mm->getMonsterByUUID($uuid);

    if (!$monster) {
        echo '<div class="user-notice user-notice--error">Monstre introuvable.</div>';
        return;
    }

    $equipped   = $mm->getMonsterEquipped($uuid);
    $availItems = $mm->getAvailableItems();

    $validTabs = ['mm-identity', 'mm-stats', 'mm-equipment'];
    $activeTab = in_array($_GET['tab'] ?? '', $validTabs) ? $_GET['tab'] : 'mm-identity';
    $fd        = $formData ?? [];

    $baseUrl = 'jdr-params?page=monsters&sub-page=modify&uuid=' . urlencode($uuid);

    function mhv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }

    $rankOpts = [
        1 => 'Rang 1 — Le plus puissant',
        2 => 'Rang 2', 3 => 'Rang 3', 4 => 'Rang 4', 5 => 'Rang 5',
        6 => 'Rang 6', 7 => 'Rang 7', 8 => 'Rang 8',
        9 => 'Rang 9 — Le plus faible',
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
    <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=monsters&sub-page=list';">
        <i class="fa-solid fa-arrow-left"></i>
        <span style="font-size:18px;">Retour à la liste</span>
    </button>
</div>

<div class="orv-menu">
    <div class="orv-menu_header">&lt;<?php echo mhv($monster['nom']); ?>&gt;</div>

    <!-- Onglets -->
    <div class="session-tabs" style="padding:0 24px;">
        <button type="button" class="session-tab <?php echo $activeTab==='mm-identity'  ? 'button-actif':''; ?>" data-target="mm-identity">
            <i class="fa-solid fa-id-card"></i> Identité
        </button>
        <button type="button" class="session-tab <?php echo $activeTab==='mm-stats'     ? 'button-actif':''; ?>" data-target="mm-stats">
            <i class="fa-solid fa-chart-simple"></i> Statistiques
        </button>
        <button type="button" class="session-tab <?php echo $activeTab==='mm-equipment' ? 'button-actif':''; ?>" data-target="mm-equipment">
            <i class="fa-solid fa-shield-halved"></i> Équipement
        </button>
    </div>

    <div class="orv-menu_container">

        <!-- ══════════════════════════════════════════════════════
             Onglet Identité
        ══════════════════════════════════════════════════════ -->
        <div id="mm-identity" class="session-tab-panel <?php echo $activeTab!=='mm-identity' ? 'hidden':''; ?>">
            <form method="POST" action="<?php echo $baseUrl; ?>&tab=mm-identity">
                <input type="hidden" name="action" value="update">

                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Identité :</h2>
                        <cite>Informations générales du monstre.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:260px;">
                                <label>Nom <span style="color:#FF6B7A;">*</span></label>
                                <input type="text" name="nom" value="<?php echo mhv($fd['nom'] ?? $monster['nom']); ?>" required style="width:260px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:200px;">
                                <label>Race</label>
                                <input type="text" name="race" value="<?php echo mhv($fd['race'] ?? $monster['race']); ?>" style="width:200px;" placeholder="ex: Gobelin, Orc…">
                            </div>
                            <div class="orv-menu_form-input" style="width:200px;">
                                <label>Rang</label>
                                <select name="rank" style="width:200px;">
                                    <?php
                                    $selRank = (int)($fd['rank'] ?? $monster['rank']);
                                    foreach ($rankOpts as $val => $lbl):
                                    ?>
                                    <option value="<?php echo $val; ?>" <?php echo $selRank === $val ? 'selected' : ''; ?>>
                                        <?php echo $lbl; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="orv-menu_form-input" style="width:160px;">
                                <label>Statut</label>
                                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; margin-top:8px;">
                                    <input type="checkbox" name="is_active" value="1" <?php echo ($fd['is_active'] ?? $monster['is_active']) ? 'checked' : ''; ?>>
                                    <span style="font-size:13px;">Actif</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Description :</h2>
                        <cite>Apparence et comportement du monstre.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:100%; max-width:700px;">
                                <label>Description</label>
                                <textarea name="description" rows="5" style="width:100%;"><?php echo mhv($fd['description'] ?? $monster['description']); ?></textarea>
                            </div>
                            <div class="orv-menu_form-input" style="width:100%; max-width:700px;">
                                <label>Notes MJ</label>
                                <textarea name="notes" rows="3" style="width:100%;"><?php echo mhv($fd['notes'] ?? $monster['notes']); ?></textarea>
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
             Onglet Statistiques
        ══════════════════════════════════════════════════════ -->
        <div id="mm-stats" class="session-tab-panel <?php echo $activeTab!=='mm-stats' ? 'hidden':''; ?>">
            <form method="POST" action="<?php echo $baseUrl; ?>&tab=mm-stats">
                <input type="hidden" name="action" value="update">
                <!-- Transmet aussi les champs non-stats pour ne pas les écraser -->
                <input type="hidden" name="nom"         value="<?php echo mhv($monster['nom']); ?>">
                <input type="hidden" name="race"        value="<?php echo mhv($monster['race']); ?>">
                <input type="hidden" name="rank"        value="<?php echo (int)$monster['rank']; ?>">
                <input type="hidden" name="description" value="<?php echo mhv($monster['description']); ?>">
                <input type="hidden" name="notes"       value="<?php echo mhv($monster['notes']); ?>">
                <?php if ($monster['is_active']): ?>
                <input type="hidden" name="is_active" value="1">
                <?php endif; ?>

                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Statistiques :</h2>
                        <cite>Valeurs de combat du monstre.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:140px;">
                                <label>Vitalité</label>
                                <input type="number" name="vitalite" value="<?php echo (int)($fd['vitalite'] ?? $monster['vitalite']); ?>" min="0" style="width:140px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:140px;">
                                <label>Force</label>
                                <input type="number" name="force" value="<?php echo (int)($fd['force'] ?? $monster['force']); ?>" min="0" style="width:140px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:140px;">
                                <label>Agilité</label>
                                <input type="number" name="agilite" value="<?php echo (int)($fd['agilite'] ?? $monster['agilite']); ?>" min="0" style="width:140px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:140px;">
                                <label>Mana</label>
                                <input type="number" name="mana" value="<?php echo (int)($fd['mana'] ?? $monster['mana']); ?>" min="0" style="width:140px;">
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
             Onglet Équipement
        ══════════════════════════════════════════════════════ -->
        <div id="mm-equipment" class="session-tab-panel <?php echo $activeTab!=='mm-equipment' ? 'hidden':''; ?>">

            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title"><i class="fa-solid fa-shield-halved"></i> Équipement :</h2>
                    <cite>Objets équipés par le monstre.</cite>
                </div>

                <?php if (!empty($availItems)): ?>
                <form method="POST" action="<?php echo $baseUrl; ?>&tab=mm-equipment">
                    <input type="hidden" name="action" value="add_equipped">
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list" style="align-items:flex-end;">
                            <div class="orv-menu_form-input" style="width:260px;">
                                <label>Objet</label>
                                <select name="item_uuid" style="width:260px;">
                                    <option value="">— Choisir —</option>
                                    <?php foreach ($availItems as $it): ?>
                                    <option value="<?php echo mhv($it['uuid']); ?>"><?php echo mhv($it['nom']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="orv-menu_form-input" style="width:160px;">
                                <label>Emplacement</label>
                                <input type="text" name="slot" placeholder="ex: Main droite" style="width:160px;">
                            </div>
                            <div class="orv-menu_form-input" style="width:90px;">
                                <label>Qté</label>
                                <input type="number" name="quantity" value="1" min="1" style="width:90px;">
                            </div>
                        </div>
                        <div class="orv-menu_form-valid">
                            <button type="submit" class="orv-edit" style="padding:8px 16px;">
                                <i class="fa-solid fa-plus"></i> Équiper
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

            <?php if (!empty($equipped)): ?>
            <table style="margin-top:8px;">
                <thead>
                    <tr>
                        <td>Objet</td>
                        <td>Emplacement</td>
                        <td style="text-align:center; width:90px;">Quantité</td>
                        <td width="60px">Retirer</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($equipped as $eq): ?>
                    <tr>
                        <td><?php echo mhv($eq['entity_nom'] ?? $eq['item_uuid']); ?></td>
                        <td style="font-size:12px; color:rgba(137,206,255,0.6);">
                            <?php echo $eq['slot'] ? mhv($eq['slot']) : '<span style="opacity:0.4;">—</span>'; ?>
                        </td>
                        <td style="text-align:center;">
                            <form method="POST" action="<?php echo $baseUrl; ?>&tab=mm-equipment" style="display:inline-flex; align-items:center; gap:4px;">
                                <input type="hidden" name="action"      value="update_equipped_qty">
                                <input type="hidden" name="relation_id" value="<?php echo (int)$eq['id']; ?>">
                                <input type="number" name="quantity" value="<?php echo (int)$eq['quantity']; ?>" min="0"
                                       style="width:60px; text-align:center;" onchange="this.form.submit()">
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="<?php echo $baseUrl; ?>&tab=mm-equipment" style="display:inline;"
                                  onsubmit="return confirm('Retirer cet équipement ?');">
                                <input type="hidden" name="action"      value="remove_equipped">
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
            <div style="padding:12px 0 20px; color:rgba(137,206,255,0.35); font-size:12px;">Aucun équipement ajouté.</div>
            <?php endif; ?>

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
