<?php

    require_once __DIR__ . '/database.php';

    class ItemsManager extends Database {

        // ── Types d'objet ─────────────────────────────────────────────────────────

        public const TYPES = [
            'equipement', 'arme', 'objet', 'consommable',
            'objet_cle', 'materiaux', 'livre_competence',
        ];

        public const TYPE_LABELS = [
            'equipement'       => 'Équipement',
            'arme'             => 'Arme',
            'objet'            => 'Objet',
            'consommable'      => 'Consommable',
            'objet_cle'        => 'Objet clé',
            'materiaux'        => 'Matériaux',
            'livre_competence' => 'Livre de compétence',
        ];

        public const TYPE_COLORS = [
            'equipement'       => '#42A5F5',
            'arme'             => '#EF5350',
            'objet'            => '#26C6DA',
            'consommable'      => '#66BB6A',
            'objet_cle'        => '#FFD700',
            'materiaux'        => '#FFA726',
            'livre_competence' => '#AB47BC',
        ];

        public const TYPE_ICONS = [
            'equipement'       => 'fa-shield-halved',
            'arme'             => 'fa-crosshairs',
            'objet'            => 'fa-box-open',
            'consommable'      => 'fa-flask',
            'objet_cle'        => 'fa-key',
            'materiaux'        => 'fa-gem',
            'livre_competence' => 'fa-book-open',
        ];

        // ── Rangs ─────────────────────────────────────────────────────────────────

        public const RANGS = [
            'F', 'E', 'D', 'C', 'B', 'A', 'S', 'SS', 'SSS',
            'legendaire', 'artefact_etoile', 'mythique',
        ];

        public const RANG_LABELS = [
            'F'               => 'F',
            'E'               => 'E',
            'D'               => 'D',
            'C'               => 'C',
            'B'               => 'B',
            'A'               => 'A',
            'S'               => 'S',
            'SS'              => 'SS',
            'SSS'             => 'SSS',
            'legendaire'      => 'Légendaire',
            'artefact_etoile' => "Artéfact d'étoile",
            'mythique'        => 'Mythique',
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

        // ── Statistiques ──────────────────────────────────────────────────────────

        public const STAT_TYPES = ['force', 'vitalite', 'agilite', 'mana'];

        public const STAT_LABELS = [
            'force'    => 'Force',
            'vitalite' => 'Vitalité',
            'agilite'  => 'Agilité',
            'mana'     => 'Mana',
        ];

        public const STAT_COLORS = [
            'force'    => '#EF5350',
            'vitalite' => '#EC407A',
            'agilite'  => '#42A5F5',
            'mana'     => '#AB47BC',
        ];

        public const STAT_ICONS = [
            'force'    => 'fa-hand-fist',
            'vitalite' => 'fa-heart',
            'agilite'  => 'fa-wind',
            'mana'     => 'fa-fire-flame-curved',
        ];

        public function __construct() { parent::__construct(); }

        // ── CRUD Objets ───────────────────────────────────────────────────────────

        public function createItem(array $d): array {
            $nom  = trim($d['nom'] ?? '');
            $type = $d['type'] ?? 'objet';
            $rang = $d['rang'] ?? 'F';
            if ($nom === '')                      return ['success' => false, 'message' => 'Le nom est requis.'];
            if (!in_array($type, self::TYPES))    return ['success' => false, 'message' => 'Type invalide.'];
            if (!in_array($rang, self::RANGS))    return ['success' => false, 'message' => 'Rang invalide.'];
            $uuid = $this->generateCustomUUID();
            $this->makeSQLRequest(
                "INSERT INTO items (uuid, nom, type, rang, description, is_active) VALUES (?,?,?,?,?,?)",
                [$uuid, $nom, $type, $rang, trim($d['description'] ?? '') ?: null, isset($d['is_active']) ? 1 : 0],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'uuid' => $uuid, 'message' => 'Objet « ' . $nom . ' » créé avec succès.'];
        }

        public function getItems(string $search = ''): array {
            $rangOrder = implode(',', array_map(fn($r) => "'$r'", self::RANGS));
            $sql = "SELECT i.*,
                           (SELECT COUNT(*) FROM item_stats WHERE item_uuid = i.uuid) AS stats_count
                    FROM items i";
            $params = [];
            if ($search !== '') {
                $sql .= " WHERE i.nom LIKE ?";
                $params[] = '%' . $search . '%';
            }
            $sql .= " ORDER BY FIELD(i.rang, $rangOrder), i.nom ASC";
            return $this->makeSQLRequest($sql, $params, ORV_SQL_FETCH_ALL) ?: [];
        }

        public function getItemByUUID(string $uuid): ?array {
            return $this->makeSQLRequest(
                "SELECT * FROM items WHERE uuid = ?",
                [$uuid], ORV_SQL_FETCH_ONE
            ) ?: null;
        }

        public function updateItem(string $uuid, array $d): void {
            $nom  = trim($d['nom'] ?? '');
            $type = in_array($d['type'] ?? '', self::TYPES) ? $d['type'] : 'objet';
            $rang = in_array($d['rang'] ?? '', self::RANGS) ? $d['rang'] : 'F';
            $this->makeSQLRequest(
                "UPDATE items SET nom=?, type=?, rang=?, description=?, is_active=?, updated_at=NOW() WHERE uuid=?",
                [$nom ?: 'Sans nom', $type, $rang, trim($d['description'] ?? '') ?: null, isset($d['is_active']) ? 1 : 0, $uuid],
                ORV_SQL_NOTHING
            );
        }

        public function deleteItem(string $uuid): void {
            $this->makeSQLRequest("DELETE FROM items WHERE uuid=?", [$uuid], ORV_SQL_NOTHING);
        }

        // ── Statistiques de l'objet ───────────────────────────────────────────────

        public function getItemStats(string $uuid): array {
            return $this->makeSQLRequest(
                "SELECT * FROM item_stats WHERE item_uuid = ? ORDER BY FIELD(stat_type,'force','vitalite','agilite','mana'), id",
                [$uuid], ORV_SQL_FETCH_ALL
            ) ?: [];
        }

        public function addItemStat(string $uuid, string $statType, int $valeur): void {
            $this->makeSQLRequest(
                "INSERT INTO item_stats (item_uuid, stat_type, valeur) VALUES (?,?,?)",
                [$uuid, $statType, $valeur], ORV_SQL_NOTHING
            );
        }

        public function removeItemStat(int $id): void {
            $this->makeSQLRequest("DELETE FROM item_stats WHERE id=?", [$id], ORV_SQL_NOTHING);
        }

        // ── Données du tableau de bord ────────────────────────────────────────────

        public function getDashboardStats(): array {
            $total   = $this->makeSQLRequest("SELECT COUNT(*) AS n FROM items",                           [], ORV_SQL_FETCH_ONE)['n'] ?? 0;
            $actifs  = $this->makeSQLRequest("SELECT COUNT(*) AS n FROM items WHERE is_active = 1",      [], ORV_SQL_FETCH_ONE)['n'] ?? 0;
            $avStats = $this->makeSQLRequest("SELECT COUNT(DISTINCT item_uuid) AS n FROM item_stats",    [], ORV_SQL_FETCH_ONE)['n'] ?? 0;
            $types   = $this->makeSQLRequest("SELECT COUNT(DISTINCT type) AS n FROM items",              [], ORV_SQL_FETCH_ONE)['n'] ?? 0;
            return compact('total', 'actifs', 'avStats', 'types');
        }
    }
