<div class="row">
    <div class="col-sm-6">
        <?php if($directUnit->image) : ?>
            <p>
                <a href="<?= $directUnit->image ?>" data-toggle="lightbox" data-gallery="lightbox">
                    <img class="img-fluid" src="<?= $directUnit->image ?>">
                </a>
        <?php endif; ?>
        <?php if($directUnit->images) : ?>
            <div class="row">
                <?php foreach(getTextRows($directUnit->images) as $image) : ?>
                    <div class="col-sm-4">
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
    <div class="col-sm-6">
        <h1><?= $directUnit->title ?></h1>

        <p class="text-success">
            <strong id="direct-unit-price" data-price="<?= $directUnit->price ?>"><?= $directUnit->price ?></strong>
            <?= CURRENCY ?>

        <form class="form-ajax form-inline" action="<?= ROOT ?>cart/add" method="post">
            <div class="input-group mr-sm-2">
                <input type="number" name="count" id="direct-unit-count" class="form-control" value="1" min="1" placeholder="Количество">
            </div>
            <div class="input-group">
                <button type="submit" class="btn btn-success">В корзину</button>
            </div>
            <input type="hidden" name="item_id" value="<?= $directUnit->id ?>">
            <input type="hidden" name="model" value="<?= $model->getName() ?>">
        </form>
        
        <hr>

        <article>
            <?= $directUnit->content ?>
        </article>
    </div>
</div>

