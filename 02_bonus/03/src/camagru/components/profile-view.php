<section class="profile align_footer">
    <div class="container">
        <article class="profile-head">
            <div class="profile-head__img">
                <img src="<?= htmlentities($row['avatar']) ?>">
            </div>
            <div class="profile-data">
                <h2><?= htmlentities($row['name']) ?></h2>
                <?php
                if (isset($_SESSION['name']) && $_SESSION['name'] == $row['name'])
                    echo '<a href="edit.php?user=' . htmlentities($row['name']) . '" class="profile-btn btn-blue">Edit profile</a>';
                ?>
                <table>
                    <tr>
                        <th>Posts</th>
                        <th>Carma</th>
                        <th>Likes</th>
                    </tr>
                    <tr>
                        <td><?= htmlentities($posts) ?></td>
                        <td><?= $likes['likes'] ?></td>
                        <td><?= htmlentities($favorites) ?></td>
                    </tr>
                </table>
                <span><?= htmlentities($row['description_user']) ?></span>
            </div>
        </article>

        <div class="profile-nav">
            <a href="profile.php?user=<?= $row['name'] ?>&page=1&posts" class="profile-nav-not">Posts</a>
            <a href="profile.php?user=<?= $row['name'] ?>&page=1&favorites" class="profile-nav-not">Likes</a>
        </div>

        <article class="profile-photos">
            <?php
            if (isset($_GET['posts']) && isset($photos)) {
                for ($i = 1; $i <= $offset; $i++) {
                    $photo = $photos->fetch(PDO::FETCH_ASSOC);
                    if ($photo !== false) {
                        if (isset($flag) && $flag == 1) {
                            echo '<a class="profile-photos__link" href="add.php"><p class="profile-photos__link-img profile-addphoto__block"><img class="profile-addphoto" src="img/icon/plus.svg">Add photo</p></a>' . "\n";
                            $i++;
                            $flag = 0;
                        }
                        echo '<a class="profile-photos__link" href="photo.php?img=' . htmlentities($photo['img_id']) . '">';
                        echo '<img class="profile-photos__link-img" src="' . htmlentities($photo['path']) . '" >';
                        echo '</a>' . "\n";
                        if ($_SESSION['user_id'] == $row['user_id']) {
                            echo '<a class="profile-photos__link-delete" href="#openModal' . $i . '">';
                            echo '<img class="page_img_delete" src="img/icon/cancel.svg"></a>';
                            echo '<div id="openModal' . $i . '" class="modal">';
                            echo '<div class="modal-dialog">';
                            echo '<div class="modal-content">';
                            echo '<p class="modal-title">Delete photo?</p>';
                            echo '<p>This can’t be undone and it will be removed from your profile.</p>';
                            echo '<form method="post" action="profile.php?user=' . $_GET['user'] . '&page=1&posts">';
                            echo '<input type="hidden" name="img_id" value="' . htmlentities($photo['img_id']) . '">';
                            echo '<input type="submit" name="delete" class="btn-blue" value="Delete">';
                            echo '<input type="submit" name="close" class="btn-gray" value="Close">';
                            echo '</form>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                }
            } elseif (isset($_GET['posts']) && $posts == 0 && isset($_SESSION['name']) && $_SESSION['name'] == $row['name'])
                echo '<a class="profile-photos__link" href="add.php"><p class="profile-photos__link-img profile-addphoto__block"><img class="profile-addphoto" src="img/icon/plus.svg">Add photo</p></a>' . "\n";
            elseif (isset($_GET['posts']) && $posts == 0)
                echo '<p class="count-message">No photos :C</p>';

            if (isset($_GET['favorites']) && isset($photo_likes)) {
                for ($i = 1; $i <= $offset; $i++) {
                    $favorite = $photo_likes->fetch(PDO::FETCH_ASSOC);
                    if ($favorite !== false) {
                        echo '<a id="' . $i . '"class="profile-photos__link" href="photo.php?img=' . htmlentities($favorite['img_id']) . '">';
                        echo '<img class="profile-photos__link-img" src="' . htmlentities($favorite['path']) . '" >';
                        echo '</a>';
                    }
                }
            } elseif (isset($_GET['favorites']) && $favorites == 0)
                echo '<p class="count-message">No likes >.></p>';
            ?>
        </article>
    </div>
</section>

<script src="js/profile.js"></script>