<?php
    require_once './import/histories.php';
    $hm   = new HistoriesManager();
    $uuid = $_GET['uuid'] ?? '';
    $hist = $hm->getHistoryByUUID($uuid);

    if (!$hist) {
        echo '<div class="user-notice user-notice--error">Histoire introuvable.</div>';
        return;
    }

    $color = HistoriesManager::RANG_COLORS[$hist['rang']] ?? '#89CEFF';
?>

<form method="POST" action="jdr-params?page=histories&sub-page=delete&uuid=<?php echo urlencode($uuid); ?>">
    <div class="orv-buttons" style="justify-content:flex-end;">
        <button type="submit" class="orv-edit" style="background:linear-gradient(135deg,#8B2020,#C0392B);">
            <i class="fa-solid fa-trash"></i>
            <span style="font-size:18px;">Confirmer la suppression</span>
        </button>
        <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=histories&sub-page=list';">
            <i class="fa-solid fa-xmark"></i>
            <span style="font-size:18px;">Annuler</span>
        </button>
    </div>

    <div class="orv-menu">
        <div class="orv-menu_header">&lt;Suppression d'une histoire&gt;</div>
        <div class="orv-menu_container">
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Attention :</h2>
                    <br>
                    <div class="user-notice user-notice--error" style="margin-bottom:20px;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        Cette action est <strong>irréversible</strong>. L'histoire et tous les stigmates associés à ses niveaux seront définitivement supprimés.
                    </div>
                    <table style="width:auto; margin-top:8px;">
                        <tbody>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Titre</td>
                                <td style="font-weight:700;"><?php echo htmlspecialchars($hist['titre']); ?></td>
                            </tr>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Rang</td>
                                <td>
                                    <span style="display:inline-block; padding:2px 10px; border-radius:12px; font-size:11px; font-weight:700;
                                                 background:<?php echo $color; ?>22; color:<?php echo $color; ?>; border:1px solid <?php echo $color; ?>55;">
                                        <?php echo htmlspecialchars($hist['rang']); ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Statut</td>
                                <td>
                                    <?php if ($hist['statut'] === 'brisee'): ?>
                                    Brisée (<?php echo (int)$hist['completion_pct']; ?>%)
                                    <?php else: ?>
                                    Complète
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Niveau actuel</td>
                                <td>Niveau <?php echo (int)$hist['niveau_actuel']; ?></td>
                            </tr>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Créée le</td>
                                <td><?php echo date('d/m/Y à H:i', strtotime($hist['created_at'])); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
