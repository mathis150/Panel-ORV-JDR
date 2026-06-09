<?php
    require_once './import/attributs.php';
    $am = new AttributesManager();
    $fd = $formData ?? [];
?>

<?php if ($formError ?? null): ?>
<div class="user-notice user-notice--error" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-xmark"></i> <?php echo htmlspecialchars($formError); ?>
</div>
<?php endif; ?>

<form method="POST" action="jdr-params?page=attributes&sub-page=create">
    <div class="orv-buttons" style="justify-content:flex-end;">
        <button type="submit" class="orv-edit">
            <i class="fa-solid fa-check"></i>
            <span style="font-size:18px;">Créer l'attribut</span>
        </button>
        <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=attributes&sub-page=list';">
            <i class="fa-solid fa-xmark"></i>
            <span style="font-size:18px;">Annuler</span>
        </button>
    </div>

    <div class="orv-menu">
        <div class="orv-menu_header">&lt;Création d'un attribut&gt;</div>
        <div class="orv-menu_container">

            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Informations générales :</h2>
                    <cite>Identité et classification de l'attribut.</cite>
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
                                <?php foreach (AttributesManager::RANGS as $r): ?>
                                <option value="<?php echo htmlspecialchars($r); ?>" <?php echo ($fd['rang'] ?? 'Commun') === $r ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($r); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Description :</h2>
                    <cite>Présentation générale de l'attribut et de ses effets.</cite>
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

        </div>
    </div>
</form>
