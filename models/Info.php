<?php

class Info extends A_BaseModel {

    public $table = 'info';
    public $title = 'Инфо';
    public $addable = true;
    public $removable = true;
    public $fields = [
        'id'        => 'int(11):key_ai',
        'title'     => 'varchar(100)',
        'url'       => 'varchar(100)',
        'content'   => 'text',
        'markdown'  => 'text',
        'image'     => 'varchar(199)',
        'static'    => 'int(1)',
        'active'    => 'int(1)::1',
        'date'      => 'timestamp::NOW()',
        'dateup'    => 'timestamp::NOW()'
    ];
    public $inputTypes = [
        'id' => 'hidden',
        'dateup' => 'hidden',
        'content' => 'wysiwyg'
    ];
    public $noEmpty = ['date', 'dateup'];
    public $searchable = ['title', 'url', 'content'];
    public $titles = [
        'title'     => 'Название',
        'url'       => 'Ссылка ЧПУ',
        'content'   => 'Описание',
        'markdown'  => 'Markdown',
        'image'     => 'Главное изображение',
        'static'    => 'Статичная',
        'active'    => 'Активен',
        'date'      => 'Дата добавления',
        'dateup'    => 'Дата обновления'
    ];

}