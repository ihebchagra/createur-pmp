<?php
if (isset($_GET['t'])) {
    switch ($_GET['t']) {
        case 'verify':
            $toast = 'Un email de vérification a été envoyé à votre adresse email.';
            break;
        case 'reset':
            $toast = 'Réinitialisation complète.';
            break;
        case 'error':
            $toast = 'Une erreur est survenue. Veuillez réessayer.';
            break;
        case 'deleted-project':
            $toast = 'Le projet a été supprimé.';
            break;
        case 'saved-project':
            $toast = 'Le projet a été sauvegardé.';
            break;
        case 'attempt-saved':
            $toast = 'La tentative a été sauvegardée.';
            break;
        default:
            $toast = 'Action inconnue.';
            break;
    }
?>
<!-- Toast Component -->
<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
    x-transition:leave="toast-leave" x-transition:leave-start="toast-leave-start"
    x-transition:leave-end="toast-leave-end" class="toast" @click="show=false">
    <p><?php echo $toast ?> </p>
</div>
<?php
}
?>