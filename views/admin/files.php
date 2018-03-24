<?php $filesInModal = isset($filesInModal) ? $filesInModal : 1; ?>

<?php if(!$filesInModal) : ?>
    <?php include(view('includes/admin/header')) ?>
    <main class="container-fluid">
<?php endif; ?>

<div class="filemanager" data-inmodal="<?= $filesInModal ?>">
    <div class="fm_tools nav bg-faded">
        <li class="nav-item"><a class="tool_refresh nav-link">Обновить</a>
        <li class="nav-item"><form action="<?= ROOT ?>files/upload" class="fm_uploadhere" method="post" enctype="multipart/form-data">
            <input type="file" name="files[]" class="tool_upload_inp" multiple style="display: none;">
            <input type="hidden" name="dir" class="tool_upload_dir" value="">
            <a class="tool_upload nav-link">Загрузить сюда</a>
        </form>
        <li class="nav-item"><a class="tool_createdir nav-link">Создать папку</a>
        <li class="nav-item"><a class="tool_remove nav-link">Удалить</a>
        <li class="nav-item">
            <input type="checkbox" id="fm_outerlink" title="Внешние ссылки" style="display: none;">
            <a id="fm_outerlink_toggle" class="nav-link">Ссылки: внут.</a>
    </div>
    <div class="fm_panes row <?= $filesInModal ? '' : 'container-fluid' ?>">
        <div class="fm_left col-lg-8 col-sm-12">
            <div class="fm_crumbs nav nav-pills"></div>
            <div class="fm_files row"></div>
        </div>
        <div class="fm_right col-lg-4 col-sm-12">
            <ul class="fm_links"></ul>
        </div>
    </div>
</div>

<script defer src="<?= ROOT ?>assets/js/files.js"></script>

<?php if(!$filesInModal) : ?>
    </main>
    <?php include(view('includes/admin/footer')) ?>
<?php endif; ?>