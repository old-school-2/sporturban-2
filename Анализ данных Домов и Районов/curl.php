<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/dataanalize/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/db.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/functions.php";
// ----------------------------------------------------------------------------------------------
require_once $_SERVER['DOCUMENT_ROOT'] . "/dataanalize/functions.php";

//https://sporturban.ru/dataanalize/curl.php?&sport_id=155&id_adm_area=9
$sport_id    = $_GET['sport_id'];
$id_adm_area = $_GET['id_adm_area'];
$sport_name = get_sport_name($sport_id);
if ($sport_name != null) {
    $district = get_district(1, 146);
//Асинхронно запустим скрипт по обновлению данных по районам
    foreach ($district as $d) {
        //Центральный админинистративный округ
        if ($d['id_adm_area'] == $id_adm_area){
        curl_post_async("https://sporturban.ru/dataanalize/index.php?d_min=" . $d['id'] . "&d_max=" . $d['id'] . "&sport_id=" . $sport_id);
        }
    }
}

echo 'Обновление запущено: ' . $sport_name;