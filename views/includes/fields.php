<?php foreach($fields as $field) : ?>
    <div class="autolabel">
        <div class="form-group">
            <?php if($field->control != 'hidden') : ?>
                <label><?= $field->title ?> <?= $field->required ? '*' : '' ?></label>
            <?php endif; ?>
            <?php if($field->control == 'wysiwyg') : ?>
                <textarea name="<?= $field->name ?>" class="ckeditor"><?= $field->value ?></textarea>
            <?php elseif($field->control == 'datetime-local') : ?>
                <?php
                    $ttime = strtotime($field->value);
                    $tvalue = date('Y-m-d', $ttime) . 'T' . date('H:i', $ttime);
                ?>
                <input class="form-control" type="<?= $field->control ?>" name="<?= $field->name ?>" value="<?= $tvalue ?>">
            <?php elseif($field->control == 'textarea') : ?>
                <textarea class="form-control"
                    <?= $field->required ? 'required' : '' ?>
                      name="<?= $field->name ?>"><?= $field->value ?></textarea>
            <?php elseif($field->control == 'checkbox') : ?>       
                <input type="<?= $field->control ?>" name="<?= $field->name ?>" <?= $field->value ? 'checked' : '' ?>>
            <?php else : ?>
                <input class="form-control"
                    <?= $field->required ? 'required' : '' ?>
                    <?php $pattern = $model->getPattern($field->name); ?>
                    <?php if($pattern) : ?>
                        pattern="<?= $pattern[0] ?>" title="<?= $pattern[1] ?>"
                    <?php endif; ?>
                    type="<?= $field->control ?>" name="<?= $field->name ?>" value="<?= $field->value ?>">
            <?php endif; ?>
        </div>
    </div>
    <?php if(in_array($field->name, ['images', 'image'])) : ?>
        <div class="form-group">
            <a class="btn btn-secondary last-focused-top" data-toggle="modal" data-target="#filesModal">Выбрать</a>
        </div>
    <?php endif; ?>
    <?php if($field->name == 'url') : ?>
        <div class="form-group">
            <a class="btn btn-secondary friendly-url-fill">Генерировать</a>
        </div>
    <?php endif; ?>
<?php endforeach; ?>