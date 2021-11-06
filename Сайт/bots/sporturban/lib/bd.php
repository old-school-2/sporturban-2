<?php


function set_field_log($protocol, $data, $field , $value)
{
    //Обновим поле
    db_query("UPDATE alisa_log SET " . $field . "='" . $value . "' WHERE protocol='" . $protocol . "' AND user_id ='" . UserId($protocol, $data) . "' AND session_id ='" . SessionId($protocol, $data) . "'", "u");
}

function answers_bd($type, $character)
{
    if ($character == ''){
        $character = 'athena';
    }
    $query = db_query("SELECT text FROM alisa_answers WHERE type ='" . $type . "' ORDER BY RAND() LIMIT 1");

    if ($query == false){
        $result =  'Нет ответа по type=' . $type .' для character=' . $character;
    }else{
        $result     = $query[0]['text'];
    }
    return $result ;
}

function check_sport($text)
{
    $query = db_query("SELECT type FROM mln_type_sport WHERE type='" . $text  . "'  LIMIT 1" );

    if ($query == false){

        $query = db_query("SELECT type FROM mln_type_sport WHERE MATCH (type,keywords) AGAINST ('" . $text  . "')  LIMIT 1" );
        if ($query == false){
            return '';
        }else{
            return $query[0]['type'];
        }
    }else{
        return $query[0]['type'];
    }
}


function comands_bd($text, $conditions)
{
    if ($text == ''){
        return 'главная';
    }

    if ($conditions == '') {
        $query = db_query("SELECT type FROM alisa_requests WHERE MATCH (text) AGAINST ('" . $text  . "')  LIMIT 1" );
    }else{
        $query = db_query("SELECT type FROM alisa_requests WHERE MATCH (text) AGAINST ('" . $text  . "') AND conditions='" . $conditions  . "' LIMIT 1" );
    }

    if ($query == false){
        return '';
    }else{
        return $query[0]['type'];
    }
}

function set_settings($protocol, $data, $geo, $adress){

    $qr = db_query("SELECT * FROM alisa_settings WHERE protocol='" . $protocol . "' AND user_id ='" . UserId($protocol, $data) . "' LIMIT 1");

    if ($qr == null ){
       db_query("INSERT INTO alisa_settings ( protocol, user_id, geo, adress) VALUES (
                '" . $protocol . "',
                '" . UserId($protocol, $data)  . "',
                '" . $geo . "',
                '" . $adress . "')", "i");
    }else{
        db_query("UPDATE alisa_settings SET geo='" .  $geo . "', adress='" . $adress . "' WHERE protocol='" . $protocol . "' AND  user_id='" . UserId($protocol, $data)  . "'", "u");
    }
}

function get_log($protocol, $data){
    $qr = db_query("SELECT * FROM alisa_log WHERE protocol='" . $protocol . "' AND user_id ='" . UserId($protocol, $data) . "' AND session_id ='" . SessionId($protocol, $data) .  "' LIMIT 1");

    if ($qr == null){
        $qr = db_query("INSERT INTO alisa_log ( protocol, user_id, session_id) VALUES (
                '" . $protocol . "',
                '" . UserId($protocol, $data)  . "',
                '" . SessionId($protocol, $data)  . "')", "i");

        $qr = db_query("SELECT * FROM alisa_log WHERE protocol='" . $protocol . "' AND user_id ='" . UserId($protocol, $data) . "' AND session_id ='" . SessionId($protocol, $data) . "' LIMIT 1");
    }
    return $qr[0];
}

function add_event($protocol, $data, $place, $sport, $date, $time, $org_geo, $img, $city){
   $map_url = "https://static-maps.yandex.ru/1.x/?ll=" . $org_geo ."&size=650,300&z=15&l=map&pt=" . $org_geo . ",pm2rdl";
   list($lng , $lat) =  parse_geo($org_geo);

   $img_url = get_img_url_by_sport($sport);
   $map_img = save_img( $map_url );
   $date  = date("Y-m-d", strtotime($date ));

   $qr = db_query("INSERT INTO alisa_events ( city, protocol, user_id, place, sport, date, time, img, img_url, geo, map_img, lng, lat, map_url) VALUES (  
                '" .  $city . "',
                '" .  $protocol . "',
                '" .  UserId($protocol, $data) . "',
                '" .  $place  . "', 
                '" .  $sport  . "', 
                '" .  $date   . "',  
                '" .  $time    . "',
                '" .  $img   . "',
                '" .  $img_url   . "',
                '" .  $org_geo   . "',
                '" .  $map_img   . "',
                '" .  $lng   . "',
                '" .  $lat   . "',
                '" .  $map_url   . "')", "i");

   curl_post_async($qr);
   return $qr;
}



function get_geo($protocol, $data){
    $qr =  db_query("SELECT geo FROM alisa_settings WHERE protocol='" . $protocol . "' AND user_id ='" . UserId($protocol, $data) . "' LIMIT 1");
    return  parse_geo( $qr[0]['geo'] );
}

function get_settings($protocol, $data){
    $qr =  db_query("SELECT * FROM alisa_settings WHERE protocol='" . $protocol . "' AND user_id ='" . UserId($protocol, $data) . "' LIMIT 1");
    return $qr[0] ;
}


function get_events_array($protocol, $data, $sport, $offset){

    if ($offset > 0){
        $sql_off = " OFFSET " . $offset;
    }else{
        $sql_off = "";
    }

   list($lng , $lat) = get_geo($protocol, $data);

    $cond = "";

    $cond = " WHERE date >='" . date('Y-m-d') .  "' ";

    $d = 0.1;
    if ($lng != 0 OR  $lat != 0){
        if ($cond == ""){
            $cond = " WHERE";
        }else{
            $cond = $cond . " AND";
        }

        $cond = $cond . " lng BETWEEN " . ($lng - $d). " AND " . ($lng + $d)  . " AND ";
        $cond = $cond . " lat BETWEEN " . ($lat - $d). " AND " . ($lat + $d) ;
    }

    if ($sport != null AND $sport != 'любой'){
        if ($cond == ""){
            $cond = " WHERE";
        }else{
            $cond = $cond . " AND";
        }
        $cond = $cond . " sport='" . $sport . "'";
    }

    $search = "SELECT * FROM alisa_events " . $cond . " LIMIT " . (get_offset($protocol) + 1);
    set_field_log($protocol, $data, 'search_sql' , clearData( $search ));

    $qr = db_query($search . $sql_off);

    if (count( $qr ) == (get_offset($protocol) + 1)){
        $offset = $offset + get_offset($protocol);
    }else{
        $offset = 0;
    }

    set_field_log($protocol, $data, 'offset' , $offset );
    return $qr;
}


function get_events($protocol, $data, $search, $offset){
    if ($offset > 0){
        $sql_off = " OFFSET " . $offset;
    }else{
        $sql_off = "";
    }

    $qr = db_query($search . $sql_off);

    if (count( $qr ) == (get_offset($protocol) + 1)){
        $offset = $offset + get_offset($protocol) ;
    }else{
        $offset = 0;
    }

    set_field_log($protocol, $data, 'offset' , $offset );
    return $qr;
}

function get_events_shedule($protocol, $data, $sport, $offset){

    if ($offset > 0){
        $sql_off = " OFFSET " . $offset;
    }else{
        $sql_off = "";
    }

    $search = "SELECT * FROM alisa_schedule INNER JOIN alisa_events  on id = event_id WHERE alisa_schedule.protocol ='" . $protocol .
                                                                                         "' AND alisa_schedule.user_id ='" . UserId($protocol, $data) .
                                                                                         "' AND date >='" . date('Y-m-d') .  "' LIMIT " . (get_offset($protocol) + 1);
    set_field_log($protocol, $data, 'search_sql' , clearData( $search ) );

   $qr = db_query($search . $sql_off);

    if (count( $qr ) == (get_offset($protocol) + 1)){
        $offset = $offset + get_offset($protocol);
    }else{
        $offset = 0;
    }

    set_field_log($protocol, $data, 'offset' , $offset );
    return $qr;
}

function get_events_array_autor($protocol, $data, $offset){

    if ($offset > 0){
        $sql_off = " OFFSET " . $offset;
    }else{
        $sql_off = "";
    }
    $search = "SELECT * FROM alisa_events WHERE user_id='" . UserId($protocol, $data) . "' AND date >='" . date('Y-m-d') .  "'  LIMIT " . (get_offset($protocol) + 1);
    set_field_log($protocol, $data, 'search_sql' , clearData( $search ) );
    $qr = db_query($search . $sql_off);

    if (count( $qr ) == (get_offset($protocol) + 1)){
        $offset = $offset + get_offset($protocol) ;
    }else{
        $offset = 0;
    }


    set_field_log($protocol, $data, 'offset' , $offset );
    return $qr;
}

function get_event($id){

    $qr = db_query("SELECT * FROM alisa_events WHERE id='" . $id . "' LIMIT 1");

    return $qr[0];
}


function subscribe($protocol, $data, $id)
{
             db_query("INSERT INTO alisa_schedule ( protocol, user_id, event_id) VALUES (
                '" . $protocol . "',
                '" . UserId($protocol, $data)  . "',
                '" . $id  . "')", "i");
}

function unsubscribe($protocol, $data, $id)
{
  db_query("DELETE FROM alisa_schedule WHERE protocol ='" . $protocol . "' AND user_id ='" . UserId($protocol, $data) . "' AND event_id='" . $id . "'");
}


function check_schedule($protocol, $data, $id)
{
    $qr = db_query("SELECT * FROM alisa_schedule WHERE protocol ='" . $protocol . "' AND user_id ='" . UserId($protocol, $data) . "' AND event_id='" . $id . "' LIMIT 1");

    return $qr;
}

function get_img_by_sport($sport) {

    $query = db_query("SELECT img_alisa FROM mln_type_sport WHERE type='" . $sport  . "'  LIMIT 1" );

    if ($query == false){
        return '';
    }else{
        return $query[0]['img_alisa'];
    }
}

function get_img_url_by_sport($sport) {

    $query = db_query("SELECT img FROM mln_type_sport WHERE type='" . $sport  . "'  LIMIT 1" );

    if ($query == false){
        return '';
    }else{
        return  "https://afisha.live/" . $query[0]['img'] ;
    }

}

function event_delete($id){
     db_query("DELETE FROM alisa_events WHERE id='" . $id . "'");
}



