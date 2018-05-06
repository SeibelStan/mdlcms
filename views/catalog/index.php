<?php include(view('includes/header')) ?>

<div class="nav">
    <li class="nav-item"><a href="<?= ROOT ?><?= $model::getName() ?>" class="nav-link"><?= $model::getTitle() ?></a>
    <?php if($parentUnit) : ?>
        <li class="nav-item"><a class="nav-link" href="<?= ROOT ?>catalog/<?= $parentUnit->url ?>"><?= $parentUnit->title ?></a>
    <?php endif; ?>
    <?php if($directUnit) : ?>
        <li class="nav-item"><span class="nav-link"><?= $directUnit->title ?></span>
    <?php endif; ?>
</div>

<main class="container">
    <?php if($directUnit) : ?>
        <?php if($directUnit->iscatalog) : ?>
            <?php include(view($model::getName() . '/direct-catalog')) ?>
        <?php else : ?>
            <?php include(view($model::getName() . '/direct-unit')) ?>
        <?php endif; ?>
    <?php endif; ?>

    <?php include(view('includes/pagination')) ?>

    <?php if($units) : ?>
    <div class="row">
        <?php foreach($units as $unit) : ?>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card mb-4">
                    <a href="<?= ROOT ?><?= $model::getName() ?>/<?= $unit->url ?>"
                        class="card-img-top-cover height-card-image"
                        style="background-image: url('<?= $unit->image ?>')"
                    ></a>
                    <div class="card-header"><a href="<?= ROOT ?><?= $model::getName() ?>/<?= $unit->url ?>"><?= $unit->title ?></a></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php include(view('includes/pagination')) ?>
</main>

<?php include(view('includes/footer')) ?>