<?php
    $fd = $formData ?? [];
    $rangOpts = [
        'mythique'        => 'Mythique',
        'legendaire'      => 'Légendaire',
        'historique_haut' => 'Historique haut-rang',
        'historique_bas'  => 'Historique bas-rang',
    ];
?>

<?php if ($formError ?? null): ?>
<div class="user-notice user-notice--error" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-xmark"></i> <?php echo htmlspecialchars($formError); ?>
</div>
<?php endif; ?>

<form method="POST" action="jdr-params?page=constellations&sub-page=create">
    <div class="orv-buttons" style="justify-content:flex-end;">
        <button type="submit" class="orv-edit">
            <i class="fa-solid fa-check"></i>
            <span style="font-size:18px;">Créer la constellation</span>
        </button>
        <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=constellations&sub-page=list';">
            <i class="fa-solid fa-xmark"></i>
            <span style="font-size:18px;">Annuler</span>
        </button>
    </div>

    <div class="orv-menu">
        <div class="orv-menu_header">&lt;Création d'une constellation&gt;</div>
        <div class="orv-menu_container">

            <!-- Informations générales -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Informations générales :</h2>
                    <cite>Identité et classification de la constellation.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:260px;">
                            <label>Nom <span style="color:#FF6B7A;">*</span></label>
                            <input type="text" name="nom" value="<?php echo htmlspecialchars($fd['nom'] ?? ''); ?>" required style="width:260px;">
                        </div>
                        <div class="orv-menu_form-input" style="width:240px;">
                            <label>Identité</label>
                            <input type="text" name="identite" value="<?php echo htmlspecialchars($fd['identite'] ?? ''); ?>" style="width:240px;" placeholder="Alias, surnom…">
                        </div>
                        <div class="orv-menu_form-input" style="width:220px;">
                            <label>Provenance</label>
                            <input type="text" name="provenance" value="<?php echo htmlspecialchars($fd['provenance'] ?? ''); ?>" style="width:220px;">
                        </div>
                        <div class="orv-menu_form-input" style="width:200px;">
                            <label>Rang</label>
                            <select name="rang" style="width:200px;">
                                <?php foreach ($rangOpts as $val => $lbl): ?>
                                <option value="<?php echo $val; ?>" <?php echo ($fd['rang'] ?? 'historique_bas') === $val ? 'selected' : ''; ?>>
                                    <?php echo $lbl; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="orv-menu_form-input" style="width:200px;">
                            <label>Race</label>
                            <input type="text" name="race" value="<?php echo htmlspecialchars($fd['race'] ?? ''); ?>" style="width:200px;">
                        </div>
                        <div class="orv-menu_form-input" style="width:220px;">
                            <label>Nébuleuse</label>
                            <input type="text" name="nebuleuse" value="<?php echo htmlspecialchars($fd['nebuleuse'] ?? ''); ?>" style="width:220px;">
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Psychologie -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Psychologie :</h2>
                    <cite>Comment est votre constellation ?</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:300px;">
                            <label>Psyché</label>
                            <textarea name="psyche" rows="4" style="width:300px;"><?php echo htmlspecialchars($fd['psyche'] ?? ''); ?></textarea>
                        </div>
                        <div class="orv-menu_form-input" style="width:260px;">
                            <label>Vertu</label>
                            <textarea name="vertu" rows="4" style="width:260px;"><?php echo htmlspecialchars($fd['vertu'] ?? ''); ?></textarea>
                        </div>
                        <div class="orv-menu_form-input" style="width:260px;">
                            <label>Vice</label>
                            <textarea name="vice" rows="4" style="width:260px;"><?php echo htmlspecialchars($fd['vice'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Statistiques JDR -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Statistiques JDR :</h2>
                    <cite>Valeurs de base de la constellation.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:140px;">
                            <label>Force vitale</label>
                            <input type="number" name="vitalite" value="<?php echo (int)($fd['vitalite'] ?? 100); ?>" min="0" style="width:140px;">
                        </div>
                        <div class="orv-menu_form-input" style="width:140px;">
                            <label>Force physique</label>
                            <input type="number" name="force" value="<?php echo (int)($fd['force'] ?? 0); ?>" min="0" style="width:140px;">
                        </div>
                        <div class="orv-menu_form-input" style="width:140px;">
                            <label>Agilité</label>
                            <input type="number" name="agilite" value="<?php echo (int)($fd['agilite'] ?? 0); ?>" min="0" style="width:140px;">
                        </div>
                        <div class="orv-menu_form-input" style="width:160px;">
                            <label>Puissance magique</label>
                            <input type="number" name="mana" value="<?php echo (int)($fd['mana'] ?? 0); ?>" min="0" style="width:160px;">
                        </div>
                        <div class="orv-menu_form-input" style="width:160px;">
                            <label>Argent <small style="opacity:0.5;">(coins)</small></label>
                            <input type="number" name="coins" value="<?php echo (int)($fd['coins'] ?? 0); ?>" min="0" style="width:160px;">
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Récit -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Récit :</h2>
                    <cite>Histoire et estimation générale.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:100%; max-width:700px;">
                            <label>Histoire</label>
                            <textarea name="histoire" rows="6" style="width:100%;"><?php echo htmlspecialchars($fd['histoire'] ?? ''); ?></textarea>
                        </div>
                        <div class="orv-menu_form-input" style="width:100%; max-width:700px;">
                            <label>Estimation générale</label>
                            <textarea name="estimation_generale" rows="4" style="width:100%;"><?php echo htmlspecialchars($fd['estimation_generale'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
