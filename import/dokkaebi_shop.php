<?php

    require_once __DIR__ . '/database.php';

    class DokkaebiShopManager extends Database {

        // ── Objets (catalogue) — miroir de ItemsManager ───────────────────────────

        public const ITEM_TYPES = [
            'equipement', 'arme', 'objet', 'consommable',
            'objet_cle', 'materiaux', 'livre_competence',
        ];

        public const ITEM_TYPE_LABELS = [
            'equipement'       => 'Équipement',
            'arme'             => 'Arme',
            'objet'            => 'Objet',
            'consommable'      => 'Consommable',
            'objet_cle'        => 'Objet clé',
            'materiaux'        => 'Matériaux',
            'livre_competence' => 'Livre de compétence',
        ];

        public const ITEM_TYPE_COLORS = [
            'equipement'       => '#42A5F5',
            'arme'             => '#EF5350',
            'objet'            => '#26C6DA',
            'consommable'      => '#66BB6A',
            'objet_cle'        => '#FFD700',
            'materiaux'        => '#FFA726',
            'livre_competence' => '#AB47BC',
        ];

        public const ITEM_TYPE_ICONS = [
            'equipement'       => 'fa-shield-halved',
            'arme'             => 'fa-crosshairs',
            'objet'            => 'fa-box-open',
            'consommable'      => 'fa-flask',
            'objet_cle'        => 'fa-key',
            'materiaux'        => 'fa-gem',
            'livre_competence' => 'fa-book-open',
        ];

        public const RANGS = [
            'F', 'E', 'D', 'C', 'B', 'A', 'S', 'SS', 'SSS',
            'legendaire', 'artefact_etoile', 'mythique',
        ];

        public const RANG_COLORS = [
            'F'               => '#9E9E9E',
            'E'               => '#78909C',
            'D'               => '#27AE60',
            'C'               => '#2980B9',
            'B'               => '#8E44AD',
            'A'               => '#E67E22',
            'S'               => '#E74C3C',
            'SS'              => '#C0392B',
            'SSS'             => '#FFD700',
            'legendaire'      => '#FF6D00',
            'artefact_etoile' => '#FF4081',
            'mythique'        => '#AA00FF',
        ];

        // ── Shop ─────────────────────────────────────────────────────────────────

        public const RANGS_MIN = ['iron', 'gold', 'platinium', 'diamond'];

        public const RANG_MIN_LABELS = [
            'iron'      => 'Iron',
            'gold'      => 'Gold',
            'platinium' => 'Platinium',
            'diamond'   => 'Diamond',
        ];

        public const RANG_MIN_COLORS = [
            'iron'      => '#9E9E9E',
            'gold'      => '#FFD700',
            'platinium' => '#00BCD4',
            'diamond'   => '#E91E63',
        ];

        public const RANG_MIN_ICONS = [
            'iron'      => 'fa-shield',
            'gold'      => 'fa-star',
            'platinium' => 'fa-gem',
            'diamond'   => 'fa-diamond',
        ];

        public function __construct() {
            parent::__construct();
        }

        // ── Lecture : shop listings ───────────────────────────────────────────────

        public function getShopListings(string $search = ''): array {
            $sql = "SELECT dsl.id, dsl.item_uuid, dsl.prix, dsl.quantite, dsl.vendeur,
                           dsl.rang_min, dsl.is_vedette, dsl.is_actif, dsl.created_at,
                           i.nom, i.type AS item_type, i.rang AS item_rang, i.description AS item_description
                    FROM dokkaebi_shop_listings dsl
                    JOIN items i ON i.uuid = dsl.item_uuid";
            $params = [];
            if ($search !== '') {
                $sql .= " WHERE i.nom LIKE ?";
                $params[] = '%' . $search . '%';
            }
            $sql .= " ORDER BY dsl.is_vedette DESC, i.nom ASC";
            return $this->makeSQLRequest($sql, $params, ORV_SQL_FETCH_ALL) ?: [];
        }

        public function getShopListingById(int $id): ?array {
            return $this->makeSQLRequest(
                "SELECT dsl.id, dsl.item_uuid, dsl.prix, dsl.quantite, dsl.vendeur,
                        dsl.rang_min, dsl.is_vedette, dsl.is_actif,
                        i.nom, i.type AS item_type, i.rang AS item_rang, i.description AS item_description
                 FROM dokkaebi_shop_listings dsl
                 JOIN items i ON i.uuid = dsl.item_uuid
                 WHERE dsl.id = ?",
                [$id], ORV_SQL_FETCH_ONE
            ) ?: null;
        }

        // ── Ajout au shop ─────────────────────────────────────────────────────────

        public function addToShop(string $itemUuid, array $d): array {
            if (empty($itemUuid)) return ['success' => false, 'message' => 'Objet invalide.'];
            $prix     = max(0, (int)($d['prix'] ?? 0));
            $quantite = !empty($d['illimitee']) ? null : max(1, (int)($d['quantite'] ?? 1));
            $vendeur  = trim($d['vendeur'] ?? '') ?: null;
            $rangMin  = in_array($d['rang_min'] ?? '', self::RANGS_MIN) ? $d['rang_min'] : 'iron';
            try {
                $this->makeSQLRequest(
                    "INSERT INTO dokkaebi_shop_listings
                        (item_uuid, prix, quantite, vendeur, rang_min, is_vedette, is_actif)
                     VALUES (?, ?, ?, ?, ?, ?, 1)",
                    [$itemUuid, $prix, $quantite, $vendeur, $rangMin, !empty($d['is_vedette']) ? 1 : 0],
                    ORV_SQL_NOTHING
                );
                return ['success' => true, 'message' => 'Objet ajouté au shop.'];
            } catch (PDOException $e) {
                return ['success' => false, 'message' => 'Cet objet est déjà dans le shop.'];
            }
        }

        // ── Mise à jour ───────────────────────────────────────────────────────────

        public function updateShopListing(int $id, array $d): array {
            $prix     = max(0, (int)($d['prix'] ?? 0));
            $quantite = !empty($d['illimitee']) ? null : max(1, (int)($d['quantite'] ?? 1));
            $vendeur  = trim($d['vendeur'] ?? '') ?: null;
            $rangMin  = in_array($d['rang_min'] ?? '', self::RANGS_MIN) ? $d['rang_min'] : 'iron';
            $this->makeSQLRequest(
                "UPDATE dokkaebi_shop_listings
                 SET prix = ?, quantite = ?, vendeur = ?, rang_min = ?, is_vedette = ?, is_actif = ?
                 WHERE id = ?",
                [$prix, $quantite, $vendeur, $rangMin, !empty($d['is_vedette']) ? 1 : 0, !empty($d['is_actif']) ? 1 : 0, $id],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Mise en vente mise à jour.'];
        }

        // ── Suppression ───────────────────────────────────────────────────────────

        public function removeFromShop(int $id): void {
            $this->makeSQLRequest("DELETE FROM dokkaebi_shop_listings WHERE id = ?", [$id], ORV_SQL_NOTHING);
        }

        // ── Toggles ───────────────────────────────────────────────────────────────

        public function toggleVedette(int $id): void {
            $this->makeSQLRequest(
                "UPDATE dokkaebi_shop_listings SET is_vedette = 1 - is_vedette WHERE id = ?",
                [$id], ORV_SQL_NOTHING
            );
        }

        public function toggleActif(int $id): void {
            $this->makeSQLRequest(
                "UPDATE dokkaebi_shop_listings SET is_actif = 1 - is_actif WHERE id = ?",
                [$id], ORV_SQL_NOTHING
            );
        }

        // ── Catalogue d'objets ────────────────────────────────────────────────────

        public function getAllItems(string $search = ''): array {
            $sql = "SELECT i.uuid, i.nom, i.type, i.rang, i.is_active,
                           dsl.id AS shop_id, dsl.prix, dsl.quantite,
                           dsl.rang_min, dsl.is_vedette, dsl.is_actif AS shop_actif
                    FROM items i
                    LEFT JOIN dokkaebi_shop_listings dsl ON dsl.item_uuid = i.uuid";
            $params = [];
            if ($search !== '') {
                $sql .= " WHERE i.nom LIKE ?";
                $params[] = '%' . $search . '%';
            }
            $sql .= " ORDER BY i.nom ASC";
            return $this->makeSQLRequest($sql, $params, ORV_SQL_FETCH_ALL) ?: [];
        }

        public function getItemsNotInShop(string $search = ''): array {
            $sql = "SELECT i.uuid, i.nom, i.type, i.rang, i.description
                    FROM items i
                    WHERE i.uuid NOT IN (SELECT item_uuid FROM dokkaebi_shop_listings)";
            $params = [];
            if ($search !== '') {
                $sql .= " AND i.nom LIKE ?";
                $params[] = '%' . $search . '%';
            }
            $sql .= " ORDER BY i.nom ASC";
            return $this->makeSQLRequest($sql, $params, ORV_SQL_FETCH_ALL) ?: [];
        }

        public function getItemByUUID(string $uuid): ?array {
            return $this->makeSQLRequest(
                "SELECT * FROM items WHERE uuid = ?",
                [$uuid], ORV_SQL_FETCH_ONE
            ) ?: null;
        }

        // ── Stats dashboard ───────────────────────────────────────────────────────

        public function getShopStats(): array {
            $total    = $this->makeSQLRequest("SELECT COUNT(*) AS n FROM dokkaebi_shop_listings", [], ORV_SQL_FETCH_ONE)['n'] ?? 0;
            $vedettes = $this->makeSQLRequest("SELECT COUNT(*) AS n FROM dokkaebi_shop_listings WHERE is_vedette = 1", [], ORV_SQL_FETCH_ONE)['n'] ?? 0;
            $inactifs = $this->makeSQLRequest("SELECT COUNT(*) AS n FROM dokkaebi_shop_listings WHERE is_actif = 0", [], ORV_SQL_FETCH_ONE)['n'] ?? 0;
            $items    = $this->makeSQLRequest("SELECT COUNT(*) AS n FROM items", [], ORV_SQL_FETCH_ONE)['n'] ?? 0;
            return compact('total', 'vedettes', 'inactifs', 'items');
        }
    }
