<?php
    require_once './import/items.php';
    $im     = new ItemsManager();
    $search = trim($_GET['search'] ?? '');
    $items  = $im->getItems($search);
    $dash   = $im->getDashboardStats();
    function ilhv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }
?>

<?php if ($notice ?? ''): ?>
<div class="user-notice" style="width:min(1225px,calc(100% - 40px)); margin-top:16px; margin-bottom:0;">
    <i class="fa-solid fa-circle-check"></i> <?php echo $notice ?? ''; ?>
</div>
<?php endif; ?>

<!-- Wrapper contrôle + stats -->
<div style="width:min(1225px,calc(100% - 40px)); margin-top:20px;">

    <!-- ── Barre de contrôle ──────────────────────────────────────── -->
    <form method="GET" action="jdr-params"
          style="display:flex; gap:10px; align-items:center; margin-bottom:14px;">
        <input type="hidden" name="page" value="items">
        <input type="hidden" name="sub-page" value="list">

        <div style="flex:1; position:relative; min-width:0;">
            <i class="fa-solid fa-magnifying-glass"
               style="position:absolute; left:13px; top:50%; transform:translateY(-50%);
                      color:rgba(137,206,255,0.4); font-size:13px; pointer-events:none; z-index:1;"></i>
            <input type="text" name="search" value="<?php echo ilhv($search); ?>"
                   placeholder="Rechercher un objet par nom..."
                   style="width:100%; padding-left:36px; box-sizing:border-box;">
            <?php if ($search !== ''): ?>
            <a href="jdr-params?page=items&sub-page=list"
               style="position:absolute; right:10px; top:50%; transform:translateY(-50%);
                      color:rgba(137,206,255,0.35); font-size:13px; line-height:1; text-decoration:none;"
               title="Effacer la recherche"><i class="fa-solid fa-xmark"></i></a>
            <?php endif; ?>
        </div>

        <button type="submit" class="orv-edit"
                style="flex-shrink:0; font-size:13px; white-space:nowrap;">
            <i class="fa-solid fa-magnifying-glass"></i> Rechercher
        </button>

        <div style="width:1px; height:28px; background:rgba(55,174,254,0.18); flex-shrink:0;"></div>

        <a href="jdr-params?page=items&sub-page=create"
           class="orv-edit"
           style="flex-shrink:0; font-size:13px; font-weight:600; text-decoration:none; white-space:nowrap;">
            <i class="fa-solid fa-plus" style="font-size:11px;"></i> Créer un objet
        </a>
    </form>

    <!-- ── Statistiques ───────────────────────────────────────────── -->
    <?php
        $cards = [
            ['label' => 'Objets au total',     'sub' => 'dans le catalogue', 'val' => $dash['total'],   'icon' => 'fa-box-open',      'color' => '#42A5F5'],
            ['label' => 'Objets actifs',        'sub' => 'visibles',          'val' => $dash['actifs'],  'icon' => 'fa-circle-check',  'color' => '#66BB6A'],
            ['label' => 'Avec statistiques',   'sub' => 'apportent des bonus','val' => $dash['avStats'],'icon' => 'fa-chart-bar',     'color' => '#AB47BC'],
            ['label' => 'Types distincts',     'sub' => 'catégories',         'val' => $dash['types'],  'icon' => 'fa-layer-group',   'color' => '#FFD700'],
        ];
    ?>
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:12px;">
        <?php foreach ($cards as $c): ?>
        <div style="background:rgba(3,8,22,0.6); border:1px solid rgba(255,255,255,0.06);
                    border-top:3px solid <?php echo $c['color']; ?>;
                    border-radius:10px; padding:16px 18px; position:relative; overflow:hidden;">
            <i class="fa-solid <?php echo $c['icon']; ?>"
               style="position:absolute; right:14px; bottom:10px; font-size:36px;
                      color:<?php echo $c['color']; ?>; opacity:.07;"></i>
            <div style="font-size:30px; font-weight:800; color:rgba(217,255,255,0.92); line-height:1; margin-bottom:6px;">
                <?php echo (int)$c['val']; ?>
            </div>
            <div style="font-size:13px; font-weight:600; color:<?php echo $c['color']; ?>; margin-bottom:2px;">
                <?php echo $c['label']; ?>
            </div>
            <div style="font-size:11px; color:rgba(137,206,255,0.35);">
                <?php echo $c['sub']; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<!-- ── Table principale ───────────────────────────────────────────── -->
<div class="orv-menu">
    <div class="orv-menu_header" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
        <span>
            <i class="fa-solid fa-box-open" style="margin-right:8px; opacity:.6;"></i>
            Catalogue des objets
        </span>
        <?php if ($search !== ''): ?>
        <span style="font-size:11px; color:rgba(137,206,255,0.45); font-weight:400; font-style:italic;">
            <?php echo count($items); ?> résultat<?php echo count($items) !== 1 ? 's' : ''; ?>
            pour «&nbsp;<?php echo ilhv($search); ?>&nbsp;»
        </span>
        <?php else: ?>
        <span style="font-size:11px; color:rgba(137,206,255,0.3); font-weight:400;">
            <?php echo (int)$dash['total']; ?> objet<?php echo (int)$dash['total'] !== 1 ? 's' : ''; ?>
        </span>
        <?php endif; ?>
    </div>
    <div class="orv-menu_container" style="padding:0;">
        <?php if (empty($items)): ?>
        <div style="padding:48px 32px; text-align:center;">
            <div style="font-size:48px; color:rgba(55,174,254,0.15); margin-bottom:14px;">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <div style="color:rgba(137,206,255,0.45); font-size:14px; margin-bottom:6px;">
                <?php echo $search !== ''
                    ? 'Aucun objet ne correspond à « ' . ilhv($search) . ' ».'
                    : 'Aucun objet dans le catalogue.'; ?>
            </div>
            <?php if ($search === ''): ?>
            <a href="jdr-params?page=items&sub-page=create"
               style="display:inline-flex; align-items:center; gap:6px; margin-top:14px; font-size:13px;
                      color:rgba(55,174,254,0.7); text-decoration:none;">
                <i class="fa-solid fa-plus"></i> Créer le premier objet
            </a>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <td>Objet</td>
                    <td>Type</td>
                    <td>Rang</td>
                    <td>Statistiques</td>
                    <td style="text-align:center; width:60px;">Actif</td>
                    <td style="width:72px;">Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <?php
                    $tColor = ItemsManager::TYPE_COLORS[$item['type']] ?? '#7F8C8D';
                    $tIcon  = ItemsManager::TYPE_ICONS[$item['type']]  ?? 'fa-box';
                    $tLabel = ItemsManager::TYPE_LABELS[$item['type']] ?? $item['type'];
                    $rColor = ItemsManager::RANG_COLORS[$item['rang']] ?? '#9E9E9E';
                    $rLabel = ItemsManager::RANG_LABELS[$item['rang']] ?? $item['rang'];
                    $searchQs = $search !== '' ? '&search=' . rawurlencode($search) : '';
                ?>
                <tr style="<?php echo !$item['is_active'] ? 'opacity:.45;' : ''; ?>">
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:38px; height:38px; border-radius:8px; flex-shrink:0;
                                        display:flex; align-items:center; justify-content:center;
                                        background:<?php echo $tColor; ?>18;
                                        border:1px solid <?php echo $tColor; ?>30;">
                                <i class="fa-solid <?php echo $tIcon; ?>"
                                   style="color:<?php echo $tColor; ?>; font-size:15px;"></i>
                            </div>
                            <div style="font-weight:600; font-size:13px; color:rgba(217,255,255,0.88); line-height:1.3;">
                                <?php echo ilhv($item['nom']); ?>
                                <?php if ($item['description']): ?>
                                <div style="font-size:11px; font-weight:400; color:rgba(137,206,255,0.4);
                                            display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical; overflow:hidden; max-width:260px; margin-top:2px;">
                                    <?php echo ilhv($item['description']); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="padding:2px 9px; border-radius:8px; font-size:10px; font-weight:700;
                                     background:<?php echo $tColor; ?>18; color:<?php echo $tColor; ?>;
                                     border:1px solid <?php echo $tColor; ?>38; white-space:nowrap;">
                            <?php echo ilhv($tLabel); ?>
                        </span>
                    </td>
                    <td>
                        <span style="padding:2px 9px; border-radius:8px; font-size:11px; font-weight:800;
                                     background:<?php echo $rColor; ?>18; color:<?php echo $rColor; ?>;
                                     border:1px solid <?php echo $rColor; ?>38; letter-spacing:.04em; white-space:nowrap;">
                            <?php echo ilhv($rLabel); ?>
                        </span>
                    </td>
                    <td>
                        <?php if ((int)$item['stats_count'] > 0): ?>
                        <div style="display:flex; gap:5px; flex-wrap:wrap;">
                            <?php
                            $statBadges = $im->getItemStats($item['uuid']);
                            $grouped = [];
                            foreach ($statBadges as $s) {
                                $grouped[$s['stat_type']] = ($grouped[$s['stat_type']] ?? 0) + (int)$s['valeur'];
                            }
                            foreach ($grouped as $st => $val):
                                $sc = ItemsManager::STAT_COLORS[$st] ?? '#9E9E9E';
                                $si = ItemsManager::STAT_ICONS[$st]  ?? 'fa-circle';
                            ?>
                            <span style="display:inline-flex; align-items:center; gap:3px; padding:1px 6px;
                                         border-radius:7px; font-size:10px; font-weight:700;
                                         background:<?php echo $sc; ?>18; color:<?php echo $sc; ?>;
                                         border:1px solid <?php echo $sc; ?>38;">
                                <i class="fa-solid <?php echo $si; ?>" style="font-size:8px;"></i>
                                +<?php echo $val; ?>
                            </span>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <span style="color:rgba(137,206,255,0.2); font-size:12px; font-style:italic;">—</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:center;">
                        <form method="POST" action="jdr-params?page=items&sub-page=list<?php echo $searchQs; ?>"
                              style="margin:0; display:inline;">
                            <input type="hidden" name="action" value="toggle_active">
                            <input type="hidden" name="uuid"   value="<?php echo ilhv($item['uuid']); ?>">
                            <button type="submit" title="<?php echo $item['is_active'] ? 'Désactiver' : 'Activer'; ?>"
                                    style="background:none; border:none; cursor:pointer; padding:4px 6px; font-size:15px;
                                           color:<?php echo $item['is_active'] ? '#2ECC71' : 'rgba(239,83,80,0.45)'; ?>;">
                                <i class="fa-solid fa-circle-<?php echo $item['is_active'] ? 'check' : 'xmark'; ?>"></i>
                            </button>
                        </form>
                    </td>
                    <td>
                        <a href="jdr-params?page=items&sub-page=modify&uuid=<?php echo urlencode($item['uuid']); ?>"
                           style="background:#89CEFF; font-size:18px; background-clip:text !important;
                                  -webkit-background-clip:text !important; -webkit-text-fill-color:transparent; margin-right:8px;">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a href="jdr-params?page=items&sub-page=delete&uuid=<?php echo urlencode($item['uuid']); ?>"
                           style="background:#EF5350; font-size:18px; background-clip:text !important;
                                  -webkit-background-clip:text !important; -webkit-text-fill-color:transparent;">
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
