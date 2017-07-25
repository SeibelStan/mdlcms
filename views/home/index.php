<?php include(view('includes/header')) ?>

<div class="jumbotron">
    <div class="container">
        <h1 class="display-3"><?= SITE_NAME ?></h1>
    </div>
</div>

<main class="container">
    <?php $slickSlider = $slider ?>
    <?php include(view('includes/slider')) ?>
</main>

<?php include(view('includes/footer')) ?>