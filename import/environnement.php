<?php

    class Environnement {

        protected string $ENV_DB_HOST       = 'localhost';
        protected string $ENV_DB_DATABASE   = '';
        protected string $ENV_DB_USER       = 'root';
        protected string $ENV_DB_PASSWORD   = '';
        protected int    $ENV_DB_PORT       = 3306;
        protected        $ENV_ENCRYPT       = PASSWORD_BCRYPT;
        protected string $ENV_APP_ENV       = 'production';
        protected string $ENV_MAIL_FROM      = 'noreply@localhost';
        protected string $ENV_MAIL_FROM_NAME = 'ORV JDR';
        protected bool   $ENV_MAIL_LOG       = false;

        public function __construct() {
            $this->loadEnv();
        }

        private function loadEnv(): void {
            $path = __DIR__ . '/../.env';

            if (!file_exists($path)) {
                throw new RuntimeException('.env introuvable. Copiez .env.example en .env et configurez vos valeurs.');
            }

            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($lines as $line) {
                $line = trim($line);

                if ($line === '' || str_starts_with($line, '#')) {
                    continue;
                }

                [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
                $key   = trim($key);
                $value = trim($value);

                // Supprimer les guillemets encadrants si présents
                if (preg_match('/^(["\'])(.+)\1$/', $value, $m)) {
                    $value = $m[2];
                }

                switch ($key) {
                    case 'DB_HOST':     $this->ENV_DB_HOST     = $value; break;
                    case 'DB_DATABASE': $this->ENV_DB_DATABASE = $value; break;
                    case 'DB_USER':     $this->ENV_DB_USER     = $value; break;
                    case 'DB_PASSWORD': $this->ENV_DB_PASSWORD = $value; break;
                    case 'DB_PORT':     $this->ENV_DB_PORT     = (int) $value; break;
                    case 'APP_ENV':     $this->ENV_APP_ENV     = $value; break;
                    case 'ENCRYPT':
                        $this->ENV_ENCRYPT = defined($value) ? (int) constant($value) : PASSWORD_BCRYPT;
                        break;
                    case 'MAIL_FROM':      $this->ENV_MAIL_FROM      = $value; break;
                    case 'MAIL_FROM_NAME': $this->ENV_MAIL_FROM_NAME = $value; break;
                    case 'MAIL_LOG':       $this->ENV_MAIL_LOG       = ($value === 'true' || $value === '1'); break;
                }
            }
        }

        public function isDevMode(): bool {
            return $this->ENV_APP_ENV === 'development';
        }
    }

?>
