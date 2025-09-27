<div class="orv-buttons" style="justify-content: flex-end;">
    <button class="orv-edit"><i class="fa-solid fa-check"></i> <span style="font-size: 18px;">Sauvegarder</span></button>
    <button class="orv-edit"><i class="fa-solid fa-xmark"></i> <span style="font-size: 18px;">Annuler</span></button>
</div>
<div class="orv-menu" id="gest-perso">
    <div class="orv-menu_header">&lt;Création d'un personnage&gt;</div>
    <div class="orv-menu_container">
        <div class="orv-menu_form">
            <div>
                <h2 class="classic-title">Informations générales du personnage :</h2>
                <cite>Quel est sont identité ?</cite>
            </div>
            <div class="orv-menu_form-elements">
                <div class="orv-menu_form-inputs-list">
                    <div class="orv-menu_form-input">
                        <label>Nom du personnage :</label>
                        <input type="number">
                    </div>
                    <div class="orv-menu_form-input">
                        <label>Prénom du personnage :</label>
                        <input type="number">
                    </div>
                    <div class="orv-menu_form-input">
                        <label>Date d'anniversaire du personnage :</label>
                        <input type="number">
                    </div>
                    <div class="orv-menu_form-input">
                        <label>Nationnalité du personnage :</label>
                        <input type="number">
                    </div>
                    <div class="orv-menu_form-input">
                        <label>Métier du personnage :</label>
                        <input type="number">
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="orv-menu_form">
            <div>
                <h2 class="classic-title">Psychologie du personnage :</h2>
                <cite>Comment est votre personnage ?</cite>
            </div>
            <div class="orv-menu_form-elements">
                <div class="orv-menu_form-inputs-list">
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
                <h2 class="classic-title">Histoire :</h2>
                <cite>Qu'a-t-il vécu ?</cite>
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
                        <select>
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
    </div>
</div>
<script>
    new MultiSelect(document.getElementById('example-multi-select2'));
    new MultiSelect(document.getElementById('example-multi-select3'));
</script>