<?php

return [
    'version'     => '017',
    'description' => 'Create modifiers, modifier_stats and modifier_effects tables',
    'up'          => [
        "CREATE TABLE IF NOT EXISTS `modifiers` (
            `uuid`          varchar(64)  COLLATE utf8mb3_bin NOT NULL,
            `nom`           varchar(256) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
            `type`          varchar(16)  COLLATE utf8mb3_bin NOT NULL DEFAULT 'titre'
                            COMMENT 'constellation | titre',
            `histoire_uuid` varchar(64)  COLLATE utf8mb3_bin DEFAULT NULL,
            `description`   text         COLLATE utf8mb3_bin DEFAULT NULL,
            `is_active`     tinyint(1)   NOT NULL DEFAULT 1,
            `created_at`    timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at`    timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`uuid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",

        "CREATE TABLE IF NOT EXISTS `modifier_stats` (
            `id`            int         NOT NULL AUTO_INCREMENT,
            `modifier_uuid` varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `stat_type`     varchar(32) COLLATE utf8mb3_bin NOT NULL DEFAULT 'force'
                            COMMENT 'force | agilite | mana | vitalite | coins',
            `valeur`        int         NOT NULL DEFAULT 0
                            COMMENT 'Bonus (positif) ou malus (négatif)',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",

        "CREATE TABLE IF NOT EXISTS `modifier_effects` (
            `id`            int          NOT NULL AUTO_INCREMENT,
            `modifier_uuid` varchar(64)  COLLATE utf8mb3_bin NOT NULL,
            `nom`           varchar(256) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
            `description`   text         COLLATE utf8mb3_bin DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",
    ],
    'down' => [
        "DROP TABLE IF EXISTS `modifier_effects`;",
        "DROP TABLE IF EXISTS `modifier_stats`;",
        "DROP TABLE IF EXISTS `modifiers`;",
    ],
];
