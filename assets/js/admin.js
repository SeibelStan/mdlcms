var lastFocused;
let wysiwyg;

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
    FM.filesGet();
    $('.fm_links').empty();
    $('#filesModal .close').click();
}

$(function () {

    $('.select-links').change(function () {
        location.href = $(this).val();
    });

    var filterMode = 'client';
    if ($('.select-links').length) {
        if ($('.select-links option').length > 200) {
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
            });
        });
    }
    else {
        $('[data-filter]').change(function (e) {
            var collection = $(this).data('filter');
            var filterVal = $(this).val();

            var result = '';
            $.post(
                baseURL + 'admin/' + $('[name="model"]').val() + '/filter',
                {
                    query: filterVal
                },
                function (data) {
                    $(collection).html(data);
                }
            );
        });
    }

    $('.last-focused-top').click(function () {
        lastFocused = $(this).parent().prev().find('input, textarea');
    });
    
    var markdown = $('[name="markdown"]');
    var markForm = markdown.closest('form');
    var content = $('[name="content"]');
    if (markdown.length && content.length) {
        markdownLabel = markdown.prev();
        contentLabel = content.prev();
        var markdownName = markdown.attr('name');
        var contentName = content.attr('name');
        markdown.after('\
            <ul class="nav nav-tabs" id="content-tabs" role="tablist">\
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-' + markdownName + '">' + markdownLabel.html() + '</a>\
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-' + contentName + '">' + contentLabel.html() + '</a>\
            </ul>\
            <div class="tab-content">\
                <div class="tab-pane active" id="tab-' + markdownName + '"></div>\
                <div class="tab-pane" id="tab-' + contentName + '"></div>\
            </div>\
        ');

        $('#tab-' + markdownName).html(markdown);
        $('#tab-' + contentName).html(content);

        var tabs = $('#content-tabs');
        if (markdown.val()) {
            tabs.find('[name="' + markdownLabel + '"]').tab('show');
        }
        
        markdownLabel.remove();
        contentLabel.remove();

        markdown.keyup(function () {
            markForm.find('[type="submit"]').attr('disabled', 'true');
        });

        markdown.change(function () {
            $.post(
                ROOT + 'helpers/markdown-parse',
                {
                    data: markdown.val()
                },
                function (data) {
                    if (typeof wysiwyg != 'undefined') {
                        wysiwyg.setData(data); 
                        editor.value = wysiwyg.getData();
                        markForm.find('[type="submit"]')
                            .attr('disabled', false)
                            .click();
                    }
                    else {
                        content.val(data);
                    }
                }
            );
        });
    }

    if ($('[name="url"]').length) {
        $('[name="title"]').each(function () {
            $(this).generateUrl({
                urlField: '[name="url"]',
                bindType: 'keyup',
                emptyOnly: false,
            });
        });
    }
        
    if ($('#editor').length) {
        ClassicEditor
        .create(document.querySelector('#editor'))
        .then( editor => {
            wysiwyg = editor;
        })
        .catch(error => {
            console.error(error);
        });
    }

});