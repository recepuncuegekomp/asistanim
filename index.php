<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

function TL($sayi) {
	return number_format($sayi, 2, ',', '.');
}

function getData( $rota ) {	
	$data = null;	
	$curl = curl_init();
	curl_setopt_array($curl, array(	  
	  CURLOPT_URL => 'http://egekomp.isimheryerde.com/Test/index.php?rota=' . $rota,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_SSL_VERIFYPEER => false,
	  CURLOPT_SSL_VERIFYHOST => 0,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 0,
	  CURLOPT_TIMEOUT => 90,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => "",
	  CURLOPT_HTTPHEADER => array("cache-control: no-cache"),
	));
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
	if (!$err) {	
	  $data = json_decode($response, true);
	}		
	return $data;
}

function getMesaj( $rota ) {		
	$data = getData( $rota );	
	$mesaj = "Sonuç bulunamadı...";
	switch ($rota) {
		case "tahsildeki_cekler":
			$mesaj = sprintf('Tahsildeki çek adeti %s, toplam tutar ise %s', $data['ADET'], TL($data['TUTAR']));
			break;
		case "cari_borc_alacak":
			$mesaj = sprintf('%s adet cari için, %s borç, %s alacak bulunuyor. Bakiye %s', $data['CARI_ADET'], TL($data['BORC']), TL($data['ALACAK']), TL($data['BAKIYE']));			
			break;
	}
	return $mesaj;
}

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

	$sonuc = getMesaj($parameters['rapor_adi']);
	$conv->ask( $sonuc );
	
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
