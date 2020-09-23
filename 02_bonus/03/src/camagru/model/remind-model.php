<?php

function findEmail($pdo, $email)
{
    $stmt = $pdo->prepare('SELECT name, email FROM Users WHERE email = :em');
    $stmt->execute(array(':em' => $email));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function findEmailByName($pdo, $name)
{
    $stmt = $pdo->prepare('SELECT email FROM Users WHERE name = :nm');
    $stmt->execute(array(':nm' => $name));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function changePass($pdo, $pass, $login)
{
    $stmt = $pdo->prepare('UPDATE Users SET password = :ps WHERE name = :nm');
    $stmt->execute(array(
        ':ps' => $pass,
        ':nm' => $login
    ));
}

function checkHash($pdo, $hash)
{
    $stmt = $pdo->prepare('SELECT user_id, confirm FROM Users WHERE hash = :hs');
    $stmt->execute(array(':hs' => $hash));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function changeConfirm($pdo, $uid)
{
    $stmt = $pdo->prepare('UPDATE Users SET confirm = :cf WHERE user_id = :uid');
    $stmt->execute(array(
        ':cf' => 'yes',
        ':uid' => $uid
    ));
}
