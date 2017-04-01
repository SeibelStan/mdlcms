<?php include(view('includes/admin-header')) ?>

<main class="container">
    <ul class="nav flex-column">
        <li><a href="<?= ROOT ?>admin/edit-models">Модели</a>
        <li><a href="<?= ROOT ?>admin/files">Файлы</a>
        <li><a href="<?= ROOT . CRONKEY ?>/reset-attempts">Сбросить попытки</a>
    </ul>
</main>

<?php include(view('includes/admin-footer')) ?>