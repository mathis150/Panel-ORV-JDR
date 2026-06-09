<?php

return [
    'version'     => '009',
    'description' => 'Create capacities, capacity_actions and capacity_levelup_conditions tables',
    'up'          => [
        "CREATE TABLE IF NOT EXISTS `capacities` (
            `uuid`            varchar(64)   COLLATE utf8mb3_bin NOT NULL,
            `nom`             varchar(128)  COLLATE utf8mb3_bin NOT NULL DEFAULT '',
            `rang`            varchar(8)    COLLATE utf8mb3_bin NOT NULL DEFAULT 'E',
            `description`     text          COLLATE utf8mb3_bin DEFAULT NULL,
            `effets_visuels`  text          COLLATE utf8mb3_bin DEFAULT NULL,
            `histoire_uuid`   varchar(64)   COLLATE utf8mb3_bin DEFAULT NULL,
            `is_active`       tinyint(1)    NOT NULL DEFAULT 1,
            `created_at`      timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at`      timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`uuid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",

        "CREATE TABLE IF NOT EXISTS `capacity_actions` (
            `id`            int          NOT NULL AUTO_INCREMENT,
            `capacity_uuid` varchar(64)  COLLATE utf8mb3_bin NOT NULL,
            `nom`           varchar(256) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
            `description`   text         COLLATE utf8mb3_bin DEFAULT NULL,
            `ordre`         int          NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",

        "CREATE TABLE IF NOT EXISTS `capacity_levelup_conditions` (
            `id`              int          NOT NULL AUTO_INCREMENT,
            `capacity_uuid`   varchar(64)  COLLATE utf8mb3_bin NOT NULL,
            `niveau_cible`    tinyint      NOT NULL DEFAULT 2 COMMENT 'Niveau à atteindre (2-10)',
            `type`            varchar(32)  COLLATE utf8mb3_bin NOT NULL DEFAULT 'usages' COMMENT 'usages | personnalise',
            `description`     varchar(512) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
            `valeur`          int          DEFAULT NULL COMMENT 'Valeur numérique (ex: nombre d\\'usages requis)',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",
    ],
    'down' => [
        "DROP TABLE IF EXISTS `capacity_levelup_conditions`;",
        "DROP TABLE IF EXISTS `capacity_actions`;",
        "DROP TABLE IF EXISTS `capacities`;",
    ],
];
