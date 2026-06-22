<?php
    require_once './import/modifiers.php';
    $mm        = new ModifiersManager();
    $modifiers = $mm->getModifiers();
    function mlhv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }
?>

<?php if ($notice ?? ''): ?>
<div class="user-notice" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-check"></i> <?php echo $notice; ?>
</div>
<?php endif; ?>

<div class="orv-buttons" style="justify-content:flex-end;">
    <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=modifiers&sub-page=create';">
        <i class="fa-solid fa-plus"></i>
        <span style="font-size:18px;">Créer un modificateur</span>
    </button>
</div>

<div class="orv-menu">
    <div class="orv-menu_header">&lt;Liste des Modificateurs&gt;</div>
    <div class="orv-menu_container" style="padding:0;">
        <?php if (empty($modifiers)): ?>
        <div style="padding:32px; text-align:center; color:rgba(137,206,255,0.35); font-style:italic;">
            Aucun modificateur enregistré.
        </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <td>Modificateur</td>
                    <td>Histoire liée</td>
                    <td>Date d'enregistrement</td>
                    <td width="80px">Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($modifiers as $mod): ?>
                <?php
                    $typeColor = ModifiersManager::TYPE_COLORS[$mod['type']] ?? '#89CEFF';
                    $typeLabel = ModifiersManager::TYPE_LABELS[$mod['type']] ?? $mod['type'];
                    $createdAt = date('d/m/Y', strtotime($mod['created_at']));
                ?>
                <tr>
                    <td>
                        <span style="font-weight:600;"><?php echo mlhv($mod['nom']); ?></span>
                        <span style="display:inline-block; margin-left:8px; padding:1px 8px; border-radius:10px; font-size:10px; font-weight:700;
                                     background:<?php echo $typeColor; ?>22; color:<?php echo $typeColor; ?>;
                                     border:1px solid <?php echo $typeColor; ?>55; vertical-align:middle; white-space:nowrap;">
                            <?php echo mlhv($typeLabel); ?>
                        </span>
                        <?php if (!$mod['is_active']): ?>
                        <span style="display:inline-block; margin-left:5px; padding:1px 7px; border-radius:10px; font-size:10px;
                                     background:rgba(120,120,120,0.12); color:rgba(180,180,180,0.5);
                                     border:1px solid rgba(120,120,120,0.25); vertical-align:middle;">
                            inactif
                        </span>
                        <?php endif; ?>
                    </td>
                    <td style="color:rgba(137,206,255,0.65);">
                        <?php echo $mod['histoire_titre'] ? mlhv($mod['histoire_titre']) : '<span style="color:rgba(137,206,255,0.25); font-style:italic;">—</span>'; ?>
                    </td>
                    <td style="color:rgba(137,206,255,0.5); font-size:13px;"><?php echo $createdAt; ?></td>
                    <td>
                        <a href="jdr-params?page=modifiers&sub-page=modify&uuid=<?php echo urlencode($mod['uuid']); ?>&tab=mod-info"
                           style="background:#89CEFF; font-size:20px; background-clip:text !important; -webkit-background-clip:text !important; -webkit-text-fill-color:transparent; margin-right:6px;">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a href="jdr-params?page=modifiers&sub-page=delete&uuid=<?php echo urlencode($mod['uuid']); ?>"
                           style="background:#EF5350; font-size:20px; background-clip:text !important; -webkit-background-clip:text !important; -webkit-text-fill-color:transparent;">
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
