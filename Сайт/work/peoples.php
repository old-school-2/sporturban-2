<?php

date_default_timezone_set("UTC"); // Устанавливаем часовой пояс по Гринвичу
header('Content-Type: text/html; charset=utf-8'); // устанавливаем кодировку

require_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/db.php";
//require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/functions.php";

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

// достаём все дома с координатами
$houses = db_query("SELECT 
          id,
          area_residential,
          lng, 
          lat 
          FROM mos_realty 
          WHERE lng!=0 
          AND area_residential!=0");

// достаём все спорт объекты
$so = db_query("SELECT a.id, 
 a.object_id,
 a.lng,
 a.lat,
 mos_availability.km 
 FROM mos_objects AS a 
 JOIN mos_availability ON a.availability_id = mos_availability.id 
 WHERE a.peoples=0");
 
 if ($so != false) {
    
    foreach($so as $sportObject) {
        
        $colPeoplesSportObject = 0;
        
        foreach($houses as $val) {
            $distance = distance($sportObject['lat'], $sportObject['lng'], $val['lat'], $val['lng']);
            
            if ($distance <= $sportObject['km']) {
                
                $colPeoplesHouse = $val['area_residential'] / 18.5;
                $colPeoplesSportObject += $colPeoplesHouse;
            }
        }
        
        $colPeoplesSportObject = round($colPeoplesSportObject);
        
        if ($colPeoplesSportObject > 0) {
            $upd = db_query("UPDATE mos_objects 
            SET peoples='".$colPeoplesSportObject."' 
            WHERE id=".$sportObject['id']." 
            LIMIT 1","u");
            
            if ($upd == true) {
                $sportZone = db_query("UPDATE mos_objects_sportzone 
                SET peoples='".$colPeoplesSportObject."' 
                WHERE object_id=".$sportObject['object_id'],"u");
            }
        }
    }
 }