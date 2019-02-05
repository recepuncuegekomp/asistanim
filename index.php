<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

use Dialogflow\WebhookClient;
use Dialogflow\Action\Responses\MediaObject;
use Dialogflow\Action\Responses\MediaResponse;
use Dialogflow\Action\Responses\Suggestions;
use Dialogflow\Action\Questions\Carousel;
use Dialogflow\Action\Questions\Carousel\Option;

$agent = new WebhookClient(json_decode(file_get_contents('php://input'),true));

$text = \Dialogflow\RichMessage\Text::create()
    ->text('Merhaba, işte aylık kazancınız...')
    ->ssml('<speak>Merhaba, <say-as interpret-as="characters">işte aylık kazancınız...</say-as></speak>')
;
$agent->reply($text);

header('Content-type: application/json');
echo json_encode($agent->render());
