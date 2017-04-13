var lastFocused;

function imagesFieldFill() {
    $('.fm_links li').each(function () {
        if(lastFocused.prop('tagName') == 'TEXTAREA') {
            lastFocused.val(
                lastFocused.val() + $(this).html() + '\n'
            );
        }
        else {
            lastFocused.val($(this).html());
        }
    });
    FM.filesGet();
    $('.fm_links').empty();
    $('#filesModal .close').click();
}

$(function () {

    $('.last-focused-top').click(function () {
        lastFocused = $(this).parent().prev().find('input, textarea');
    });

    $('.friendly-url-fill').click(function() {
        $.post(
            baseURL + 'helpers/friendly-url',
            {
                url: $('[name="title"]').val()
            },
            function (data) {
                $('[name="url"]').val(data);
            }
        );
    });

});