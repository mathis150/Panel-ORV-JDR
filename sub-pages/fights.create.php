<div class="orv-buttons" style="justify-content: flex-end;">
    <button class="orv-edit"><i class="fa-solid fa-check"></i> <span style="font-size: 18px;">Sauvegarder</span></button>
    <button class="orv-edit" onclick="window.location.href='fights?sub-page=list'">
        <i class="fa-solid fa-xmark"></i> <span style="font-size: 18px;">Annuler</span>
    </button>
</div>

<div class="orv-menu" id="fight-create">
    <div class="orv-menu_header">&lt;Nouveau Combat&gt;</div>
    <div class="orv-menu_container">

        <!-- Informations générales -->
        <div class="orv-menu_form">
            <div>
                <h2 class="classic-title">Informations générales :</h2>
                <cite>Nommez le combat et définissez son type.</cite>
            </div>
            <div class="orv-menu_form-elements">
                <div class="orv-menu_form-inputs-list">
                    <div class="orv-menu_form-input" style="width: 400px;">
                        <label>Nom du combat :</label>
                        <input type="text" placeholder="Ex : La Horde du Roi-Démon" style="width: 400px;">
                    </div>
                    <div class="orv-menu_form-input" style="width: 200px;">
                        <label>Type de combat :</label>
                        <select style="width: 200px;">
                            <option value="pve">PvE — Joueurs vs Mobs</option>
                            <option value="pvp">PvP — Joueur vs Joueur</option>
                            <option value="mixed">Mixte — PvE + PvP</option>
                        </select>
                    </div>
                    <div class="orv-menu_form-input">
                        <label>Session liée :</label>
                        <select style="width: 260px;">
                            <option value="">— Aucune —</option>
                            <option value="s1" selected>Le Premier Scénario</option>
                            <option value="s2">La Tour du Roi-Démon</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <!-- Joueurs participants -->
        <div class="orv-menu_form">
            <div>
                <h2 class="classic-title">Joueurs participants :</h2>
                <cite>Les personnages joueurs impliqués dans ce combat.</cite>
            </div>
            <div class="orv-menu_form-elements">
                <div class="orv-menu_form-inputs-list">
                    <div class="orv-menu_form-input" style="width: 360px;">
                        <label>Personnages :</label>
                        <select id="fc-players" data-placeholder="Sélectionner des personnages" multiple="multiple">
                            <option value="p1">Kim Dokja (PV : 450)</option>
                            <option value="p2">Yoo Joonghyuk (PV : 600)</option>
                            <option value="p3">Jung Heewon (PV : 380)</option>
                            <option value="p4">Lee Hyunsung (PV : 520)</option>
                            <option value="p5">Lee Jihye (PV : 340)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <!-- Adversaires existants -->
        <div class="orv-menu_form">
            <div>
                <h2 class="classic-title">Adversaires configurés :</h2>
                <cite>Monstres et PNJ déjà enregistrés dans la base de données.</cite>
            </div>
            <div class="orv-menu_form-elements">
                <div class="orv-menu_form-inputs-list">
                    <div class="orv-menu_form-input" style="width: 360px;">
                        <label>Adversaires :</label>
                        <select id="fc-mobs" data-placeholder="Sélectionner des adversaires" multiple="multiple">
                            <option value="m1">Golem Ancien (PV : 500 / ATK : 95)</option>
                            <option value="m2">Spectre de Hyehwa (PV : 200 / ATK : 60)</option>
                            <option value="m3">Larve Mutante (PV : 150 / ATK : 45)</option>
                            <option value="m4">Dokkaebi de Bronze (PV : 350 / ATK : 70)</option>
                            <option value="m5">Roi des Larves (PV : 800 / ATK : 140)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <!-- Quicksetup nouveaux mobs -->
        <div class="orv-menu_form" style="align-items: stretch;">
            <div>
                <h2 class="classic-title">Configuration rapide :</h2>
                <cite>Créez des adversaires inédits sans passer par la gestion des monstres.</cite>
            </div>
            <div style="width: 100%;">
                <div id="mob-quicksetup-container"></div>
                <button class="fight-add-mob-btn" onclick="addMobCard()">
                    <i class="fa-solid fa-plus"></i> Ajouter un adversaire (quicksetup)
                </button>
            </div>
        </div>

        <hr>

        <!-- Notes de combat -->
        <div class="orv-menu_form">
            <div>
                <h2 class="classic-title">Notes de préparation :</h2>
                <cite>Contexte, déclencheurs, objectifs secondaires…</cite>
            </div>
            <div class="orv-menu_form-elements" style="grid-template-columns: 1fr;">
                <div class="orv-menu_form-inputs-list">
                    <div class="orv-menu_form-input" style="width: 100%;">
                        <label>Notes MJ :</label>
                        <textarea class="session-notes" style="min-height: 120px;" placeholder="Ex : Les Spectres fuient si le Roi des Larves est éliminé. Bonus de 200 coins si aucun joueur n'est KO."></textarea>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    new MultiSelect(document.getElementById('fc-players'));
    new MultiSelect(document.getElementById('fc-mobs'));

    var mobCount = 0;

    function addMobCard() {
        mobCount++;
        var id = 'mob-qs-' + mobCount;
        var card = document.createElement('div');
        card.className = 'fight-mob-quicksetup';
        card.id = id;
        card.innerHTML =
            '<div class="fight-mob-quicksetup-header">' +
                '<input type="text" placeholder="Nom de l\'adversaire" style="min-height: 36px; padding: 0 12px; flex: 1;">' +
                '<button class="fight-mob-remove" onclick="document.getElementById(\'' + id + '\').remove()" title="Supprimer"><i class="fa-solid fa-xmark"></i></button>' +
            '</div>' +
            '<div class="fight-mob-stats-row">' +
                '<div class="fight-mob-stat-field"><label>PV Max</label><input type="number" placeholder="500" min="1"></div>' +
                '<div class="fight-mob-stat-field"><label>ATK</label><input type="number" placeholder="80" min="0"></div>' +
                '<div class="fight-mob-stat-field"><label>DEF</label><input type="number" placeholder="40" min="0"></div>' +
                '<div class="fight-mob-stat-field"><label>VIT</label><input type="number" placeholder="12" min="0"></div>' +
                '<div class="fight-mob-stat-field"><label>Initiative</label><input type="number" placeholder="10" min="1" max="99"></div>' +
            '</div>' +
            '<div class="orv-menu_form-input" style="width: 100%;">' +
                '<label>Compétences / Capacités spéciales :</label>' +
                '<textarea class="session-notes" style="min-height: 70px;" placeholder="Ex : Coup de griffe (50 dégâts), Rugissement (debuff DEF -20%)…"></textarea>' +
            '</div>';
        document.getElementById('mob-quicksetup-container').appendChild(card);
    }
</script>
