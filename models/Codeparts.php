<?php

class Codeparts extends A_BaseModel {

    public static $table = 'codeparts';
    public static $title = 'Части кода';
    public static $addable = true;
    public static $removable = true;
    public static $fields = [
        'id'        => 'int(11)::key_ai',
        'namespace' => 'varchar(20)',
        'title'     => 'varchar(50)',
        'type'      => 'varchar(20)',
        'link'      => 'varchar(255)',
        'name'      => 'varchar(50)',
        'content'   => 'text',
        'params'    => 'varchar(255)'
    ];
    public static $inputTypes = [
        'id' => 'hidden'
    ];
    public static $fillable = ['name', 'content'];
    public static $titles = [
        'namespace' => 'Пространство',
        'title'     => 'Название',
        'type'      => 'Тип (script, stylesheet, style, meta)',
        'link'      => 'Href / src',
        'name'      => 'Name',
        'content'   => 'Content',
        'params'    => 'Параметры'
    ];

}