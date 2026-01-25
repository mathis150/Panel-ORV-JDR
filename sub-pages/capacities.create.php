<div class="orv-buttons" style="justify-content: flex-end;">
    <button class="orv-edit"><i class="fa-solid fa-check"></i> <span style="font-size: 18px;">Sauvegarder</span></button>
    <button class="orv-edit"><i class="fa-solid fa-xmark"></i> <span style="font-size: 18px;">Annuler</span></button>
</div>
<div class="orv-menu" id="gest-perso">
    <div class="orv-menu_header">&lt;Création d'une capacité&gt;</div>
    <div class="orv-menu_container">
        <div class="orv-menu_form">
            <div>
                <h2 class="classic-title">Informations générales de la capacité :</h2>
                <cite>Quel est sont identité ?</cite>
            </div>
            <div class="orv-menu_form-elements">
                <div class="orv-menu_form-inputs-list">
                    <div class="orv-menu_form-input">
                        <label>Nom de la compétence :</label>
                        <input type="number">
                    </div>
                    <div class="orv-menu_form-input" style="width: 300px;">
                        <label>Rang de la compétence :</label>
                        <select style="width: 300px;">
                            <option value="option1">Rang E</option>
                            <option value="option1">Rang D</option>
                            <option value="option1">Rang C</option>
                            <option value="option1">Rang B</option>
                            <option value="option1">Rang A</option>
                            <option value="option1">Rang S</option>
                        </select>
                    </div>
                    <div class="orv-menu_form-input">
                        <label>Effets de la compétence :</label>
                        <select id="example-multi-select2" data-placeholder="Select options" multiple="multiple">
                            <option value="option1">Damage</option>
                            <option value="option2">Boost</option>
                            <option value="option2">Analyse</option>
                            <option value="option2">Soin</option>
                            <option value="option2">Debuffs</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="orv-menu_form">
            <div>
                <h2 class="classic-title">Gestion des effets :</h2>
                <cite>Effets réelles de la compétence ?</cite>
            </div>
            <div class="orv-menu_form-elements">
                <div class="orv-menu_form-inputs-list">
                    <div class="orv-menu_form-input" style="width: 300px;">
                        <label>Dégâts générés par la compétences :</label>
                    </div>
                </div>
            </div>
            <div class="orv-menu_form-elements">
                <div class="orv-menu_form-inputs-list">
                    <div class="orv-menu_form-input" style="width: 200px;">
                        <label>Type :</label>
                        <select style="width: 200px;">
                            <option value="option1">Addition</option>
                            <option value="option1">Jet de dès</option>
                        </select>
                    </div>
                    <div class="orv-menu_form-input" style="width: 325px;">
                        <label>Psyché du personnage :</label>
                        <textarea></textarea>
                    </div>
                    <div class="orv-menu_form-input" style="width: 325px;">
                        <label>Vertu du personnage :</label>
                        <textarea></textarea>
                    </div>
                    <div class="orv-menu_form-input" style="width: 325px;">
                        <label>Vice du personnage :</label>
                        <textarea></textarea>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="orv-menu_form">
            <div>
                <h2 class="classic-title">Informations relatifs au JDR :</h2>
                <cite>Comment est votre personnage ?</cite>
            </div>
            <div class="orv-menu_form-elements">
                <div class="orv-menu_form-inputs-list">
                    <div class="orv-menu_form-input">
                        <label>Force vital :</label>
                        <input type="number">
                    </div>
                    <div class="orv-menu_form-input">
                        <label>Force physique :</label>
                        <input type="number">
                    </div>
                    <div class="orv-menu_form-input">
                        <label>Agilité :</label>
                        <input type="number">
                    </div>
                    <div class="orv-menu_form-input">
                        <label>Puissance magique :</label>
                        <input type="number">
                    </div>
                </div>
            </div>
            <div class="orv-menu_form-elements">
                <div class="orv-menu_form-inputs-list">
                    <div class="orv-menu_form-input" style="width: 325px;">
                        <label>Constellation sponsor :</label>
                        <select style="width: 300px;">
                            <option value="option1">Option 1</option>
                            <option value="option2">Option 2</option>
                        </select>
                    </div>
                    <div class="orv-menu_form-input" style="width: 325px;">
                        <label>Charactéristiques spéciales :</label>
                        <select id="example-multi-select2" data-placeholder="Select options" multiple="multiple">
                            <option value="option1">Option 1</option>
                            <option value="option2">Option 2</option>
                        </select>
                    </div>
                    <div class="orv-menu_form-input" style="width: 325px;">
                        <label>Compétences :</label>
                        <select id="example-multi-select3" data-placeholder="Select options" multiple="multiple">
                            <option value="option1">Option 1</option>
                            <option value="option2">Option 2</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="orv-menu_form">
            <div>
                <h2 class="classic-title">Description de la compétence :</h2>
                <cite>Décrivez la compétence en détail.</cite>
            </div>
            <div class="orv-menu_form-elements">
                <div class="orv-menu_form-inputs-list">
                    <textarea style="width: calc(100% - 20px); height: 200px;"></textarea>
                </div>
            </div>
        </div>
        <hr>
        <div class="orv-menu_form">
            <div>
                <h2 class="classic-title">Estimations générales :</h2>
                <cite>Qu'a-t-il vécu ?</cite>
            </div>
            <div class="orv-menu_form-elements">
                <div class="orv-menu_form-inputs-list">
                    <textarea style="width: calc(100% - 20px); height: 200px;"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    new MultiSelect(document.getElementById('example-multi-select2'));
    new MultiSelect(document.getElementById('example-multi-select3'));
</script>