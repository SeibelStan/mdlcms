function showAlert(message, type = 'danger', time = 3000) {
    $('main').prepend('<div class="alert alert-' + type + ' alert-sticky">' + message + '</div>');
    $('body .alert:first-child')
        .animate({top: '10px'}, 300)
        .animate({clear: 'none'}, time)
        .fadeOut(500, function () {
            $(this).remove();
        })
}

function attachForms() {
    $('.form-ajax').on('submit', function () {
        if(typeof ClassicEditor != 'undefined' && typeof ClassicEditor.instances.content != 'undefined') {
            ClassicEditor.instances.content.updateElement();
        }
    });

    $('.form-ajax').ajaxForm({
        success: function (data) {
            $('.form-resetable').resetForm();

            data = JSON.parse(data);
            if(data.message) {
                showAlert(data.message, data.type);
            }
            if(data.callback) {
                eval(data.callback);
            }
        }
    });
}

function getCart() {
    $.get(
        baseURL + 'cart/get',
        function (data) {
            data = JSON.parse(data);
            $('#cart-widget li:not(.noremove)').remove();
            var sum = 0;
            for(var i in data) {
                var item = data[i];
                sum += item.count * item.price;
                $('#cart-widget').prepend('\
                    <li class="dropdown-item">\
                        <a href="' + baseURL + item.model + '/' + item.id + '" style="display: flex;">'
                            + item.title + ' (' + item.count + ') - '
                            + item.count * item.price + ' ' + $('#currency').val() + '\
                            <form class="form-ajax" action="' + baseURL + 'cart/remove" method="post">\
                                <button class="btn btn-link btn-text pull-right" type="submit">&times;</button>\
                                <input type="hidden" name="id" value="' + item.cart_id + '">\
                            </form>\
                        </a>\
                    </li>\
                ');
            }
            if(data.length) {
                $('.cart-sum').html(sum);
                $('#cart-widget-actions').show();
                $('#cart-widget-noitems').hide();
                attachForms();
            }
            else {
                $('#cart-widget-actions').hide();
                $('#cart-widget-noitems').show();
            }
        }
    );
}

$(function () {

    attachForms();
    getCart();

    $('.search-widget-trigger').keyup(function () {
        var query = $(this).val();
        $.post(
            baseURL + 'search-widget',
            {
                searchQuery: query
            },
            function (data) {
                data = JSON.parse(data);
                $('.search-widget').empty();
                for(var i in data) {
                    var item = data[i];
                    $('.search-widget')
                        .append('<li><a class="dropdown-item" href="' + item.link + '">' + item.title + '</a></li>');
                }
                if(!data.length) {
                    $('.search-widget').append('<li class="dropdown-item text-muted">Ничего не найдено</li>');
                }
            }
        );
    });

    $('body').on('click', '.alert-sticky', function () {
        $(this).remove();
    });

    if($('#smes-val').val()) {
        showAlert($('#smes-val').val(), $('#smes-type').val());
    }

    $('.autolabel label').each(function (i) {
        var label = 'autolabel-' + Math.random();
        if(!$(this).attr('for')) {
            $(this).attr('for', label);
            var input = $(this).next();
            if(!input.attr('id')) {
                input.attr('id', label);
            }
        }
    });

    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });

    $('#direct-unit-count').on('input', function () {
        $('#direct-unit-price').html(
            $(this).val() * $('#direct-unit-price').data('price')
        );
    });

    $('.pagination').each(function () {
        var pagination = $(this);
        var activePage = pagination.find('li.active a').attr('href').match(/(\d+)$/).pop();
        var pagesCount = pagination.find('li:not([data-helper])').length;
        var steps = pagination.data('steps') || 5;

        var showPages = [1];
        for(var i = 0; i <= steps; i++) {
            showPages.push(activePage - i);
        }
        for(var i = 0; i <= steps; i++) {
            showPages.push(parseInt(activePage) + i);
        }
        showPages.push(pagesCount);

        $('.pagination').find('li:not([data-helper])').each(function () {
            var page = parseInt($(this).find('a').attr('href').match(/(\d+)$/).pop());
            if(showPages.indexOf(page) == -1) {
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

    $('.form-login [name="login"').on('keyup, change', function () {
        $('.form-remind [name="login"]').val($(this).val());
    });

});