<?php

    class UsersManager extends Database {
        private Database $database;

        public String $userId = "";
        public Array $userData = array();

        public function __construct() {
            $this->database = new Database();
        }
        public function getUserDataBySession($token) {}

        public function editUserByUUID($uuid, $pseudo = "", $email = "", $password = "") {}

        public function updateUserActivitiesByUUID($uuid) {}

        public function getUserByUUID($uuid) {}

        public function getUsers($email) {}
    }

?>