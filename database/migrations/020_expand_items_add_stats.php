<?php

return [
    'version'     => '020',
    'description' => 'Expand items.rang column length and add item_stats table',
    'up' => [
        "ALTER TABLE `items`
            MODIFY COLUMN `rang` varchar(16) COLLATE utf8mb3_bin NOT NULL DEFAULT 'F';",

        "CREATE TABLE IF NOT EXISTS `item_stats` (
            `id`        int unsigned NOT NULL AUTO_INCREMENT,
            `item_uuid` varchar(64)  COLLATE utf8mb3_bin NOT NULL,
            `stat_type` varchar(16)  COLLATE utf8mb3_bin NOT NULL
                        COMMENT 'force | vitalite | agilite | mana',
            `valeur`    int          NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            KEY `idx_is_item` (`item_uuid`),
            CONSTRAINT `fk_is_item_uuid`
                FOREIGN KEY (`item_uuid`) REFERENCES `items` (`uuid`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",
    ],
    'down' => [
        "DROP TABLE IF EXISTS `item_stats`;",
        "ALTER TABLE `items`
            MODIFY COLUMN `rang` varchar(4) COLLATE utf8mb3_bin NOT NULL DEFAULT 'F';",
    ],
];
