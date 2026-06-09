<?php
    require_once './import/monsters.php';
    $mm          = new MonstersManager();
    $races       = $mm->getDistinctRaces();
    $raceFilter  = $_GET['race'] ?? '';
    $monsters    = $mm->getMonsters($raceFilter);

    $rankLabels = [
        1 => 'Rang 1', 2 => 'Rang 2', 3 => 'Rang 3',
        4 => 'Rang 4', 5 => 'Rang 5', 6 => 'Rang 6',
        7 => 'Rang 7', 8 => 'Rang 8', 9 => 'Rang 9',
    ];
    $rankColors = [
        1 => '#C0392B', 2 => '#E74C3C', 3 => '#E67E22',
        4 => '#F39C12', 5 => '#D4AC0D', 6 => '#27AE60',
        7 => '#2ECC71', 8 => '#1ABC9C', 9 => '#3498DB',
    ];
?>

<?php if ($notice ?? ''): ?>
<div class="user-notice" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-check"></i> <?php echo $notice; ?>
</div>
<?php endif; ?>

<div class="orv-buttons" style="justify-content:flex-end;">
    <button class="orv-edit" onclick="window.location.href='jdr-params?page=monsters&sub-page=create';">
        <i class="fa-solid fa-plus"></i>
        <span style="font-size:18px;">Créer un monstre</span>
    </button>
</div>

<?php if (!empty($races)): ?>
<div style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:16px; align-items:center;">
    <span style="font-size:12px; color:rgba(137,206,255,0.5); margin-right:4px;">Races :</span>
    <a href="jdr-params?page=monsters&sub-page=list"
       style="display:inline-block; padding:4px 12px; border-radius:20px; font-size:12px; text-decoration:none;
              background:<?php echo $raceFilter === '' ? 'rgba(137,206,255,0.2)' : 'rgba(137,206,255,0.07)'; ?>;
              color:<?php echo $raceFilter === '' ? '#89CEFF' : 'rgba(137,206,255,0.5)'; ?>;
              border:1px solid <?php echo $raceFilter === '' ? 'rgba(137,206,255,0.4)' : 'rgba(137,206,255,0.15)'; ?>;">
        Toutes
    </a>
    <?php foreach ($races as $r): ?>
    <a href="jdr-params?page=monsters&sub-page=list&race=<?php echo urlencode($r); ?>"
       style="display:inline-block; padding:4px 12px; border-radius:20px; font-size:12px; text-decoration:none;
              background:<?php echo $raceFilter === $r ? 'rgba(137,206,255,0.2)' : 'rgba(137,206,255,0.07)'; ?>;
              color:<?php echo $raceFilter === $r ? '#89CEFF' : 'rgba(137,206,255,0.5)'; ?>;
              border:1px solid <?php echo $raceFilter === $r ? 'rgba(137,206,255,0.4)' : 'rgba(137,206,255,0.15)'; ?>;">
        <?php echo htmlspecialchars($r); ?>
    </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="orv-menu">
    <div class="orv-menu_header">&lt;Liste des Monstres<?php echo $raceFilter ? ' — ' . htmlspecialchars($raceFilter) : ''; ?>&gt;</div>
    <div class="orv-menu_container" style="padding:0;">
        <?php if (empty($monsters)): ?>
        <div style="padding:40px; text-align:center; color:rgba(137,206,255,0.4); font-size:13px;">
            <i class="fa-solid fa-dragon" style="font-size:24px; display:block; margin-bottom:10px;"></i>
            Aucun monstre enregistré<?php echo $raceFilter ? ' pour cette race' : ''; ?>.
        </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <td>Nom</td>
                    <td>Race</td>
                    <td style="text-align:center;">Rang</td>
                    <td>Vitalité</td>
                    <td>Statut</td>
                    <td width="80px">Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($monsters as $m): ?>
                <tr class="<?php echo $m['is_active'] ? '' : 'user-row--inactive'; ?>">
                    <td><strong><?php echo htmlspecialchars($m['nom']); ?></strong></td>
                    <td style="font-size:12px; color:rgba(137,206,255,0.6);">
                        <?php echo $m['race'] ? htmlspecialchars($m['race']) : '<span style="opacity:0.4;">—</span>'; ?>
                    </td>
                    <td style="text-align:center;">
                        <?php
                            $rank  = (int)$m['rank'];
                            $color = $rankColors[$rank] ?? '#89CEFF';
                            $label = $rankLabels[$rank] ?? 'Rang ?';
                        ?>
                        <span style="display:inline-block; padding:2px 10px; border-radius:12px; font-size:11px; font-weight:700;
                                     background:<?php echo $color; ?>22; color:<?php echo $color; ?>;
                                     border:1px solid <?php echo $color; ?>55;">
                            <?php echo $label; ?>
                        </span>
                    </td>
                    <td style="font-size:12px;"><?php echo (int)$m['vitalite']; ?></td>
                    <td>
                        <?php if ($m['is_active']): ?>
                        <span class="user-status-badge user-status-badge--active">Actif</span>
                        <?php else: ?>
                        <span class="user-status-badge user-status-badge--inactive">Inactif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="jdr-params?page=monsters&sub-page=modify&uuid=<?php echo urlencode($m['uuid']); ?>"
                           style="background:#FFF; font-size:22px; background-clip:text !important; -webkit-background-clip:text !important; margin-right:8px;">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a href="jdr-params?page=monsters&sub-page=delete&uuid=<?php echo urlencode($m['uuid']); ?>"
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
