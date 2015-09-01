$(document).ready(function () {
    $('i.glyphicon-search').parent().on('click', function () {
        $('form[role="search"]').submit();
    });
});