var lastFocused;
var inscrybmde;

function imagesFieldFill() {
    $('.fm_links li').each(function () {
        if (lastFocused.prop('tagName') == 'TEXTAREA') {
            var fieldVal = lastFocused.val() + '\n' + $(this).html()
            lastFocused.val(fieldVal.trim());
        }
        else {
            lastFocused.val($(this).html());
        }
    });
    FM.get();
    $('.fm_links').empty();
    $('#filesModal .close').click();
}

$(function () {

    $('.select-links').change(function () {
        if ($(this).val()) {
            location.href = $(this).val();
        }
    });

    var filterMode = 'client';
    if ($('.select-links').length) {
        if ($('.select-links option').length > 1000) {
            filterMode = 'server';
        }
    }

    if (filterMode == 'client') {
        $('[data-filter]').keyup(function () {
            var collection = $(this).data('filter');
            var filterVal = $(this).val();
            var regStr = '';
            for (i in filterVal) {
                regStr += filterVal[i] + '.*';
            }
            var reg = new RegExp(regStr, 'i');
            $(collection + ' > *').hide();
            $(collection + ' > *').each(function () {
                var dataId = [] + $(this).data('id');
                if ($(this).html().match(reg) || dataId.match(reg)) {
                    $(this).show();
                }
                $('.select-links option').first().prop('selected', true);
                $('.select-links').change();
            });
        });
    }
    else {
        $('[data-filter]').change(function (e) {
            var collection = $(this).data('filter');
            var filterVal = $(this).val();

            $.post(
                baseURL + 'admin/' + $('[name="model"]').val() + '/filter',
                {
                    query: filterVal
                },
                function (data) {
                    $(collection).html(data);
                    $('.select-links option').first().prop('selected', true);
                    $('.select-links').change();
                }
            );
        });
    }

    $('.last-focused-set').click(function () {
        lastFocused = $(this).parent().find('input, textarea');
    });

    if ($('[name="url"]').length) {
        $('[name="title"]').each(function () {
            $(this).generateUrl({
                urlField: '[name="url"]',
                bindType: 'keyup',
                emptyOnly: false,
            });
        });
    }
        
    inscrybmde = new InscrybMDE({
        autosave: {
            //enabled: true,
            uniqueId: "inscrybmde",
            delay: 1000
        },
        spellChecker: false,
        forceSync: true,
        tabSize: 4,
        previewRender: function (x) {
            console.log(x);
        }
    });
    inscrybmde.codemirror.on('change', function(){
        $('[name="content"]').val(inscrybmde.markdown(inscrybmde.value()));
    });

});