<?php

    class UsersManager extends Database {
        private Database $database;

        public String $userId = "";
        public Array $userData = array();

        public function __construct() {
            $this->database = new Database();
        }
        public function checkIfUserExist($pseudo = "", $email = "") {
            if(isset($pseudo)) {
                $returned = $this->database->makeSQLRequest("SELECT * FROM users WHERE pseudonyme = ?", array($this->filtrerTexteSQL($pseudo)), ORV_SQL_COUNT);

                return ($returned == 1) ? true : false;
            } elseif(isset($pseudo)) {
                $returned = $this->database->makeSQLRequest("SELECT * FROM users WHERE email = ?", array($this->filtrerTexteSQL($email)), ORV_SQL_COUNT);

                return ($returned == 1) ? true : false;
            } else {
                return "Need arguments.";
            }
        }

        public function isPasswordValid($password, $password_hash) {
            return password_verify($password, $password_hash);
        }

        public function hashPasswordTo($password) {
            return password_hash($password, $this->hash);
        }

        public function changeUserPassword($uuid, $newPassword) {
            $passwordHash = $this->hashPasswordTo($newPassword);

            $this->database->makeSQLRequest("UPDATE users SET password=? WHERE uuid = ?", array($passwordHash, $uuid));
        }
        public function getUserDataBySession($token) {}

        public function editUserByUUID($uuid, $pseudo = "", $email = "", $password = "") {}

        public function updateUserActivitiesByUUID($uuid) {}

        public function getUserByUUID($uuid) {}

        public function getUsers($email) {}
    }

?>