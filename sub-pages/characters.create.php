<?php
    require_once './import/characters.php';
    $cm            = new CharactersManager();
    $users         = $cm->getAvailableUsers();
    $constellations = $cm->getAvailableConstellations();
    $fd            = $formData ?? [];
?>

<?php if ($formError ?? null): ?>
<div class="user-notice user-notice--error" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-xmark"></i> <?php echo htmlspecialchars($formError); ?>
</div>
<?php endif; ?>

<form method="POST" action="jdr-params?page=characters&sub-page=create" enctype="multipart/form-data">
    <div class="orv-buttons" style="justify-content:flex-end;">
        <button type="submit" class="orv-edit">
            <i class="fa-solid fa-check"></i>
            <span style="font-size:18px;">Créer le personnage</span>
        </button>
        <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=characters&sub-page=list';">
            <i class="fa-solid fa-xmark"></i>
            <span style="font-size:18px;">Annuler</span>
        </button>
    </div>

    <div class="orv-menu">
        <div class="orv-menu_header">&lt;Création d'un personnage&gt;</div>
        <div class="orv-menu_container">

            <!-- Identité -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Identité :</h2>
                    <cite>Informations générales du personnage.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:220px;">
                            <label>Nom <span style="color:#FF6B7A;">*</span></label>
                            <input type="text" name="nom" value="<?php echo htmlspecialchars($fd['nom'] ?? ''); ?>" required style="width:220px;">
                        </div>
                        <div class="orv-menu_form-input" style="width:220px;">
                            <label>Prénom</label>
                            <input type="text" name="prenom" value="<?php echo htmlspecialchars($fd['prenom'] ?? ''); ?>" style="width:220px;">
                        </div>
                        <div class="orv-menu_form-input" style="width:200px;">
                            <label>Race</label>
                            <input type="text" name="race" value="<?php echo htmlspecialchars($fd['race'] ?? ''); ?>" style="width:200px;">
                        </div>
                        <div class="orv-menu_form-input" style="width:200px;">
                            <label>Nationalité</label>
                            <input type="text" name="nationalite" value="<?php echo htmlspecialchars($fd['nationalite'] ?? ''); ?>" style="width:200px;">
                        </div>
                        <div class="orv-menu_form-input" style="width:200px;">
                            <label>Métier</label>
                            <input type="text" name="metier" value="<?php echo htmlspecialchars($fd['metier'] ?? ''); ?>" style="width:200px;">
                        </div>
                        <div class="orv-menu_form-input" style="width:240px;">
                            <label>Joueur associé</label>
                            <select name="user_uuid" style="width:240px;">
                                <option value="">— Aucun —</option>
                                <?php foreach ($users as $u): ?>
                                <option value="<?php echo htmlspecialchars($u['uuid']); ?>"
                                    <?php echo ($fd['user_uuid'] ?? '') === $u['uuid'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($u['display_name'] ?: $u['pseudonyme']); ?> (@<?php echo htmlspecialchars($u['pseudonyme']); ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="orv-menu_form-input" style="width:240px;">
                            <label>Constellation Sponsor</label>
                            <?php if (!empty($constellations)): ?>
                            <select name="constellation_sponsor_uuid" style="width:240px;">
                                <option value="">— Aucune —</option>
                                <?php foreach ($constellations as $con): ?>
                                <option value="<?php echo htmlspecialchars($con['uuid']); ?>"
                                    <?php echo ($fd['constellation_sponsor_uuid'] ?? '') === $con['uuid'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($con['nom']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <?php else: ?>
                            <input type="text" name="constellation_sponsor_uuid"
                                   value="<?php echo htmlspecialchars($fd['constellation_sponsor_uuid'] ?? ''); ?>"
                                   placeholder="UUID de la constellation" style="width:240px;">
                            <small style="color:rgba(137,206,255,0.4); font-size:11px;">Table constellations non disponible.</small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Personnalité -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Personnalité :</h2>
                    <cite>Comment est votre personnage ?</cite>
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

            <!-- Apparence -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Apparence :</h2>
                    <cite>Description physique et image du personnage.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:420px;">
                            <label>Description</label>
                            <textarea name="apparence_description" rows="5" style="width:420px;"><?php echo htmlspecialchars($fd['apparence_description'] ?? ''); ?></textarea>
                        </div>
                        <div class="orv-menu_form-input" style="width:260px;">
                            <label>Image du personnage</label>
                            <input type="file" name="apparence_image" accept="image/*" style="width:260px;">
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Récit -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Récit :</h2>
                    <cite>Histoire et estimation générale du personnage.</cite>
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

            <hr>

            <!-- Stats JDR -->
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Statistiques JDR :</h2>
                    <cite>Valeurs de base du personnage.</cite>
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
                        <div class="orv-menu_form-input" style="width:160px;">
                            <label>Coins <small style="opacity:0.5;">(banque)</small></label>
                            <input type="number" name="coins" value="<?php echo (int)($fd['coins'] ?? 0); ?>" min="0" style="width:160px;">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
