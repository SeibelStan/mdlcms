<?php include(view('includes/admin-header')) ?>
 
<main class="container">
    <h1 class="d-flex justify-content-between">
        <?= $model->getTitle() ?>
        <?php if($model->isAddable()) : ?>
            <p class="mt-3 mb-3"><a class="btn btn-success" href="<?= ROOT ?>admin/edit-model/<?= $model->getName() ?>">Создать</a>
        <?php endif; ?>
    </h1>

    <table class="table">
        <thead>
        <tr>
            <?php foreach($model->getFields() as $field) : ?>
            <th><?= $field->title ?>
            <?php endforeach; ?>
            <th>
        </thead>
        <?php foreach($units as $unit) : ?>
            <tr>
            <?php foreach($model->getFields($unit->id) as $field) : ?>
            <td><?= $field->value ?>
            <?php endforeach; ?>
            <td><a href="<?= ROOT ?>admin/edit-models/<?= $model->getName() ?>/<?= $unit->id ?>">Изм.</a>
            <?php if($model->isRemovable()) : ?>
            <td><a href="<?= ROOT ?>admin/delete-models/<?= $model->getName() ?>/<?= $unit->id ?>">&times;</a>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
</main>

<script src="<?= ROOT ?>vendor/ckeditor/adapters/jquery.js"></script>

<?php include(view('includes/admin-footer')) ?>