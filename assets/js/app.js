function getCart() {
    $.get(
        ROOT + 'cart/get',
        function (data) {
            data = JSON.parse(data);
            $('#cart-widget li:not(.noremove)').remove();
            var sum = 0;
            for (var i in data) {
                var item = data[i];
                sum += item.count * item.price;
                $('#cart-widget').prepend('\
                    <li class="dropdown-item">\
                        <a href="' + ROOT + item.model + '/' + item.id + '" style="display: flex;">'
                            + item.title + ' (' + item.count + ') - '
                            + item.count * item.price + ' ' + $('#currency').val() + '\
                            <form class="form-ajax" action="' + ROOT + 'cart/remove" method="post">\
                                <button class="btn btn-link btn-text pull-right" type="submit">&times;</button>\
                                <input type="hidden" name="id" value="' + item.cart_id + '">\
                            </form>\
                        </a>\
                    </li>\
                ');
            }
            if (data.length) {
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

    getCart();

    $('.search-widget-trigger').keyup(function () {
        var query = $(this).val();
        $.post(
            ROOT + 'search-widget',
            {
                searchQuery: query
            },
            function (data) {
                data = JSON.parse(data);
                $('.search-widget').empty();
                for (var i in data) {
                    var item = data[i];
                    $('.search-widget')
                        .append('<li><a class="dropdown-item" href="' + item.link + '">' + item.title + '</a></li>');
                }
                if (!data.length) {
                    $('.search-widget').append('<li class="dropdown-item text-muted">Ничего не найдено</li>');
                }
            }
        );
    });

    $('#direct-unit-count').on('input', function () {
        $('#direct-unit-price').html(
            $(this).val() * $('#direct-unit-price').data('price')
        );
    });

});