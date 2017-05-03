function showAlert(message, type, time = 3000) {
    type = type ? type : 'danger';
    $('main').prepend('<div class="alert alert-' + type + ' alert-sticky">' + message + '</div>');
    $('body .alert:first-child')
        .animate({top: '10px'}, 500)
        .animate({clear: 'none'}, time)
        .fadeOut(500, function () {
            $(this).remove();
        })
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

function attachForms() {
    $('.form-ajax').on('submit', function () {
        if(typeof CKEDITOR != 'undefined' && typeof CKEDITOR.instances.content != 'undefined') {
            CKEDITOR.instances.content.updateElement();
        }
    });

    $('.form-ajax').ajaxForm({
        success: function (data) {
            $('.form-resetable *').each(function () {
                if($(this).attr('type') != 'hidden') {
                    $(this).val('');
                }
            });

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

function timeAgo() {
    $('[data-timeago]:not(.parsed)').each(function () {
        var time = $(this).data('timeago') * 1000;
        $(this).html(new Date(time).toTwitterRelativeTime('ru'));
        $(this).addClass('parsed');
    });
}

$(function () {

    attachForms();
    getCart();
    timeAgo();

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
        $(collection + ' > *').hide();
        $(collection + ' > *').each(function () {
            var dataId = [] + $(this).data('id');
            if($(this).html().match(reg) || dataId.match(reg)) {
                $(this).show();
            }
        });
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

});