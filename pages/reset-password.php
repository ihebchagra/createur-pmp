<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../config.php';

$error = '';
$showForm = false;

if (isset($_GET['selector']) && isset($_GET['token'])) {
    $selector = $_GET['selector'];
    $token = $_GET['token'];

    try {
        $auth->canResetPasswordOrThrow($selector, $token);
        $showForm = true;
    } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
        $error = 'Token invalide.';
    } catch (\Delight\Auth\TokenExpiredException $e) {
        $error = 'Le token a expiré.';
    } catch (\Delight\Auth\ResetDisabledException $e) {
        $error = 'La réinitialisation du mot de passe est désactivée.';
    } catch (\Delight\Auth\TooManyRequestsException $e) {
        $error = 'Trop de demandes.';
    }
} else {
    $error = 'Paramètres manquants.';
}

$content = '';

if ($showForm) {
    $content .= <<<HTML
            <main class="container" x-data="{ password: '', confirmPassword: '' }">
                <h2>Réinitialiser le mot de passe</h2>
                <form action="/reset-password" method="post">
                    <input type="hidden" name="selector" value="{$selector}">
                    <input type="hidden" name="token" value="{$token}">
                    
                    <label for="password">Nouveau mot de passe :</label>
                    <input type="password" id="password" name="password" x-model="password" required><br>
                    
                    <label for="confirmPassword">Confirmer le mot de passe :</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" x-model="confirmPassword" required><br>
                    
                    <input type="submit" value="Réinitialiser le mot de passe" :disabled="password === '' || confirmPassword === ''">
                </form>
            </main>
            <script>
                document.title = "Réinitialiser le mot de passe";
            </script>
        HTML;
} else {
    $content .= <<<HTML
            <main class="container">
                <h2>Erreur</h2>
                <p style="color: red;">{$error}</p>
            </main>
            <script>
                document.title = "Erreur de réinitialisation";
            </script>
        HTML;
}
