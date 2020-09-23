<?php

function delPhoto($pdo, $imageId)
{
    $stmt = $pdo->prepare('DELETE FROM Photo WHERE img_id = :iid');
    return $stmt->execute(array(':iid' => $imageId));
}

function getImgPath($pdo, $imageId)
{
    $stmt = $pdo->prepare('SELECT path FROM Photo WHERE img_id = :iid');
    $stmt->execute(array(':iid' => $imageId));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUser($pdo, $login)
{
    $stmt = $pdo->prepare('SELECT user_id, name, avatar, description_user FROM Users WHERE name = :nm');
    $stmt->execute(array(':nm' => $login));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getSumLikes($pdo, $userId)
{
    $stmt = $pdo->prepare('SELECT SUM(likes) likes FROM Photo WHERE user_id = :uid');
    $stmt->execute(array(':uid' => $userId));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getCountPosts($pdo, $userId)
{
    $stmt = $pdo->prepare('SELECT user_id FROM Photo WHERE user_id = :uid');
    $stmt->execute(array(':uid' => $userId));
    return $stmt->rowCount();
}

function getPosts($pdo, $limit, $offset = null)
{
    $sql = 'SELECT img_id, path FROM Photo WHERE user_id = :uid ORDER BY created_at_photo DESC ';
    if ($offset)
        $sql = $sql . ' LIMIT ' . ($limit - $offset) . ', ' . $limit;
    else
        $sql = $sql . ' LIMIT ' . '0, ' . $limit;
    return $pdo->prepare($sql);
}

function getCountFavorites($pdo, $userId)
{
    $stmt = $pdo->prepare('SELECT img_id FROM Likes WHERE user_id = :uid');
    $stmt->execute(array(':uid' => $userId));
    return $stmt->rowCount();
}

function getFavorites($pdo, $limit, $offset = null)
{
    $sql = 'SELECT l.img_id, p.path FROM Likes l JOIN Photo p WHERE l.user_id = :uid AND l.img_id = p.img_id ORDER BY l.created_at_like DESC ';
    if ($offset)
        $sql = $sql . ' LIMIT ' . ($limit - $offset) . ', ' . $limit;
    else
        $sql = $sql . ' LIMIT ' . '0, ' . $limit;
    return $pdo->prepare($sql);
}
