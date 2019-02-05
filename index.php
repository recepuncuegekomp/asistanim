<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

use Dialogflow\WebhookClient;
use Dialogflow\Action\Responses\SimpleResponse;

$agent = new WebhookClient(json_decode(file_get_contents('php://input'),true));
$conv = $agent->getActionConversation();

$conv->ask(SimpleResponse::create()
     ->displayText('Merhaba, size nasıl yardımcı olabilirim?')
     ->ssml('<speak>İşte, <break time="0.5s"/> <prosody rate="slow">bugünkü kazancınız...</prosody></speak>')
);

$conv->close('İyi günler!');

//header('Content-type: application/json');
//echo json_encode($agent->render());
