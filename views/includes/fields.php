<?php foreach ($fields as $field) : ?>
    <div class="autolabel">
        <div class="form-group">
            <?php if ($field->control != 'hidden') : ?>
                <label>
                    <?php
                        $fieldTitle = tr($field->name, false);
                        if (Helpers::checkAdminZone() || $fieldTitle == $field->name) {
                            $fieldTitle = $field->title;
                        }
                    ?>
                    <?= $fieldTitle ?>
                    <?= $field->required ? '*' : '' ?>
                </label>
            <?php endif; ?>

            <?php if ($field->control == 'wysiwyg') : ?>
                <textarea name="<?= $field->name ?>" id="editor"><?= $field->value ?></textarea>
            <?php elseif ($field->control == 'datetime-local') : ?>
                <?php
                    $ttime = strtotime($field->value);
                    $tvalue = date('Y-m-d', $ttime) . 'T' . date('H:i', $ttime);
                ?>
                <input class="form-control" type="<?= $field->control ?>" name="<?= $field->name ?>" value="<?= $tvalue ?>">
            <?php elseif ($field->control == 'textarea') : ?>
                <textarea class="form-control"
                    <?= $field->required ? 'required' : '' ?>
                      name="<?= $field->name ?>"><?= $field->value ?></textarea>
            <?php elseif ($field->control == 'checkbox') : ?>
                <input type="hidden" name="<?= $field->name ?>" value="0">
                <input type="<?= $field->control ?>" name="<?= $field->name ?>" <?= $field->value ? 'checked' : '' ?>>
            <?php elseif ($field->control == 'select') : ?>
                <select class="form-control" name="<?= $field->name ?>" value="<?= $field->value ?>"></select>
            <?php else : ?>
                <input class="form-control"
                    <?= $field->required ? 'required' : '' ?>
                    <?php $pattern = $model::getPattern($field->name); ?>
                    <?php if ($pattern) : ?>
                        <?= $field->control = 'text' ?>
                        pattern="<?= $pattern[0] ?>" title="<?= $pattern[1] ?>"
                    <?php endif; ?>
                    <?= $field->type == 'float' ? 'step="any"' : '' ?>
                    type="<?= $field->control ?>"
                    name="<?= $field->name ?>"
                    value="<?= $field->value ?>">
            <?php endif; ?>

            <?php if (in_array($field->name, ['images', 'image'])) : ?>
                <div class="mt-2 btn btn-secondary last-focused-set" data-toggle="modal" data-target="#filesModal">Выбрать</div>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>