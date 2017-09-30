<?php

class Feedback extends A_BaseModel {

    public $table = 'feedback';
    public $title = 'Обратная связь';
    public $addable = true;
    public $removable = true;
    public $fields = [
        'id'      => 'int(11):key_ai',
        'name'    => 'varchar(100)',
        'tel'     => 'varchar(50)',
        'email'   => 'varchar(100)',
        'content' => 'text',
        'date'    => 'timestamp::CURRENT_TIMESTAMP',
    ];
    public $inputTypes = [
        'id' => 'hidden'
    ];
    public $fillable = ['name', 'tel', 'email', 'content'];
    public $required = ['name', 'content'];
    public $pattern = [
        'name' => ['[А-яA-z ]{3,50}', 'Длиннее трёх символов, может содержать буквы и пробел'],
        'email' => ['.+?@.+?\.[A-z]+', '']
    ];
    public $noEmpty = ['date'];
    public $titles = [
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
        $this->save(0, $data, true);
  
        smail('Отзыв от ' . $data['name'], $mailText, EMAIL_CONTACT);
        
        return [
            'message' => 'Сообщение отправлено',
            'type' => 'success',
            'mailText' => DEBUG ? $mailText : ''
        ];
    }

}