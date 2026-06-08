<?php

return [
    'version'     => '005',
    'description' => 'Create characters table',
    'up'          => "
        CREATE TABLE IF NOT EXISTS `characters` (
            `uuid`                       varchar(64)   COLLATE utf8mb3_bin NOT NULL,
            `user_uuid`                  varchar(64)   COLLATE utf8mb3_bin DEFAULT NULL,
            `nom`                        varchar(128)  COLLATE utf8mb3_bin NOT NULL DEFAULT '',
            `prenom`                     varchar(128)  COLLATE utf8mb3_bin NOT NULL DEFAULT '',
            `race`                       varchar(128)  COLLATE utf8mb3_bin DEFAULT NULL,
            `nationalite`                varchar(128)  COLLATE utf8mb3_bin DEFAULT NULL,
            `metier`                     varchar(128)  COLLATE utf8mb3_bin DEFAULT NULL,
            `constellation_sponsor_uuid` varchar(64)   COLLATE utf8mb3_bin DEFAULT NULL,
            `psyche`                     text          COLLATE utf8mb3_bin DEFAULT NULL,
            `vertu`                      varchar(512)  COLLATE utf8mb3_bin DEFAULT NULL,
            `vice`                       varchar(512)  COLLATE utf8mb3_bin DEFAULT NULL,
            `apparence_description`      text          COLLATE utf8mb3_bin DEFAULT NULL,
            `apparence_image`            varchar(512)  COLLATE utf8mb3_bin DEFAULT NULL,
            `histoire`                   longtext      COLLATE utf8mb3_bin DEFAULT NULL,
            `estimation_generale`        text          COLLATE utf8mb3_bin DEFAULT NULL,
            `coins`                      int           NOT NULL DEFAULT 0,
            `vitalite`                   int           NOT NULL DEFAULT 100,
            `force`                      int           NOT NULL DEFAULT 0,
            `agilite`                    int           NOT NULL DEFAULT 0,
            `mana`                       int           NOT NULL DEFAULT 0,
            `is_active`                  tinyint(1)    NOT NULL DEFAULT 1,
            `created_at`                 timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at`                 timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`uuid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
    ",
    'down' => "DROP TABLE IF EXISTS `characters`;",
];
