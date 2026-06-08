<?php
    $loginError = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once './import/users.php';
        $um     = new UsersManager();
        $result = $um->login(
            trim($_POST['identification'] ?? ''),
            $_POST['password']            ?? '',
            isset($_POST['remember'])
        );

        if ($result['success']) {
            header('Location: ./dashboard');
            exit;
        }
        $loginError = $result['message'];
    }
?>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Connexion - ORV JDR</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Koh+Santepheap:wght@100;300;400;700;900&display=swap" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900italic,900' rel='stylesheet' type='text/css'>
        <link href="./css/style.css" rel="stylesheet">
    </head>
    <body>
        <nav style="grid-template-columns: 1fr auto 1fr;">
            <section class="navigation_info"></section>
            <section class="navigation_container">
                <a class="nav-button" href="./"><img class="nav-logo" src="./img/generic/LogoOrv.png" width="56px"></a>
                <a class="nav-button nav-title" href="./">Panneau de gestion du JDR ORV</a>
            </section>
            <section class="navigation_info"></section>
        </nav>
        <section class="container container-center">
            <div class="cards cards-login">
                <div class="cards-header"><h2>Veuillez vous connecter.</h2></div>
                <div class="cards-body container-center flex-dir-column-down">

                    <?php if ($loginError): ?>
                        <div class="login-error">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <?php echo htmlspecialchars($loginError); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="./" style="display:contents;">
                        <div class="login_field">
                            <label class="important-white login_label">Identifiant (Pseudo ou E-Mail) :</label>
                            <input type="text" name="identification" placeholder="Pseudonyme OU e-mail@example.xyz"
                                   value="<?php echo htmlspecialchars($_POST['identification'] ?? ''); ?>" autocomplete="username">
                        </div>
                        <div class="login_field">
                            <label class="important-white login_label">Mot de passe :</label>
                            <input type="password" name="password" placeholder="Votre mot de passe" autocomplete="current-password">
                        </div>
                        <div class="login_help">
                            <div>
                                <label class="important-white checkbox">Se souvenir de moi
                                    <input type="checkbox" name="remember">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="login_lost_password"><a href="./">Mot de passe oublié ?</a></div>
                        </div>
                        <input type="submit" name="submit" value="Connexion">
                    </form>

                </div>
            </div>
        </section>
    </body>
</html>
