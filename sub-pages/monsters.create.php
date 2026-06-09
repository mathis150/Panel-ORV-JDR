<?php
    require_once './import/monsters.php';
    $mm = new MonstersManager();
    $fd = $formData ?? [];
?>

<?php if ($formError ?? null): ?>
<div class="user-notice user-notice--error" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-xmark"></i> <?php echo htmlspecialchars($formError); ?>
</div>
<?php endif; ?>

<form method="POST" action="jdr-params?page=monsters&sub-page=create">
    <div class="orv-buttons" style="justify-content:flex-end;">
        <button type="submit" class="orv-edit">
            <i class="fa-solid fa-check"></i>
            <span style="font-size:18px;">Créer le monstre</span>
        </button>
        <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=monsters&sub-page=list';">
            <i class="fa-solid fa-xmark"></i>
            <span style="font-size:18px;">Annuler</span>
        </button>
    </div>

    <div class="orv-menu">
        <div class="orv-menu_header">&lt;Création d'un monstre&gt;</div>
        <div class="orv-menu_container">

            <!-- Identité -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Identité :</h2>
                    <cite>Informations générales du monstre.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:260px;">
                            <label>Nom <span style="color:#FF6B7A;">*</span></label>
                            <input type="text" name="nom" value="<?php echo htmlspecialchars($fd['nom'] ?? ''); ?>" required style="width:260px;">
                        </div>
                        <div class="orv-menu_form-input" style="width:200px;">
                            <label>Race</label>
                            <input type="text" name="race" value="<?php echo htmlspecialchars($fd['race'] ?? ''); ?>" style="width:200px;" placeholder="ex: Gobelin, Orc…">
                        </div>
                        <div class="orv-menu_form-input" style="width:200px;">
                            <label>Rang <span style="color:#FF6B7A;">*</span></label>
                            <select name="rank" style="width:200px;">
                                <?php
                                $rankOpts = [
                                    1 => 'Rang 1 — Le plus puissant',
                                    2 => 'Rang 2', 3 => 'Rang 3', 4 => 'Rang 4', 5 => 'Rang 5',
                                    6 => 'Rang 6', 7 => 'Rang 7', 8 => 'Rang 8',
                                    9 => 'Rang 9 — Le plus faible',
                                ];
                                $selRank = (int)($fd['rank'] ?? 9);
                                foreach ($rankOpts as $val => $lbl):
                                ?>
                                <option value="<?php echo $val; ?>" <?php echo $selRank === $val ? 'selected' : ''; ?>>
                                    <?php echo $lbl; ?>
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
                    <cite>Apparence et comportement du monstre.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:100%; max-width:700px;">
                            <label>Description</label>
                            <textarea name="description" rows="5" style="width:100%;"><?php echo htmlspecialchars($fd['description'] ?? ''); ?></textarea>
                        </div>
                        <div class="orv-menu_form-input" style="width:100%; max-width:700px;">
                            <label>Notes MJ</label>
                            <textarea name="notes" rows="3" style="width:100%;"><?php echo htmlspecialchars($fd['notes'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Stats -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Statistiques :</h2>
                    <cite>Valeurs de combat du monstre.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:140px;">
                            <label>Vitalité</label>
                            <input type="number" name="vitalite" value="<?php echo (int)($fd['vitalite'] ?? 100); ?>" min="0" style="width:140px;">
                        </div>
                        <div class="orv-menu_form-input" style="width:140px;">
                            <label>Force</label>
                            <input type="number" name="force" value="<?php echo (int)($fd['force'] ?? 0); ?>" min="0" style="width:140px;">
                        </div>
                        <div class="orv-menu_form-input" style="width:140px;">
                            <label>Agilité</label>
                            <input type="number" name="agilite" value="<?php echo (int)($fd['agilite'] ?? 0); ?>" min="0" style="width:140px;">
                        </div>
                        <div class="orv-menu_form-input" style="width:140px;">
                            <label>Mana</label>
                            <input type="number" name="mana" value="<?php echo (int)($fd['mana'] ?? 0); ?>" min="0" style="width:140px;">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
