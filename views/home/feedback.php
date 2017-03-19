<?php include(view('includes/header')) ?>

<div class="container">
    <?php if($model->isAddable()) : ?>
        <form action="<?= ROOT ?>home/send-feedback" class="ajax-form" method="post">
            <?php include(view('includes/fields')) ?>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Отправить</button>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include(view('includes/footer')) ?>