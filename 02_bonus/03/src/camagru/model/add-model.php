<?php

function addPhoto($pdo, $userId, $newSrc, $desription)
{
    $stmt = $pdo->prepare('INSERT INTO Photo (user_id, path, description_photo) VALUES (:uid, :src, :dp)');
    $stmt->execute(array(
        ':uid' => $userId,
        ':src' => $newSrc,
        ':dp' => nl2br(trim(mb_substr($desription, 0, 80)))
    ));
}

function getTools($pdo, $table)
{
    $stmt = 'SELECT path FROM ' . $table;
    return $pdo->query($stmt);
}
