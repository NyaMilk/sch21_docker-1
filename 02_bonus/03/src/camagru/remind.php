<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (isset($_SESSION['name'])) {
    header('Location: index.php');
    return;
}

require_once 'util.php';
require_once 'model/remind-model.php';

if (isset($_POST['submit']) && $_POST['submit'] == 'Send') {
    $row = findEmail($pdo, trim($_POST['remind-email']));
    if ($row == false) {
        $_SESSION['error'] = 'Email address has not found';
        header('Location: remind.php');
        return;
    }
    $arr = array($row['name'], $_POST['remind-email']);
    $page = 'remind.php';
    sendNotification($row['email'], $arr, $page);
    $_SESSION['success'] = 'Link to change pass has been sent to your mail.';
    header('Location: index.php');
    return;
}

if (isset($_POST['submit']) && $_POST['submit'] == 'Set new password') {
    $row = findEmailByName($pdo, trim($_GET['name']));
    if ((md5($row['email']) != $_GET['uniq']) || !$row || !isset($_GET['name']) || !isset($_GET['uniq'])) {
        $_SESSION['error'] = "Wrong name to change password.";
        header('Location: remind.php');
        return;
    }
    $page = 'remind.php?name=' . $_GET['name'] . '&uniq=' . $_GET['uniq'];
    if (!checkPassword($pdo, $page))
        return;
    changePass($pdo, hash('sha512', $salt . $_POST['repass_up']), $_GET['name']);
    $_SESSION['success'] = 'Password has been changed successfully!';
    header('Location: index.php');
    return;
}

require_once 'components/header.php';

if (isset($_GET['name']))
    require_once "components/reset-pass.php";
else
    require_once "components/remind-page.php";

require_once "components/footer.php";

flashMessages();
