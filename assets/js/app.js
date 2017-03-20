function showAlert(message, type, time = 3000) {
    type = type ? type : 'danger';
    $('body > .container').prepend('<div class="alert alert-' + type + ' alert-sticky">' + message + '</div>');
    $('body .alert:first-child')
        .animate({top: '10px'}, 500)
        .animate({clear: 'none'}, time)
        .fadeOut(500, function () {
            $(this).remove();
        })
}

$(function () {

    if($('#smes-val').val()) {
        showAlert($('#smes-val').val(), $('#smes-type').val());
    }

    $('.form-resetable').on('submit', function () {
        $(this).find('*').each(function () {
            if($(this).attr('type') != 'hidden') {
                $(this).val('');
            }
        });
    });

    $('.form-ajax').on('submit', function () {
        if(
            typeof CKEDITOR != 'undefined' &&
            typeof CKEDITOR.instances.content != 'undefined'
        ) {
            CKEDITOR.instances.content.updateElement();
        }
    });

    $('.form-ajax').ajaxForm(function (data) {
        data = JSON.parse(data);
        if(data.message) {
            showAlert(data.message, data.messageType);
        }
        if(data.callback) {
            eval(data.callback);
        }
    });

    $('.autolabel label').each(function (i) {
        var label = 'autolabel-' + Math.random();
        if(!$(this).attr('for')) {
            $(this).attr('for', label);
            $(this).next().attr('id', label);
        }
    });

    $('.select-links').change(function () {
        location.href = $(this).val();
    });

    $('[data-filter').keyup(function () {
        var collection = $(this).data('filter');
        var filterVal = $(this).val();
        var regStr = '';
        for(i in filterVal) {
            regStr += filterVal[i] + '.*';
        }
        var reg = new RegExp(regStr, 'i');
        $(collection + ' > *').show();
        $(collection + ' > *').each(function () {
            if(!$(this).html().match(reg)) {
                $(this).hide();
            }
        });
    });

    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });

});