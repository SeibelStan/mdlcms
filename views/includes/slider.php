<div class="slider-<?= $slider->id ?>">
    <?php foreach(getTextRows($slider->images) as $slide) : ?>
        <div class="slider-item" style="background-image: url('<?= $slide ?>'); height: <?= $slider->height ?>px;"></div>
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