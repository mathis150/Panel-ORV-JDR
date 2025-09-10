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
                </div>
                <?php $bases->footer(); ?>
            </section>
        </section>
    </body>
</html>