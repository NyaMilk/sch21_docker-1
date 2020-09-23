<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();


require_once 'util.php';
require_once 'model/edit-model.php';

flashMessages();

if (checkSignIn()) {
    if (isset($_SESSION['name']) && isset($_GET['user']) && $_SESSION['name'] == $_GET['user']) {
        $row = getUserData($pdo, $_GET['user']);
        if ($row !== false) {
            if ($row['notification'] == 'yes')
                $checked = 'checked';
            else
                $checked = '';

            if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
                $page = 'edit.php?user=' . $row['name'];

                if (isset($_POST['notific']) && $_POST['notific'] == 'yes')
                    $notific = 'yes';
                else
                    $notific = 'no';

                if (strlen($_POST['username_up']) == 0 || strlen($_POST['email_up']) == 0) {
                    $_SESSION['error'] = 'Username and email are required';
                    header('Location: edit.php?user=' . $row['name']);
                    return;
                }

                if ($_POST['username_up'] != $row['name']) {
                    checkUserName($pdo, $page);
                }
                if ($_POST['email_up'] != $row['email'])
                    checkEmail($pdo, $page);
                if (!checkLenInput('description', 'Description')) {
                    header('Location: ' . $page);
                    return;
                }
                if (strlen($_POST['pass_up']) > 0 || strlen($_POST['repass_up']) > 0) {
                    checkPassword($pdo, $page);
                    if (!isset($_SESSION['error']))
                        changePass($pdo, hash('sha512', $salt . $_POST['repass_up']), $_SESSION['user_id']);
                }

                if (!isset($_SESSION['error'])) {
                    updateAll($pdo, trim($_POST['username_up']), trim($_POST['email_up']), trim($_POST['description']), $_SESSION['user_id']);
                    $_SESSION['name'] = $_POST['username_up'];

                    $upload_dir = 'images/' . $row['user_id'];
                    if (!file_exists($upload_dir))
                        mkdir($upload_dir, 0777, true);
                    $upload_dir .= '/avatar';
                    if (!file_exists($upload_dir))
                        mkdir($upload_dir, 0777, true);

                    $tmp_name = $_FILES['ava']['tmp_name'];
                    $name = $upload_dir . '/' . date('HisdmY') . '_' . $row['user_id'] . '.png';
                    $move = move_uploaded_file($tmp_name, $name);
                    if ($move) {
                        updateAva($pdo, $name, $_SESSION['user_id']);
                        if (isset($row['avatar']) && $row['avatar'] && $row['avatar'] != 'img/icon/user.svg')
                            unlink($row['avatar']);
                    }
                    changeNotific($pdo, $notific, $_SESSION['user_id']);
                    header('Location: profile.php?user=' . $_SESSION['name'] . '&page=1&posts');
                    return;
                }
            }
            if (isset($_POST['submit']) && $_POST['submit'] == 'Cancel') {
                header('Location: profile.php?user=' . $_SESSION['name'] . '&page=1&posts');
                return;
            }
        }
    } else {
        header('Location: index.php');
        return;
    }

    require_once 'components/header.php';
    require_once 'components/edit-view.php';
    require_once 'components/footer.php';
}
