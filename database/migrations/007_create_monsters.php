<?php

return [
    'version'     => '007',
    'description' => 'Create monsters and monster_equipped tables',
    'up'          => [
        "CREATE TABLE IF NOT EXISTS `monsters` (
            `uuid`        varchar(64)   COLLATE utf8mb3_bin NOT NULL,
            `nom`         varchar(128)  COLLATE utf8mb3_bin NOT NULL DEFAULT '',
            `race`        varchar(128)  COLLATE utf8mb3_bin DEFAULT NULL,
            `rank`        tinyint       NOT NULL DEFAULT 9,
            `description` text          COLLATE utf8mb3_bin DEFAULT NULL,
            `vitalite`    int           NOT NULL DEFAULT 100,
            `force`       int           NOT NULL DEFAULT 0,
            `agilite`     int           NOT NULL DEFAULT 0,
            `mana`        int           NOT NULL DEFAULT 0,
            `notes`       text          COLLATE utf8mb3_bin DEFAULT NULL,
            `is_active`   tinyint(1)    NOT NULL DEFAULT 1,
            `created_at`  timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at`  timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`uuid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",

        "CREATE TABLE IF NOT EXISTS `monster_equipped` (
            `id`           int         NOT NULL AUTO_INCREMENT,
            `monster_uuid` varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `item_uuid`    varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `slot`         varchar(64) COLLATE utf8mb3_bin DEFAULT NULL,
            `quantity`     int         NOT NULL DEFAULT 1,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",
    ],
    'down' => [
        "DROP TABLE IF EXISTS `monster_equipped`;",
        "DROP TABLE IF EXISTS `monsters`;",
    ],
];
