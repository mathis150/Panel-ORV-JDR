<?php
    require_once './import/scenarios.php';
    $sm       = new ScenariosManager();
    $uuid     = $_GET['uuid'] ?? '';
    $scenario = $sm->getScenarioByUUID($uuid);
    if (!$scenario) {
        echo '<div class="user-notice user-notice--error">Scénario introuvable.</div>';
        return;
    }
    function sdhv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }
    $typeColor = ScenariosManager::TYPE_COLORS[$scenario['type']]  ?? '#89CEFF';
    $typeLabel = ScenariosManager::TYPE_LABELS[$scenario['type']]  ?? $scenario['type'];
    $rangColor = ScenariosManager::RANG_COLORS[$scenario['rang']]  ?? '#9E9E9E';
    $rangDisp  = $scenario['rang'] . ($scenario['rang_plus'] ? '+' : '');
    $titleDisplay = $scenario['titre'];
    if ($scenario['type'] === 'principal' && $scenario['numero'] !== null) {
        $titleDisplay = 'Scénario Principal n°' . (int)$scenario['numero'] . ' — ' . $titleDisplay;
    }
?>

<div class="orv-buttons" style="justify-content:flex-end;">
    <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=scenarios&sub-page=list';">
        <i class="fa-solid fa-arrow-left"></i>
        <span style="font-size:18px;">Annuler</span>
    </button>
</div>

<div class="orv-menu">
    <div class="orv-menu_header">&lt;Suppression d'un scénario&gt;</div>
    <div class="orv-menu_container">
        <div style="text-align:center; padding:28px 20px;">
            <div style="font-size:40px; color:rgba(239,83,80,0.4); margin-bottom:16px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <p style="color:rgba(217,255,255,0.8); font-size:16px; margin-bottom:10px;">
                Êtes-vous sûr de vouloir supprimer le scénario
            </p>
            <p style="color:#EF5350; font-size:18px; font-weight:700; margin-bottom:14px;">
                « <?php echo sdhv($titleDisplay); ?> »
            </p>
            <div style="display:flex; justify-content:center; gap:8px; margin-bottom:22px;">
                <span style="padding:2px 10px; border-radius:12px; font-size:11px; font-weight:700;
                             background:<?php echo $typeColor; ?>22; color:<?php echo $typeColor; ?>;
                             border:1px solid <?php echo $typeColor; ?>55;">
                    <?php echo sdhv($typeLabel); ?>
                </span>
                <span style="padding:2px 8px; border-radius:10px; font-size:11px; font-weight:800;
                             background:<?php echo $rangColor; ?>22; color:<?php echo $rangColor; ?>;
                             border:1px solid <?php echo $rangColor; ?>55; letter-spacing:.04em;">
                    <?php echo sdhv($rangDisp); ?>
                </span>
            </div>
            <p style="color:rgba(217,255,255,0.35); font-size:12px; margin-bottom:28px;">
                Cette action supprimera également toutes les récompenses associées. Elle est irréversible.
            </p>
            <form method="POST" action="jdr-params?page=scenarios&sub-page=delete&uuid=<?php echo urlencode($uuid); ?>"
                  style="display:inline;">
                <button type="submit"
                        style="background:rgba(239,83,80,0.12); border:1px solid rgba(239,83,80,0.35);
                               color:#EF5350; -webkit-text-fill-color:#EF5350;">
                    <i class="fa-solid fa-trash"></i> Confirmer la suppression
                </button>
            </form>
        </div>
    </div>
</div>
