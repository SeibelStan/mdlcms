<?php include(view('includes/header')) ?>

<div class="container">
    <p class="lead"><?= count($result) ?> результатов по запросу &laquo;<?= request('searchQuery') ?>&raquo;</ph1>
    <div class="row">
    <?php foreach($result as $unit) : ?>
        <div class="col-sm-3">
            <div class="card mb-4">
                <a href="<?= $unit->link ?>"
                    class="card-img-top-cover height-card-image"
                    style="background-image: url('<?= $unit->image ?>')"
                ></a>
                <div class="card-header"><a href="<?= $unit->link ?>"><?= $unit->title ?></a></div>
                <div class="card-block">
                    <?php if($unit->content) : ?>
                        <p class="card-text"><?= stripWord($unit->content, 100) ?></p>
                    <?php endif; ?>
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
</div>

<?php include(view('includes/footer')) ?>