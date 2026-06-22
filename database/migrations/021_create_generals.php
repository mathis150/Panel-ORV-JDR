<?php

return [
    'version'     => '021',
    'description' => 'Create generals (settings) table with default values',
    'up' => [
        "CREATE TABLE IF NOT EXISTS `generals` (
            `key`        varchar(64)  COLLATE utf8mb3_bin NOT NULL,
            `value`      text         COLLATE utf8mb3_bin DEFAULT NULL,
            `updated_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",

        "INSERT IGNORE INTO `generals` (`key`, `value`) VALUES
            ('app_name',          'Solo Leveling JDR'),
            ('app_subtitle',      'Système de gestion de jeu de rôle'),
            ('app_status',        'active'),
            ('app_status_msg',    ''),
            ('announce_active',   '0'),
            ('announce_type',     'info'),
            ('announce_text',     ''),
            ('world_arc',         ''),
            ('world_season',      'Saison 1'),
            ('world_session',     '1'),
            ('world_date',        ''),
            ('eco_currency',      'Coins'),
            ('eco_symbol',        'C'),
            ('eco_start_coins',   '0'),
            ('rules_max_players', '6'),
            ('rules_pvp',         '0'),
            ('rules_notes',       '');",
    ],
    'down' => [
        "DROP TABLE IF EXISTS `generals`;",
    ],
];
