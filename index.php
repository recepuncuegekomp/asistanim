<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

function TL($sayi) {
	return number_format($sayi, 2, ',', '.');
}

function getData( $rota, $aranacakKelime ) {	
	$data = null;	
	$curl = curl_init();
	curl_setopt_array($curl, array(	  
	  CURLOPT_URL => "http://egekomp.isimheryerde.com/Test/index.php?rota={$rota}&aranacakKelime={$aranacakKelime}",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_SSL_VERIFYPEER => false,
	  CURLOPT_SSL_VERIFYHOST => 0,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 0,
	  CURLOPT_TIMEOUT => 90,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => '',
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

function getMesaj($rota) {
	$data = getData($rota, '');	
	$mesaj = "Sonuç bulunamadı...";
	switch ($rota) {
		case "tahsildeki_cekler":
			$mesaj = sprintf('Tahsildeki çek sayısı %s, toplam tutar ise %s', $data['ADET'], TL($data['TUTAR']));
			break;
		case "cari_borc_alacak":
			$mesaj = sprintf('%s adet kayıt var. %s borç, %s alacak bulunuyor. Bakiye %s', $data['CARI_ADET'], TL($data['BORC']), TL($data['ALACAK']), TL($data['BAKIYE']));			
			break;
	}
	return $mesaj;
}

function jsonKaydet($dosyaAdi, $array) {
	$fp = fopen($dosyaAdi . '.json', 'w');
	fwrite($fp, json_encode($array));
	fclose($fp);
}

use Dialogflow\WebhookClient;
use Dialogflow\Action\Responses\Image;
use Dialogflow\Action\Responses\BasicCard;
use Dialogflow\Action\Questions\ListCard;
use Dialogflow\Action\Questions\ListCard\Option;
use Dialogflow\RichMessage;

$post = json_decode(file_get_contents('php://input'),true);
$agent = new WebhookClient($post);
$parameters = $agent->getParameters();
$query = $agent->getQuery();
$originalRequest = $agent->getOriginalRequest();
$originalRequestSource = $agent->getRequestSource();
$contexts = $agent->getContexts();

$conv = $agent->getActionConversation();
$arguments = $conv->getArguments();

if ($conv) {
	/*$conv->close('Bu bir conversation işlemi.');*/	

	if ($parameters['rapor_adi']=='stok_bul') {
		
		$komut = $post['queryResult']['outputContexts'][0]['parameters']['rapor_adi.original'];
		$aranacakKelime = trim(str_replace($komut, '', $query));
		$stoklar_json = getData($parameters['rapor_adi'], $aranacakKelime );
		$stoklar = json_decode($stoklar_json, true);
		
		$sonuclar = ListCard::create();
		$sonuclar->title('Sonuçlar:');
		
		$i = 0;
		foreach($stoklar as $stok) {
			$i++;
			$sonuc = Option::create();
			$sonuc->key('OPTION_'.$i);
			$sonuc->title( $stok['STOK_KODU'] );			
			$sonuc->description( $stok['STOK_ADI'] );
			$sonuc->image('https://picsum.photos/48/48');
			$sonuclar->addOption($sonuc);
		}
		
		$conv->ask('Stok bulundu.');
		$conv->ask($sonuclar);
		
	} else {
		$sonuc = getMesaj($parameters['rapor_adi']);
		$conv->ask($sonuc);
	}
	
	$agent->reply($conv);
	
	//$conv->ask('İşte fotoğraf...');	
	//$conv->close(Image::create('https://picsum.photos/240/240'));	
	
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
