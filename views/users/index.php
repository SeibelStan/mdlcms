<?php
    global$model;
    global $fields;
?>

<main class="container">
    <form class="col-lg-4 col-md-6 col-sm-12 form-ajax" action="<?= ROOT ?>users/save" method="post">
        <?php include 'views/includes/fields.php' ?>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </form>
</main>