<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once "util.php";
require_once "model/add-model.php";

flashMessages();

if (checkSignIn()) {

    if (isset($_POST['close']) && $_POST['close']) {
        header('Location: profile.php?user=' . $_SESSION['name'] . '&page=1&posts');
        return;
    }

    if (isset($_POST['save']) && $_POST['save']) {
        $upload_dir = 'images/' . $_SESSION['user_id'];
        if (!file_exists($upload_dir))
            mkdir($upload_dir, 0777, true);
        $upload_dir .= '/post';
        if (!file_exists($upload_dir))
            mkdir($upload_dir, 0777, true);
        $new_src = $upload_dir . '/' . date('HisdmY') . '_' . $_SESSION['user_id'] . '.png';
        file_put_contents($new_src, file_get_contents($_POST['src']));

        addPhoto($pdo, $_SESSION['user_id'], $new_src, htmlentities($_POST['text_photo']));
        header('Location: profile.php?user=' . $_SESSION['name'] . '&page=1&posts');
        return;
    }

    $stmt_filters = getTools($pdo, "Filters");
    $stmt_stickers = getTools($pdo, "Stickers");

    require_once "components/header.php";
    require_once "components/add-view.php";
    require_once "components/footer.php";
}
