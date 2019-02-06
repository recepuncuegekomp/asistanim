<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

use Dialogflow\WebhookClient;

$agent = new WebhookClient(json_decode(file_get_contents('php://input'),true));

/* $text = \Dialogflow\RichMessage\Text::create()
    ->text('Bu mesaj text')
    ->ssml('<speak>Bu mesaj <say-as interpret-as="characters">ssml</say-as></speak>');
	
$agent->reply($text); */

$image = \Dialogflow\RichMessage\Image::create('https://picsum.photos/240/240');
$agent->reply($image);

header('Content-type: application/json');
echo json_encode($agent->render());
