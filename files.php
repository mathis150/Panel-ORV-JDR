<?php
    require_once("./import/base.php");

    $bases = new WebPageBases();

?>
    <?php $bases->header(); ?>
    <body>
        <?php $bases->navigation(); ?>
        <section class="container container-menus">
            <section class="left-menu">
                <a href="#" class="left-menu_button">
                    <i class="fa-solid fa-plus"></i> Créer une nouvelle fiche
                </a>
                <a href="#" class="left-menu_page">
                    <div class="left-menu_page_text">Nom du personnage</div>
                    <div class="left-menu_page_text">Lvl.99</div>
                </a>
                <a href="#" class="left-menu_page">
                    <div class="left-menu_page_text">Nom du personnage</div>
                    <div class="left-menu_page_text">Lvl.99</div>
                </a>
                <a href="#" class="left-menu_page left-menu_actif">
                    <div class="left-menu_page_text">Nom du personnage (Actif)</div>
                    <div class="left-menu_page_text">Lvl.99</div>
                </a>
                <a href="#" class="left-menu_page">
                    <div class="left-menu_page_text">Nom du personnage</div>
                    <div class="left-menu_page_text">Lvl.99</div>
                </a>
                <a href="#" class="left-menu_page left-menu_dead">
                    <div class="left-menu_page_text">Nom du personnage (Dead)</div>
                    <div class="left-menu_page_text">Lvl.99</div>
                </a>
            </section>
            <section class="right-menu">
                <div class="data-popup hidden">
                    <div class="data-popup_popup">
                        <div class="data-popup_popup-header">
                            &lt;Information de la compétence&gt;
                            <button class="button-close data-popup_close-position">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <div class="data-popup_popup-body_grid">
                            <div class="data-popup_popup-body_content">
                                <b>Nom de la compétence :</b> &lt;Nom&gt;<br>
                                <br>
                                <b>Description :</b><br>
                                XXX
                            </div>
                            <div class="data-popup_popup-body_content">
                                <b>Niveau :</b> 1/10<br>
                                <b>Coût en mana :</b>  XX<br>
                                <b>Dégât causés :</b>  XX ou [XdXX]<br>
                                <br>
                                [Prochain niveau dans : XX/XX] (A)
                            </div>
                        </div>
                    </div>
                </div>
                <div class="menu-right-container">
                    <div class="orv-buttons">
                        <button class="orv-edit"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button class="button-actif">Votre fiche de personnage</button>
                        <button>Inventaire du personnage</button>
                        <button>Gestion du personnage</button>
                        <button>Baluchon du Dokkaebi</button>
                    </div>
                    <div class="orv-menu hidden" id="info-perso">
                        <div class="orv-menu_header">&lt;Information du personnage&gt;</div>
                        <div class="orv-menu_double-grid">
                            <div class="orv-menu_stats-general">
                                <b>Nom :</b> &lt;Nom&gt; &lt;Prénom&gt;<br>
                                <b>Âge :</b> XXX ans (XX/XX/XXXX)<br>
                                <b>Nationnalité :</b> XXX<br>
                                <b>Métier :</b> XXX<br>
                                <b>Constellation sponsor :</b> XXXXXXXXX<br>
                                <br>
                                <b>Charactéristique spécial :</b> XXXXX (XXX)<br>
                                <b>Vertu :</b> XXXXX<br>
                                <b>Vice :</b> XXXXX<br>
                                <br>
                                <b>Apparence :</b><br>
                                Lorem ipsum dolor sit amet.<br>
                                <br>
                                <b>Psyché :</b><br>
                                Lorem ipsum dolor sit amet.<br>
                                <br>
                                <b>Vertu :</b> XXXXX<br>
                                <b>Vice :</b> XXXXX<br>
                                <br>
                                <b>Histoire :</b><br>
                                Lorem ipsum dolor sit amet.<br>
                                <br>
                                <b>Estimation générales (A) :</b><br>
                                Lorem ipsum dolor sit amet.<br>
                            </div>
                            <div class="orv-menu_stats-personnals">
                                <div class="orv-menu_stats-personnals_content">
                                    <b>Coins en banque :</b> XXXXXX C<br>
                                    <br>
                                    <b>Compétences générales :</b><br>
                                    [Vitalité : Niveau XX],<br>
                                    [Force physique : Niveau XX],<br>
                                    [Agilité : Niveau XX],<br>
                                    [Pouvoir magiques : Niveau XX]<br>
                                    <br>
                                    [HP : 20/20], [MP : 20/20], [Déplacement : 14m]<br>
                                    <br>
                                    <b>Distance de saut :</b> [Avec élan : Xm], [Sans élan : Xm], [Hauteur : Xm]
                                </div>

                                <div class="orv-menu_stats_list">
                                    <div class="orv-menu_stats_header">Attribut personnels :</div>
                                    <div class="orv-menu_stats_list_buttons">
                                        <div class="orv-menu_stats_info">[Compétence, Lv X]</div>
                                        <div class="orv-menu_stats_info">[Compétence, Lv X]</div>
                                        <div class="orv-menu_stats_info">[Compétence, Lv X]</div>
                                        <div class="orv-menu_stats_info">[Compétence, Lv X]</div>
                                    </div>
                                </div>

                                <div class="orv-menu_stats_list">
                                    <div class="orv-menu_stats_header">Attribut personnels :</div>
                                    <div class="orv-menu_stats_list_buttons">
                                        <div class="orv-menu_stats_info">[Compétence, Lv X]</div>
                                        <div class="orv-menu_stats_info">[Compétence, Lv X]</div>
                                        <div class="orv-menu_stats_info">[Compétence, Lv X]</div>
                                        <div class="orv-menu_stats_info">[Compétence, Lv X]</div>
                                    </div>
                                </div>

                                <div class="orv-menu_stats_list">
                                    <div class="orv-menu_stats_header">Attribut personnels :</div>
                                    <div class="orv-menu_stats_list_buttons">
                                        <div class="orv-menu_stats_info">[Compétence, Lv X]</div>
                                        <div class="orv-menu_stats_info">[Compétence, Lv X]</div>
                                        <div class="orv-menu_stats_info">[Compétence, Lv X]</div>
                                        <div class="orv-menu_stats_info">[Compétence, Lv X]</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="orv-menu hidden" id="inv-perso">
                        <div class="orv-menu_header">&lt;Inventaire du personnage&gt;</div>
                        <div class="orv-menu_double-grid">
                            <div class="orv-menu_equipement">
                                <div class="orv-menu_equipement_title"><h2>Éléments équipés</h2></div>
                                <div class="orv-menu_equipement-item">
                                    <div class="orv-menu_content-center"><img src="./img/icons/sword.svg" width="40px"></div>
                                    <div class="orv-menu_content-center">
                                        <h3>Nom de l'équipement [Force +XX; Mana +XX]</h3>
                                    </div>
                                </div>
                                <div class="orv-menu_equipement-item">
                                    <div class="orv-menu_content-center"><img src="./img/icons/helmet.svg" width="40px"></div>
                                    <div class="orv-menu_content-center">
                                        <h3>Nom de l'équipement [Force +XX; Mana +XX]</h3>
                                    </div>
                                </div>
                                <div class="orv-menu_equipement-item">
                                    <div class="orv-menu_content-center"><img src="./img/icons/chestplate.svg" width="40px"></div>
                                    <div class="orv-menu_content-center">
                                        <h3>Nom de l'équipement [Force +XX; Mana +XX]</h3>
                                    </div>
                                </div>
                                <div class="orv-menu_equipement-item">
                                    <div class="orv-menu_content-center"><img src="./img/icons/gants.svg" width="40px"></div>
                                    <div class="orv-menu_content-center">
                                        <h3>Nom de l'équipement [Force +XX; Mana +XX]</h3>
                                    </div>
                                </div>
                                <div class="orv-menu_equipement-item">
                                    <div class="orv-menu_content-center"><img src="./img/icons/legging.svg" width="40px"></div>
                                    <div class="orv-menu_content-center">
                                        <h3>Nom de l'équipement [Force +XX; Mana +XX]</h3>
                                    </div>
                                </div>
                                <div class="orv-menu_equipement-item">
                                    <div class="orv-menu_content-center"><img src="./img/icons/boots.svg" width="40px"></div>
                                    <div class="orv-menu_content-center">
                                        <h3>Nom de l'équipement [Force +XX; Mana +XX]</h3>
                                    </div>
                                </div>
                                <div class="orv-menu_equipement-item">
                                    <div class="orv-menu_content-center"><img src="./img/icons/star.svg" width="40px"></div>
                                    <div class="orv-menu_content-center">
                                        <h3>Nom de l'équipement [Force +XX; Mana +XX]</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="orv-menu_stats-personnals">
                                <div class="orv-menu_equipement_title"><h2>Vos poches</h2></div>
                                <div class="orv-menu_equipement-item">
                                    <div class="orv-menu_content-center"><img src="./img/icons/sword.svg" width="40px"></div>
                                    <div class="orv-menu_content-center">
                                        <h3>Nom de l'équipement [Force +XX; Mana +XX]</h3>
                                    </div>
                                </div>
                                <div class="orv-menu_equipement-item">
                                    <div class="orv-menu_content-center"><img src="./img/icons/helmet.svg" width="40px"></div>
                                    <div class="orv-menu_content-center">
                                        <h3>Nom de l'équipement [Force +XX; Mana +XX]</h3>
                                    </div>
                                </div>
                                <div class="orv-menu_equipement-item">
                                    <div class="orv-menu_content-center"><img src="./img/icons/chestplate.svg" width="40px"></div>
                                    <div class="orv-menu_content-center">
                                        <h3>Nom de l'équipement [Force +XX; Mana +XX]</h3>
                                    </div>
                                </div>
                                <div class="orv-menu_equipement-item">
                                    <div class="orv-menu_content-center"><img src="./img/icons/gants.svg" width="40px"></div>
                                    <div class="orv-menu_content-center">
                                        <h3>Nom de l'équipement [Force +XX; Mana +XX]</h3>
                                    </div>
                                </div>
                                <div class="orv-menu_equipement-item">
                                    <div class="orv-menu_content-center"><img src="./img/icons/legging.svg" width="40px"></div>
                                    <div class="orv-menu_content-center">
                                        <h3>Nom de l'équipement [Force +XX; Mana +XX]</h3>
                                    </div>
                                </div>
                                <div class="orv-menu_equipement-item">
                                    <div class="orv-menu_content-center"><img src="./img/icons/boots.svg" width="40px"></div>
                                    <div class="orv-menu_content-center">
                                        <h3>Nom de l'équipement [Force +XX; Mana +XX]</h3>
                                    </div>
                                </div>
                                <div class="orv-menu_equipement-item">
                                    <div class="orv-menu_content-center"><img src="./img/icons/star.svg" width="40px"></div>
                                    <div class="orv-menu_content-center">
                                        <h3>Nom de l'équipement [Force +XX; Mana +XX]</h3>
                                    </div>
                                </div>
                                <div class="orv-menu_equipement-item">
                                    <div class="orv-menu_content-center"><img src="./img/icons/consum.svg" width="40px"></div>
                                    <div class="orv-menu_content-center">
                                        <h3>Xx Nom du consommable</h3>
                                    </div>
                                </div>
                                <div class="orv-menu_equipement-item">
                                    <div class="orv-menu_content-center"><img src="./img/icons/materials.svg" width="40px"></div>
                                    <div class="orv-menu_content-center">
                                        <h3>Xx Nom du matériaux</h3>
                                    </div>
                                </div>
                                <div class="orv-menu_equipement-item">
                                    <div class="orv-menu_content-center"><img src="./img/icons/key.svg" width="40px"></div>
                                    <div class="orv-menu_content-center">
                                        <h3>Xx Nom de l'objet clés</h3>
                                    </div>
                                </div>
                                <div class="orv-menu_equipement-item">
                                    <div class="orv-menu_content-center"><img src="./img/icons/lamp.svg" width="40px"></div>
                                    <div class="orv-menu_content-center">
                                        <h3>Xx Nom de l'objet simple</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="orv-menu hidden" id="gest-perso">
                        <div class="orv-menu_header">&lt;Fenêtre de gestion du personnage&gt;</div>
                        <div class="orv-menu_container">
                            <div class="orv-menu_coins">
                                <b>Coins en banque :</b> 0 C
                            </div>
                            <div class="orv-menu_form">
                                <div>
                                    <h2 class="classic-title">Gestion des compétences générales :</h2>
                                    <cite>Pour quand vous utilisez un sort ou prennez des dégâts, et inversement quand vous régénérez ! (Ne dépasse pas les limites, le MJ te vois.)</cite>
                                </div>
                                <div class="orv-menu_form-elements">
                                    <div class="orv-menu_form-inputs-list">
                                        <div class="orv-menu_form-input">
                                            <label>Ajouter des HPs :</label>
                                            <input type="number">
                                        </div>
                                        <div class="orv-menu_form-input">
                                            <label>Retirer des HPs :</label>
                                            <input type="number">
                                        </div>
                                        <div class="orv-menu_form-input">
                                            <label>Ajouter des MPs :</label>
                                            <input type="number">
                                        </div>
                                        <div class="orv-menu_form-input">
                                            <label>Retirer des MPs :</label>
                                            <input type="number">
                                        </div>
                                    </div>
                                    <div class="orv-menu_form-valid"><button>Valider</button></div>
                                </div>
                            </div>
                            <hr>
                            <div class="orv-menu_form">
                                <div>
                                    <h2 class="classic-title">Augmenter les compétences générales :</h2>
                                    <cite>Pour faire évoluer votre personnage ! (Attention : Coûte des pièces, vous ne pouvez pas être en négatif.)</cite>
                                </div>
                                <div class="orv-menu_form-elements">
                                    <div class="orv-menu_form-inputs-list">
                                        <div class="orv-menu_form-input">
                                            <label>Force vitale :</label>
                                            <input type="number">
                                            <cite>Niveau actuelle : X</cite>
                                        </div>
                                        <div class="orv-menu_form-input">
                                            <label>Force physique :</label>
                                            <input type="number">
                                            <cite>Niveau actuelle : X</cite>
                                        </div>
                                        <div class="orv-menu_form-input">
                                            <label>Agilité :</label>
                                            <input type="number">
                                            <cite>Niveau actuelle : X</cite>
                                        </div>
                                        <div class="orv-menu_form-input">
                                            <label>Force magique :</label>
                                            <input type="number">
                                            <cite>Niveau actuelle : X</cite>
                                        </div>
                                    </div>
                                    <div class="orv-menu_form-valid"><button>Valider</button></div>
                                </div>
                                <div class="orv-menu_form-info"><b>Prix total à payer :</b> <span>0</span> C</div>
                            </div>
                        </div>
                    </div>
                    <div class="orv-menu hidden" id="baluch-dokka">
                        <div class="orv-menu_header">&lt;Baluchon des Dokkaebi&gt;</div>
                        <div class="orv-menu_container">
                            <center>Pas disponible.</center>
                        </div>
                    </div>
                    <div class="orv-menu" id="edit-perso">
                        <div class="orv-menu_header">&lt;Fenêtre d'édition du personnage&gt;</div>
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
                                    <div class="orv-menu_form-valid"><button>Sauvegarder</button></div>
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
                                    <div class="orv-menu_form-valid"><button>Sauvegarder</button></div>
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
                                    <div class="orv-menu_form-valid" style="padding-top: 0px;"><button>Sauvegarder</button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $bases->footer(); ?>
            </section>
        </section>
    </body>
</html>