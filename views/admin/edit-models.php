<?php include(view('includes/admin-header')) ?>

<main class="container">
    <h2>Изменение</h2>
    <ul>
        <?php foreach($modelListExemps as $model) : ?>
        <li><a href="<?= ROOT ?>admin/edit-models/<?= $model->getName() ?>"><?= $model->getTitle() ?></a>
        <?php endforeach; ?>
    </ul>

    <h2>Обзор</h2>
    <ul>
        <?php foreach($modelListExemps as $model) : ?>
        <li><a href="<?= ROOT ?>admin/table/<?= $model->getName() ?>"><?= $model->getTitle() ?></a>
        <?php endforeach; ?>
    </ul>
</main>

<?php include(view('includes/admin-footer')) ?>