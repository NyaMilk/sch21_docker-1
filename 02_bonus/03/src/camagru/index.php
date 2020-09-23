<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (isset($_SESSION['name'])) {
    header('Location: gallery.php?sort=all&page=1');
    return;
}

require_once 'util.php';
require_once 'model/index-model.php';

flashMessages();

if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Sign In') {
        if (strlen($_POST['username']) == 0 || strlen($_POST['pass']) == 0) {
            $_SESSION['error'] = 'Username and password are required.';
            header('Location: index.php');
            return;
        }
        $check = hash('sha512', $salt . $_POST['pass']);
        $row = checkUser($pdo, trim($_POST['username']), $check);
        if ($row !== false) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['confirm'] = $row['confirm'];
            if ($row['confirm'] == 'no')
                deleteNotConfirmUser($pdo, $_SESSION['name']);
            header('Location: gallery.php?sort=all&page=1');
            return;
        } else {
            $_SESSION['error'] = 'Incorrect username or password.';
            header('Location: index.php');
            return;
        }
    }

    if ($_POST['submit'] == 'Sign Up') {
        if (strlen($_POST['username_up']) == 0 || strlen($_POST['email_up']) == 0 || strlen($_POST['pass_up']) == 0 || strlen($_POST['repass_up']) == 0) {
            $_SESSION['error'] = 'All values are required.';
            header('Location: index.php');
            return;
        }

        $page = 'index.php';
        if (checkUserName($pdo, $page) == false)
            return;
        $hash = md5($_POST['username_up'] . time());
        addUser($pdo, trim($_POST['username_up']), trim($_POST['email_up']), hash('sha512', $salt . $_POST['pass_up']), $hash);
        $_SESSION['success'] = 'Profile added. Please verify email address to complete your registration.';
        sendNotification('email_up', $hash, $page);
        header('Location: index.php');
        return;
    }
}

require_once 'components/header.php';
require_once 'components/login.php';
require_once 'components/footer.php';
