<?php
    require_once './import/histories.php';
    $hm        = new HistoriesManager();
    $histories = $hm->getHistories();
?>

<?php if ($notice ?? ''): ?>
<div class="user-notice" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-check"></i> <?php echo $notice; ?>
</div>
<?php endif; ?>

<div class="orv-buttons" style="justify-content:flex-end;">
    <button class="orv-edit" onclick="window.location.href='jdr-params?page=histories&sub-page=create';">
        <i class="fa-solid fa-plus"></i>
        <span style="font-size:18px;">Créer une histoire</span>
    </button>
</div>

<div class="orv-menu">
    <div class="orv-menu_header">&lt;Liste des Histoires&gt;</div>
    <div class="orv-menu_container" style="padding:0;">
        <?php if (empty($histories)): ?>
        <div style="padding:40px; text-align:center; color:rgba(137,206,255,0.4); font-size:13px;">
            <i class="fa-solid fa-scroll" style="font-size:24px; display:block; margin-bottom:10px;"></i>
            Aucune histoire enregistrée pour l'instant.
        </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <td>Titre</td>
                    <td style="text-align:center;">Rang</td>
                    <td style="text-align:center;">Niveau</td>
                    <td>Statut</td>
                    <td>Drapeaux</td>
                    <td width="80px">Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($histories as $hist): ?>
                <?php
                    $rang  = $hist['rang'];
                    $color = HistoriesManager::RANG_COLORS[$rang] ?? '#89CEFF';
                    $isBrisee = $hist['statut'] === 'brisee';
                ?>
                <tr class="<?php echo $hist['is_active'] ? '' : 'user-row--inactive'; ?>">
                    <td>
                        <strong><?php echo htmlspecialchars($hist['titre']); ?></strong>
                        <?php if ($hist['rang'] === 'Histoire Gigantesque' && $hist['ownership_pct'] < 100): ?>
                        <span style="margin-left:6px; font-size:11px; color:#FFD700; opacity:0.8;">
                            <?php echo (int)$hist['ownership_pct']; ?>%
                        </span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:center;">
                        <span style="display:inline-block; padding:2px 10px; border-radius:12px; font-size:10px; font-weight:700;
                                     background:<?php echo $color; ?>22; color:<?php echo $color; ?>;
                                     border:1px solid <?php echo $color; ?>55; white-space:nowrap;">
                            <?php echo htmlspecialchars($rang); ?>
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <span style="font-weight:700; color:rgba(137,206,255,0.8);">
                            Niv. <?php echo (int)$hist['niveau_actuel']; ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($isBrisee): ?>
                        <span class="user-status-badge user-status-badge--inactive" style="white-space:nowrap;">
                            Brisée — <?php echo (int)$hist['completion_pct']; ?>%
                        </span>
                        <?php else: ?>
                        <span class="user-status-badge user-status-badge--active">Complète</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:12px;">
                        <?php if ($hist['is_perso']): ?>
                        <span style="margin-right:4px; padding:1px 7px; border-radius:10px; font-size:11px; font-weight:600;
                                     background:rgba(137,206,255,0.1); color:rgba(137,206,255,0.7); border:1px solid rgba(137,206,255,0.2);">
                            Perso
                        </span>
                        <?php endif; ?>
                        <?php if ($hist['is_fondatrice']): ?>
                        <span style="padding:1px 7px; border-radius:10px; font-size:11px; font-weight:600;
                                     background:rgba(255,215,0,0.1); color:#FFD700; border:1px solid rgba(255,215,0,0.3);">
                            Fondatrice
                        </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="jdr-params?page=histories&sub-page=modify&uuid=<?php echo urlencode($hist['uuid']); ?>"
                           style="background:#FFF; font-size:22px; background-clip:text !important; -webkit-background-clip:text !important; margin-right:8px;">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a href="jdr-params?page=histories&sub-page=delete&uuid=<?php echo urlencode($hist['uuid']); ?>"
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
