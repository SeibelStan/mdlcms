<?php

class Slider extends A_BaseModel {

    public $table = 'slider';
    public $title = 'Слайдер';
    public $addable = true;
    public $removable = true;
    public $fields = [
        'id'        => 'int(11):key_ai',
        'title'     => 'varchar(100)',
        'name'      => 'varchar(20)',
        'height'    => 'int(11)::320',
        'toshow'    => 'int(11)::1',
        'toscroll'  => 'int(11)::1',
        'autoplay'  => 'int(1)::1',
        'speed'     => 'int(11)::2000',
        'dots'      => 'int(1)',
        'active'    => 'int(1)::1'
    ];
    public $noEmpty = ['speed', 'height', 'toshow', 'toscroll', 'date', 'dateup'];
    public $titles = [
        'title'     => 'Название',
        'name'      => 'Имя',
        'images'    => 'Изображения (одно в строку)',
        'height'    => 'Высота в px',
        'toshow'    => 'Показывать слайдов',
        'toscroll'  => 'Прокручивать по',
        'autoplay'  => 'Автопрокручивание',
        'speed'     => 'Скорость',
        'dots'      => 'Точки',
        'active'    => 'Активен'
    ];

}