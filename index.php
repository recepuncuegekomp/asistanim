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

$conv->ask('Please choose below');

$conv->ask(
    Carousel::create()
    ->Option(
        Option::create()
        ->key('OPTION_1')
        ->title('Option 1')
        ->synonyms(['option one', 'one'])
        ->description('Select option 1')
        ->image('https://picsum.photos/300/300')
    )
    ->Option(
        Option::create()
        ->key('OPTION_2')
        ->title('Option 2')
        ->synonyms(['option two', 'two'])
        ->description('Select option 2')
        ->image('https://picsum.photos/300/300')
    )
    ->Option(
        Option::create()
        ->key('OPTION_3')
        ->title('Option 3')
        ->synonyms(['option three', 'three'])
        ->description('Select option 3')
        ->image('https://picsum.photos/300/300')
    )
    ->Option(
        Option::create()
        ->key('OPTION_4')
        ->title('Option 4')
        ->synonyms(['option four', 'four'])
        ->description('Select option 4')
        ->image('https://picsum.photos/300/300')
    )
);
