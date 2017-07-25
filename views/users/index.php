<?php include(view('includes/header')) ?>

<main class="container">
    <form class="col-lg-4 col-md-6 col-sm-12 form-ajax" action="<?= ROOT ?>users/save" method="post">
        <?php include(view('includes/fields')) ?>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </form>
</main>

<?php include(view('includes/footer')) ?>