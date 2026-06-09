<?php

    require_once __DIR__ . '/database.php';

    class UsersManager extends Database {

        public function __construct() {
            parent::__construct();
        }

        // ── Création ──────────────────────────────────────────────────────────────

        public function createUser(string $pseudo, string $email, string $role = 'player', string $displayName = '', string $mjNotes = ''): array {
            if (empty($pseudo) || empty($email)) {
                return ['success' => false, 'message' => 'Pseudonyme et adresse e-mail requis.'];
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Adresse e-mail invalide.'];
            }
            if (!in_array($role, ['sudo', 'admin', 'player'], true)) {
                return ['success' => false, 'message' => 'Rôle invalide.'];
            }
            if ($this->pseudoExists($pseudo)) {
                return ['success' => false, 'message' => 'Ce pseudonyme est déjà utilisé.'];
            }
            if ($this->emailExists($email)) {
                return ['success' => false, 'message' => 'Cette adresse e-mail est déjà utilisée.'];
            }

            $uuid        = $this->generateCustomUUID();
            $dName       = ($displayName !== '') ? $displayName : $pseudo;
            $tempPass    = $this->generateTempPassword();
            $hash        = password_hash($tempPass, $this->ENV_ENCRYPT);

            $this->makeSQLRequest(
                "INSERT INTO users (uuid, pseudonyme, display_name, email, password, role, mj_notes)
                 VALUES (?, ?, ?, ?, ?, ?, ?)",
                [$uuid, $this->filtrerTexteSQL($pseudo), $this->filtrerTexteSQL($dName),
                 $email, $hash, $role, $mjNotes],
                ORV_SQL_NOTHING
            );

            $this->sendWelcomeEmail($email, $dName, $pseudo, $tempPass);

            return ['success' => true, 'message' => 'Compte créé. E-mail envoyé à ' . htmlspecialchars($email) . '.', 'temp_password' => $tempPass];
        }

        // ── Authentification ──────────────────────────────────────────────────────

        public function login(string $identification, string $password, bool $remember = false): array {
            if (empty($identification) || empty($password)) {
                return ['success' => false, 'message' => 'Identifiant et mot de passe requis.'];
            }

            $user = $this->makeSQLRequest(
                "SELECT * FROM users WHERE (pseudonyme = ? OR email = ?) AND is_active = 1",
                [$identification, $identification],
                ORV_SQL_FETCH_ONE
            );

            if (!$user || !password_verify($password, $user['password'])) {
                return ['success' => false, 'message' => 'Identifiant ou mot de passe incorrect.'];
            }

            $ttl       = $remember ? 60 * 60 * 24 * 30 : 60 * 60 * 24;
            $expiresAt = time() + $ttl;
            $token     = $this->createSession($user['uuid'], $expiresAt);

            setcookie('USER_SESSION', $token, [
                'expires'  => $expiresAt,
                'path'     => '/',
                'httponly' => true,
                'samesite' => 'Lax',
            ]);

            $this->makeSQLRequest(
                "UPDATE users SET last_activity = ? WHERE uuid = ?",
                [time(), $user['uuid']],
                ORV_SQL_NOTHING
            );

            return [
                'success'              => true,
                'must_change_password' => (bool) $user['must_change_password'],
                'uuid'                 => $user['uuid'],
                'role'                 => $user['role'],
            ];
        }

        public function logout(string $token): void {
            $this->makeSQLRequest(
                "DELETE FROM sessions WHERE token = ?",
                [$token],
                ORV_SQL_NOTHING
            );
            setcookie('USER_SESSION', '', [
                'expires'  => time() - 3600,
                'path'     => '/',
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        }

        // ── Vérification de session ────────────────────────────────────────────────

        public function verifySession(string $token): bool {
            $row = $this->makeSQLRequest(
                "SELECT COUNT(*) AS cnt FROM sessions WHERE token = ? AND expires_at > ?",
                [$token, time()],
                ORV_SQL_FETCH_ONE
            );
            return ($row['cnt'] ?? 0) > 0;
        }

        public function getUserBySession(string $token): ?array {
            $session = $this->makeSQLRequest(
                "SELECT user_uuid FROM sessions WHERE token = ? AND expires_at > ?",
                [$token, time()],
                ORV_SQL_FETCH_ONE
            );
            if (!$session) return null;

            $user = $this->makeSQLRequest(
                "SELECT uuid, pseudonyme, display_name, email, role, is_active,
                        must_change_password, last_activity, registered
                 FROM users WHERE uuid = ? AND is_active = 1",
                [$session['user_uuid']],
                ORV_SQL_FETCH_ONE
            );
            return $user ?: null;
        }

        // ── Lecture ────────────────────────────────────────────────────────────────

        public function getUsers(): array {
            return $this->makeSQLRequest(
                "SELECT uuid, pseudonyme, display_name, email, role, is_active,
                        must_change_password, last_activity, registered
                 FROM users ORDER BY registered DESC",
                [],
                ORV_SQL_FETCH_ALL
            ) ?: [];
        }

        public function getUserByUUID(string $uuid): ?array {
            $user = $this->makeSQLRequest(
                "SELECT uuid, pseudonyme, display_name, email, role, is_active,
                        must_change_password, mj_notes, last_activity, registered
                 FROM users WHERE uuid = ?",
                [$uuid],
                ORV_SQL_FETCH_ONE
            );
            return $user ?: null;
        }

        // ── Mise à jour ────────────────────────────────────────────────────────────

        public function updateUser(string $uuid, string $pseudo, string $displayName, string $email, string $role, string $mjNotes, bool $isActive): array {
            if (empty($pseudo) || empty($email)) {
                return ['success' => false, 'message' => 'Pseudonyme et adresse e-mail requis.'];
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Adresse e-mail invalide.'];
            }
            if (!in_array($role, ['sudo', 'admin', 'player'], true)) {
                return ['success' => false, 'message' => 'Rôle invalide.'];
            }

            $existPseudo = $this->makeSQLRequest(
                "SELECT COUNT(*) AS cnt FROM users WHERE pseudonyme = ? AND uuid != ?",
                [$pseudo, $uuid], ORV_SQL_FETCH_ONE
            );
            if (($existPseudo['cnt'] ?? 0) > 0) {
                return ['success' => false, 'message' => 'Ce pseudonyme est déjà utilisé.'];
            }

            $existEmail = $this->makeSQLRequest(
                "SELECT COUNT(*) AS cnt FROM users WHERE email = ? AND uuid != ?",
                [$email, $uuid], ORV_SQL_FETCH_ONE
            );
            if (($existEmail['cnt'] ?? 0) > 0) {
                return ['success' => false, 'message' => 'Cette adresse e-mail est déjà utilisée.'];
            }

            $dName = ($displayName !== '') ? $displayName : $pseudo;

            $this->makeSQLRequest(
                "UPDATE users SET pseudonyme = ?, display_name = ?, email = ?,
                                  role = ?, mj_notes = ?, is_active = ?
                 WHERE uuid = ?",
                [$this->filtrerTexteSQL($pseudo), $this->filtrerTexteSQL($dName),
                 $email, $role, $mjNotes, $isActive ? 1 : 0, $uuid],
                ORV_SQL_NOTHING
            );

            return ['success' => true, 'message' => 'Utilisateur mis à jour.'];
        }

        public function resetPassword(string $uuid): array {
            $user = $this->getUserByUUID($uuid);
            if (!$user) return ['success' => false, 'message' => 'Utilisateur introuvable.'];

            $tempPass = $this->generateTempPassword();
            $hash     = password_hash($tempPass, $this->ENV_ENCRYPT);

            $this->makeSQLRequest(
                "UPDATE users SET password = ?, must_change_password = 1 WHERE uuid = ?",
                [$hash, $uuid],
                ORV_SQL_NOTHING
            );

            // Invalider toutes les sessions actives de cet utilisateur
            $this->makeSQLRequest(
                "DELETE FROM sessions WHERE user_uuid = ?",
                [$uuid],
                ORV_SQL_NOTHING
            );

            $dName = $user['display_name'] ?: $user['pseudonyme'];
            $this->sendPasswordResetEmail($user['email'], $dName, $user['pseudonyme'], $tempPass);

            return ['success' => true, 'message' => 'Mot de passe réinitialisé. E-mail envoyé à ' . htmlspecialchars($user['email']) . '.'];
        }

        // ── Profil utilisateur ────────────────────────────────────────────────────

        public function updateProfile(string $uuid, string $displayName, string $email): array {
            if (empty($email)) {
                return ['success' => false, 'message' => 'Adresse e-mail requise.'];
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Adresse e-mail invalide.'];
            }

            $existEmail = $this->makeSQLRequest(
                "SELECT COUNT(*) AS cnt FROM users WHERE email = ? AND uuid != ?",
                [$email, $uuid], ORV_SQL_FETCH_ONE
            );
            if (($existEmail['cnt'] ?? 0) > 0) {
                return ['success' => false, 'message' => 'Cette adresse e-mail est déjà utilisée.'];
            }

            $user  = $this->getUserByUUID($uuid);
            $dName = ($displayName !== '') ? $this->filtrerTexteSQL($displayName) : $user['pseudonyme'];

            $this->makeSQLRequest(
                "UPDATE users SET display_name = ?, email = ? WHERE uuid = ?",
                [$dName, $email, $uuid],
                ORV_SQL_NOTHING
            );

            return ['success' => true, 'message' => 'Profil mis à jour.'];
        }

        public function changePassword(string $uuid, string $currentPassword, string $newPassword, string $confirmPassword): array {
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                return ['success' => false, 'message' => 'Tous les champs sont requis.'];
            }
            if ($newPassword !== $confirmPassword) {
                return ['success' => false, 'message' => 'Les mots de passe ne correspondent pas.'];
            }
            if (strlen($newPassword) < 8) {
                return ['success' => false, 'message' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.'];
            }

            $row = $this->makeSQLRequest(
                "SELECT password FROM users WHERE uuid = ?",
                [$uuid], ORV_SQL_FETCH_ONE
            );
            if (!$row || !password_verify($currentPassword, $row['password'])) {
                return ['success' => false, 'message' => 'Mot de passe actuel incorrect.'];
            }

            $hash = password_hash($newPassword, $this->ENV_ENCRYPT);
            $this->makeSQLRequest(
                "UPDATE users SET password = ?, must_change_password = 0 WHERE uuid = ?",
                [$hash, $uuid],
                ORV_SQL_NOTHING
            );

            return ['success' => true, 'message' => 'Mot de passe modifié avec succès.'];
        }

        // ── Suppression ────────────────────────────────────────────────────────────

        public function deleteUser(string $uuid): void {
            $this->makeSQLRequest("DELETE FROM sessions WHERE user_uuid = ?", [$uuid], ORV_SQL_NOTHING);
            $this->makeSQLRequest("DELETE FROM users WHERE uuid = ?",         [$uuid], ORV_SQL_NOTHING);
        }

        // ── Helpers privés ────────────────────────────────────────────────────────

        private function createSession(string $userUuid, int $expiresAt): string {
            // Purger les sessions expirées de cet utilisateur
            $this->makeSQLRequest(
                "DELETE FROM sessions WHERE user_uuid = ? AND expires_at < ?",
                [$userUuid, time()],
                ORV_SQL_NOTHING
            );

            $token = $this->generateCustomUUID();

            $this->makeSQLRequest(
                "INSERT INTO sessions (token, user_uuid, expires_at) VALUES (?, ?, ?)",
                [$token, $userUuid, $expiresAt],
                ORV_SQL_NOTHING
            );

            return $token;
        }

        private function pseudoExists(string $pseudo): bool {
            $r = $this->makeSQLRequest(
                "SELECT COUNT(*) AS cnt FROM users WHERE pseudonyme = ?",
                [$pseudo], ORV_SQL_FETCH_ONE
            );
            return ($r['cnt'] ?? 0) > 0;
        }

        private function emailExists(string $email): bool {
            $r = $this->makeSQLRequest(
                "SELECT COUNT(*) AS cnt FROM users WHERE email = ?",
                [$email], ORV_SQL_FETCH_ONE
            );
            return ($r['cnt'] ?? 0) > 0;
        }

        private function generateTempPassword(): string {
            $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';
            $pass  = '';
            for ($i = 0; $i < 12; $i++) {
                $pass .= $chars[random_int(0, strlen($chars) - 1)];
            }
            return $pass;
        }

        private function sendWelcomeEmail(string $to, string $displayName, string $pseudo, string $tempPass): void {
            $subject = '[ORV JDR] Votre compte a été créé';
            $body    = "Bonjour {$displayName},\n\n"
                     . "Votre compte sur le Panneau de Gestion ORV JDR a été créé par le Maître du Jeu.\n\n"
                     . "Identifiant : {$pseudo}\n"
                     . "Mot de passe temporaire : {$tempPass}\n\n"
                     . "Connectez-vous et changez votre mot de passe dès votre première connexion.\n\n"
                     . "— Le Maître du Jeu";

            $this->sendMail($to, $subject, $body);
        }

        private function sendPasswordResetEmail(string $to, string $displayName, string $pseudo, string $tempPass): void {
            $subject = '[ORV JDR] Réinitialisation de votre mot de passe';
            $body    = "Bonjour {$displayName},\n\n"
                     . "Votre mot de passe a été réinitialisé par le Maître du Jeu.\n\n"
                     . "Identifiant : {$pseudo}\n"
                     . "Nouveau mot de passe temporaire : {$tempPass}\n\n"
                     . "Connectez-vous et changez votre mot de passe dès que possible.\n\n"
                     . "— Le Maître du Jeu";

            $this->sendMail($to, $subject, $body);
        }

        private function sendMail(string $to, string $subject, string $body): void {
            if ($this->ENV_MAIL_LOG) {
                $this->logMail($to, $subject, $body);
                return;
            }

            $headers = implode("\r\n", [
                'From: ' . $this->ENV_MAIL_FROM_NAME . ' <' . $this->ENV_MAIL_FROM . '>',
                'Reply-To: ' . $this->ENV_MAIL_FROM,
                'Content-Type: text/plain; charset=UTF-8',
                'X-Mailer: PHP/' . PHP_VERSION,
            ]);
            mail($to, $subject, $body, $headers);
        }

        private function logMail(string $to, string $subject, string $body): void {
            $logDir  = __DIR__ . '/../logs';
            $logFile = $logDir . '/mail.log';

            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }

            $line  = str_repeat('=', 72);
            $entry = "\n{$line}\n"
                   . '[' . date('Y-m-d H:i:s') . ']'
                   . '  TO: '      . $to      . "\n"
                   . 'SUBJECT: '   . $subject . "\n"
                   . str_repeat('-', 72) . "\n"
                   . $body         . "\n"
                   . "{$line}\n";

            file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
        }
    }

?>
