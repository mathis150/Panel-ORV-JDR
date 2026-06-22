<?php
    require_once './import/dokkaebi_shop.php';
    $dsm  = new DokkaebiShopManager();
    $id   = (int)($_GET['id'] ?? 0);
    $lst  = $dsm->getShopListingById($id);

    if (!$lst) {
        echo '<div class="user-notice user-notice--error">Mise en vente introuvable.</div>';
        return;
    }

    $fd       = $formData ?? [];
    $itColor  = DokkaebiShopManager::ITEM_TYPE_COLORS[$lst['item_type']] ?? '#7F8C8D';
    $itIcon   = DokkaebiShopManager::ITEM_TYPE_ICONS[$lst['item_type']]  ?? 'fa-box';
    $itLabel  = DokkaebiShopManager::ITEM_TYPE_LABELS[$lst['item_type']] ?? $lst['item_type'];
    $rgColor  = DokkaebiShopManager::RANG_COLORS[$lst['item_rang']]      ?? '#9E9E9E';
    $baseUrl  = 'jdr-params?page=dokkaebi_bag&sub-page=modify&id=' . $id;

    function dbmhv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }

    $currentIllimitee = !empty($fd) ? !empty($fd['illimitee']) : ($lst['quantite'] === null);
    $currentQte       = !empty($fd) ? ($fd['quantite'] ?? 1) : ($lst['quantite'] ?? 1);
    $currentPrix      = !empty($fd) ? ($fd['prix'] ?? 0) : $lst['prix'];
    $currentVendeur   = !empty($fd) ? ($fd['vendeur'] ?? '') : ($lst['vendeur'] ?? '');
    $currentRangMin   = !empty($fd) ? ($fd['rang_min'] ?? $lst['rang_min']) : $lst['rang_min'];
    $currentVedette   = !empty($fd) ? !empty($fd['is_vedette']) : (bool)$lst['is_vedette'];
    $currentActif     = !empty($fd) ? !empty($fd['is_actif'])   : (bool)$lst['is_actif'];
?>

<?php if ($notice ?? ''): ?>
<div class="user-notice" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-check"></i> <?php echo $notice; ?>
</div>
<?php endif; ?>
<?php if ($formError ?? null): ?>
<div class="user-notice user-notice--error" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-xmark"></i> <?php echo htmlspecialchars($formError); ?>
</div>
<?php endif; ?>

<div class="orv-buttons" style="justify-content:flex-end;">
    <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=dokkaebi_bag&sub-page=list';">
        <i class="fa-solid fa-arrow-left"></i>
        <span style="font-size:18px;">Retour à la liste</span>
    </button>
</div>

<div class="orv-menu">
    <div class="orv-menu_header">
        &lt;Modifier la mise en vente&gt;
    </div>
    <div class="orv-menu_container">

        <!-- Aperçu de l'objet (non modifiable) -->
        <div style="display:flex; align-items:center; gap:14px; padding:14px 20px;
                    background:rgba(55,174,254,0.04); border:1px solid rgba(55,174,254,0.14);
                    border-radius:8px; margin-bottom:24px;">
            <span style="width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center;
                         background:<?php echo $itColor; ?>22; color:<?php echo $itColor; ?>; font-size:20px; flex-shrink:0;">
                <i class="fa-solid <?php echo $itIcon; ?>"></i>
            </span>
            <div>
                <div style="font-size:16px; font-weight:700; color:rgba(217,255,255,0.9);">
                    <?php echo dbmhv($lst['nom']); ?>
                </div>
                <div style="display:flex; gap:6px; margin-top:4px;">
                    <span style="padding:1px 7px; border-radius:8px; font-size:10px; font-weight:700;
                                 background:<?php echo $itColor; ?>18; color:<?php echo $itColor; ?>; border:1px solid <?php echo $itColor; ?>40;">
                        <?php echo dbmhv($itLabel); ?>
                    </span>
                    <span style="padding:1px 7px; border-radius:8px; font-size:11px; font-weight:800;
                                 background:<?php echo $rgColor; ?>18; color:<?php echo $rgColor; ?>; border:1px solid <?php echo $rgColor; ?>40; letter-spacing:.04em;">
                        <?php echo dbmhv($lst['item_rang']); ?>
                    </span>
                    <?php if ($lst['is_vedette']): ?>
                    <span style="padding:1px 7px; border-radius:8px; font-size:10px; font-weight:700;
                                 background:rgba(255,215,0,0.12); color:#FFD700; border:1px solid rgba(255,215,0,0.3);">
                        <i class="fa-solid fa-star"></i> En vedette
                    </span>
                    <?php endif; ?>
                </div>
            </div>
            <div style="margin-left:auto; text-align:right;">
                <div style="font-size:20px; font-weight:800; color:#F5D020;">
                    <?php echo number_format((int)$lst['prix'], 0, ',', ' '); ?> C
                </div>
                <div style="font-size:12px; color:rgba(137,206,255,0.4); margin-top:2px;">
                    <?php echo $lst['quantite'] === null ? '<i class="fa-solid fa-infinity"></i> Illimitée' : (int)$lst['quantite'] . ' en stock'; ?>
                </div>
            </div>
        </div>

        <form method="POST" action="<?php echo $baseUrl; ?>">
            <input type="hidden" name="action" value="update">

            <div style="display:flex; justify-content:flex-end; margin-bottom:20px;">
                <button type="submit" class="orv-edit">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span style="font-size:16px;">Sauvegarder</span>
                </button>
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
                                   value="<?php echo dbmhv($currentPrix); ?>"
                                   required style="width:180px;">
                        </div>

                        <div style="padding-top:22px; display:flex; align-items:center; gap:8px;">
                            <input type="checkbox" name="illimitee" id="db_illimitee_m" value="1"
                                   <?php echo $currentIllimitee ? 'checked' : ''; ?>
                                   onchange="syncQteModify(this.checked)">
                            <label for="db_illimitee_m" style="cursor:pointer; white-space:nowrap;">Quantité illimitée</label>
                        </div>

                        <div class="orv-menu_form-input" id="qte_wrap_modify"
                             style="width:140px; <?php echo $currentIllimitee ? 'display:none;' : ''; ?>">
                            <label>Quantité disponible</label>
                            <input type="number" name="quantite" min="1"
                                   value="<?php echo dbmhv($currentQte); ?>"
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
                                   value="<?php echo dbmhv($currentVendeur); ?>"
                                   placeholder="ex: Dokkaebi" style="width:280px;">
                        </div>

                        <div class="orv-menu_form-input" style="width:180px;">
                            <label>Rang minimum requis</label>
                            <select name="rang_min" style="width:180px;">
                                <?php foreach (DokkaebiShopManager::RANGS_MIN as $rm): ?>
                                <?php $rmColor = DokkaebiShopManager::RANG_MIN_COLORS[$rm]; ?>
                                <option value="<?php echo dbmhv($rm); ?>"
                                    <?php echo $currentRangMin === $rm ? 'selected' : ''; ?>
                                    style="color:<?php echo $rmColor; ?>">
                                    <?php echo dbmhv(DokkaebiShopManager::RANG_MIN_LABELS[$rm]); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <!-- Visibilité -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Visibilité :</h2>
                    <cite>Mise en vedette et statut actif.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">

                        <div style="display:flex; align-items:center; gap:8px; padding-top:6px;">
                            <input type="checkbox" name="is_vedette" id="db_vedette_m" value="1"
                                   <?php echo $currentVedette ? 'checked' : ''; ?>>
                            <label for="db_vedette_m" style="cursor:pointer;">
                                <i class="fa-solid fa-star" style="color:#FFD700;"></i> En vedette
                            </label>
                        </div>

                        <div style="display:flex; align-items:center; gap:8px; padding-top:6px;">
                            <input type="checkbox" name="is_actif" id="db_actif_m" value="1"
                                   <?php echo $currentActif ? 'checked' : ''; ?>>
                            <label for="db_actif_m" style="cursor:pointer;">
                                <i class="fa-solid fa-circle-check" style="color:#2ECC71;"></i> Mise en vente active
                            </label>
                        </div>

                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
function syncQteModify(illimitee) {
    const wrap = document.getElementById('qte_wrap_modify');
    if (wrap) wrap.style.display = illimitee ? 'none' : '';
}
</script>
