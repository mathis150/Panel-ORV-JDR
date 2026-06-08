<?php
    require_once "./import/base.php";
    $bases = new WebPageBases();
    $currentSubPage = $_GET['sub-page'] ?? 'list';
?>
    <?php $bases->header(); ?>
    <body>
        <?php $bases->navigation(); ?>
        <section class="container container-menus">
            <section class="right-menu" style="grid-column: 1 / -1;">
                <div class="menu-right-container">
                    <?php
                        try {
                            require_once "./sub-pages/fights.".$currentSubPage.".php";
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
