<?php foreach($codeparts as $codepart) : ?>
    <?php if($codepart->type == 'script' && getJS()) : ?>
        <script <?= $codepart->params ?> <?= $codepart->link ? 'src="' . $codepart->link . '"' : '' ?>><?= $codepart->content ?></script>
    <?php elseif($codepart->type == 'style') : ?>
        <style <?= $codepart->params ?>><?= $codepart->content ?></style>
    <?php elseif($codepart->type == 'stylesheet') : ?>
        <link <?= $codepart->params ?> rel="stylesheet" href="<?= $codepart->link ?>">
    <?php elseif($codepart->type == 'meta') : ?>
        <meta name="<?= $codepart->name ?>" content="<?= $codepart->content ?>">
    <?php else : ?>
        <?= $codepart->content ?>
    <?php endif; ?>
<?php endforeach; ?>