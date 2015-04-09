$(document).ready(function () {
    $('h4.panel-title a').on('click', function () {
        if ($(this).hasClass('collapsed')) {
            $('h4.panel-title a').children('i').removeClass('glyphicon-chevron-down');
            $('h4.panel-title a').children('i').addClass('glyphicon-chevron-right');
            $(this).children('i').addClass('glyphicon-chevron-down');
        } else {
            $('h4.panel-title a').children('i').removeClass('glyphicon-chevron-down');
            $('h4.panel-title a').children('i').addClass('glyphicon-chevron-right');
            $(this).children('i').addClass('glyphicon-chevron-right');
        }
    });
});