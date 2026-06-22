<?php
    require_once './import/dokkaebi_shop.php';
    $dsm      = new DokkaebiShopManager();
    $search   = trim($_GET['search'] ?? '');
    $listings = $dsm->getShopListings($search);
    $stats    = $dsm->getShopStats();
    function dblhv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }
?>

<?php if ($notice ?? ''): ?>
<div class="user-notice" style="width:min(1225px,calc(100% - 40px)); margin-top:16px; margin-bottom:0;">
    <i class="fa-solid fa-circle-check"></i> <?php echo $notice ?? ''; ?>
</div>
<?php endif; ?>

<!-- Wrapper contrôle + stats — même largeur que .orv-menu -->
<div style="width:min(1225px,calc(100% - 40px)); margin-top:20px;">

    <!-- ── Barre de contrôle ──────────────────────────────────────── -->
    <form method="GET" action="jdr-params"
          style="display:flex; gap:10px; align-items:center; margin-bottom:14px;">
        <input type="hidden" name="page" value="dokkaebi_bag">
        <input type="hidden" name="sub-page" value="list">

        <!-- Champ de recherche -->
        <div style="flex:1; position:relative; min-width:0;">
            <i class="fa-solid fa-magnifying-glass"
               style="position:absolute; left:13px; top:50%; transform:translateY(-50%);
                      color:rgba(137,206,255,0.4); font-size:13px; pointer-events:none; z-index:1;"></i>
            <input type="text" name="search" value="<?php echo dblhv($search); ?>"
                   placeholder="Rechercher un objet par nom..."
                   style="width:100%; padding-left:36px; box-sizing:border-box;">
            <?php if ($search !== ''): ?>
            <a href="jdr-params?page=dokkaebi_bag&sub-page=list"
               style="position:absolute; right:10px; top:50%; transform:translateY(-50%);
                      color:rgba(137,206,255,0.35); font-size:13px; line-height:1; text-decoration:none;"
               title="Effacer la recherche">
                <i class="fa-solid fa-xmark"></i>
            </a>
            <?php endif; ?>
        </div>

        <!-- Bouton recherche -->
        <button type="submit" class="orv-edit"
                style="flex-shrink:0; font-size:13px; white-space:nowrap;">
            <i class="fa-solid fa-magnifying-glass"></i>
            Rechercher
        </button>

        <!-- Séparateur visuel -->
        <div style="width:1px; height:28px; background:rgba(55,174,254,0.18); flex-shrink:0;"></div>

        <!-- Bouton ajouter -->
        <a href="jdr-params?page=dokkaebi_bag&sub-page=create"
           class="orv-edit"
           style="flex-shrink:0; font-size:13px; font-weight:600; text-decoration:none; white-space:nowrap;">
            <i class="fa-solid fa-plus" style="font-size:11px;"></i>
            Ajouter un objet
        </a>
    </form>

    <!-- ── Statistiques ───────────────────────────────────────────── -->
    <?php
        $quickStats = [
            ['label'=>'Objets en vente', 'sublabel'=>'dans le shop',  'value'=>$stats['total'],   'icon'=>'fa-bag-shopping', 'color'=>'#42A5F5'],
            ['label'=>'En vedette',      'sublabel'=>'mis en avant',   'value'=>$stats['vedettes'],'icon'=>'fa-star',         'color'=>'#FFD700'],
            ['label'=>'Désactivés',      'sublabel'=>'non visibles',   'value'=>$stats['inactifs'],'icon'=>'fa-eye-slash',    'color'=>'#EF5350'],
            ['label'=>'Au catalogue',    'sublabel'=>'objets totaux',  'value'=>$stats['items'],   'icon'=>'fa-layer-group',  'color'=>'#66BB6A'],
        ];
    ?>
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:12px;">
        <?php foreach ($quickStats as $qs): ?>
        <div style="background:rgba(3,8,22,0.6); border:1px solid rgba(255,255,255,0.06);
                    border-top:3px solid <?php echo $qs['color']; ?>;
                    border-radius:10px; padding:16px 18px; position:relative; overflow:hidden;">
            <i class="fa-solid <?php echo $qs['icon']; ?>"
               style="position:absolute; right:14px; bottom:10px; font-size:36px;
                      color:<?php echo $qs['color']; ?>; opacity:.07;"></i>
            <div style="font-size:30px; font-weight:800; color:rgba(217,255,255,0.92); line-height:1; margin-bottom:6px;">
                <?php echo (int)$qs['value']; ?>
            </div>
            <div style="font-size:13px; font-weight:600; color:<?php echo $qs['color']; ?>; margin-bottom:2px;">
                <?php echo $qs['label']; ?>
            </div>
            <div style="font-size:11px; color:rgba(137,206,255,0.35);">
                <?php echo $qs['sublabel']; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<!-- ── Table principale ───────────────────────────────────────── -->
<div class="orv-menu">
    <div class="orv-menu_header" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; padding:0 20px;">
        <span>
            <i class="fa-solid fa-bag-shopping" style="margin-right:8px; opacity:.6;"></i>
            Gestion du Baluchon des Dokkaebi
        </span>
        <?php if ($search !== ''): ?>
        <span style="font-size:11px; color:rgba(137,206,255,0.45); font-weight:400; font-style:italic;">
            <?php echo count($listings); ?> résultat<?php echo count($listings) !== 1 ? 's' : ''; ?>
            pour «&nbsp;<?php echo dblhv($search); ?>&nbsp;»
        </span>
        <?php else: ?>
        <span style="font-size:11px; color:rgba(137,206,255,0.3); font-weight:400;">
            <?php echo (int)$stats['total']; ?> objet<?php echo (int)$stats['total'] !== 1 ? 's' : ''; ?> en vente
        </span>
        <?php endif; ?>
    </div>
    <div class="orv-menu_container" style="padding:0;">
        <?php if (empty($listings)): ?>
        <div style="padding:48px 32px; text-align:center;">
            <div style="font-size:48px; color:rgba(55,174,254,0.15); margin-bottom:14px;">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
            <div style="color:rgba(137,206,255,0.45); font-size:14px; margin-bottom:6px;">
                <?php echo $search !== ''
                    ? 'Aucun objet ne correspond à « ' . dblhv($search) . ' ».'
                    : 'Aucun objet en vente dans le shop.'; ?>
            </div>
            <?php if ($search === ''): ?>
            <a href="jdr-params?page=dokkaebi_bag&sub-page=create"
               style="display:inline-flex; align-items:center; gap:6px; margin-top:14px; font-size:13px;
                      color:rgba(55,174,254,0.7); text-decoration:none;">
                <i class="fa-solid fa-plus"></i> Ajouter le premier objet
            </a>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <td>Objet</td>
                    <td>Prix</td>
                    <td>Quantité</td>
                    <td>Vendeur</td>
                    <td>Rang minimum</td>
                    <td style="text-align:center; width:64px;">Vedette</td>
                    <td style="text-align:center; width:60px;">Actif</td>
                    <td style="width:72px;">Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listings as $lst): ?>
                <?php
                    $itColor  = DokkaebiShopManager::ITEM_TYPE_COLORS[$lst['item_type']] ?? '#7F8C8D';
                    $itIcon   = DokkaebiShopManager::ITEM_TYPE_ICONS[$lst['item_type']]  ?? 'fa-box';
                    $itLabel  = DokkaebiShopManager::ITEM_TYPE_LABELS[$lst['item_type']] ?? $lst['item_type'];
                    $rgColor  = DokkaebiShopManager::RANG_COLORS[$lst['item_rang']]      ?? '#9E9E9E';
                    $rmColor  = DokkaebiShopManager::RANG_MIN_COLORS[$lst['rang_min']]   ?? '#9E9E9E';
                    $rmIcon   = DokkaebiShopManager::RANG_MIN_ICONS[$lst['rang_min']]    ?? 'fa-shield';
                    $rmLabel  = DokkaebiShopManager::RANG_MIN_LABELS[$lst['rang_min']]   ?? $lst['rang_min'];
                    $searchQs = $search !== '' ? '&search=' . rawurlencode($search) : '';
                ?>
                <tr style="<?php echo !$lst['is_actif'] ? 'opacity:.45;' : ''; ?>">
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:36px; height:36px; border-radius:8px; flex-shrink:0;
                                        display:flex; align-items:center; justify-content:center;
                                        background:<?php echo $itColor; ?>18;
                                        border:1px solid <?php echo $itColor; ?>30;">
                                <i class="fa-solid <?php echo $itIcon; ?>"
                                   style="color:<?php echo $itColor; ?>; font-size:14px;"></i>
                            </div>
                            <div>
                                <div style="font-weight:600; font-size:13px; color:rgba(217,255,255,0.88);">
                                    <?php echo dblhv($lst['nom']); ?>
                                </div>
                                <div style="display:flex; gap:4px; margin-top:3px; flex-wrap:wrap;">
                                    <span style="padding:1px 7px; border-radius:8px; font-size:10px; font-weight:700;
                                                 background:<?php echo $itColor; ?>18; color:<?php echo $itColor; ?>;
                                                 border:1px solid <?php echo $itColor; ?>38;">
                                        <?php echo dblhv($itLabel); ?>
                                    </span>
                                    <span style="padding:1px 7px; border-radius:8px; font-size:10px; font-weight:800;
                                                 background:<?php echo $rgColor; ?>18; color:<?php echo $rgColor; ?>;
                                                 border:1px solid <?php echo $rgColor; ?>38; letter-spacing:.04em;">
                                        <?php echo dblhv($lst['item_rang']); ?>
                                    </span>
                                    <?php if ($lst['is_vedette']): ?>
                                    <span style="padding:1px 7px; border-radius:8px; font-size:10px; font-weight:700;
                                                 background:rgba(255,215,0,0.12); color:#FFD700;
                                                 border:1px solid rgba(255,215,0,0.28);">
                                        <i class="fa-solid fa-star" style="font-size:9px;"></i> Vedette
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td style="white-space:nowrap;">
                        <span style="font-size:14px; font-weight:800; color:#F5D020;">
                            <?php echo number_format((int)$lst['prix'], 0, ',', ' '); ?>
                        </span>
                        <span style="font-size:11px; color:rgba(245,208,32,0.55); margin-left:2px;">C</span>
                    </td>
                    <td style="font-size:13px; white-space:nowrap;">
                        <?php if ($lst['quantite'] === null): ?>
                        <span style="color:rgba(137,206,255,0.35);">
                            <i class="fa-solid fa-infinity"></i> Illimitée
                        </span>
                        <?php else: ?>
                        <span style="color:rgba(217,255,255,0.7);"><?php echo (int)$lst['quantite']; ?></span>
                        <span style="font-size:11px; color:rgba(137,206,255,0.35); margin-left:2px;">en stock</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:12px; color:rgba(137,206,255,0.55);">
                        <?php echo $lst['vendeur']
                            ? dblhv($lst['vendeur'])
                            : '<span style="color:rgba(137,206,255,0.2); font-style:italic;">—</span>'; ?>
                    </td>
                    <td>
                        <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 10px;
                                     border-radius:10px; font-size:11px; font-weight:700; white-space:nowrap;
                                     background:<?php echo $rmColor; ?>14; color:<?php echo $rmColor; ?>;
                                     border:1px solid <?php echo $rmColor; ?>38;">
                            <i class="fa-solid <?php echo $rmIcon; ?>" style="font-size:9px;"></i>
                            <?php echo dblhv($rmLabel); ?>
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <form method="POST" action="jdr-params?page=dokkaebi_bag&sub-page=list<?php echo $searchQs; ?>"
                              style="margin:0; display:inline;">
                            <input type="hidden" name="action" value="toggle_vedette">
                            <input type="hidden" name="id"     value="<?php echo (int)$lst['id']; ?>">
                            <?php if ($lst['is_vedette']): ?>
                            <button type="submit" title="Retirer de la vedette"
                                    style="background:rgba(255,215,0,0.15); border:1px solid rgba(255,215,0,0.4);
                                           border-radius:6px; cursor:pointer; padding:5px 9px;
                                           color:#FFD700; font-size:14px; transition:background .15s;"
                                    onmouseover="this.style.background='rgba(255,215,0,0.25)'"
                                    onmouseout="this.style.background='rgba(255,215,0,0.15)'">
                                <i class="fa-solid fa-star"></i>
                            </button>
                            <?php else: ?>
                            <button type="submit" title="Mettre en vedette"
                                    style="background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.1);
                                           border-radius:6px; cursor:pointer; padding:5px 9px;
                                           color:rgba(137,206,255,0.4); font-size:14px; transition:all .15s;"
                                    onmouseover="this.style.background='rgba(255,215,0,0.1)';this.style.color='rgba(255,215,0,0.7)';this.style.borderColor='rgba(255,215,0,0.3)'"
                                    onmouseout="this.style.background='rgba(255,255,255,0.04)';this.style.color='rgba(137,206,255,0.4)';this.style.borderColor='rgba(255,255,255,0.1)'">
                                <i class="fa-solid fa-star"></i>
                            </button>
                            <?php endif; ?>
                        </form>
                    </td>
                    <td style="text-align:center;">
                        <form method="POST" action="jdr-params?page=dokkaebi_bag&sub-page=list<?php echo $searchQs; ?>"
                              style="margin:0; display:inline;">
                            <input type="hidden" name="action" value="toggle_actif">
                            <input type="hidden" name="id"     value="<?php echo (int)$lst['id']; ?>">
                            <?php if ($lst['is_actif']): ?>
                            <button type="submit" title="Désactiver"
                                    style="background:rgba(46,204,113,0.15); border:1px solid rgba(46,204,113,0.4);
                                           border-radius:6px; cursor:pointer; padding:5px 9px;
                                           color:#2ECC71; font-size:14px; transition:background .15s;"
                                    onmouseover="this.style.background='rgba(46,204,113,0.25)'"
                                    onmouseout="this.style.background='rgba(46,204,113,0.15)'">
                                <i class="fa-solid fa-circle-check"></i>
                            </button>
                            <?php else: ?>
                            <button type="submit" title="Activer"
                                    style="background:rgba(239,83,80,0.12); border:1px solid rgba(239,83,80,0.35);
                                           border-radius:6px; cursor:pointer; padding:5px 9px;
                                           color:#EF5350; font-size:14px; transition:background .15s;"
                                    onmouseover="this.style.background='rgba(239,83,80,0.22)'"
                                    onmouseout="this.style.background='rgba(239,83,80,0.12)'">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </button>
                            <?php endif; ?>
                        </form>
                    </td>
                    <td>
                        <a href="jdr-params?page=dokkaebi_bag&sub-page=modify&id=<?php echo (int)$lst['id']; ?>"
                           style="background:#89CEFF; font-size:18px; background-clip:text !important;
                                  -webkit-background-clip:text !important; -webkit-text-fill-color:transparent; margin-right:8px;">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a href="jdr-params?page=dokkaebi_bag&sub-page=delete&id=<?php echo (int)$lst['id']; ?>"
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
