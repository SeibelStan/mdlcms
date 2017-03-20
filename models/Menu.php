<?php

class Menu extends A_BaseModel {

    public $table = 'menu';
    public $title = 'Меню';
    public $addable = true;
    public $removable = true;
    public $fields = [
        'id'        => 'int(11):key_ai',
        'namespace' => 'varchar(20)',
        'title'     => 'varchar(100)',
        'link'      => 'varchar(255)',
        'external'  => 'int(1)',
        'params'    => 'varchar(255)',
        'sort'      => 'varchar(10)'
    ];
    public $fillable = ['name', 'content'];
    public $titles = [
        'namespace' => 'Пространство',
        'title'     => 'Название',
        'link'      => 'Ссылка',
        'external'  => 'Внешняя',
        'params'    => 'Параметры',
        'sort'      => 'Порядок'
    ];

}