<?php include(view('includes/admin-header')) ?>

<div class="container">
    <div class="form-group">
        <select class="custom-select select-links" id="model-units">
            <option value="<?= ROOT ?>admin/edit-models/<?= $modelName ?>">Новый</option>
            <?php foreach($units as $unit) : ?>
                <?php $thisLink = ROOT . 'admin/edit-models/' . $modelName . '/' . $unit->id; ?>
                <option value="<?= $thisLink ?>" <?= $_SERVER['REQUEST_URI'] == $thisLink ? 'selected' : '' ?>>
                    <?= $unit->display_name ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="text" class="form-control col-sm-4" placeholder="Фильтр" data-filter="#model-units">
    
        <?php if($id) : ?>
            <a class="btn btn-success" href="<?= ROOT ?>admin/edit-models/<?= $modelName ?>">Новый</a>
        <?php endif; ?>
    </div>

    <form class="form-ajax" action="<?= ROOT ?>admin/save-models/<?= $modelName ?><?= $id ? '/' . $id : '' ?>" method="post">
        <?php include(view('includes/fields')) ?>
        <div class="form-group">
            <?php if($id || $model->isAddable()) : ?>
                <button class="btn btn-primary" type="submit">Сохранить</button>
            <?php endif; ?>
            <?php if($id && $model->isRemovable()) : ?>
                <a class="btn btn-danger" onclick="if(!confirm('Точно?')) return false;" href="<?= ROOT ?>admin/delete-models/<?= $modelName ?>/<?= $id ?>">Удалить</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<script src="<?= ROOT ?>vendor/ckeditor/ckeditor.js?v=<?= time() ?>"></script>
<script src="<?= ROOT ?>vendor/ckeditor/adapters/jquery.js"></script>

<div class="modal fade" id="filesModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Файлы</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php include(view('admin/files')) ?>
            </div>
            <div class="modal-footer" style="justify-content: flex-start;">
                <button type="button" class="btn btn-primary" onclick="imagesFieldFill()">Выбрать</button>
            </div>
        </div>
    </div>
</div>

<?php include(view('includes/admin-footer')) ?>