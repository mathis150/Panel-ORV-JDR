<?php
    require_once './import/users.php';
    $um   = new UsersManager();
    $uuid = $_GET['uuid'] ?? '';
    $user = $um->getUserByUUID($uuid);

    if (!$user) {
        echo '<div class="user-notice user-notice--error"><i class="fa-solid fa-triangle-exclamation"></i> Utilisateur introuvable.</div>';
        return;
    }

    // Pré-remplir avec les données du POST (erreur de validation) ou les données BDD
    $v = [
        'pseudo'       => htmlspecialchars($formData['pseudo']       ?? $user['pseudonyme']),
        'display_name' => htmlspecialchars($formData['display_name'] ?? $user['display_name']),
        'email'        => htmlspecialchars($formData['email']        ?? $user['email']),
        'role'         => $formData['role']      ?? $user['role'],
        'is_active'    => isset($formData['is_active']) ? (bool)$formData['is_active'] : (bool)$user['is_active'],
        'mj_notes'     => htmlspecialchars($formData['mj_notes']     ?? ($user['mj_notes'] ?? '')),
    ];

    $dName = $user['display_name'] ?: $user['pseudonyme'];
?>

<div class="orv-buttons" style="justify-content: flex-end;">
    <button class="orv-edit" form="form-user-modify"><i class="fa-solid fa-check"></i> <span style="font-size: 18px;">Sauvegarder</span></button>
    <button class="orv-edit" onclick="window.location.href='jdr-params?page=users&sub-page=list'"><i class="fa-solid fa-xmark"></i> <span style="font-size: 18px;">Retour</span></button>
</div>

<?php if (!empty($notice)): ?>
    <div class="user-notice"><i class="fa-solid fa-circle-check"></i> <?php echo $notice; ?></div>
<?php endif; ?>
<?php if (isset($formError) && $formError): ?>
    <div class="user-notice user-notice--error"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($formError); ?></div>
<?php endif; ?>

<form id="form-user-modify" method="POST"
      action="jdr-params?page=users&sub-page=modify&uuid=<?php echo rawurlencode($uuid); ?>">

<div class="orv-menu">
    <div class="orv-menu_header">&lt;Modification — <?php echo htmlspecialchars($dName); ?>&gt;</div>

    <div class="session-tabs" style="padding: 0 24px;">
        <button type="button" class="session-tab button-actif" data-target="um-info">
            <i class="fa-solid fa-user-pen"></i> Informations
        </button>
        <button type="button" class="session-tab" data-target="um-notes">
            <i class="fa-solid fa-scroll"></i> Notes MJ
        </button>
        <button type="button" class="session-tab" data-target="um-security">
            <i class="fa-solid fa-lock"></i> Sécurité
        </button>
    </div>

    <div class="orv-menu_container">

        <!-- ===== Onglet : Informations ===== -->
        <div id="um-info" class="session-tab-panel">

            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Identité :</h2>
                    <cite>Le pseudonyme sert à se connecter. Le nom affiché est optionnel.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:260px;">
                            <label>Pseudonyme <span style="color:#FF6B7A;">*</span></label>
                            <input type="text" name="pseudo" value="<?php echo $v['pseudo']; ?>"
                                   maxlength="64" required style="width:260px;">
                        </div>
                        <div class="orv-menu_form-input" style="width:260px;">
                            <label>Nom affiché</label>
                            <input type="text" name="display_name" value="<?php echo $v['display_name']; ?>"
                                   maxlength="128" style="width:260px;">
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Compte :</h2>
                    <cite>Adresse e-mail, rôle et statut du compte.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:300px;">
                            <label>Adresse e-mail <span style="color:#FF6B7A;">*</span></label>
                            <input type="email" name="email" value="<?php echo $v['email']; ?>"
                                   maxlength="256" required style="width:300px;">
                        </div>
                        <div class="orv-menu_form-input" style="width:200px;">
                            <label>Rôle</label>
                            <select name="role" style="width:200px;">
                                <option value="player" <?php echo $v['role']==='player' ? 'selected' : ''; ?>>Joueur</option>
                                <option value="admin"  <?php echo $v['role']==='admin'  ? 'selected' : ''; ?>>Administrateur / MJ</option>
                                <option value="sudo"   <?php echo $v['role']==='sudo'   ? 'selected' : ''; ?>>Super-Administrateur</option>
                            </select>
                        </div>
                        <div class="orv-menu_form-input">
                            <label>Compte actif</label>
                            <label class="important-white checkbox" style="margin-top:10px;">
                                Activé
                                <input type="checkbox" name="is_active" value="1"
                                       <?php echo $v['is_active'] ? 'checked' : ''; ?>>
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ===== Onglet : Notes MJ ===== -->
        <div id="um-notes" class="session-tab-panel hidden">
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Notes privées :</h2>
                    <cite>Jamais visibles par le joueur. Habitudes, absences, infos importantes…</cite>
                </div>
                <div class="orv-menu_form-elements" style="grid-template-columns: 1fr;">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width:100%;">
                            <textarea name="mj_notes" class="session-notes" style="min-height:220px;"
                                      placeholder="Notes du MJ…"><?php echo $v['mj_notes']; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== Onglet : Sécurité ===== -->
        <div id="um-security" class="session-tab-panel hidden">
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Réinitialisation du mot de passe :</h2>
                    <cite>Génère un nouveau mot de passe temporaire et l'envoie par e-mail.
                          Toutes les sessions actives de cet utilisateur seront invalidées.</cite>
                </div>
                <div class="orv-menu_form-valid" style="margin-top:16px;">
                    <button type="submit" form="form-reset-pwd" class="orv-edit" style="background:rgba(255,107,122,0.12); border-color:rgba(255,107,122,0.35); color:#FF6B7A;"
                            onclick="return confirm('Réinitialiser le mot de passe de <?php echo htmlspecialchars($dName, ENT_QUOTES); ?> ?');">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span style="font-size:17px;">Réinitialiser le mot de passe</span>
                    </button>
                </div>
            </div>

            <?php if ($user['must_change_password']): ?>
            <div style="margin-top:12px; padding:10px 16px; background:rgba(251,191,36,0.06); border:1px solid rgba(251,191,36,0.25); border-radius:var(--r-md); color:#FBBF24; font-size:13px;">
                <i class="fa-solid fa-key" style="margin-right:6px;"></i>
                Cet utilisateur utilise encore un mot de passe temporaire et ne l'a pas encore changé.
            </div>
            <?php endif; ?>
        </div>

    </div>
</div>
</form>

<form id="form-reset-pwd" method="POST"
      action="jdr-params?page=users&sub-page=modify&uuid=<?php echo rawurlencode($uuid); ?>">
    <input type="hidden" name="action" value="reset_password">
</form>

<script>
    document.querySelectorAll('.session-tab').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.session-tab').forEach(function(b) { b.classList.remove('button-actif'); });
            document.querySelectorAll('.session-tab-panel').forEach(function(p) { p.classList.add('hidden'); });
            btn.classList.add('button-actif');
            document.getElementById(btn.dataset.target).classList.remove('hidden');
        });
    });
</script>
