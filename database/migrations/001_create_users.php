<?php

return [
    'version'     => '001',
    'description' => 'Create users table',
    'up'          => "
        CREATE TABLE IF NOT EXISTS `users` (
            `uuid`                  varchar(64)                         COLLATE utf8mb3_bin NOT NULL,
            `pseudonyme`            varchar(64)                         COLLATE utf8mb3_bin NOT NULL,
            `display_name`          varchar(128)                        COLLATE utf8mb3_bin NOT NULL DEFAULT '',
            `email`                 varchar(256)                        COLLATE utf8mb3_bin NOT NULL,
            `password`              text                                COLLATE utf8mb3_bin NOT NULL,
            `role`                  enum('sudo','admin','player')       COLLATE utf8mb3_bin NOT NULL DEFAULT 'player',
            `is_active`             tinyint(1)                          NOT NULL DEFAULT 1,
            `must_change_password`  tinyint(1)                          NOT NULL DEFAULT 1,
            `mj_notes`              text                                COLLATE utf8mb3_bin DEFAULT NULL,
            `last_activity`         int                                          DEFAULT NULL,
            `registered`            timestamp                           NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`uuid`),
            UNIQUE KEY `pseudonyme` (`pseudonyme`),
            UNIQUE KEY `email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
    ",
    'down'        => "DROP TABLE IF EXISTS `users`;",
];
