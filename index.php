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
                a {
                    font-size: 14px;
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
                }
                .important-white {
                    background: var(--Important-text-color-2, linear-gradient(180deg, #FFF 0%, #999 190%));
                    background-clip: text;
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                }

            /*3. Buttons and inputs*/

            /*4. Navigation bar*/
            nav {
                width: 100%;
                height: 72px;

                display: grid;
                grid-template-columns: 300px auto 300px;

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
            .nav-button {
                font-size: 16px;
                font-family: "Koh Santepheap";
                font-weight: 600;

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
            }
        </style>
    </head>
    <body>
        <nav>
            <section></section>
            <section class="navigation_container">
                <a class="nav-button" href="./"><img src="./img/generic/LogoOrv.png" width="60px"></a>
                <a class="nav-button nav-title" href="./">Panneau de gestion du Jdr ORV</a>
            </section>
            <section></section>
        </nav>
        </section>
    </body>
</html>