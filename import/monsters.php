<?php

    require_once __DIR__ . '/database.php';

    class MonstersManager extends Database {

        public function __construct() {
            parent::__construct();
        }

        // ── Création ──────────────────────────────────────────────────────────────

        public function createMonster(array $d): array {
            if (empty(trim($d['nom'] ?? ''))) {
                return ['success' => false, 'message' => 'Le nom du monstre est requis.'];
            }
            $rank = (int)($d['rank'] ?? 9);
            if ($rank < 1 || $rank > 9) $rank = 9;

            $uuid = $this->generateCustomUUID();
            $this->makeSQLRequest(
                "INSERT INTO monsters
                    (uuid, nom, race, `rank`, description, vitalite, `force`, agilite, mana, notes)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $uuid,
                    trim($d['nom'] ?? ''),
                    trim($d['race'] ?? '') ?: null,
                    $rank,
                    trim($d['description'] ?? '') ?: null,
                    (int)($d['vitalite'] ?? 100),
                    (int)($d['force']    ?? 0),
                    (int)($d['agilite']  ?? 0),
                    (int)($d['mana']     ?? 0),
                    trim($d['notes'] ?? '') ?: null,
                ],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'uuid' => $uuid, 'message' => 'Monstre créé.'];
        }

        // ── Lecture ───────────────────────────────────────────────────────────────

        public function getMonsters(?string $raceFilter = null): array {
            if ($raceFilter !== null && $raceFilter !== '') {
                return $this->makeSQLRequest(
                    "SELECT * FROM monsters WHERE race = ? ORDER BY `rank` ASC, nom ASC",
                    [$raceFilter], ORV_SQL_FETCH_ALL
                ) ?: [];
            }
            return $this->makeSQLRequest(
                "SELECT * FROM monsters ORDER BY `rank` ASC, nom ASC",
                [], ORV_SQL_FETCH_ALL
            ) ?: [];
        }

        public function getDistinctRaces(): array {
            $stmt = $this->makeSQLRequest(
                "SELECT DISTINCT race FROM monsters WHERE race IS NOT NULL AND race != '' ORDER BY race ASC",
                [], ORV_SQL_REQUEST
            );
            return $stmt ? $stmt->fetchAll(PDO::FETCH_COLUMN) : [];
        }

        public function getMonsterByUUID(string $uuid): ?array {
            return $this->makeSQLRequest(
                "SELECT * FROM monsters WHERE uuid = ?",
                [$uuid], ORV_SQL_FETCH_ONE
            ) ?: null;
        }

        public function getMonsterEquipped(string $uuid): array {
            try {
                return $this->makeSQLRequest(
                    "SELECT me.id, me.item_uuid, me.slot, me.quantity, i.nom AS entity_nom
                     FROM monster_equipped me
                     LEFT JOIN items i ON i.uuid = me.item_uuid
                     WHERE me.monster_uuid = ? ORDER BY me.slot ASC, me.id ASC",
                    [$uuid], ORV_SQL_FETCH_ALL
                ) ?: [];
            } catch (PDOException $e) {
                return [];
            }
        }

        // ── Mise à jour ───────────────────────────────────────────────────────────

        public function updateMonster(string $uuid, array $d): array {
            if (empty(trim($d['nom'] ?? ''))) {
                return ['success' => false, 'message' => 'Le nom du monstre est requis.'];
            }
            $rank = (int)($d['rank'] ?? 9);
            if ($rank < 1 || $rank > 9) $rank = 9;

            $this->makeSQLRequest(
                "UPDATE monsters SET
                    nom = ?, race = ?, `rank` = ?, description = ?,
                    vitalite = ?, `force` = ?, agilite = ?, mana = ?,
                    notes = ?, is_active = ?
                 WHERE uuid = ?",
                [
                    trim($d['nom'] ?? ''),
                    trim($d['race'] ?? '') ?: null,
                    $rank,
                    trim($d['description'] ?? '') ?: null,
                    (int)($d['vitalite'] ?? 100),
                    (int)($d['force']    ?? 0),
                    (int)($d['agilite']  ?? 0),
                    (int)($d['mana']     ?? 0),
                    trim($d['notes'] ?? '') ?: null,
                    !empty($d['is_active']) ? 1 : 0,
                    $uuid,
                ],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Monstre mis à jour.'];
        }

        // ── Suppression ───────────────────────────────────────────────────────────

        public function deleteMonster(string $uuid): void {
            $this->makeSQLRequest(
                "DELETE FROM monster_equipped WHERE monster_uuid = ?",
                [$uuid], ORV_SQL_NOTHING
            );
            $this->makeSQLRequest(
                "DELETE FROM monsters WHERE uuid = ?",
                [$uuid], ORV_SQL_NOTHING
            );
        }

        // ── Équipement ────────────────────────────────────────────────────────────

        public function addEquipped(string $monsterUuid, string $itemUuid, string $slot, int $quantity = 1): array {
            if (empty($itemUuid)) return ['success' => false, 'message' => 'Objet requis.'];
            $this->makeSQLRequest(
                "INSERT INTO monster_equipped (monster_uuid, item_uuid, slot, quantity) VALUES (?, ?, ?, ?)",
                [$monsterUuid, $itemUuid, trim($slot) ?: null, max(1, $quantity)],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Élément équipé.'];
        }

        public function updateEquippedQuantity(int $id, string $monsterUuid, int $quantity): void {
            if ($quantity <= 0) {
                $this->makeSQLRequest(
                    "DELETE FROM monster_equipped WHERE id = ? AND monster_uuid = ?",
                    [$id, $monsterUuid], ORV_SQL_NOTHING
                );
            } else {
                $this->makeSQLRequest(
                    "UPDATE monster_equipped SET quantity = ? WHERE id = ? AND monster_uuid = ?",
                    [$quantity, $id, $monsterUuid], ORV_SQL_NOTHING
                );
            }
        }

        public function removeEquipped(int $id, string $monsterUuid): void {
            $this->makeSQLRequest(
                "DELETE FROM monster_equipped WHERE id = ? AND monster_uuid = ?",
                [$id, $monsterUuid], ORV_SQL_NOTHING
            );
        }

        // ── Listes d'entités liées ────────────────────────────────────────────────

        public function getAvailableItems(): array {
            try {
                return $this->makeSQLRequest("SELECT uuid, nom FROM items ORDER BY nom ASC", [], ORV_SQL_FETCH_ALL) ?: [];
            } catch (PDOException $e) {
                return [];
            }
        }
    }
