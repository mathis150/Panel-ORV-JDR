<?php

return [
    'version'     => '012',
    'description' => 'Create capacity_activation_effects and stigma_activation_effects tables',
    'up'          => [
        "CREATE TABLE IF NOT EXISTS `capacity_activation_effects` (
            `id`            int          NOT NULL AUTO_INCREMENT,
            `capacity_uuid` varchar(64)  COLLATE utf8mb3_bin NOT NULL,
            `categorie`     varchar(16)  COLLATE utf8mb3_bin NOT NULL DEFAULT 'stat'
                            COMMENT 'stat | degats | soin | statut',
            `sous_type`     varchar(32)  COLLATE utf8mb3_bin NOT NULL DEFAULT 'force',
            `valeur`        varchar(128) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
            `type_duree`    varchar(16)  COLLATE utf8mb3_bin NOT NULL DEFAULT 'tours'
                            COMMENT 'instantane | tours | passif',
            `duree`         int          DEFAULT NULL COMMENT 'Nombre de tours si type_duree=tours',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",

        "CREATE TABLE IF NOT EXISTS `stigma_activation_effects` (
            `id`           int          NOT NULL AUTO_INCREMENT,
            `stigma_uuid`  varchar(64)  COLLATE utf8mb3_bin NOT NULL,
            `categorie`    varchar(16)  COLLATE utf8mb3_bin NOT NULL DEFAULT 'stat'
                           COMMENT 'stat | degats | soin | statut',
            `sous_type`    varchar(32)  COLLATE utf8mb3_bin NOT NULL DEFAULT 'force',
            `valeur`       varchar(128) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
            `type_duree`   varchar(16)  COLLATE utf8mb3_bin NOT NULL DEFAULT 'tours'
                           COMMENT 'instantane | tours | passif',
            `duree`        int          DEFAULT NULL COMMENT 'Nombre de tours si type_duree=tours',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",
    ],
    'down' => [
        "DROP TABLE IF EXISTS `stigma_activation_effects`;",
        "DROP TABLE IF EXISTS `capacity_activation_effects`;",
    ],
];
