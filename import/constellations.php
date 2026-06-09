<?php

    require_once __DIR__ . '/database.php';

    class ConstellationsManager extends Database {

        public function __construct() {
            parent::__construct();
        }

        // ── Création ──────────────────────────────────────────────────────────────

        public function createConstellation(array $d): array {
            if (empty(trim($d['nom'] ?? ''))) {
                return ['success' => false, 'message' => 'Le nom de la constellation est requis.'];
            }
            $uuid = $this->generateCustomUUID();
            $this->makeSQLRequest(
                "INSERT INTO constellations
                    (uuid, nom, identite, provenance, rang, race, nebuleuse,
                     psyche, vertu, vice, vitalite, `force`, agilite, mana, coins,
                     histoire, estimation_generale)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $uuid,
                    trim($d['nom']        ?? ''),
                    trim($d['identite']   ?? '') ?: null,
                    trim($d['provenance'] ?? '') ?: null,
                    $d['rang']            ?? 'historique_bas',
                    trim($d['race']       ?? '') ?: null,
                    trim($d['nebuleuse']  ?? '') ?: null,
                    trim($d['psyche']     ?? '') ?: null,
                    trim($d['vertu']      ?? '') ?: null,
                    trim($d['vice']       ?? '') ?: null,
                    (int)($d['vitalite']  ?? 100),
                    (int)($d['force']     ?? 0),
                    (int)($d['agilite']   ?? 0),
                    (int)($d['mana']      ?? 0),
                    (int)($d['coins']     ?? 0),
                    trim($d['histoire']           ?? '') ?: null,
                    trim($d['estimation_generale'] ?? '') ?: null,
                ],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'uuid' => $uuid, 'message' => 'Constellation créée.'];
        }

        // ── Lecture ───────────────────────────────────────────────────────────────

        public function getConstellations(): array {
            return $this->makeSQLRequest(
                "SELECT uuid, nom, identite, rang, nebuleuse, race, is_active, created_at
                 FROM constellations ORDER BY nom ASC",
                [], ORV_SQL_FETCH_ALL
            ) ?: [];
        }

        public function getConstellationByUUID(string $uuid): ?array {
            return $this->makeSQLRequest(
                "SELECT * FROM constellations WHERE uuid = ?",
                [$uuid], ORV_SQL_FETCH_ONE
            ) ?: null;
        }

        public function getConstellationRelations(string $uuid): array {
            $titles = $this->makeSQLRequest(
                "SELECT * FROM constellation_titles WHERE constellation_uuid = ? ORDER BY is_displayed DESC, id ASC",
                [$uuid], ORV_SQL_FETCH_ALL
            ) ?: [];

            try {
                $attributes = $this->makeSQLRequest(
                    "SELECT ca.id, ca.attribute_uuid, ca.is_locked, a.nom AS entity_nom
                     FROM constellation_attributes ca
                     LEFT JOIN attributes a ON a.uuid = ca.attribute_uuid
                     WHERE ca.constellation_uuid = ? ORDER BY ca.id ASC",
                    [$uuid], ORV_SQL_FETCH_ALL
                ) ?: [];
            } catch (PDOException $e) { $attributes = []; }

            try {
                $skills = $this->makeSQLRequest(
                    "SELECT cs.id, cs.skill_uuid, cs.is_locked, c.nom AS entity_nom
                     FROM constellation_skills cs
                     LEFT JOIN capacities c ON c.uuid = cs.skill_uuid
                     WHERE cs.constellation_uuid = ? ORDER BY cs.id ASC",
                    [$uuid], ORV_SQL_FETCH_ALL
                ) ?: [];
            } catch (PDOException $e) { $skills = []; }

            try {
                $stigmata = $this->makeSQLRequest(
                    "SELECT cst.id, cst.stigma_uuid, cst.is_locked, s.nom AS entity_nom
                     FROM constellation_stigmata cst
                     LEFT JOIN stigmata s ON s.uuid = cst.stigma_uuid
                     WHERE cst.constellation_uuid = ? ORDER BY cst.id ASC",
                    [$uuid], ORV_SQL_FETCH_ALL
                ) ?: [];
            } catch (PDOException $e) { $stigmata = []; }

            return compact('titles', 'attributes', 'skills', 'stigmata');
        }

        // ── Mise à jour ───────────────────────────────────────────────────────────

        public function updateConstellation(string $uuid, array $d): array {
            if (empty(trim($d['nom'] ?? ''))) {
                return ['success' => false, 'message' => 'Le nom de la constellation est requis.'];
            }
            $this->makeSQLRequest(
                "UPDATE constellations SET
                    nom = ?, identite = ?, provenance = ?, rang = ?, race = ?, nebuleuse = ?,
                    psyche = ?, vertu = ?, vice = ?,
                    vitalite = ?, `force` = ?, agilite = ?, mana = ?, coins = ?,
                    histoire = ?, estimation_generale = ?, is_active = ?
                 WHERE uuid = ?",
                [
                    trim($d['nom']        ?? ''),
                    trim($d['identite']   ?? '') ?: null,
                    trim($d['provenance'] ?? '') ?: null,
                    $d['rang']            ?? 'historique_bas',
                    trim($d['race']       ?? '') ?: null,
                    trim($d['nebuleuse']  ?? '') ?: null,
                    trim($d['psyche']     ?? '') ?: null,
                    trim($d['vertu']      ?? '') ?: null,
                    trim($d['vice']       ?? '') ?: null,
                    (int)($d['vitalite']  ?? 100),
                    (int)($d['force']     ?? 0),
                    (int)($d['agilite']   ?? 0),
                    (int)($d['mana']      ?? 0),
                    (int)($d['coins']     ?? 0),
                    trim($d['histoire']           ?? '') ?: null,
                    trim($d['estimation_generale'] ?? '') ?: null,
                    !empty($d['is_active']) ? 1 : 0,
                    $uuid,
                ],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Constellation mise à jour.'];
        }

        // ── Suppression ───────────────────────────────────────────────────────────

        public function deleteConstellation(string $uuid): void {
            foreach ([
                'constellation_stigmata', 'constellation_skills',
                'constellation_attributes', 'constellation_titles',
            ] as $table) {
                $this->makeSQLRequest(
                    "DELETE FROM `{$table}` WHERE constellation_uuid = ?",
                    [$uuid], ORV_SQL_NOTHING
                );
            }
            $this->makeSQLRequest(
                "DELETE FROM constellations WHERE uuid = ?",
                [$uuid], ORV_SQL_NOTHING
            );
        }

        // ── Titres ────────────────────────────────────────────────────────────────

        public function addTitle(string $cUuid, string $title): array {
            $title = trim($title);
            if ($title === '') return ['success' => false, 'message' => 'Le titre ne peut pas être vide.'];
            $this->makeSQLRequest(
                "INSERT INTO constellation_titles (constellation_uuid, title) VALUES (?, ?)",
                [$cUuid, $title], ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Titre ajouté.'];
        }

        public function setDisplayedTitle(string $cUuid, int $titleId): void {
            $this->makeSQLRequest(
                "UPDATE constellation_titles SET is_displayed = 0 WHERE constellation_uuid = ?",
                [$cUuid], ORV_SQL_NOTHING
            );
            $this->makeSQLRequest(
                "UPDATE constellation_titles SET is_displayed = 1 WHERE id = ? AND constellation_uuid = ?",
                [$titleId, $cUuid], ORV_SQL_NOTHING
            );
        }

        public function deleteTitle(int $titleId, string $cUuid): void {
            $this->makeSQLRequest(
                "DELETE FROM constellation_titles WHERE id = ? AND constellation_uuid = ?",
                [$titleId, $cUuid], ORV_SQL_NOTHING
            );
        }

        // ── Attributs ─────────────────────────────────────────────────────────────

        public function addAttribute(string $cUuid, string $attributeUuid): array {
            if (empty($attributeUuid)) return ['success' => false, 'message' => 'Attribut requis.'];
            $this->makeSQLRequest(
                "INSERT IGNORE INTO constellation_attributes (constellation_uuid, attribute_uuid) VALUES (?, ?)",
                [$cUuid, $attributeUuid], ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Attribut ajouté.'];
        }

        public function toggleAttributeLock(int $id, string $cUuid): void {
            $this->makeSQLRequest(
                "UPDATE constellation_attributes SET is_locked = 1 - is_locked WHERE id = ? AND constellation_uuid = ?",
                [$id, $cUuid], ORV_SQL_NOTHING
            );
        }

        public function removeAttribute(int $id, string $cUuid): void {
            $this->makeSQLRequest(
                "DELETE FROM constellation_attributes WHERE id = ? AND constellation_uuid = ?",
                [$id, $cUuid], ORV_SQL_NOTHING
            );
        }

        // ── Compétences ───────────────────────────────────────────────────────────

        public function addSkill(string $cUuid, string $skillUuid): array {
            if (empty($skillUuid)) return ['success' => false, 'message' => 'Compétence requise.'];
            $this->makeSQLRequest(
                "INSERT IGNORE INTO constellation_skills (constellation_uuid, skill_uuid) VALUES (?, ?)",
                [$cUuid, $skillUuid], ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Compétence ajoutée.'];
        }

        public function toggleSkillLock(int $id, string $cUuid): void {
            $this->makeSQLRequest(
                "UPDATE constellation_skills SET is_locked = 1 - is_locked WHERE id = ? AND constellation_uuid = ?",
                [$id, $cUuid], ORV_SQL_NOTHING
            );
        }

        public function removeSkill(int $id, string $cUuid): void {
            $this->makeSQLRequest(
                "DELETE FROM constellation_skills WHERE id = ? AND constellation_uuid = ?",
                [$id, $cUuid], ORV_SQL_NOTHING
            );
        }

        // ── Stigmates ─────────────────────────────────────────────────────────────

        public function addStigma(string $cUuid, string $stigmaUuid): array {
            if (empty($stigmaUuid)) return ['success' => false, 'message' => 'Stigmate requis.'];
            $this->makeSQLRequest(
                "INSERT IGNORE INTO constellation_stigmata (constellation_uuid, stigma_uuid) VALUES (?, ?)",
                [$cUuid, $stigmaUuid], ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Stigmate ajouté.'];
        }

        public function toggleStigmaLock(int $id, string $cUuid): void {
            $this->makeSQLRequest(
                "UPDATE constellation_stigmata SET is_locked = 1 - is_locked WHERE id = ? AND constellation_uuid = ?",
                [$id, $cUuid], ORV_SQL_NOTHING
            );
        }

        public function removeStigma(int $id, string $cUuid): void {
            $this->makeSQLRequest(
                "DELETE FROM constellation_stigmata WHERE id = ? AND constellation_uuid = ?",
                [$id, $cUuid], ORV_SQL_NOTHING
            );
        }

        // ── Entités disponibles ───────────────────────────────────────────────────

        public function getAvailableAttributes(): array {
            try {
                return $this->makeSQLRequest("SELECT uuid, nom FROM attributes ORDER BY nom ASC", [], ORV_SQL_FETCH_ALL) ?: [];
            } catch (PDOException $e) { return []; }
        }

        public function getAvailableSkills(): array {
            try {
                return $this->makeSQLRequest("SELECT uuid, nom FROM capacities ORDER BY nom ASC", [], ORV_SQL_FETCH_ALL) ?: [];
            } catch (PDOException $e) { return []; }
        }

        public function getAvailableStigmata(): array {
            try {
                return $this->makeSQLRequest("SELECT uuid, nom FROM stigmata ORDER BY nom ASC", [], ORV_SQL_FETCH_ALL) ?: [];
            } catch (PDOException $e) { return []; }
        }
    }
