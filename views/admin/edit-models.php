<?php include(view('includes/admin/header')) ?>

<main class="container">

<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#forms" role="tab">Формы</a>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#table" role="tab">Таблицы</a>
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="forms" role="tabpanel">
        <ul>
            <?php foreach ($modelListExemps as $model) : ?>
            <li><a href="<?= ROOT ?>admin/edit-models/<?= $model::getName() ?>"><?= $model::getTitle() ?></a>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="tab-pane" id="table" role="tabpanel">
        <ul>
            <?php foreach ($modelListExemps as $model) : ?>
            <li><a href="<?= ROOT ?>admin/table/<?= $model::getName() ?>"><?= $model::getTitle() ?></a>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

</main>

<?php include(view('includes/admin/footer')) ?>