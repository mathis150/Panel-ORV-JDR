<?php
    require_once './import/scenarios.php';
    $sm       = new ScenariosManager();
    $uuid     = $_GET['uuid'] ?? '';
    $scenario = $sm->getScenarioByUUID($uuid);

    if (!$scenario) {
        echo '<div class="user-notice user-notice--error">Scénario introuvable.</div>';
        return;
    }

    $rewards    = $sm->getScenarioRewards($uuid);
    $validTabs  = ['sc-info', 'sc-rewards'];
    $activeTab  = in_array($_GET['tab'] ?? '', $validTabs) ? $_GET['tab'] : 'sc-info';
    $fd         = $formData ?? [];
    $baseUrl    = 'jdr-params?page=scenarios&sub-page=modify&uuid=' . urlencode($uuid);
    $typeColor  = ScenariosManager::TYPE_COLORS[$scenario['type']]  ?? '#89CEFF';
    $typeLabel  = ScenariosManager::TYPE_LABELS[$scenario['type']]  ?? $scenario['type'];
    $rangColor  = ScenariosManager::RANG_COLORS[$scenario['rang']]  ?? '#9E9E9E';
    $rangDisp   = $scenario['rang'] . ($scenario['rang_plus'] ? '+' : '');

    function smhv(mixed $v): string { return htmlspecialchars((string)($v ?? '')); }
?>

<?php if ($notice ?? ''): ?>
<div class="user-notice" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-check"></i> <?php echo $notice; ?>
</div>
<?php endif; ?>
<?php if ($formError ?? null): ?>
<div class="user-notice user-notice--error" style="margin-bottom:12px;">
    <i class="fa-solid fa-circle-xmark"></i> <?php echo htmlspecialchars($formError); ?>
</div>
<?php endif; ?>

<div class="orv-buttons" style="justify-content:flex-end;">
    <button type="button" class="orv-edit" onclick="window.location.href='jdr-params?page=scenarios&sub-page=list';">
        <i class="fa-solid fa-arrow-left"></i>
        <span style="font-size:18px;">Retour à la liste</span>
    </button>
</div>

<div class="orv-menu">
    <div class="orv-menu_header">
        &lt;<?php echo smhv($scenario['titre']); ?>&gt;
        <span style="display:inline-block; margin-left:10px; padding:2px 10px; border-radius:12px; font-size:10px; font-weight:700;
                     background:<?php echo $typeColor; ?>22; color:<?php echo $typeColor; ?>;
                     border:1px solid <?php echo $typeColor; ?>55; vertical-align:middle; white-space:nowrap;">
            <?php echo smhv($typeLabel); ?>
        </span>
        <span style="display:inline-block; margin-left:6px; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:800;
                     background:<?php echo $rangColor; ?>22; color:<?php echo $rangColor; ?>;
                     border:1px solid <?php echo $rangColor; ?>55; vertical-align:middle; letter-spacing:.04em;">
            <?php echo smhv($rangDisp); ?>
        </span>
    </div>

    <!-- Onglets -->
    <div class="session-tabs" style="padding:0 24px;">
        <button type="button" class="session-tab <?php echo $activeTab === 'sc-info'    ? 'button-actif' : ''; ?>" data-target="sc-info">
            <i class="fa-solid fa-sign-hanging"></i> Informations
        </button>
        <button type="button" class="session-tab <?php echo $activeTab === 'sc-rewards' ? 'button-actif' : ''; ?>" data-target="sc-rewards">
            <i class="fa-solid fa-trophy"></i> Récompenses
            <?php if (!empty($rewards)): ?>
            <span style="margin-left:6px; background:rgba(137,206,255,0.15); padding:1px 7px; border-radius:10px; font-size:11px;">
                <?php echo count($rewards); ?>
            </span>
            <?php endif; ?>
        </button>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════ -->
    <!-- ONGLET : Informations                                          -->
    <!-- ═══════════════════════════════════════════════════════════════ -->
    <div id="sc-info" class="tab-content" style="<?php echo $activeTab !== 'sc-info' ? 'display:none;' : ''; ?>">
        <form method="POST" action="<?php echo $baseUrl; ?>&tab=sc-info">
            <input type="hidden" name="action" value="update">

            <div style="padding:16px 24px 8px; display:flex; justify-content:flex-end; gap:10px;">
                <button type="submit" class="orv-edit">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span style="font-size:16px;">Sauvegarder</span>
                </button>
            </div>

            <div class="orv-menu_container">

                <!-- Identité -->
                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Identité :</h2>
                        <cite>Titre, type et numéro du scénario.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">

                            <div class="orv-menu_form-input" style="width:360px;">
                                <label>Titre <span style="color:#FF6B7A;">*</span></label>
                                <input type="text" name="titre"
                                       value="<?php echo smhv(!empty($fd) ? ($fd['titre'] ?? '') : $scenario['titre']); ?>"
                                       required style="width:360px;">
                            </div>

                            <div class="orv-menu_form-input" style="width:180px;">
                                <label>Type</label>
                                <?php $currentType = !empty($fd) ? ($fd['type'] ?? $scenario['type']) : $scenario['type']; ?>
                                <select name="type" id="sc_type_modify" style="width:180px;"
                                        onchange="syncScType(this.value, 'modify')">
                                    <?php foreach (ScenariosManager::TYPES as $t): ?>
                                    <option value="<?php echo smhv($t); ?>" <?php echo $currentType === $t ? 'selected' : ''; ?>>
                                        <?php echo smhv(ScenariosManager::TYPE_LABELS[$t]); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <?php
                                $currentNumero = !empty($fd) ? ($fd['numero'] ?? $scenario['numero']) : $scenario['numero'];
                                $showNumero = $currentType === 'principal';
                            ?>
                            <div class="orv-menu_form-input" id="sc_numero_wrap_modify"
                                 style="width:120px; <?php echo !$showNumero ? 'display:none;' : ''; ?>">
                                <label>Numéro</label>
                                <input type="number" name="numero" min="1"
                                       value="<?php echo smhv($currentNumero); ?>"
                                       placeholder="ex: 9" style="width:120px;">
                            </div>

                            <div style="display:flex; align-items:center; gap:8px; padding-top:22px;">
                                <input type="checkbox" name="is_active" id="sc_is_active" value="1"
                                    <?php echo (!empty($fd) ? !empty($fd['is_active']) : $scenario['is_active']) ? 'checked' : ''; ?>>
                                <label for="sc_is_active" style="cursor:pointer;">Scénario actif</label>
                            </div>

                        </div>
                    </div>
                </div>

                <hr>

                <!-- Difficulté -->
                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Difficulté :</h2>
                        <cite>Rang de difficulté du scénario.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">

                            <div class="orv-menu_form-input" style="width:120px;">
                                <label>Rang</label>
                                <?php $currentRang = !empty($fd) ? ($fd['rang'] ?? $scenario['rang']) : $scenario['rang']; ?>
                                <select name="rang" style="width:120px;">
                                    <?php foreach (ScenariosManager::RANGS as $r): ?>
                                    <?php $rc = ScenariosManager::RANG_COLORS[$r] ?? '#9E9E9E'; ?>
                                    <option value="<?php echo smhv($r); ?>"
                                        <?php echo $currentRang === $r ? 'selected' : ''; ?>
                                        style="color:<?php echo $rc; ?>">
                                        <?php echo smhv($r); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div style="display:flex; align-items:center; gap:8px; padding-top:22px;">
                                <?php $currentPlus = !empty($fd) ? !empty($fd['rang_plus']) : (bool)$scenario['rang_plus']; ?>
                                <input type="checkbox" name="rang_plus" id="sc_rang_plus_modify" value="1"
                                       <?php echo $currentPlus ? 'checked' : ''; ?>>
                                <label for="sc_rang_plus_modify" style="cursor:pointer;">Rang + (version améliorée)</label>
                            </div>

                        </div>
                    </div>
                </div>

                <hr>

                <!-- Temps limite -->
                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Temps limite :</h2>
                        <cite>Durée accordée pour compléter le scénario.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">

                            <?php
                                $currentTempsType = !empty($fd) ? ($fd['temps_type'] ?? $scenario['temps_type']) : $scenario['temps_type'];
                                $currentTempsVal  = !empty($fd) ? ($fd['temps_valeur'] ?? $scenario['temps_valeur']) : $scenario['temps_valeur'];
                                $showTemps = $currentTempsType !== 'aucune';
                            ?>
                            <div class="orv-menu_form-input" style="width:160px;">
                                <label>Unité</label>
                                <select name="temps_type" id="sc_temps_type_modify" style="width:160px;"
                                        onchange="syncScTemps(this.value, 'modify')">
                                    <?php foreach (ScenariosManager::TEMPS_TYPES as $tt): ?>
                                    <option value="<?php echo smhv($tt); ?>"
                                        <?php echo $currentTempsType === $tt ? 'selected' : ''; ?>>
                                        <?php echo smhv(ScenariosManager::TEMPS_LABELS[$tt]); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="orv-menu_form-input" id="sc_temps_val_wrap_modify"
                                 style="width:140px; <?php echo !$showTemps ? 'display:none;' : ''; ?>">
                                <label>Valeur</label>
                                <input type="number" name="temps_valeur" min="1"
                                       value="<?php echo smhv($currentTempsVal); ?>"
                                       placeholder="ex: 30" style="width:140px;">
                            </div>

                        </div>
                    </div>
                </div>

                <hr>

                <!-- Conséquence échec -->
                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Conséquence en cas d'échec :</h2>
                        <cite>Ce qui arrive si le scénario est échoué.</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:360px;">
                                <label>Information d'échec</label>
                                <input type="text" name="echec_info"
                                       value="<?php echo smhv(!empty($fd) ? ($fd['echec_info'] ?? '') : $scenario['echec_info']); ?>"
                                       placeholder="ex: La mort" style="width:360px;">
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Description -->
                <div class="orv-menu_form">
                    <div>
                        <h2 class="classic-title">Description :</h2>
                        <cite>Résumé et détails du scénario (optionnel).</cite>
                    </div>
                    <div class="orv-menu_form-elements">
                        <div class="orv-menu_form-inputs-list">
                            <div class="orv-menu_form-input" style="width:100%; max-width:700px;">
                                <label>Description</label>
                                <textarea name="description" rows="6" style="width:100%;"><?php echo smhv(!empty($fd) ? ($fd['description'] ?? '') : $scenario['description']); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════ -->
    <!-- ONGLET : Récompenses                                           -->
    <!-- ═══════════════════════════════════════════════════════════════ -->
    <div id="sc-rewards" class="tab-content" style="<?php echo $activeTab !== 'sc-rewards' ? 'display:none;' : ''; ?>">
        <div class="orv-menu_container">

            <!-- Liste des récompenses -->
            <?php if (!empty($rewards)): ?>
            <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:24px;">
                <?php foreach ($rewards as $rw): ?>
                <?php
                    $rwTypeColor = ScenariosManager::REWARD_TYPE_COLORS[$rw['type']] ?? '#7F8C8D';
                    $rwTypeIcon  = ScenariosManager::REWARD_TYPE_ICONS[$rw['type']]  ?? 'fa-gift';
                    $rwTypeLabel = ScenariosManager::REWARD_TYPE_LABELS[$rw['type']] ?? $rw['type'];
                ?>
                <div style="display:flex; align-items:center; justify-content:space-between;
                            background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.06);
                            border-left:3px solid <?php echo $rwTypeColor; ?>; border-radius:6px; padding:10px 14px;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <span style="width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center;
                                     background:<?php echo $rwTypeColor; ?>22; color:<?php echo $rwTypeColor; ?>; font-size:13px; flex-shrink:0;">
                            <i class="fa-solid <?php echo $rwTypeIcon; ?>"></i>
                        </span>
                        <div>
                            <div style="font-size:13px; font-weight:700; color:rgba(217,255,255,0.85);">
                                <?php if ($rw['type'] === 'coins'): ?>
                                    <?php echo number_format((int)($rw['quantite'] ?? 0), 0, ',', ' '); ?> Coins
                                <?php else: ?>
                                    <?php echo smhv($rw['nom']); ?>
                                    <?php if ($rw['quantite'] !== null && $rw['quantite'] > 1): ?>
                                    <span style="font-size:11px; color:rgba(137,206,255,0.5); margin-left:4px;">
                                        ×<?php echo (int)$rw['quantite']; ?>
                                    </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div style="font-size:11px; color:<?php echo $rwTypeColor; ?>; margin-top:2px;">
                                <?php echo smhv($rwTypeLabel); ?>
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="<?php echo $baseUrl; ?>&tab=sc-rewards" style="margin:0;">
                        <input type="hidden" name="action"    value="remove_reward">
                        <input type="hidden" name="reward_id" value="<?php echo (int)$rw['id']; ?>">
                        <button type="submit"
                                style="background:none; border:none; cursor:pointer; color:rgba(255,100,100,0.5); font-size:15px; padding:4px 6px;"
                                title="Supprimer" onclick="return confirm('Supprimer cette récompense ?')">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div style="font-size:13px; color:rgba(137,206,255,0.3); font-style:italic; margin-bottom:24px;">
                Aucune récompense définie pour ce scénario.
            </div>
            <?php endif; ?>

            <!-- Formulaire ajout récompense -->
            <div style="background:rgba(55,174,254,0.04); border:1px solid rgba(55,174,254,0.14); border-radius:8px; padding:18px 20px;">
                <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.08em;
                            color:rgba(137,206,255,0.45); margin-bottom:14px;">
                    <i class="fa-solid fa-plus"></i> Ajouter une récompense
                </div>
                <form method="POST" action="<?php echo $baseUrl; ?>&tab=sc-rewards"
                      id="reward-form" onsubmit="return validateRewardForm()">
                    <input type="hidden" name="action" value="add_reward">

                    <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;">

                        <div class="orv-menu_form-input" style="width:160px;">
                            <label>Type</label>
                            <select name="reward_type" id="reward_type_select" style="width:160px;"
                                    onchange="syncRewardForm(this.value)">
                                <?php foreach (ScenariosManager::REWARD_TYPES as $rt): ?>
                                <option value="<?php echo smhv($rt); ?>">
                                    <?php echo smhv(ScenariosManager::REWARD_TYPE_LABELS[$rt]); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Champ nom (histoire, objet, autre) -->
                        <div class="orv-menu_form-input" id="reward_nom_wrap" style="width:260px;">
                            <label>Nom / Description</label>
                            <input type="text" name="reward_nom" id="reward_nom_input"
                                   placeholder="ex: Titre de l'histoire" style="width:260px;">
                        </div>

                        <!-- Champ quantité (tous types, requis pour coins) -->
                        <div class="orv-menu_form-input" id="reward_qty_wrap" style="width:130px; display:none;">
                            <label id="reward_qty_label">Quantité</label>
                            <input type="number" name="reward_quantite" id="reward_qty_input"
                                   min="1" placeholder="ex: 10000" style="width:130px;">
                        </div>

                        <div style="padding-bottom:0;">
                            <button type="submit" class="orv-edit" style="font-size:13px; padding:7px 14px;">
                                <i class="fa-solid fa-plus"></i> Ajouter
                            </button>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>

</div>

<script>
document.querySelectorAll('.session-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        const target = btn.dataset.target;
        document.querySelectorAll('.tab-content').forEach(tc => tc.style.display = 'none');
        document.querySelectorAll('.session-tab').forEach(b => b.classList.remove('button-actif'));
        document.getElementById(target).style.display = '';
        btn.classList.add('button-actif');
    });
});

function syncScType(type, suffix) {
    const wrap = document.getElementById('sc_numero_wrap_' + suffix);
    if (wrap) wrap.style.display = (type === 'principal') ? '' : 'none';
}
function syncScTemps(type, suffix) {
    const wrap = document.getElementById('sc_temps_val_wrap_' + suffix);
    if (wrap) wrap.style.display = (type !== 'aucune') ? '' : 'none';
}

function syncRewardForm(type) {
    const nomWrap = document.getElementById('reward_nom_wrap');
    const qtyWrap = document.getElementById('reward_qty_wrap');
    const qtyLabel = document.getElementById('reward_qty_label');
    const nomInput = document.getElementById('reward_nom_input');

    if (type === 'coins') {
        nomWrap.style.display = 'none';
        nomInput.removeAttribute('required');
        qtyWrap.style.display = '';
        qtyLabel.textContent = 'Montant (C)';
        document.getElementById('reward_qty_input').setAttribute('required', '');
    } else {
        nomWrap.style.display = '';
        nomInput.setAttribute('required', '');
        if (type === 'objet') {
            qtyWrap.style.display = '';
            qtyLabel.textContent = 'Quantité';
            document.getElementById('reward_qty_input').removeAttribute('required');
        } else {
            qtyWrap.style.display = 'none';
            document.getElementById('reward_qty_input').removeAttribute('required');
        }
    }
}

function validateRewardForm() {
    const type = document.getElementById('reward_type_select').value;
    const nom  = document.getElementById('reward_nom_input').value.trim();
    const qty  = parseInt(document.getElementById('reward_qty_input').value, 10);
    if (type === 'coins' && (isNaN(qty) || qty <= 0)) {
        alert('Veuillez entrer un montant valide en coins.');
        return false;
    }
    if (type !== 'coins' && nom === '') {
        alert('Le nom de la récompense est requis.');
        return false;
    }
    return true;
}

// Init
syncRewardForm(document.getElementById('reward_type_select').value);
</script>
