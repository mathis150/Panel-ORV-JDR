<?php
    require_once("./import/base.php");

    $bases = new WebPageBases();

?>
    <?php $bases->header(); ?>
    <body>
        <?php $bases->navigation(); ?>
        <section class="container container-menus">
            <section class="left-menu">
                <a href="jdr-params?page=users&sub-page=list" class="left-menu_page">
                    <div class="left-menu_page_text">Gestion des utilisateurs</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-user"></i></div>
                </a>
                <a href="jdr-params?page=characters&sub-page=list" class="left-menu_page">
                    <div class="left-menu_page_text">Gestion des personnages</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-user-injured"></i></div>
                </a>
                <a href="jdr-params?page=constellations&sub-page=list" class="left-menu_page">
                    <div class="left-menu_page_text">Gestion des Constellations</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-user-astronaut"></i></div>
                </a>
                <a href="jdr-params?page=capacities&sub-page=list" class="left-menu_page">
                    <div class="left-menu_page_text">Gestion des compétences</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-newspaper"></i></div>
                </a>
                <a href="jdr-params?page=stigmata&sub-page=list" class="left-menu_page">
                    <div class="left-menu_page_text">Gestion des stigmates</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-copy"></i></div>
                </a>
                <a href="jdr-params?page=attributes&sub-page=list" class="left-menu_page">
                    <div class="left-menu_page_text">Gestion des attributs</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-address-card"></i></div>
                </a>
                <a href="jdr-params?page=histories&sub-page=list" class="left-menu_page">
                    <div class="left-menu_page_text">Gestion des histoires</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-scroll"></i></div>
                </a>
                <a href="jdr-params?page=modifiers&sub-page=list" class="left-menu_page">
                    <div class="left-menu_page_text">Gestion des modifieurs</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-id-card"></i></div>
                </a>
                <a href="jdr-params?page=scenarios&sub-page=list" class="left-menu_page">
                    <div class="left-menu_page_text">Gestion des scénarios</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-sign-hanging"></i></div>
                </a>
                <a href="jdr-params?page=items&sub-page=list" class="left-menu_page">
                    <div class="left-menu_page_text">Gestion des objets</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-flask"></i></div>
                </a>
                <a href="jdr-params?page=dokkaebi_bag&sub-page=list" class="left-menu_page">
                    <div class="left-menu_page_text">Gestion du baluchon du Dokkaebi</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-bag-shopping"></i></div>
                </a>
                <a href="jdr-params?page=generals&sub-page=list" class="left-menu_page">
                    <div class="left-menu_page_text">Paramètres généraux</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-gear"></i></div>
                </a>
            </section>
            <section class="right-menu">
                <div class="menu-right-container">
                    <?php
                        if(empty($_GET['page'])) {
                            try {
                                require_once("./sub-pages/users.list.php");
                            } catch(error) {
                                echo "Page introuvable.";
                            }
                        }else{
                            try {
                                if($_GET['sub-page']){$_GET['sub-page'];}

                                require_once("./sub-pages/".$_GET['page'].".".$_GET['sub-page'].".php");
                            } catch(error) {
                                echo "<h3 style='color: #FFF'>Page introuvable.</h3>";
                            }
                        }
                    ?>
                </div>
                <?php $bases->footer(); ?>
            </section>
        </section>
    </body>
</html>