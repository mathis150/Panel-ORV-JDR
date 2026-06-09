<?php
    require_once './import/attributs.php';
    $am         = new AttributesManager();
    $attributes = $am->getAttributes();
?>

<?php if ($notice ?? ''): ?>
<div class="user-notice" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-check"></i> <?php echo $notice; ?>
</div>
<?php endif; ?>

<div class="orv-buttons" style="justify-content:flex-end;">
    <button class="orv-edit" onclick="window.location.href='jdr-params?page=attributes&sub-page=create';">
        <i class="fa-solid fa-plus"></i>
        <span style="font-size:18px;">Créer un attribut</span>
    </button>
</div>

<div class="orv-menu">
    <div class="orv-menu_header">&lt;Liste des Attributs&gt;</div>
    <div class="orv-menu_container" style="padding:0;">
        <?php if (empty($attributes)): ?>
        <div style="padding:40px; text-align:center; color:rgba(137,206,255,0.4); font-size:13px;">
            <i class="fa-solid fa-address-card" style="font-size:24px; display:block; margin-bottom:10px;"></i>
            Aucun attribut enregistré pour l'instant.
        </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <td>Nom</td>
                    <td style="text-align:center;">Rang</td>
                    <td>Statut</td>
                    <td width="80px">Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attributes as $attr): ?>
                <?php
                    $rang  = $attr['rang'];
                    $color = AttributesManager::RANG_COLORS[$rang] ?? '#89CEFF';
                ?>
                <tr class="<?php echo $attr['is_active'] ? '' : 'user-row--inactive'; ?>">
                    <td><strong><?php echo htmlspecialchars($attr['nom']); ?></strong></td>
                    <td style="text-align:center;">
                        <span style="display:inline-block; padding:2px 10px; border-radius:12px; font-size:11px; font-weight:700;
                                     background:<?php echo $color; ?>22; color:<?php echo $color; ?>;
                                     border:1px solid <?php echo $color; ?>55;">
                            <?php echo htmlspecialchars($rang); ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($attr['is_active']): ?>
                        <span class="user-status-badge user-status-badge--active">Actif</span>
                        <?php else: ?>
                        <span class="user-status-badge user-status-badge--inactive">Inactif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="jdr-params?page=attributes&sub-page=modify&uuid=<?php echo urlencode($attr['uuid']); ?>"
                           style="background:#FFF; font-size:22px; background-clip:text !important; -webkit-background-clip:text !important; margin-right:8px;">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a href="jdr-params?page=attributes&sub-page=delete&uuid=<?php echo urlencode($attr['uuid']); ?>"
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
