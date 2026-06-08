<?php
    require_once './import/users.php';
    $um   = new UsersManager();
    $uuid = $_GET['uuid'] ?? '';
    $user = $um->getUserByUUID($uuid);

    if (!$user) {
        echo '<div class="user-notice user-notice--error"><i class="fa-solid fa-triangle-exclamation"></i> Utilisateur introuvable.</div>';
        return;
    }

    $dName = $user['display_name'] ?: $user['pseudonyme'];
?>

<div class="orv-buttons" style="justify-content: flex-end;">
    <button class="orv-edit" form="form-user-delete"
            style="background:rgba(255,107,122,0.12); border-color:rgba(255,107,122,0.35); color:#FF6B7A;">
        <i class="fa-solid fa-trash"></i>
        <span style="font-size: 18px;">Confirmer la suppression</span>
    </button>
    <button class="orv-edit" onclick="window.location.href='jdr-params?page=users&sub-page=list'">
        <i class="fa-solid fa-xmark"></i>
        <span style="font-size: 18px;">Annuler</span>
    </button>
</div>

<form id="form-user-delete" method="POST"
      action="jdr-params?page=users&sub-page=delete&uuid=<?php echo rawurlencode($uuid); ?>">
</form>

<div class="orv-menu">
    <div class="orv-menu_header">&lt;Suppression d'un utilisateur&gt;</div>
    <div class="orv-menu_container">
        <div class="orv-menu_form">
            <div>
                <h2 class="classic-title" style="color:#FF6B7A;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Attention — action irréversible
                </h2>
                <br>
                <p style="color:var(--c-panel-text); font-size:15px; line-height:1.7;">
                    Vous êtes sur le point de supprimer le compte de
                    <strong style="color:var(--c-accent);"><?php echo htmlspecialchars($dName); ?></strong>
                    (<?php echo htmlspecialchars($user['pseudonyme']); ?>).
                </p>
                <br>
                <cite style="font-size:14px; line-height:1.8;">
                    Cette action supprimera définitivement le compte, toutes ses sessions actives
                    et toutes les données qui lui sont associées.<br>
                    <strong style="color:#FF6B7A;">Cette opération est irréversible.</strong>
                </cite>
            </div>
        </div>

        <div style="margin-top:16px; display:flex; gap:16px; flex-wrap:wrap;">
            <div style="padding:12px 20px; background:rgba(6,16,42,0.6); border:1px solid var(--c-panel-border); border-radius:var(--r-md); min-width:180px;">
                <div style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:#4A5A7A; margin-bottom:4px;">Pseudonyme</div>
                <div style="color:var(--c-panel-text); font-weight:600;"><?php echo htmlspecialchars($user['pseudonyme']); ?></div>
            </div>
            <div style="padding:12px 20px; background:rgba(6,16,42,0.6); border:1px solid var(--c-panel-border); border-radius:var(--r-md); min-width:180px;">
                <div style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:#4A5A7A; margin-bottom:4px;">E-Mail</div>
                <div style="color:var(--c-panel-text);"><?php echo htmlspecialchars($user['email']); ?></div>
            </div>
            <div style="padding:12px 20px; background:rgba(6,16,42,0.6); border:1px solid var(--c-panel-border); border-radius:var(--r-md); min-width:160px;">
                <div style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:#4A5A7A; margin-bottom:4px;">Inscrit le</div>
                <div style="color:var(--c-panel-text);"><?php echo date('d/m/Y', strtotime($user['registered'])); ?></div>
            </div>
        </div>

    </div>
</div>
