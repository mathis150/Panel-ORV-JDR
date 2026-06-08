<?php
    require_once './import/characters.php';
    $cm         = new CharactersManager();
    $characters = $cm->getCharacters();
?>

<?php if ($notice ?? ''): ?>
<div class="user-notice" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-check"></i> <?php echo $notice; ?>
</div>
<?php endif; ?>

<div class="orv-buttons" style="justify-content:flex-end;">
    <button class="orv-edit" onclick="window.location.href='jdr-params?page=characters&sub-page=create';">
        <i class="fa-solid fa-plus"></i>
        <span style="font-size:18px;">Créer un personnage</span>
    </button>
</div>

<div class="orv-menu">
    <div class="orv-menu_header">&lt;Liste des Personnages&gt;</div>
    <div class="orv-menu_container" style="padding:0;">
        <?php if (empty($characters)): ?>
        <div style="padding:40px; text-align:center; color:rgba(137,206,255,0.4); font-size:13px;">
            <i class="fa-solid fa-user-slash" style="font-size:24px; display:block; margin-bottom:10px;"></i>
            Aucun personnage créé pour l'instant.
        </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <td>Personnage</td>
                    <td>Titre affiché</td>
                    <td>Race / Métier</td>
                    <td>Joueur</td>
                    <td>Statut</td>
                    <td width="80px">Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($characters as $c): ?>
                <tr class="<?php echo $c['is_active'] ? '' : 'user-row--inactive'; ?>">
                    <td>
                        <strong><?php echo htmlspecialchars($c['prenom'] . ' ' . $c['nom']); ?></strong>
                    </td>
                    <td style="font-style:italic; color:rgba(137,206,255,0.6); font-size:12px;">
                        <?php echo $c['titre_affiche'] ? htmlspecialchars($c['titre_affiche']) : '—'; ?>
                    </td>
                    <td style="font-size:12px;">
                        <?php
                            $parts = array_filter([
                                htmlspecialchars($c['race']   ?? ''),
                                htmlspecialchars($c['metier'] ?? ''),
                            ]);
                            echo $parts ? implode(' · ', $parts) : '—';
                        ?>
                    </td>
                    <td style="font-size:12px;">
                        <?php
                            if ($c['joueur_pseudo']) {
                                $jName = $c['joueur_display_name'] ?: $c['joueur_pseudo'];
                                echo htmlspecialchars($jName);
                            } else {
                                echo '<span style="opacity:0.4;">—</span>';
                            }
                        ?>
                    </td>
                    <td>
                        <?php if ($c['is_active']): ?>
                        <span class="user-status-badge user-status-badge--active">Actif</span>
                        <?php else: ?>
                        <span class="user-status-badge user-status-badge--inactive">Inactif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="jdr-params?page=characters&sub-page=modify&uuid=<?php echo urlencode($c['uuid']); ?>"
                           style="background:#FFF; font-size:22px; background-clip:text !important; -webkit-background-clip:text !important; margin-right:8px;">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a href="jdr-params?page=characters&sub-page=delete&uuid=<?php echo urlencode($c['uuid']); ?>"
                           style="background:#FFF; font-size:22px; background-clip:text !important; -webkit-background-clip:text !important;">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
