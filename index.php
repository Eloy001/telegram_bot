<?php
$token = '5157341455:AAGNtT2zn5XkdsabqisoDdZvieJZhdyQI5w';
$website = 'https://api.telegram.org/bot'.$token;
$input = file_get_contents('php://input');
$update = json_decode($input, TRUE);
$chatId = $update['message']['chat']['id'];
$message = $update['message']['text'];
$reply=$update["message"]["reply_to_message"]["text"];

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
                    -/noticias para tener todas las noticias del momento.';
                    sendMessage($chatId, $response);
                    break;    
                case '/noticias':
                    $obligarRespuesta=forzarRespuesta();
                    $response="¿Que tipo de noticia quieres ver? (deportes, generales, culturales, internacionales o economicas)";
                    sendMessage($chatId,$response,$obligarRespuesta);
                    break;
                case '/avisos meteorologicos':
                    $obligarRespuesta=forzarRespuesta();
                    $response="¿Sobre cual comunidad autonoma quieres saber? (andalucia)";
                    sendMessage($chatId,$response,$obligarRespuesta);
                    break;
                default:
                    $response = 'No te he entendido';
                    sendMessage($chatId, $response);
                    break;
            }
    }else{

        if($reply=="¿Que tipo de noticia quieres ver? (deportes, generales, culturales, internacionales o economicas)"){
            switch ($message){
                case 'deportes':
                    getNoticiasDeportes($chatId);
                break;
                case 'general':
                    getNoticias($chatId);
                break;
                case 'culturales':
                    getNoticiasCulturales($chatId);
                break;
                case 'internacionales':
                    getNoticiasInternacionales($chatId);
                break;
                case 'economicas':
                    getNoticiasEconomicas($chatId);
                break;
            }
        }if($reply=="¿Sobre cual comunidad autonoma quieres saber? (andalucia)"){
            switch ($message){
                case 'andalucia':
                    getAvisosAndalucia($chatId);
                break;
        }
    }
}
function sendMessage($chatId, $response, $reply_markup="") {
    $url = $GLOBALS['website'].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response)."&reply_markup=".$reply_markup;
    file_get_contents($url);
}
function getNoticiasDeportes($chatId){
    $context = stream_context_create(array('http' =>  array('header' => 'Accept: application/xml')));
    $url = "https://e00-marca.uecdn.es/rss/futbol/primera-division.xml";
    $xmlstring = file_get_contents($url, false, $context);
    $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    for ($i=0; $i < 9; $i++) { 
    $titulos = $titulos."\n\n".$array['channel']['item'][$i]['title']."<a href='".$array['channel']['item'][$i]['link']."'> +info</a>";
    }
    sendMessage($chatId, $titulos);
}
function getNoticiasInternacionales($chatId){
    $context = stream_context_create(array('http' =>  array('header' => 'Accept: application/xml')));
    $url = "https://www.abc.es/rss/feeds/abc_Internacional.xml";
    $xmlstring = file_get_contents($url, false, $context);
    $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    for ($i=0; $i < 9; $i++) { 
    $titulos = $titulos."\n\n".$array['channel']['item'][$i]['title']."<a href='".$array['channel']['item'][$i]['link']."'> +info</a>";
    }
    sendMessage($chatId, $titulos);
}
function getNoticiasEconomicas($chatId){
    $context = stream_context_create(array('http' =>  array('header' => 'Accept: application/xml')));
    $url = "https://e00-expansion.uecdn.es/rss/portada.xml";
    $xmlstring = file_get_contents($url, false, $context);
    $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    for ($i=0; $i < 9; $i++) { 
    $titulos = $titulos."\n\n".$array['channel']['item'][$i]['title']."<a href='".$array['channel']['item'][$i]['link']."'> +info</a>";
    }
    sendMessage($chatId, $titulos);
}

function getNoticiasCulturales($chatId){
    $context = stream_context_create(array('http' =>  array('header' => 'Accept: application/xml')));
    $url = "https://www.abc.es/rss/feeds/abc_ultima.xml";
    $xmlstring = file_get_contents($url, false, $context);
    $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    for ($i=0; $i < 9; $i++) { 
    $titulos = $titulos."\n\n".$array['channel']['item'][$i]['title']."<a href='".$array['channel']['item'][$i]['link']."'> +info</a>";
    }
    sendMessage($chatId, $titulos);
}

function forzarRespuesta(){
    $reply_markup= array ('force_reply' => true, 'selective' => true);
    return json_encode($reply_markup, true);
}
function getNoticias($chatId){
    //include("simple_html_dom.php");
    $context = stream_context_create(array('http' =>  array('header' => 'Accept: application/xml')));
    $url = "http://www.europapress.es/rss/rss.aspx";
    $xmlstring = file_get_contents($url, false, $context);
    $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
    $json = j0son_encode($xml);
    $array = json_decode($json, TRUE);
    for ($i=0; $i < 9; $i++){ 
        $titulos = $titulos."\n\n".$array['channel']['item'][$i]['title']."<a href='".$array['channel']['item'][$i]['link']."'> +info</a>";
    }
    sendMessage($chatId, $titulos);
}
function getAvisosAndalucia($chatId){
    $context = stream_context_create(array('http' =>  array('header' => 'Accept: application/xml')));
    $url = "http://www.aemet.es/documentos_d/eltiempo/prediccion/avisos/rss/CAP_AFAC61_RSS.xml";
    $xmlstring = file_get_contents($url, false, $context);
    $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    for ($i=0; $i < 9; $i++) { 
    $titulos = $titulos."\n\n".$array['channel']['item'][$i]['title']."<a href='".$array['channel']['item'][$i]['link']."'> +info</a>";
    }
    sendMessage($chatId, $titulos);
}

?>