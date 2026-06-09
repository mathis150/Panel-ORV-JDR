<?php
    require_once './import/histories.php';
    $hm = new HistoriesManager();
    $fd = $formData ?? [];
?>

<?php if ($formError ?? null): ?>
<div class="user-notice user-notice--error" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-xmark"></i> <?php echo htmlspecialchars($formError); ?>
</div>
<?php endif; ?>

<form method="POST" action="jdr-params?page=histories&sub-page=create">
    <div class="orv-buttons" style="justify-content:flex-end;">
        <button type="submit" class="orv-edit">
            <i class="fa-solid fa-check"></i>
            <span style="font-size:18px;">Créer l'histoire</span>
        </button>
        <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=histories&sub-page=list';">
            <i class="fa-solid fa-xmark"></i>
            <span style="font-size:18px;">Annuler</span>
        </button>
    </div>

    <div class="orv-menu">
        <div class="orv-menu_header">&lt;Création d'une histoire&gt;</div>
        <div class="orv-menu_container">

            <!-- Identité -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Identité :</h2>
                    <cite>Titre, rang et classification de l'histoire.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">

                        <div class="orv-menu_form-input" style="width:320px;">
                            <label>Titre <span style="color:#FF6B7A;">*</span></label>
                            <input type="text" name="titre" value="<?php echo htmlspecialchars($fd['titre'] ?? ''); ?>" required style="width:320px;">
                        </div>

                        <div class="orv-menu_form-input" style="width:220px;">
                            <label>Rang <span style="color:#FF6B7A;">*</span></label>
                            <select name="rang" id="hist_rang_create" style="width:220px;" onchange="syncHistRang(this.value, 'create')">
                                <?php foreach (HistoriesManager::RANGS as $r): ?>
                                <?php $rc = HistoriesManager::RANG_COLORS[$r] ?? '#89CEFF'; ?>
                                <option value="<?php echo htmlspecialchars($r); ?>"
                                    <?php echo ($fd['rang'] ?? 'Historique bas-rang') === $r ? 'selected' : ''; ?>
                                    style="color:<?php echo $rc; ?>">
                                    <?php echo htmlspecialchars($r); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Fraction de propriété (Histoire Gigantesque uniquement) -->
                        <div class="orv-menu_form-input" id="ownership_wrap_create" style="width:180px; display:none;">
                            <label>Fraction de propriété (%)</label>
                            <input type="number" name="ownership_pct" min="1" max="100"
                                   value="<?php echo (int)($fd['ownership_pct'] ?? 100); ?>"
                                   placeholder="ex: 30" style="width:180px;">
                        </div>

                    </div>

                    <div class="orv-menu_form-inputs-list" style="margin-top:10px;">

                        <div class="orv-menu_form-input" style="display:flex; flex-direction:row; align-items:center; gap:8px; padding-top:22px;">
                            <input type="checkbox" name="is_perso" id="is_perso_create" value="1"
                                   <?php echo !empty($fd['is_perso']) ? 'checked' : ''; ?>>
                            <label for="is_perso_create" style="cursor:pointer;">Histoire du personnage</label>
                        </div>

                        <div class="orv-menu_form-input" style="display:flex; flex-direction:row; align-items:center; gap:8px; padding-top:22px;">
                            <input type="checkbox" name="is_fondatrice" id="is_fondatrice_create" value="1"
                                   <?php echo !empty($fd['is_fondatrice']) ? 'checked' : ''; ?>>
                            <label for="is_fondatrice_create" style="cursor:pointer;">Histoire fondatrice</label>
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <!-- Statut -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Statut :</h2>
                    <cite>L'histoire est-elle complète ou brisée ?</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">

                        <div class="orv-menu_form-input" style="width:180px;">
                            <label>Statut</label>
                            <select name="statut" id="hist_statut_create" style="width:180px;" onchange="syncHistStatut(this.value, 'create')">
                                <option value="complete" <?php echo ($fd['statut'] ?? 'complete') === 'complete' ? 'selected' : ''; ?>>Complète</option>
                                <option value="brisee"   <?php echo ($fd['statut'] ?? '') === 'brisee' ? 'selected' : ''; ?>>Brisée</option>
                            </select>
                        </div>

                        <div class="orv-menu_form-input" id="completion_wrap_create" style="width:180px; display:none;">
                            <label>Complétion (%)</label>
                            <input type="number" name="completion_pct" min="1" max="99"
                                   value="<?php echo (int)($fd['completion_pct'] ?? 50); ?>"
                                   placeholder="ex: 45" style="width:180px;">
                            <small style="color:rgba(137,206,255,0.4); font-size:11px;">Entre 1% et 99%</small>
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <!-- Description -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Description :</h2>
                    <cite>Résumé et lore de l'histoire.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:100%; max-width:700px;">
                            <label>Description générale</label>
                            <textarea name="description" rows="6" style="width:100%;"><?php echo htmlspecialchars($fd['description'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

<script>
function syncHistRang(rang, suffix) {
    const wrap = document.getElementById('ownership_wrap_' + suffix);
    if (wrap) wrap.style.display = (rang === 'Histoire Gigantesque') ? '' : 'none';
}
function syncHistStatut(statut, suffix) {
    const wrap = document.getElementById('completion_wrap_' + suffix);
    if (wrap) wrap.style.display = (statut === 'brisee') ? '' : 'none';
}
// Init
syncHistRang(document.getElementById('hist_rang_create').value, 'create');
syncHistStatut(document.getElementById('hist_statut_create').value, 'create');
</script>
