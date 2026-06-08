<div class="orv-buttons" style="justify-content: flex-end;">
    <button class="orv-edit" onclick="window.location.href='fights?sub-page=create'">
        <i class="fa-solid fa-plus"></i>
        <span style="font-size: 18px;">Nouveau combat</span>
    </button>
</div>

<div class="orv-menu" id="fights-list">
    <div class="orv-menu_header">&lt;Gestion des Combats&gt;</div>
    <div class="orv-menu_container" style="padding: 0;">
        <table>
            <thead>
                <tr>
                    <td>Nom du combat</td>
                    <td>Type</td>
                    <td>Joueurs</td>
                    <td>Adversaires</td>
                    <td>Statut</td>
                    <td width="140px">Actions</td>
                </tr>
            </thead>
            <tbody>
                <tr data-status="active" data-uuid="fight-001">
                    <td><strong>La Horde du Roi-Démon</strong></td>
                    <td><span class="fight-type-badge fight-type-badge--pve">PvE</span></td>
                    <td>3</td>
                    <td>5</td>
                    <td><span class="fight-badge fight-badge--active"><i class="fa-solid fa-swords"></i> En cours</span></td>
                    <td class="fight-list-actions">
                        <a href="fights?sub-page=combat&id=fight-001" title="Ouvrir le combat" class="fight-list-link fight-list-link--open"><i class="fa-solid fa-circle-play"></i></a>
                        <a href="fights?sub-page=delete&id=fight-001" title="Supprimer" class="fight-list-link fight-list-link--delete"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <tr data-status="prep" data-uuid="fight-002">
                    <td><strong>Duel de Sélection</strong></td>
                    <td><span class="fight-type-badge fight-type-badge--pvp">PvP</span></td>
                    <td>2</td>
                    <td>—</td>
                    <td><span class="fight-badge fight-badge--prep"><i class="fa-solid fa-clock"></i> Préparation</span></td>
                    <td class="fight-list-actions">
                        <a href="fights?sub-page=create&id=fight-002" title="Modifier" class="fight-list-link fight-list-link--edit"><i class="fa-solid fa-pen"></i></a>
                        <a href="fights?sub-page=combat&id=fight-002" title="Lancer" class="fight-list-link fight-list-link--launch"><i class="fa-solid fa-play"></i></a>
                        <a href="fights?sub-page=delete&id=fight-002" title="Supprimer" class="fight-list-link fight-list-link--delete"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <tr data-status="prep" data-uuid="fight-003">
                    <td><strong>Embuscade à Séoul</strong></td>
                    <td><span class="fight-type-badge fight-type-badge--mixed">Mixte</span></td>
                    <td>4</td>
                    <td>3</td>
                    <td><span class="fight-badge fight-badge--prep"><i class="fa-solid fa-clock"></i> Préparation</span></td>
                    <td class="fight-list-actions">
                        <a href="fights?sub-page=create&id=fight-003" title="Modifier" class="fight-list-link fight-list-link--edit"><i class="fa-solid fa-pen"></i></a>
                        <a href="fights?sub-page=combat&id=fight-003" title="Lancer" class="fight-list-link fight-list-link--launch"><i class="fa-solid fa-play"></i></a>
                        <a href="fights?sub-page=delete&id=fight-003" title="Supprimer" class="fight-list-link fight-list-link--delete"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <tr data-status="done" data-uuid="fight-000">
                    <td><strong>Prologue : Métro de Séoul</strong></td>
                    <td><span class="fight-type-badge fight-type-badge--pve">PvE</span></td>
                    <td>4</td>
                    <td>8</td>
                    <td><span class="fight-badge fight-badge--done"><i class="fa-solid fa-flag-checkered"></i> Terminé</span></td>
                    <td class="fight-list-actions">
                        <a href="fights?sub-page=combat&id=fight-000" title="Voir" class="fight-list-link fight-list-link--view"><i class="fa-solid fa-eye"></i></a>
                        <a href="fights?sub-page=delete&id=fight-000" title="Supprimer" class="fight-list-link fight-list-link--delete"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
