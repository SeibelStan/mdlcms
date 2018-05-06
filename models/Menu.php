<?php

class Menu extends A_BaseModel {

    public static $table = 'menu';
    public static $title = 'Меню';
    public static $addable = true;
    public static $removable = true;
    public static $fields = [
        'id'        => 'int(11)::key_ai',
        'namespace' => 'varchar(20)',
        'title'     => 'varchar(100)',
        'link'      => 'varchar(255)',
        'external'  => 'int(1)',
        'params'    => 'varchar(255)',
        'sort'      => 'varchar(10)'
    ];
    public static $inputTypes = [
        'id' => 'hidden',
        'link' => 'text',
        'params' => 'text'
    ];
    public static $titles = [
        'namespace' => 'Пространство',
        'title'     => 'Название',
        'link'      => 'Ссылка',
        'external'  => 'Внешняя',
        'params'    => 'Параметры',
        'sort'      => 'Порядок'
    ];

}