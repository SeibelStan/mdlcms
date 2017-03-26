var filemanagerDir = '';
var filemanagerSegments = [];
var filemanagerChecked = [];
var defaultPath = 'data/files/';

function dirChange(path) {
    if(!path) {
        path = location.hash ? location.hash.replace(/#/, '') : defaultPath;
    }

    filemanagerDir = path;
    filemanagerSegments = filemanagerDir.replace(/\/$/, '').split('/');
    location.hash = filemanagerDir;
    $('.tool_upload_dir').val(filemanagerDir);
    filesGet();
}

function filesGet() {
    $('.filemanager_crumbs').html('');
    for(var i = 0; i < filemanagerSegments.length; i++) {
        var tmp = '';
        for(var j = 0; j <= i; j++) {
            tmp += filemanagerSegments[j] + '/';
        }
        $('.filemanager_crumbs').append('<li class="nav-item"><a class="nav-link" data-path="' + tmp + '">' + filemanagerSegments[i] + '</a>');
    }
    $('.filemanager_crumbs .nav-item:last-child a').addClass('active');

    $('.filemanager_files').html('');
    $.post(
        baseURL + 'files/get',
        {
            dir: filemanagerDir
        },
        function (data) {
            data = JSON.parse(data);
            $('.filemanager_files').html('');

            $.each(data, function (i, el) {
                $('.filemanager_files').append('\
                    <div class="col-lg-2 col-md-4 col-sm-6 filemanager_item mb-3"\
                        data-type="' + el.type + '"\
                        data-fullname="' + el.fullname + '">\
                        <div class="filemanager_item_inner card">\
                            <div class="filemanager_item_icon card-img-top" style="background-image: url(\'' + el.icon + '\')"></div>\
                            <input type="text" class="filemanager_item_title" value="' + el.name + '">\
                        </div>\
                    </div>\
                ');
            });
        }
    );
}

function filesRemove() {
    if(!confirm('Точно?')) {
        return false;
    }

    var files = [];
    $('.filemanager_item.checked').each(function () {
        files.push($(this).data('fullname'));
    });

    $.post(
        baseURL + 'files/remove',
        {
            files: files
        },
        function () {
            filesGet();
        }
    );
}

function fileRename(oldName, newName) {
    $.post(
        baseURL + 'files/rename',
        {
            oldName: oldName,
            newName: newName
        },
        function () {
            filesGet();
        }
    );
}

function fileCheck() {
    var outerLink = $('#outer_link').prop('checked');
    $('.filemanager_links').html('');
    $('.filemanager_item.checked').each(function () {
        $('.filemanager_links').append('<li>' +
            (outerLink ? domainURL : '') +
            baseURL + $(this).data('fullname') +
        '</li>');
    });
}

function dirCreate() {
    $.post(
        baseURL + 'files/create-dir',
        {
            dir: filemanagerDir
        },
        function (data) {
            filesGet();
        }
    );
}

$(function () {

    dirChange(filemanagerDir);

    $(window).on('hashchange', function() {
        dirChange('');
    });

    $('.filemanager').on('change', '.filemanager_item_title', function () {
        fileRename(
            $(this).closest('.filemanager_item').data('fullname'),
            filemanagerDir + $(this).val()
        );
    });
    

    $('.filemanager').on('click', '.filemanager_crumbs a', function () {
        dirChange($(this).data('path'));
    });

    $('.filemanager').on('click', '.filemanager_item', function (e) {
        if(!$(e.target).hasClass('filemanager_item_title')) {
            $(this).toggleClass('checked');
            fileCheck();
        }
    });

    $('.filemanager').on('dblclick', '.filemanager_item[data-type="dir"]', function () {
        dirChange($(this).data('fullname'));
    });

    $('.tool_upload').click(function () {
        $('.tool_upload_inp').click();
    });

    $('.tool_upload_inp').change(function () {
        $(this).closest('form').submit();
    });

    $('.tool_createdir').click(function () {
        dirCreate();
    });

    $('.filemanager_uploadhere').ajaxForm(function () {
        filesGet();
    });

    $('.tool_remove').click(function () {
        filesRemove();
    });

    $('.tool_refresh').click(function () {
        filesGet();
    });

    $('#outerlink_toggle').click(function () {
        console.log('1');
        $('#outerlink').click();
        var outerLinkVal = $('#outerlink').prop('checked');
        $(this).html('Ссылки: ' + (outerLinkVal ? 'внеш.' : 'внутр.'));
    });

});
