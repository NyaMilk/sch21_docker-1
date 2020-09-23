"use strict";

(function () {
    let new_avatar = document.getElementById("new-avatar");
    let save_btn = document.querySelector(".btn-save");

    function changeSave() {
        save_btn.disabled = false;
        save_btn.style.background = "#49D1CA";
        save_btn.style.borderColor = "#49D1CA";
        save_btn.style.cursor = "pointer";
    }

    document.querySelectorAll(".input-gray").forEach(item => {
        item.addEventListener("keyup", changeSave, false)
    });

    document.querySelector("input[name='notific']").addEventListener("click", changeSave, false);

    new_avatar.addEventListener("change", function () {
        if (this.files && this.files[0]) {
            if (!this.files[0].type.match("image*")) {
                alert("Wrong type file");
                return;
            }
            let reader = new FileReader();

            reader.onload = function (e) {
                document.getElementById('current-avatar').setAttribute('src', e.target.result);
                changeSave();
            };
            reader.readAsDataURL(this.files[0]);
        }
    }, false);
})();