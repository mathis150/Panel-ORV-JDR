<?php
    require_once './import/stigmates.php';
    $sm   = new StigmataManager();
    $uuid = $_GET['uuid'] ?? '';
    $s    = $sm->getStigmaByUUID($uuid);

    if (!$s) {
        echo '<div class="user-notice user-notice--error">Stigmate introuvable.</div>';
        return;
    }
?>

<form method="POST" action="jdr-params?page=stigmata&sub-page=delete&uuid=<?php echo urlencode($uuid); ?>">
    <div class="orv-buttons" style="justify-content:flex-end;">
        <button type="submit" class="orv-edit" style="background:linear-gradient(135deg,#8B2020,#C0392B);">
            <i class="fa-solid fa-trash"></i>
            <span style="font-size:18px;">Confirmer la suppression</span>
        </button>
        <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=stigmata&sub-page=list';">
            <i class="fa-solid fa-xmark"></i>
            <span style="font-size:18px;">Annuler</span>
        </button>
    </div>

    <div class="orv-menu">
        <div class="orv-menu_header">&lt;Suppression d'un stigmate&gt;</div>
        <div class="orv-menu_container">
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Attention :</h2>
                    <br>
                    <div class="user-notice user-notice--error" style="margin-bottom:20px;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        Cette action est <strong>irréversible</strong>. Le stigmate, ses effets et toutes ses conditions de level-up seront définitivement supprimés.
                    </div>
                    <table style="width:auto; margin-top:8px;">
                        <tbody>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Nom</td>
                                <td style="font-weight:700;"><?php echo htmlspecialchars($s['nom']); ?></td>
                            </tr>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Rang</td>
                                <td>Rang <?php echo htmlspecialchars($s['rang']); ?></td>
                            </tr>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Créé le</td>
                                <td><?php echo date('d/m/Y à H:i', strtotime($s['created_at'])); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
