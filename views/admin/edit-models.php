<?php include(view('includes/admin-header')) ?>

<main class="container">
    <ul>
        <?php foreach($modelListExemps as $model) : ?>
        <li><a href="<?= ROOT ?>admin/edit-models/<?= $model->getName() ?>"><?= $model->getTitle() ?></a>
        <?php endforeach; ?>
    </ul>
</main>

<?php include(view('includes/admin-footer')) ?>