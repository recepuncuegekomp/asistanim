<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

use Dialogflow\WebhookClient;
use Dialogflow\RichMessage\Image;

$agent = new WebhookClient(json_decode(file_get_contents('php://input'),true));

$image = Image::create('https://picsum.photos/200/300');

$agent->reply($image);

//header('Content-type: application/json');
//echo json_encode($agent->render());
