<?php
$token = '5157341455:AAGNtT2zn5XkdsabqisoDdZvieJZhdyQI5w';
$website = 'https://api.telegram.org/bot'.$token;
$input = file_get_contents('php://input');
$update = json_decode($input, TRUE);
$chatId = $update['message']['chat']['id'];
$message = $update['message']['text'];
$reply=$update["message"]["reply_to_message"]["text"];
$urlDeportes="https://e00-marca.uecdn.es/rss/futbol/primera-division.xml";
$urlInternacionales="https://www.abc.es/rss/feeds/abc_Internacional.xml";
$urlEconomicas="https://e00-expansion.uecdn.es/rss/portada.xml";
$urlCulturales="https://www.abc.es/rss/feeds/abc_ultima.xml";
$urlAvisosEspaña="http://www.aemet.es/documentos_d/eltiempo/prediccion/avisos/rss/CAP_AFAE_wah_RSS.xml";
$urlAvisosInternacionales="https://e00-elmundo.uecdn.es/blogs/elmundo/clima/index.xml";
$urlNoticias="http://www.europapress.es/rss/rss.aspx";

    if(empty($reply)){

            switch($message) {
                case '/start':
                    $response = 'Me has iniciado';
                    sendMessage($chatId, $response);
                    break;
                case '/info':
                    $response = 'Bienvenido! Soy @xCrimson_bot';
                    sendMessage($chatId, $response);
                    break;
                case '/help':
                    $response = 'Hola! Soy @xCrimson_bot, los comandos que puedes usar son:
                    -/start  para iniciarme.
                    -/info para tener información sobre mi.
                    -/help para saber todos los comandos disponibles.
                    -/noticias para tener todas las noticias del momento.
                    -/avisos para conocer todos los avisos climaticos a nivel nacional e internacional';
                    sendMessage($chatId, $response);
                    break;    
                case '/noticias':
                    $obligarRespuesta=forzarRespuesta();
                    $response="¿Que tipo de noticia quieres ver? (deportes, generales, culturales, internacionales o economicas)";
                    sendMessage($chatId,$response,$obligarRespuesta);
                    break;
                case '/avisos':
                    $obligarRespuesta=forzarRespuesta();
                    $response="¿Sobre cual quieres saber? (españa o internacionales)";
                    sendMessage($chatId,$response,$obligarRespuesta);
                    break;
                default:
                    $response = 'No te he entendido, puedes usar /help para conocer los comandos disponibles.';
                    sendMessage($chatId, $response);
                    break;
            }
    }else{

        if($reply=="¿Que tipo de noticia quieres ver? (deportes, generales, culturales, internacionales o economicas)"){
            switch ($message){
                case 'deportes':
                    getNoticias($chatId, $urlDeportes);
                break;
                case 'generales':
                    getNoticias($chatId, $urlNoticias);
                break;
                case 'culturales':
                    getNoticias($chatId, $urlCulturales);
                break;
                case 'internacionales':
                    getNoticias($chatId, $urlInternacionales);
                break;
                case 'economicas':
                    getNoticias($chatId, $urlEconomicas);
                break;
            }
        }if($reply=="¿Sobre cual quieres saber? (españa o internacionales)"){
            switch ($message){
                case 'españa':
                    getNoticias($chatId, $urlAvisosEspaña);
                break;
                case 'internacionales':
                    getNoticias($chatId, $urlAvisosInternacionales);
                break;
        }
    }
}
function sendMessage($chatId, $response, $reply_markup="") {
    $url = $GLOBALS['website'].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response)."&reply_markup=".$reply_markup;
    file_get_contents($url);
}

function forzarRespuesta(){
    $reply_markup= array ('force_reply' => true, 'selective' => true);
    return json_encode($reply_markup, true);
}
function getNoticias($chatId,$url){
    //include("simple_html_dom.php");
    $context = stream_context_create(array('http' =>  array('header' => 'Accept: application/xml')));
    $xmlstring = file_get_contents($url, false, $context);
    $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    for ($i=0; $i < 5; $i++){ 
        $titulos = $titulos."\n\n".$array['channel']['item'][$i]['title']."<a href='".$array['channel']['item'][$i]['link']."'> +info</a>";
    }
    sendMessage($chatId, $titulos);
}

?>