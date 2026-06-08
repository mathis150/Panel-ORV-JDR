<div class="orv-buttons">
    <div id="session-status-action" style="margin-right: auto; display: flex; align-items: center;">
        <button class="session-launch-btn" onclick="setSessionStatus('active')">
            <i class="fa-solid fa-circle-play"></i> Lancer la session
        </button>
    </div>
    <button class="orv-edit"><i class="fa-solid fa-check"></i> <span style="font-size: 18px;">Sauvegarder</span></button>
    <button class="orv-edit" onclick="window.location.href='sessions?sub-page=list'">
        <i class="fa-solid fa-xmark"></i> <span style="font-size: 18px;">Retour</span>
    </button>
</div>

<div class="orv-menu" id="session-modify">
    <div class="orv-menu_header">&lt;Préparation de Session&gt;</div>

    <div class="session-stepper" id="session-stepper">
        <div class="session-step session-step--current" data-step="planned">
            <div class="session-step__icon"><i class="fa-solid fa-clock"></i></div>
            <div class="session-step__label">Planifiée</div>
        </div>
        <div class="session-connector"></div>
        <div class="session-step" data-step="active">
            <div class="session-step__icon"><i class="fa-solid fa-circle-play"></i></div>
            <div class="session-step__label">En cours</div>
        </div>
        <div class="session-connector"></div>
        <div class="session-step" data-step="done">
            <div class="session-step__icon"><i class="fa-solid fa-flag-checkered"></i></div>
            <div class="session-step__label">Terminée</div>
        </div>
    </div>

    <div class="session-tabs">
        <button class="session-tab button-actif" data-target="sp-info">
            <i class="fa-solid fa-circle-info"></i> Informations
        </button>
        <button class="session-tab" data-target="sp-notes">
            <i class="fa-solid fa-note-sticky"></i> Notes
        </button>
        <button class="session-tab" data-target="sp-liaisons">
            <i class="fa-solid fa-link"></i> Liaisons
        </button>
    </div>

    <div class="orv-menu_container">

        <!-- Panel : Informations -->
        <div id="sp-info" class="session-tab-panel">
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Informations générales :</h2>
                    <cite>Les détails principaux de la session.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width: 380px;">
                            <label>Titre de la session :</label>
                            <input type="text" value="Le Premier Scénario" style="width: 380px;">
                        </div>
                        <div class="orv-menu_form-input">
                            <label>Date prévue :</label>
                            <input type="date" value="2025-01-15">
                        </div>
                        <div class="orv-menu_form-input" style="width: 280px;">
                            <label>Scénario associé :</label>
                            <select style="width: 280px;">
                                <option value="">— Aucun —</option>
                                <option value="arc1" selected>Arc I – Survivre</option>
                                <option value="arc2">Arc II – Les Grands Scénarios</option>
                                <option value="arc3">Arc III – La Tour</option>
                            </select>
                        </div>
                        <div class="orv-menu_form-input" style="width: 300px;">
                            <label>Lieu concerné :</label>
                            <input type="text" placeholder="Ex : Station de métro Hyehwa" style="width: 300px;">
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Synopsis :</h2>
                    <cite>Un résumé du déroulement de la session.</cite>
                </div>
                <div class="orv-menu_form-elements" style="grid-template-columns: 1fr;">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width: 100%;">
                            <label>Synopsis :</label>
                            <textarea class="session-notes" style="min-height: 110px;">Les joueurs arrivent dans le premier scénario officiel du système Dokkaebi. L'objectif est de survivre à la mise en place du Scénario du Dragon Ancien.</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel : Notes -->
        <div id="sp-notes" class="session-tab-panel hidden">
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Notes de préparation :</h2>
                    <cite>Notes internes MJ — PNJ, événements, rebondissements, secrets…</cite>
                </div>
                <div class="orv-menu_form-elements" style="grid-template-columns: 1fr;">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width: 100%;">
                            <label>Notes MJ :</label>
                            <textarea class="session-notes" style="min-height: 280px;" placeholder="Vos notes de préparation (PNJ à introduire, secrets, événements déclencheurs…)"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Compte-rendu :</h2>
                    <cite>Notes post-session — ce qui s'est réellement passé.</cite>
                </div>
                <div class="orv-menu_form-elements" style="grid-template-columns: 1fr;">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width: 100%;">
                            <label>Compte-rendu de session :</label>
                            <textarea class="session-notes" style="min-height: 200px;" placeholder="Résumé de ce qui s'est passé lors de la session…"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel : Liaisons -->
        <div id="sp-liaisons" class="session-tab-panel hidden">
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Personnages & Groupes :</h2>
                    <cite>Les acteurs impliqués dans cette session.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width: 325px;">
                            <label>Personnages liés :</label>
                            <select id="sm-characters" data-placeholder="Sélectionner des personnages" multiple="multiple">
                                <option value="dokja" selected>Kim Dokja</option>
                                <option value="joonghyuk" selected>Yoo Joonghyuk</option>
                                <option value="heewon">Jung Heewon</option>
                                <option value="hyunsung">Lee Hyunsung</option>
                                <option value="jihye">Lee Jihye</option>
                            </select>
                        </div>
                        <div class="orv-menu_form-input" style="width: 325px;">
                            <label>Groupes liés :</label>
                            <select id="sm-groups" data-placeholder="Sélectionner des groupes" multiple="multiple">
                                <option value="g1" selected>Groupe de Survie de Dokja</option>
                                <option value="g2">Congrès des Survivants</option>
                                <option value="g3">Alliance des Constellations</option>
                                <option value="g4">Armée du Fleuve Han</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="orv-menu_form">
                <div>
                    <h2 class="classic-title">Éléments narratifs :</h2>
                    <cite>Les objets, compétences et constellations qui jouent un rôle dans la session.</cite>
                </div>
                <div class="orv-menu_form-elements">
                    <div class="orv-menu_form-inputs-list">
                        <div class="orv-menu_form-input" style="width: 325px;">
                            <label>Objets importants :</label>
                            <select id="sm-items" data-placeholder="Sélectionner des objets" multiple="multiple">
                                <option value="i1">Épée de la Réincarnation</option>
                                <option value="i2">Armure du Roi des Démons</option>
                                <option value="i3">Baguette du Dokkaebi</option>
                            </select>
                        </div>
                        <div class="orv-menu_form-input" style="width: 325px;">
                            <label>Compétences impliquées :</label>
                            <select id="sm-capacities" data-placeholder="Sélectionner des compétences" multiple="multiple">
                                <option value="c1">Lecture Omnisciente</option>
                                <option value="c2">Souverain des Étoiles</option>
                                <option value="c3">Tournant de l'Histoire</option>
                            </select>
                        </div>
                        <div class="orv-menu_form-input" style="width: 325px;">
                            <label>Constellations impliquées :</label>
                            <select id="sm-constellations" data-placeholder="Sélectionner des constellations" multiple="multiple">
                                <option value="cn1">Yoo Joonghyuk – Regressor</option>
                                <option value="cn2">Kim Dokja – Lecteur Omniscient</option>
                                <option value="cn3">Bihyung – Dokkaebi</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    new MultiSelect(document.getElementById('sm-characters'));
    new MultiSelect(document.getElementById('sm-groups'));
    new MultiSelect(document.getElementById('sm-items'));
    new MultiSelect(document.getElementById('sm-capacities'));
    new MultiSelect(document.getElementById('sm-constellations'));

    var sessionStatus = 'planned';

    function updateStepper(status) {
        var order = ['planned', 'active', 'done'];
        var idx   = order.indexOf(status);

        document.querySelectorAll('#session-stepper .session-step').forEach(function (step, i) {
            step.className = 'session-step ' + (i < idx ? 'session-step--done' : i === idx ? 'session-step--current' : '');
        });
        document.querySelectorAll('#session-stepper .session-connector').forEach(function (c, i) {
            c.className = 'session-connector' + (i < idx ? ' session-connector--done' : '');
        });

        var area = document.getElementById('session-status-action');
        if (status === 'planned') {
            area.innerHTML = '<button class="session-launch-btn" onclick="setSessionStatus(\'active\')"><i class="fa-solid fa-circle-play"></i> Lancer la session</button>';
        } else if (status === 'active') {
            area.innerHTML = '<button class="session-end-btn" onclick="setSessionStatus(\'done\')"><i class="fa-solid fa-flag-checkered"></i> Terminer la session</button>';
        } else {
            area.innerHTML = '<span style="color:#8899BB;font-size:13px;display:flex;align-items:center;gap:6px;"><i class="fa-solid fa-box-archive"></i> Session archivée</span>';
        }
    }

    function setSessionStatus(status) {
        sessionStatus = status;
        updateStepper(status);
    }
</script>
