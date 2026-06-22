<?php
return [
    'version'     => '024',
    'description' => 'Add shop_rang column to characters table',
    'up' => [
        "ALTER TABLE `characters`
            ADD COLUMN `shop_rang` ENUM('iron','gold','platinium','diamond') NOT NULL DEFAULT 'iron'
            AFTER `has_dokkaebi_bag`;",
    ],
    'down' => [
        "ALTER TABLE `characters` DROP COLUMN `shop_rang`;",
    ],
];
