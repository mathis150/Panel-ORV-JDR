<?php

return [
    'version'     => '006',
    'description' => 'Create character sub-tables (titles, attributes, skills, stigmata, inventory, equipped)',
    'up'          => [
        "CREATE TABLE IF NOT EXISTS `character_titles` (
            `id`             int          NOT NULL AUTO_INCREMENT,
            `character_uuid` varchar(64)  COLLATE utf8mb3_bin NOT NULL,
            `title`          varchar(256) COLLATE utf8mb3_bin NOT NULL,
            `is_displayed`   tinyint(1)   NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",

        "CREATE TABLE IF NOT EXISTS `character_attributes` (
            `id`             int         NOT NULL AUTO_INCREMENT,
            `character_uuid` varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `attribute_uuid` varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `is_locked`      tinyint(1)  NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            UNIQUE KEY `char_attr` (`character_uuid`, `attribute_uuid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",

        "CREATE TABLE IF NOT EXISTS `character_skills` (
            `id`             int         NOT NULL AUTO_INCREMENT,
            `character_uuid` varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `skill_uuid`     varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `is_locked`      tinyint(1)  NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            UNIQUE KEY `char_skill` (`character_uuid`, `skill_uuid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",

        "CREATE TABLE IF NOT EXISTS `character_stigmata` (
            `id`             int         NOT NULL AUTO_INCREMENT,
            `character_uuid` varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `stigma_uuid`    varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `is_locked`      tinyint(1)  NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            UNIQUE KEY `char_stigma` (`character_uuid`, `stigma_uuid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",

        "CREATE TABLE IF NOT EXISTS `character_inventory` (
            `id`             int         NOT NULL AUTO_INCREMENT,
            `character_uuid` varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `item_uuid`      varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `quantity`       int         NOT NULL DEFAULT 1,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",

        "CREATE TABLE IF NOT EXISTS `character_equipped` (
            `id`             int         NOT NULL AUTO_INCREMENT,
            `character_uuid` varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `item_uuid`      varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `slot`           varchar(64) COLLATE utf8mb3_bin DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",
    ],
    'down' => [
        "DROP TABLE IF EXISTS `character_equipped`;",
        "DROP TABLE IF EXISTS `character_inventory`;",
        "DROP TABLE IF EXISTS `character_stigmata`;",
        "DROP TABLE IF EXISTS `character_skills`;",
        "DROP TABLE IF EXISTS `character_attributes`;",
        "DROP TABLE IF EXISTS `character_titles`;",
    ],
];
