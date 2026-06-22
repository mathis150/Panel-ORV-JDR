<?php
    require_once './import/modifiers.php';
    $mm       = new ModifiersManager();
    $uuid     = $_GET['uuid'] ?? '';
    $modifier = $mm->getModifierByUUID($uuid);
    if (!$modifier) {
        echo '<div class="user-notice user-notice--error">Modificateur introuvable.</div>';
        return;
    }
    function mdhv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }
?>

<div class="orv-buttons" style="justify-content:flex-end;">
    <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=modifiers&sub-page=list';">
        <i class="fa-solid fa-arrow-left"></i>
        <span style="font-size:18px;">Annuler</span>
    </button>
</div>

<div class="orv-menu">
    <div class="orv-menu_header">&lt;Suppression d'un modificateur&gt;</div>
    <div class="orv-menu_container">
        <div style="text-align:center; padding:28px 20px;">
            <div style="font-size:40px; color:rgba(239,83,80,0.4); margin-bottom:16px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <p style="color:rgba(217,255,255,0.8); font-size:16px; margin-bottom:10px;">
                Êtes-vous sûr de vouloir supprimer le modificateur
            </p>
            <p style="color:#EF5350; font-size:18px; font-weight:700; margin-bottom:10px;">
                « <?php echo mdhv($modifier['nom']); ?> »
            </p>
            <?php $typeColor = ModifiersManager::TYPE_COLORS[$modifier['type']] ?? '#89CEFF'; ?>
            <?php $typeLabel = ModifiersManager::TYPE_LABELS[$modifier['type']] ?? $modifier['type']; ?>
            <p style="margin-bottom:6px;">
                <span style="padding:2px 10px; border-radius:12px; font-size:11px; font-weight:700;
                             background:<?php echo $typeColor; ?>22; color:<?php echo $typeColor; ?>;
                             border:1px solid <?php echo $typeColor; ?>55;">
                    <?php echo mdhv($typeLabel); ?>
                </span>
            </p>
            <p style="color:rgba(217,255,255,0.35); font-size:12px; margin-bottom:28px;">
                Cette action supprimera également toutes les statistiques et effets associés. Elle est irréversible.
            </p>
            <form method="POST" action="jdr-params?page=modifiers&sub-page=delete&uuid=<?php echo urlencode($uuid); ?>"
                  style="display:inline;">
                <button type="submit"
                        style="background:rgba(239,83,80,0.12); border:1px solid rgba(239,83,80,0.35);
                               color:#EF5350; -webkit-text-fill-color:#EF5350;">
                    <i class="fa-solid fa-trash"></i> Confirmer la suppression
                </button>
            </form>
        </div>
    </div>
</div>
