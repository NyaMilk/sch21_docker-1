"use strict";

(function () {
    let page = new URL(window.location.href).searchParams.keys();
    let nav = document.querySelectorAll('.profile-nav a');
    let display = 0;
    
    for (let key of page)
        if (key == 'favorites')
            display = 1;

    nav[display].classList.add('profile-nav-active');
})();
