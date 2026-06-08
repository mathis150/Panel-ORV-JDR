<?php
    require_once "./import/base.php";
    require_once "./import/users.php";

    $bases = new WebPageBases();
    $user  = $bases->getCurrentUser();

    $notice      = htmlspecialchars($_GET['notice'] ?? '');
    $activeTab   = in_array($_GET['tab'] ?? '', ['pp-compte', 'pp-notes', 'pp-sessions', 'pp-stats'])
                   ? $_GET['tab']
                   : 'pp-compte';
    $formError   = null;
    $formSection = null;
    $formData    = [];

    // ── Gestion des formulaires POST ─────────────────────────────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $um     = new UsersManager();
        $action = $_POST['action'] ?? '';

        if ($action === 'update_profile') {
            $result = $um->updateProfile(
                $user['uuid'],
                trim($_POST['display_name'] ?? ''),
                trim($_POST['email']        ?? '')
            );
            if ($result['success']) {
                header('Location: profile?tab=pp-compte&notice=' . rawurlencode($result['message']));
                exit;
            }
            $formError   = $result['message'];
            $formSection = 'identity';
            $formData    = $_POST;
            $activeTab   = 'pp-compte';
        }

        if ($action === 'change_password') {
            $result = $um->changePassword(
                $user['uuid'],
                $_POST['current_password']  ?? '',
                $_POST['new_password']      ?? '',
                $_POST['confirm_password']  ?? ''
            );
            if ($result['success']) {
                header('Location: profile?tab=pp-compte&notice=' . rawurlencode($result['message']));
                exit;
            }
            $formError   = $result['message'];
            $formSection = 'security';
            $activeTab   = 'pp-compte';
        }
    }

    // ── Données affichées ────────────────────────────────────────────────────
    $um_r     = new UsersManager();
    $fullUser = $um_r->getUserByUUID($user['uuid']);
    $mjNotes  = trim($fullUser['mj_notes'] ?? '');

    $dName       = htmlspecialchars($user['display_name'] ?: $user['pseudonyme']);
    $pseudo      = htmlspecialchars($user['pseudonyme']);
    $email       = htmlspecialchars($user['email']);
    $role        = $user['role'];
    $mustChange  = (bool)($user['must_change_password'] ?? false);
    $registeredTs = strtotime($user['registered'] ?? '');
    $membresDepuis = $registeredTs ? date('d/m/Y', $registeredTs) : '—';

    $roleLabel = match($role) {
        'sudo'  => 'Super-Administrateur',
        'admin' => 'Maître du Jeu',
        default => 'Joueur',
    };

    // Valeurs du formulaire (en cas d'erreur POST on re-remplit depuis $_POST)
    $fDisplayName = htmlspecialchars($formData['display_name'] ?? $user['display_name']);
    $fEmail       = htmlspecialchars($formData['email']        ?? $user['email']);
?>
    <?php $bases->header(); ?>
    <body>
        <?php $bases->navigation(); ?>
        <section class="container container-menus">

            <!-- Sidebar profil -->
            <section class="left-menu">
                <div class="profile-sidebar">
                    <div class="profile-avatar">
                        <img src="./img/generic/LogoOrv.png" alt="Avatar">
                    </div>
                    <div class="profile-name"><?php echo $dName; ?></div>
                    <div class="profile-role"><?php echo htmlspecialchars($roleLabel); ?></div>

                    <?php if ($mustChange): ?>
                    <div style="width:100%; margin-bottom:14px;">
                        <div class="user-notice user-notice--error" style="font-size:11px; padding:7px 10px; text-align:center;">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            Mot de passe temporaire — veuillez le changer.
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="profile-divider"></div>
                    <div class="profile-quick-stat">
                        <span class="profile-quick-stat__label">Membre depuis</span>
                        <span class="profile-quick-stat__value"><?php echo $membresDepuis; ?></span>
                    </div>
                    <div class="profile-quick-stat">
                        <span class="profile-quick-stat__label">Identifiant</span>
                        <span class="profile-quick-stat__value">@<?php echo $pseudo; ?></span>
                    </div>
                </div>
            </section>

            <!-- Contenu principal -->
            <section class="right-menu">

                <?php if ($notice): ?>
                <div class="user-notice" style="margin:0 0 12px;">
                    <i class="fa-solid fa-circle-check"></i> <?php echo $notice; ?>
                </div>
                <?php endif; ?>

                <!-- Onglets -->
                <div class="session-tabs">
                    <button class="session-tab <?php echo $activeTab === 'pp-compte'   ? 'button-actif' : ''; ?>" data-target="pp-compte">
                        <i class="fa-solid fa-user-pen"></i> Mon compte
                    </button>
                    <button class="session-tab <?php echo $activeTab === 'pp-notes'    ? 'button-actif' : ''; ?>" data-target="pp-notes">
                        <i class="fa-solid fa-scroll"></i> Notes MJ
                    </button>
                    <button class="session-tab <?php echo $activeTab === 'pp-sessions' ? 'button-actif' : ''; ?>" data-target="pp-sessions">
                        <i class="fa-solid fa-calendar-days"></i> Sessions à venir
                    </button>
                    <button class="session-tab <?php echo $activeTab === 'pp-stats'    ? 'button-actif' : ''; ?>" data-target="pp-stats">
                        <i class="fa-solid fa-chart-bar"></i> Statistiques
                    </button>
                </div>

                <div class="menu-right-container">

                    <!-- ===== Onglet : Mon compte ===== -->
                    <div id="pp-compte" class="session-tab-panel <?php echo $activeTab !== 'pp-compte' ? 'hidden' : ''; ?>">
                        <div class="orv-menu">
                            <div class="orv-menu_header">&lt;Informations du compte&gt;</div>
                            <div class="orv-menu_container">

                                <?php if ($formError && $formSection === 'identity'): ?>
                                <div class="user-notice user-notice--error" style="margin-bottom:16px;">
                                    <i class="fa-solid fa-circle-xmark"></i> <?php echo htmlspecialchars($formError); ?>
                                </div>
                                <?php endif; ?>

                                <form method="POST" action="profile">
                                    <input type="hidden" name="action" value="update_profile">
                                    <div class="orv-menu_form">
                                        <div>
                                            <h2 class="classic-title">Identité :</h2>
                                            <cite>Modifier votre nom d'affichage et votre adresse e-mail.</cite>
                                        </div>
                                        <div class="orv-menu_form-elements">
                                            <div class="orv-menu_form-inputs-list">
                                                <div class="orv-menu_form-input">
                                                    <label>Pseudonyme :</label>
                                                    <input type="text" value="<?php echo $pseudo; ?>" disabled style="opacity:0.5; cursor:not-allowed;">
                                                    <small style="color:rgba(137,206,255,0.45); font-size:11px;">Non modifiable — contacter le MJ.</small>
                                                </div>
                                                <div class="orv-menu_form-input" style="width:300px;">
                                                    <label>Nom d'affichage :</label>
                                                    <input type="text" name="display_name" value="<?php echo $fDisplayName; ?>" maxlength="128" style="width:300px;" placeholder="<?php echo $pseudo; ?>">
                                                </div>
                                                <div class="orv-menu_form-input" style="width:300px;">
                                                    <label>Adresse e-mail :</label>
                                                    <input type="email" name="email" value="<?php echo $fEmail; ?>" required style="width:300px;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="orv-menu_form-valid">
                                            <button type="submit" class="orv-edit">
                                                <i class="fa-solid fa-check"></i>
                                                <span style="font-size:17px;">Sauvegarder</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <hr>

                                <?php if ($formError && $formSection === 'security'): ?>
                                <div class="user-notice user-notice--error" style="margin-bottom:16px;">
                                    <i class="fa-solid fa-circle-xmark"></i> <?php echo htmlspecialchars($formError); ?>
                                </div>
                                <?php endif; ?>

                                <form method="POST" action="profile">
                                    <input type="hidden" name="action" value="change_password">
                                    <div class="orv-menu_form">
                                        <div>
                                            <h2 class="classic-title">Sécurité :</h2>
                                            <cite>Modifier votre mot de passe. Renseignez d'abord votre mot de passe actuel.</cite>
                                        </div>
                                        <div class="orv-menu_form-elements">
                                            <div class="orv-menu_form-inputs-list">
                                                <div class="orv-menu_form-input" style="width:280px;">
                                                    <label>Mot de passe actuel :</label>
                                                    <input type="password" name="current_password" placeholder="••••••••••" style="width:280px;" autocomplete="current-password">
                                                </div>
                                                <div class="orv-menu_form-input" style="width:280px;">
                                                    <label>Nouveau mot de passe :</label>
                                                    <input type="password" name="new_password" placeholder="••••••••••" style="width:280px;" autocomplete="new-password" minlength="8">
                                                </div>
                                                <div class="orv-menu_form-input" style="width:280px;">
                                                    <label>Confirmer le mot de passe :</label>
                                                    <input type="password" name="confirm_password" placeholder="••••••••••" style="width:280px;" autocomplete="new-password">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="orv-menu_form-valid">
                                            <button type="submit" class="orv-edit">
                                                <i class="fa-solid fa-lock"></i>
                                                <span style="font-size:17px;">Changer le mot de passe</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                    <!-- ===== Onglet : Notes MJ ===== -->
                    <div id="pp-notes" class="session-tab-panel <?php echo $activeTab !== 'pp-notes' ? 'hidden' : ''; ?>">
                        <div class="orv-menu" style="min-width: 700px;">
                            <div class="orv-menu_header">&lt;Notes du Maître du Jeu&gt;</div>
                            <div class="orv-menu_container">

                                <div class="orv-menu_form">
                                    <div>
                                        <h2 class="classic-title">À propos de vous :</h2>
                                        <cite>Notes rédigées par le MJ — lecture seule.</cite>
                                    </div>
                                </div>

                                <div style="margin-top:20px; min-height:400px;">
                                    <?php if ($mjNotes !== ''): ?>
                                    <div class="profile-mj-note">
                                        <div class="profile-mj-note-header">
                                            <i class="fa-solid fa-feather-pointed"></i> Notes du Maître du Jeu
                                        </div>
                                        <?php echo nl2br(htmlspecialchars($mjNotes)); ?>
                                    </div>
                                    <?php else: ?>
                                    <div style="color:rgba(137,206,255,0.4); font-size:13px; text-align:center; padding:40px 0;">
                                        <i class="fa-solid fa-feather-pointed" style="font-size:24px; margin-bottom:10px; display:block;"></i>
                                        Aucune note du MJ pour le moment.
                                    </div>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- ===== Onglet : Sessions à venir ===== -->
                    <div id="pp-sessions" class="session-tab-panel <?php echo $activeTab !== 'pp-sessions' ? 'hidden' : ''; ?>">
                        <div class="orv-menu" style="min-width: 700px;">
                            <div class="orv-menu_header">&lt;Calendrier des Sessions&gt;</div>
                            <div class="orv-menu_container">

                                <div class="orv-menu_form">
                                    <div>
                                        <h2 class="classic-title">Prochaines sessions :</h2>
                                        <cite>Calendrier établi par le MJ — lecture seule.</cite>
                                    </div>
                                </div>

                                <div style="color:rgba(137,206,255,0.4); font-size:13px; text-align:center; padding:40px 0; margin-top:20px; min-height:400px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                                    <i class="fa-solid fa-calendar-days" style="font-size:24px; margin-bottom:10px;"></i>
                                    Aucune session planifiée pour le moment.
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- ===== Onglet : Statistiques ===== -->
                    <div id="pp-stats" class="session-tab-panel <?php echo $activeTab !== 'pp-stats' ? 'hidden' : ''; ?>">
                        <div class="orv-menu" style="min-width: 700px;">
                            <div class="orv-menu_header">&lt;Statistiques Globales&gt;</div>
                            <div class="orv-menu_container">

                                <div style="color:rgba(137,206,255,0.4); font-size:13px; text-align:center; padding:40px 0; min-height:400px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                                    <i class="fa-solid fa-chart-bar" style="font-size:24px; margin-bottom:10px;"></i>
                                    Les statistiques seront disponibles une fois les personnages liés.
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <?php $bases->footer(); ?>
            </section>

        </section>
    </body>
</html>
