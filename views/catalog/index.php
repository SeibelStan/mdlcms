<?php include(view('includes/header')) ?>

<div class="nav">
    <li class="nav-item"><a href="<?= ROOT ?><?= $model->getTable() ?>" class="nav-link"><?= $model->getTitle() ?></a>
    <?php if($parentUnit) : ?>
        <li class="nav-item"><a class="nav-link" href="<?= ROOT ?>catalog/<?= $parentUnit->url ?>"><?= $parentUnit->title ?></a>
    <?php endif; ?>
    <?php if($directUnit) : ?>
        <li class="nav-item"><span class="nav-link"><?= $directUnit->title ?></span>
    <?php endif; ?>
</div>

<div class="container">
    <?php if($directUnit && !$directUnit->iscatalog) : ?>
        <?php include(view($model->getTable() . '/direct-unit')) ?>
    <?php endif; ?>

    <?php if($units) : ?>
    <div class="row">
        <?php foreach($units as $unit) : ?>
            <div class="col-sm-3">
                <div class="card mb-4">
                    <a href="<?= ROOT ?><?= $model->getTable() ?>/<?= $unit->url ?>"
                        class="card-img-top-cover height-card-image"
                        style="background-image: url('<?= $unit->image ?>')"
                    ></a>
                    <div class="card-header"><a href="<?= ROOT ?><?= $model->getTable() ?>/<?= $unit->url ?>"><?= $unit->title ?></a></div>
                    <div class="card-block">
                        <p class="card-text"><?= stripWord($unit->content, 100) ?></p>
                        <?php if($unit->dateup > $unit->date) : ?>
                            Обновлено <span data-timeago="<?= strtotime($unit->dateup) ?>"><?= dateReformat($unit->dateup) ?></span>
                        <?php else : ?>
                            Размещено <span data-timeago="<?= strtotime($unit->date) ?>"><?= dateReformat($unit->date) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php include(view('includes/footer')) ?>