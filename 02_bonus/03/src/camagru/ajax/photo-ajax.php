<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once '../util.php';
require_once '../model/photo-model.php';

$row = getUserInfo($pdo, $_POST['img']);

if (isset($_POST['comment'])) /* valid */ {
    addComment($pdo, $_SESSION['user_id'], $_POST['img'], nl2br(trim(mb_substr(htmlentities($_POST['comment']), 0, 80))));
    if ($row['notification'] == 'yes') {
        $page = 'comments.php';
        $new_comment = array($_POST['comment'], $_POST['img']);
        sendNotification($row['email'], $new_comment, $page);
    }
    return;
}

if (isset($_POST['com_id'])) {
    if (isset($_POST['com_id']) && $_POST['com_id'] && $_SESSION['user_id']) {
        delComment($pdo, $_POST['com_id']);
    }
    return;
}

if (isset($_POST['img']) && isset($_POST['get'])) {
    $add_comm = getImageInfo($pdo, $_POST['img']);
    $comments = $add_comm->rowCount();
    if ($comments > 0) {
        for ($i = 1; $i <= $comments; $i++) {
            $comment = $add_comm->fetch(PDO::FETCH_ASSOC);
            if ($comment !== false) {
                echo '<article>';
                echo '<div class="photo-user__block">';
                echo '<a href="profile.php?user=' . htmlentities($comment['name']) . '&page=1&posts">';
                echo '<img class="photo-user__block-img" src="' . htmlentities($comment['avatar']) . '">';
                echo '</a></div>';
                echo '<div class="page_info_user"><span>' . htmlentities($comment['name']) . '</span> '; /* проверить */
                echo '<time>' . date("d M Y G:i", strtotime($comment['created_at_comment'])) . '</time>';
                if ($_SESSION['user_id'] == $row['user_id'] || $_SESSION['user_id'] == $comment['user_id']) {
                    echo '<a class="modal-link" href="#openModal' . $i . '">';
                    echo '<img class="page-img_delete" src="img/icon/cancel.svg">';
                    echo '</a>';
                    echo '<div id="openModal' . $i . '" class="modal">';
                    echo '<div class="modal-dialog">';
                    echo '<div class="modal-content">';
                    echo '<p class="modal-title">Delete comment?</p>';
                    echo '<p>This can’t be undone and it will be removed from your profile.</p>';
                    echo '<div>';
                    echo '<input id="' . htmlentities($comment['comment_id']) . '" type="submit" name="delete" class="btn-blue btn-confirm-del" value="Delete">';
                    echo '<input type="submit" name="close" class="btn-gray btn-close" value="Close">';
                    echo '</div></div></div></div>';
                }
                echo '<p>' . $comment['comment'] . '</p>';
                echo '</div></article>';
            }
        }
    } else
        echo '<p class="count-message">There is no comment yet</p>';
    return;
}

if (isset($_POST['like']) && $_POST['like'] == 'get') {
    header("Content-Type: application/json; charset=UTF-8");
    $response = new stdClass();
    $response->likes = getCountLikes($pdo, $_POST['img']);

    if (isLiked($pdo, $_SESSION['user_id'], $_POST['img']))
        $response->isLiked = true;
    else
        $response->isLiked = false;

    $json = json_encode($response);
    echo $json;
    return;
}

if (isset($_POST['like']) && $_POST['like'] == 'set') {
    header("Content-Type: application/json; charset=UTF-8");
    $response = new stdClass();
    $response->isLiked = false;

    if (!isLiked($pdo, $_SESSION['user_id'], $_POST['img'])) {
        $response->isLiked = true;
        addLike($pdo, $_SESSION['user_id'], $_POST['img']);
    } else {
        $response->isLiked = false;
        delLike($pdo, $_SESSION['user_id'], $_POST['img']);
    }

    updateLikes($pdo, $_POST['img']);
    $response->likes = getCountLikes($pdo, $_POST['img']);

    $json = json_encode($response);
    echo $json;
    return;
}
header('Location: ../index.php');