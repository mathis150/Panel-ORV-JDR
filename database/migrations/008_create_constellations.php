<?php

return [
    'version'     => '008',
    'description' => 'Create constellations table and constellation sub-tables',
    'up'          => [
        "CREATE TABLE IF NOT EXISTS `constellations` (
            `uuid`                varchar(64)   COLLATE utf8mb3_bin NOT NULL,
            `nom`                 varchar(128)  COLLATE utf8mb3_bin NOT NULL DEFAULT '',
            `identite`            varchar(256)  COLLATE utf8mb3_bin DEFAULT NULL,
            `provenance`          varchar(256)  COLLATE utf8mb3_bin DEFAULT NULL,
            `rang`                varchar(64)   COLLATE utf8mb3_bin DEFAULT 'narrative',
            `race`                varchar(128)  COLLATE utf8mb3_bin DEFAULT NULL,
            `nebuleuse`           varchar(128)  COLLATE utf8mb3_bin DEFAULT NULL,
            `psyche`              text          COLLATE utf8mb3_bin DEFAULT NULL,
            `vertu`               varchar(512)  COLLATE utf8mb3_bin DEFAULT NULL,
            `vice`                varchar(512)  COLLATE utf8mb3_bin DEFAULT NULL,
            `vitalite`            int           NOT NULL DEFAULT 100,
            `force`               int           NOT NULL DEFAULT 0,
            `agilite`             int           NOT NULL DEFAULT 0,
            `mana`                int           NOT NULL DEFAULT 0,
            `coins`               int           NOT NULL DEFAULT 0,
            `histoire`            longtext      COLLATE utf8mb3_bin DEFAULT NULL,
            `estimation_generale` text          COLLATE utf8mb3_bin DEFAULT NULL,
            `is_active`           tinyint(1)    NOT NULL DEFAULT 1,
            `created_at`          timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at`          timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`uuid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",

        "CREATE TABLE IF NOT EXISTS `constellation_titles` (
            `id`                  int          NOT NULL AUTO_INCREMENT,
            `constellation_uuid`  varchar(64)  COLLATE utf8mb3_bin NOT NULL,
            `title`               varchar(256) COLLATE utf8mb3_bin NOT NULL,
            `is_displayed`        tinyint(1)   NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",

        "CREATE TABLE IF NOT EXISTS `constellation_attributes` (
            `id`                  int         NOT NULL AUTO_INCREMENT,
            `constellation_uuid`  varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `attribute_uuid`      varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `is_locked`           tinyint(1)  NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            UNIQUE KEY `constel_attr` (`constellation_uuid`, `attribute_uuid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",

        "CREATE TABLE IF NOT EXISTS `constellation_skills` (
            `id`                  int         NOT NULL AUTO_INCREMENT,
            `constellation_uuid`  varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `skill_uuid`          varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `is_locked`           tinyint(1)  NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            UNIQUE KEY `constel_skill` (`constellation_uuid`, `skill_uuid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",

        "CREATE TABLE IF NOT EXISTS `constellation_stigmata` (
            `id`                  int         NOT NULL AUTO_INCREMENT,
            `constellation_uuid`  varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `stigma_uuid`         varchar(64) COLLATE utf8mb3_bin NOT NULL,
            `is_locked`           tinyint(1)  NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            UNIQUE KEY `constel_stigma` (`constellation_uuid`, `stigma_uuid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",
    ],
    'down' => [
        "DROP TABLE IF EXISTS `constellation_stigmata`;",
        "DROP TABLE IF EXISTS `constellation_skills`;",
        "DROP TABLE IF EXISTS `constellation_attributes`;",
        "DROP TABLE IF EXISTS `constellation_titles`;",
        "DROP TABLE IF EXISTS `constellations`;",
    ],
];
