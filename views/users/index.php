<?php include(view('includes/header')) ?>

<div class="container">
    <div class="row">
        <form class="col-lg-4 col-md-6 col-sm-12 form-ajax" action="<?= ROOT ?>users/save" method="post">
            <?php include(view('includes/fields')) ?>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </div>
</div>

<?php include(view('includes/footer')) ?>