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
        'image'     => 'varchar(199)',
        'images'    => 'text',
        'connect'   => 'int(11)',
        'active'    => 'int(1)::1',
        'iscatalog' => 'int(1)',
        'date'      => 'timestamp::CURRENT_TIMESTAMP',
        'dateup'    => 'timestamp::CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
    ];
    public $fillable = ['title', 'active', 'content'];
    public $extraView = [
        'content' => 'wysiwyg'
    ];
    public $noEmpty = ['active', 'date', 'dateup'];
    public $titles = [
        'title'     => 'Название',
        'url'       => 'Ссылка ЧПУ',
        'price'     => 'Цена',
        'content'   => 'Описание',
        'image'     => 'Главное изображение',
        'images'    => 'Изображения (одно в строку)',
        'connect'   => 'Привязан к',
        'active'    => 'Активен',
        'iscatalog' => 'Это каталог',
        'date'      => 'Дата добавления',
        'dateup'    => 'Дата обновления'
    ];

}