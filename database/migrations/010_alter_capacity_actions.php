<?php

return [
    'version'     => '010',
    'description' => 'Add type and valeur columns to capacity_actions',
    'up'          => [
        "ALTER TABLE `capacity_actions`
            ADD COLUMN `type`   varchar(32)  COLLATE utf8mb3_bin NOT NULL DEFAULT 'autre' AFTER `nom`,
            ADD COLUMN `valeur` varchar(512) COLLATE utf8mb3_bin DEFAULT NULL AFTER `type`;",
    ],
    'down' => [
        "ALTER TABLE `capacity_actions` DROP COLUMN `valeur`, DROP COLUMN `type`;",
    ],
];
