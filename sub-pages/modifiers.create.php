<?php
    require_once './import/modifiers.php';
    $mm        = new ModifiersManager();
    $histories = $mm->getAvailableHistories();
    $fd        = $formData ?? [];
    function mchv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }
?>

<?php if ($formError ?? null): ?>
<div class="user-notice user-notice--error" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-xmark"></i> <?php echo htmlspecialchars($formError); ?>
</div>
<?php endif; ?>

<form method="POST" action="jdr-params?page=modifiers&sub-page=create">
    <div class="orv-buttons" style="justify-content:flex-end;">
        <button type="submit" class="orv-edit">
            <i class="fa-solid fa-check"></i>
            <span style="font-size:18px;">Créer le modificateur</span>
        </button>
        <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=modifiers&sub-page=list';">
            <i class="fa-solid fa-xmark"></i>
            <span style="font-size:18px;">Annuler</span>
        </button>
    </div>

    <div class="orv-menu">
        <div class="orv-menu_header">&lt;Création d'un modificateur&gt;</div>
        <div class="orv-menu_container">

            <!-- Identité -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Identité :</h2>
                    <cite>Nom et type du modificateur.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">

                        <div class="orv-menu_form-input" style="width:340px;">
                            <label>Nom <span style="color:#FF6B7A;">*</span></label>
                            <input type="text" name="nom"
                                   value="<?php echo mchv($fd['nom'] ?? ''); ?>"
                                   required style="width:340px;">
                        </div>

                        <div class="orv-menu_form-input" style="width:240px;">
                            <label>Type <span style="color:#FF6B7A;">*</span></label>
                            <select name="type" style="width:240px;">
                                <?php foreach (ModifiersManager::TYPES as $t): ?>
                                <option value="<?php echo mchv($t); ?>"
                                    <?php echo ($fd['type'] ?? 'titre') === $t ? 'selected' : ''; ?>>
                                    <?php echo mchv(ModifiersManager::TYPE_LABELS[$t]); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <!-- Histoire liée -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Histoire liée :</h2>
                    <cite>L'histoire à laquelle ce modificateur est rattaché.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:380px;">
                            <label>Histoire <span style="color:#FF6B7A;">*</span></label>
                            <select name="histoire_uuid" style="width:380px;" required>
                                <option value="">— Choisir une histoire —</option>
                                <?php foreach ($histories as $h): ?>
                                <option value="<?php echo mchv($h['uuid']); ?>"
                                    <?php echo ($fd['histoire_uuid'] ?? '') === $h['uuid'] ? 'selected' : ''; ?>>
                                    <?php echo mchv($h['titre']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Description -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Description :</h2>
                    <cite>Description du modificateur (optionnel).</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:100%; max-width:700px;">
                            <label>Description</label>
                            <textarea name="description" rows="5" style="width:100%;"><?php echo mchv($fd['description'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
