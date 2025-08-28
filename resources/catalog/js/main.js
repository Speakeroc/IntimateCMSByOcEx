$(document).ready(function () {
    $('.ex_sidebar__bg').on('click', function () {
        $('.ex_sidebar').removeClass('ex_sidebar_is_anim');
        setTimeout(function () {
            $('.ex_sidebar').removeClass('active');
        }, 500);
    });

    $('.ex_sidebar__close').on('click', function () {
        $('.ex_sidebar').removeClass('ex_sidebar_is_anim');
        setTimeout(function () {
            $('.ex_sidebar').removeClass('active');
        }, 500);
    });

    $('.ex_open_sb').on('click', function () {
        $('.ex_sidebar').addClass('active');
        setTimeout(function () {
            $('.ex_sidebar').addClass('ex_sidebar_is_anim');
        }, 100);
    });
});
