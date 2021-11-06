<?php

function get_district($d_min, $d_max)
{


    $query = db_query("SELECT * FROM mln_districts where id>=".$d_min." AND id<=".$d_max);


    return $query;
}


function get_homes($district_id)
{

    if ($district_id != ""){
        $query = db_query("SELECT * FROM mos_realty WHERE district_id='" . $district_id . "'");
    }else{
        $query = db_query("SELECT * FROM mos_realty WHERE formalname_region='" . "Москва" . "'");
    }

    return $query;
}

function get_sportzone($lat, $lng, $sport_id)
{
    $lat_step = 1/111 * 5;
    $lng_step = 1/111 * 5 / 0.54;

    $lat_min = $lat - $lat_step;
    $lat_max = $lat + $lat_step;

    $lng_min = $lng - $lng_step;
    $lng_max = $lng + $lng_step;

    $query = db_query("SELECT * FROM mos_objects_sportzone WHERE sport_id='" . $sport_id .
                      "' AND lat BETWEEN ".$lat_min." AND ". $lat_max .
                      "  AND lng BETWEEN ".$lng_min." AND ".$lng_max );


    return $query ;
}

function set_home_field($id, $field , $value)
{
    //Обновим поле
    db_query("UPDATE mos_realty SET " . $field . "='" . $value . "' WHERE id='" . $id . "'", "u");
}


function distance($lat_1, $lon_1, $lat_2, $lon_2) {

    $radius_earth = 6371; // Радиус Земли

    $lat_1 = deg2rad($lat_1);
    $lon_1 = deg2rad($lon_1);
    $lat_2 = deg2rad($lat_2);
    $lon_2 = deg2rad($lon_2);

    $d = 2 * $radius_earth * asin(sqrt(sin(($lat_2 - $lat_1) / 2) ** 2 + cos($lat_1) * cos($lat_2) * sin(($lon_2 - $lon_1) / 2) ** 2));
//
    return number_format($d, 2, '.', '');
}

function    set_data_analiz($h, $sport_id , $sport_name, $koeff_y, $koeff_count)
{
    //Обновим поле
    $query = db_query("UPDATE data_analiz_full SET houseguid='" . $h['houseguid'] .
                                              "' , sport_id='" . $sport_id .
                                              "' , sport_name='" . $sport_name .
                                              "' , koeff_y='"    . $koeff_y .
                                              "' , koeff_count='"    . $koeff_count .
                                              "' , lat='"      . $h['lat'] .
                                              "' , lng='"      . $h['lng'] .
                                              "' , address='"  . $h['address'] .
                                              "' , adm_area='" . $h['adm_area'] .
                                              "' , district='" . $h['district'] .

                        "' WHERE houseguid='" .  $h['houseguid'] .
                         "'  AND sport_id='" . $sport_id . "'", "u");
    if  ($query == false){
        //Вставим строку
        $query = db_query("INSERT INTO data_analiz_full (houseguid, sport_id, sport_name, koeff_y, koeff_count, lat, lng, address, adm_area, district ) VALUES (" .
        " '" .  $h['houseguid'] . "' ," .
        " '" . $sport_id . "' ," .
        " '" . $sport_name . "' ," .
        " '" . $koeff_y . "' ," .
        " '" . $koeff_count . "' ," .
        " '" . $h['lat'] . "' ," .
        " '" . $h['lng']  . "' ," .
        " '" . $h['address'] . "' ," .
        " '" . $h['adm_area'] . "' ," .
        " '" . $h['district']  . "')", "i");
    }
}

function set_data_analiz_district($district_id, $sport_id , $sport_name, $district_name , $koeff_y,  $koeff_count_zone )
{
    //Обновим поле
    $query = db_query("UPDATE data_analiz_district SET   district_id='"   . $district_id .
                                                    "' , sport_id='"      . $sport_id .
                                                    "' , sport_name='"    . $sport_name .
                                                    "' , district_name='" . $district_name .
                                                    "' , koeff_y='" . $koeff_y .
                                                    "' , koeff_count_zone='" . $koeff_count_zone .
                                                    "' WHERE district_id='" . $district_id .
                                                    "'   AND sport_id='" . $sport_id . "'", "u");
    if  ($query == false){
        //Вставим строку
        $query = db_query("INSERT INTO data_analiz_district(district_id, sport_id, sport_name, district_name, koeff_y, koeff_count_zone) VALUES (" .
            " '" . $district_id . "' ," .
            " '" . $sport_id . "' ," .
            " '" . $sport_name . "' ," .
            " '" . $district_name . "' ," .
            " '" . $koeff_y . "' ," .
            " '" . $koeff_count_zone . "')", "i");
    }
}




function get_sport_name($sport_id)
{
    $query = db_query("SELECT name_dataset FROM mln_type_sport where id='". $sport_id . "' LIMIT 1");
    return $query[0]['name_dataset'];
}

function get_sports()
{
    $query = db_query("SELECT id FROM mln_type_sport");
    return $query;
}

function curl_post_async($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_exec($ch);
    curl_close($ch);
}