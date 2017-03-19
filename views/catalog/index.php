<?php include(view('includes/header')) ?>

<div>
<a href="<?= ROOT ?>catalog">Каталог</a>
<?php if($parentUnit) : ?>
    -
    <a href="<?= ROOT ?>catalog/<?= $parentUnit->url ?>"><?= $parentUnit->title ?></a>
<?php endif; ?>
<?php if($directUnit) : ?>
    -
    <?= $directUnit->title ?>
<?php endif; ?>
</div>

<?php if($directUnit) : ?>
    <?php include(view('catalog/direct-unit')) ?>
<?php endif; ?>

<?php foreach($units as $unit) : ?>
    <a href="<?= ROOT ?>catalog/<?= $unit->url ?>"><?= $unit->title ?></a>
<?php endforeach; ?>

<?php include(view('includes/footer')) ?>