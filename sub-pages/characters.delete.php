<?php
    require_once './import/characters.php';
    $cm   = new CharactersManager();
    $uuid = $_GET['uuid'] ?? '';
    $char = $cm->getCharacterByUUID($uuid);

    if (!$char) {
        echo '<div class="user-notice user-notice--error">Personnage introuvable.</div>';
        return;
    }
?>

<form method="POST" action="jdr-params?page=characters&sub-page=delete&uuid=<?php echo urlencode($uuid); ?>">
    <div class="orv-buttons" style="justify-content:flex-end;">
        <button type="submit" class="orv-edit" style="background:linear-gradient(135deg,#8B2020,#C0392B);">
            <i class="fa-solid fa-trash"></i>
            <span style="font-size:18px;">Confirmer la suppression</span>
        </button>
        <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=characters&sub-page=list';">
            <i class="fa-solid fa-xmark"></i>
            <span style="font-size:18px;">Annuler</span>
        </button>
    </div>

    <div class="orv-menu">
        <div class="orv-menu_header">&lt;Suppression d'un personnage&gt;</div>
        <div class="orv-menu_container">
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Attention :</h2>
                    <br>
                    <div class="user-notice user-notice--error" style="margin-bottom:20px;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        Cette action est <strong>irréversible</strong>. Toutes les données du personnage seront définitivement supprimées.
                    </div>
                    <table style="width:auto; margin-top:8px;">
                        <tbody>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Nom</td>
                                <td style="font-weight:700;"><?php echo htmlspecialchars($char['prenom'] . ' ' . $char['nom']); ?></td>
                            </tr>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Race</td>
                                <td><?php echo htmlspecialchars($char['race'] ?? '—'); ?></td>
                            </tr>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Métier</td>
                                <td><?php echo htmlspecialchars($char['metier'] ?? '—'); ?></td>
                            </tr>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Créé le</td>
                                <td><?php echo date('d/m/Y à H:i', strtotime($char['created_at'])); ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="margin-top:16px; font-size:13px; color:rgba(137,206,255,0.55);">
                        Seront également supprimés : titres, attributs, compétences, stigmates, inventaire et équipement du personnage.
                    </p>
                </div>
            </div>
        </div>
    </div>
</form>
