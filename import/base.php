<?php
    class WebPageBases {

        private $pagesNames = array (
            "dashboard" => "Dashboard",
            "files"     => "Vos fiches",
            "sessions"  => "Gestion des sessions",
            "fights"    => "Combats",
            "jdr-params"=> "Paramètres",
            "profile"   => "Mon profil"
        );
        private $pagesList = array(
            0 => array("name" => "Dashboard",           "active" => "dashboard",  "permissions" => ""),
            1 => array("name" => "Vos fiches",          "active" => "files",      "permissions" => ""),
            2 => array("name" => "Gestion des sessions","active" => "sessions",   "permissions" => "admin"),
            3 => array("name" => "Combats",             "active" => "fights",     "permissions" => "admin"),
            4 => array("name" => "Parametres",          "active" => "jdr-params", "permissions" => "admin"),
        );

        private ?array $currentUser = null;

        public function __construct() {
            $this->requireAuth();
        }

        public function __destruct() { }

        // ── Authentification ──────────────────────────────────────────────────

        private function requireAuth(): void {
            $token = $_COOKIE['USER_SESSION'] ?? '';

            if (empty($token)) {
                $this->redirectLogin();
            }

            require_once __DIR__ . '/users.php';
            $um   = new UsersManager();
            $user = $um->getUserBySession($token);

            if (!$user) {
                setcookie('USER_SESSION', '', ['expires' => time() - 3600, 'path' => '/']);
                $this->redirectLogin();
            }

            // Vérifier les permissions pour les pages réservées aux admins
            $page = $this->getPage();
            foreach ($this->pagesList as $p) {
                if ($p['active'] === $page && $p['permissions'] === 'admin') {
                    if (!in_array($user['role'], ['admin', 'sudo'], true)) {
                        header('Location: ./dashboard');
                        exit;
                    }
                }
            }

            $this->currentUser = $user;
        }

        public function getCurrentUser(): ?array {
            return $this->currentUser;
        }

        private function redirectLogin(): void {
            header('Location: ./');
            exit;
        }

        // ── Rendu HTML ────────────────────────────────────────────────────────

        public function header() {
            $page = $this->pagesNames[$this->getPage()] ?? 'ORV JDR';
            ?>
            <html lang="fr">
                <head>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title><?php echo $page; ?> - ORV JDR</title>
                    <link rel="preconnect" href="https://fonts.googleapis.com">
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                    <link href="https://fonts.googleapis.com/css2?family=Koh+Santepheap:wght@100;300;400;700;900&display=swap" rel="stylesheet">
                    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900italic,900' rel='stylesheet' type='text/css'>
                    <link href="./css/style.css" rel="stylesheet">
                    <link rel="stylesheet" href="./css/multi-selecter.css">

                    <script src="https://kit.fontawesome.com/7f499188c8.js" crossorigin="anonymous"></script>
                    <script src="./js/multi-selecter.js"></script>
                    <script src="./js/app.js" defer></script>
                </head>
            <?php
        }

        public function navigation() {
            $page    = $this->getPage();
            $dName   = htmlspecialchars($this->currentUser['display_name'] ?: $this->currentUser['pseudonyme']);
            $role    = $this->currentUser['role'];
            $roleLabel = match($role) {
                'sudo'  => 'Super-Administrateur',
                'admin' => 'Maître du Jeu',
                default => 'Joueur',
            };
            ?>
            <nav>
                <section class="navigation_info">
                    <button class="nav-hamburger" id="nav-hamburger" aria-label="Menu">
                        <span></span><span></span><span></span>
                    </button>
                    <a class="nav-button" href="./"><img class="nav-logo" src="./img/generic/LogoOrv.png" width="56px"></a>
                    <a class="nav-button nav-title" href="./">Panneau de gestion<br>du JDR ORV</a>
                </section>
                <section class="navigation_container">
                    <?php foreach ($this->pagesList as $values):
                        // Masquer les pages admin aux joueurs
                        if ($values['permissions'] === 'admin' && !in_array($role, ['admin', 'sudo'], true)) continue;
                    ?>
                        <a class="nav-button <?php echo $values['active'] === $page ? 'nav-active' : ''; ?>"
                           href="./<?php echo $values['active']; ?>">
                            <?php echo $values['name']; ?>
                        </a>
                    <?php endforeach; ?>
                </section>
                <section class="navigation_info">
                    <a href="./profile" class="nav-profil">
                        <img src="./img/generic/LogoOrv.png" width="50px">
                        <div style="display:flex; flex-direction:column; align-items:flex-start; gap:1px;">
                            <div class="nav-profil_text"><?php echo $dName; ?></div>
                            <span style="font-size:10px; color:#89CEFF; -webkit-text-fill-color:#89CEFF; letter-spacing:0.4px; line-height:1;"><?php echo $roleLabel; ?></span>
                        </div>
                    </a>
                </section>
            </nav>
            <div class="sidebar-overlay" id="sidebar-overlay"></div>
            <?php
        }

        public function footer() {
            ?>
            <section class="footer_background">
                <footer>Copyright © <?php echo date("Y"); ?> - Mathis Lenoir, Version 1.0.0</footer>
            </section>
            <?php
        }

        private function getPage() {
            $page = explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]);
            return $page[count($page) - 1];
        }
    }
?>
