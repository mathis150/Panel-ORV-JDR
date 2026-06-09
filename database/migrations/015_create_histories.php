<?php

return [
    'version'     => '015',
    'description' => 'Create histories and history_level_stigmata tables',
    'up'          => [
        "CREATE TABLE IF NOT EXISTS `histories` (
            `uuid`           varchar(64)   COLLATE utf8mb3_bin NOT NULL,
            `titre`          varchar(256)  COLLATE utf8mb3_bin NOT NULL DEFAULT '',
            `rang`           varchar(64)   COLLATE utf8mb3_bin NOT NULL DEFAULT 'Historique bas-rang',
            `description`    text          COLLATE utf8mb3_bin DEFAULT NULL,
            `statut`         varchar(16)   COLLATE utf8mb3_bin NOT NULL DEFAULT 'complete'
                                            COMMENT 'complete | brisee',
            `completion_pct` int           NOT NULL DEFAULT 100
                                            COMMENT 'Pourcentage de complétion (100 = complète, <100 = brisée)',
            `is_perso`       tinyint(1)    NOT NULL DEFAULT 0
                                            COMMENT 'Histoire du personnage',
            `is_fondatrice`  tinyint(1)    NOT NULL DEFAULT 0
                                            COMMENT 'Histoire fondatrice',
            `ownership_pct`  int           NOT NULL DEFAULT 100
                                            COMMENT 'Fraction de propriété % (Histoire Gigantesque)',
            `niveau_actuel`  int           NOT NULL DEFAULT 1
                                            COMMENT 'Niveau actuel de l\'histoire (1-10)',
            `is_active`      tinyint(1)    NOT NULL DEFAULT 1,
            `created_at`     timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at`     timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`uuid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",

"CREATE TABLE IF NOT EXISTS `history_level_stigmata` (
            `id`            int         NOT NULL AUTO_INCREMENT,
            `history_uuid`  varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `niveau`        tinyint     NOT NULL DEFAULT 1 COMMENT 'Niveau 1-10',
            `stigma_uuid`   varchar(64) COLLATE utf8mb3_bin NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `hist_lvl_stigma` (`history_uuid`, `niveau`, `stigma_uuid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",
    ],
    'down' => [
        "DROP TABLE IF EXISTS `history_level_stigmata`;",
        "DROP TABLE IF EXISTS `histories`;",
    ],
];
