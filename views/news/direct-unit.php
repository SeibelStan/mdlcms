<?php if($directUnit->image) : ?>
    <p><img class="img-fluid" src="<?= $directUnit->image ?>">
<?php endif; ?>

<h1><?= $directUnit->title ?></h1>

<p class="text-muted"><?= dateReformat($directUnit->date) ?>

<article>
    <?= $directUnit->content ?>
</article>