<?php

    require_once __DIR__ . '/database.php';

    class ScenariosManager extends Database {

        public const TYPES = ['principal', 'secondaire', 'personnel'];

        public const TYPE_LABELS = [
            'principal'  => 'Principal',
            'secondaire' => 'Secondaire',
            'personnel'  => 'Personnel',
        ];

        public const TYPE_COLORS = [
            'principal'  => '#FFD700',
            'secondaire' => '#42A5F5',
            'personnel'  => '#66BB6A',
        ];

        public const RANGS = ['F', 'E', 'D', 'C', 'B', 'A', 'S', 'SS', 'SSS'];

        public const RANG_COLORS = [
            'F'   => '#9E9E9E',
            'E'   => '#78909C',
            'D'   => '#27AE60',
            'C'   => '#2980B9',
            'B'   => '#8E44AD',
            'A'   => '#E67E22',
            'S'   => '#E74C3C',
            'SS'  => '#C0392B',
            'SSS' => '#FFD700',
        ];

        public const TEMPS_TYPES = ['aucune', 'minute', 'heure', 'jour', 'semaine', 'mois'];

        public const TEMPS_LABELS = [
            'aucune'  => 'Aucune',
            'minute'  => 'Minute(s)',
            'heure'   => 'Heure(s)',
            'jour'    => 'Jour(s)',
            'semaine' => 'Semaine(s)',
            'mois'    => 'Mois',
        ];

        public const REWARD_TYPES = ['coins', 'histoire', 'objet', 'autre'];

        public const REWARD_TYPE_LABELS = [
            'coins'   => 'Coins',
            'histoire' => 'Histoire',
            'objet'   => 'Objet',
            'autre'   => 'Autre',
        ];

        public const REWARD_TYPE_COLORS = [
            'coins'   => '#F39C12',
            'histoire' => '#AB47BC',
            'objet'   => '#1ABC9C',
            'autre'   => '#7F8C8D',
        ];

        public const REWARD_TYPE_ICONS = [
            'coins'   => 'fa-coins',
            'histoire' => 'fa-scroll',
            'objet'   => 'fa-flask',
            'autre'   => 'fa-gift',
        ];

        public function __construct() {
            parent::__construct();
        }

        // ── Création ──────────────────────────────────────────────────────────────

        public function createScenario(array $d): array {
            $titre = trim($d['titre'] ?? '');
            if ($titre === '') return ['success' => false, 'message' => 'Le titre du scénario est requis.'];
            $type   = in_array($d['type'] ?? '', self::TYPES) ? $d['type'] : 'secondaire';
            $rang   = in_array($d['rang'] ?? '', self::RANGS) ? $d['rang'] : 'F';
            $uuid   = $this->generateCustomUUID();
            $numero = $type === 'principal' && is_numeric($d['numero'] ?? '') && (int)$d['numero'] > 0
                      ? (int)$d['numero'] : null;
            $tempsType = in_array($d['temps_type'] ?? '', self::TEMPS_TYPES) ? $d['temps_type'] : 'aucune';
            $tempsVal  = $tempsType !== 'aucune' && is_numeric($d['temps_valeur'] ?? '') && (int)$d['temps_valeur'] > 0
                         ? (int)$d['temps_valeur'] : null;
            $this->makeSQLRequest(
                "INSERT INTO scenarios
                    (uuid, titre, type, numero, rang, rang_plus, temps_type, temps_valeur, echec_info, description)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $uuid, $titre, $type, $numero, $rang,
                    !empty($d['rang_plus']) ? 1 : 0,
                    $tempsType, $tempsVal,
                    trim($d['echec_info']  ?? '') ?: null,
                    trim($d['description'] ?? '') ?: null,
                ],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'uuid' => $uuid, 'message' => 'Scénario créé.'];
        }

        // ── Lecture ───────────────────────────────────────────────────────────────

        public function getScenarios(): array {
            return $this->makeSQLRequest(
                "SELECT uuid, titre, type, numero, rang, rang_plus, temps_type, temps_valeur,
                        is_active, created_at
                 FROM scenarios
                 ORDER BY FIELD(type,'principal','secondaire','personnel'),
                          numero ASC, titre ASC",
                [], ORV_SQL_FETCH_ALL
            ) ?: [];
        }

        public function getScenarioByUUID(string $uuid): ?array {
            return $this->makeSQLRequest(
                "SELECT * FROM scenarios WHERE uuid = ?",
                [$uuid], ORV_SQL_FETCH_ONE
            ) ?: null;
        }

        // ── Mise à jour ───────────────────────────────────────────────────────────

        public function updateScenario(string $uuid, array $d): array {
            $titre = trim($d['titre'] ?? '');
            if ($titre === '') return ['success' => false, 'message' => 'Le titre du scénario est requis.'];
            $type   = in_array($d['type'] ?? '', self::TYPES) ? $d['type'] : 'secondaire';
            $rang   = in_array($d['rang'] ?? '', self::RANGS) ? $d['rang'] : 'F';
            $numero = $type === 'principal' && is_numeric($d['numero'] ?? '') && (int)$d['numero'] > 0
                      ? (int)$d['numero'] : null;
            $tempsType = in_array($d['temps_type'] ?? '', self::TEMPS_TYPES) ? $d['temps_type'] : 'aucune';
            $tempsVal  = $tempsType !== 'aucune' && is_numeric($d['temps_valeur'] ?? '') && (int)$d['temps_valeur'] > 0
                         ? (int)$d['temps_valeur'] : null;
            $this->makeSQLRequest(
                "UPDATE scenarios SET
                    titre = ?, type = ?, numero = ?, rang = ?, rang_plus = ?,
                    temps_type = ?, temps_valeur = ?, echec_info = ?, description = ?, is_active = ?
                 WHERE uuid = ?",
                [
                    $titre, $type, $numero, $rang,
                    !empty($d['rang_plus']) ? 1 : 0,
                    $tempsType, $tempsVal,
                    trim($d['echec_info']  ?? '') ?: null,
                    trim($d['description'] ?? '') ?: null,
                    !empty($d['is_active']) ? 1 : 0,
                    $uuid,
                ],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Scénario mis à jour.'];
        }

        // ── Suppression ───────────────────────────────────────────────────────────

        public function deleteScenario(string $uuid): void {
            $this->makeSQLRequest("DELETE FROM scenario_rewards WHERE scenario_uuid = ?", [$uuid], ORV_SQL_NOTHING);
            $this->makeSQLRequest("DELETE FROM scenarios        WHERE uuid = ?",          [$uuid], ORV_SQL_NOTHING);
        }

        // ── Récompenses ───────────────────────────────────────────────────────────

        public function getScenarioRewards(string $uuid): array {
            return $this->makeSQLRequest(
                "SELECT * FROM scenario_rewards WHERE scenario_uuid = ? ORDER BY id ASC",
                [$uuid], ORV_SQL_FETCH_ALL
            ) ?: [];
        }

        public function addScenarioReward(string $uuid, string $type, string $nom, ?int $quantite): array {
            $nom  = trim($nom);
            $type = in_array($type, self::REWARD_TYPES) ? $type : 'autre';
            if ($type === 'coins' && ($quantite === null || $quantite <= 0)) {
                return ['success' => false, 'message' => 'Une récompense en coins nécessite une quantité valide.'];
            }
            if ($type !== 'coins' && $nom === '') {
                return ['success' => false, 'message' => 'Le nom de la récompense est requis.'];
            }
            if ($type === 'coins' && $nom === '') $nom = 'Coins';
            $this->makeSQLRequest(
                "INSERT INTO scenario_rewards (scenario_uuid, type, nom, quantite) VALUES (?, ?, ?, ?)",
                [$uuid, $type, $nom, $quantite], ORV_SQL_NOTHING
            );
            $label = self::REWARD_TYPE_LABELS[$type] ?? $type;
            $suffix = $quantite !== null ? " ({$quantite})" : '';
            return ['success' => true, 'message' => "Récompense « {$nom}{$suffix} » ajoutée."];
        }

        public function removeScenarioReward(int $id, string $uuid): void {
            $this->makeSQLRequest(
                "DELETE FROM scenario_rewards WHERE id = ? AND scenario_uuid = ?",
                [$id, $uuid], ORV_SQL_NOTHING
            );
        }

        // ── Helpers ───────────────────────────────────────────────────────────────

        public function getRangDisplay(string $rang, bool $plus): string {
            return $rang . ($plus ? '+' : '');
        }

        public function getTempsDisplay(string $tempsType, ?int $tempsValeur): string {
            if ($tempsType === 'aucune' || $tempsValeur === null) return 'Aucune';
            $label = self::TEMPS_LABELS[$tempsType] ?? $tempsType;
            return $tempsValeur . ' ' . $label;
        }

        public function getTitleDisplay(array $scenario): string {
            if ($scenario['type'] === 'principal' && $scenario['numero'] !== null) {
                return 'Scénario Principal n°' . (int)$scenario['numero'] . ' — ' . $scenario['titre'];
            }
            return $scenario['titre'];
        }
    }
