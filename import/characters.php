<?php

    require_once __DIR__ . '/database.php';

    class CharactersManager extends Database {

        // ── Formules de stats ─────────────────────────────────────────────────────

        public const HP_PER_VITALITE    = 4;
        public const MP_PER_MANA        = 4;
        public const STAT_COST_PER_POINT = 100; // conservé pour compatibilité

        // Coût pour passer du niveau $n au niveau $n+1
        public static function statLevelCost(int $n): int {
            if ($n < 100) {
                return 300 + 100 * intdiv($n, 10);
            }
            return (int)round(1200 * pow(1.03, $n - 99));
        }

        public static function calcHpMax(int $vitalite): int {
            return $vitalite * self::HP_PER_VITALITE;
        }
        public static function calcMpMax(int $mana): int {
            return $mana * self::MP_PER_MANA;
        }
        // Déplacement en mètres : agilité × 2
        public static function calcMovement(int $agilite): int {
            return $agilite * 2;
        }
        // Saut avec élan en mètres : (force + agilité) / 3
        public static function calcJumpRunning(int $force, int $agilite): float {
            return round(($force + $agilite) / 3, 1);
        }
        // Saut sans élan en mètres : (force + agilité) / 4
        public static function calcJumpStanding(int $force, int $agilite): float {
            return round(($force + $agilite) / 4, 1);
        }
        // Hauteur de saut en centimètres : (force + agilité) × 10
        public static function calcJumpHeight(int $force, int $agilite): int {
            return ($force + $agilite) * 10;
        }

        public function __construct() {
            parent::__construct();
        }

        // ── Création ──────────────────────────────────────────────────────────────

        public function createCharacter(array $d): array {
            if (empty(trim($d['nom'] ?? ''))) {
                return ['success' => false, 'message' => 'Le nom du personnage est requis.'];
            }
            $uuid = $this->generateCustomUUID();
            $vitalite = (int)($d['vitalite'] ?? 100);
            $mana     = (int)($d['mana']     ?? 0);
            $this->makeSQLRequest(
                "INSERT INTO characters
                    (uuid, user_uuid, nom, prenom, race, nationalite, metier,
                     constellation_sponsor_uuid, psyche, vertu, vice,
                     apparence_description, apparence_image, histoire,
                     estimation_generale, coins, vitalite, `force`, agilite, mana,
                     hp_actuel, mp_actuel)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
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
                    $vitalite,
                    (int)($d['force']    ?? 0),
                    (int)($d['agilite']  ?? 0),
                    $mana,
                    $vitalite * self::HP_PER_VITALITE,
                    $mana     * self::MP_PER_MANA,
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
                "SELECT ci.id, ci.item_uuid, ci.quantity,
                        i.nom AS entity_nom, i.type AS entity_type,
                        i.rang AS entity_rang, i.description AS entity_desc
                 FROM character_inventory ci
                 LEFT JOIN items i ON i.uuid = ci.item_uuid
                 WHERE ci.character_uuid = ? ORDER BY ci.id ASC",
                [$uuid]
            );
            $equipped = $this->safeQuery(
                "SELECT ce.id, ce.item_uuid, ce.slot,
                        i.nom AS entity_nom, i.type AS entity_type,
                        i.rang AS entity_rang, i.description AS entity_desc
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

        // ── Lecture joueur ────────────────────────────────────────────────────────

        // Personnages d'un joueur (keyed by uuid via ORV_SQL_FETCH_ALL)
        public function getCharactersForUser(string $userUuid): array {
            return $this->makeSQLRequest(
                "SELECT c.uuid, c.nom, c.prenom, c.vitalite, c.`force`, c.agilite, c.mana,
                        c.hp_actuel, c.mp_actuel, c.coins, c.is_active,
                        ct.title AS titre_affiche
                 FROM characters c
                 LEFT JOIN character_titles ct ON ct.character_uuid = c.uuid AND ct.is_displayed = 1
                 WHERE c.user_uuid = ?
                 ORDER BY c.is_active DESC, c.nom ASC",
                [$userUuid], ORV_SQL_FETCH_ALL
            ) ?: [];
        }

        // Fiche complète avec constellation et titre affiché
        public function getCharacterFull(string $charUuid): ?array {
            return $this->makeSQLRequest(
                "SELECT c.*, con.nom AS constellation_nom, ct.title AS titre_affiche
                 FROM characters c
                 LEFT JOIN constellations con ON con.uuid = c.constellation_sponsor_uuid
                 LEFT JOIN character_titles ct ON ct.character_uuid = c.uuid AND ct.is_displayed = 1
                 WHERE c.uuid = ?",
                [$charUuid], ORV_SQL_FETCH_ONE
            ) ?: null;
        }

        // ── Actions joueur ────────────────────────────────────────────────────────

        // Ajuster HP et MP courants (clampé entre 0 et max)
        public function adjustHpMp(
            string $charUuid, string $userUuid,
            int $addHp, int $removeHp,
            int $addMp, int $removeMp
        ): array {
            $char = $this->makeSQLRequest(
                "SELECT vitalite, mana, hp_actuel, mp_actuel
                 FROM characters WHERE uuid = ? AND user_uuid = ?",
                [$charUuid, $userUuid], ORV_SQL_FETCH_ONE
            );
            if (!$char) return ['success' => false, 'message' => 'Personnage introuvable.'];

            $hpMax = (int)$char['vitalite'] * self::HP_PER_VITALITE;
            $mpMax = (int)$char['mana']     * self::MP_PER_MANA;
            $newHp = max(0, min($hpMax, (int)$char['hp_actuel'] + $addHp - $removeHp));
            $newMp = max(0, min($mpMax, (int)$char['mp_actuel'] + $addMp - $removeMp));

            $this->makeSQLRequest(
                "UPDATE characters SET hp_actuel = ?, mp_actuel = ? WHERE uuid = ? AND user_uuid = ?",
                [$newHp, $newMp, $charUuid, $userUuid], ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => "PV/PM mis à jour ({$newHp}/{$hpMax} PV · {$newMp}/{$mpMax} PM)."];
        }

        // Améliorer des stats en dépensant des coins
        public function upgradeStats(string $charUuid, string $userUuid, array $increments): array {
            $char = $this->makeSQLRequest(
                "SELECT vitalite, `force`, agilite, mana, hp_actuel, mp_actuel, coins
                 FROM characters WHERE uuid = ? AND user_uuid = ?",
                [$charUuid, $userUuid], ORV_SQL_FETCH_ONE
            );
            if (!$char) return ['success' => false, 'message' => 'Personnage introuvable.'];

            $keys = ['vitalite', 'force', 'agilite', 'mana'];
            $inc  = [];
            foreach ($keys as $k) {
                $inc[$k] = max(0, (int)($increments[$k] ?? 0));
            }

            $totalCost = 0;
            foreach ($keys as $k) {
                $cur = (int)$char[$k];
                for ($i = 0; $i < $inc[$k]; $i++) {
                    $totalCost += self::statLevelCost($cur + $i);
                }
            }

            if ($totalCost === 0) return ['success' => false, 'message' => 'Aucune amélioration sélectionnée.'];
            if ($totalCost > (int)$char['coins']) {
                return ['success' => false, 'message' => "Coins insuffisants — coût : {$totalCost} C, disponible : {$char['coins']} C."];
            }

            $newVit  = (int)$char['vitalite'] + $inc['vitalite'];
            $newFrc  = (int)$char['force']    + $inc['force'];
            $newAgi  = (int)$char['agilite']  + $inc['agilite'];
            $newMna  = (int)$char['mana']     + $inc['mana'];
            $newCoins = (int)$char['coins'] - $totalCost;

            // Quand on monte la vitalité/mana, on gagne les PV/PM correspondants
            $newHpMax = $newVit * self::HP_PER_VITALITE;
            $newMpMax = $newMna * self::MP_PER_MANA;
            $newHp    = min($newHpMax, (int)$char['hp_actuel'] + $inc['vitalite'] * self::HP_PER_VITALITE);
            $newMp    = min($newMpMax, (int)$char['mp_actuel'] + $inc['mana']     * self::MP_PER_MANA);

            $this->makeSQLRequest(
                "UPDATE characters SET
                    vitalite = ?, `force` = ?, agilite = ?, mana = ?, coins = ?,
                    hp_actuel = ?, mp_actuel = ?
                 WHERE uuid = ? AND user_uuid = ?",
                [$newVit, $newFrc, $newAgi, $newMna, $newCoins, $newHp, $newMp, $charUuid, $userUuid],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => "Statistiques améliorées pour {$totalCost} C."];
        }

        public function upgradeShopRank(string $charUuid, string $userUuid): array {
            $char = $this->makeSQLRequest(
                "SELECT coins, shop_rang FROM characters WHERE uuid = ? AND user_uuid = ?",
                [$charUuid, $userUuid], ORV_SQL_FETCH_ONE
            );
            if (!$char) return ['success' => false, 'message' => 'Personnage introuvable.'];

            $current    = $char['shop_rang'] ?? 'iron';
            $rankPrices = ['iron' => 5000, 'gold' => 100000, 'platinium' => 250000];
            $rankNext   = ['iron' => 'gold', 'gold' => 'platinium', 'platinium' => 'diamond'];

            if (!isset($rankNext[$current])) {
                return ['success' => false, 'message' => 'Rang Diamond déjà atteint.'];
            }
            $price = $rankPrices[$current];
            if ((int)$char['coins'] < $price) {
                return ['success' => false, 'message' => "Coins insuffisants — coût : " . number_format($price, 0, ',', ' ') . " C."];
            }
            $next = $rankNext[$current];
            $this->makeSQLRequest(
                "UPDATE characters SET coins = coins - ?, shop_rang = ? WHERE uuid = ? AND user_uuid = ?",
                [$price, $next, $charUuid, $userUuid], ORV_SQL_NOTHING
            );
            $labels = ['gold' => 'Gold', 'platinium' => 'Platinium', 'diamond' => 'Diamond'];
            return ['success' => true, 'message' => "Rang amélioré en " . $labels[$next] . " pour " . number_format($price, 0, ',', ' ') . " C !"];
        }

        // Activer / désactiver l'accès au Baluchon du Dokkaebi (admin)
        public function setDokkaebiBag(string $charUuid, bool $value): void {
            $this->makeSQLRequest(
                "UPDATE characters SET has_dokkaebi_bag = ? WHERE uuid = ?",
                [$value ? 1 : 0, $charUuid], ORV_SQL_NOTHING
            );
        }

        // Achat d'un article du shop par le joueur
        public function buyFromShop(string $charUuid, string $userUuid, int $listingId, int $qty = 1): array {
            $qty  = max(1, $qty);
            $char = $this->makeSQLRequest(
                "SELECT coins, has_dokkaebi_bag FROM characters WHERE uuid = ? AND user_uuid = ?",
                [$charUuid, $userUuid], ORV_SQL_FETCH_ONE
            );
            if (!$char)                      return ['success' => false, 'message' => 'Personnage introuvable.'];
            if (!$char['has_dokkaebi_bag'])  return ['success' => false, 'message' => 'Accès au baluchon non activé.'];

            $listing = $this->makeSQLRequest(
                "SELECT id, item_uuid, prix, quantite FROM dokkaebi_shop_listings WHERE id = ? AND is_actif = 1",
                [$listingId], ORV_SQL_FETCH_ONE
            );
            if (!$listing) return ['success' => false, 'message' => 'Article introuvable ou indisponible.'];

            if ($listing['quantite'] !== null && (int)$listing['quantite'] < $qty) {
                return ['success' => false, 'message' => 'Stock insuffisant.'];
            }
            $totalPrice = (int)$listing['prix'] * $qty;
            if ($totalPrice > (int)$char['coins']) {
                return ['success' => false, 'message' => "Coins insuffisants — coût : {$totalPrice} C, disponible : {$char['coins']} C."];
            }

            $this->makeSQLRequest(
                "UPDATE characters SET coins = coins - ? WHERE uuid = ?",
                [$totalPrice, $charUuid], ORV_SQL_NOTHING
            );
            if ($listing['quantite'] !== null) {
                $this->makeSQLRequest(
                    "UPDATE dokkaebi_shop_listings SET quantite = quantite - ? WHERE id = ?",
                    [$qty, $listingId], ORV_SQL_NOTHING
                );
            }
            $this->makeSQLRequest(
                "INSERT INTO character_inventory (character_uuid, item_uuid, quantity) VALUES (?, ?, ?)",
                [$charUuid, $listing['item_uuid'], $qty], ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => "Achat effectué pour {$totalPrice} C."];
        }

        // Mise à jour des informations par le joueur (champs autorisés seulement)
        public function updatePlayerInfo(string $charUuid, string $userUuid, array $d): array {
            $nom = trim($d['nom'] ?? '');
            if ($nom === '') return ['success' => false, 'message' => 'Le nom du personnage est requis.'];

            $this->makeSQLRequest(
                "UPDATE characters SET
                    nom = ?, prenom = ?, nationalite = ?, metier = ?,
                    psyche = ?, vertu = ?, vice = ?,
                    apparence_description = ?, histoire = ?
                 WHERE uuid = ? AND user_uuid = ?",
                [
                    $nom,
                    trim($d['prenom']               ?? ''),
                    trim($d['nationalite']           ?? '') ?: null,
                    trim($d['metier']               ?? '') ?: null,
                    trim($d['psyche']               ?? '') ?: null,
                    trim($d['vertu']                ?? '') ?: null,
                    trim($d['vice']                 ?? '') ?: null,
                    trim($d['apparence_description'] ?? '') ?: null,
                    trim($d['histoire']             ?? '') ?: null,
                    $charUuid, $userUuid,
                ],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Fiche mise à jour.'];
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
