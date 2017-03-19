<?php

class Feedback extends A_BaseModel {

    public $table = 'feedback';
    public $title = 'Обратная связь';
       public $addable = true;
       public $removable = true;
    public $fields = [
        'id'      => 'int(11):key_ai',
        'name'    => 'varchar(100)',
        'content' => 'text'
    ];
    public $fillable = ['name', 'content'];
    public $titles = [
        'name'    => 'Имя',
        'content' => 'Комментарий'
    ];

    public function send($data) {
        global $db;
        if(ATTEMPTS) {
            $attempts = dbs("select * from attempts where type = 'feedback' and ip = '" . USER_IP . "'");
            $count_att = count($attempts);
            if($count_att >= 5) {
                return [
                    'message' => 'Попробуйте позже',
                    'error' => true
                ];
            }
            dbi("insert into attempts (type, data, ip) values ('feedback', '" . $db->real_escape_string(json_encode($data)) . "', '" . USER_IP . "')");
        }

        $mailHeaders = "Content-type: text/html; charset=utf-8 \r\n";
        $mailHeaders .= "From: " . SITE_NAME . "<" . ADMIN_EMAIL . ">\r\n";

        $mailText = '';
        foreach($data as $name => $value) {
            $mailText .= '<p>' . $this->getFieldTitle($name) . ': ' . nl2br($value);
        }

        mail(CONTACT_EMAIL, 'Отзыв от ' . $data['name'], $mailText, $mailHeaders);
        return [
            'message' => 'Сообщение отправлено',
            'messageType' => 'success',
            'mailText' => DEBUG ? $mailText : ''
        ];
    }

}