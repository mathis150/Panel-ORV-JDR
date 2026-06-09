<?php

    require_once __DIR__ . '/database.php';

    class StigmataManager extends Database {

        public const RANGS = ['E', 'D', 'C', 'B', 'A', 'S', 'SS', 'SSS'];

        public const ACTIVATION_CATEGORIES = [
            'stat' => [
                'label' => 'Statistique', 'icon' => 'fa-chart-line', 'color' => '#89CEFF',
                'duree_default' => 'tours',
                'valeur_label' => 'Valeur (positive = bonus, négative = malus)', 'valeur_placeholder' => 'ex: 10 ou -5',
                'sous_types' => [
                    'force'    => ['label' => 'Force',    'icon' => 'fa-dumbbell',          'color' => '#E74C3C'],
                    'agilite'  => ['label' => 'Agilité',  'icon' => 'fa-bolt',              'color' => '#F1C40F'],
                    'mana'     => ['label' => 'Mana',     'icon' => 'fa-fire-flame-curved', 'color' => '#3498DB'],
                    'vitalite' => ['label' => 'Vitalité', 'icon' => 'fa-heart',             'color' => '#E91E63'],
                    'coins'    => ['label' => 'Coins',    'icon' => 'fa-coins',             'color' => '#F39C12'],
                ],
            ],
            'degats' => [
                'label' => 'Dégâts', 'icon' => 'fa-burst', 'color' => '#E74C3C',
                'duree_default' => 'instantane',
                'valeur_label' => 'Formule de dégâts', 'valeur_placeholder' => 'ex: 2d6 + Force',
                'sous_types' => [
                    'physique' => ['label' => 'Physique', 'icon' => 'fa-shield-halved',       'color' => '#BDC3C7'],
                    'magique'  => ['label' => 'Magique',  'icon' => 'fa-wand-magic-sparkles', 'color' => '#9B59B6'],
                    'feu'      => ['label' => 'Feu',      'icon' => 'fa-fire',               'color' => '#E67E22'],
                    'glace'    => ['label' => 'Glace',    'icon' => 'fa-snowflake',          'color' => '#74B9FF'],
                    'foudre'   => ['label' => 'Foudre',   'icon' => 'fa-bolt',               'color' => '#F1C40F'],
                    'poison'   => ['label' => 'Poison',   'icon' => 'fa-biohazard',          'color' => '#27AE60'],
                    'ombre'    => ['label' => 'Ombre',    'icon' => 'fa-moon',               'color' => '#8E44AD'],
                    'sacre'    => ['label' => 'Sacré',    'icon' => 'fa-sun',                'color' => '#F9CA24'],
                ],
            ],
            'soin' => [
                'label' => 'Soin', 'icon' => 'fa-heart-pulse', 'color' => '#2ECC71',
                'duree_default' => 'instantane',
                'valeur_label' => 'Formule de soin', 'valeur_placeholder' => 'ex: 1d6 + Mana',
                'sous_types' => [
                    'pv'   => ['label' => 'Points de Vie', 'icon' => 'fa-heart',             'color' => '#E91E63'],
                    'mana' => ['label' => 'Mana',          'icon' => 'fa-fire-flame-curved', 'color' => '#3498DB'],
                ],
            ],
            'statut' => [
                'label' => 'Statut / DoT', 'icon' => 'fa-circle-radiation', 'color' => '#F39C12',
                'duree_default' => 'tours',
                'valeur_label' => 'Dégâts par tour (optionnel)', 'valeur_placeholder' => 'ex: 1d4 ou 5',
                'sous_types' => [
                    'brulure'         => ['label' => 'Brûlure',        'icon' => 'fa-fire',            'color' => '#E67E22'],
                    'poison'          => ['label' => 'Poison',         'icon' => 'fa-biohazard',       'color' => '#27AE60'],
                    'saignement'      => ['label' => 'Saignement',     'icon' => 'fa-droplet',         'color' => '#C0392B'],
                    'etourdissement'  => ['label' => 'Étourdissement', 'icon' => 'fa-face-dizzy',      'color' => '#F1C40F'],
                    'ralentissement'  => ['label' => 'Ralentissement', 'icon' => 'fa-person-falling',  'color' => '#95A5A6'],
                    'affaiblissement' => ['label' => 'Affaiblissement','icon' => 'fa-arrow-trend-down','color' => '#E74C3C'],
                    'silence'         => ['label' => 'Silence',        'icon' => 'fa-volume-xmark',    'color' => '#9B59B6'],
                    'malediction'     => ['label' => 'Malédiction',    'icon' => 'fa-skull-crossbones','color' => '#8E44AD'],
                ],
            ],
            'protection' => [
                'label' => 'Protection', 'icon' => 'fa-shield-halved', 'color' => '#1ABC9C',
                'duree_default' => 'tours',
                'valeur_label' => 'Valeur de protection', 'valeur_placeholder' => 'ex: 20 pts de bouclier',
                'sous_types' => [
                    'bouclier'        => ['label' => 'Bouclier',       'icon' => 'fa-shield',          'color' => '#1ABC9C'],
                    'resistance'      => ['label' => 'Résistance',     'icon' => 'fa-ban',             'color' => '#27AE60'],
                    'invulnerabilite' => ['label' => 'Invulnérabilité','icon' => 'fa-shield-halved',   'color' => '#2ECC71'],
                ],
            ],
            'controle' => [
                'label' => 'Contrôle', 'icon' => 'fa-lock', 'color' => '#9B59B6',
                'duree_default' => 'tours',
                'valeur_label' => 'Description du contrôle', 'valeur_placeholder' => 'ex: Immobilise 1 tour',
                'sous_types' => [
                    'immobilisation' => ['label' => 'Immobilisation', 'icon' => 'fa-lock',           'color' => '#9B59B6'],
                    'deplacement'    => ['label' => 'Déplacement',    'icon' => 'fa-person-running', 'color' => '#27AE60'],
                    'invocation'     => ['label' => 'Invocation',     'icon' => 'fa-ghost',          'color' => '#8E44AD'],
                ],
            ],
            'passif' => [
                'label' => 'Passif', 'icon' => 'fa-circle-dot', 'color' => '#7F8C8D',
                'duree_default' => 'passif',
                'valeur_label' => 'Description de l\'effet passif', 'valeur_placeholder' => 'ex: +10% de résistance physique',
                'sous_types' => [
                    'aura'           => ['label' => 'Aura',           'icon' => 'fa-circle-dot',         'color' => '#7F8C8D'],
                    'benediction'    => ['label' => 'Bénédiction',    'icon' => 'fa-star',               'color' => '#F1C40F'],
                    'transformation' => ['label' => 'Transformation', 'icon' => 'fa-person-burst',       'color' => '#E74C3C'],
                    'autre'          => ['label' => 'Autre',          'icon' => 'fa-wand-magic-sparkles','color' => '#95A5A6'],
                ],
            ],
        ];

        public function __construct() {
            parent::__construct();
        }

        // ── Création ──────────────────────────────────────────────────────────────

        public function createStigma(array $d): array {
            if (empty(trim($d['nom'] ?? ''))) {
                return ['success' => false, 'message' => 'Le nom du stigmate est requis.'];
            }
            $rang = in_array($d['rang'] ?? '', self::RANGS) ? $d['rang'] : 'E';
            $uuid = $this->generateCustomUUID();
            $this->makeSQLRequest(
                "INSERT INTO stigmata (uuid, nom, rang, description, effets_visuels, histoire_uuid)
                 VALUES (?, ?, ?, ?, ?, ?)",
                [
                    $uuid,
                    trim($d['nom'] ?? ''),
                    $rang,
                    trim($d['description']    ?? '') ?: null,
                    trim($d['effets_visuels'] ?? '') ?: null,
                    trim($d['histoire_uuid']  ?? '') ?: null,
                ],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'uuid' => $uuid, 'message' => 'Stigmate créé.'];
        }

        // ── Lecture ───────────────────────────────────────────────────────────────

        public function getStigmata(): array {
            try {
                return $this->makeSQLRequest(
                    "SELECT s.uuid, s.nom, s.rang, s.is_active, s.created_at,
                            h.titre AS histoire_titre
                     FROM stigmata s
                     LEFT JOIN histories h ON h.uuid = s.histoire_uuid
                     ORDER BY FIELD(s.rang,'E','D','C','B','A','S','SS','SSS'), s.nom ASC",
                    [], ORV_SQL_FETCH_ALL
                ) ?: [];
            } catch (PDOException $e) {
                return $this->makeSQLRequest(
                    "SELECT uuid, nom, rang, is_active, created_at, NULL AS histoire_titre
                     FROM stigmata
                     ORDER BY FIELD(rang,'E','D','C','B','A','S','SS','SSS'), nom ASC",
                    [], ORV_SQL_FETCH_ALL
                ) ?: [];
            }
        }

        public function getStigmaByUUID(string $uuid): ?array {
            return $this->makeSQLRequest(
                "SELECT * FROM stigmata WHERE uuid = ?",
                [$uuid], ORV_SQL_FETCH_ONE
            ) ?: null;
        }

        public function getStigmaLevelupConditions(string $uuid): array {
            $rows = $this->makeSQLRequest(
                "SELECT * FROM stigma_levelup_conditions WHERE stigma_uuid = ? ORDER BY niveau_cible ASC, id ASC",
                [$uuid], ORV_SQL_FETCH_ALL
            ) ?: [];
            $byLevel = [];
            for ($i = 2; $i <= 10; $i++) $byLevel[$i] = [];
            foreach ($rows as $row) {
                $lvl = (int)$row['niveau_cible'];
                if ($lvl >= 2 && $lvl <= 10) $byLevel[$lvl][] = $row;
            }
            return $byLevel;
        }

        // ── Mise à jour ───────────────────────────────────────────────────────────

        public function updateStigma(string $uuid, array $d): array {
            if (empty(trim($d['nom'] ?? ''))) {
                return ['success' => false, 'message' => 'Le nom du stigmate est requis.'];
            }
            $rang = in_array($d['rang'] ?? '', self::RANGS) ? $d['rang'] : 'E';
            $this->makeSQLRequest(
                "UPDATE stigmata SET
                    nom = ?, rang = ?, description = ?, effets_visuels = ?,
                    histoire_uuid = ?, is_active = ?
                 WHERE uuid = ?",
                [
                    trim($d['nom'] ?? ''),
                    $rang,
                    trim($d['description']    ?? '') ?: null,
                    trim($d['effets_visuels'] ?? '') ?: null,
                    trim($d['histoire_uuid']  ?? '') ?: null,
                    !empty($d['is_active']) ? 1 : 0,
                    $uuid,
                ],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Stigmate mis à jour.'];
        }

        // ── Suppression ───────────────────────────────────────────────────────────

        public function deleteStigma(string $uuid): void {
            $this->makeSQLRequest("DELETE FROM stigma_activation_effects WHERE stigma_uuid = ?", [$uuid], ORV_SQL_NOTHING);
            $this->makeSQLRequest("DELETE FROM stigma_levelup_conditions WHERE stigma_uuid = ?", [$uuid], ORV_SQL_NOTHING);
            $this->makeSQLRequest("DELETE FROM stigmata                  WHERE uuid = ?",        [$uuid], ORV_SQL_NOTHING);
        }

        // ── Effets (système unifié) ───────────────────────────────────────────────

        public function getEffects(string $uuid): array {
            return $this->makeSQLRequest(
                "SELECT * FROM stigma_activation_effects WHERE stigma_uuid = ? ORDER BY ordre ASC, id ASC",
                [$uuid], ORV_SQL_FETCH_ALL
            ) ?: [];
        }

        public function addEffect(string $uuid, string $nom, string $categorie, string $sousType, string $valeur, string $typeDuree, ?int $duree, string $description): array {
            $nom = trim($nom);
            if ($nom === '') return ['success' => false, 'message' => 'Le nom de l\'effet est requis.'];
            $cats = self::ACTIVATION_CATEGORIES;
            if (!isset($cats[$categorie])) return ['success' => false, 'message' => 'Catégorie invalide.'];
            if (!isset($cats[$categorie]['sous_types'][$sousType])) return ['success' => false, 'message' => 'Type invalide.'];
            $valeur    = trim($valeur);
            $typeDuree = in_array($typeDuree, ['instantane', 'tours', 'passif']) ? $typeDuree : 'tours';
            $ordre     = $this->makeSQLRequest(
                "SELECT COALESCE(MAX(ordre), 0) + 1 AS next_ordre FROM stigma_activation_effects WHERE stigma_uuid = ?",
                [$uuid], ORV_SQL_FETCH_ONE
            )['next_ordre'] ?? 1;
            $this->makeSQLRequest(
                "INSERT INTO stigma_activation_effects
                    (stigma_uuid, nom, categorie, sous_type, valeur, type_duree, duree, description, ordre)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [$uuid, $nom, $categorie, $sousType, $valeur, $typeDuree, $typeDuree === 'tours' ? $duree : null, trim($description) ?: null, (int)$ordre],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Effet ajouté.'];
        }

        public function deleteEffect(int $id, string $uuid): void {
            $this->makeSQLRequest(
                "DELETE FROM stigma_activation_effects WHERE id = ? AND stigma_uuid = ?",
                [$id, $uuid], ORV_SQL_NOTHING
            );
        }

        // ── Conditions de level-up ────────────────────────────────────────────────

        public function addLevelupCondition(string $stigmaUuid, int $niveauCible, string $type, string $description, ?int $valeur): array {
            if ($niveauCible < 2 || $niveauCible > 10) {
                return ['success' => false, 'message' => 'Niveau cible invalide (2-10).'];
            }
            $description = trim($description);
            if ($description === '') return ['success' => false, 'message' => 'La description de la condition est requise.'];
            $type = in_array($type, ['usages', 'personnalise']) ? $type : 'usages';
            $this->makeSQLRequest(
                "INSERT INTO stigma_levelup_conditions (stigma_uuid, niveau_cible, type, description, valeur) VALUES (?, ?, ?, ?, ?)",
                [$stigmaUuid, $niveauCible, $type, $description, $valeur],
                ORV_SQL_NOTHING
            );
            return ['success' => true, 'message' => 'Condition ajoutée.'];
        }

        public function deleteLevelupCondition(int $id, string $stigmaUuid): void {
            $this->makeSQLRequest(
                "DELETE FROM stigma_levelup_conditions WHERE id = ? AND stigma_uuid = ?",
                [$id, $stigmaUuid], ORV_SQL_NOTHING
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
