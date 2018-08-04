<?php include(view('includes/header')) ?>

<?php if (!$directUnit->static) : ?>
    <ul class="nav">
        <li class="nav-item"><a href="<?= ROOT ?><?= $model::getName() ?>" class="nav-link"><?= $model::getTitle() ?></a>
        <li class="nav-item"><span class="nav-link"><?= $directUnit->title ?></span>
    </ul>
<?php endif; ?>

<main class="container">
    <?php if ($directUnit->image) : ?>
        <p><img class="img-fluid" src="<?= $directUnit->image ?>">
    <?php endif; ?>

    <h1><?= $directUnit->title ?></h1>

    <?php if (!$directUnit->static) : ?>
        <p class="text-muted"><?= dateReformat($directUnit->date) ?>
    <?php endif; ?>

    <article>
        <?= $directUnit->content ?>
    </article>
</main>

<?php include(view('includes/footer')) ?>