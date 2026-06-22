<?php
    require_once './import/dokkaebi_shop.php';
    $dsm = new DokkaebiShopManager();
    $id  = (int)($_GET['id'] ?? 0);
    $lst = $dsm->getShopListingById($id);
    if (!$lst) {
        echo '<div class="user-notice user-notice--error">Mise en vente introuvable.</div>';
        return;
    }
    function dbdhv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }
    $itColor = DokkaebiShopManager::ITEM_TYPE_COLORS[$lst['item_type']] ?? '#7F8C8D';
    $itIcon  = DokkaebiShopManager::ITEM_TYPE_ICONS[$lst['item_type']]  ?? 'fa-box';
    $itLabel = DokkaebiShopManager::ITEM_TYPE_LABELS[$lst['item_type']] ?? $lst['item_type'];
    $rgColor = DokkaebiShopManager::RANG_COLORS[$lst['item_rang']]      ?? '#9E9E9E';
?>

<div class="orv-buttons" style="justify-content:flex-end;">
    <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=dokkaebi_bag&sub-page=list';">
        <i class="fa-solid fa-arrow-left"></i>
        <span style="font-size:18px;">Annuler</span>
    </button>
</div>

<div class="orv-menu">
    <div class="orv-menu_header">&lt;Retirer un objet du shop&gt;</div>
    <div class="orv-menu_container">
        <div style="text-align:center; padding:28px 20px;">
            <div style="font-size:40px; color:rgba(239,83,80,0.4); margin-bottom:16px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <p style="color:rgba(217,255,255,0.7); font-size:15px; margin-bottom:14px;">
                Êtes-vous sûr de vouloir retirer cet objet du shop ?
            </p>

            <!-- Aperçu de l'objet -->
            <div style="display:inline-flex; align-items:center; gap:12px; padding:12px 20px;
                        background:rgba(239,83,80,0.06); border:1px solid rgba(239,83,80,0.2);
                        border-radius:10px; margin-bottom:18px;">
                <span style="width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center;
                             background:<?php echo $itColor; ?>22; color:<?php echo $itColor; ?>; font-size:16px; flex-shrink:0;">
                    <i class="fa-solid <?php echo $itIcon; ?>"></i>
                </span>
                <div style="text-align:left;">
                    <div style="font-size:15px; font-weight:700; color:#EF5350;"><?php echo dbdhv($lst['nom']); ?></div>
                    <div style="display:flex; gap:5px; margin-top:3px;">
                        <span style="padding:1px 6px; border-radius:7px; font-size:10px; font-weight:700;
                                     background:<?php echo $itColor; ?>18; color:<?php echo $itColor; ?>; border:1px solid <?php echo $itColor; ?>40;">
                            <?php echo dbdhv($itLabel); ?>
                        </span>
                        <span style="padding:1px 6px; border-radius:7px; font-size:10px; font-weight:800;
                                     background:<?php echo $rgColor; ?>18; color:<?php echo $rgColor; ?>; border:1px solid <?php echo $rgColor; ?>40; letter-spacing:.04em;">
                            <?php echo dbdhv($lst['item_rang']); ?>
                        </span>
                    </div>
                </div>
                <div style="margin-left:8px; font-size:16px; font-weight:800; color:#F5D020;">
                    <?php echo number_format((int)$lst['prix'], 0, ',', ' '); ?> C
                </div>
            </div>

            <p style="color:rgba(217,255,255,0.3); font-size:12px; margin-bottom:28px;">
                L'objet restera dans le catalogue mais ne sera plus disponible à la vente.<br>
                Cette action est réversible (vous pourrez le remettre en vente).
            </p>
            <form method="POST" action="jdr-params?page=dokkaebi_bag&sub-page=delete&id=<?php echo $id; ?>"
                  style="display:inline;">
                <button type="submit"
                        style="background:rgba(239,83,80,0.12); border:1px solid rgba(239,83,80,0.35);
                               color:#EF5350; -webkit-text-fill-color:#EF5350;">
                    <i class="fa-solid fa-store-slash"></i> Retirer du shop
                </button>
            </form>
        </div>
    </div>
</div>
