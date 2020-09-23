<?php

function checkUser($pdo, $login, $pass)
{
    $stmt = $pdo->prepare('SELECT user_id, name, confirm FROM Users WHERE name = :nm AND password = :pw');
    $stmt->execute(array(
        ':nm' => $login,
        ':pw' => $pass
    ));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addUser($pdo, $login, $email, $pass, $hash)
{
    $stmt = $pdo->prepare('INSERT INTO Users (name, email, password, hash) VALUES (:nm, :em, :ps, :hs)');
    $stmt->execute(array(
        ':nm' => $login,
        ':em' => $email,
        ':ps' => $pass,
        ':hs' => $hash
    ));
}
