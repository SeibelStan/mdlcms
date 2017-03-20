<?php include(view('includes/header')) ?>

<div class="nav">
    <li class="nav-item"><a href="<?= ROOT ?><?= $model->getTable() ?>" class="nav-link"><?= $model->getTitle() ?></a>
    <?php if($directUnit) : ?>
        <li class="nav-item"><span class="nav-link"><?= $directUnit->title ?></span>
    <?php endif; ?>
</div>

<div class="container">
    <?php if($directUnit) : ?>
        <?php include(view($model->getTable() . '/direct-unit')) ?>
    <?php endif; ?>

    <?php if($units) : ?>
    <div class="row">
        <?php foreach($units as $unit) : ?>
            <div class="col-sm-3">
                <div class="card">
                    <a href="<?= ROOT ?><?= $model->getTable() ?>/<?= $unit->url ?>"
                        class="card-img-top-cover height-card-image"
                        style="background-image: url('<?= $unit->image ?>')"
                    ></a>
                    <div class="card-block">
                        <h4 class="card-title">
                            <a href="<?= ROOT ?><?= $model->getTable() ?>/<?= $unit->url ?>"><?= $unit->title ?></a>
                        </h4>
                        <p class="card-text"><?= stripWord($unit->content, 100) ?></p>
                        <p class="card-text"><small class="text-muted"><?= dateReformat($unit->date) ?></small></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php include(view('includes/footer')) ?>