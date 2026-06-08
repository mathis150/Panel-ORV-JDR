<?php
    require_once "./import/base.php";

    $bases = new WebPageBases();
?>
    <?php $bases->header(); ?>
    <body>
        <?php $bases->navigation(); ?>
        <section class="container container-menus">

            <!-- Sidebar profil -->
            <section class="left-menu">
                <div class="profile-sidebar">
                    <div class="profile-avatar">
                        <img src="./img/generic/LogoOrv.png" alt="Avatar">
                    </div>
                    <div class="profile-name">Administrateur</div>
                    <div class="profile-role">Maître du Jeu</div>
                    <div class="profile-divider"></div>
                    <div class="profile-quick-stat">
                        <span class="profile-quick-stat__label">Sessions jouées</span>
                        <span class="profile-quick-stat__value">12</span>
                    </div>
                    <div class="profile-quick-stat">
                        <span class="profile-quick-stat__label">Personnages actifs</span>
                        <span class="profile-quick-stat__value">3</span>
                    </div>
                    <div class="profile-quick-stat">
                        <span class="profile-quick-stat__label">Scénarios complétés</span>
                        <span class="profile-quick-stat__value">8</span>
                    </div>
                    <div class="profile-quick-stat">
                        <span class="profile-quick-stat__label">Membre depuis</span>
                        <span class="profile-quick-stat__value">Jan. 2025</span>
                    </div>
                </div>
            </section>

            <!-- Contenu principal -->
            <section class="right-menu">

                <!-- Onglets -->
                <div class="session-tabs">
                    <button class="session-tab button-actif" data-target="pp-compte">
                        <i class="fa-solid fa-user-pen"></i> Mon compte
                    </button>
                    <button class="session-tab" data-target="pp-notes">
                        <i class="fa-solid fa-scroll"></i> Notes MJ
                    </button>
                    <button class="session-tab" data-target="pp-sessions">
                        <i class="fa-solid fa-calendar-days"></i> Sessions à venir
                    </button>
                    <button class="session-tab" data-target="pp-stats">
                        <i class="fa-solid fa-chart-bar"></i> Statistiques
                    </button>
                </div>

                <div class="menu-right-container">

                    <!-- ===== Onglet : Mon compte ===== -->
                    <div id="pp-compte" class="session-tab-panel">
                        <div class="orv-menu">
                            <div class="orv-menu_header">&lt;Informations du compte&gt;</div>
                            <div class="orv-menu_container">

                                <div class="orv-menu_form">
                                    <div>
                                        <h2 class="classic-title">Identité :</h2>
                                        <cite>Modifier votre nom d'utilisateur et votre adresse e-mail.</cite>
                                    </div>
                                    <div class="orv-menu_form-elements">
                                        <div class="orv-menu_form-inputs-list">
                                            <div class="orv-menu_form-input" style="width: 300px;">
                                                <label>Nom d'utilisateur :</label>
                                                <input type="text" value="Administrateur" style="width: 300px;">
                                            </div>
                                            <div class="orv-menu_form-input" style="width: 300px;">
                                                <label>Adresse e-mail :</label>
                                                <input type="email" value="admin@orv-jdr.fr" style="width: 300px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="orv-menu_form-valid">
                                        <button class="orv-edit">
                                            <i class="fa-solid fa-check"></i>
                                            <span style="font-size: 17px;">Sauvegarder</span>
                                        </button>
                                    </div>
                                </div>

                                <hr>

                                <div class="orv-menu_form">
                                    <div>
                                        <h2 class="classic-title">Sécurité :</h2>
                                        <cite>Modifier votre mot de passe. Renseignez d'abord votre mot de passe actuel.</cite>
                                    </div>
                                    <div class="orv-menu_form-elements">
                                        <div class="orv-menu_form-inputs-list">
                                            <div class="orv-menu_form-input" style="width: 280px;">
                                                <label>Mot de passe actuel :</label>
                                                <input type="password" placeholder="••••••••••" style="width: 280px;">
                                            </div>
                                            <div class="orv-menu_form-input" style="width: 280px;">
                                                <label>Nouveau mot de passe :</label>
                                                <input type="password" placeholder="••••••••••" style="width: 280px;">
                                            </div>
                                            <div class="orv-menu_form-input" style="width: 280px;">
                                                <label>Confirmer le mot de passe :</label>
                                                <input type="password" placeholder="••••••••••" style="width: 280px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="orv-menu_form-valid">
                                        <button class="orv-edit">
                                            <i class="fa-solid fa-lock"></i>
                                            <span style="font-size: 17px;">Changer le mot de passe</span>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- ===== Onglet : Notes MJ ===== -->
                    <div id="pp-notes" class="session-tab-panel hidden">
                        <div class="orv-menu">
                            <div class="orv-menu_header">&lt;Notes du Maître du Jeu&gt;</div>
                            <div class="orv-menu_container">

                                <div class="orv-menu_form">
                                    <div>
                                        <h2 class="classic-title">À propos de vous :</h2>
                                        <cite>Notes rédigées par le MJ — lecture seule.</cite>
                                    </div>
                                </div>

                                <div style="margin-top: 24px;">
                                    <div class="profile-mj-note">
                                        <div class="profile-mj-note-header">
                                            <i class="fa-solid fa-feather-pointed"></i> Note du 15 janvier 2025
                                        </div>
Kim Dokja est un joueur particulièrement attentif à la narration. Il mémorise les détails des sessions précédentes avec une précision remarquable. À surveiller : il a tendance à prendre des risques calculés pour protéger ses alliés, au détriment de sa propre survie.

Son personnage possède une connaissance approfondie de la "Prophétie du Lecteur". Il serait judicieux de lui révéler des indices lors des prochains scénarios impliquant les Constellations.
                                    </div>

                                    <div class="profile-mj-note">
                                        <div class="profile-mj-note-header">
                                            <i class="fa-solid fa-feather-pointed"></i> Note du 8 janvier 2025
                                        </div>
Première session très prometteuse. Bonne compréhension des mécaniques de jeu, excellente interaction avec les autres joueurs. A su improviser face à une situation imprévue dans le scénario du Métro.

Points à développer : l'utilisation des compétences passives est encore sous-optimale.
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- ===== Onglet : Sessions à venir ===== -->
                    <div id="pp-sessions" class="session-tab-panel hidden">
                        <div class="orv-menu">
                            <div class="orv-menu_header">&lt;Calendrier des Sessions&gt;</div>
                            <div class="orv-menu_container">

                                <div class="orv-menu_form">
                                    <div>
                                        <h2 class="classic-title">Prochaines sessions :</h2>
                                        <cite>Calendrier établi par le MJ — lecture seule.</cite>
                                    </div>
                                </div>

                                <div style="margin-top: 24px;">

                                    <div class="profile-session-item">
                                        <div class="profile-session-date">
                                            <div class="profile-session-date__day">22</div>
                                            <div class="profile-session-date__month">Jan</div>
                                        </div>
                                        <div>
                                            <div class="profile-session-title">La Tour du Roi-Démon</div>
                                            <div class="profile-session-meta">
                                                <span><i class="fa-solid fa-map-pin"></i>Tour de Babel — Étage 1</span>
                                                <span><i class="fa-solid fa-book-open"></i>Arc I – Survivre</span>
                                            </div>
                                        </div>
                                        <span class="session-badge session-badge--planned"><i class="fa-solid fa-clock"></i> Planifiée</span>
                                    </div>

                                    <div class="profile-session-item">
                                        <div class="profile-session-date">
                                            <div class="profile-session-date__day">29</div>
                                            <div class="profile-session-date__month">Jan</div>
                                        </div>
                                        <div>
                                            <div class="profile-session-title">La Constellation de l'Abîme</div>
                                            <div class="profile-session-meta">
                                                <span><i class="fa-solid fa-map-pin"></i>Hangang — Pont effrondré</span>
                                                <span><i class="fa-solid fa-book-open"></i>Arc II – Les Grands Scénarios</span>
                                            </div>
                                        </div>
                                        <span class="session-badge session-badge--planned"><i class="fa-solid fa-clock"></i> Planifiée</span>
                                    </div>

                                    <div class="profile-session-item">
                                        <div class="profile-session-date">
                                            <div class="profile-session-date__day">05</div>
                                            <div class="profile-session-date__month">Fév</div>
                                        </div>
                                        <div>
                                            <div class="profile-session-title">Le Régressor et le Lecteur</div>
                                            <div class="profile-session-meta">
                                                <span><i class="fa-solid fa-map-pin"></i>Séoul — District de Mapo</span>
                                                <span><i class="fa-solid fa-book-open"></i>Arc II – Les Grands Scénarios</span>
                                            </div>
                                        </div>
                                        <span class="session-badge session-badge--planned"><i class="fa-solid fa-clock"></i> Planifiée</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ===== Onglet : Statistiques ===== -->
                    <div id="pp-stats" class="session-tab-panel hidden">
                        <div class="orv-menu">
                            <div class="orv-menu_header">&lt;Statistiques Globales&gt;</div>
                            <div class="orv-menu_container">

                                <div class="orv-menu_form">
                                    <div>
                                        <h2 class="classic-title">Combat :</h2>
                                        <cite>Vos performances offensives et défensives cumulées sur toutes les sessions.</cite>
                                    </div>
                                    <div class="profile-stats-grid">
                                        <div class="profile-stat-card profile-stat-card--combat">
                                            <div class="profile-stat-card__icon"><i class="fa-solid fa-fire"></i></div>
                                            <div class="profile-stat-card__value">4 820</div>
                                            <div class="profile-stat-card__label">Dégâts infligés</div>
                                        </div>
                                        <div class="profile-stat-card profile-stat-card--defense">
                                            <div class="profile-stat-card__icon"><i class="fa-solid fa-shield-halved"></i></div>
                                            <div class="profile-stat-card__value">1 340</div>
                                            <div class="profile-stat-card__label">Dégâts subis</div>
                                        </div>
                                        <div class="profile-stat-card profile-stat-card--ko">
                                            <div class="profile-stat-card__icon"><i class="fa-solid fa-skull"></i></div>
                                            <div class="profile-stat-card__value">17</div>
                                            <div class="profile-stat-card__label">Ennemis mis KO</div>
                                        </div>
                                        <div class="profile-stat-card profile-stat-card--heal">
                                            <div class="profile-stat-card__icon"><i class="fa-solid fa-kit-medical"></i></div>
                                            <div class="profile-stat-card__value">2 150</div>
                                            <div class="profile-stat-card__label">Soins prodigués</div>
                                        </div>
                                        <div class="profile-stat-card profile-stat-card--ko">
                                            <div class="profile-stat-card__icon"><i class="fa-solid fa-circle-xmark"></i></div>
                                            <div class="profile-stat-card__value">2</div>
                                            <div class="profile-stat-card__label">Mises KO reçues</div>
                                        </div>
                                        <div class="profile-stat-card profile-stat-card--combat">
                                            <div class="profile-stat-card__icon"><i class="fa-solid fa-bolt"></i></div>
                                            <div class="profile-stat-card__value">63</div>
                                            <div class="profile-stat-card__label">Compétences utilisées</div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="orv-menu_form">
                                    <div>
                                        <h2 class="classic-title">Sessions :</h2>
                                        <cite>Votre participation et progression dans la campagne.</cite>
                                    </div>
                                    <div class="profile-stats-grid">
                                        <div class="profile-stat-card profile-stat-card--session">
                                            <div class="profile-stat-card__icon"><i class="fa-solid fa-calendar-check"></i></div>
                                            <div class="profile-stat-card__value">12</div>
                                            <div class="profile-stat-card__label">Sessions jouées</div>
                                        </div>
                                        <div class="profile-stat-card profile-stat-card--session">
                                            <div class="profile-stat-card__icon"><i class="fa-solid fa-flag-checkered"></i></div>
                                            <div class="profile-stat-card__value">8</div>
                                            <div class="profile-stat-card__label">Scénarios complétés</div>
                                        </div>
                                        <div class="profile-stat-card profile-stat-card--heal">
                                            <div class="profile-stat-card__icon"><i class="fa-solid fa-handshake"></i></div>
                                            <div class="profile-stat-card__value">34</div>
                                            <div class="profile-stat-card__label">Alliés secourus</div>
                                        </div>
                                        <div class="profile-stat-card profile-stat-card--session">
                                            <div class="profile-stat-card__icon"><i class="fa-solid fa-star"></i></div>
                                            <div class="profile-stat-card__value">3</div>
                                            <div class="profile-stat-card__label">Personnages actifs</div>
                                        </div>
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
