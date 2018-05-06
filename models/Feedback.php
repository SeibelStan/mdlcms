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
        'name' => ['[А-яA-z ]{3,50}', 'Длиннее трёх символов, может содержать буквы и пробел'],
        'email' => ['.+?@.+?\.[A-z]+', '']
    ];
    public static $noEmpty = ['date'];
    public static $titles = [
        'name'    => 'Имя',
        'tel'     => 'Телефон',
        'email'   => 'Емаил',
        'content' => 'Комментарий',
        'date'    => 'Дата добавления'
    ];

    public function send($data) {
        $attempt = Attempts::add('feedback', $data);
        if($attempt->action) {
            return $attempt;
        }

        $mailText = '';
        foreach($data as $name => $value) {
            if($this->isFillable($name)) {
                $mailText .= '<p>' . $this->getFieldTitle($name) . ': ' . nl2br(strip_tags($value));
            }
        }
        $this->save($data, 0, true);

        if(MAILS) {
            smail('Отзыв от ' . $data['name'], $mailText, EMAIL_CONTACT);
        }

        return [
            'message' => 'Сообщение отправлено',
            'type' => 'success',
            'mailText' => DEBUG ? $mailText : ''
        ];
    }

}