<?php
    require_once './import/constellations.php';
    $cm             = new ConstellationsManager();
    $constellations = $cm->getConstellations();

    $rangLabels = [
        'mythique'            => 'Mythique',
        'legendaire'          => 'Légendaire',
        'historique_haut'     => 'Historique haut-rang',
        'historique_bas'      => 'Historique bas-rang',
    ];
    $rangColors = [
        'mythique'            => '#C0392B',
        'legendaire'          => '#E67E22',
        'historique_haut'     => '#2980B9',
        'historique_bas'      => '#27AE60',
    ];
?>

<?php if ($notice ?? ''): ?>
<div class="user-notice" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-check"></i> <?php echo $notice; ?>
</div>
<?php endif; ?>

<div class="orv-buttons" style="justify-content:flex-end;">
    <button class="orv-edit" onclick="window.location.href='jdr-params?page=constellations&sub-page=create';">
        <i class="fa-solid fa-plus"></i>
        <span style="font-size:18px;">Créer une constellation</span>
    </button>
</div>

<div class="orv-menu">
    <div class="orv-menu_header">&lt;Liste des Constellations&gt;</div>
    <div class="orv-menu_container" style="padding:0;">
        <?php if (empty($constellations)): ?>
        <div style="padding:40px; text-align:center; color:rgba(137,206,255,0.4); font-size:13px;">
            <i class="fa-solid fa-user-astronaut" style="font-size:24px; display:block; margin-bottom:10px;"></i>
            Aucune constellation enregistrée pour l'instant.
        </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <td>Constellation</td>
                    <td>Identité</td>
                    <td style="text-align:center;">Rang</td>
                    <td>Nébuleuse</td>
                    <td>Statut</td>
                    <td width="80px">Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($constellations as $c): ?>
                <tr class="<?php echo $c['is_active'] ? '' : 'user-row--inactive'; ?>">
                    <td><strong><?php echo htmlspecialchars($c['nom']); ?></strong></td>
                    <td style="font-size:12px; color:rgba(137,206,255,0.6);">
                        <?php echo $c['identite'] ? htmlspecialchars($c['identite']) : '<span style="opacity:0.4;">—</span>'; ?>
                    </td>
                    <td style="text-align:center;">
                        <?php
                            $rang  = strtolower($c['rang'] ?? 'narrative');
                            $color = $rangColors[$rang] ?? '#89CEFF';
                            $label = $rangLabels[$rang] ?? htmlspecialchars($c['rang']);
                        ?>
                        <span style="display:inline-block; padding:2px 10px; border-radius:12px; font-size:11px; font-weight:700;
                                     background:<?php echo $color; ?>22; color:<?php echo $color; ?>;
                                     border:1px solid <?php echo $color; ?>55;">
                            <?php echo $label; ?>
                        </span>
                    </td>
                    <td style="font-size:12px;">
                        <?php echo $c['nebuleuse'] ? htmlspecialchars($c['nebuleuse']) : '<span style="opacity:0.4;">—</span>'; ?>
                    </td>
                    <td>
                        <?php if ($c['is_active']): ?>
                        <span class="user-status-badge user-status-badge--active">Active</span>
                        <?php else: ?>
                        <span class="user-status-badge user-status-badge--inactive">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="jdr-params?page=constellations&sub-page=modify&uuid=<?php echo urlencode($c['uuid']); ?>"
                           style="background:#FFF; font-size:22px; background-clip:text !important; -webkit-background-clip:text !important; margin-right:8px;">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a href="jdr-params?page=constellations&sub-page=delete&uuid=<?php echo urlencode($c['uuid']); ?>"
                           style="background:#FFF; font-size:22px; background-clip:text !important; -webkit-background-clip:text !important;">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
