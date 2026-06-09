<?php
/**
 * Script d'initialisation — Création du compte super-administrateur
 * Accès : http://localhost/panel-orv/setup.php
 * SUPPRIMER ce fichier après utilisation.
 */

define('ORV_SETUP', true);
require_once __DIR__ . '/import/users.php';

$users  = new UsersManager();
$result = null;
$error  = null;

// Vérifie si un compte sudo existe déjà
$existing = $users->makeSQLRequest(
    "SELECT COUNT(*) AS cnt FROM users WHERE role = 'sudo'",
    [],
    ORV_SQL_FETCH_ONE
);
$sudoExists = ($existing['cnt'] ?? 0) > 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$sudoExists) {
    $pseudo      = trim($_POST['pseudo']      ?? '');
    $displayName = trim($_POST['display_name'] ?? '');
    $email       = trim($_POST['email']        ?? '');

    $result = $users->createUser($pseudo, $email, 'sudo', $displayName);
    if ($result['success']) {
        $sudoExists = true;
    } else {
        $error = $result['message'];
    }
}

?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup — ORV JDR</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #0f1117;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .card {
            background: #1a1d27;
            border: 1px solid #2d3148;
            border-radius: 12px;
            padding: 2.5rem;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 20px 60px rgba(0,0,0,.5);
        }
        .badge {
            display: inline-block;
            background: #7c3aed22;
            border: 1px solid #7c3aed55;
            color: #a78bfa;
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: .25rem .75rem;
            border-radius: 999px;
            margin-bottom: 1.25rem;
        }
        h1 { font-size: 1.5rem; font-weight: 700; color: #f1f5f9; margin-bottom: .5rem; }
        .subtitle { font-size: .875rem; color: #64748b; margin-bottom: 2rem; }
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; font-size: .8rem; font-weight: 600; color: #94a3b8; margin-bottom: .4rem; letter-spacing: .04em; }
        input[type=text], input[type=email] {
            width: 100%;
            background: #0f1117;
            border: 1px solid #2d3148;
            border-radius: 8px;
            color: #e2e8f0;
            padding: .65rem 1rem;
            font-size: .9rem;
            outline: none;
            transition: border-color .15s;
        }
        input:focus { border-color: #7c3aed; }
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: #7c3aed22;
            border: 1px solid #7c3aed55;
            color: #a78bfa;
            font-size: .8rem;
            font-weight: 600;
            padding: .5rem 1rem;
            border-radius: 8px;
        }
        .role-badge::before { content: '★'; }
        .btn {
            width: 100%;
            background: #7c3aed;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: .75rem;
            font-size: .9rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: .75rem;
            transition: background .15s;
        }
        .btn:hover { background: #6d28d9; }
        .alert {
            padding: .9rem 1rem;
            border-radius: 8px;
            font-size: .85rem;
            margin-bottom: 1.5rem;
        }
        .alert-error { background: #7f1d1d33; border: 1px solid #ef444455; color: #fca5a5; }
        .alert-success { background: #14532d33; border: 1px solid #22c55e55; color: #86efac; }
        .alert-warning { background: #78350f33; border: 1px solid #f59e0b55; color: #fcd34d; }
        .credentials {
            background: #0f1117;
            border: 1px solid #2d3148;
            border-radius: 8px;
            padding: 1rem 1.25rem;
            margin: 1.25rem 0;
        }
        .credentials dt { font-size: .75rem; color: #64748b; font-weight: 600; letter-spacing: .04em; margin-top: .75rem; }
        .credentials dt:first-child { margin-top: 0; }
        .credentials dd { font-size: 1rem; color: #e2e8f0; font-family: 'Cascadia Code', 'Fira Code', monospace; margin-top: .2rem; }
        .credentials .pass { color: #4ade80; font-size: 1.1rem; font-weight: 700; }
        .divider { border: none; border-top: 1px solid #2d3148; margin: 1.5rem 0; }
        .delete-warning {
            background: #7c3aed11;
            border: 1px dashed #7c3aed66;
            border-radius: 8px;
            padding: .9rem 1rem;
            font-size: .8rem;
            color: #a78bfa;
            text-align: center;
            line-height: 1.5;
        }
    </style>
</head>
<body>
<div class="card">
    <span class="badge">Configuration initiale</span>
    <h1>Créer le Super-Admin</h1>
    <p class="subtitle">Ce formulaire crée le premier compte <strong>sudo</strong> et applique toutes les migrations de base de données.</p>

    <?php if ($result && $result['success']): ?>

        <div class="alert alert-success">Compte super-administrateur créé avec succès.</div>
        <dl class="credentials">
            <dt>RÔLE</dt>
            <dd>Super-Administrateur (sudo)</dd>
            <dt>IDENTIFIANT</dt>
            <dd><?= htmlspecialchars($_POST['pseudo']) ?></dd>
            <dt>E-MAIL</dt>
            <dd><?= htmlspecialchars($_POST['email']) ?></dd>
            <dt>MOT DE PASSE TEMPORAIRE</dt>
            <dd class="pass"><?= htmlspecialchars($result['temp_password']) ?></dd>
        </dl>
        <div class="alert alert-warning">
            Notez ce mot de passe — il ne sera plus affiché.<br>
            Connectez-vous et changez-le immédiatement.
        </div>
        <hr class="divider">
        <div class="delete-warning">
            Supprimez le fichier <code>setup.php</code> de votre serveur<br>avant la mise en production.
        </div>

    <?php elseif ($sudoExists): ?>

        <div class="alert alert-warning">
            Un compte super-administrateur existe déjà dans la base de données.<br>
            Ce script ne peut pas être utilisé une seconde fois.
        </div>
        <hr class="divider">
        <div class="delete-warning">Supprimez le fichier <code>setup.php</code> de votre serveur.</div>

    <?php else: ?>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>PSEUDONYME *</label>
                <input type="text" name="pseudo" value="<?= htmlspecialchars($_POST['pseudo'] ?? 'superadmin') ?>" required autofocus>
            </div>
            <div class="form-group">
                <label>NOM D'AFFICHAGE</label>
                <input type="text" name="display_name" value="<?= htmlspecialchars($_POST['display_name'] ?? 'Super Administrateur') ?>">
            </div>
            <div class="form-group">
                <label>ADRESSE E-MAIL *</label>
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>RÔLE</label>
                <div class="role-badge">Super-Administrateur (sudo)</div>
            </div>
            <button type="submit" class="btn">Créer le compte &amp; appliquer les migrations</button>
        </form>

    <?php endif; ?>
</div>
</body>
</html>
