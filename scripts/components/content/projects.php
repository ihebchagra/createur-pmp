<?php
if (isset($_SESSION['_internal_user_projects']) && is_array($_SESSION['_internal_user_projects'])) {
    $projects = $_SESSION['_internal_user_projects'];
    usort($projects, function ($a, $b) {
        return strtotime($b['updated_at']) - strtotime($a['updated_at']);
    });
    $projectCount = count($projects);
    if ($projectCount > 0) {
        foreach ($projects as $project) {
            $content .= <<<HTML
                <article>
                    <details x-data="{ open: false }" @toggle="open = !open">
                        <summary>{$project['project_name']}</summary>
                        <div x-show="open" x-collapse>
                            <form method="get" action="/edit">
                                <input type="hidden" name="project_id" value="{$project['project_id']}">
                                <button type="submit">Modifier le PMP</button>
                            </form>
                            <form method="get" action="/start-exam">
                                <input type="hidden" name="project_id" value="{$project['project_id']}">
                                <button type="submit">Commencer une épreuve</button>
                            </form>
                            <form method="get" action="/results">
                                <input type="hidden" name="project_id" value="{$project['project_id']}">
                                <button type="submit">Analyser Résultats</button>
                            </form>
                            <form method="post" action="/delete-project" x-data @submit.prevent="
                            modal.confirm('Êtes-vous sûr de vouloir supprimer ce PMP ?','Confirmation').then(result=>{if (result) \$el.submit()})
                            ">
                                <input type="hidden" name="project_id" value="{$project['project_id']}">
                                <button class="secondary" type="submit">Supprimer le PMP</button>
                            </form>
                        </div>
                    </details>
                </article>

                HTML;
        }
    } else {
        $content .= <<<HTML
            <p>Aucun projet trouvé.</p>
            HTML;
    }
} else {
    $content .= <<<HTML
        <p>Aucun projet trouvé.</p>
        HTML;
}

$content .= <<<HTML
    <form method="post" action="/add-project">
        <label for="project_name"><h3>Ajouter un PMP :</h3></label>
        <input type="text" id="project_name" name="project_name" placeholder="Entrez le nom du PMP" required>
        <button type="submit">Ajouter un PMP</button>
    </form>
    HTML;