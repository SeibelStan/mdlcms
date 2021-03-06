<?php
    global $result;
?>

<main class="container">
    <p class="lead"><?= count($units) ?> результатов по запросу &laquo;<?= $query ?>&raquo;</ph1>
    <div class="row">
    <?php foreach ($units as $unit) : ?>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card mb-4">
                <a href="<?= $unit->link ?>"
                    class="card-img-top-cover height-card-image"
                    style="background-image: url('<?= $unit->image ?>')"
                ></a>
                <div class="card-header"><a href="<?= $unit->link ?>"><?= $unit->title ?></a></div>
                <div class="card-body">
                    <?php if ($unit->content) : ?>
                        <p class="card-text"><?= stripWord($unit->content, 100) ?></p>
                    <?php endif; ?>
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
</main>