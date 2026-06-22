<?php
    require_once './import/items.php';
    $fd = $formData ?? [];
    function ichv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }
?>

<?php if ($formError ?? null): ?>
<div class="user-notice user-notice--error" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-xmark"></i> <?php echo htmlspecialchars($formError); ?>
</div>
<?php endif; ?>

<div class="orv-buttons" style="justify-content:flex-end;">
    <button type="button" class="orv-edit"
            onclick="window.location.href='jdr-params?page=items&sub-page=list';">
        <i class="fa-solid fa-arrow-left"></i>
        <span style="font-size:18px;">Retour à la liste</span>
    </button>
</div>

<form method="POST" action="jdr-params?page=items&sub-page=create">

    <div class="orv-menu">
        <div class="orv-menu_header">&lt;Créer un objet&gt;</div>
        <div class="orv-menu_container">

            <!-- Identité -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Identité :</h2>
                    <cite>Nom, type et rang de l'objet.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">

                        <div class="orv-menu_form-input" style="width:340px;">
                            <label>Nom <span style="color:#FF6B7A;">*</span></label>
                            <input type="text" name="nom" required
                                   value="<?php echo ichv($fd['nom'] ?? ''); ?>"
                                   placeholder="ex: Épée de Sang du Roi Démon"
                                   style="width:340px;">
                        </div>

                        <div class="orv-menu_form-input" style="width:200px;">
                            <label>Type <span style="color:#FF6B7A;">*</span></label>
                            <select name="type" style="width:200px;" required>
                                <?php foreach (ItemsManager::TYPES as $t):
                                    $tColor = ItemsManager::TYPE_COLORS[$t];
                                    $tLabel = ItemsManager::TYPE_LABELS[$t];
                                ?>
                                <option value="<?php echo ichv($t); ?>"
                                    <?php echo ($fd['type'] ?? 'objet') === $t ? 'selected' : ''; ?>
                                    style="color:<?php echo $tColor; ?>">
                                    <?php echo ichv($tLabel); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="orv-menu_form-input" style="width:180px;">
                            <label>Rang <span style="color:#FF6B7A;">*</span></label>
                            <select name="rang" style="width:180px;" required>
                                <?php foreach (ItemsManager::RANGS as $r):
                                    $rColor = ItemsManager::RANG_COLORS[$r];
                                    $rLabel = ItemsManager::RANG_LABELS[$r];
                                ?>
                                <option value="<?php echo ichv($r); ?>"
                                    <?php echo ($fd['rang'] ?? 'F') === $r ? 'selected' : ''; ?>
                                    style="color:<?php echo $rColor; ?>">
                                    <?php echo ichv($rLabel); ?>
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
                    <cite>Description et lore de l'objet.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:100%;">
                            <label>Description</label>
                            <textarea name="description" rows="4"
                                      placeholder="Décrivez l'objet, ses effets, son lore..."
                                      style="width:100%; resize:vertical;"><?php echo ichv($fd['description'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Statut -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Statut :</h2>
                    <cite>Visibilité de l'objet dans le catalogue.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div style="display:flex; align-items:center; gap:8px; padding-top:6px;">
                            <input type="checkbox" name="is_active" id="ic_active" value="1"
                                   <?php echo !empty($fd) ? (!empty($fd['is_active']) ? 'checked' : '') : 'checked'; ?>>
                            <label for="ic_active" style="cursor:pointer;">
                                <i class="fa-solid fa-circle-check" style="color:#2ECC71;"></i> Actif dans le catalogue
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; padding:16px 20px 4px;">
                <button type="submit" class="orv-edit">
                    <i class="fa-solid fa-plus"></i>
                    <span style="font-size:16px;">Créer l'objet</span>
                </button>
            </div>

        </div>
    </div>

</form>
