<?php

    class UsersManager extends Database {
        private Database $database;

        public String $userId = "";
        public Array $userData = array();

        public function __construct() {
            $this->database = new Database();
        }

        public function registerUser(String $pseudo, String $email, String $password, String $passwordConfirm){
            if(!empty($pseudo) && !empty($email) && !empty($password) && !empty($passwordConfirm)) {
                if($this->checkIfUserExist($pseudo)) {
                    if($this->checkIfUserExist(email: $email)) {
                        if($password === $passwordConfirm) {
                            $passwordHash = $this->hashPasswordTo($password);

                            $this->database->makeSQLRequest("INSERT INTO users (pseudonyme, email, password) VALUES (?,?,?)", array($this->filtrerTexteSQL($pseudo), $this->filtrerTexteSQL($email), $passwordHash));

                            return "Utilisateur créé !";
                        } else {
                            return "Mots de passes invalide.";
                        }
                    } else {
                        return "E-Mail déjà utilisé.";
                    }
                } else {
                    return "Pseudonyme déjà utilisé.";
                }
            }
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

        public function loginUser($identification, $password, $remember = true) {
            $returned = $this->database->makeSQLRequest("SELECT * FROM users WHERE pseudonyme = ? OR email= ?", array($this->filtrerTexteSQL($identification), $this->filtrerTexteSQL($identification)), ORV_SQL_REQUEST);

            if($returned->rowCount() == 1) {
                $returned = $returned->fetch(PDO::FETCH_ASSOC);
                if($this->isPasswordValid($password, $returned['password'])) {
                    
                }
            }
        }

        public function changeUserPassword($uuid, $newPassword) {
            $passwordHash = $this->hashPasswordTo($newPassword);

            $this->database->makeSQLRequest("UPDATE users SET password=? WHERE uuid = ?", array($passwordHash, $uuid));
        }

        public function generateSession($uuid, $remember) {
            $token = $this->generateCustomUUID();
            $date = time() + (isset($remember) && $remember) ? 60*60*24*31 : (60*60*24);

            $this->database->makeSQLRequest("INSERT INTO sessions(token, user_uuid, date_expiration)", array($token, $uuid, $date));

            return $token;
        }

        public function verifySession($token) {
            $count = $this->database->makeSQLRequest("SELECT * FROM sessions WHERE token = ? AND date_expiration > ?", array($token, time()), ORV_SQL_COUNT);

            if($count == 1) {
                return true;
            } else {
                return false;
            }
        }

        public function getUserDataBySession($token) {}

        public function editUserByUUID($uuid, $pseudo = "", $email = "", $password = "") {}

        public function updateUserActivitiesByUUID($uuid) {}

        public function getUserByUUID($uuid) {}

        public function getUsers($email) {}
    }

?>