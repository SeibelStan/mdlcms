<?php include(view('includes/header')) ?>

<div class="container">
    <div class="row">
        <table class="table">
        <tr>
            <th>№
            <th>Предметы
            <th>Сумма
            <th>Дата
            <th>Статус
        <?php foreach($orders as $order) : ?>
            <?php $sum = 0; ?>
            <tr>
            <td><?= $order->id ?>
            <td>
                <table class="table-inline">
                <?php foreach($order->items as $item) : ?>
                    <tr>
                        <td><a href="<?= ROOT . $item->model ?>/<?= $item->url ?>"><?= $item->title ?></a>
                        <td>x<?= $item->count ?>
                        <td><?= $item->price ?> <?= CURRENCY ?>
                        <td><?= $item->count * $item->price ?> <?= CURRENCY ?>
                        <?php $sum += $item->count * $item->price; ?>
                <?php endforeach; ?>
                </table>
            <td><?= $sum ?> <?= CURRENCY ?>
            <td><?= dateReformat($order->date) ?>
            <td><?= getStateText($order->state) ?>
        <?php endforeach; ?>
        </table>
    </div>
</div>

<?php include(view('includes/footer')) ?>