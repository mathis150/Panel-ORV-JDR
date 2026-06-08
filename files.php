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
                        <button class="orv-edit" data-target="edit-perso"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button class="button-actif" data-target="info-perso">Votre fiche de personnage</button>
                        <button data-target="inv-perso">Inventaire du personnage</button>
                        <button data-target="gest-perso">Gestion du personnage</button>
                        <button data-target="baluch-dokka">Baluchon du Dokkaebi</button>
                    </div>
                    <div class="orv-menu" id="info-perso">
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

                        <!-- Sidebar latérale (slide-in) -->
                        <div class="shop-sidebar" id="shop-sidebar">
                            <div class="shop-nav-label">Navigation</div>
                            <nav class="shop-nav">
                                <a href="#" class="shop-nav-item shop-nav-active">
                                    <i class="fa-solid fa-house"></i> Accueil
                                </a>
                                <a href="#" class="shop-nav-item">
                                    <i class="fa-solid fa-magnifying-glass"></i> Rechercher un item
                                </a>
                                <a href="#" class="shop-nav-item">
                                    <i class="fa-solid fa-comments"></i> Chat des constellations
                                </a>
                                <a href="#" class="shop-nav-item">
                                    <i class="fa-solid fa-tv"></i> Dokkaebi TV
                                </a>
                                <a href="#" class="shop-nav-item">
                                    <i class="fa-solid fa-bag-shopping"></i> Mes achats
                                </a>
                                <a href="#" class="shop-nav-item">
                                    <i class="fa-solid fa-star"></i> Favoris
                                </a>
                            </nav>
                        </div>
                        <div class="shop-sidebar-overlay" id="shop-sidebar-overlay"></div>

                        <!-- En-tête interne de la boutique -->
                        <div class="shop-header">
                            <button class="shop-hamburger" id="shop-hamburger" aria-label="Menu boutique">
                                <span></span><span></span><span></span>
                            </button>
                            <div class="shop-header-title">Baluchon des Dokkaebi</div>
                            <div class="shop-header-user">
                                <div class="shop-header-user-info">
                                    <div class="shop-header-user-name">Grégoire Carte</div>
                                    <div class="shop-header-user-rank">Rang Iron</div>
                                </div>
                                <div class="shop-header-avatar">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Contenu scrollable -->
                        <div class="shop-layout">

                            <!-- Carte profil utilisateur -->
                            <div class="shop-profile-card">
                                <div class="shop-profile-avatar">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div class="shop-profile-info">
                                    <div class="shop-profile-name">Grégoire Carte</div>
                                    <div class="shop-profile-rank">Rang Iron</div>
                                </div>
                                <div class="shop-profile-stats">
                                    <div class="shop-profile-stat">
                                        <span class="shop-stat-value">2 500</span>
                                        <span class="shop-stat-label">Coins</span>
                                    </div>
                                    <div class="shop-profile-stat">
                                        <span class="shop-stat-value">Iron</span>
                                        <span class="shop-stat-label">Rang</span>
                                    </div>
                                    <button class="shop-profile-cart-btn">
                                        <i class="fa-solid fa-bag-shopping"></i> Voir le panier
                                    </button>
                                </div>
                            </div>

                            <!-- Objets en vedette -->
                            <div>
                                <div class="shop-section-title">Objets en vedette</div>
                                <div class="shop-featured-grid">
                                    <div class="shop-featured-card">
                                        <div class="shop-card-category">Équipement</div>
                                        <div class="shop-card-name">Épée du Vide Céleste</div>
                                        <div class="shop-card-attr-label">Force :</div>
                                        <div class="shop-card-attr-value">Augmente les dégâts physiques</div>
                                        <button class="shop-card-price">2 500 C</button>
                                    </div>
                                    <div class="shop-featured-card">
                                        <div class="shop-card-category">Armure</div>
                                        <div class="shop-card-name">Manteau de la Nuit</div>
                                        <div class="shop-card-attr-label">Défense :</div>
                                        <div class="shop-card-attr-value">Résistance aux dégâts magiques</div>
                                        <button class="shop-card-price">2 500 C</button>
                                    </div>
                                    <div class="shop-featured-card">
                                        <div class="shop-card-category">Consommable</div>
                                        <div class="shop-card-name">Potion de Régénération</div>
                                        <div class="shop-card-attr-label">Soin :</div>
                                        <div class="shop-card-attr-value">Usage unique en combat</div>
                                        <button class="shop-card-price">2 500 C</button>
                                    </div>
                                    <div class="shop-featured-card">
                                        <div class="shop-card-category">Accessoire</div>
                                        <div class="shop-card-name">Anneau de Mana Pur</div>
                                        <div class="shop-card-attr-label">Mana :</div>
                                        <div class="shop-card-attr-value">Régénération de mana passive</div>
                                        <button class="shop-card-price">2 500 C</button>
                                    </div>
                                    <div class="shop-featured-card">
                                        <div class="shop-card-category">Compétence</div>
                                        <div class="shop-card-name">Parchemin de Maîtrise</div>
                                        <div class="shop-card-attr-label">Expérience :</div>
                                        <div class="shop-card-attr-value">Boost d'une compétence au choix</div>
                                        <button class="shop-card-price">2 500 C</button>
                                    </div>
                                    <div class="shop-featured-card">
                                        <div class="shop-card-category">Spécial</div>
                                        <div class="shop-card-name">Fragment de Constellation</div>
                                        <div class="shop-card-attr-label">Probabilité rare :</div>
                                        <div class="shop-card-attr-value">Débloque une aptitude cachée</div>
                                        <button class="shop-card-price">2 500 C</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Catalogue général -->
                            <div>
                                <div class="shop-section-title">Catalogue général</div>
                                <div class="shop-catalog-layout">
                                    <div class="shop-catalog-grid">
                                        <div class="shop-catalog-card">
                                            <div class="shop-catalog-card-subtitle">Consommable</div>
                                            <div class="shop-catalog-card-name">Élixir de Force</div>
                                            <div class="shop-catalog-card-price">2 500 C</div>
                                        </div>
                                        <div class="shop-catalog-card">
                                            <div class="shop-catalog-card-subtitle">Consommable</div>
                                            <div class="shop-catalog-card-name">Eau Sacrée</div>
                                            <div class="shop-catalog-card-price">2 500 C</div>
                                        </div>
                                        <div class="shop-catalog-card">
                                            <div class="shop-catalog-card-subtitle">Matériaux</div>
                                            <div class="shop-catalog-card-name">Pierre de Mana</div>
                                            <div class="shop-catalog-card-price">2 500 C</div>
                                        </div>
                                        <div class="shop-catalog-card">
                                            <div class="shop-catalog-card-subtitle">Matériaux</div>
                                            <div class="shop-catalog-card-name">Acier des Abysses</div>
                                            <div class="shop-catalog-card-price">2 500 C</div>
                                        </div>
                                        <div class="shop-catalog-card">
                                            <div class="shop-catalog-card-subtitle">Consommable</div>
                                            <div class="shop-catalog-card-name">Potion d'Agilité</div>
                                            <div class="shop-catalog-card-price">2 500 C</div>
                                        </div>
                                        <div class="shop-catalog-card">
                                            <div class="shop-catalog-card-subtitle">Parchemin</div>
                                            <div class="shop-catalog-card-name">Sceau d'Identification</div>
                                            <div class="shop-catalog-card-price">2 500 C</div>
                                        </div>
                                        <div class="shop-catalog-card">
                                            <div class="shop-catalog-card-subtitle">Objet clé</div>
                                            <div class="shop-catalog-card-name">Clé du Donjon</div>
                                            <div class="shop-catalog-card-price">2 500 C</div>
                                        </div>
                                        <div class="shop-catalog-card">
                                            <div class="shop-catalog-card-subtitle">Objet clé</div>
                                            <div class="shop-catalog-card-name">Sceau du Dokkaebi</div>
                                            <div class="shop-catalog-card-price">2 500 C</div>
                                        </div>
                                    </div>
                                    <div class="shop-catalog-actions">
                                        <button class="shop-action-btn">Panier</button>
                                        <button class="shop-action-btn">Plus</button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Barre basse de la boutique -->
                        <div class="shop-footer-bar">
                            <div class="shop-coins-display">COINS : 100 000 000 000 C</div>
                            <button class="shop-upgrade-btn">UPGRADE TO GOLD — 1 000 000 C</button>
                            <input class="shop-search-input" type="text" placeholder="Rechercher un item...">
                            <button class="shop-search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>

                    </div>
                    <div class="orv-menu hidden" id="edit-perso">
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