var lastFocused;

function imagesFieldFill() {
    $('.filemanager_links li').each(function () {
        lastFocused.val(
            lastFocused.val() + $(this).html() + '\n'
        );
    });
    filesGet();
    $('.filemanager_links').empty();
    $('#filesModal .close').click();
}

$(function () {

    $('input, textarea').focus(function () {
        lastFocused = $(this);
    });

});