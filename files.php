<?php
    require_once("./import/base.php");

    $bases = new WebPageBases();

?>
    <?php $bases->header(); ?>
    <style>
        .container-menus {
            display: grid;

            grid-template-columns: 300px auto;
        }
        .left-menu {
            background: #576B9A;

            display: flex;
            flex-direction: column;
            align-items: center;
            flex-shrink: 0;
        }
        .left-menu_button, .left-menu_button:link, .left-menu_button:visited {
            background: #FFFFFF;
            color: black;
            -webkit-text-fill-color: initial;
            text-decoration: none;

            cursor: pointer;

            border: none;
            border-radius: 12px;

            padding: 12px 25px;
            margin: 40px 0;
        }
        .left-menu_button:hover {
            background: #DDDDDD;
        }
        .left-menu_page, .left-menu_page:link, .left-menu_page:visited {
            background: #50638E;

            width: 100%;
            height: 60px;

            display: grid;
            grid-template-columns: 220px 80px;
        }
        .left-menu_page:nth-child(even) {
            background: #46577E;
        }
        .left-menu_actif {
            background: #3D4C6F !important;
        }
        .left-menu_dead {
            background: #8E5050 !important;
        }
        .left-menu_page_text, .left-menu_page_text:link, .left-menu_page_text:visited, .left-menu_page_text:hover, .left-menu_page_text:active {
            font-size: 14px;

            background: var(--Important-text-color-2, linear-gradient(180deg, #FFF 0%, #999 190%)) !important;
            background-clip: text !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;

            display: flex;
            justify-content: center;
            align-items: center;
        }
        .left-menu_page:hover {
            background: #3e4f75ff;
        }
        .left-menu_actif:hover {
            background: #313d59ff !important;
        }
        .left-menu_dead:hover {
            background: #703f3fff !important;
        }
        .menu-right-container {
            height: calc(100% - 72px);

            overflow-y: auto;
            overflow-x: hidden;

            display: flex;
            justify-content: center;
            align-items: center;
        }
        .orv-menu {
            width: 1225px;

            border-radius: 16px 0;
            border: 2px solid #D9FFFF;

            background: rgba(55, 174, 254, 0.80);
            color: #FFFFFF;
        }
        .orv-menu_header {
            height: 60px;
            width: 100%;

            font-size: 24px;

            background: rgba(255, 255, 255, 0.25);
            font-weight: 700;
            color: #D9FFFF;

            display: flex;
            justify-content: center;
            align-items: center;
        }
        .orv-menu_double-grid {
            display: grid;
            grid-template-columns: 50% 50%;
        }
        .orv-menu_stats-general {
            padding: 20px;

            color: #D9FFFF;
        }
        .orv-menu_stats-personnals {
            background: rgba(255, 255, 255, 0.15);

            color: #D9FFFF;
        }
        .orv-menu_stats-personnals_content {
            padding: 20px;
        }
        .orv-menu_stats_list {
            background: rgba(255, 255, 255, 0.15);

            margin-top: 20px;
        }
        .orv-menu_stats_list:nth-child(even) {
            background: rgba(255, 255, 255, 0.05);
        }
        .orv-menu_stats_header {
            padding: 20px 14px;
            font-weight: 700;

            font-size: 18px;
        }
        .orv-menu_stats_list_buttons {
            display: flex;
            flex-wrap: wrap;
            flex-direction: row;
            row-gap: 5px;
        }
        .orv-menu_stats_info {
            min-height: 60px;
            min-width: 204.16px;
            width: 100%;
            flex: 1 1 0;

            cursor: pointer;

            display: flex;
            justify-content: center;
            align-items: center;

            font-size: 16px;

            background: rgba(255, 255, 255, 0.10);
        }
        .orv-menu_stats_info:nth-child(even) {
            background: rgba(255, 255, 255, 0.20);
        }
        .orv-menu_stats_info:hover {
            background: rgba(255, 255, 255, 0.25);
        }
    </style>
    <body>
        <?php $bases->navigation(); ?>
        <section class="container container-menus">
            <section class="left-menu">
                <a href="#" class="left-menu_button">
                    Créer une nouvelle fiche
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
            <section>
                <div class="menu-right-container">
                    <div class="orv-menu">
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