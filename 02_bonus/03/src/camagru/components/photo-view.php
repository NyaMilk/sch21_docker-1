<section class="page-img align_footer">
    <div class="container">
        <div class="page-img__photo">
            <div class="page-img__photo-block">
                <img src="<?= htmlentities($row['path']) ?>">
                <div class="page-img__photo-likes">
                    <button type="submit" name="likes">
                        <img class="photo-like" src="img/icon/valentines-heart.svg" alt="like">
                    </button>
                </div>
            </div>

            <div class="page-img__photo-info">
                <p><span><?= htmlentities(changeNumber($view['views'])) ?></span> Views</p>
                <p><span id="likes"></span> Likes</p>

                <div>
                    <p>Share:</p>
                    <a class="btn btn-default btn-lg" target="_blank" title="facebook" href="http://www.facebook.com/sharer.php?u=http://localhost:8080/photo.php?img=<?= $_GET['img'] ?>&text=True%20story">
                        <img src="../img/icon/facebook.svg" alt="fb">
                    </a>
                    <a class="btn btn-default btn-lg" target="_blank" title="twitter" href="http://twitter.com/share?url=http://localhost:8080/photo.php?img=<?= $_GET['img'] ?>&text=True%20story">
                        <img src="../img/icon/twitter.svg" alt="tw">
                    </a>
                    <a class="btn btn-default btn-lg" target="_blank" title="vk" href="http://vk.com/share.php?url=http://localhost:8080/photo.php?img=<?= $_GET['img'] ?>&text=True%20story">
                        <img src="../img/icon/vk.svg" alt="vk">
                    </a>
                </div>

            </div>

            <p class="page-img__photo-description"><?= $row['description_photo'] ?></p>
            <time><?= date("d M Y G:i", strtotime($row['created_at_photo'])) ?></time>

            <a class="page-img__photo-user" href="profile.php?user=<?= htmlentities($row['name']) ?>&page=1&posts">
                <div class="photo-user__block">
                    <img class="photo-user__block-img" src="<?= htmlentities($row['avatar']) ?>">
                </div>
                <p><?= htmlentities($row['name']) ?></p>
            </a>
        </div>

        <div class="page-img__comments">
            <div class="page-img__comments-set">
                <h2>Comments</h2>
                <div class="page-img__comments-set__form">
                    <div class="photo-com">
                        <span class="span_comment">No more than 80 characters</span>
                        <textarea id="text" name="text_comment" rows="1" placeholder="Leave a comment"></textarea>
                    </div>

                    <button class="btn-blue btn-save" type="submit">Send</button>
                </div>
            </div>

            <div class="page-img__comments-list"></div>
        </div>
    </div>
</section>

<script src="js/photo.js"></script>