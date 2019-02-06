<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require 'vendor/autoload.php';

use Dialogflow\WebhookClient;

$agent = new WebhookClient(json_decode(file_get_contents('php://input'),true));

$text = \Dialogflow\RichMessage\Text::create()
    ->text('Bu mesal text')
    ->ssml('<speak>Bu mesaj <say-as interpret-as="characters">ssml</say-as></speak>');
$agent->reply($text);

header('Content-type: application/json');
echo json_encode($agent->render());
