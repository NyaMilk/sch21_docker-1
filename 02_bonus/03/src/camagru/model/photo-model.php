<?php

function getValidImg($pdo, $imgId)
{
    $stmt = $pdo->prepare('SELECT img_id FROM Photo WHERE img_id = :iid');
    $stmt->execute(array(':iid' => $imgId));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getView($pdo, $imgId)
{
    $stmt = $pdo->prepare('SELECT img_id FROM Views WHERE img_id = :iid AND date_views = CURDATE()');
    $stmt->execute(array(':iid' => $imgId));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function setView($pdo, $imgId)
{
    $stmt = $pdo->prepare('INSERT INTO Views SET counter = 1, img_id = :iid, date_views = CURDATE()');
    $stmt->execute(array(':iid' => $imgId));
}

function updateView($pdo, $imgId)
{
    $stmt = $pdo->prepare('UPDATE Views SET counter = counter + 1 WHERE img_id = :iid AND date_views = CURDATE()');
    $stmt->execute(array(':iid' => $imgId));
}

function getSumViews($pdo, $imgId)
{
    $stmt = $pdo->prepare('SELECT SUM(counter) views FROM Views WHERE img_id = :iid');
    $stmt->execute(array(':iid' => $imgId));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getPhotoInfo($pdo, $imgId)
{
    $stmt = $pdo->prepare('SELECT u.user_id, u.name, u.avatar, u.email, u.notification, p.likes, p.path, p.description_photo, p.created_at_photo
    FROM Users u JOIN Photo p ON u.user_id = p.user_id WHERE p.img_id = :iid');
    $stmt->execute(array(':iid' => $imgId));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUserInfo($pdo, $imgId)
{
    $stmt = $pdo->prepare('SELECT u.user_id, u.email, u.notification FROM Users u JOIN Photo p ON u.user_id = p.user_id WHERE p.img_id = :iid');
    $stmt->execute(array(':iid' => $imgId));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addComment($pdo, $userId, $imgId, $comment)
{
    $stmt = $pdo->prepare('INSERT INTO Comment (user_id, img_id, comment) VALUES (:uid, :iid, :cm)');
    $stmt->execute(array(
        ':uid' => $userId,
        ':iid' => $imgId,
        ':cm' => $comment
    ));
}

function delComment($pdo, $comId)
{
    $stmt = $pdo->prepare('DELETE FROM Comment WHERE comment_id = :cid');
    $stmt->execute(array(':cid' => $comId));
}

function getImageInfo($pdo, $imgId)
{
    $add_comm = $pdo->prepare('SELECT c.user_id, name, comment_id, comment, avatar, created_at_comment
    FROM Comment c JOIN Users u ON c.user_id = u.user_id WHERE img_id = :iid ORDER BY comment_id');
    $add_comm->execute(array(':iid' => $imgId));
    return $add_comm;
}

function isLiked($pdo, $userId, $imgId)
{
    $stmt = $pdo->prepare('SELECT user_id FROM Likes WHERE user_id = :uid AND img_id = :iid');
    $stmt->execute(array(
        ':uid' => $userId,
        ':iid' => $imgId
    ));
    $stmt->fetch(PDO::FETCH_ASSOC);
    if ($stmt->rowCount() == 0)
        return false;
    return true;
}

function getCountLikes($pdo, $imgId)
{
    $stmt = $pdo->prepare('SELECT img_id FROM Likes WHERE img_id = :iid');
    $stmt->execute(array(':iid' => $imgId));
    return $stmt->rowCount();
}

function addLike($pdo, $userId, $imgId)
{
    $stmt = $pdo->prepare('INSERT INTO Likes (user_id, img_id) VALUES (:uid, :iid)');
    $stmt->execute(array(
        ':uid' => $userId,
        ':iid' => $imgId
    ));
}

function delLike($pdo, $userId, $imgId)
{
    $stmt = $pdo->prepare('DELETE FROM Likes WHERE user_id = :uid AND img_id = :iid');
    $stmt->execute(array(
        ':uid' => $userId,
        ':iid' => $imgId
    ));
}

function updateLikes($pdo, $imgId)
{
    $count = getCountLikes($pdo, $imgId);
    $stmt = $pdo->prepare('UPDATE Photo SET likes = :c WHERE img_id = :iid');
    $stmt->execute(array(
        ':c' => $count,
        ':iid' => $imgId
    ));
}
