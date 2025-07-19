<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Connexion - ORV JDR</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Koh+Santepheap:wght@100;300;400;700;900&display=swap" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900italic,900' rel='stylesheet' type='text/css'>
        <link href="./css/style.css" rel="stylesheet">

        <style>
            /* Nommation : <type>_<subtype>-<variation> */

            /*1. Bases*/
            body {
                margin: 0;
                padding : 0;

                font-family: 'Roboto', sans-serif;

                background: linear-gradient(107deg, #26314F 1.63%, #2B3758 17.13%, #2D3A5D 32.22%, #313F65 50.56%, #384874 78.71%, #44588E 95.17%, #5770B5 100.35%);
            }
            .container {
                width: 100%;
                height: calc(100vh - 72px);
            }
            .container-center {
                display: flex;
                justify-content: center;
                align-items: center;
            }
            hr {}

            /*2. Textes*/
                h1,h2,h3,h4,h5,h6,p {
                    margin-block-start: 0;
                    margin-block-end: 0;
                }
                /*Titres*/
                h1 {
                    font-size: 32px;
                    font-family: "Koh Santepheap";
                }
                h2 {
                    font-size: 24px;
                    font-family: "Koh Santepheap";
                }
                h3 {
                    font-size: 18px;
                    font-family: "Koh Santepheap";
                }
                h4 {
                    font-size: 16px;
                    font-family: "Koh Santepheap";
                }
                h5 {
                    font-size: 14px;
                    font-family: "Koh Santepheap";
                }
                h6 {
                    font-size: 12px;
                    font-family: "Koh Santepheap";
                }
                /*Textes classiques*/
                p {
                    font-size: 14px;
                }
                a, a:visited, a:link, a:visited {
                    font-size: 14px;
                    font-weight: 700;
                    
                    background: var(--Links-color, linear-gradient(180deg, #09F 0%, #0069AE 190%));
                    background-clip: text;
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                }
                label {
                    font-size: 14px;

                    margin-bottom: 15px;
                }
                /*Citations et autre*/
                blockquote {
                    font-size: 12px;
                }
                cite {
                    font-size: 12px;
                }
                
                /*Variations*/
                .text-black {
                    color: #000000;
                }
                .text-white {
                    color: #FFFFFF;
                }
                .important-black {
                    background: linear-gradient(180deg, #000 0%, #999 190%);
                    background-clip: text;
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;

                    font-weight: 700;
                }
                .important-white {
                    background: var(--Important-text-color-2, linear-gradient(180deg, #FFF 0%, #999 190%));
                    background-clip: text;
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;

                    font-weight: 700;
                }

            /*3. Buttons and inputs*/

            /*5. Navigation bar*/
            nav {
                width: 100%;
                height: 72px;

                display: grid;
                grid-template-columns: 320px auto 320px;

                background: #6D7FA8;
            }
            nav > section {
                margin: 0px 10px;
            }
            nav > .navigation_container {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                align-content: center;
                justify-content: center;
                align-items: center;

                gap: 25px;
                padding: 0px 25px;
            }
            nav > .navigation_info {
                display: flex;
                flex-direction: row;
                justify-content: left;
                align-items: center;

                gap: 10px;
            }
            .nav-button {
                font-size: 16px;
                font-family: "Koh Santepheap";
                font-weight: 700;

                background: var(--Important-text-color-2, linear-gradient(180deg, #FFF 0%, #999 190%));
                background-clip: text;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            .nav-button:hover {
                background: var(--Important-text-color-2, linear-gradient(180deg, #CCC 0%, #999 190%));
                background-clip: text;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            .nav-active {
                background: var(--Active, linear-gradient(180deg, #263150 0%, #0069AE 190%));
                background-clip: text;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            .nav-title:link, .nav-title:visited, .nav-title:hover, .nav-title:active {
                background: var(--Important-text-color-2, linear-gradient(180deg, #FFF 0%, #999 190%));
                background-clip: text;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;

                font-size: 16px;
            }

            /*6. Cards*/
            .cards {
                border-radius: 16px;

                background: rgba(255, 255, 255, 0.50);

                overflow: hidden;
            }
            .cards-login {
                width: 550px;
            }
            .cards-header {
                padding: 25px 30px;
                background: #FFF;
                text-align: center;
            }
            .cards-body {
                padding: 25px;
            }

        </style>
    </head>
    <body>
        <nav>
            <section  class="navigation_info"></section>
            <section class="navigation_container">
                <a class="nav-button" href="./"><img src="./img/generic/LogoOrv.png" width="60px"></a>
                <a class="nav-button nav-title" href="./">Panneau de gestion du Jdr ORV</a>
            </section>
            <section class="navigation_info"></section>
        </nav>
        <section class="container container-center">
            <div class="cards cards-login">
                <div class="cards-header"><h2>Veuillez vous connecter.</h2></div>
                <div class="cards-body container-center flex-dir-column-down">
                </div>
            </div>
        </section>
    </body>
</html>