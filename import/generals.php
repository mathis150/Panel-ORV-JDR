<?php

    require_once __DIR__ . '/database.php';

    class GeneralsManager extends Database {

        // Valeurs par défaut si une clé n'existe pas encore en base
        public const DEFAULTS = [
            'app_name'          => 'Solo Leveling JDR',
            'app_subtitle'      => 'Système de gestion de jeu de rôle',
            'app_status'        => 'active',
            'app_status_msg'    => '',
            'announce_active'   => '0',
            'announce_type'     => 'info',
            'announce_text'     => '',
            'world_arc'         => '',
            'world_season'      => 'Saison 1',
            'world_session'     => '1',
            'world_date'        => '',
            'eco_currency'      => 'Coins',
            'eco_symbol'        => 'C',
            'eco_start_coins'   => '0',
            'rules_max_players' => '6',
            'rules_pvp'         => '0',
            'rules_notes'       => '',
        ];

        public const STATUS_LABELS = [
            'active'      => 'Actif',
            'maintenance' => 'Maintenance',
            'pause'       => 'En pause',
        ];

        public const STATUS_COLORS = [
            'active'      => '#2ECC71',
            'maintenance' => '#E67E22',
            'pause'       => '#3498DB',
        ];

        public const STATUS_ICONS = [
            'active'      => 'fa-circle-check',
            'maintenance' => 'fa-wrench',
            'pause'       => 'fa-pause-circle',
        ];

        public const ANNOUNCE_TYPES = [
            'info'    => ['label' => 'Information',  'color' => '#42A5F5', 'icon' => 'fa-circle-info'],
            'success' => ['label' => 'Succès',        'color' => '#2ECC71', 'icon' => 'fa-circle-check'],
            'warning' => ['label' => 'Avertissement', 'color' => '#E67E22', 'icon' => 'fa-triangle-exclamation'],
            'danger'  => ['label' => 'Danger',        'color' => '#EF5350', 'icon' => 'fa-circle-xmark'],
        ];

        public function __construct() { parent::__construct(); }

        public function get(string $key): string {
            $r = $this->makeSQLRequest(
                "SELECT `value` FROM generals WHERE `key` = ?",
                [$key], ORV_SQL_FETCH_ONE
            );
            if ($r && array_key_exists('value', $r)) {
                return (string)($r['value'] ?? '');
            }
            return self::DEFAULTS[$key] ?? '';
        }

        public function getAll(): array {
            // Alias `key` AS uuid so ORV_SQL_FETCH_ALL keys the result by setting name
            $rows = $this->makeSQLRequest(
                "SELECT `key` AS uuid, `value` FROM generals ORDER BY `key`",
                [], ORV_SQL_FETCH_ALL
            ) ?: [];

            $out = self::DEFAULTS;
            foreach ((array)$rows as $settingKey => $row) {
                if (is_array($row)) {
                    $out[$settingKey] = (string)($row['value'] ?? '');
                }
            }
            return $out;
        }

        public function set(string $key, ?string $value): void {
            $this->makeSQLRequest(
                "INSERT INTO generals (`key`, `value`) VALUES (?, ?)
                 ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)",
                [$key, $value], ORV_SQL_NOTHING
            );
        }

        public function setMany(array $data): void {
            foreach ($data as $key => $value) {
                $this->set((string)$key, $value !== null ? (string)$value : null);
            }
        }
    }
