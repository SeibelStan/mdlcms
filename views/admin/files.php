<?php $filesInPage = isset($filesInPage) ? 1 : 0; ?>

<?php if($filesInPage) : ?>
    <?php include(view('includes/admin-header')) ?>
<?php endif; ?>

<div class="filemanager" data-inpage="<?= $filesInPage ?>">
    <div class="filemanager_tools nav bg-faded">
        <li class="nav-item"><a class="tool_refresh nav-link">Обновить</a>
        <li class="nav-item"><form action="<?= ROOT ?>files/upload" class="filemanager_uploadhere" method="post" enctype="multipart/form-data">
            <input type="file" name="files[]" class="tool_upload_inp" multiple style="display: none;">
            <input type="hidden" name="dir" class="tool_upload_dir" value="">
            <a class="tool_upload nav-link">Загрузить сюда</a>
        </form>
        <li class="nav-item"><a class="tool_createdir nav-link">Создать папку</a>
        <li class="nav-item"><a class="tool_remove nav-link">Удалить</a>
        <li class="nav-item">
            <input type="checkbox" id="outerlink" title="Внешние ссылки" style="display: none;">
            <a id="outerlink_toggle" class="nav-link">Ссылки: внут.</a>
    </div>
    <div class="filemanager_panes <?= $filesInPage ? 'container-fluid' : 'row' ?>">
        <div class="filemanager_left col-lg-8 col-sm-12">
            <div class="filemanager_crumbs nav nav-pills"></div>
            <div class="filemanager_files row"></div>
        </div>
        <div class="filemanager_right col-lg-4 col-sm-12">
            <ul class="filemanager_links"></ul>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= ROOT ?>assets/css/files.css"></script>
<script src="<?= ROOT ?>assets/js/files.js"></script>

<?php if($filesInPage) : ?>
    <?php include(view('includes/admin-footer')) ?>
<?php endif; ?>