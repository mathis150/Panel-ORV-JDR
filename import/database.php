<?php

    DEFINE("ORV_SQL_NOTHING", 0);
    DEFINE("ORV_SQL_FETCH_ONE", 1);
    DEFINE("ORV_SQL_FETCH_ALL", 2);
    DEFINE("ORV_SQL_COUNT", 3);
    DEFINE("ORV_SQL_REQUEST", 4);

    class Database extends Environnement {

        private PDO $connect;

        public function __construct() {
            try {
                $this->connect = new PDO('mysql:host='.$this->ENV_DB_HOST.';dbname='.$this->ENV_DB_DATABASE, $this->ENV_DB_USER, $this->ENV_DB_PASSWORD);
                $this->connect->exec('SET NAMES utf8');

                $this->initializedDatabase();
            } catch (PDOException $e) {
                echo "Une erreur est survenue... " . $e->getMessage() . "<br>" ;
                die();
            }
        }

        public function __destruct() {
            unset($this->connect);
        }

        public function makeSQLRequest(String $SQLRequest, Array $values = array(), int $type = 0) {
            $request = $this->connect->prepare($SQLRequest);
            $request->execute($values);

            if($type = ORV_SQL_NOTHING) {
                return ($request->rowCount() > 0) ? true : false;
            } elseif($type = ORV_SQL_FETCH_ONE) {
                return $request->fetch(PDO::FETCH_ASSOC);
            } elseif($type = ORV_SQL_FETCH_ALL) {
                $table = array();

                while($r = $request->fetch(PDO::FETCH_ASSOC)) {
                    $table[(isset($r['uuid'])) ? $r['uuid'] : $r['id']] = $r;
                }

                return $table;
            } elseif($type = ORV_SQL_COUNT) {
                return $request->rowCount();
            } elseif($type = ORV_SQL_REQUEST) {
                return $request;
            }
        }

        private function initializedDatabase() {
            $this->makeSQLRequest("
            --
            -- Structure de la table `users`
            --

            CREATE TABLE IF NOT EXISTS `users` (
            `uuid` varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `pseudonyme` varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `email` varchar(256) COLLATE utf8mb3_bin NOT NULL,
            `password` text COLLATE utf8mb3_bin NOT NULL,
            `admin` tinyint(1) NOT NULL DEFAULT '0',
            `last_activity` int DEFAULT NULL,
            `registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`uuid`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
            COMMIT;
            ");
        }
    }

?>