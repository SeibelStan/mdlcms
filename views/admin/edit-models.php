<?php include(view('includes/admin-header')) ?>

<main class="container">
    <ul class="nav flex-column">
        <?php foreach($modelListExemps as $model) : ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= ROOT ?>admin/edit-models/<?= $model->getName() ?>"><?= $model->getTitle() ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</main>

<?php include(view('includes/admin-footer')) ?>