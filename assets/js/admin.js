var lastFocused;
let wysiwyg;

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

    $('.last-focused-top').click(function () {
        lastFocused = $(this).parent().prev().find('input, textarea');
    });
    
    var markdown = $('[name="markdown"]');
    var content = $('[name="content"]');
    if(markdown.length && content.length) {
        markdownLabel = markdown.prev();
        contentLabel = content.prev();
        var markdownName = markdown.attr('name');
        var contentName = content.attr('name');
        markdown.after('\
            <ul class="nav nav-tabs" id="content-tabs" role="tablist">\
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-' + contentName + '">' + contentLabel.html() + '</a>\
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-' + markdownName + '">' + markdownLabel.html() + '</a>\
            </ul>\
            <div class="tab-content">\
                <div class="tab-pane active" id="tab-' + contentName + '"></div>\
                <div class="tab-pane" id="tab-' + markdownName + '"></div>\
            </div>\
        ');

        $('#tab-' + markdownName).html(markdown);
        $('#tab-' + contentName).html(content);

        var tabs = $('#content-tabs');
        if(markdown.val()) {
            tabs.find('[name="' + markdownLabel + '"]').tab('show');
        }
        
        markdownLabel.remove();
        contentLabel.remove();

        markdown.change(function () {
            $.post(
                baseURL + 'helpers/markdown-parse',
                {
                    data: markdown.val()
                },
                function (data) {
                    if(typeof wysiwyg != 'undefined') {
                        wysiwyg.setData(data); 
                        wysiwyg.content.updateElement();
                    }
                    else {
                        content.val(data);
                    }
                }
            );
        });
    }

    $('[name="title"]')
        .generateUrl({
            urlField: '[name="url"]',
            bindType: 'keyup',
            emptyOnly: false,
        })

    ClassicEditor
        .create(document.querySelector('#editor'))
        .then( editor => {
            wysiwyg = editor;
        })
        .catch(error => {
            console.error(error);
        });

});