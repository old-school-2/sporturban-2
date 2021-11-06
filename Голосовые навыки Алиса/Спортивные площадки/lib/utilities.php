<?php

function get_protocol($data)
{
    if ($data['session'] != null ){
        $protocol = 'alisa';
    }elseif ($data['uuid'] != null){
        $protocol = 'sber';
    }else{
        $protocol = 'alisa';
    }
    return $protocol;
}

function get_state( $log ) {
    //Итоговый ответ
    $state = $log['state'];

    //По умолчанию Главная
    if ($state == ''){
        $state = 'главная';
    }
    return $state;
}

function get_telegram_url($protocol,$data, $e) {
//    $url = 'https://t.me/spb_vall/121?comment=186';//'http://t.me/sporturbanBot?start=' . urlencode(UserId($protocol,$data) ). '&event_id=' ;
    $url = $e['telegram_link'];
    return $url;
}

function get_map_url($protocol,$data, $e) {
//    $url = "https://yandex.ru/maps/?pt=37.643345,55.681656&z=16&l=map
    $url = "https://yandex.ru/maps/?pt=" .$e['lng'] . "," .$e['lat']   . "&z=18&l=map" ;
    return $url;
}

function check_adress($adress) {

    //Если Долгота - широта, нуно развернуть координаты

    $ch = curl_init('https://geocode-maps.yandex.ru/1.x/?apikey=c0a2116b-646f-4d03-8f18-55b021aceb0b&format=json&sco=latlong&geocode=' . urlencode("Москва " . $adress));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $res = curl_exec($ch);
    curl_close($ch);

    $res = json_decode($res, true);

    $coordinates = $res['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];

    $Components = $res['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['Components'];

    $locality = $Components[3]['name'];
    foreach ($Components as $value) {
        if ( $value['kind'] == "locality"){
            $locality  = $value['name'];
            break;
        }
    }

    $coordinates = explode(' ', $coordinates);
    $geo = $coordinates[0] . "," . $coordinates[1];

    $name = $res['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['text'];

    $name = str_replace("Россия, ", "", $name);

    return [ $geo, $name, $locality ];
}

function check_date($text) {

        switch ($text) {
            case 'сейчас':
                $d = strtotime("+0 day");
                $d = date('d.m.Y',$d);
                break;
            case 'сегодня':
                $d = strtotime("+0 day");
                $d = date('d.m.Y',$d);
                break;
            case 'завтра':
                $d = strtotime("+1 day");
                $d = date('d.m.Y',$d);
                break;
            case 'послезавтра':
                $d = strtotime("+2 day");
                $d = date('d.m.Y',$d);
                break;
            case 'понедельник':
                $d = strtotime("next Monday");
                $d = date('d.m.Y',$d);
                break;
            case 'вторник':
                $d = strtotime("next Tuesday");
                $d = date('d.m.Y',$d);
                break;
            case 'среда':
                $d = strtotime("next Wednesday");
                $d = date('d.m.Y',$d);
                break;
            case 'четверг':
                $d = strtotime("next Thursday");
                $d = date('d.m.Y',$d);
                break;
            case 'пятница':
                $d = strtotime("next Friday");
                $d = date('d.m.Y',$d);
                break;
            case 'суббота':
                $d = strtotime("next Saturday");
                $d = date('d.m.Y',$d);
                break;
            case 'воскресенье':
                $d = strtotime("next Sunday");
                $d = date('d.m.Y',$d);
                break;
            default:
                $d = null;
                break;
    }

    return $d;
}

function save_img($img_url)
{
    $url = "https://dialogs.yandex.net/api/v1/skills/dcc72fe1-368c-4e3c-a610-9e9267f72bad/images";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
        "Authorization: Bearer AQAAAAAMjLknAAT7o-_--6WDZU_cuo19dm760UI",
        "Content-Type: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $data = '{ "url": "' . $img_url . '" }';
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    $resp = curl_exec($curl) ;

    $res =  json_decode($resp, true);

    curl_close($curl);

    return  $res['image']['id'];
}

function curl_post_async($id)
{

    $url = "https://afisha.live/bots/sberhack/bot.php?event=" . $id;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    curl_exec($ch);
    curl_close($ch);
}
function parse_geo($str){
    if ($str == ''){
        return [0,0];
    }else{
        return explode(",", $str );
    }
}

function get_distance($lon_1, $lat_1, $lon_2, $lat_2)
{
//    if ($geo_a == "" or $geo_b == ""){
//        return "" ;
//    }else{
//      list($lon_1, $lat_1) = parse_geo($geo_a);
//      list($lon_2, $lat_2) = parse_geo($geo_b);
      return  distance($lat_1, $lon_1, $lat_2, $lon_2) . " ";
//    }
}

function distance($lat_1, $lon_1, $lat_2, $lon_2) {

    $radius_earth = 6371; // Радиус Земли

    $lat_1 = deg2rad($lat_1);
    $lon_1 = deg2rad($lon_1);
    $lat_2 = deg2rad($lat_2);
    $lon_2 = deg2rad($lon_2);

//    return $lat_1 . $lon_1 . $lat_2 . $lon_2;
    $d = 2 * $radius_earth * asin(sqrt(sin(($lat_2 - $lat_1) / 2) ** 2 + cos($lat_1) * cos($lat_2) * sin(($lon_2 - $lon_1) / 2) ** 2));
//
    return number_format($d, 2, '.', '').' км';

}
