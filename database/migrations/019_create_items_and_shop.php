<?php

return [
    'version'     => '019',
    'description' => 'Create items catalog and dokkaebi_shop_listings tables',
    'up'          => [
        "CREATE TABLE IF NOT EXISTS `items` (
            `uuid`        varchar(64)  COLLATE utf8mb3_bin NOT NULL,
            `nom`         varchar(256) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
            `type`        varchar(32)  COLLATE utf8mb3_bin NOT NULL DEFAULT 'consommable'
                          COMMENT 'consommable | equipement | rang | titre | competence | divers',
            `rang`        varchar(4)   COLLATE utf8mb3_bin NOT NULL DEFAULT 'F'
                          COMMENT 'F | E | D | C | B | A | S | SS | SSS',
            `description` text         COLLATE utf8mb3_bin DEFAULT NULL,
            `is_active`   tinyint(1)   NOT NULL DEFAULT 1,
            `created_at`  timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at`  timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`uuid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",

        "CREATE TABLE IF NOT EXISTS `dokkaebi_shop_listings` (
            `id`          int          NOT NULL AUTO_INCREMENT,
            `item_uuid`   varchar(64)  COLLATE utf8mb3_bin NOT NULL,
            `prix`        int          NOT NULL DEFAULT 0
                          COMMENT 'Prix en Coins',
            `quantite`    int          DEFAULT NULL
                          COMMENT 'NULL = illimitée',
            `vendeur`     varchar(256) COLLATE utf8mb3_bin DEFAULT NULL,
            `rang_min`    varchar(16)  COLLATE utf8mb3_bin NOT NULL DEFAULT 'iron'
                          COMMENT 'iron | gold | platinium | diamond',
            `is_vedette`  tinyint(1)   NOT NULL DEFAULT 0,
            `is_actif`    tinyint(1)   NOT NULL DEFAULT 1,
            `created_at`  timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at`  timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uq_shop_item` (`item_uuid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",
    ],
    'down' => [
        "DROP TABLE IF EXISTS `dokkaebi_shop_listings`;",
        "DROP TABLE IF EXISTS `items`;",
    ],
];
