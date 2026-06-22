<?php
    require_once './import/scenarios.php';
    $sm        = new ScenariosManager();
    $scenarios = $sm->getScenarios();
    function slhv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }
?>

<?php if ($notice ?? ''): ?>
<div class="user-notice" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-check"></i> <?php echo $notice; ?>
</div>
<?php endif; ?>

<div class="orv-buttons" style="justify-content:flex-end;">
    <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=scenarios&sub-page=create';">
        <i class="fa-solid fa-plus"></i>
        <span style="font-size:18px;">Créer un scénario</span>
    </button>
</div>

<div class="orv-menu">
    <div class="orv-menu_header">&lt;Liste des Scénarios&gt;</div>
    <div class="orv-menu_container" style="padding:0;">
        <?php if (empty($scenarios)): ?>
        <div style="padding:32px; text-align:center; color:rgba(137,206,255,0.35); font-style:italic;">
            Aucun scénario enregistré.
        </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <td>Titre</td>
                    <td>Type</td>
                    <td>Rang</td>
                    <td>Temps limite</td>
                    <td width="80px">Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($scenarios as $sc): ?>
                <?php
                    $typeColor  = ScenariosManager::TYPE_COLORS[$sc['type']]  ?? '#89CEFF';
                    $typeLabel  = ScenariosManager::TYPE_LABELS[$sc['type']]  ?? $sc['type'];
                    $rangColor  = ScenariosManager::RANG_COLORS[$sc['rang']]  ?? '#9E9E9E';
                    $rangDisp   = $sc['rang'] . ($sc['rang_plus'] ? '+' : '');
                    $tempsDisp  = $sm->getTempsDisplay($sc['temps_type'], $sc['temps_valeur'] !== null ? (int)$sc['temps_valeur'] : null);
                    $isAucune   = $sc['temps_type'] === 'aucune';

                    $titre = $sc['titre'];
                    if ($sc['type'] === 'principal' && $sc['numero'] !== null) {
                        $titre = 'Scénario Principal n°' . (int)$sc['numero'] . ' — ' . $titre;
                    }
                ?>
                <tr>
                    <td>
                        <span style="font-weight:600;"><?php echo slhv($titre); ?></span>
                        <?php if (!$sc['is_active']): ?>
                        <span style="display:inline-block; margin-left:6px; padding:1px 7px; border-radius:10px; font-size:10px;
                                     background:rgba(120,120,120,0.12); color:rgba(180,180,180,0.5);
                                     border:1px solid rgba(120,120,120,0.25); vertical-align:middle;">
                            inactif
                        </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span style="padding:2px 9px; border-radius:10px; font-size:11px; font-weight:700;
                                     background:<?php echo $typeColor; ?>22; color:<?php echo $typeColor; ?>;
                                     border:1px solid <?php echo $typeColor; ?>55; white-space:nowrap;">
                            <?php echo slhv($typeLabel); ?>
                        </span>
                    </td>
                    <td>
                        <span style="padding:2px 9px; border-radius:10px; font-size:12px; font-weight:800;
                                     background:<?php echo $rangColor; ?>22; color:<?php echo $rangColor; ?>;
                                     border:1px solid <?php echo $rangColor; ?>55; white-space:nowrap; letter-spacing:.04em;">
                            <?php echo slhv($rangDisp); ?>
                        </span>
                    </td>
                    <td style="font-size:13px; color:<?php echo $isAucune ? 'rgba(137,206,255,0.3)' : 'rgba(137,206,255,0.7)'; ?>;">
                        <?php echo $isAucune ? '<i class="fa-solid fa-infinity" style="font-size:14px;"></i>' : slhv($tempsDisp); ?>
                    </td>
                    <td>
                        <a href="jdr-params?page=scenarios&sub-page=modify&uuid=<?php echo urlencode($sc['uuid']); ?>&tab=sc-info"
                           style="background:#89CEFF; font-size:20px; background-clip:text !important; -webkit-background-clip:text !important; -webkit-text-fill-color:transparent; margin-right:6px;">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a href="jdr-params?page=scenarios&sub-page=delete&uuid=<?php echo urlencode($sc['uuid']); ?>"
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
