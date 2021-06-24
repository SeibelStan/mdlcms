<?php
    global $model;
    global $fields;
?>

<main class="container">
    <?php if ($model::isAddable()) : ?>
        <div class="row">
            <form class="col-lg-4 col-md-6 col-sm-12 form-ajax form-resetable" action="<?= ROOT ?>home/send-feedback" method="post">
                <?php include 'views/includes/fields.php' ?>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Отправить</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</main>