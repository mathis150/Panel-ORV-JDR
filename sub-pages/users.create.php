<?php
    $v = [
        'pseudo'       => htmlspecialchars($formData['pseudo']       ?? ''),
        'display_name' => htmlspecialchars($formData['display_name'] ?? ''),
        'email'        => htmlspecialchars($formData['email']        ?? ''),
        'role'         => $formData['role'] ?? 'player',
        'mj_notes'     => htmlspecialchars($formData['mj_notes']     ?? ''),
    ];
?>

<div class="orv-buttons" style="justify-content: flex-end;">
    <button class="orv-edit" form="form-user-create"><i class="fa-solid fa-check"></i> <span style="font-size: 18px;">Créer & envoyer l'e-mail</span></button>
    <button class="orv-edit" onclick="window.location.href='jdr-params?page=users&sub-page=list'"><i class="fa-solid fa-xmark"></i> <span style="font-size: 18px;">Annuler</span></button>
</div>

<?php if (isset($formError) && $formError): ?>
    <div class="user-notice user-notice--error"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($formError); ?></div>
<?php endif; ?>

<form id="form-user-create" method="POST" action="jdr-params?page=users&sub-page=create">
<div class="orv-menu">
    <div class="orv-menu_header">&lt;Création d'un utilisateur&gt;</div>
    <div class="orv-menu_container">

        <div class="orv-menu_form">
            <div>
                <h2 class="classic-title">Identité :</h2>
                <cite>Le pseudonyme sert à se connecter. Le nom affiché est optionnel (pseudonyme par défaut).</cite>
            </div>
            <div class="orv-menu_form-elements">
                <div class="orv-menu_form-inputs-list">
                    <div class="orv-menu_form-input" style="width:260px;">
                        <label>Pseudonyme <span style="color:#FF6B7A;">*</span></label>
                        <input type="text" name="pseudo" value="<?php echo $v['pseudo']; ?>"
                               placeholder="ex : KimDokja" maxlength="64" required style="width:260px;">
                    </div>
                    <div class="orv-menu_form-input" style="width:260px;">
                        <label>Nom affiché</label>
                        <input type="text" name="display_name" value="<?php echo $v['display_name']; ?>"
                               placeholder="ex : Kim Dokja" maxlength="128" style="width:260px;">
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="orv-menu_form">
            <div>
                <h2 class="classic-title">Connexion :</h2>
                <cite>Le mot de passe temporaire sera généré automatiquement et envoyé à l'adresse ci-dessous.</cite>
            </div>
            <div class="orv-menu_form-elements">
                <div class="orv-menu_form-inputs-list">
                    <div class="orv-menu_form-input" style="width:320px;">
                        <label>Adresse e-mail <span style="color:#FF6B7A;">*</span></label>
                        <input type="email" name="email" value="<?php echo $v['email']; ?>"
                               placeholder="joueur@example.fr" maxlength="256" required style="width:320px;">
                    </div>
                    <div class="orv-menu_form-input" style="width:200px;">
                        <label>Rôle</label>
                        <select name="role" style="width:200px;">
                            <option value="player" <?php echo $v['role']==='player' ? 'selected' : ''; ?>>Joueur</option>
                            <option value="admin"  <?php echo $v['role']==='admin'  ? 'selected' : ''; ?>>Administrateur / MJ</option>
                            <option value="sudo"   <?php echo $v['role']==='sudo'   ? 'selected' : ''; ?>>Super-Administrateur</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="orv-menu_form">
            <div>
                <h2 class="classic-title">Notes MJ :</h2>
                <cite>Notes privées sur ce joueur — jamais visibles par le joueur lui-même.</cite>
            </div>
            <div class="orv-menu_form-elements" style="grid-template-columns: 1fr;">
                <div class="orv-menu_form-inputs-list">
                    <div class="orv-menu_form-input" style="width:100%;">
                        <label>Notes :</label>
                        <textarea name="mj_notes" class="session-notes" style="min-height:120px;"
                                  placeholder="Habitudes de jeu, infos importantes, absences prévues…"><?php echo $v['mj_notes']; ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-top:8px; padding: 10px 16px; background:rgba(55,174,254,0.06); border:1px solid rgba(55,174,254,0.18); border-radius:var(--r-md); color:#6B8AB4; font-size:13px;">
            <i class="fa-solid fa-envelope" style="color:var(--c-accent);margin-right:6px;"></i>
            Un e-mail contenant le mot de passe temporaire sera envoyé automatiquement lors de la création du compte.
            L'utilisateur devra le changer à sa première connexion.
        </div>

    </div>
</div>
</form>
