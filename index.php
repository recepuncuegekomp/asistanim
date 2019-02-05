<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

use Dialogflow\WebhookClient;
use Dialogflow\Action\Responses\SimpleResponse;

$agent = new WebhookClient(json_decode(file_get_contents('php://input'),true));

$agent->reply('İşte bu ayki kazancınız...');

header('Content-type: application/json');
echo json_encode($agent->render());
