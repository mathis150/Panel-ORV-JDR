<?php

    require_once __DIR__ . '/database.php';

    class HistoriesManager extends Database {

        public const RANGS = [
            'Historique bas-rang',
            'Historique Haut-rang',
            'Légendaire bas-rang',
            'Légendaire Haut-rang',
            'Quasi-mythique',
            'Mythique',
            'Histoire Gigantesque',
            'Unique',
            '???',
        ];

        public const RANG_COLORS = [
            'Historique bas-rang'  => '#78909C',
            'Historique Haut-rang' => '#4CAF50',
            'Légendaire bas-rang'  => '#42A5F5',
            'Légendaire Haut-rang' => '#AB47BC',
            'Quasi-mythique'       => '#FFA726',
            'Mythique'             => '#EF5350',
            'Histoire Gigantesque' => '#FFD700',
            'Unique'               => '#FF4ECD',
            '???'                  => '#C0C0C0',
        ];

        public function __construct() {
            parent::__construct();
        }

        // ── Création ──────────────────────────────────────────────────────────────

        public function createHistory(array $d): array {
            $titre = trim($d['titre'] ?? '');
            if ($titre === '') return ['success' => false, 'message' => 'Le titre de l\'histoire est requis.'];
            $rang  = in_array($d['rang'] ?? '', self::RANGS) ? $d['rang'] : 'Historique bas-rang';
            $uuid  = $this->generateCustomUUID();
            $statut        = ($d['statut'] ?? 'complete') === 'brisee' ? 'brisee' : 'complete';
            $completionPct = $statut === 'brisee' ? max(1, min(99, (int)($d['completion_pct'] ?? 50))) : 100;
            $ownershipPct  = $rang === 'Histoire Gigantesque' ? max(1, min(100, (int)($d['ownership_pct'] ?? 100))) : 100;
            $this->makeSQLRequest(
                "INSERT INTO histories
                    (uuid, titre, rang, description, statut, completion_pct, is_perso, is_fondatrice, ownership_pct, niveau_actuel)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $uuid, $titre, $rang,
                    trim($d['description'] ?? '') ?: null,
                    $statut, $completionPct,
                    !empty($d['is_perso'])      ? 1 : 0,
                    !empty($d['is_fondatrice']) ? 1 : 0,
                    $ownershipPct,
                    max(1, min(10, (int)($d['niveau_actuel'] ?? 1))),
                ],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'uuid' => $uuid, 'message' => 'Histoire créée.'];
        }

        // ── Lecture ───────────────────────────────────────────────────────────────

        public function getHistories(): array {
            return $this->makeSQLRequest(
                "SELECT uuid, titre, rang, statut, completion_pct, is_perso, is_fondatrice,
                        ownership_pct, niveau_actuel, is_active, created_at
                 FROM histories
                 ORDER BY FIELD(rang,
                    'Historique bas-rang','Historique Haut-rang',
                    'Légendaire bas-rang','Légendaire Haut-rang',
                    'Quasi-mythique','Mythique','Histoire Gigantesque','Unique','???'
                 ), titre ASC",
                [], ORV_SQL_FETCH_ALL
            ) ?: [];
        }

        public function getHistoryByUUID(string $uuid): ?array {
            return $this->makeSQLRequest(
                "SELECT * FROM histories WHERE uuid = ?",
                [$uuid], ORV_SQL_FETCH_ONE
            ) ?: null;
        }

        // ── Mise à jour ───────────────────────────────────────────────────────────

        public function updateHistory(string $uuid, array $d): array {
            $titre = trim($d['titre'] ?? '');
            if ($titre === '') return ['success' => false, 'message' => 'Le titre de l\'histoire est requis.'];
            $rang          = in_array($d['rang'] ?? '', self::RANGS) ? $d['rang'] : 'Historique bas-rang';
            $statut        = ($d['statut'] ?? 'complete') === 'brisee' ? 'brisee' : 'complete';
            $completionPct = $statut === 'brisee' ? max(1, min(99, (int)($d['completion_pct'] ?? 50))) : 100;
            $ownershipPct  = $rang === 'Histoire Gigantesque' ? max(1, min(100, (int)($d['ownership_pct'] ?? 100))) : 100;
            $this->makeSQLRequest(
                "UPDATE histories SET
                    titre = ?, rang = ?, description = ?, statut = ?,
                    completion_pct = ?, is_perso = ?, is_fondatrice = ?,
                    ownership_pct = ?, niveau_actuel = ?, is_active = ?
                 WHERE uuid = ?",
                [
                    $titre, $rang,
                    trim($d['description'] ?? '') ?: null,
                    $statut, $completionPct,
                    !empty($d['is_perso'])      ? 1 : 0,
                    !empty($d['is_fondatrice']) ? 1 : 0,
                    $ownershipPct,
                    max(1, min(10, (int)($d['niveau_actuel'] ?? 1))),
                    !empty($d['is_active']) ? 1 : 0,
                    $uuid,
                ],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Histoire mise à jour.'];
        }

        // ── Suppression ───────────────────────────────────────────────────────────

        public function deleteHistory(string $uuid): void {
            $this->makeSQLRequest("DELETE FROM history_level_stigmata WHERE history_uuid = ?", [$uuid], ORV_SQL_NOTHING);
            $this->makeSQLRequest("DELETE FROM history_level_stats     WHERE history_uuid = ?", [$uuid], ORV_SQL_NOTHING);
            $this->makeSQLRequest("DELETE FROM histories               WHERE uuid = ?",         [$uuid], ORV_SQL_NOTHING);
        }

        // ── Niveaux & Stigmates ───────────────────────────────────────────────────

        public function getLevelStigmata(string $uuid): array {
            $byLevel = [];
            for ($i = 1; $i <= 10; $i++) $byLevel[$i] = [];
            try {
                $rows = $this->makeSQLRequest(
                    "SELECT hls.id, hls.niveau, hls.stigma_uuid, s.nom AS stigma_nom, s.rang AS stigma_rang
                     FROM history_level_stigmata hls
                     JOIN stigmata s ON s.uuid = hls.stigma_uuid
                     WHERE hls.history_uuid = ?
                     ORDER BY hls.niveau ASC, s.nom ASC",
                    [$uuid], ORV_SQL_FETCH_ALL
                ) ?: [];
                foreach ($rows as $row) {
                    $lvl = (int)$row['niveau'];
                    if ($lvl >= 1 && $lvl <= 10) $byLevel[$lvl][] = $row;
                }
            } catch (PDOException $e) {}
            return $byLevel;
        }

        public function addLevelStigma(string $uuid, int $niveau, string $stigmaUuid): array {
            if ($niveau < 1 || $niveau > 10) return ['success' => false, 'message' => 'Niveau invalide (1-10).'];
            if (empty($stigmaUuid)) return ['success' => false, 'message' => 'Stigmate invalide.'];
            try {
                $this->makeSQLRequest(
                    "INSERT INTO history_level_stigmata (history_uuid, niveau, stigma_uuid) VALUES (?, ?, ?)",
                    [$uuid, $niveau, $stigmaUuid], ORV_SQL_NOTHING
                );
                return ['success' => true, 'message' => 'Stigmate ajouté au niveau ' . $niveau . '.'];
            } catch (PDOException $e) {
                return ['success' => false, 'message' => 'Ce stigmate est déjà associé à ce niveau.'];
            }
        }

        public function removeLevelStigma(int $id, string $uuid): void {
            $this->makeSQLRequest(
                "DELETE FROM history_level_stigmata WHERE id = ? AND history_uuid = ?",
                [$id, $uuid], ORV_SQL_NOTHING
            );
        }

        // ── Niveaux & Stats ───────────────────────────────────────────────────────

        public const STAT_TYPES = [
            'force'    => ['label' => 'Force',    'icon' => 'fa-dumbbell',          'color' => '#E74C3C'],
            'agilite'  => ['label' => 'Agilité',  'icon' => 'fa-bolt',              'color' => '#F1C40F'],
            'mana'     => ['label' => 'Mana',     'icon' => 'fa-fire-flame-curved', 'color' => '#3498DB'],
            'vitalite' => ['label' => 'Vitalité', 'icon' => 'fa-heart',             'color' => '#E91E63'],
            'coins'    => ['label' => 'Coins',    'icon' => 'fa-coins',             'color' => '#F39C12'],
        ];

        public function getLevelStats(string $uuid): array {
            $byLevel = [];
            for ($i = 1; $i <= 10; $i++) $byLevel[$i] = [];
            $rows = $this->makeSQLRequest(
                "SELECT * FROM history_level_stats WHERE history_uuid = ? ORDER BY niveau ASC, id ASC",
                [$uuid], ORV_SQL_FETCH_ALL
            ) ?: [];
            foreach ($rows as $row) {
                $lvl = (int)$row['niveau'];
                if ($lvl >= 1 && $lvl <= 10) $byLevel[$lvl][] = $row;
            }
            return $byLevel;
        }

        public function addLevelStat(string $uuid, int $niveau, string $statType, int $valeur): array {
            if ($niveau < 1 || $niveau > 10) return ['success' => false, 'message' => 'Niveau invalide (1-10).'];
            if (!isset(self::STAT_TYPES[$statType])) return ['success' => false, 'message' => 'Type de statistique invalide.'];
            if ($valeur === 0) return ['success' => false, 'message' => 'La valeur ne peut pas être 0.'];
            $this->makeSQLRequest(
                "INSERT INTO history_level_stats (history_uuid, niveau, stat_type, valeur) VALUES (?, ?, ?, ?)",
                [$uuid, $niveau, $statType, $valeur], ORV_SQL_NOTHING
            );
            $label = self::STAT_TYPES[$statType]['label'];
            $sign  = $valeur > 0 ? '+' : '';
            return ['success' => true, 'message' => "{$sign}{$valeur} {$label} ajouté au niveau {$niveau}."];
        }

        public function removeLevelStat(int $id, string $uuid): void {
            $this->makeSQLRequest(
                "DELETE FROM history_level_stats WHERE id = ? AND history_uuid = ?",
                [$id, $uuid], ORV_SQL_NOTHING
            );
        }

        // ── Entités liées ─────────────────────────────────────────────────────────

        public function getAvailableStigmata(): array {
            try {
                return $this->makeSQLRequest(
                    "SELECT uuid, nom, rang FROM stigmata WHERE is_active = 1 ORDER BY nom ASC",
                    [], ORV_SQL_FETCH_ALL
                ) ?: [];
            } catch (PDOException $e) { return []; }
        }
    }
