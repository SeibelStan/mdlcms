<?php

class Codeparts extends A_BaseModel {

    public $table = 'codeparts';
    public $title = 'Части кода';
    public $addable = true;
    public $removable = true;
    public $fields = [
        'id'        => 'int(11):key_ai',
        'namespace' => 'varchar(20)',
        'title' => 'varchar(50)',
        'type'      => 'varchar(20)',
        'link'      => 'varchar(255)',
        'name'      => 'varchar(50)',
        'content'   => 'text',
        'params'    => 'varchar(255)'
    ];
    public $fillable = ['name', 'content'];
    public $titles = [
        'namespace' => 'Пространство',
        'title'     => 'Название',
        'type'      => 'Тип (script, stylesheet, style, meta)',
        'link'      => 'Href / src',
        'name'      => 'Name',
        'content'   => 'Content',
        'params'    => 'Параметры'
    ];

}