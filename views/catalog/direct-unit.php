<div class="row">
    <div class="col-sm-6">
        <?php if($directUnit->image) : ?>
            <p><img class="img-fluid" src="<?= $directUnit->image ?>">
        <?php endif; ?>
        <?php if($directUnit->images) : ?>
            <div class="row">
                <?php foreach(getTextRows($directUnit->images) as $image) : ?>
                    <div class="col-sm-4">
                        <div class="card-img-top-cover height-card-image-half" style="background-image: url('<?= $image ?>')"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="col-sm-6">
        <h1><?= $directUnit->title ?></h1>

        <p class="text-success"><?= $directUnit->price ?> <?= CURRENCY ?>

        <article>
            <?= $directUnit->content ?>
        </article>
    </div>
</div>

