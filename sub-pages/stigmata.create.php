<?php
    require_once './import/stigmates.php';
    $sm        = new StigmataManager();
    $histories = $sm->getAvailableHistories();
    $fd        = $formData ?? [];
?>

<?php if ($formError ?? null): ?>
<div class="user-notice user-notice--error" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-xmark"></i> <?php echo htmlspecialchars($formError); ?>
</div>
<?php endif; ?>

<form method="POST" action="jdr-params?page=stigmata&sub-page=create">
    <div class="orv-buttons" style="justify-content:flex-end;">
        <button type="submit" class="orv-edit">
            <i class="fa-solid fa-check"></i>
            <span style="font-size:18px;">Créer le stigmate</span>
        </button>
        <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=stigmata&sub-page=list';">
            <i class="fa-solid fa-xmark"></i>
            <span style="font-size:18px;">Annuler</span>
        </button>
    </div>

    <div class="orv-menu">
        <div class="orv-menu_header">&lt;Création d'un stigmate&gt;</div>
        <div class="orv-menu_container">

            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Informations générales :</h2>
                    <cite>Identité et classification du stigmate.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:280px;">
                            <label>Nom <span style="color:#FF6B7A;">*</span></label>
                            <input type="text" name="nom" value="<?php echo htmlspecialchars($fd['nom'] ?? ''); ?>" required style="width:280px;">
                        </div>
                        <div class="orv-menu_form-input" style="width:180px;">
                            <label>Rang <span style="color:#FF6B7A;">*</span></label>
                            <select name="rang" style="width:180px;">
                                <?php foreach (StigmataManager::RANGS as $r): ?>
                                <option value="<?php echo $r; ?>" <?php echo ($fd['rang'] ?? 'E') === $r ? 'selected' : ''; ?>>
                                    Rang <?php echo $r; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="orv-menu_form-input" style="width:260px;">
                            <label>Histoire liée</label>
                            <?php if (!empty($histories)): ?>
                            <select name="histoire_uuid" style="width:260px;">
                                <option value="">— Aucune —</option>
                                <?php foreach ($histories as $h): ?>
                                <option value="<?php echo htmlspecialchars($h['uuid']); ?>"
                                    <?php echo ($fd['histoire_uuid'] ?? '') === $h['uuid'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($h['titre']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <?php else: ?>
                            <input type="text" value="Aucune histoire disponible" style="width:260px;" disabled>
                            <small style="color:rgba(137,206,255,0.4); font-size:11px;">Créez des histoires dans "Gestion des histoires".</small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Description :</h2>
                    <cite>Présentation générale du stigmate.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:100%; max-width:700px;">
                            <label>Description générale</label>
                            <textarea name="description" rows="5" style="width:100%;"><?php echo htmlspecialchars($fd['description'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Effets visuels :</h2>
                    <cite>Description de ce que le stigmate produit visuellement (marque, aura, transformation…).</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:100%; max-width:700px;">
                            <label>Description des effets visuels</label>
                            <textarea name="effets_visuels" rows="5" style="width:100%;"><?php echo htmlspecialchars($fd['effets_visuels'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
