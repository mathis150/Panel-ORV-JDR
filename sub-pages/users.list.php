<?php
    require_once './import/users.php';
    $um    = new UsersManager();
    $users = $um->getUsers();

    $roleLabels = [
        'sudo'   => ['label' => 'Super-Admin', 'class' => 'user-role-badge--sudo',  'icon' => 'fa-crown'],
        'admin'  => ['label' => 'Admin / MJ',  'class' => 'user-role-badge--admin', 'icon' => 'fa-shield-halved'],
        'player' => ['label' => 'Joueur',       'class' => 'user-role-badge--player','icon' => 'fa-user'],
    ];

    function formatActivity(?int $ts): string {
        if (!$ts) return '<span style="color:#4A5A7A;">—</span>';
        $diff = time() - $ts;
        if ($diff < 60)        return 'À l\'instant';
        if ($diff < 3600)      return 'Il y a ' . floor($diff / 60) . ' min';
        if ($diff < 86400)     return 'Il y a ' . floor($diff / 3600) . ' h';
        if ($diff < 86400 * 7) return 'Il y a ' . floor($diff / 86400) . ' j';
        return date('d/m/Y', $ts);
    }
?>

<div class="orv-buttons" style="justify-content: flex-end;">
    <button class="orv-edit" onclick="window.location.href='jdr-params?page=users&sub-page=create';">
        <i class="fa-solid fa-user-plus"></i>
        <span style="font-size: 18px;">Créer un utilisateur</span>
    </button>
</div>

<?php if ($notice): ?>
    <div class="user-notice"><i class="fa-solid fa-circle-check"></i> <?php echo $notice; ?></div>
<?php endif; ?>

<div class="orv-menu">
    <div class="orv-menu_header">&lt;Gestion des Utilisateurs&gt;</div>
    <div class="orv-menu_container" style="padding: 0;">
        <table>
            <thead>
                <tr>
                    <td>Utilisateur</td>
                    <td>E-Mail</td>
                    <td>Rôle</td>
                    <td>Statut</td>
                    <td>Dernière activité</td>
                    <td width="100px">Actions</td>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($users)): ?>
                <tr><td colspan="6" style="text-align:center; color:#4A5A7A; padding: 32px;">Aucun utilisateur enregistré.</td></tr>
            <?php else: ?>
                <?php foreach ($users as $user):
                    $role       = $roleLabels[$user['role']] ?? $roleLabels['player'];
                    $dName      = $user['display_name'] ?: $user['pseudonyme'];
                    $isSameName = $dName === $user['pseudonyme'];
                ?>
                <tr class="<?php echo !$user['is_active'] ? 'user-row--inactive' : ''; ?>">
                    <td>
                        <div style="display:flex; flex-direction:column; gap:2px;">
                            <span style="font-weight:600; color:var(--c-panel-text);">
                                <?php echo htmlspecialchars($dName); ?>
                            </span>
                            <?php if (!$isSameName): ?>
                                <span style="font-size:11px; color:#4A5A7A;">@<?php echo htmlspecialchars($user['pseudonyme']); ?></span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td style="color:#8899BB;"><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <span class="user-role-badge <?php echo $role['class']; ?>">
                            <i class="fa-solid <?php echo $role['icon']; ?>"></i>
                            <?php echo $role['label']; ?>
                        </span>
                    </td>
                    <td>
                        <?php if (!$user['is_active']): ?>
                            <span class="user-status-badge user-status-badge--inactive"><i class="fa-solid fa-ban"></i> Inactif</span>
                        <?php elseif ($user['must_change_password']): ?>
                            <span class="user-status-badge user-status-badge--temp"><i class="fa-solid fa-key"></i> Mdp temporaire</span>
                        <?php else: ?>
                            <span class="user-status-badge user-status-badge--active"><i class="fa-solid fa-circle-check"></i> Actif</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:12px; color:#6B7A9A;">
                        <?php echo formatActivity($user['last_activity']); ?>
                    </td>
                    <td>
                        <a href="jdr-params?page=users&sub-page=modify&uuid=<?php echo rawurlencode($user['uuid']); ?>"
                           title="Modifier" style="text-decoration:none; font-size:20px; color:var(--c-accent); margin-right:8px;">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a href="jdr-params?page=users&sub-page=delete&uuid=<?php echo rawurlencode($user['uuid']); ?>"
                           title="Supprimer" style="text-decoration:none; font-size:20px; color:#FF6B7A;">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
