<?php
    global $model;
    global $fields;
    global $units;
    $id = $fields->id->value;
?>

<main class="container">
    <h1><?= $model::getTitle() ?></h1>
    <div class="form-group">
        <select class="custom-select select-links" id="model-units">
            <option data-id="0" value="<?= ROOT ?>admin/edit-models/<?= $model::getName() ?>">Новый</option>
            <?php foreach ($units as $unit) : ?>
                <?php
                    $thisLink = ROOT . 'admin/edit-models/' . $modelName . '/' . $unit->id;
                    $selected = $_SERVER['REQUEST_URI'] == $thisLink ? 'selected' : '';
                ?>
                <option data-id="<?= $unit->id ?>" value="<?= $thisLink ?>" <?= $selected ?>>
                    <?= $unit->display_name ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="text" class="form-control col-sm-4" placeholder="Фильтр" data-filter="#model-units">
    </div>

    <form class="main-form form-ajax" action="<?= ROOT ?>admin/save-models/<?= $model::getName() ?><?= $id ? '/' . $id : '' ?>" method="post">
        <?php include 'views/includes/fields.php' ?>
        <div class="form-group flow-btns">
            <?php if ($id || $model::isAddable()) : ?>
                <button class="btn btn-primary" type="submit">Сохранить</button>
            <?php endif; ?>
            <?php if ($id && $model::isRemovable()) : ?>
                <a class="btn btn-danger" onclick="if (!confirm('Точно?')) return false;" href="<?= ROOT ?>admin/delete-models/<?= $model::getName() ?>/<?= $id ?>">&times;</a>
            <?php endif; ?>
        </div>
    </form>
</main>

<div class="modal fade" id="filesModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Файлы</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php include 'admin/files.php' ?>
            </div>
            <div class="modal-footer" style="justify-content: flex-start;">
                <button type="button" class="btn btn-primary" onclick="imagesFieldFill()">Выбрать</button>
            </div>
        </div>
    </div>
</div>