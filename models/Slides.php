<?php

class Slides extends A_BaseModel {

    public $table = 'slides';
    public $title = 'Слайды';
    public $addable = true;
    public $removable = true;
    public $fields = [
        'id'       => 'int(11):key_ai',
        'connect'  => 'varchar(20)',
        'title'    => 'varchar(100)',
        'content'  => 'text',
        'image'    => 'varchar(199)',
        'link'     => 'varchar(255)',
        'external' => 'int(1)',
        'active'   => 'int(1)::1',
    ];
    public $titles = [
        'connect'  => 'Привязан к',
        'title'    => 'Название',
        'content'  => 'Содержимое',
        'image'    => 'Изображение',
        'link'     => 'Ссылка',
        'external' => 'Внешняя',
        'active'   => 'Активен',
    ];

}