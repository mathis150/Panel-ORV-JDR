<?php

    require_once __DIR__ . '/database.php';

    class ModifiersManager extends Database {

        public const TYPES = ['constellation', 'titre'];

        public const TYPE_LABELS = [
            'constellation' => 'Modificateur de constellation',
            'titre'         => 'Titre',
        ];

        public const TYPE_COLORS = [
            'constellation' => '#AB47BC',
            'titre'         => '#FFD700',
        ];

        public const STAT_TYPES = [
            'force'    => ['label' => 'Force',    'icon' => 'fa-dumbbell',          'color' => '#E74C3C'],
            'agilite'  => ['label' => 'Agilité',  'icon' => 'fa-bolt',              'color' => '#F1C40F'],
            'mana'     => ['label' => 'Mana',     'icon' => 'fa-fire-flame-curved', 'color' => '#3498DB'],
            'vitalite' => ['label' => 'Vitalité', 'icon' => 'fa-heart',             'color' => '#E91E63'],
            'coins'    => ['label' => 'Coins',    'icon' => 'fa-coins',             'color' => '#F39C12'],
        ];

        public function __construct() {
            parent::__construct();
        }

        // ── Création ──────────────────────────────────────────────────────────────

        public function createModifier(array $d): array {
            $nom = trim($d['nom'] ?? '');
            if ($nom === '') return ['success' => false, 'message' => 'Le nom du modificateur est requis.'];
            $type         = in_array($d['type'] ?? '', self::TYPES) ? $d['type'] : 'titre';
            $uuid         = $this->generateCustomUUID();
            $histoireUuid = trim($d['histoire_uuid'] ?? '') !== '' ? trim($d['histoire_uuid']) : null;
            $this->makeSQLRequest(
                "INSERT INTO modifiers (uuid, nom, type, histoire_uuid, description) VALUES (?, ?, ?, ?, ?)",
                [$uuid, $nom, $type, $histoireUuid, trim($d['description'] ?? '') ?: null],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'uuid' => $uuid, 'message' => 'Modificateur créé.'];
        }

        // ── Lecture ───────────────────────────────────────────────────────────────

        public function getModifiers(): array {
            return $this->makeSQLRequest(
                "SELECT m.uuid, m.nom, m.type, m.histoire_uuid, m.is_active, m.created_at,
                        h.titre AS histoire_titre
                 FROM modifiers m
                 LEFT JOIN histories h ON h.uuid = m.histoire_uuid
                 ORDER BY m.type ASC, m.nom ASC",
                [], ORV_SQL_FETCH_ALL
            ) ?: [];
        }

        public function getModifierByUUID(string $uuid): ?array {
            return $this->makeSQLRequest(
                "SELECT m.*, h.titre AS histoire_titre
                 FROM modifiers m
                 LEFT JOIN histories h ON h.uuid = m.histoire_uuid
                 WHERE m.uuid = ?",
                [$uuid], ORV_SQL_FETCH_ONE
            ) ?: null;
        }

        // ── Mise à jour ───────────────────────────────────────────────────────────

        public function updateModifier(string $uuid, array $d): array {
            $nom = trim($d['nom'] ?? '');
            if ($nom === '') return ['success' => false, 'message' => 'Le nom du modificateur est requis.'];
            $type         = in_array($d['type'] ?? '', self::TYPES) ? $d['type'] : 'titre';
            $histoireUuid = trim($d['histoire_uuid'] ?? '') !== '' ? trim($d['histoire_uuid']) : null;
            $this->makeSQLRequest(
                "UPDATE modifiers SET nom = ?, type = ?, histoire_uuid = ?, description = ?, is_active = ? WHERE uuid = ?",
                [
                    $nom, $type, $histoireUuid,
                    trim($d['description'] ?? '') ?: null,
                    !empty($d['is_active']) ? 1 : 0,
                    $uuid,
                ],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Modificateur mis à jour.'];
        }

        // ── Suppression ───────────────────────────────────────────────────────────

        public function deleteModifier(string $uuid): void {
            $this->makeSQLRequest("DELETE FROM modifier_stats   WHERE modifier_uuid = ?", [$uuid], ORV_SQL_NOTHING);
            $this->makeSQLRequest("DELETE FROM modifier_effects WHERE modifier_uuid = ?", [$uuid], ORV_SQL_NOTHING);
            $this->makeSQLRequest("DELETE FROM modifiers        WHERE uuid = ?",          [$uuid], ORV_SQL_NOTHING);
        }

        // ── Statistiques ──────────────────────────────────────────────────────────

        public function getModifierStats(string $uuid): array {
            return $this->makeSQLRequest(
                "SELECT * FROM modifier_stats WHERE modifier_uuid = ? ORDER BY id ASC",
                [$uuid], ORV_SQL_FETCH_ALL
            ) ?: [];
        }

        public function addModifierStat(string $uuid, string $statType, int $valeur): array {
            if (!isset(self::STAT_TYPES[$statType])) return ['success' => false, 'message' => 'Type de statistique invalide.'];
            if ($valeur === 0) return ['success' => false, 'message' => 'La valeur ne peut pas être 0.'];
            $this->makeSQLRequest(
                "INSERT INTO modifier_stats (modifier_uuid, stat_type, valeur) VALUES (?, ?, ?)",
                [$uuid, $statType, $valeur], ORV_SQL_NOTHING
            );
            $label = self::STAT_TYPES[$statType]['label'];
            $sign  = $valeur > 0 ? '+' : '';
            return ['success' => true, 'message' => "{$sign}{$valeur} {$label} ajouté."];
        }

        public function removeModifierStat(int $id, string $uuid): void {
            $this->makeSQLRequest(
                "DELETE FROM modifier_stats WHERE id = ? AND modifier_uuid = ?",
                [$id, $uuid], ORV_SQL_NOTHING
            );
        }

        // ── Effets ────────────────────────────────────────────────────────────────

        public function getModifierEffects(string $uuid): array {
            return $this->makeSQLRequest(
                "SELECT * FROM modifier_effects WHERE modifier_uuid = ? ORDER BY id ASC",
                [$uuid], ORV_SQL_FETCH_ALL
            ) ?: [];
        }

        public function addModifierEffect(string $uuid, string $nom, string $description): array {
            $nom = trim($nom);
            if ($nom === '') return ['success' => false, 'message' => 'Le nom de l\'effet est requis.'];
            $this->makeSQLRequest(
                "INSERT INTO modifier_effects (modifier_uuid, nom, description) VALUES (?, ?, ?)",
                [$uuid, $nom, trim($description) ?: null], ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => "Effet « {$nom} » ajouté."];
        }

        public function removeModifierEffect(int $id, string $uuid): void {
            $this->makeSQLRequest(
                "DELETE FROM modifier_effects WHERE id = ? AND modifier_uuid = ?",
                [$id, $uuid], ORV_SQL_NOTHING
            );
        }

        // ── Entités liées ─────────────────────────────────────────────────────────

        public function getAvailableHistories(): array {
            try {
                return $this->makeSQLRequest(
                    "SELECT uuid, titre FROM histories ORDER BY titre ASC",
                    [], ORV_SQL_FETCH_ALL
                ) ?: [];
            } catch (PDOException $e) { return []; }
        }
    }
