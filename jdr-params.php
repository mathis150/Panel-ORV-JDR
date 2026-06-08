<?php
    require_once "./import/base.php";

    $bases = new WebPageBases();

    $currentPage = $_GET['page'] ?? 'users';
    $currentSubPage = $_GET['sub-page'] ?? 'list';
?>
    <?php $bases->header(); ?>
    <body>
        <?php $bases->navigation(); ?>
        <section class="container container-menus">
            <section class="left-menu">
                <a href="jdr-params?page=users&sub-page=list" class="left-menu_page <?php if($currentPage == 'users') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des utilisateurs</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-user"></i></div>
                </a>
                <a href="jdr-params?page=characters&sub-page=list" class="left-menu_page <?php if($currentPage == 'characters') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des personnages</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-user-injured"></i></div>
                </a>
                <a href="jdr-params?page=constellations&sub-page=list" class="left-menu_page <?php if($currentPage == 'constellations') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des Constellations</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-user-astronaut"></i></div>
                </a>
                <a href="jdr-params?page=capacities&sub-page=list" class="left-menu_page <?php if($currentPage == 'capacities') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des compétences</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-newspaper"></i></div>
                </a>
                <a href="jdr-params?page=stigmata&sub-page=list" class="left-menu_page <?php if($currentPage == 'stigmata') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des stigmates</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-copy"></i></div>
                </a>
                <a href="jdr-params?page=attributes&sub-page=list" class="left-menu_page <?php if($currentPage == 'attributes') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des attributs</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-address-card"></i></div>
                </a>
                <a href="jdr-params?page=histories&sub-page=list" class="left-menu_page <?php if($currentPage == 'histories') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des histoires</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-scroll"></i></div>
                </a>
                <a href="jdr-params?page=modifiers&sub-page=list" class="left-menu_page <?php if($currentPage == 'modifiers') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des modifieurs</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-id-card"></i></div>
                </a>
                <a href="jdr-params?page=scenarios&sub-page=list" class="left-menu_page <?php if($currentPage == 'scenarios') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des scénarios</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-sign-hanging"></i></div>
                </a>
                <a href="jdr-params?page=items&sub-page=list" class="left-menu_page <?php if($currentPage == 'items') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion des objets</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-flask"></i></div>
                </a>
                <a href="jdr-params?page=dokkaebi_bag&sub-page=list" class="left-menu_page <?php if($currentPage == 'dokkaebi_bag') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Gestion du baluchon du Dokkaebi</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-bag-shopping"></i></div>
                </a>
<a href="jdr-params?page=generals&sub-page=list" class="left-menu_page <?php if($currentPage == 'generals') echo 'left-menu_actif'; ?>">
                    <div class="left-menu_page_text">Paramètres généraux</div>
                    <div class="left-menu_page_text" style="font-size: 24px;"><i class="fa-solid fa-gear"></i></div>
                </a>
            </section>
            <section class="right-menu">
                <div class="menu-right-container">
                    <?php
                        try {
                            require_once "./sub-pages/".$currentPage.".".$currentSubPage.".php";
                        } catch(error) {
                            echo "<h3 style='color: #FFF'>Page introuvable.</h3>";
                        }
                    ?>
                </div>
                <?php $bases->footer(); ?>
            </section>
        </section>
    </body>
</html>