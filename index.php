<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

function getCurl( $rota ) {	
	$data = [];	
	$curl = curl_init();
	curl_setopt_array($curl, array(	  
	  CURLOPT_URL => 'http://egekomp.isimheryerde.com/Test/index.php?rota=' . $rota,
	  CURLOPT_RETURNTRANSFER => false,
	  CURLOPT_SSL_VERIFYPEER => false,
	  CURLOPT_SSL_VERIFYHOST => 0,	  
	  CURLOPT_TIMEOUT => 90,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => "",
	  CURLOPT_HTTPHEADER => array("cache-control: no-cache"),
	));	
	return $curl;
}

function tahsildeki_cekler( $rota ) {	
	$data = [];		
	$curl = getCurl( $rota );	
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
	if ($err) {
	  //echo "cURL Error #:" . $err;
	} else {
	  $data = json_decode($response, true);
	}		
	return sprintf('Tahsildeki çek adeti %s ve toplam tutar %s', $data['ADET'], number_format($data['TUTAR'], 2, ',', '.'));
}

require 'vendor/autoload.php';

use Dialogflow\WebhookClient;
use Dialogflow\Action\Responses\Image;
use Dialogflow\Action\Responses\BasicCard;
use Dialogflow\Action\Questions\ListCard;
use Dialogflow\Action\Questions\ListCard\Option;

$agent = new WebhookClient(json_decode(file_get_contents('php://input'),true));
$parameters = $agent->getParameters();
$conv = $agent->getActionConversation();

if ($conv) {
	/*$conv->close('Bu bir conversation işlemi.');*/	

	$data = getData('tahsildeki_cekler');
	$conv->ask( $data );
	
	//$conv->ask('İşte fotoğraf...');	
	//$conv->close(Image::create('https://picsum.photos/240/240'));	
	
	$agent->reply($conv);
} else {
	$agent->reply('İşlem action conversation değil.');
}

/* $text = \Dialogflow\RichMessage\Text::create()
    ->text('Bu mesaj text')
    ->ssml('<speak>Bu mesaj <say-as interpret-as="characters">ssml</say-as></speak>');
	
$agent->reply($text); */

/*$image = \Dialogflow\RichMessage\Image::create('https://picsum.photos/240/240');
$agent->reply($image);*/

header('Content-type: application/json');
echo json_encode($agent->render());
