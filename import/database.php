<?php

    require_once __DIR__ . '/environnement.php';

    DEFINE("ORV_SQL_NOTHING", 0);
    DEFINE("ORV_SQL_FETCH_ONE", 1);
    DEFINE("ORV_SQL_FETCH_ALL", 2);
    DEFINE("ORV_SQL_COUNT", 3);
    DEFINE("ORV_SQL_REQUEST", 4);

    class Database extends Environnement {

        private PDO $connect;
        public $hash;

        public function __construct() {
            parent::__construct();

            $this->hash = $this->ENV_ENCRYPT;

            try {
                $dsn = 'mysql:host=' . $this->ENV_DB_HOST
                     . ';port='   . $this->ENV_DB_PORT
                     . ';dbname=' . $this->ENV_DB_DATABASE
                     . ';charset=utf8mb4';

                $this->connect = new PDO($dsn, $this->ENV_DB_USER, $this->ENV_DB_PASSWORD, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);

                $this->runMigrations();
            } catch (PDOException $e) {
                $message = $this->isDevMode()
                    ? $e->getMessage()
                    : 'Impossible de se connecter à la base de données.';

                echo "Une erreur est survenue... " . $message . "<br>";
                die();
            }
        }

        public function __destruct() {
            unset($this->connect);
        }

        public function makeSQLRequest(String $SQLRequest, Array $values = array(), int $type = 0) {
            $request = $this->connect->prepare($SQLRequest);
            $request->execute($values);

            if ($type === ORV_SQL_NOTHING) {
                return ($request->rowCount() > 0) ? true : false;
            } elseif ($type === ORV_SQL_FETCH_ONE) {
                return $request->fetch(PDO::FETCH_ASSOC);
            } elseif ($type === ORV_SQL_FETCH_ALL) {
                $table = array();
                while ($r = $request->fetch(PDO::FETCH_ASSOC)) {
                    $table[(isset($r['uuid'])) ? $r['uuid'] : $r['id']] = $r;
                }
                return $table;
            } elseif ($type === ORV_SQL_COUNT) {
                return $request->rowCount();
            } elseif ($type === ORV_SQL_REQUEST) {
                return $request;
            }
        }

        // ─── Système de migrations ────────────────────────────────────────────────

        private function runMigrations(): void {
            // Table de suivi des migrations
            $this->connect->exec("
                CREATE TABLE IF NOT EXISTS `schema_migrations` (
                    `version`     varchar(10)  COLLATE utf8mb3_bin NOT NULL,
                    `description` varchar(255) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
                    `executed_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`version`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
            ");

            // Versions déjà appliquées
            $applied = $this->connect->query("SELECT version FROM schema_migrations")
                                     ->fetchAll(PDO::FETCH_COLUMN);

            // Charger et trier les fichiers de migration
            $files = glob(__DIR__ . '/../database/migrations/*.php');
            sort($files);

            foreach ($files as $file) {
                $migration = require $file;

                if (!in_array($migration['version'], $applied, true)) {
                    $statements = is_array($migration['up']) ? $migration['up'] : [$migration['up']];
                    foreach ($statements as $sql) {
                        $sql = trim($sql);
                        if ($sql === '') continue;
                        $stmt = $this->connect->query($sql);
                        if ($stmt) $stmt->closeCursor();
                    }

                    $this->connect->prepare(
                        "INSERT INTO schema_migrations (version, description) VALUES (?, ?)"
                    )->execute([$migration['version'], $migration['description']]);
                }
            }
        }

        // ─── Utilitaires ─────────────────────────────────────────────────────────

        public function generateCustomUUID() {
            $date = date('ymd');
            $time = date('Hi');

            $random1 = strtoupper(bin2hex(random_bytes(3)));
            $random2 = strtoupper(bin2hex(random_bytes(4)));
            $random3 = strtoupper(bin2hex(random_bytes(3)));
            $random4 = strtoupper(bin2hex(random_bytes(3)));

            return sprintf(
                "%s-%s%s-%s-%s%s",
                substr($random1, 0, 6),
                $date,
                substr($random2, 0, 4),
                substr($random3, 0, 6),
                substr($random4, 0, 6),
                $time
            );
        }

        public function filtrerTexteSQL(string $texte): string {
            $texte = trim($texte);

            $caracteres_dangereux = ["'", '"', ';', '\\', '--', '#', '/*', '*/', '`'];
            $texte = str_replace($caracteres_dangereux, '', $texte);

            $mots_interdits = [
                'select', 'insert', 'update', 'delete',
                'drop', 'truncate', 'exec', 'union',
                'create', 'alter', 'rename', 'replace'
            ];

            foreach ($mots_interdits as $mot) {
                $texte = preg_replace('/\b' . preg_quote($mot, '/') . '\b/i', '', $texte);
            }

            return $texte;
        }
    }

?>
