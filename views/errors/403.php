<?php include(view('includes/header')) ?>

<main class="container">
    <h1>Заблокированы</h1>
    <p>Ваш IP: <strong><?= USER_IP ?></strong>
    <p>Обратитесь к администратору: <?= EMAIL_CONTACT ?>
</main>

<?php include(view('includes/footer')) ?>