<?php
    require_once './import/items.php';
    $im   = new ItemsManager();
    $uuid = $_GET['uuid'] ?? '';
    $item = $im->getItemByUUID($uuid);

    if (!$item) {
        echo '<div class="user-notice user-notice--error">Objet introuvable.</div>';
        return;
    }

    $tColor = ItemsManager::TYPE_COLORS[$item['type']] ?? '#7F8C8D';
    $tIcon  = ItemsManager::TYPE_ICONS[$item['type']]  ?? 'fa-box';
    $tLabel = ItemsManager::TYPE_LABELS[$item['type']] ?? $item['type'];
    $rColor = ItemsManager::RANG_COLORS[$item['rang']] ?? '#9E9E9E';
    $rLabel = ItemsManager::RANG_LABELS[$item['rang']] ?? $item['rang'];

    function idhv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }
?>

<div class="orv-buttons" style="justify-content:flex-end;">
    <button type="button" class="orv-edit"
            onclick="window.location.href='jdr-params?page=items&sub-page=modify&uuid=<?php echo urlencode($uuid); ?>';">
        <i class="fa-solid fa-arrow-left"></i>
        <span style="font-size:18px;">Annuler</span>
    </button>
</div>

<div class="orv-menu">
    <div class="orv-menu_header">&lt;Supprimer un objet&gt;</div>
    <div class="orv-menu_container">
        <div style="text-align:center; padding:32px 24px;">

            <div style="font-size:44px; color:rgba(239,83,80,0.35); margin-bottom:18px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <p style="color:rgba(217,255,255,0.7); font-size:15px; margin-bottom:18px;">
                Êtes-vous sûr de vouloir supprimer définitivement cet objet ?
            </p>

            <!-- Aperçu de l'objet -->
            <div style="display:inline-flex; align-items:center; gap:14px; padding:14px 24px;
                        background:rgba(239,83,80,0.05); border:1px solid rgba(239,83,80,0.18);
                        border-radius:12px; margin-bottom:18px; text-align:left;">
                <span style="width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center;
                             background:<?php echo $tColor; ?>22; color:<?php echo $tColor; ?>; font-size:18px; flex-shrink:0;">
                    <i class="fa-solid <?php echo $tIcon; ?>"></i>
                </span>
                <div>
                    <div style="font-size:15px; font-weight:700; color:#EF5350; margin-bottom:5px;">
                        <?php echo idhv($item['nom']); ?>
                    </div>
                    <div style="display:flex; gap:6px;">
                        <span style="padding:1px 8px; border-radius:8px; font-size:10px; font-weight:700;
                                     background:<?php echo $tColor; ?>18; color:<?php echo $tColor; ?>;
                                     border:1px solid <?php echo $tColor; ?>40;">
                            <?php echo idhv($tLabel); ?>
                        </span>
                        <span style="padding:1px 8px; border-radius:8px; font-size:11px; font-weight:800;
                                     background:<?php echo $rColor; ?>18; color:<?php echo $rColor; ?>;
                                     border:1px solid <?php echo $rColor; ?>40; letter-spacing:.04em;">
                            <?php echo idhv($rLabel); ?>
                        </span>
                    </div>
                </div>
            </div>

            <p style="color:rgba(239,83,80,0.55); font-size:12px; margin-bottom:10px;">
                <i class="fa-solid fa-exclamation-circle"></i>
                Cette action est <strong>irréversible</strong>.
                Les statistiques associées seront également supprimées.
            </p>
            <p style="color:rgba(137,206,255,0.35); font-size:12px; margin-bottom:28px;">
                Si l'objet est dans le shop du Dokkaebi, sa mise en vente sera retirée automatiquement.
            </p>

            <form method="POST" action="jdr-params?page=items&sub-page=delete&uuid=<?php echo urlencode($uuid); ?>"
                  style="display:inline;">
                <button type="submit"
                        style="background:rgba(239,83,80,0.12); border:1px solid rgba(239,83,80,0.35);
                               color:#EF5350; -webkit-text-fill-color:#EF5350; padding:10px 24px;
                               border-radius:var(--r-md); cursor:pointer; font-size:14px; font-weight:600;
                               display:inline-flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-trash"></i> Supprimer définitivement
                </button>
            </form>

        </div>
    </div>
</div>
