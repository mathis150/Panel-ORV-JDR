<?php
    require_once './import/dokkaebi_shop.php';
    $dsm      = new DokkaebiShopManager();
    $itemUuid = trim($_GET['item_uuid'] ?? '');
    $search   = trim($_GET['search'] ?? '');
    $fd       = $formData ?? [];
    function dbchv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }

    // Étape 2 : l'objet est sélectionné — on affiche le formulaire de configuration
    if ($itemUuid !== '') {
        $selectedItem = $dsm->getItemByUUID($itemUuid);
        if (!$selectedItem) {
            echo '<div class="user-notice user-notice--error">Objet introuvable.</div>';
            return;
        }
    } else {
        $selectedItem = null;
    }
?>

<?php if ($formError ?? null): ?>
<div class="user-notice user-notice--error" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-xmark"></i> <?php echo htmlspecialchars($formError); ?>
</div>
<?php endif; ?>

<?php if ($selectedItem === null): ?>
<!-- ═══════════════════════════════════════════════════════════════ -->
<!-- ÉTAPE 1 : Navigateur d'objets                                 -->
<!-- ═══════════════════════════════════════════════════════════════ -->

<!-- Wrapper contrôle — même largeur que .orv-menu -->
<div style="width:min(1225px,calc(100% - 40px)); margin-top:20px; margin-bottom:0;">
    <form method="GET" action="jdr-params"
          style="display:flex; gap:10px; align-items:center; margin-bottom:0;">
        <input type="hidden" name="page" value="dokkaebi_bag">
        <input type="hidden" name="sub-page" value="create">

        <!-- Retour -->
        <button type="button" class="orv-edit"
                style="flex-shrink:0; font-size:13px; white-space:nowrap;"
                onclick="window.location.href='jdr-params?page=dokkaebi_bag&sub-page=list';">
            <i class="fa-solid fa-arrow-left"></i> Retour
        </button>

        <div style="width:1px; height:28px; background:rgba(55,174,254,0.18); flex-shrink:0;"></div>

        <!-- Champ de recherche -->
        <div style="flex:1; position:relative; min-width:0;">
            <i class="fa-solid fa-magnifying-glass"
               style="position:absolute; left:13px; top:50%; transform:translateY(-50%);
                      color:rgba(137,206,255,0.4); font-size:13px; pointer-events:none; z-index:1;"></i>
            <input type="text" name="search" value="<?php echo dbchv($search); ?>"
                   placeholder="Rechercher un objet par nom..."
                   style="width:100%; padding-left:36px; box-sizing:border-box;">
            <?php if ($search !== ''): ?>
            <a href="jdr-params?page=dokkaebi_bag&sub-page=create"
               style="position:absolute; right:10px; top:50%; transform:translateY(-50%);
                      color:rgba(137,206,255,0.35); font-size:13px; line-height:1; text-decoration:none;"
               title="Effacer"><i class="fa-solid fa-xmark"></i></a>
            <?php endif; ?>
        </div>

        <button type="submit" class="orv-edit"
                style="flex-shrink:0; font-size:13px; white-space:nowrap;">
            <i class="fa-solid fa-magnifying-glass"></i> Rechercher
        </button>
    </form>
</div>

<?php
    $availableItems = $dsm->getItemsNotInShop($search);
?>

<div class="orv-menu">
    <div class="orv-menu_header">
        &lt;Sélectionner un objet à mettre en vente&gt;
        <?php if ($search !== ''): ?>
        <span style="margin-left:10px; font-size:11px; color:rgba(137,206,255,0.45); font-weight:400;">
            — Résultats pour «&nbsp;<?php echo dbchv($search); ?>&nbsp;»
        </span>
        <?php endif; ?>
    </div>
    <div class="orv-menu_container" style="padding:0;">
        <?php if (empty($availableItems)): ?>
        <div style="padding:32px; text-align:center; color:rgba(137,206,255,0.35); font-style:italic;">
            <?php echo $search !== ''
                ? 'Aucun objet disponible pour « ' . dbchv($search) . ' ».'
                : 'Tous les objets du catalogue sont déjà dans le shop, ou le catalogue est vide.'; ?>
        </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <td>Nom</td>
                    <td>Type</td>
                    <td>Rang</td>
                    <td>Description</td>
                    <td width="100px"></td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($availableItems as $item): ?>
                <?php
                    $itColor = DokkaebiShopManager::ITEM_TYPE_COLORS[$item['type']] ?? '#7F8C8D';
                    $itIcon  = DokkaebiShopManager::ITEM_TYPE_ICONS[$item['type']]  ?? 'fa-box';
                    $itLabel = DokkaebiShopManager::ITEM_TYPE_LABELS[$item['type']] ?? $item['type'];
                    $rgColor = DokkaebiShopManager::RANG_COLORS[$item['rang']]      ?? '#9E9E9E';
                    $configUrl = 'jdr-params?page=dokkaebi_bag&sub-page=create&item_uuid=' . urlencode($item['uuid'])
                                 . ($search !== '' ? '&search=' . rawurlencode($search) : '');
                ?>
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span style="width:26px; height:26px; border-radius:6px; display:flex; align-items:center; justify-content:center;
                                         background:<?php echo $itColor; ?>18; color:<?php echo $itColor; ?>; font-size:11px; flex-shrink:0;">
                                <i class="fa-solid <?php echo $itIcon; ?>"></i>
                            </span>
                            <span style="font-weight:600; font-size:13px;"><?php echo dbchv($item['nom']); ?></span>
                        </div>
                    </td>
                    <td>
                        <span style="padding:1px 7px; border-radius:8px; font-size:10px; font-weight:700;
                                     background:<?php echo $itColor; ?>18; color:<?php echo $itColor; ?>;
                                     border:1px solid <?php echo $itColor; ?>40;">
                            <?php echo dbchv($itLabel); ?>
                        </span>
                    </td>
                    <td>
                        <span style="padding:1px 7px; border-radius:8px; font-size:11px; font-weight:800;
                                     background:<?php echo $rgColor; ?>18; color:<?php echo $rgColor; ?>;
                                     border:1px solid <?php echo $rgColor; ?>40; letter-spacing:.04em;">
                            <?php echo dbchv($item['rang']); ?>
                        </span>
                    </td>
                    <td style="font-size:12px; color:rgba(137,206,255,0.5); max-width:300px;">
                        <?php if ($item['description']): ?>
                        <span style="display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical; overflow:hidden;">
                            <?php echo dbchv($item['description']); ?>
                        </span>
                        <?php else: ?>
                        <span style="color:rgba(137,206,255,0.2); font-style:italic;">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo $configUrl; ?>"
                           class="orv-edit"
                           style="font-size:12px; padding:6px 14px; text-decoration:none;">
                            <i class="fa-solid fa-plus"></i> Configurer
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php else: ?>
<!-- ═══════════════════════════════════════════════════════════════ -->
<!-- ÉTAPE 2 : Configuration de la mise en vente                   -->
<!-- ═══════════════════════════════════════════════════════════════ -->

<?php
    $itColor = DokkaebiShopManager::ITEM_TYPE_COLORS[$selectedItem['type']] ?? '#7F8C8D';
    $itIcon  = DokkaebiShopManager::ITEM_TYPE_ICONS[$selectedItem['type']]  ?? 'fa-box';
    $itLabel = DokkaebiShopManager::ITEM_TYPE_LABELS[$selectedItem['type']] ?? $selectedItem['type'];
    $rgColor = DokkaebiShopManager::RANG_COLORS[$selectedItem['rang']]      ?? '#9E9E9E';
?>

<form method="POST" action="jdr-params?page=dokkaebi_bag&sub-page=create">
    <input type="hidden" name="item_uuid" value="<?php echo dbchv($selectedItem['uuid']); ?>">

    <div class="orv-buttons" style="justify-content:flex-end;">
        <button type="submit" class="orv-edit">
            <i class="fa-solid fa-check"></i>
            <span style="font-size:18px;">Mettre en vente</span>
        </button>
        <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=dokkaebi_bag&sub-page=create<?php echo $search !== '' ? '&search=' . rawurlencode($search) : ''; ?>';">
            <i class="fa-solid fa-arrow-left"></i>
            <span style="font-size:18px;">Retour</span>
        </button>
    </div>

    <div class="orv-menu">
        <div class="orv-menu_header">&lt;Configuration de la mise en vente&gt;</div>
        <div class="orv-menu_container">

            <!-- Aperçu de l'objet -->
            <div style="display:flex; align-items:center; gap:14px; padding:14px 20px;
                        background:rgba(55,174,254,0.04); border:1px solid rgba(55,174,254,0.14);
                        border-radius:8px; margin-bottom:20px;">
                <span style="width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center;
                             background:<?php echo $itColor; ?>22; color:<?php echo $itColor; ?>; font-size:20px; flex-shrink:0;">
                    <i class="fa-solid <?php echo $itIcon; ?>"></i>
                </span>
                <div>
                    <div style="font-size:16px; font-weight:700; color:rgba(217,255,255,0.9);">
                        <?php echo dbchv($selectedItem['nom']); ?>
                    </div>
                    <div style="display:flex; gap:6px; margin-top:4px;">
                        <span style="padding:1px 7px; border-radius:8px; font-size:10px; font-weight:700;
                                     background:<?php echo $itColor; ?>18; color:<?php echo $itColor; ?>; border:1px solid <?php echo $itColor; ?>40;">
                            <?php echo dbchv($itLabel); ?>
                        </span>
                        <span style="padding:1px 7px; border-radius:8px; font-size:11px; font-weight:800;
                                     background:<?php echo $rgColor; ?>18; color:<?php echo $rgColor; ?>; border:1px solid <?php echo $rgColor; ?>40; letter-spacing:.04em;">
                            <?php echo dbchv($selectedItem['rang']); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Prix & Quantité -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Prix & Disponibilité :</h2>
                    <cite>Prix en Coins et stock disponible.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">

                        <div class="orv-menu_form-input" style="width:180px;">
                            <label>Prix (C) <span style="color:#FF6B7A;">*</span></label>
                            <input type="number" name="prix" min="0"
                                   value="<?php echo dbchv($fd['prix'] ?? 0); ?>"
                                   required style="width:180px;">
                        </div>

                        <div style="padding-top:22px; display:flex; align-items:center; gap:8px;">
                            <input type="checkbox" name="illimitee" id="db_illimitee_c" value="1"
                                   <?php echo !empty($fd['illimitee']) ? 'checked' : ''; ?>
                                   onchange="syncQteCreate(this.checked)">
                            <label for="db_illimitee_c" style="cursor:pointer; white-space:nowrap;">Quantité illimitée</label>
                        </div>

                        <div class="orv-menu_form-input" id="qte_wrap_create" style="width:140px; <?php echo !empty($fd['illimitee']) ? 'display:none;' : ''; ?>">
                            <label>Quantité disponible</label>
                            <input type="number" name="quantite" min="1"
                                   value="<?php echo dbchv($fd['quantite'] ?? 1); ?>"
                                   style="width:140px;">
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <!-- Vendeur & Accès -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Vendeur & Accès :</h2>
                    <cite>Nom du vendeur et rang minimum requis.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">

                        <div class="orv-menu_form-input" style="width:280px;">
                            <label>Vendeur</label>
                            <input type="text" name="vendeur"
                                   value="<?php echo dbchv($fd['vendeur'] ?? ''); ?>"
                                   placeholder="ex: Dokkaebi" style="width:280px;">
                        </div>

                        <div class="orv-menu_form-input" style="width:180px;">
                            <label>Rang minimum requis</label>
                            <select name="rang_min" style="width:180px;">
                                <?php foreach (DokkaebiShopManager::RANGS_MIN as $rm): ?>
                                <?php $rmColor = DokkaebiShopManager::RANG_MIN_COLORS[$rm]; ?>
                                <option value="<?php echo dbchv($rm); ?>"
                                    <?php echo ($fd['rang_min'] ?? 'iron') === $rm ? 'selected' : ''; ?>
                                    style="color:<?php echo $rmColor; ?>">
                                    <?php echo dbchv(DokkaebiShopManager::RANG_MIN_LABELS[$rm]); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <!-- Mise en vedette -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Mise en vedette :</h2>
                    <cite>Afficher cet objet dans la section « En vedette ».</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div style="display:flex; align-items:center; gap:8px; padding-top:6px;">
                            <input type="checkbox" name="is_vedette" id="db_vedette_c" value="1"
                                   <?php echo !empty($fd['is_vedette']) ? 'checked' : ''; ?>>
                            <label for="db_vedette_c" style="cursor:pointer;">
                                <i class="fa-solid fa-star" style="color:#FFD700;"></i> Mettre en vedette
                            </label>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

<script>
function syncQteCreate(illimitee) {
    const wrap = document.getElementById('qte_wrap_create');
    if (wrap) wrap.style.display = illimitee ? 'none' : '';
}
</script>

<?php endif; ?>
