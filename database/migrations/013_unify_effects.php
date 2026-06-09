<?php

return [
    'version'     => '013',
    'description' => 'Add nom/description/ordre to activation_effects, drop old action/effect tables',
    'up'          => [
        "ALTER TABLE `capacity_activation_effects`
            MODIFY COLUMN `categorie` varchar(32) COLLATE utf8mb3_bin NOT NULL DEFAULT 'stat',
            ADD COLUMN `nom`         varchar(256) COLLATE utf8mb3_bin NOT NULL DEFAULT '' AFTER `id`,
            ADD COLUMN `description` text         COLLATE utf8mb3_bin DEFAULT NULL,
            ADD COLUMN `ordre`       int          NOT NULL DEFAULT 0;",

        "ALTER TABLE `stigma_activation_effects`
            MODIFY COLUMN `categorie` varchar(32) COLLATE utf8mb3_bin NOT NULL DEFAULT 'stat',
            ADD COLUMN `nom`         varchar(256) COLLATE utf8mb3_bin NOT NULL DEFAULT '' AFTER `id`,
            ADD COLUMN `description` text         COLLATE utf8mb3_bin DEFAULT NULL,
            ADD COLUMN `ordre`       int          NOT NULL DEFAULT 0;",

        "DROP TABLE IF EXISTS `capacity_actions`;",
        "DROP TABLE IF EXISTS `stigma_effects`;",
    ],
    'down' => [
        "ALTER TABLE `stigma_activation_effects`
            DROP COLUMN `ordre`, DROP COLUMN `description`, DROP COLUMN `nom`,
            MODIFY COLUMN `categorie` varchar(16) COLLATE utf8mb3_bin NOT NULL DEFAULT 'stat';",
        "ALTER TABLE `capacity_activation_effects`
            DROP COLUMN `ordre`, DROP COLUMN `description`, DROP COLUMN `nom`,
            MODIFY COLUMN `categorie` varchar(16) COLLATE utf8mb3_bin NOT NULL DEFAULT 'stat';",
    ],
];
