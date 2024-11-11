<?php
$content = <<<HTML
    <main class="container">
        <h1>Bienvenue Dr&nbsp;
HTML;

$content .= ucwords(strtolower($lastname));

$content .= <<<HTML
        </h1>
        <!-- Add your dashboard content here -->
        <div class="dashboard-content">
            <h2>Vos PMPs</h2>
HTML;

require $_SERVER['DOCUMENT_ROOT'] . '/../scripts/components/content/projects.php';

$content .= <<<HTML

        </div>
    </div>
    <script>
        document.title = "Créateur PMP";
        </script>
HTML;