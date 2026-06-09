<?php
    require_once './import/attributs.php';
    $am   = new AttributesManager();
    $uuid = $_GET['uuid'] ?? '';
    $attr = $am->getAttributeByUUID($uuid);

    if (!$attr) {
        echo '<div class="user-notice user-notice--error">Attribut introuvable.</div>';
        return;
    }

    $color = AttributesManager::RANG_COLORS[$attr['rang']] ?? '#89CEFF';
?>

<form method="POST" action="jdr-params?page=attributes&sub-page=delete&uuid=<?php echo urlencode($uuid); ?>">
    <div class="orv-buttons" style="justify-content:flex-end;">
        <button type="submit" class="orv-edit" style="background:linear-gradient(135deg,#8B2020,#C0392B);">
            <i class="fa-solid fa-trash"></i>
            <span style="font-size:18px;">Confirmer la suppression</span>
        </button>
        <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=attributes&sub-page=list';">
            <i class="fa-solid fa-xmark"></i>
            <span style="font-size:18px;">Annuler</span>
        </button>
    </div>

    <div class="orv-menu">
        <div class="orv-menu_header">&lt;Suppression d'un attribut&gt;</div>
        <div class="orv-menu_container">
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Attention :</h2>
                    <br>
                    <div class="user-notice user-notice--error" style="margin-bottom:20px;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        Cette action est <strong>irréversible</strong>. L'attribut et tous ses effets seront définitivement supprimés.
                    </div>
                    <table style="width:auto; margin-top:8px;">
                        <tbody>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Nom</td>
                                <td style="font-weight:700;"><?php echo htmlspecialchars($attr['nom']); ?></td>
                            </tr>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Rang</td>
                                <td>
                                    <span style="display:inline-block; padding:2px 10px; border-radius:12px; font-size:11px; font-weight:700;
                                                 background:<?php echo $color; ?>22; color:<?php echo $color; ?>; border:1px solid <?php echo $color; ?>55;">
                                        <?php echo htmlspecialchars($attr['rang']); ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Créé le</td>
                                <td><?php echo date('d/m/Y à H:i', strtotime($attr['created_at'])); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
