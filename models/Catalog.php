<?php

class Catalog extends A_BaseModel {

    public $table = 'catalog';
    public $title = 'Каталог';
    public $addable = true;
    public $removable = true;
    public $fields = [
        'id'        => 'int(11):key_ai',
        'title'     => 'varchar(100)',
        'url'       => 'varchar(100)',
        'price'     => 'int(11)',
        'content'   => 'text',
        'markdown'  => 'text',
        'image'     => 'varchar(199)',
        'images'    => 'text',
        'connect'   => 'varchar(50)',
        'active'    => 'int(1)::1',
        'iscatalog' => 'int(1)',
        'date'      => 'timestamp::CURRENT_TIMESTAMP',
        'dateup'    => 'timestamp'
    ];
    public $inputTypes = [
        'id' => 'hidden',
        'date' => 'hidden',
        'dateup' => 'hidden',
        'content' => 'wysiwyg'
    ];
    public $noEmpty = ['date', 'dateup'];
    public $searchable = ['title', 'url', 'content'];
    public $titles = [
        'title'     => 'Название',
        'url'       => 'Ссылка ЧПУ',
        'price'     => 'Цена',
        'content'   => 'Описание',
        'markdown'  => 'Markdown',
        'image'     => 'Главное изображение',
        'images'    => 'Изображения (одно в строку)',
        'connect'   => 'Привязан к',
        'active'    => 'Активен',
        'iscatalog' => 'Это каталог',
        'date'      => 'Дата добавления',
        'dateup'    => 'Дата обновления'
    ];

}