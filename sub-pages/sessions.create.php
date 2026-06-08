<div class="orv-buttons" style="justify-content: flex-end;">
    <button class="orv-edit"><i class="fa-solid fa-check"></i> <span style="font-size: 18px;">Sauvegarder</span></button>
    <button class="orv-edit" onclick="window.location.href='sessions?sub-page=list'">
        <i class="fa-solid fa-xmark"></i> <span style="font-size: 18px;">Annuler</span>
    </button>
</div>

<div class="orv-menu" id="session-create">
    <div class="orv-menu_header">&lt;Nouvelle Session&gt;</div>
    <div class="orv-menu_container">

        <div class="orv-menu_form">
            <div>
                <h2 class="classic-title">Informations générales :</h2>
                <cite>Les détails principaux de la session à venir.</cite>
            </div>
            <div class="orv-menu_form-elements">
                <div class="orv-menu_form-inputs-list">
                    <div class="orv-menu_form-input" style="width: 380px;">
                        <label>Titre de la session :</label>
                        <input type="text" placeholder="Ex : La Nuit du Roi-Démon" style="width: 380px;">
                    </div>
                    <div class="orv-menu_form-input">
                        <label>Date prévue :</label>
                        <input type="date">
                    </div>
                    <div class="orv-menu_form-input" style="width: 280px;">
                        <label>Scénario associé :</label>
                        <select style="width: 280px;">
                            <option value="">— Aucun —</option>
                            <option value="arc1">Arc I – Survivre</option>
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
                <cite>Un résumé de ce qui est prévu pour cette session.</cite>
            </div>
            <div class="orv-menu_form-elements" style="grid-template-columns: 1fr;">
                <div class="orv-menu_form-inputs-list">
                    <div class="orv-menu_form-input" style="width: 100%;">
                        <label>Synopsis :</label>
                        <textarea class="session-notes" style="min-height: 110px;" placeholder="Décrivez le déroulement prévu de la session…"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="orv-menu_form">
            <div>
                <h2 class="classic-title">Notes de préparation :</h2>
                <cite>Notes internes MJ — PNJ, événements, rebondissements…</cite>
            </div>
            <div class="orv-menu_form-elements" style="grid-template-columns: 1fr;">
                <div class="orv-menu_form-inputs-list">
                    <div class="orv-menu_form-input" style="width: 100%;">
                        <label>Notes :</label>
                        <textarea class="session-notes" style="min-height: 180px;" placeholder="Vos notes de préparation…"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="orv-menu_form">
            <div>
                <h2 class="classic-title">Liaisons :</h2>
                <cite>Les personnages, groupes et éléments impliqués dans cette session.</cite>
            </div>
            <div class="orv-menu_form-elements">
                <div class="orv-menu_form-inputs-list">
                    <div class="orv-menu_form-input" style="width: 325px;">
                        <label>Personnages liés :</label>
                        <select id="sc-characters" data-placeholder="Sélectionner des personnages" multiple="multiple">
                            <option value="dokja">Kim Dokja</option>
                            <option value="joonghyuk">Yoo Joonghyuk</option>
                            <option value="heewon">Jung Heewon</option>
                            <option value="hyunsung">Lee Hyunsung</option>
                            <option value="jihye">Lee Jihye</option>
                        </select>
                    </div>
                    <div class="orv-menu_form-input" style="width: 325px;">
                        <label>Groupes liés :</label>
                        <select id="sc-groups" data-placeholder="Sélectionner des groupes" multiple="multiple">
                            <option value="g1">Groupe de Survie de Dokja</option>
                            <option value="g2">Congrès des Survivants</option>
                            <option value="g3">Alliance des Constellations</option>
                            <option value="g4">Armée du Fleuve Han</option>
                        </select>
                    </div>
                    <div class="orv-menu_form-input" style="width: 325px;">
                        <label>Objets importants :</label>
                        <select id="sc-items" data-placeholder="Sélectionner des objets" multiple="multiple">
                            <option value="i1">Épée de la Réincarnation</option>
                            <option value="i2">Armure du Roi des Démons</option>
                            <option value="i3">Baguette du Dokkaebi</option>
                        </select>
                    </div>
                    <div class="orv-menu_form-input" style="width: 325px;">
                        <label>Compétences impliquées :</label>
                        <select id="sc-capacities" data-placeholder="Sélectionner des compétences" multiple="multiple">
                            <option value="c1">Lecture Omnisciente</option>
                            <option value="c2">Souverain des Étoiles</option>
                            <option value="c3">Tournant de l'Histoire</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    new MultiSelect(document.getElementById('sc-characters'));
    new MultiSelect(document.getElementById('sc-groups'));
    new MultiSelect(document.getElementById('sc-items'));
    new MultiSelect(document.getElementById('sc-capacities'));
</script>
