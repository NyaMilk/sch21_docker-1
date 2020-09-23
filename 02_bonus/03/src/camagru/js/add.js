"use strict";

(function () {
    let width = 900;
    let height = 0;
    let streaming = false;
    let isInited = false;

    let photo = document.getElementById('origin');
    let preview = document.getElementById('preview');
    let video = document.getElementById('video');
    let canvas = document.getElementById('canvas');
    let shoot = document.getElementById('shoot');
    let discard = document.getElementById('discard');
    let save = document.getElementById('save');
    let save_btn = document.getElementById('save_btn');

    let file_upload = document.getElementById('file-upload');

    let snapchat = {
        filter: "none",
        isClicked: false,
        stickers: []
    };

    let sticker_width = 100;
    let sticker_height = 100;
    let start_pos_x = 100;
    let start_pos_y = 100;

    function startup() {
        if (isInited)
            destroyFiltersAndStickers();
        save_btn.disabled = true;
        save_btn.style.background = "#E6E7ED";
        save_btn.style.borderColor = "#E6E7ED";
        save_btn.style.cursor = "auto";
        clear();
        navigator.mediaDevices.getUserMedia({
            video: true,
            audio: false
        })
            .then(function (stream) {
                video.srcObject = stream;
                video.play();
            })
            .catch(function (err) {
                console.log("An error occurred: " + err);
            });

        video.addEventListener('canplay', function () {
            if (!streaming) {
                height = video.videoHeight / (video.videoWidth / width);
                if (isNaN(height)) {
                    height = width / (4 / 3);
                }
                video.setAttribute('width', width);
                video.setAttribute('height', height);
                video.style.display = "block";
                canvas.setAttribute('width', width);
                canvas.setAttribute('height', height);
                preview.style.display = "none";
                streaming = true;
            }
        }, false);

        shoot.addEventListener('click', function (event) {
            takePicture();
            save_btn.disabled = false;
            save_btn.style.background = "#49D1CA";
            save_btn.style.borderColor = "#49D1CA";
            save_btn.style.cursor = "pointer";
            event.preventDefault();
        }, false);
        shoot.removeAttribute('disabled');
        clearphoto();
    }

    function clearphoto() {
        let context = canvas.getContext('2d');
        context.fillStyle = "#AAA";
        context.fillRect(0, 0, canvas.width, canvas.height);

        let data = canvas.toDataURL('image/png');
        photo.setAttribute('src', data);
    }

    function takePicture() {
        let context = canvas.getContext('2d');
        if (width && height) {
            canvas.width = width;
            canvas.height = height;
            context.drawImage(video, 0, 0, width, height);

            let data = canvas.toDataURL('image/png');
            video.style.display = "none";
            photo.setAttribute('src', data);
            preview.style.display = "block";
            preview.setAttribute('src', data);
            streaming = false;
            save_btn.removeAttribute("disabled");
            shoot.setAttribute('disabled', 'disabled');
            save.value = preview.src;
            if (!isInited)
                initFiltersAndStickers();
        } else {
            clearphoto();
        }
    }

    function vidOff() {
        if (streaming) {
            const stream = video.srcObject;
            const tracks = stream.getTracks();

            tracks.forEach(function (track) {
                track.stop();
            });
            video.style.display = "none";
            preview.style.display = "block";
            streaming = false;
            video.srcObject = null;
            streaming = false;
        }
    }

    let startBtn = document.getElementById('startbutton');
    let upBtn = document.getElementById('file-upload');
    startBtn.addEventListener('click', startup, false);
    upBtn.addEventListener('click', vidOff, false);

    let filters = ['blur(5px)',
        'grayscale(100%)',
        'sepia(60%)',
        'invert(100%)',
        'brightness(200%)'
    ];

    function addFilter() {
        snapchat['filter'] = filters[this.id];
        render();
    }

    function addSticker() {
        snapchat['stickers'].push({
            elem: document.getElementById('stick').getElementsByClassName('sticker')[this.id].getElementsByTagName('img')[0],
            x: start_pos_x,
            y: start_pos_y,
            isActive: false
        }
        );
        render();
    }

    function initFiltersAndStickers() {
        isInited = true;

        document.querySelectorAll('.filter').forEach(item => {
            item.addEventListener('click', addFilter, false);
        });

        document.querySelectorAll('.sticker').forEach(item => {
            item.addEventListener('click', addSticker, false);
        })
    }

    function destroyFiltersAndStickers() {
        isInited = false;

        document.querySelectorAll('.filter').forEach(item => {
            item.removeEventListener('click', addFilter, false);
        });

        document.querySelectorAll('.sticker').forEach(item => {
            item.removeEventListener('click', addSticker, false);
        })
    }

    function render() {
        let context = canvas.getContext('2d');
        context.canvas.width = photo.width;
        context.canvas.height = photo.height;

        context.filter = snapchat['filter'];
        context.drawImage(photo, 0, 0, photo.width, photo.height);
        context.filter = "none";
        if (snapchat['stickers']) {
            for (let el of snapchat['stickers']) {
                if (el.isActive) {
                    context.arc(el.x + sticker_width / 2, el.y + sticker_width / 2, sticker_width / 2, 0, 2 * Math.PI);
                    context.lineWidth = 10;
                    context.strokeStyle = "#49d1ca";
                    context.stroke();
                }
                context.drawImage(el['elem'], el.x, el.y, sticker_width, sticker_height);
            }
        }
        let data = canvas.toDataURL('image/png');
        preview.setAttribute('src', data);
        save.value = preview.src;
    }

    function clear() {
        snapchat['filter'] = "none";
        snapchat['stickers'] = [];
        preview.src = "img/preview.png";
    }

    discard.addEventListener('click', function () {
        clear();
        render();
    }, false);

    function inArrayStickers(newX, newY) {
        for (let i = snapchat['stickers'].length - 1; i > -1; i--) {
            let el = snapchat['stickers'][i];
            if (newX >= el.x && newX <= el.x + sticker_width && newY >= el.y && newY <= el.y + sticker_height) {
                el.isActive = true;
                return true;
            }
        }
        return false;
    }

    document.getElementById('preview').addEventListener('click', function (event) {
        if (!snapchat['isClicked'] && inArrayStickers(event.offsetX, event.offsetY)) {
            snapchat['isClicked'] = true;
        }
        else {
            for (let el of snapchat['stickers']) {
                if (el.isActive) {
                    el.x = event.offsetX - sticker_width / 2;
                    el.y = event.offsetY - sticker_height / 2;
                    el.isActive = false;
                }
            }
            snapchat['isClicked'] = false;
        }
        render();
    }, false);

    file_upload.addEventListener('click', function () {
        if (streaming)
            vidOff();
        if (!isInited)
            initFiltersAndStickers();
    }, false);

    file_upload.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            if (!this.files[0].type.match("image*")) {
                alert("Wrong type file");
                return;
            }
            clear();
            let reader = new FileReader();
            save_btn.removeAttribute("disabled");
            reader.onload = function (event) {
                clear();
                document.getElementById('preview')
                    .setAttribute('src', event.target.result);
                document.getElementById('origin')
                    .setAttribute('src', event.target.result);
                save.value = preview.src;
                save_btn.disabled = false;
                save_btn.style.background = "#49D1CA";
                save_btn.style.borderColor = "#49D1CA";
                save_btn.style.cursor = "pointer";
            };
            reader.readAsDataURL(this.files[0]);
            file_upload.value = "";
        }
    }, false);
})();