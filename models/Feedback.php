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
    public $fillable = ['name', 'tel', 'email', 'content'];
    public $required = ['name', 'content'];
    public $pattern = [
        'name' => ['[А-яA-z ]{3,50}', 'Длиннее трёх символов, может содержать буквы и пробел']
    ];
    public $noEmpty = ['date', 'dateup'];
    public $titles = [
        'name'    => 'Имя',
        'tel'     => 'Телефон',
        'email'   => 'Емаил',
        'content' => 'Комментарий',
        'date'    => 'Дата добавления'
    ];

    public function send($data) {
        if(ATTEMPTS) {
            $attempts = dbs("select * from attempts where type = 'feedback' and ip = '" . USER_IP . "'");
            $count_att = count($attempts);
            if($count_att >= 5) {
                return [
                    'message' => 'Попробуйте позже',
                    'error' => true
                ];
            }
            dbi("insert into attempts (type, data, ip) values ('feedback', '" . json_encode($data) . "', '" . USER_IP . "')");
        }

        $mailHeaders = "Content-type: text/html; charset=utf-8 \r\n";
        $mailHeaders .= "From: " . SITE_NAME . "<" . ADMIN_EMAIL . ">\r\n";

        $mailText = '';
        foreach($data as $name => $value) {
            $mailText .= '<p>' . $this->getFieldTitle($name) . ': ' . nl2br($value);
        }
        mail(CONTACT_EMAIL, 'Отзыв от ' . $data['name'], $mailText, $mailHeaders);

        $this->saveUnit(0, $data, true);
  
        return [
            'message' => 'Сообщение отправлено',
            'messageType' => 'success',
            'mailText' => DEBUG ? $mailText : ''
        ];
    }

}