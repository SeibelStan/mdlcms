var changeSpy = false;

function showAlert(message, type = '', time = 3000) {
    if (!message) {
        return false;
    }

    var lastAlert = $('.alert-sticky').first();
    var alertTop = 10;
    if (lastAlert.length) {
        alertTop = parseInt(lastAlert.data('top')) + 10 + lastAlert.outerHeight();
    }

    $('main').prepend('<div class="alert alert-' + (type ? type : 'danger') + ' alert-sticky">' + message + '</div>');
    $('.alert-sticky').first()
        .animate({ top: alertTop }, 300)
        .animate({ clear: 'none' }, time)
        .data('top', alertTop)
        .fadeOut(500, function () {
            $(this).remove();
        });
}

function selectVal() {
    $('select').each(function () {
        var sel = $(this);
        sel.find('option').each(function () {
            if (sel.attr('value') == $(this).val()) {
                $(this).prop('selected', true);
            }
        });
    });
}

function attachForms() {
    $('.form-ajax').ajaxForm({
        success: function (data) {
            $('.form-resetable').resetForm();

            data = JSON.parse(data);
            if (data.message) {
                showAlert(data.message, data.type);
            }
            if (data.callback) {
                eval(data.callback);
            }
        }
    });

    $('.form-ajax button').attr('disabled', false);

    $('.form-instsub *').change(function () {
        $(this).closest('form').submit();
    });

    $('.form-changespy *').on('change keyup', function () {
        changeSpy = true;
    });
}

$(function () {

    attachForms();

    window.onbeforeunload = function () {
        if (changeSpy) {
            return 'Уйти не сохраняя?';
        }
    };

    $('body').on('click', '.alert-sticky', function () {
        $(this).remove();
    });

    if (alert) {
        alert = JSON.parse(alert);
        showAlert(alert.message, alert.type);
    }

    $('.autolabel label').each(function (i) {
        var label = 'autolabel' + Math.random();
        if (!$(this).attr('for')) {
            $(this).attr('for', label);
            $(this).siblings().each(function () {
                if (!$(this).attr('id') && $(this).attr('type') != 'hidden') {
                    $(this).attr('id', label);
                }
            });
        }
    });

    $('[type="checkbox"]').change(function () {
        var name = $(this).attr('name');
        setTimeout(function () {
            $('[type="hidden"][name="' + name + '"]').val(0);
        }, 100);
    });

    $('label + [type="checkbox"]').each(function () {
        $(this).parent().prepend($(this));
    });

    $('.pagination').each(function () {
        var pagination = $(this);
        var activePage = pagination.find('li.active a').attr('href').match(/(\d+)$/).pop();
        var pagesCount = pagination.find('li:not([data-helper])').length;
        var steps = pagination.data('steps') || 5;

        var showPages = [1];
        for (var i = 0; i <= steps; i++) {
            showPages.push(activePage - i);
        }
        for (var i = 0; i <= steps; i++) {
            showPages.push(parseInt(activePage) + i);
        }
        showPages.push(pagesCount);

        $('.pagination').find('li:not([data-helper])').each(function () {
            var page = parseInt($(this).find('a').attr('href').match(/(\d+)$/).pop());
            if (showPages.indexOf(page) == -1) {
                $(this).after('<li class="page-item dots"><a class="page-link">&bull;</a></li>');
                $(this).remove();
            }
            $('.dots + .dots').remove();
        });

        pagination.fadeTo('fast', 1);
    });

    $('[name="password"]').on('dblclick', function () {
        $(this).attr('type', $(this).attr('type') == 'text' ? 'password' : 'text');
    });

    selectVal();

});