<?php
try {
    if ($auth->getUserId() == $_POST['id']) {
        try {
            $auth->admin()->deleteUserById($_POST['id']);
            $stmt = $db->prepare('DELETE FROM user_profiles WHERE user_id = :id');
            $stmt->execute(['id' => $_POST['id']]);
        } catch (Exception $e) {
            // handle the exception
        }

        $auth->logOut();
        header('Location: /');
        exit();
    } else {
        header('Location: /profile?error=unallowed');
        exit();
    }
} catch (\Delight\Auth\UnknownIdException $e) {
    die('Unknown ID');
}