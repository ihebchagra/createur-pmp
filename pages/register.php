<?php
$error = '';

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'email':
            $error = 'Email invalide.';
            break;
        case 'password':
            $error = 'Mot de passe invalide.';
            break;
        case 'userexists':
            $error = "L'utilisateur existe déjà.";
            break;
        case 'toomanyrequests':
            $error = 'Trop de demandes. Veuillez réessayer plus tard.';
            break;
        case 'emailerror':
            $error = 'Erreur lors d\'envoi de l\'email de vérification.';
            break;
    }
};

$content = <<<HTML
    <main class="container" x-data="{ password: '', confirmPassword: '' }">
        <h2>S'inscrire</h2>
        <form action="/register" method="post">
            <label for="prenom">Prenom :</label>
            <input type="fname" id="prenom" name="prenom" required><br>

            <label for="nom">Nom :</label>
            <input type="lname" id="nom" name="nom" required><br>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required><br>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" x-model="password" required><br>

            <label for="confirm_password">Confirmer le mot de passe :</label>
            <input type="password" id="confirm_password" name="confirm_password" x-model="confirmPassword"
                :aria-invalid="password !== confirmPassword" required><br>

            <p x-show="password !== confirmPassword && confirmPassword !== ''" style="color: red;">Les mots de passe ne
                correspondent pas.</p>

    HTML;

if (!empty($error)) {
    $content .= <<<HTML
        <p style="color: red;">$error</p>
        HTML;
}

$content .= <<<HTML
            <input type="submit" value="S'inscrire" :disabled="password !== confirmPassword">
            </form>
        </main>
        <script>
        document.title = "S'inscrire";
        </script>
    HTML;