<?php include(view('includes/header')) ?>

<main class="container">
    <h1><?= tr('forbidden') ?></h1>
    <p>IP: <strong><?= USER_IP ?></strong>
    <p><?= tr('contact_us') ?>: <?= EMAIL_CONTACT ?>
</main>

<?php include(view('includes/footer')) ?>