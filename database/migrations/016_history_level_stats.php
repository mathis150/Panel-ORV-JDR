<?php

return [
    'version'     => '016',
    'description' => 'Add history_level_stats table for per-level stat bonuses',
    'up'          => [
        "CREATE TABLE IF NOT EXISTS `history_level_stats` (
            `id`            int         NOT NULL AUTO_INCREMENT,
            `history_uuid`  varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `niveau`        tinyint     NOT NULL DEFAULT 1 COMMENT 'Niveau 1-10',
            `stat_type`     varchar(32) COLLATE utf8mb3_bin NOT NULL DEFAULT 'force'
                            COMMENT 'force | agilite | mana | vitalite | coins',
            `valeur`        int         NOT NULL DEFAULT 0
                            COMMENT 'Bonus (positif) ou malus (négatif)',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",
    ],
    'down' => [
        "DROP TABLE IF EXISTS `history_level_stats`;",
    ],
];
