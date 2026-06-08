<div class="orv-buttons" style="justify-content: flex-end;">
    <button class="orv-edit" onclick="window.location.href='sessions?sub-page=create';">
        <i class="fa-solid fa-plus"></i>
        <span style="font-size: 18px;">Nouvelle session</span>
    </button>
</div>

<div class="orv-menu" id="sessions-list">
    <div class="orv-menu_header">&lt;Gestion des Sessions&gt;</div>
    <div class="orv-menu_container" style="padding: 0;">
        <table>
            <thead>
                <tr>
                    <td>#</td>
                    <td>Titre</td>
                    <td>Date</td>
                    <td>Scénario</td>
                    <td>Statut</td>
                    <td width="130px">Actions</td>
                </tr>
            </thead>
            <tbody>
                <tr class="session-row session-row--active" data-status="active" data-uuid="002">
                    <td>2</td>
                    <td>Le Premier Scénario</td>
                    <td>15 jan. 2025</td>
                    <td>Arc I – Survivre</td>
                    <td class="session-badge-cell">
                        <span class="session-badge session-badge--active"><i class="fa-solid fa-circle-play"></i> En cours</span>
                    </td>
                    <td class="session-actions-cell"></td>
                </tr>
                <tr class="session-row" data-status="planned" data-uuid="003">
                    <td>3</td>
                    <td>La Tour du Roi-Démon</td>
                    <td>22 jan. 2025</td>
                    <td>Arc I – Survivre</td>
                    <td class="session-badge-cell">
                        <span class="session-badge session-badge--planned"><i class="fa-solid fa-clock"></i> Planifiée</span>
                    </td>
                    <td class="session-actions-cell"></td>
                </tr>
                <tr class="session-row" data-status="planned" data-uuid="004">
                    <td>4</td>
                    <td>La Constellation de l'Abîme</td>
                    <td>29 jan. 2025</td>
                    <td>Arc II – Les Grands Scénarios</td>
                    <td class="session-badge-cell">
                        <span class="session-badge session-badge--planned"><i class="fa-solid fa-clock"></i> Planifiée</span>
                    </td>
                    <td class="session-actions-cell"></td>
                </tr>
                <tr class="session-row session-row--done" data-status="done" data-uuid="001">
                    <td>1</td>
                    <td>Prologue : L'Enfer du Métro</td>
                    <td>08 jan. 2025</td>
                    <td>Arc I – Survivre</td>
                    <td class="session-badge-cell">
                        <span class="session-badge session-badge--done"><i class="fa-solid fa-check"></i> Terminée</span>
                    </td>
                    <td class="session-actions-cell"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
(function () {
    var EDIT_STYLE   = 'text-decoration:none;font-size:22px;margin-right:4px;background:#FFF;background-clip:text!important;-webkit-background-clip:text!important;';
    var DELETE_STYLE = 'text-decoration:none;font-size:22px;background:#FFF;background-clip:text!important;-webkit-background-clip:text!important;';

    function renderActions(row) {
        var status = row.dataset.status;
        var uuid   = row.dataset.uuid;
        var cell   = row.querySelector('.session-actions-cell');

        var edit = '<a href="sessions?sub-page=modify&uuid=' + uuid + '" title="Préparer" style="' + EDIT_STYLE + '"><i class="fa-solid fa-pen"></i></a>';
        var del  = '<a href="sessions?sub-page=delete&uuid=' + uuid + '" title="Supprimer" style="' + DELETE_STYLE + '"><i class="fa-solid fa-trash"></i></a>';

        var action = '';
        if (status === 'planned') {
            action = '<button onclick="sessionLancer(this)" title="Lancer la session" style="background:none;border:none;cursor:pointer;font-size:22px;color:#7EFFA6;padding:0 5px 0 0;"><i class="fa-solid fa-play"></i></button>';
        } else if (status === 'active') {
            action = '<button onclick="sessionTerminer(this)" title="Terminer la session" style="background:none;border:none;cursor:pointer;font-size:22px;color:#FFD97E;padding:0 5px 0 0;"><i class="fa-solid fa-flag-checkered"></i></button>';
        }

        cell.innerHTML = edit + action + del;
    }

    function updateBadge(row) {
        var status = row.dataset.status;
        var cell   = row.querySelector('.session-badge-cell');
        var badges = {
            planned: '<span class="session-badge session-badge--planned"><i class="fa-solid fa-clock"></i> Planifiée</span>',
            active:  '<span class="session-badge session-badge--active"><i class="fa-solid fa-circle-play"></i> En cours</span>',
            done:    '<span class="session-badge session-badge--done"><i class="fa-solid fa-check"></i> Terminée</span>'
        };
        cell.innerHTML = badges[status] || '';
        row.className  = 'session-row' + (status === 'active' ? ' session-row--active' : status === 'done' ? ' session-row--done' : '');
    }

    window.sessionLancer = function (btn) {
        var row = btn.closest('tr');
        row.dataset.status = 'active';
        updateBadge(row);
        renderActions(row);
    };

    window.sessionTerminer = function (btn) {
        var row = btn.closest('tr');
        row.dataset.status = 'done';
        updateBadge(row);
        renderActions(row);
    };

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('#sessions-list .session-row').forEach(renderActions);
    });
}());
</script>
