<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

use Dialogflow\WebhookClient;
use Dialogflow\Action\Responses\Image;

$agent = new WebhookClient(json_decode(file_get_contents('php://input'),true));

if ($agent->getRequestSource()=='google') {
    $conv = $agent->getActionConversation();
    
    $conv->close(Image::create('https://picsum.photos/400/300'));
    
    $agent->reply($conv);
}

header('Content-type: application/json');
echo json_encode($agent->render());
