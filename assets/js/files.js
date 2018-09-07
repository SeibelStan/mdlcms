var FM = {
    dir: '',
    segments: [],
    checked: [],
    defaultPath: 'data/files/',
    inModal: false,
    outerLink: false,

    dirChange: function (path) {
        if (!path) {
            if (FM.inModal) {
                path = localStorage.getItem('path') ? localStorage.getItem('path') : FM.defaultPath;
            }
            else {
                path = location.hash ? location.hash.replace(/#/, '') : FM.defaultPath;
            }
        }

        FM.dir = path;
        FM.segments = FM.dir.replace(/\/$/, '').split('/');
        if (FM.inModal) {
            localStorage.setItem('path', FM.dir);
        }
        else {
            location.hash = FM.dir;
        }
        $('.tool_upload_dir').val(FM.dir);
        FM.filesGet();
    },

    filesGet: function () {
        $('.fm_crumbs').empty();
        for (var i = 0; i < FM.segments.length; i++) {
            var tmp = '';
            for (var j = 0; j <= i; j++) {
                tmp += FM.segments[j] + '/';
            }
            $('.fm_crumbs').append('<li class="nav-item"><a class="nav-link" href="#" data-path="' + tmp + '">' + FM.segments[i] + '</a>');
        }
        $('.fm_crumbs .nav-item:last-child a').removeAttr('href');

        $.post(
            ROOT + 'files/get',
            {
                dir: FM.dir
            },
            function (data) {
                data = JSON.parse(data);

                var res = '';
                $.each(data, function (i, el) {
                    res += '\
                        <div class="col-lg-2 col-md-4 col-sm-6 fm_item mb-3"\
                            data-type="' + el.type + '"\
                            data-fullname="' + el.fullname + '">\
                            <div class="fm_item_inner card">\
                                <img class="fm_item_icon card-img-top" src="' + el.icon + '">\
                                <input type="text" class="fm_item_title" value="' + el.name + '">\
                            </div>\
                        </div>\
                    ';
                });
                $('.fm_files').html(res);
            }
        );
    },

    filesDelete: function () {
        if (!confirm('Точно?')) {
            return false;
        }

        $('.fm_links').empty();

        var files = [];
        $('.fm_item.checked').each(function () {
            files.push($(this).data('fullname'));
        });

        $.post(
            ROOT + 'files/delete',
            {
                files: files,
                inUploadPath: true
            },
            function () {
                FM.filesGet();
            }
        );
    },

    fileRename: function (oldName, newName) {
        $.post(
            ROOT + 'files/rename',
            {
                oldName: oldName,
                newName: newName
            },
            function () {
                FM.filesGet();
            }
        );
    },

    fileCheck: function () {
        FM.outerLink = $('#fm_outerlink').prop('checked');
        $('.fm_links').empty();
        $('.fm_item.checked').each(function () {
            $('.fm_links').append('<li>' +
                (FM.outerLink ? FULLHOST : '') +
                ROOT + $(this).data('fullname') +
            '</li>');
        });
    },

    dirCreate: function () {
        $.post(
            ROOT + 'files/create-dir',
            {
                dir: FM.dir
            },
            function (data) {
                FM.filesGet();
            }
        );
    }
};

$(function () {

    FM.inModal = $('.filemanager').data('inmodal');

    FM.dirChange(FM.dir);

    $(window).on('hashchange', function () {
        FM.dirChange('');
    });

    $('.filemanager').on('change', '.fm_item_title', function () {
        FM.fileRename(
            $(this).closest('.fm_item').data('fullname'),
            FM.dir + $(this).val()
        );
    });
    

    $('.filemanager').on('click', '.fm_crumbs a', function () {
        FM.dirChange($(this).data('path'));
        return false;
    });

    $('.filemanager').on('click', '.fm_item', function (e) {
        if (!$(e.target).hasClass('fm_item_title')) {
            $(this).toggleClass('checked');
            FM.fileCheck();
        }
    });

    $('.filemanager').on('dblclick', '.fm_item[data-type="dir"]', function () {
        FM.dirChange($(this).data('fullname'));
    });

    $('.tool_upload').click(function () {
        $('.tool_upload_inp').click();
    });

    $('.tool_upload_inp').change(function () {
        $(this).closest('form').submit();
    });

    $('.tool_createdir').click(function () {
        FM.dirCreate();
    });

    $('.fm_uploadhere').ajaxForm(function () {
        FM.filesGet();
    });

    $('.tool_remove').click(function () {
        FM.filesDelete();
    });

    $('.tool_refresh').click(function () {
        FM.filesGet();
    });

    $('#fm_outerlink_toggle').click(function () {
        $('#fm_outerlink').click();
        var outerLinkVal = $('#fm_outerlink').prop('checked');
        $(this).html('Ссылки: ' + (outerLinkVal ? 'внеш.' : 'внутр.'));
    });

});
