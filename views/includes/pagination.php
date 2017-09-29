<?php if($pagination) : ?>

<nav>
    <div class="pagination mb-4" style="opacity: 0;" data-steps="3">
        <?php foreach($pagination as $page) : ?>
            <li class="page-item <?= $page->active ? 'active' : '' ?>"
                <?php if(isset($page->helper)) : ?>
                    data-helper="<?= $page->helper ?>"
                <?php endif; ?>
            >
                <a class="page-link" href="<?= $page->link ?>"><?= $page->title ?></a>
        <?php endforeach; ?>
    </div>
</nav>

<?php endif; ?>