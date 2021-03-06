<?php

class Info extends A_BaseModel {

    public static $table = 'info';
    public static $title = 'Инфо';
    public static $addable = true;
    public static $removable = true;
    public static $fields = [
        'id'       => 'int(11)::key_ai',
        'title'    => 'varchar(100)',
        'url'      => 'varchar(100)',
        'markdown' => 'text',
        'content'  => 'text',
        'image'    => 'varchar(199)',
        'static'   => 'int(1)',
        'active'   => 'int(1):1',
        'date'     => 'timestamp:NOW()',
        'dateup'   => 'timestamp:NOW()'
    ];
    public static $inputTypes = [
        'id'       => 'hidden',
        'dateup'   => 'hidden',
        'markdown' => 'wysiwyg',
        'content'  => 'hidden'
    ];
    public static $searchable = ['title', 'url', 'content'];
    public static $titles = [
        'title'    => 'Название',
        'url'      => 'Ссылка ЧПУ',
        'markdown' => 'Описание',
        'content'  => 'Описание (html)',
        'image'    => 'Главное изображение',
        'static'   => 'Статичная',
        'active'   => 'Активен',
        'date'     => 'Дата добавления',
        'dateup'   => 'Дата обновления'
    ];

}