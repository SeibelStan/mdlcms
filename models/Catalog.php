<?php

class Catalog extends A_BaseModel {

    public static $table = 'catalog';
    public static $title = 'Каталог';
    public static $addable = true;
    public static $removable = true;
    public static $fields = [
        'id'        => 'int(11)::key_ai',
        'title'     => 'varchar(100)',
        'url'       => 'varchar(100)',
        'price'     => 'int(11)',
        'markdown'  => 'text',
        'content'   => 'text',
        'image'     => 'varchar(199)',
        'images'    => 'text',
        'connect'   => 'varchar(50):0',
        'active'    => 'int(1):1',
        'iscatalog' => 'int(1):0',
        'date'      => 'timestamp:NOW()',
        'dateup'    => 'timestamp:NOW()'
    ];
    public static $inputTypes = [
        'id'       => 'hidden',
        'date'     => 'hidden',
        'dateup'   => 'hidden',
        'markdown' => 'wysiwyg',
        'content'  => 'hidden'
    ];
    public static $searchable = ['title', 'url', 'content'];
    public static $titles = [
        'title'     => 'Название',
        'url'       => 'Ссылка ЧПУ',
        'price'     => 'Цена',
        'markdown'  => 'Описание',
        'content'   => 'Описание (html)',
        'image'     => 'Главное изображение',
        'images'    => 'Изображения (одно в строку)',
        'connect'   => 'Привязан к',
        'active'    => 'Активен',
        'iscatalog' => 'Это каталог',
        'date'      => 'Дата добавления',
        'dateup'    => 'Дата обновления'
    ];

}