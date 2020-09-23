<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once "util.php";
require_once "model/profile-model.php";

if (checkSignIn()) {
    if (isset($_GET['user']) && isset($_GET['page']) && (isset($_GET['posts']) || isset($_GET['favorites']))) {
        if (isset($_POST['delete'])) {
            if (isset($_POST['img_id']) && $_POST['img_id'] && $_SESSION['user_id']) {
                $path = getImgPath($pdo, $_POST['img_id']);
                if (delPhoto($pdo, $_POST['img_id'])) {
                    if ($path['path'])
                        unlink($path['path']);
                }
                header('Location: profile.php?user=' . $_SESSION['name'] . '&page=1&posts');
            }
        }

        $row = getUser($pdo, $_GET['user']);
        if ($row !== false) {
            $likes = getSumLikes($pdo, $row['user_id']);
            if (!$likes['likes'])
                $likes['likes'] = 0;

            $offset = 6;
            $posts = getCountPosts($pdo, $row['user_id']);
            $pages = ceil(($posts + 1) / $offset);

            $favorites = getCountFavorites($pdo, $row['user_id']);
            $pages_likes = ceil($favorites / $offset);

            if (isset($_GET['favorites']))
                $pages = $pages_likes + 1;
            if ((isset($_GET['page']) && ($_GET['page'] <= 0 || $_GET['page'] > $pages || !is_numeric($_GET['page'])))
                || ((isset($_GET['posts']) && strlen($_GET['posts']) != 0)
                    || (isset($_GET['favorites']) && strlen($_GET['favorites']) != 0))
            ) {
                header('Location: profile.php?user=' . $_GET['user'] . '&page=1&posts');
                return;
            }

            if ($posts && isset($_GET['posts'])) {
                if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $pages) {
                    $limit = $offset * $_GET['page'];
                    if (isset($_SESSION['name']) && $_SESSION['name'] == $row['name']) {
                        $limit--;
                        if ($_GET['page'] == 1) {
                            $photos = getPosts($pdo, $limit);
                            $flag = 1;
                        } else
                            $photos = getPosts($pdo, $limit, $offset);
                    } else
                        $photos = getPosts($pdo, $limit, $offset);
                    $photos->execute(array(':uid' => $row['user_id']));
                } else {
                    header('Location: profile.php?user=' . $_GET['user'] . '&page=1&posts');
                    return;
                }
            }

            if ($favorites && isset($_GET['favorites'])) {
                if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $pages_likes) {
                    $limit = $offset * $_GET['page'];
                    if (isset($_SESSION['name']) && $_SESSION['name'] == $row['name']) {
                        if ($_GET['page'] == 1) {
                            $photo_likes = getFavorites($pdo, $limit);
                        } else
                            $photo_likes = getFavorites($pdo, $limit, $offset);
                    } else
                        $photo_likes = getFavorites($pdo, $limit, $offset);
                    $photo_likes->execute(array(':uid' => $row['user_id']));
                } else {
                    header('Location: profile.php?user=' . $_GET['user'] . '&page=1&favorites');
                    return;
                }
            }

            require_once "components/header.php";
            require_once "components/profile-view.php";
            $page = 'profile';
            if (isset($_GET['posts'])) {
                $text = '&posts';
            } elseif (isset($_GET['favorites'])) {
                $text = '&favorites';
                $pages =  $pages_likes;
            }
            paginationList($page, $pages, $text);
            require_once "components/footer.php";
        } else {
            $_SESSION['error'] = 'Error profile. Please contact the site administrator.';
            header('Location: gallery.php?sort=all&page=1');
            return;
        }
    } else {
        header('Location: profile.php?user=' . $_GET['user'] . '&page=1&posts');
        return;
    }
}
