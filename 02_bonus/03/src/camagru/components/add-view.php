<section class="photo-edit align_footer">
    <div class="container">
        <div class="photo-upload">
            <label class="custom-file-upload">
                <img src="img/icon/transfer.svg" alt="from computer">
                <p>Upload</p>
                <input id='file-upload' type="file">
            </label>

            <label class="custom-file-upload">
                <img src="img/icon/photo-camera.svg" alt="from camera">
                <p>Camera</p>
                <input id="startbutton" type="button">
            </label>
        </div>

        <div class="photo-edit__canvas">
            <img id="preview" src="img/preview.png" alt="preview">

            <img id="origin" src="img/preview.png">
            <video id="video"></video>
            <canvas id="canvas"></canvas>
            <form><input type='button' id='snapshot' value="snapshot"></form>
        </div>

        <div class="photo-upload">
            <label class="custom-file-upload">
                <img src="img/icon/shoot.svg" alt="from computer">
                <p>Shoot</p>
                <input id='shoot' type="button">
            </label>

            <label id="discard" class="custom-file-upload">
                <img src="img/icon/filter.svg" alt="from camera">
                <p>Clear</p>
            </label>
        </div>

        <div class="photo-filters">
            <h2>filters</h2>
            <div class="photo-carousel">
                <?php
                $i = 0;
                while ($row = $stmt_filters->fetch(PDO::FETCH_ASSOC))
                    echo '<div id="' . $i++ . '" class="filter"><img class="carousel-item" src="' . $row['path'] . '"></div>';
                ?>
            </div>
        </div>

        <div class="photo-stickers">
            <h2>stickers</h2>
            <div id="stick" class="photo-carousel">
                <?php
                $i = 0;
                while ($row = $stmt_stickers->fetch(PDO::FETCH_ASSOC))
                    echo '<div id="' . $i++ . '" class="sticker"><img class="carousel-item" src="' . $row['path'] . '"></div>';
                ?>
            </div>
        </div>

        <form method="post" enctype="multipart/form-data">
            <h2>description</h2>
            <div class="photo-dsc">
                <span class="span_photo">No more than 80 characters</span>
                <textarea name="text_photo" rows="3" placeholder="Leave a description"></textarea>
            </div>

            <input id="save" name="src" type="hidden" value="img/preview.png">
            <input id="save_btn" class="btn-blue btn-save" name="save" type="submit" value="Save" disabled>
            <input class="btn-gray" name="close" type="submit" value="Close">
        </form>
    </div>
</section>

<script src="js/add.js"></script>