<?php include(view('includes/admin-header')) ?>

<div class="container">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="<?= ROOT ?>admin/edit-models">Модели</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= ROOT ?>admin/files">Файлы</a>
        </li>
    </ul>
</div>

<?php include(view('includes/admin-footer')) ?>