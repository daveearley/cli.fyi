document.addEventListener('DOMContentLoaded', function () {

    var $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
    if ($navbarBurgers.length > 0) {
        $navbarBurgers.forEach(function ($el) {
            $el.addEventListener('click', function () {
                var target = $el.dataset.target;
                var $target = document.getElementById(target);
                $el.classList.toggle('is-active');
                $target.classList.toggle('is-active');
            });
        });
    }
});


function smoothScroll(el, to, duration) {
    if (duration < 0) {
        return;
    }
    var difference = to - $(window).scrollTop();
    var perTick = difference / duration * 10;
    this.scrollToTimerCache = setTimeout(function () {
        if (!isNaN(parseInt(perTick, 10))) {
            window.scrollTo(0, $(window).scrollTop() + perTick);
            smoothScroll(el, to, duration - 10);
        }
    }.bind(this), 10);
}

$('a').on('click', function (e) {
    var location = $(e.currentTarget).attr('href');
    if (location.charAt(0) === '#') {
        e.preventDefault();
        smoothScroll($(window), $($(e.currentTarget).attr('href')).offset().top, 200);
    }
});

$(function () {
    if ($(document).width() >= 960) {
        $('aside').stickySidebar({
            topSpacing: 60,
            bottomSpacing: 60
        });
    }

    $('.toggle-sidebar').on('click', function (e) {
        e.preventDefault();
        $('aside.sidebar').toggle();
    })
});