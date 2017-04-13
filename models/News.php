<?php

class News extends A_BaseModel {

    public $table = 'news';
    public $title = 'Новости';
    public $addable = true;
    public $removable = true;
    public $fields = [
        'id'        => 'int(11):key_ai',
        'title'     => 'varchar(100)',
        'url'       => 'varchar(100)',
        'content'   => 'text',
        'image'     => 'varchar(199)',
        'static'    => 'int(1)',
        'active'    => 'int(1)::1',
        'date'      => 'timestamp::CURRENT_TIMESTAMP',
        'dateup'    => 'timestamp::CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
    ];
    public $inputTypes = [
        'id' => 'hidden',
        'dateup' => 'hidden',
        'content' => 'wysiwyg'
    ];
    public $noEmpty = ['date'];
    public $searchable = ['title', 'url', 'content'];
    public $titles = [
        'title'     => 'Название',
        'url'       => 'Ссылка ЧПУ',
        'content'   => 'Описание',
        'image'     => 'Главное изображение',
	    'static'    => 'Статичная',
        'active'    => 'Активен',
        'date'      => 'Дата добавления',
        'dateup'    => 'Дата обновления'
    ];

}