<?php

class Feedback extends A_BaseModel {

    public static $table = 'feedback';
    public static $title = 'Обратная связь';
    public static $addable = true;
    public static $removable = true;
    public static $fields = [
        'id'      => 'int(11)::key_ai',
        'name'    => 'varchar(100)',
        'tel'     => 'varchar(50)',
        'email'   => 'varchar(100)',
        'content' => 'text',
        'date'    => 'timestamp:NOW()',
    ];
    public static $inputTypes = [
        'id' => 'hidden'
    ];
    public static $fillable = ['name', 'tel', 'email', 'content'];
    public static $required = ['name', 'content'];
    public static $pattern = [
        'name'  => ['[А-яA-z ]{3,50}', 'Длиннее трёх символов, может содержать буквы и пробел'],
        'email' => ['.+?@.+?\.[A-z]+', '']
    ];
    public static $titles = [
        'name'    => 'Имя',
        'tel'     => 'Телефон',
        'email'   => 'Емаил',
        'content' => 'Комментарий',
        'date'    => 'Дата добавления'
    ];

    public static function send($data) {
        $attempt = Attempts::add('feedback', $data);
        if ($attempt->action) {
            return $attempt;
        }

        $response = '';
        foreach ($data as $name => $value) {
            if (static::isFillable($name)) {
                $response .= '<p>' . static::getFieldTitle($name) . ': ' . nl2br(strip_tags($value));
            }
        }
        static::save($data, 0, true);

        if (MAILS) {
            smail('Сообщение от ' . $data['name'], $response, EMAIL_CONTACT);
        }

        /* @Bot
        if (BOT) {
            $bot->sendMessage([
                'chat_id' => BOTMYID,
                'text' => $response
            ]);
        }
        /* /Bot */

        return [
            'message'  => 'Сообщение отправлено',
            'type'     => 'success',
            'response' => DEBUG ? $response : ''
        ];
    }

}