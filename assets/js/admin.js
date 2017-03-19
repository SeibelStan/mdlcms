var lastFocused;

function imagesFieldFill() {
    $('.filemanager_links li').each(function () {
        if(lastFocused.prop('tagName') == 'textarea') {
            lastFocused.val(
                lastFocused.val() + $(this).html() + '\n'
            );
        }
        else {
            lastFocused.val($(this).html());
        }
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