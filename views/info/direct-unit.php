<?php if($directUnit->image) : ?>
    <p><img class="img-fluid" src="<?= $directUnit->image ?>">
<?php endif; ?>

<h1><?= $directUnit->title ?></h1>

<?php if(!$directUnit->static) : ?>
    <p class="text-muted"><?= dateReformat($directUnit->date) ?>
<?php endif; ?>

<article>
    <?= $directUnit->content ?>
</article>