<?php include(view('includes/admin-header')) ?>

<div class="container">
    <ul class="nav flex-column">
        <?php foreach($modelListExemps as $model) : ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= ROOT ?>admin/edit-models/<?= $model->getName() ?>"><?= $model->getTitle() ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php include(view('includes/admin-footer')) ?>