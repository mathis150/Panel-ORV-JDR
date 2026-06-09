<?php
    require_once './import/constellations.php';
    $cm   = new ConstellationsManager();
    $uuid = $_GET['uuid'] ?? '';
    $c    = $cm->getConstellationByUUID($uuid);

    if (!$c) {
        echo '<div class="user-notice user-notice--error">Constellation introuvable.</div>';
        return;
    }

    $rangLabels = [
        'mythique'        => 'Mythique',
        'legendaire'      => 'Légendaire',
        'historique_haut' => 'Historique haut-rang',
        'historique_bas'  => 'Historique bas-rang',
    ];
?>

<form method="POST" action="jdr-params?page=constellations&sub-page=delete&uuid=<?php echo urlencode($uuid); ?>">
    <div class="orv-buttons" style="justify-content:flex-end;">
        <button type="submit" class="orv-edit" style="background:linear-gradient(135deg,#8B2020,#C0392B);">
            <i class="fa-solid fa-trash"></i>
            <span style="font-size:18px;">Confirmer la suppression</span>
        </button>
        <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=constellations&sub-page=list';">
            <i class="fa-solid fa-xmark"></i>
            <span style="font-size:18px;">Annuler</span>
        </button>
    </div>

    <div class="orv-menu">
        <div class="orv-menu_header">&lt;Suppression d'une constellation&gt;</div>
        <div class="orv-menu_container">
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Attention :</h2>
                    <br>
                    <div class="user-notice user-notice--error" style="margin-bottom:20px;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        Cette action est <strong>irréversible</strong>. La constellation et toutes ses données associées seront définitivement supprimées.
                    </div>
                    <table style="width:auto; margin-top:8px;">
                        <tbody>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Nom</td>
                                <td style="font-weight:700;"><?php echo htmlspecialchars($c['nom']); ?></td>
                            </tr>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Identité</td>
                                <td><?php echo htmlspecialchars($c['identite'] ?? '—'); ?></td>
                            </tr>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Rang</td>
                                <td><?php echo $rangLabels[strtolower($c['rang'] ?? '')] ?? htmlspecialchars($c['rang'] ?? '—'); ?></td>
                            </tr>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Nébuleuse</td>
                                <td><?php echo htmlspecialchars($c['nebuleuse'] ?? '—'); ?></td>
                            </tr>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Créée le</td>
                                <td><?php echo date('d/m/Y à H:i', strtotime($c['created_at'])); ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="margin-top:16px; font-size:13px; color:rgba(137,206,255,0.55);">
                        Seront également supprimés : titres, attributs, compétences et stigmates de la constellation.
                    </p>
                </div>
            </div>
        </div>
    </div>
</form>
