<?php
    $hasPhotos = $directUnit->image || $directUnit->images;
?>

<div class="row mb-5">
    <?php if($hasPhotos) : ?>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <p>
                <a class="card" href="<?= $directUnit->image ?>" data-toggle="lightbox" data-gallery="lightbox">
                    <img class="img-fluid" src="<?= $directUnit->image ?>">
                </a>
            </p>
            <?php if($directUnit->images) : ?>
                <div class="row">
                    <?php foreach(textRows($directUnit->images) as $image) : ?>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <a href="<?= $image ?>"
                                data-toggle="lightbox"
                                data-gallery="lightbox"
                                class="card-img-top-cover height-card-image-half"
                                style="background-image: url('<?= $image ?>')"
                            ></a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="<?= $hasPhotos ? 'col-lg-4 col-md-6' : '' ?> col-sm-12">
        <h1><?= $directUnit->title ?></h1>

        <p class="text-success">
            <strong id="direct-unit-price" data-price="<?= $directUnit->price ?>"><?= $directUnit->price ?></strong>
            <?= CURRENCY ?>

        <form class="form-ajax form-inline" action="<?= ROOT ?>cart/add" method="post">
            <div class="input-group mr-sm-2">
                <input type="number" name="count" id="direct-unit-count" class="form-control" value="1" placeholder="Количество">
            </div>
            <div class="input-group">
                <button type="submit" class="btn btn-success">В корзину</button>
            </div>
            <input type="hidden" name="item_id" value="<?= $directUnit->id ?>">
            <input type="hidden" name="model" value="<?= $model::getName() ?>">
        </form>

        <?php if($directUnit->content) : ?>
            <hr>

            <article>
                <?= $directUnit->content ?>
            </article>
        <?php endif; ?>
    </div>
</div>

