<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once "util.php";
require_once "model/gallery-model.php";

flashMessages();

if (isset($_SESSION['confirm']) && $_SESSION['confirm'] == 'no') {

    if (checkConfirmUser($pdo) == false) {
        if (deleteNotConfirmUser($pdo, $_SESSION['name'])) {
            header('Location: index.php');
            return;
        }
    }
}

$offset = 9;
$pages = getPages($pdo, $offset);

$bord = $pages;
if ($pages == 0)
    $bord = 1;
if (!isset($_GET['sort']) || ($_GET['sort'] != 'all' && $_GET['sort'] != 'popular' && $_GET['sort'] != 'new')
    || ($_GET['page'] <= 0 || $_GET['page'] > $bord)) {
    header('Location: gallery.php?sort=all&page=1');
    return;
}

if ($pages) {
    if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $pages) {
        $limit = $offset * $_GET['page'];
        if ($_GET['sort'] == 'popular')
            $type = ' ORDER BY likes DESC';
        elseif ($_GET['sort'] == 'new')
            $type = ' ORDER BY created_at_photo DESC';
        else
            $type = null;
        $stmt = getSortImg($pdo, $type, $limit, $offset);
    } else
        header('Location: gallery.php?sort=all&page=1');
}

require_once "components/header.php";
require_once "components/gallery-view.php";
$pageName = 'gallery';
paginationList($pageName, $pages);
require_once "components/footer.php";
