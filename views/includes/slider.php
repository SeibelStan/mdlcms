<?php if($slider && $slider->active && $slides) : ?>

<div class="slider-<?= $slider->id ?>">
    <?php foreach($slides as $slide) : ?>
        <a class="slider-item"
            <?php if($slide->link) : ?>
                href="<?= $slide->external ? '' : ROOT ?><?= $slide->link ?>"
            <?php endif; ?>
            style="background-image: url('<?= $slide->image ?>'); height: <?= $slider->height ?>px;">
            <div class="slider-item-inner">
                <?php if($slide->title) : ?>
                    <h3><?= $slide->title ?></h3>
                <?php endif; ?>
                <?php if($slide->content) : ?>
                    <div><?= $slide->content ?></div>
                <?php endif; ?>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<script>
$(function () {
    $('.slider-<?= $slider->id ?>').slick({
        infinite: true,
        lazyLoad: 'ondemand',
        dots: <?= $slider->dots ? 'true' : 'false' ?>,
        autoplay: <?= $slider->autoplay ?>,
        autoplaySpeed: <?= $slider->speed ?>,
        slidesToShow: <?= $slider->toshow ?>,
        slidesToScroll: <?= $slider->toscroll ?>,
    });
});
</script>

<?php endif; ?>