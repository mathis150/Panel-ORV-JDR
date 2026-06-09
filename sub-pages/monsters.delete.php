<?php
    require_once './import/monsters.php';
    $mm      = new MonstersManager();
    $uuid    = $_GET['uuid'] ?? '';
    $monster = $mm->getMonsterByUUID($uuid);

    if (!$monster) {
        echo '<div class="user-notice user-notice--error">Monstre introuvable.</div>';
        return;
    }

    $rankLabels = [
        1 => 'Rang 1 (Le plus puissant)', 2 => 'Rang 2', 3 => 'Rang 3',
        4 => 'Rang 4', 5 => 'Rang 5', 6 => 'Rang 6',
        7 => 'Rang 7', 8 => 'Rang 8', 9 => 'Rang 9 (Le plus faible)',
    ];
?>

<form method="POST" action="jdr-params?page=monsters&sub-page=delete&uuid=<?php echo urlencode($uuid); ?>">
    <div class="orv-buttons" style="justify-content:flex-end;">
        <button type="submit" class="orv-edit" style="background:linear-gradient(135deg,#8B2020,#C0392B);">
            <i class="fa-solid fa-trash"></i>
            <span style="font-size:18px;">Confirmer la suppression</span>
        </button>
        <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=monsters&sub-page=list';">
            <i class="fa-solid fa-xmark"></i>
            <span style="font-size:18px;">Annuler</span>
        </button>
    </div>

    <div class="orv-menu">
        <div class="orv-menu_header">&lt;Suppression d'un monstre&gt;</div>
        <div class="orv-menu_container">
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Attention :</h2>
                    <br>
                    <div class="user-notice user-notice--error" style="margin-bottom:20px;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        Cette action est <strong>irréversible</strong>. Le monstre et tout son équipement seront définitivement supprimés.
                    </div>
                    <table style="width:auto; margin-top:8px;">
                        <tbody>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Nom</td>
                                <td style="font-weight:700;"><?php echo htmlspecialchars($monster['nom']); ?></td>
                            </tr>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Race</td>
                                <td><?php echo htmlspecialchars($monster['race'] ?? '—'); ?></td>
                            </tr>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Rang</td>
                                <td><?php echo $rankLabels[(int)$monster['rank']] ?? 'Rang ' . (int)$monster['rank']; ?></td>
                            </tr>
                            <tr>
                                <td style="padding:6px 16px 6px 0; color:rgba(137,206,255,0.5); font-size:12px;">Créé le</td>
                                <td><?php echo date('d/m/Y à H:i', strtotime($monster['created_at'])); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
