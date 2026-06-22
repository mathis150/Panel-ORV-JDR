<?php

return [
    'version'     => '018',
    'description' => 'Create scenarios and scenario_rewards tables',
    'up'          => [
        "CREATE TABLE IF NOT EXISTS `scenarios` (
            `uuid`         varchar(64)  COLLATE utf8mb3_bin NOT NULL,
            `titre`        varchar(256) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
            `type`         varchar(16)  COLLATE utf8mb3_bin NOT NULL DEFAULT 'secondaire'
                           COMMENT 'principal | secondaire | personnel',
            `numero`       int          DEFAULT NULL
                           COMMENT 'Numéro du scénario (principal uniquement)',
            `rang`         varchar(4)   COLLATE utf8mb3_bin NOT NULL DEFAULT 'F'
                           COMMENT 'F | E | D | C | B | A | S | SS | SSS',
            `rang_plus`    tinyint(1)   NOT NULL DEFAULT 0
                           COMMENT '0 = normal, 1 = rang+',
            `temps_type`   varchar(16)  COLLATE utf8mb3_bin NOT NULL DEFAULT 'aucune'
                           COMMENT 'aucune | minute | heure | jour | semaine | mois',
            `temps_valeur` int          DEFAULT NULL,
            `echec_info`   text         COLLATE utf8mb3_bin DEFAULT NULL
                           COMMENT 'Conséquence en cas d\'échec',
            `description`  text         COLLATE utf8mb3_bin DEFAULT NULL,
            `is_active`    tinyint(1)   NOT NULL DEFAULT 1,
            `created_at`   timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at`   timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`uuid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",

        "CREATE TABLE IF NOT EXISTS `scenario_rewards` (
            `id`            int          NOT NULL AUTO_INCREMENT,
            `scenario_uuid` varchar(64)  COLLATE utf8mb3_bin NOT NULL,
            `type`          varchar(16)  COLLATE utf8mb3_bin NOT NULL DEFAULT 'autre'
                            COMMENT 'coins | histoire | objet | autre',
            `nom`           varchar(256) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
            `quantite`      int          DEFAULT NULL
                            COMMENT 'Montant ou quantité (ex : nombre de coins)',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;",
    ],
    'down' => [
        "DROP TABLE IF EXISTS `scenario_rewards`;",
        "DROP TABLE IF EXISTS `scenarios`;",
    ],
];
