<?php
    global $model;
    global $units;
?>

<ul class="nav">
    <li class="nav-item"><a href="<?= ROOT ?><?= $model::getName() ?>" class="nav-link"><?= $model::getTitle() ?></a>
</ul>

<main class="container">
    <?php include 'views/includes/pagination.php' ?>

    <div class="row">
        <?php foreach ($units as $unit) : ?>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card mb-4">
                    <a href="<?= ROOT ?><?= $model::getName() ?>/<?= $unit->url ?>"
                        class="card-img-top-cover height-card-image"
                        style="background-image: url('<?= $unit->image ?>')"
                    ></a>
                    <div class="card-header"><a href="<?= ROOT ?><?= $model::getName() ?>/<?= $unit->url ?>"><?= $unit->title ?></a></div>
                    <div class="card-body">
                        <p class="card-text"><?= stripWord($unit->content, 100, '...') ?></p>
                        <p class="card-text small text-muted">
                            <?php if ($unit->dateup > $unit->date) : ?>
                                Обновлено <span data-time="<?= strtotime($unit->dateup) ?>"><?= dateReformat($unit->dateup) ?></span>
                            <?php else : ?>
                                Размещено <span data-time="<?= strtotime($unit->date) ?>"><?= dateReformat($unit->date) ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php include 'views/includes/pagination.php' ?>
</main>