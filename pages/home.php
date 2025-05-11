<?php
$content = <<<HTML
    <main class="container home-container">
        <h1>Créateur PMP</h1>
        <p>
            Bienvenue sur Créateur PMP, votre outil en ligne pour créer des Patient-management problems (PMP). Utilisez
            notre plateforme pour générer des scénarios cliniques réalistes.
        </p>
        <div class="centered-container">
            <button onclick="window.location.href='/login'">Se connecter</button>
            <button onclick="window.location.href='/register'">S'inscrire</button>
        </div>
    </main>
    <script>
    document.title = "Créateur PMP";
    </script>
    HTML;