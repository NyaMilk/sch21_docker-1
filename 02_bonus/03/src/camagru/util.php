<?php
require_once 'config/pdo.php';
require_once 'model/util-model.php';

$salt = 'XyZzy12*_';

function flashMessages()
{
    if (isset($_SESSION['error'])) {
        echo '<script>alert("' . htmlentities($_SESSION['error']) . '");</script>';
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<script>alert("' . htmlentities($_SESSION['success']) . '");</script>';
        unset($_SESSION['success']);
    }
}

function checkSignIn()
{
    if (!isset($_SESSION['name'])) {
        $_SESSION['error'] = 'You must sign in the site.';
        header('Location: index.php');
        return false;
    }
    if (isset($_SESSION['confirm']) && $_SESSION['confirm'] == 'no') {
        $_SESSION['error'] = 'Confirm your email address.';
        header('Location: gallery.php?sort=all&page=1');
        return false;
    }
    return true;
}

function checkLenInput($value, $msg)
{
    if (isset($_POST[$value]) && strlen($_POST[$value]) > 80) {
        $_SESSION['error'] = $msg . ' must be no more than 80 characters';
        return false;
    }
    return true;
}
function validateInputName($value)
{
    $regex = '/^(?=[a-zA-Z0-9._]{6,80}$)(?!.*[_.]{2})[^_.].*[^_.]$/';
    if (preg_match($regex, $value))
        return true;
    return false;
}

function validateInputPass($password)
{
    $uppercase = preg_match('/[A-Z]/', $password);
    $lowercase = preg_match('/[a-z]/', $password);
    $number = preg_match('/[0-9]/', $password);
    $specialChars = preg_match('/[^\w]/', $password);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 6)
        return false;
    return true;
}

function checkUserName($pdo, $page)
{
    if (checkLenInput('username_up', 'Username') == false) {
        header('Location: ' . $page);
        return false;
    }

    if (!validateInputName($_POST['username_up'])) {
        $_SESSION['error'] = 'This username is invalid. Username must contain 6-80 alphabet characters, . or _.';
        header('Location: ' . $page);
        return false;
    }

    if (getUserName($pdo, $_POST['username_up'])) {
        $_SESSION['error'] = 'This username is already taken';
        header('Location: ' . $page);
        return false;
    }
    if ($page == 'index.php')
        if (checkEmail($pdo, $page) == false)
            return false;
    return true;
}

function checkEmail($pdo, $page)
{
    if (!checkLenInput('email_up', 'Email')) {
        header('Location: ' . $page);
        return false;
    }

    if (!filter_var($_POST['email_up'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'This email address is invalid';
        header('Location: ' . $page);
        return false;
    }

    if (getEmail($pdo, trim($_POST['email_up']))) {
        $_SESSION['error'] = 'This email address is already taken';
        header('Location: ' . $page);
        return false;
    }
    if ($page == 'index.php')
        if (checkPassword($pdo, $page) == false)
            return false;
    return true;
}

function checkPassword($pdo, $page)
{
    if (!checkLenInput('repass_up', 'Password')) {
        header('Location: ' . $page);
        return false;
    }

    if (!validateInputPass($_POST['repass_up'])) {
        $_SESSION['error'] = 'Password must be at least 6 characters in length and must include at least one upper case letter, one number, and one special character.';
        header('Location: ' . $page);
        return false;
    }

    if ($page == 'index.php' || strpos($page, 'remind.php') !== false) {
        if ($_POST['pass_up'] != $_POST['repass_up']) {
            $_SESSION['error'] = 'Password do not match';
            header('Location: ' . $page);
            return false;
        }
    } else {
        $salt = 'XyZzy12*_';
        if (!getPassword($pdo, hash('sha512', $salt . $_POST['pass_up']))) {
            $_SESSION['error'] = 'Wrong password';
            header('Location: ' . $page);
            return false;
        }
    }
    return true;
}

function deleteNotConfirmUser($pdo, $name)
{
    deleteUser($pdo);
    if (!getUserName($pdo, $name)) {
        session_destroy();
        return true;
    }
    return false;
}

function checkConfirmUser($pdo)
{
    if ($row = getConfirmInfo($pdo, $_SESSION['name'])) {
        if ($row['confirm'] == 'yes') {
            $_SESSION['confirm'] = $row['confirm'];
            return true;
        }
        return false;
    }
}

function paginationList($pageName, $pages, $text = null)
{
    if (isset($_GET['user']))
        $getter = 'user=' . $_GET['user'];
    if (isset($_GET['sort']))
        $getter = 'sort=' . $_GET['sort'];

    echo '<div class="pagination">';
    if ($pages > 1 && isset($_GET['page'])) {
        if (!is_numeric($_GET['page']))
            $_GET['page'] = 1;
        if ($_GET['page'] == 1) {
            $count = $_GET['page'] + 2;
            $i = $_GET['page'];
        } else {
            $count = $_GET['page'] + 1;
            $i = $_GET['page'] - 1;
        }
        if ($_GET['page'] == $pages)
            $i = $_GET['page'] - 2;
        if ($_GET['page'] > 1) {
            echo '<a href="' . $pageName . '.php?' . $getter . '&page=1' . $text . '">&#171;</a>';
            echo '<a href="' . $pageName . '.php?' . $getter . '&page=' . ($_GET['page'] - 1) . $text . '">&#8249;</a>';
        } else {
            echo '<p>&#171;</p>';
            echo '<p>&#8249;</p>';
        }
        for ($i; $i <= $count; $i++) {
            if ($i < 1)
                continue;
            if ($i <= $pages) {
                if ($i == $_GET['page'])
                    echo '<a href="' . $pageName . '.php?' . $getter . '&page=' . $i . $text . '" class="active">' . $i . '</a>';
                else
                    echo '<a href="' . $pageName . '.php?' . $getter . '&page=' . $i . $text . '">' . $i . '</a>';
            }
        }
        if ($_GET['page'] + 1 <= $pages) {
            echo '<a href="' . $pageName . '.php?' . $getter . '&page=' . ($_GET['page'] + 1) . $text . '">&#8250;</a>';
            echo '<a href="' . $pageName . '.php?' . $getter . '&page=' . $pages . $text . '">&#187;</a>';
        } else {
            echo '<p>&#8250;</p>';
            echo '<p>&#187;</p>';
        }
    }
    echo '</div>';
}

function sendNotification($value, $elem, $page)
{
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: amilyukovadev@gmail.com\r\n";

    if ($page == 'index.php') {
        $email = $_POST[$value];
        $subject = 'Confirm email address';
        $message = '<p>To complete the sign-up process, please follow the <a href="http://localhost:8080/confirm.php?hash=' . $elem . '">link.</a></p>';
    } elseif ($page == 'remind.php') {
        $email = $value;
        $subject = 'Remind username and password';
        $message = '<p>Your username: ' . htmlentities($elem[0]) . '.</p>';
        $message .= '<p>To reset your password please follow the <a href="http://localhost:8080/remind.php?name=' . htmlentities($elem[0]) . '&uniq=' . md5($elem[1]) . '">link.</a></p>';
    } elseif ($page == 'comments.php') {
        echo $elem['0'];
        $email = $value;
        $subject = 'New comment';
        $message = '<p>You have new comment on <a href="http://localhost:8080/photo.php?img=' . $elem[1] . '">photo.</a></p>';
        $message .= '<blockquote><p>' . htmlentities($elem[0]) . '</p>';
        $message .= '<cite>author: ' . $_SESSION['name'] . '</cite></blockquote>';
    }
    mail($email, $subject, $message, $headers);
}

function changeNumber($nb)
{
    if ($nb >= 1000000) {
        return floor($nb / 1000000) . 'kk';
    } elseif ($nb >= 1000) {
        return floor($nb / 1000) . 'k';
    } else {
        return $nb;
    }
}
