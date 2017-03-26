<?php include(view('includes/header')) ?>

<div class="container">
    <?php if($model->isAddable()) : ?>
        <div class="row">
            <form class="col-lg-4 col-md-6 col-sm-12 form-ajax form-resetable" action="<?= ROOT ?>home/send-feedback" method="post">
                <?php include(view('includes/fields')) ?>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Отправить</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include(view('includes/footer')) ?>