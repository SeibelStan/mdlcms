<?php

class BotController {

    public static function index() {
        global $bot;

        $test = isset($_GET['test']);
        $data = json_decode(file_get_contents('php://input'));

        if ($test) {
            $message = (object) [
                'text' => $_GET['message']
            ];
            $chatId = BOTMYID;
            $firstName = 'SS';
        }
        else {
            $message = $data->message;
            $firstName = $message->from->first_name;
            $chatId = $message->chat->id;
        }

        $message->text = urldecode($message->text);
        $message->text = preg_replace('/< /', '', $message->text);
        $attrs = preg_split('/[ _]/', $message->text);
        $method = preg_replace('/\//', '', $attrs[0]);
        $attrs = array_splice($attrs, 1);

        $response = preg_replace('/^\s+/m', '', $response);

        if ($test) {
            header('Content-type: text/plain');
            print_r($response);
        }
        else {
            $bot->sendMessage([
                'chat_id' => $chatId,
                'text' => $response,
                'parse_mode' => 'Markdown'
            ]);
        }
    }

    public static function init() {
        $link = BOTAPI . 'setWebhook?url=https://' . DOMAIN . '/bot';
        file_get_contents($link);
        echo $link . '<br>ok';
    }

}