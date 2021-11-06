<?php

date_default_timezone_set("UTC"); // Устанавливаем часовой пояс по Гринвичу
header('Content-Type: text/html; charset=utf-8'); // устанавливаем кодировку

require_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/db.php";
//require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/functions.php";


$popular = array();

// достаём виды спорта
$ts = db_query("SELECT id, popular FROM mln_type_sport");

foreach($ts as $b) {
    $popular[ $b['id'] ] = $b['popular'];
}

// достаём спортзоны
$sz = db_query("SELECT id, 
     sport_id, 
     peoples 
     FROM mos_objects_sportzone 
     WHERE peoples!=0 
     AND target_peoples=0");
     
if ($sz != false) {
    foreach($sz as $sportZone) {
        
        $target = 0;
        
        if (!empty($popular[ $sportZone['sport_id'] ])) {
            $target = $popular[ $sportZone['sport_id'] ] * $sportZone['peoples'];
            $target = round($target / 100000);
            
            $upd = db_query("UPDATE mos_objects_sportzone 
            SET target_peoples='".$target."' 
            WHERE id=".$sportZone['id']." 
            LIMIT 1","u");
        }
    }
}