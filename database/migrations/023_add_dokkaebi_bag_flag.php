<?php

return [
    'version'     => '023',
    'description' => 'Add has_dokkaebi_bag flag to characters table',
    'up' => [
        "ALTER TABLE `characters`
            ADD COLUMN `has_dokkaebi_bag` tinyint(1) NOT NULL DEFAULT 0 AFTER `is_active`;",
    ],
    'down' => [
        "ALTER TABLE `characters`
            DROP COLUMN `has_dokkaebi_bag`;",
    ],
];
