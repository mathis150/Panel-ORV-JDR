<?php
    require_once './import/scenarios.php';
    $sm = new ScenariosManager();
    $fd = $formData ?? [];
    function schv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }
?>

<?php if ($formError ?? null): ?>
<div class="user-notice user-notice--error" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-xmark"></i> <?php echo htmlspecialchars($formError); ?>
</div>
<?php endif; ?>

<form method="POST" action="jdr-params?page=scenarios&sub-page=create">
    <div class="orv-buttons" style="justify-content:flex-end;">
        <button type="submit" class="orv-edit">
            <i class="fa-solid fa-check"></i>
            <span style="font-size:18px;">Créer le scénario</span>
        </button>
        <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=scenarios&sub-page=list';">
            <i class="fa-solid fa-xmark"></i>
            <span style="font-size:18px;">Annuler</span>
        </button>
    </div>

    <div class="orv-menu">
        <div class="orv-menu_header">&lt;Création d'un scénario&gt;</div>
        <div class="orv-menu_container">

            <!-- Identité -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Identité :</h2>
                    <cite>Titre, type et numéro du scénario.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">

                        <div class="orv-menu_form-input" style="width:360px;">
                            <label>Titre <span style="color:#FF6B7A;">*</span></label>
                            <input type="text" name="titre"
                                   value="<?php echo schv($fd['titre'] ?? ''); ?>"
                                   required style="width:360px;">
                        </div>

                        <div class="orv-menu_form-input" style="width:180px;">
                            <label>Type <span style="color:#FF6B7A;">*</span></label>
                            <select name="type" id="sc_type_create" style="width:180px;"
                                    onchange="syncScType(this.value, 'create')">
                                <?php foreach (ScenariosManager::TYPES as $t): ?>
                                <option value="<?php echo schv($t); ?>"
                                    <?php echo ($fd['type'] ?? 'secondaire') === $t ? 'selected' : ''; ?>>
                                    <?php echo schv(ScenariosManager::TYPE_LABELS[$t]); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="orv-menu_form-input" id="sc_numero_wrap_create" style="width:120px; display:none;">
                            <label>Numéro</label>
                            <input type="number" name="numero" min="1"
                                   value="<?php echo schv($fd['numero'] ?? ''); ?>"
                                   placeholder="ex: 9" style="width:120px;">
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <!-- Difficulté -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Difficulté :</h2>
                    <cite>Rang de difficulté du scénario.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">

                        <div class="orv-menu_form-input" style="width:120px;">
                            <label>Rang <span style="color:#FF6B7A;">*</span></label>
                            <select name="rang" style="width:120px;">
                                <?php foreach (ScenariosManager::RANGS as $r): ?>
                                <?php $rc = ScenariosManager::RANG_COLORS[$r] ?? '#9E9E9E'; ?>
                                <option value="<?php echo schv($r); ?>"
                                    <?php echo ($fd['rang'] ?? 'F') === $r ? 'selected' : ''; ?>
                                    style="color:<?php echo $rc; ?>">
                                    <?php echo schv($r); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div style="display:flex; align-items:center; gap:8px; padding-top:22px;">
                            <input type="checkbox" name="rang_plus" id="sc_rang_plus_create" value="1"
                                   <?php echo !empty($fd['rang_plus']) ? 'checked' : ''; ?>>
                            <label for="sc_rang_plus_create" style="cursor:pointer;">Rang + (version améliorée)</label>
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <!-- Temps limite -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Temps limite :</h2>
                    <cite>Durée accordée pour compléter le scénario.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">

                        <div class="orv-menu_form-input" style="width:160px;">
                            <label>Unité</label>
                            <select name="temps_type" id="sc_temps_type_create" style="width:160px;"
                                    onchange="syncScTemps(this.value, 'create')">
                                <?php foreach (ScenariosManager::TEMPS_TYPES as $tt): ?>
                                <option value="<?php echo schv($tt); ?>"
                                    <?php echo ($fd['temps_type'] ?? 'aucune') === $tt ? 'selected' : ''; ?>>
                                    <?php echo schv(ScenariosManager::TEMPS_LABELS[$tt]); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="orv-menu_form-input" id="sc_temps_val_wrap_create" style="width:140px; display:none;">
                            <label>Valeur</label>
                            <input type="number" name="temps_valeur" min="1"
                                   value="<?php echo schv($fd['temps_valeur'] ?? ''); ?>"
                                   placeholder="ex: 30" style="width:140px;">
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <!-- Conséquence échec -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Conséquence en cas d'échec :</h2>
                    <cite>Ce qui arrive si le scénario est échoué.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:360px;">
                            <label>Information d'échec</label>
                            <input type="text" name="echec_info"
                                   value="<?php echo schv($fd['echec_info'] ?? ''); ?>"
                                   placeholder="ex: La mort" style="width:360px;">
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Description -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Description :</h2>
                    <cite>Résumé et détails du scénario (optionnel).</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:100%; max-width:700px;">
                            <label>Description</label>
                            <textarea name="description" rows="5" style="width:100%;"><?php echo schv($fd['description'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

<script>
function syncScType(type, suffix) {
    const wrap = document.getElementById('sc_numero_wrap_' + suffix);
    if (wrap) wrap.style.display = (type === 'principal') ? '' : 'none';
}
function syncScTemps(type, suffix) {
    const wrap = document.getElementById('sc_temps_val_wrap_' + suffix);
    if (wrap) wrap.style.display = (type !== 'aucune') ? '' : 'none';
}
// Init
syncScType(document.getElementById('sc_type_create').value, 'create');
syncScTemps(document.getElementById('sc_temps_type_create').value, 'create');
</script>
