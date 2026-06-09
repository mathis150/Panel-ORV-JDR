<?php

return [
    'version'     => '014',
    'description' => 'Create attributs and attribut_effects tables',
    'up'          => [
        "CREATE TABLE IF NOT EXISTS `attributs` (
            `uuid`        varchar(64)   COLLATE utf8mb3_bin NOT NULL,
            `nom`         varchar(128)  COLLATE utf8mb3_bin NOT NULL DEFAULT '',
            `rang`        varchar(32)   COLLATE utf8mb3_bin NOT NULL DEFAULT 'Commun',
            `description` text          COLLATE utf8mb3_bin DEFAULT NULL,
            `is_active`   tinyint(1)    NOT NULL DEFAULT 1,
            `created_at`  timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at`  timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`uuid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",

        "CREATE TABLE IF NOT EXISTS `attribut_effects` (
            `id`            int          NOT NULL AUTO_INCREMENT,
            `attribut_uuid` varchar(64)  COLLATE utf8mb3_bin NOT NULL,
            `nom`           varchar(256) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
            `categorie`     varchar(32)  COLLATE utf8mb3_bin NOT NULL DEFAULT 'stat',
            `sous_type`     varchar(64)  COLLATE utf8mb3_bin NOT NULL DEFAULT 'force',
            `valeur`        varchar(256) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
            `type_duree`    varchar(16)  COLLATE utf8mb3_bin NOT NULL DEFAULT 'tours',
            `duree`         int          DEFAULT NULL,
            `description`   text         COLLATE utf8mb3_bin DEFAULT NULL,
            `ordre`         int          NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",
    ],
    'down' => [
        "DROP TABLE IF EXISTS `attribut_effects`;",
        "DROP TABLE IF EXISTS `attributs`;",
    ],
];
