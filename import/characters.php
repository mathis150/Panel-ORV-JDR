<?php

    require_once __DIR__ . '/database.php';

    class CharactersManager extends Database {

        public function __construct() {
            parent::__construct();
        }

        // ── Création ──────────────────────────────────────────────────────────────

        public function createCharacter(array $d): array {
            if (empty(trim($d['nom'] ?? ''))) {
                return ['success' => false, 'message' => 'Le nom du personnage est requis.'];
            }
            $uuid = $this->generateCustomUUID();
            $this->makeSQLRequest(
                "INSERT INTO characters
                    (uuid, user_uuid, nom, prenom, race, nationalite, metier,
                     constellation_sponsor_uuid, psyche, vertu, vice,
                     apparence_description, apparence_image, histoire,
                     estimation_generale, coins, vitalite, `force`, agilite, mana)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $uuid,
                    $d['user_uuid'] ?: null,
                    trim($d['nom']   ?? ''),
                    trim($d['prenom'] ?? ''),
                    trim($d['race']        ?? '') ?: null,
                    trim($d['nationalite'] ?? '') ?: null,
                    trim($d['metier']      ?? '') ?: null,
                    $d['constellation_sponsor_uuid'] ?: null,
                    trim($d['psyche'] ?? '') ?: null,
                    trim($d['vertu']  ?? '') ?: null,
                    trim($d['vice']   ?? '') ?: null,
                    trim($d['apparence_description'] ?? '') ?: null,
                    $d['apparence_image'] ?: null,
                    trim($d['histoire']           ?? '') ?: null,
                    trim($d['estimation_generale'] ?? '') ?: null,
                    (int)($d['coins']    ?? 0),
                    (int)($d['vitalite'] ?? 100),
                    (int)($d['force']    ?? 0),
                    (int)($d['agilite']  ?? 0),
                    (int)($d['mana']     ?? 0),
                ],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'uuid' => $uuid, 'message' => 'Personnage créé.'];
        }

        // ── Lecture ───────────────────────────────────────────────────────────────

        public function getCharacters(): array {
            return $this->makeSQLRequest(
                "SELECT c.uuid, c.nom, c.prenom, c.race, c.metier, c.is_active, c.created_at,
                        u.pseudonyme   AS joueur_pseudo,
                        u.display_name AS joueur_display_name,
                        ct.title       AS titre_affiche
                 FROM characters c
                 LEFT JOIN users u ON u.uuid = c.user_uuid
                 LEFT JOIN character_titles ct ON ct.character_uuid = c.uuid AND ct.is_displayed = 1
                 ORDER BY c.created_at DESC",
                [], ORV_SQL_FETCH_ALL
            ) ?: [];
        }

        public function getCharacterByUUID(string $uuid): ?array {
            return $this->makeSQLRequest(
                "SELECT * FROM characters WHERE uuid = ?",
                [$uuid], ORV_SQL_FETCH_ONE
            ) ?: null;
        }

        public function getCharacterRelations(string $uuid): array {
            $titles = $this->makeSQLRequest(
                "SELECT * FROM character_titles WHERE character_uuid = ? ORDER BY is_displayed DESC, id ASC",
                [$uuid], ORV_SQL_FETCH_ALL
            ) ?: [];

            $attributes = $this->safeQuery(
                "SELECT ca.id, ca.attribute_uuid, ca.is_locked, a.nom AS entity_nom
                 FROM character_attributes ca
                 LEFT JOIN attributes a ON a.uuid = ca.attribute_uuid
                 WHERE ca.character_uuid = ? ORDER BY ca.id ASC",
                [$uuid]
            );
            $skills = $this->safeQuery(
                "SELECT cs.id, cs.skill_uuid, cs.is_locked, c.nom AS entity_nom
                 FROM character_skills cs
                 LEFT JOIN capacities c ON c.uuid = cs.skill_uuid
                 WHERE cs.character_uuid = ? ORDER BY cs.id ASC",
                [$uuid]
            );
            $stigmata = $this->safeQuery(
                "SELECT cst.id, cst.stigma_uuid, cst.is_locked, s.nom AS entity_nom
                 FROM character_stigmata cst
                 LEFT JOIN stigmata s ON s.uuid = cst.stigma_uuid
                 WHERE cst.character_uuid = ? ORDER BY cst.id ASC",
                [$uuid]
            );
            $inventory = $this->safeQuery(
                "SELECT ci.id, ci.item_uuid, ci.quantity, i.nom AS entity_nom
                 FROM character_inventory ci
                 LEFT JOIN items i ON i.uuid = ci.item_uuid
                 WHERE ci.character_uuid = ? ORDER BY ci.id ASC",
                [$uuid]
            );
            $equipped = $this->safeQuery(
                "SELECT ce.id, ce.item_uuid, ce.slot, i.nom AS entity_nom
                 FROM character_equipped ce
                 LEFT JOIN items i ON i.uuid = ce.item_uuid
                 WHERE ce.character_uuid = ? ORDER BY ce.slot ASC, ce.id ASC",
                [$uuid]
            );

            return compact('titles', 'attributes', 'skills', 'stigmata', 'inventory', 'equipped');
        }

        // ── Mise à jour ───────────────────────────────────────────────────────────

        public function updateCharacter(string $uuid, array $d): array {
            if (empty(trim($d['nom'] ?? ''))) {
                return ['success' => false, 'message' => 'Le nom du personnage est requis.'];
            }
            $this->makeSQLRequest(
                "UPDATE characters SET
                    user_uuid = ?, nom = ?, prenom = ?, race = ?, nationalite = ?, metier = ?,
                    constellation_sponsor_uuid = ?, psyche = ?, vertu = ?, vice = ?,
                    apparence_description = ?, apparence_image = ?, histoire = ?,
                    estimation_generale = ?, coins = ?, vitalite = ?, `force` = ?,
                    agilite = ?, mana = ?, is_active = ?
                 WHERE uuid = ?",
                [
                    $d['user_uuid'] ?: null,
                    trim($d['nom']   ?? ''),
                    trim($d['prenom'] ?? ''),
                    trim($d['race']        ?? '') ?: null,
                    trim($d['nationalite'] ?? '') ?: null,
                    trim($d['metier']      ?? '') ?: null,
                    $d['constellation_sponsor_uuid'] ?: null,
                    trim($d['psyche'] ?? '') ?: null,
                    trim($d['vertu']  ?? '') ?: null,
                    trim($d['vice']   ?? '') ?: null,
                    trim($d['apparence_description'] ?? '') ?: null,
                    $d['apparence_image'] ?: null,
                    trim($d['histoire']           ?? '') ?: null,
                    trim($d['estimation_generale'] ?? '') ?: null,
                    (int)($d['coins']    ?? 0),
                    (int)($d['vitalite'] ?? 100),
                    (int)($d['force']    ?? 0),
                    (int)($d['agilite']  ?? 0),
                    (int)($d['mana']     ?? 0),
                    !empty($d['is_active']) ? 1 : 0,
                    $uuid,
                ],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Personnage mis à jour.'];
        }

        // ── Suppression ───────────────────────────────────────────────────────────

        public function deleteCharacter(string $uuid): void {
            $char = $this->getCharacterByUUID($uuid);
            if ($char && !empty($char['apparence_image'])) {
                $imgPath = __DIR__ . '/../' . ltrim($char['apparence_image'], './');
                if (file_exists($imgPath)) unlink($imgPath);
            }
            foreach ([
                'character_equipped', 'character_inventory', 'character_stigmata',
                'character_skills', 'character_attributes', 'character_titles',
            ] as $table) {
                $this->makeSQLRequest("DELETE FROM `{$table}` WHERE character_uuid = ?", [$uuid], ORV_SQL_NOTHING);
            }
            $this->makeSQLRequest("DELETE FROM characters WHERE uuid = ?", [$uuid], ORV_SQL_NOTHING);
        }

        // ── Titres ────────────────────────────────────────────────────────────────

        public function addTitle(string $charUuid, string $title): array {
            $title = trim($title);
            if ($title === '') return ['success' => false, 'message' => 'Le titre ne peut pas être vide.'];
            $this->makeSQLRequest(
                "INSERT INTO character_titles (character_uuid, title) VALUES (?, ?)",
                [$charUuid, $title], ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Titre ajouté.'];
        }

        public function setDisplayedTitle(string $charUuid, int $titleId): void {
            $this->makeSQLRequest(
                "UPDATE character_titles SET is_displayed = 0 WHERE character_uuid = ?",
                [$charUuid], ORV_SQL_NOTHING
            );
            $this->makeSQLRequest(
                "UPDATE character_titles SET is_displayed = 1 WHERE id = ? AND character_uuid = ?",
                [$titleId, $charUuid], ORV_SQL_NOTHING
            );
        }

        public function deleteTitle(int $titleId, string $charUuid): void {
            $this->makeSQLRequest(
                "DELETE FROM character_titles WHERE id = ? AND character_uuid = ?",
                [$titleId, $charUuid], ORV_SQL_NOTHING
            );
        }

        // ── Attributs ─────────────────────────────────────────────────────────────

        public function addAttribute(string $charUuid, string $attributeUuid): array {
            if (empty($attributeUuid)) return ['success' => false, 'message' => 'Attribut requis.'];
            $this->makeSQLRequest(
                "INSERT IGNORE INTO character_attributes (character_uuid, attribute_uuid) VALUES (?, ?)",
                [$charUuid, $attributeUuid], ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Attribut ajouté.'];
        }

        public function toggleAttributeLock(int $id, string $charUuid): void {
            $this->makeSQLRequest(
                "UPDATE character_attributes SET is_locked = 1 - is_locked WHERE id = ? AND character_uuid = ?",
                [$id, $charUuid], ORV_SQL_NOTHING
            );
        }

        public function removeAttribute(int $id, string $charUuid): void {
            $this->makeSQLRequest(
                "DELETE FROM character_attributes WHERE id = ? AND character_uuid = ?",
                [$id, $charUuid], ORV_SQL_NOTHING
            );
        }

        // ── Compétences spéciales ─────────────────────────────────────────────────

        public function addSkill(string $charUuid, string $skillUuid): array {
            if (empty($skillUuid)) return ['success' => false, 'message' => 'Compétence requise.'];
            $this->makeSQLRequest(
                "INSERT IGNORE INTO character_skills (character_uuid, skill_uuid) VALUES (?, ?)",
                [$charUuid, $skillUuid], ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Compétence ajoutée.'];
        }

        public function toggleSkillLock(int $id, string $charUuid): void {
            $this->makeSQLRequest(
                "UPDATE character_skills SET is_locked = 1 - is_locked WHERE id = ? AND character_uuid = ?",
                [$id, $charUuid], ORV_SQL_NOTHING
            );
        }

        public function removeSkill(int $id, string $charUuid): void {
            $this->makeSQLRequest(
                "DELETE FROM character_skills WHERE id = ? AND character_uuid = ?",
                [$id, $charUuid], ORV_SQL_NOTHING
            );
        }

        // ── Stigmates ─────────────────────────────────────────────────────────────

        public function addStigma(string $charUuid, string $stigmaUuid): array {
            if (empty($stigmaUuid)) return ['success' => false, 'message' => 'Stigmate requis.'];
            $this->makeSQLRequest(
                "INSERT IGNORE INTO character_stigmata (character_uuid, stigma_uuid) VALUES (?, ?)",
                [$charUuid, $stigmaUuid], ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Stigmate ajouté.'];
        }

        public function toggleStigmaLock(int $id, string $charUuid): void {
            $this->makeSQLRequest(
                "UPDATE character_stigmata SET is_locked = 1 - is_locked WHERE id = ? AND character_uuid = ?",
                [$id, $charUuid], ORV_SQL_NOTHING
            );
        }

        public function removeStigma(int $id, string $charUuid): void {
            $this->makeSQLRequest(
                "DELETE FROM character_stigmata WHERE id = ? AND character_uuid = ?",
                [$id, $charUuid], ORV_SQL_NOTHING
            );
        }

        // ── Inventaire ────────────────────────────────────────────────────────────

        public function addInventoryItem(string $charUuid, string $itemUuid, int $quantity = 1): array {
            if (empty($itemUuid)) return ['success' => false, 'message' => 'Objet requis.'];
            $this->makeSQLRequest(
                "INSERT INTO character_inventory (character_uuid, item_uuid, quantity) VALUES (?, ?, ?)",
                [$charUuid, $itemUuid, max(1, $quantity)], ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => "Objet ajouté à l'inventaire."];
        }

        public function updateInventoryQuantity(int $id, string $charUuid, int $quantity): void {
            if ($quantity <= 0) {
                $this->makeSQLRequest(
                    "DELETE FROM character_inventory WHERE id = ? AND character_uuid = ?",
                    [$id, $charUuid], ORV_SQL_NOTHING
                );
            } else {
                $this->makeSQLRequest(
                    "UPDATE character_inventory SET quantity = ? WHERE id = ? AND character_uuid = ?",
                    [$quantity, $id, $charUuid], ORV_SQL_NOTHING
                );
            }
        }

        public function removeInventoryItem(int $id, string $charUuid): void {
            $this->makeSQLRequest(
                "DELETE FROM character_inventory WHERE id = ? AND character_uuid = ?",
                [$id, $charUuid], ORV_SQL_NOTHING
            );
        }

        // ── Équipement ────────────────────────────────────────────────────────────

        public function addEquipped(string $charUuid, string $itemUuid, string $slot): array {
            if (empty($itemUuid)) return ['success' => false, 'message' => 'Objet requis.'];
            $this->makeSQLRequest(
                "INSERT INTO character_equipped (character_uuid, item_uuid, slot) VALUES (?, ?, ?)",
                [$charUuid, $itemUuid, trim($slot) ?: null], ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Élément équipé.'];
        }

        public function removeEquipped(int $id, string $charUuid): void {
            $this->makeSQLRequest(
                "DELETE FROM character_equipped WHERE id = ? AND character_uuid = ?",
                [$id, $charUuid], ORV_SQL_NOTHING
            );
        }

        // ── Listes d'entités liées ────────────────────────────────────────────────

        public function getAvailableConstellations(): array {
            return $this->safeQuery("SELECT uuid, nom FROM constellations ORDER BY nom ASC", []);
        }

        public function getAvailableAttributes(): array {
            return $this->safeQuery("SELECT uuid, nom FROM attributes ORDER BY nom ASC", []);
        }

        public function getAvailableSkills(): array {
            return $this->safeQuery("SELECT uuid, nom FROM capacities ORDER BY nom ASC", []);
        }

        public function getAvailableStigmata(): array {
            return $this->safeQuery("SELECT uuid, nom FROM stigmata ORDER BY nom ASC", []);
        }

        public function getAvailableItems(): array {
            return $this->safeQuery("SELECT uuid, nom FROM items ORDER BY nom ASC", []);
        }

        public function getAvailableUsers(): array {
            return $this->makeSQLRequest(
                "SELECT uuid, pseudonyme, display_name FROM users WHERE is_active = 1 ORDER BY pseudonyme ASC",
                [], ORV_SQL_FETCH_ALL
            ) ?: [];
        }

        // ── Utilitaire ────────────────────────────────────────────────────────────

        private function safeQuery(string $sql, array $params): array {
            try {
                return $this->makeSQLRequest($sql, $params, ORV_SQL_FETCH_ALL) ?: [];
            } catch (\Exception $e) {
                return [];
            }
        }
    }
