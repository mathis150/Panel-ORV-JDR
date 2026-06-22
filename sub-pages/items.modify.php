<?php
    require_once './import/items.php';
    $im   = new ItemsManager();
    $uuid = $_GET['uuid'] ?? '';
    $tab  = $_GET['tab']  ?? 'item-info';
    $item = $im->getItemByUUID($uuid);

    if (!$item) {
        echo '<div class="user-notice user-notice--error">Objet introuvable.</div>';
        return;
    }

    $fd       = $formData ?? [];
    $baseUrl  = 'jdr-params?page=items&sub-page=modify&uuid=' . urlencode($uuid);
    $stats    = $im->getItemStats($uuid);

    $tColor   = ItemsManager::TYPE_COLORS[$item['type']] ?? '#7F8C8D';
    $tIcon    = ItemsManager::TYPE_ICONS[$item['type']]  ?? 'fa-box';
    $tLabel   = ItemsManager::TYPE_LABELS[$item['type']] ?? $item['type'];
    $rColor   = ItemsManager::RANG_COLORS[$item['rang']] ?? '#9E9E9E';
    $rLabel   = ItemsManager::RANG_LABELS[$item['rang']] ?? $item['rang'];

    function imhv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }
?>

<?php if ($notice ?? ''): ?>
<div class="user-notice" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-check"></i> <?php echo $notice ?? ''; ?>
</div>
<?php endif; ?>
<?php if ($formError ?? null): ?>
<div class="user-notice user-notice--error" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-xmark"></i> <?php echo htmlspecialchars($formError); ?>
</div>
<?php endif; ?>

<div class="orv-buttons" style="justify-content:flex-end;">
    <button type="button" class="orv-edit"
            onclick="window.location.href='jdr-params?page=items&sub-page=list';">
        <i class="fa-solid fa-arrow-left"></i>
        <span style="font-size:18px;">Retour à la liste</span>
    </button>
    <a href="jdr-params?page=items&sub-page=delete&uuid=<?php echo urlencode($uuid); ?>"
       class="orv-edit" style="text-decoration:none; background:rgba(239,83,80,0.12);
              border:1px solid rgba(239,83,80,0.3); color:#EF5350; -webkit-text-fill-color:#EF5350;">
        <i class="fa-solid fa-trash"></i>
        <span style="font-size:18px;">Supprimer</span>
    </a>
</div>

<div class="orv-menu">
    <div class="orv-menu_header" style="display:flex; align-items:center; gap:12px;">
        <span style="width:32px; height:32px; border-radius:7px; display:flex; align-items:center; justify-content:center;
                     background:<?php echo $tColor; ?>22; color:<?php echo $tColor; ?>; font-size:14px; flex-shrink:0;">
            <i class="fa-solid <?php echo $tIcon; ?>"></i>
        </span>
        <span>&lt;<?php echo imhv($item['nom']); ?>&gt;</span>
        <span style="padding:2px 8px; border-radius:8px; font-size:11px; font-weight:800;
                     background:<?php echo $rColor; ?>18; color:<?php echo $rColor; ?>;
                     border:1px solid <?php echo $rColor; ?>38; letter-spacing:.04em;">
            <?php echo imhv($rLabel); ?>
        </span>
    </div>

    <!-- Onglets -->
    <div style="display:flex; gap:0; border-bottom:1px solid rgba(55,174,254,0.12); background:rgba(3,8,22,0.3);">
        <button type="button" class="session-tab <?php echo $tab === 'item-info'  ? 'button-actif' : ''; ?>"
                data-target="item-info"
                style="padding:12px 22px; border:none; border-bottom:2px solid transparent; cursor:pointer;
                       background:none; font-size:13px; font-weight:600; color:rgba(137,206,255,0.6);
                       <?php echo $tab === 'item-info' ? 'color:rgba(217,255,255,0.9); border-bottom-color:rgba(55,174,254,0.7);' : ''; ?>">
            <i class="fa-solid fa-circle-info" style="margin-right:6px;"></i>Informations
        </button>
        <button type="button" class="session-tab <?php echo $tab === 'item-stats' ? 'button-actif' : ''; ?>"
                data-target="item-stats"
                style="padding:12px 22px; border:none; border-bottom:2px solid transparent; cursor:pointer;
                       background:none; font-size:13px; font-weight:600; color:rgba(137,206,255,0.6);
                       <?php echo $tab === 'item-stats' ? 'color:rgba(217,255,255,0.9); border-bottom-color:rgba(55,174,254,0.7);' : ''; ?>">
            <i class="fa-solid fa-chart-bar" style="margin-right:6px;"></i>Statistiques
            <?php if (!empty($stats)): ?>
            <span style="display:inline-flex; align-items:center; justify-content:center; width:18px; height:18px;
                         border-radius:50%; background:rgba(171,71,188,0.25); color:#AB47BC;
                         font-size:10px; font-weight:800; margin-left:5px;">
                <?php echo count($stats); ?>
            </span>
            <?php endif; ?>
        </button>
    </div>

    <div class="orv-menu_container">

        <!-- ═══════════════ TAB : Informations ═══════════════ -->
        <div class="tab-content" id="item-info"
             style="<?php echo $tab !== 'item-info' ? 'display:none;' : ''; ?>">

            <form method="POST" action="<?php echo $baseUrl; ?>&tab=item-info">
                <input type="hidden" name="action" value="update">

                <div style="display:flex; justify-content:flex-end; margin-bottom:20px;">
                    <button type="submit" class="orv-edit">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span style="font-size:16px;">Sauvegarder</span>
                    </button>
                </div>

                <!-- Identité -->
                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Identité :</h2>
                        <cite>Nom, type et rang.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">

                            <div class="orv-menu_form-input" style="width:340px;">
                                <label>Nom <span style="color:#FF6B7A;">*</span></label>
                                <input type="text" name="nom" required
                                       value="<?php echo imhv(!empty($fd) ? ($fd['nom'] ?? '') : $item['nom']); ?>"
                                       style="width:340px;">
                            </div>

                            <div class="orv-menu_form-input" style="width:200px;">
                                <label>Type</label>
                                <select name="type" style="width:200px;">
                                    <?php
                                    $curType = !empty($fd) ? ($fd['type'] ?? $item['type']) : $item['type'];
                                    foreach (ItemsManager::TYPES as $t):
                                        $tc = ItemsManager::TYPE_COLORS[$t];
                                        $tl = ItemsManager::TYPE_LABELS[$t];
                                    ?>
                                    <option value="<?php echo imhv($t); ?>"
                                        <?php echo $curType === $t ? 'selected' : ''; ?>
                                        style="color:<?php echo $tc; ?>">
                                        <?php echo imhv($tl); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="orv-menu_form-input" style="width:180px;">
                                <label>Rang</label>
                                <select name="rang" style="width:180px;">
                                    <?php
                                    $curRang = !empty($fd) ? ($fd['rang'] ?? $item['rang']) : $item['rang'];
                                    foreach (ItemsManager::RANGS as $r):
                                        $rc = ItemsManager::RANG_COLORS[$r];
                                        $rl = ItemsManager::RANG_LABELS[$r];
                                    ?>
                                    <option value="<?php echo imhv($r); ?>"
                                        <?php echo $curRang === $r ? 'selected' : ''; ?>
                                        style="color:<?php echo $rc; ?>">
                                        <?php echo imhv($rl); ?>
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
                        <cite>Lore et effets de l'objet.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:100%;">
                                <label>Description</label>
                                <textarea name="description" rows="5"
                                          style="width:100%; resize:vertical;"><?php echo imhv(!empty($fd) ? ($fd['description'] ?? '') : $item['description']); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Statut -->
                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Statut :</h2>
                        <cite>Visibilité dans le catalogue.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div style="display:flex; align-items:center; gap:8px; padding-top:6px;">
                                <input type="checkbox" name="is_active" id="im_active" value="1"
                                       <?php echo (!empty($fd) ? !empty($fd['is_active']) : (bool)$item['is_active']) ? 'checked' : ''; ?>>
                                <label for="im_active" style="cursor:pointer;">
                                    <i class="fa-solid fa-circle-check" style="color:#2ECC71;"></i> Actif dans le catalogue
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <!-- ═══════════════ TAB : Statistiques ═══════════════ -->
        <div class="tab-content" id="item-stats"
             style="<?php echo $tab !== 'item-stats' ? 'display:none;' : ''; ?>">

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:28px;">

                <!-- Liste des stats actuelles -->
                <div>
                    <h2 class="classic-title" style="margin-bottom:14px;">
                        Statistiques actuelles
                        <span style="font-size:12px; font-weight:400; color:rgba(137,206,255,0.4); margin-left:6px;">
                            (<?php echo count($stats); ?> entrée<?php echo count($stats) !== 1 ? 's' : ''; ?>)
                        </span>
                    </h2>

                    <?php if (empty($stats)): ?>
                    <div style="padding:20px; text-align:center; color:rgba(137,206,255,0.3);
                                font-style:italic; font-size:13px; background:rgba(3,8,22,0.4);
                                border-radius:8px; border:1px dashed rgba(55,174,254,0.1);">
                        Aucune statistique définie.
                    </div>
                    <?php else: ?>
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <?php foreach ($stats as $stat):
                            $sc = ItemsManager::STAT_COLORS[$stat['stat_type']] ?? '#9E9E9E';
                            $si = ItemsManager::STAT_ICONS[$stat['stat_type']]  ?? 'fa-circle';
                            $sl = ItemsManager::STAT_LABELS[$stat['stat_type']] ?? $stat['stat_type'];
                        ?>
                        <div style="display:flex; align-items:center; gap:10px; padding:8px 14px;
                                    background:rgba(3,8,22,0.5); border:1px solid <?php echo $sc; ?>25;
                                    border-left:3px solid <?php echo $sc; ?>; border-radius:8px;">
                            <span style="width:28px; height:28px; border-radius:6px; flex-shrink:0;
                                         display:flex; align-items:center; justify-content:center;
                                         background:<?php echo $sc; ?>18; color:<?php echo $sc; ?>; font-size:12px;">
                                <i class="fa-solid <?php echo $si; ?>"></i>
                            </span>
                            <span style="font-size:13px; font-weight:600; color:<?php echo $sc; ?>; flex:1;">
                                <?php echo imhv($sl); ?>
                            </span>
                            <span style="font-size:15px; font-weight:800; color:rgba(217,255,255,0.85);">
                                +<?php echo (int)$stat['valeur']; ?>
                            </span>
                            <form method="POST" action="<?php echo $baseUrl; ?>&tab=item-stats"
                                  style="margin:0; flex-shrink:0;">
                                <input type="hidden" name="action"  value="remove_stat">
                                <input type="hidden" name="stat_id" value="<?php echo (int)$stat['id']; ?>">
                                <button type="submit"
                                        style="background:none; border:none; cursor:pointer; padding:3px 5px;
                                               color:rgba(239,83,80,0.4); font-size:13px;"
                                        onmouseover="this.style.color='#EF5350'"
                                        onmouseout="this.style.color='rgba(239,83,80,0.4)'"
                                        title="Retirer cette statistique">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Formulaire d'ajout -->
                <div>
                    <h2 class="classic-title" style="margin-bottom:14px;">Ajouter une statistique</h2>

                    <form method="POST" action="<?php echo $baseUrl; ?>&tab=item-stats"
                          style="background:rgba(3,8,22,0.4); border:1px solid rgba(55,174,254,0.1);
                                 border-radius:10px; padding:18px;">
                        <input type="hidden" name="action" value="add_stat">

                        <div class="orv-menu_form-inputs-list" style="flex-direction:column; gap:14px;">

                            <div class="orv-menu_form-input" style="width:100%;">
                                <label>Statistique</label>
                                <select name="stat_type" style="width:100%;" id="stat_type_select"
                                        onchange="updateStatPreview(this.value)">
                                    <?php foreach (ItemsManager::STAT_TYPES as $st):
                                        $sc = ItemsManager::STAT_COLORS[$st];
                                        $sl = ItemsManager::STAT_LABELS[$st];
                                        $si = ItemsManager::STAT_ICONS[$st];
                                    ?>
                                    <option value="<?php echo imhv($st); ?>"
                                            data-color="<?php echo $sc; ?>"
                                            data-icon="<?php echo $si; ?>"
                                            style="color:<?php echo $sc; ?>">
                                        <i class="fa-solid <?php echo $si; ?>"></i>
                                        <?php echo imhv($sl); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="orv-menu_form-input" style="width:100%;">
                                <label>Valeur (bonus) <span style="color:#FF6B7A;">*</span></label>
                                <input type="number" name="valeur" id="stat_valeur"
                                       min="1" max="99999" value="10" required style="width:100%;">
                            </div>

                            <button type="submit" class="orv-edit" style="width:100%; justify-content:center;">
                                <i class="fa-solid fa-plus"></i>
                                <span style="font-size:15px;">Ajouter</span>
                            </button>

                        </div>
                    </form>

                    <!-- Aperçu -->
                    <div id="stat_preview"
                         style="margin-top:12px; padding:10px 14px; border-radius:8px;
                                background:rgba(239,83,80,0.06); border:1px solid rgba(239,83,80,0.2);
                                display:flex; align-items:center; gap:10px; font-size:13px;">
                        <i class="fa-solid fa-hand-fist" id="prev_icon" style="color:#EF5350; font-size:16px;"></i>
                        <span style="color:rgba(137,206,255,0.6);">Aperçu :</span>
                        <span id="prev_label" style="color:#EF5350; font-weight:700;">Force</span>
                        <span style="color:rgba(217,255,255,0.8); font-weight:800;">
                            +<span id="prev_val">10</span>
                        </span>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
// Tab switching
document.querySelectorAll('.session-tab').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var target = this.dataset.target;
        document.querySelectorAll('.tab-content').forEach(function(t) {
            t.style.display = 'none';
        });
        document.getElementById(target).style.display = '';
        document.querySelectorAll('.session-tab').forEach(function(b) {
            b.style.color = 'rgba(137,206,255,0.6)';
            b.style.borderBottomColor = 'transparent';
        });
        this.style.color = 'rgba(217,255,255,0.9)';
        this.style.borderBottomColor = 'rgba(55,174,254,0.7)';
    });
});

// Stat preview update
var statLabels = <?php echo json_encode(ItemsManager::STAT_LABELS); ?>;
function updateStatPreview(val) {
    var sel  = document.getElementById('stat_type_select');
    var opt  = sel.options[sel.selectedIndex];
    var col  = opt.dataset.color || '#9E9E9E';
    var icon = opt.dataset.icon  || 'fa-circle';
    var lbl  = statLabels[val]   || val;
    var num  = document.getElementById('stat_valeur').value || '0';

    document.getElementById('stat_preview').style.background  = col + '10';
    document.getElementById('stat_preview').style.borderColor = col + '35';
    document.getElementById('prev_icon').className = 'fa-solid ' + icon;
    document.getElementById('prev_icon').style.color = col;
    document.getElementById('prev_label').style.color = col;
    document.getElementById('prev_label').textContent = lbl;
    document.getElementById('prev_val').textContent   = num;
}
document.getElementById('stat_valeur').addEventListener('input', function() {
    document.getElementById('prev_val').textContent = this.value || '0';
});
</script>
