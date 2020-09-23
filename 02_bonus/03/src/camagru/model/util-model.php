<?php

function getUserName($pdo, $name)
{
    $stmt = $pdo->prepare('SELECT name FROM Users WHERE name = :nm');
    $stmt->execute(array(':nm' => $name));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getEmail($pdo, $email)
{
    $stmt = $pdo->prepare('SELECT email FROM Users WHERE email = :em');
    $stmt->execute(array(':em' => $email));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getPassword($pdo, $hash)
{
    $stmt = $pdo->prepare('SELECT name FROM Users WHERE password = :ps');
    $stmt->execute(array(':ps' => $hash));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function deleteUser($pdo)
{
    $pdo->query('DELETE FROM Users WHERE confirm = "no" AND created_at_user < (NOW() - INTERVAL 1 DAY)');
}

function getConfirmInfo($pdo, $name)
{
    $stmt = $pdo->prepare('SELECT confirm FROM Users WHERE name = :nm');
    $stmt->execute(array(':nm' => $name));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
