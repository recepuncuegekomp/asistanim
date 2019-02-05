<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

use Dialogflow\WebhookClient;

$agent = new WebhookClient(json_decode(file_get_contents('php://input'),true));

$suggestion = \Dialogflow\RichMessage\Suggestion::create(['Seçenek 1', 'Seçenek 2']);
$agent->reply($suggestion);

header('Content-type: application/json');
echo json_encode($agent->render());
