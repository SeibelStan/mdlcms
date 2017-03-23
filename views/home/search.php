<?php include(view('includes/header')) ?>

<div class="container">
    <h1><?= count($result) ?> результатов по запросу &laquo;<?= request('query') ?>&raquo;</h1>
    <p>
    <div class="row">
    <?php foreach($result as $unit) : ?>
        <div class="col-sm-3">
            <div class="card">
                <a href="<?= $unit->link ?>"
                    class="card-img-top-cover height-card-image"
                    style="background-image: url('<?= $unit->image ?>')"
                ></a>
                <div class="card-block">
                    <h4 class="card-title">
                        <a href="<?= $unit->link ?>"><?= $unit->title ?></a>
                    </h4>
                    <?php if($unit->content) : ?>
                        <p class="card-text"><?= stripWord($unit->content, 100) ?></p>
                    <?php endif; ?>
                    <?php if($unit->date) : ?>
                        <p class="card-text"><small class="text-muted"><?= dateReformat($unit->date) ?></small></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
</div>

<?php include(view('includes/footer')) ?>