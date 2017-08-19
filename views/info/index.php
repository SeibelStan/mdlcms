<?php include(view('includes/header')) ?>

<ul class="nav">
    <li class="nav-item"><a href="<?= ROOT ?><?= $model->getName() ?>" class="nav-link"><?= $model->getTitle() ?></a>
</ul>

<main class="container">
    <?php include(view('includes/pagination')) ?>

    <?php if($units) : ?>
    <div class="row">
        <?php foreach($units as $unit) : ?>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card mb-4">
                    <a href="<?= ROOT ?><?= $model->getName() ?>/<?= $unit->url ?>"
                        class="card-img-top-cover height-card-image"
                        style="background-image: url('<?= $unit->image ?>')"
                    ></a>
                    <div class="card-header"><a href="<?= ROOT ?><?= $model->getName() ?>/<?= $unit->url ?>"><?= $unit->title ?></a></div>
                    <div class="card-block">
                        <p class="card-text"><?= stripWord($unit->content, 100) ?></p>
                        <p class="card-text small text-muted">
                            <?php if($unit->dateup > $unit->date) : ?>
                                Обновлено <span data-timeago="<?= strtotime($unit->dateup) ?>"><?= dateReformat($unit->dateup) ?></span>
                            <?php else : ?>
                                Размещено <span data-timeago="<?= strtotime($unit->date) ?>"><?= dateReformat($unit->date) ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php include(view('includes/pagination')) ?>
</main>

<?php include(view('includes/footer')) ?>