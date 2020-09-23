<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'config/pdo.php';
require_once 'model/remind-model.php';

if (isset($_GET['hash']) && $_GET['hash']) {
    $row = checkHash($pdo, $_GET['hash']);
    if ($row !== false) {
        if ($row['confirm'] == 'no') {
            changeConfirm($pdo, $row['user_id']);
            $_SESSION['success'] = "Email address confirm.";
        } elseif ($row['confirm'] == 'yes')
            $_SESSION['success'] = "Your email address already confirmed.";
    } else
        $_SESSION['error'] = "Email address confirm error.";
    header('Location: ../index.php');
    return;
}
