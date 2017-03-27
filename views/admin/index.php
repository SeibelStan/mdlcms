<?php include(view('includes/admin-header')) ?>

<main class="container">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="<?= ROOT ?>admin/edit-models">Модели</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= ROOT ?>admin/files">Файлы</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= ROOT . CRONKEY ?>/reset-attempts">Сбросить попытки</a>
        </li>
    </ul>
</main>

<?php include(view('includes/admin-footer')) ?>