<?php

function getUserData($pdo, $name)
{
    $stmt = $pdo->prepare('SELECT user_id, name, email, password, avatar, description_user, notification FROM Users WHERE name = :nm');
    $stmt->execute(array(':nm' => $name));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function changeNotific($pdo, $notific, $userId)
{
    $stmt = $pdo->prepare('UPDATE Users SET notification = :nf WHERE user_id = :uid');
    $stmt->execute(array(
        ':nf' => $notific,
        ':uid' => $userId
    ));
}

function changePass($pdo, $hash, $userId)
{
    $stmt = $pdo->prepare('UPDATE Users SET password = :ps WHERE user_id = :uid');
    $stmt->execute(array(
        ':ps' => $hash,
        ':uid' => $userId
    ));
}

function updateAll($pdo, $name, $email, $desc, $userId)
{
    $stmt = $pdo->prepare('UPDATE Users SET name = :nm, email = :em, description_user = :du WHERE user_id = :uid');
    $stmt->execute(array(
        ':nm' => $name,
        ':em' => $email,
        ':du' => $desc,
        ':uid' => $userId
    ));
}

function updateAva($pdo, $newSrc, $userId)
{
    $stmt = $pdo->prepare('UPDATE Users SET avatar = :av WHERE user_id = :uid');
    $stmt->execute(array(
        ':av' => $newSrc,
        ':uid' => $userId
    ));
}
