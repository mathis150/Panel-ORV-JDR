<?php

return [
    'version'     => '002',
    'description' => 'Create sessions table',
    'up'          => "
        CREATE TABLE IF NOT EXISTS `sessions` (
            `id`         int         NOT NULL AUTO_INCREMENT,
            `token`      varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `user_uuid`  varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `expires_at` int         NOT NULL,
            `created_at` timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `token` (`token`),
            KEY `user_uuid` (`user_uuid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
    ",
    'down'        => "DROP TABLE IF EXISTS `sessions`;",
];
