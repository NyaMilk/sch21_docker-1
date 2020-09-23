"use strict";

(function () {
    let typeSrt = document.querySelectorAll('.gallery__form label');
    let sortOptions = document.querySelectorAll('input[name=sort]');
    let sortSelected = new URL(window.location.href).searchParams.get('sort');

    sortOptions.forEach(item => {
        item.addEventListener('click', function () {
            window.location.href = "gallery.php?sort=" + this.value + "&page=1";
        })
    })

    if (sortSelected == 'popular')
        typeSrt[1].classList.add('gallery__form-active');
    else if (sortSelected == 'new')
        typeSrt[2].classList.add('gallery__form-active');
    else
        typeSrt[0].classList.add('gallery__form-active');
})();