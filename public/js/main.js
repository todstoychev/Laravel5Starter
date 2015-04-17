$(document).ready(function () {
    var height = $(window).height();
    var footer = $('footer').height();
    var header = $('header').height();
    height = height - footer - header - 60;
    console.log(height);
    $('div.body').css({"min-height": height + "px"});
});