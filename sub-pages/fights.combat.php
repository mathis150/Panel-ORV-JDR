<div class="orv-menu" id="fight-combat-panel" style="overflow: visible;">

    <!-- En-tête du combat -->
    <div class="fight-combat-header">
        <div class="fight-combat-title">
            <i class="fa-solid fa-swords"></i>
            La Horde du Roi-Démon
        </div>
        <div class="fight-turn-counter">
            <i class="fa-solid fa-arrows-rotate"></i> Tour <span id="turn-num">1</span>
        </div>
        <button class="fight-next-btn" onclick="nextTurn()">
            <i class="fa-solid fa-forward-step"></i> Tour suivant
        </button>
        <button class="fight-end-btn" onclick="if(confirm('Terminer ce combat et revenir à la liste ?')) window.location.href='fights?sub-page=list'">
            <i class="fa-solid fa-flag-checkered"></i> Terminer le combat
        </button>
    </div>

    <!-- Barre d'initiative -->
    <div class="fight-initiative">
        <div class="fight-initiative-label"><i class="fa-solid fa-list-ol"></i> Ordre d'initiative</div>
        <div class="fight-initiative-track" id="initiative-track"></div>
    </div>

    <!-- Grille combat : Joueurs | Adversaires -->
    <div class="fight-combat-grid">
        <div class="fight-combat-side" id="side-players">
            <div class="fight-side-header fight-side-header--players">
                <i class="fa-solid fa-users"></i> Joueurs
            </div>
        </div>
        <div class="fight-combat-side" id="side-mobs">
            <div class="fight-side-header fight-side-header--mobs">
                <i class="fa-solid fa-skull"></i> Adversaires
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="fight-quick-label"><i class="fa-solid fa-bolt"></i> Actions rapides</div>
    <div class="fight-quick-actions">
        <button class="fight-quick-action-btn fight-quick-action-btn--constellation" onclick="openModal('modal-constellation')">
            <i class="fa-solid fa-star"></i>
            Constellation
        </button>
        <button class="fight-quick-action-btn fight-quick-action-btn--system" onclick="openModal('modal-system')">
            <i class="fa-solid fa-terminal"></i>
            Message Système
        </button>
        <button class="fight-quick-action-btn fight-quick-action-btn--effect" onclick="openModal('modal-effect')">
            <i class="fa-solid fa-wand-sparkles"></i>
            Effet de Statut
        </button>
        <button class="fight-quick-action-btn fight-quick-action-btn--note" onclick="openModal('modal-note')">
            <i class="fa-solid fa-note-sticky"></i>
            Note rapide
        </button>
    </div>

    <!-- Journal de combat -->
    <div class="fight-log-section">
        <div class="fight-log-header">
            <i class="fa-solid fa-scroll"></i> Journal de combat
        </div>
        <div class="fight-log-list" id="fight-log"></div>
    </div>

</div>

<!-- ===== Modal : Constellation ===== -->
<div class="fight-action-modal" id="modal-constellation">
    <div class="fight-action-modal-box">
        <div class="fight-action-modal-title">
            <i class="fa-solid fa-star" style="color:#F5D020;"></i>
            Intervention d'une Constellation
            <button class="fight-modal-close" onclick="closeModal('modal-constellation')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="orv-menu_form-input" style="width: 100%;">
            <label>Constellation :</label>
            <select id="mc-constellation" style="width: 100%;">
                <option value="">— Sélectionner —</option>
                <option>Yoo Joonghyuk – Regressor</option>
                <option>Kim Dokja – Lecteur Omniscient</option>
                <option>Bihyung – Dokkaebi Maître</option>
                <option>Secretive Plotter</option>
            </select>
        </div>
        <div class="orv-menu_form-input" style="width: 100%; margin-top: 12px;">
            <label>Message / Action :</label>
            <textarea id="mc-message" class="session-notes" style="min-height: 90px; width: 100%;" placeholder="Ex : La Constellation vous octroie 500 coins en récompense pour votre bravoure…"></textarea>
        </div>
        <button class="orv-edit fight-modal-confirm" onclick="confirmConstellation()">
            <i class="fa-solid fa-check"></i> <span style="font-size: 16px;">Confirmer</span>
        </button>
    </div>
</div>

<!-- ===== Modal : Message Système ===== -->
<div class="fight-action-modal" id="modal-system">
    <div class="fight-action-modal-box">
        <div class="fight-action-modal-title">
            <i class="fa-solid fa-terminal" style="color:#89CEFF;"></i>
            Message Système
            <button class="fight-modal-close" onclick="closeModal('modal-system')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="orv-menu_form-input" style="width: 100%;">
            <label>Message :</label>
            <textarea id="ms-message" class="session-notes" style="min-height: 100px; width: 100%;" placeholder="Ex : [Alerte Dokkaebi] Le Scénario du Dragon Ancien est activé. Objectif : survivre 5 tours."></textarea>
        </div>
        <button class="orv-edit fight-modal-confirm" onclick="confirmSystem()">
            <i class="fa-solid fa-check"></i> <span style="font-size: 16px;">Envoyer</span>
        </button>
    </div>
</div>

<!-- ===== Modal : Effet de Statut ===== -->
<div class="fight-action-modal" id="modal-effect">
    <div class="fight-action-modal-box">
        <div class="fight-action-modal-title">
            <i class="fa-solid fa-wand-sparkles" style="color:#D099FF;"></i>
            Appliquer un Effet de Statut
            <button class="fight-modal-close" onclick="closeModal('modal-effect')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="orv-menu_form-input" style="width: 100%;">
            <label>Cible :</label>
            <select id="me-target" style="width: 100%;"></select>
        </div>
        <div class="orv-menu_form-input" style="width: 100%; margin-top: 12px;">
            <label>Effet :</label>
            <select id="me-effect" style="width: 100%;" onchange="document.getElementById('me-custom-wrap').hidden = this.value !== 'custom'">
                <option value="poison">☠ Poison</option>
                <option value="burn">🔥 Brûlure</option>
                <option value="freeze">❄ Gel</option>
                <option value="stun">⚡ Étourdissement</option>
                <option value="buff-atk">↑ Buff ATK</option>
                <option value="debuff-def">↓ Debuff DEF</option>
                <option value="regen">✚ Régénération</option>
                <option value="bleed">🩸 Saignement</option>
                <option value="custom">Personnalisé…</option>
            </select>
        </div>
        <div class="orv-menu_form-input" style="width: 100%; margin-top: 12px;" id="me-custom-wrap" hidden>
            <label>Nom de l'effet :</label>
            <input type="text" id="me-custom-name" placeholder="Ex : Malédiction du Roi-Démon" style="width: 100%;">
        </div>
        <div class="orv-menu_form-input" style="width: 100%; margin-top: 12px;">
            <label>Durée (tours) :</label>
            <input type="number" id="me-duration" value="3" min="1" max="99" style="width: 100px;">
        </div>
        <button class="orv-edit fight-modal-confirm" style="margin-top: 4px;" onclick="confirmEffect()">
            <i class="fa-solid fa-check"></i> <span style="font-size: 16px;">Appliquer</span>
        </button>
    </div>
</div>

<!-- ===== Modal : Note rapide ===== -->
<div class="fight-action-modal" id="modal-note">
    <div class="fight-action-modal-box">
        <div class="fight-action-modal-title">
            <i class="fa-solid fa-note-sticky" style="color:#7EFFA6;"></i>
            Note rapide
            <button class="fight-modal-close" onclick="closeModal('modal-note')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="orv-menu_form-input" style="width: 100%;">
            <label>Note :</label>
            <textarea id="mn-note" class="session-notes" style="min-height: 100px; width: 100%;" placeholder="Rebondissement, secret révélé, décision importante…"></textarea>
        </div>
        <button class="orv-edit fight-modal-confirm" onclick="confirmNote()">
            <i class="fa-solid fa-check"></i> <span style="font-size: 16px;">Ajouter au journal</span>
        </button>
    </div>
</div>

<script>
/* ---- Données demo ---- */
var participants = [
    { id: 'p1', name: 'Kim Dokja',       type: 'player', hp: 340, maxHp: 450, initiative: 18, status: 'active', effects: [] },
    { id: 'p2', name: 'Yoo Joonghyuk',  type: 'player', hp: 520, maxHp: 600, initiative: 15, status: 'active', effects: [] },
    { id: 'p3', name: 'Jung Heewon',    type: 'player', hp: 280, maxHp: 380, initiative: 12, status: 'active', effects: [] },
    { id: 'm1', name: 'Golem Ancien',   type: 'mob',    hp: 120, maxHp: 500, initiative: 10, status: 'active', effects: ['Brûlure'] },
    { id: 'm2', name: 'Spectre ×3',     type: 'mob',    hp: 60,  maxHp: 200, initiative: 7,  status: 'active', effects: [] },
    { id: 'm3', name: 'Roi des Larves', type: 'mob',    hp: 800, maxHp: 800, initiative: 4,  status: 'active', effects: [] }
];

participants.sort(function(a, b) { return b.initiative - a.initiative; });

var currentTurnIdx = 0;
var turnNumber     = 1;
var logEntries     = [];

/* ---- Utilitaires ---- */
function hpPct(p) { return Math.max(0, Math.round(p.hp / p.maxHp * 100)); }
function hpClass(pct) {
    if (pct <= 0)  return 'fight-hp-bar-fill--dead';
    if (pct <= 25) return 'fight-hp-bar-fill--low';
    if (pct <= 55) return 'fight-hp-bar-fill--mid';
    return '';
}
function addLog(type, html) {
    logEntries.unshift({ type: type, html: html, turn: turnNumber });
    renderLog();
}
function renderLog() {
    var el = document.getElementById('fight-log');
    el.innerHTML = logEntries.map(function(e) {
        return '<div class="fight-log-entry fight-log-entry--' + e.type + '">' +
               '<span class="fight-log-turn">T' + e.turn + '</span>' +
               '<span class="fight-log-text">' + e.html + '</span></div>';
    }).join('');
}

/* ---- Initiative ---- */
function renderInitiative() {
    var track = document.getElementById('initiative-track');
    track.innerHTML = participants.map(function(p, i) {
        var cls = 'fight-initiative-item fight-initiative-item--' + (p.type === 'player' ? 'player' : 'mob');
        if (i === currentTurnIdx) cls += ' fight-initiative-item--current';
        if (p.status === 'ko')    cls += ' fight-initiative-item--ko';
        var arrow = i < participants.length - 1
            ? '<div class="fight-initiative-arrow"><i class="fa-solid fa-chevron-right"></i></div>' : '';
        return '<div class="' + cls + '" title="Initiative : ' + p.initiative + '">' +
               '<div class="fight-initiative-name">' + p.name + '</div>' +
               '<div class="fight-initiative-score">' + p.initiative + '</div>' +
               '</div>' + arrow;
    }).join('');
}

/* ---- Carte participant ---- */
function renderCard(p) {
    var card = document.getElementById('card-' + p.id);
    if (!card) return;
    var pct = hpPct(p);
    var isCurrent = participants[currentTurnIdx] && participants[currentTurnIdx].id === p.id;

    var cls = 'fight-participant-card' + (p.type === 'mob' ? ' fight-participant-card--mob' : '');
    if (isCurrent)      cls += ' fight-participant-card--current';
    if (p.status === 'ko') cls += ' fight-participant-card--ko';
    card.className = cls;

    var effects = p.effects.length
        ? '<div class="fight-effects">' + p.effects.map(function(e) {
            return '<span class="fight-effect-tag"><i class="fa-solid fa-bolt"></i> ' + e + '</span>';
          }).join('') + '</div>' : '';

    var koBtn = p.status === 'ko'
        ? '<button class="fight-ko-btn fight-ko-btn--restore" onclick="toggleKO(\'' + p.id + '\')" title="Remettre en jeu"><i class="fa-solid fa-heart-circle-plus"></i> Remettre</button>'
        : '<button class="fight-ko-btn" onclick="toggleKO(\'' + p.id + '\')" title="Mettre KO"><i class="fa-solid fa-skull"></i> KO</button>';

    card.innerHTML =
        '<div class="fight-participant-top">' +
            '<div class="fight-participant-name">' + p.name + '</div>' +
            '<div class="fight-participant-hp-text"><span class="hp-current">' + p.hp + '</span> / ' + p.maxHp + ' PV</div>' +
        '</div>' +
        effects +
        '<div class="fight-hp-bar-wrap"><div id="bar-' + p.id + '" class="fight-hp-bar-fill ' + hpClass(pct) + '" style="width:' + pct + '%"></div></div>' +
        '<div class="fight-dmg-form">' +
            '<input class="fight-dmg-input" id="dmg-' + p.id + '" type="number" placeholder="PV" min="0">' +
            '<button class="fight-apply-btn" onclick="applyDamage(\'' + p.id + '\')"><i class="fa-solid fa-shield-minus"></i> Dégâts</button>' +
            '<button class="fight-heal-btn" onclick="applyHeal(\'' + p.id + '\')"><i class="fa-solid fa-heart-circle-plus"></i> Soins</button>' +
            koBtn +
        '</div>';
}

function renderAllCards() {
    var ps = document.getElementById('side-players');
    var ms = document.getElementById('side-mobs');
    var ph = ps.querySelector('.fight-side-header');
    var mh = ms.querySelector('.fight-side-header');
    ps.innerHTML = ''; ms.innerHTML = '';
    if (ph) ps.appendChild(ph);
    if (mh) ms.appendChild(mh);
    participants.forEach(function(p) {
        var div = document.createElement('div');
        div.id = 'card-' + p.id;
        (p.type === 'player' ? ps : ms).appendChild(div);
        renderCard(p);
    });
}

/* ---- Actions de combat ---- */
function applyDamage(id) {
    var p   = participants.find(function(x) { return x.id === id; });
    var inp = document.getElementById('dmg-' + id);
    var amt = parseInt(inp.value, 10) || 0;
    if (amt <= 0) return;
    var prev = p.hp;
    p.hp = Math.max(0, p.hp - amt);
    if (p.hp === 0) p.status = 'ko';
    addLog('damage', p.name + ' subit <strong>' + amt + ' dégâts</strong> — PV : ' + prev + ' → ' + p.hp + (p.hp === 0 ? ' <strong style="color:#FF8888">[KO]</strong>' : ''));
    inp.value = '';
    renderCard(p);
    renderInitiative();
}
function applyHeal(id) {
    var p   = participants.find(function(x) { return x.id === id; });
    var inp = document.getElementById('dmg-' + id);
    var amt = parseInt(inp.value, 10) || 0;
    if (amt <= 0) return;
    var prev = p.hp;
    p.hp = Math.min(p.maxHp, p.hp + amt);
    addLog('heal', p.name + ' récupère <strong>+' + amt + ' PV</strong> — PV : ' + prev + ' → ' + p.hp);
    inp.value = '';
    renderCard(p);
}
function toggleKO(id) {
    var p = participants.find(function(x) { return x.id === id; });
    if (p.status === 'ko') {
        p.status = 'active';
        p.hp = Math.max(1, Math.floor(p.maxHp * 0.10));
        addLog('heal', p.name + ' est <strong>remis en jeu</strong> — PV : ' + p.hp);
    } else {
        p.status = 'ko';
        p.hp = 0;
        addLog('ko', p.name + ' est <strong style="color:#FF8888">mis KO</strong> !');
    }
    renderCard(p);
    renderInitiative();
}
function nextTurn() {
    var tries = 0, total = participants.length;
    do {
        currentTurnIdx = (currentTurnIdx + 1) % total;
        if (currentTurnIdx === 0) {
            turnNumber++;
            document.getElementById('turn-num').textContent = turnNumber;
            addLog('turn', '━━ Début du Tour ' + turnNumber + ' ━━');
        }
        tries++;
    } while (participants[currentTurnIdx].status === 'ko' && tries < total);
    renderInitiative();
    participants.forEach(function(p) { renderCard(p); });
    addLog('system', 'C\'est au tour de <strong>' + participants[currentTurnIdx].name +
        '</strong> <span style="opacity:.5">(initiative ' + participants[currentTurnIdx].initiative + ')</span>');
}

/* ---- Modals ---- */
function openModal(id) {
    if (id === 'modal-effect') {
        var sel = document.getElementById('me-target');
        sel.innerHTML = participants
            .filter(function(p) { return p.status !== 'ko'; })
            .map(function(p) { return '<option value="' + p.id + '">' + p.name + '</option>'; })
            .join('');
    }
    document.getElementById(id).classList.add('open');
}
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

/* Fermer en cliquant hors de la modal-box */
document.querySelectorAll('.fight-action-modal').forEach(function(m) {
    m.addEventListener('click', function(e) { if (e.target === m) closeModal(m.id); });
});

function confirmConstellation() {
    var name = document.getElementById('mc-constellation').value;
    var msg  = document.getElementById('mc-message').value.trim();
    if (!name || !msg) return;
    addLog('constellation', '<strong>[' + name + ']</strong> ' + msg);
    document.getElementById('mc-message').value = '';
    closeModal('modal-constellation');
}
function confirmSystem() {
    var msg = document.getElementById('ms-message').value.trim();
    if (!msg) return;
    addLog('system', '<strong>[Système]</strong> ' + msg);
    document.getElementById('ms-message').value = '';
    closeModal('modal-system');
}
function confirmEffect() {
    var tgtId = document.getElementById('me-target').value;
    var effEl = document.getElementById('me-effect');
    var dur   = document.getElementById('me-duration').value;
    var p     = participants.find(function(x) { return x.id === tgtId; });
    var label = effEl.value === 'custom'
        ? (document.getElementById('me-custom-name').value || 'Effet personnalisé')
        : effEl.options[effEl.selectedIndex].text;
    p.effects.push(label);
    addLog('effect', p.name + ' reçoit l\'effet <strong>' + label + '</strong> (' + dur + ' tour' + (dur > 1 ? 's' : '') + ')');
    renderCard(p);
    closeModal('modal-effect');
}
function confirmNote() {
    var note = document.getElementById('mn-note').value.trim();
    if (!note) return;
    addLog('system', '<strong>[Note MJ]</strong> ' + note);
    document.getElementById('mn-note').value = '';
    closeModal('modal-note');
}

/* ---- Init ---- */
document.addEventListener('DOMContentLoaded', function() {
    renderAllCards();
    renderInitiative();
    addLog('system', 'Combat lancé — ' + participants.length + ' participants');
    addLog('system', 'C\'est au tour de <strong>' + participants[0].name +
        '</strong> <span style="opacity:.5">(initiative ' + participants[0].initiative + ')</span>');
});
</script>
