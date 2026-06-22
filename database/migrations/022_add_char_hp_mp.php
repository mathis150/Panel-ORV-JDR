<?php

return [
    'version'     => '022',
    'description' => 'Add hp_actuel and mp_actuel columns to characters table',
    'up' => [
        "ALTER TABLE `characters`
            ADD COLUMN `hp_actuel` int NOT NULL DEFAULT 0 AFTER `mana`,
            ADD COLUMN `mp_actuel` int NOT NULL DEFAULT 0 AFTER `hp_actuel`;",
        "UPDATE `characters` SET
            `hp_actuel` = `vitalite` * 4,
            `mp_actuel` = `mana` * 4;",
    ],
    'down' => [
        "ALTER TABLE `characters`
            DROP COLUMN `hp_actuel`,
            DROP COLUMN `mp_actuel`;",
    ],
];
