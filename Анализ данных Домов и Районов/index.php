<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/dataanalize/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/db.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/functions.php";
// ----------------------------------------------------------------------------------------------
require_once $_SERVER['DOCUMENT_ROOT'] . "/dataanalize/functions.php";

//https://sporturban.ru/dataanalize/index.php?d_min=125&d_max=125&sport_id=155
$sport_id = $_GET['sport_id'];
$sport_name = get_sport_name($sport_id);
if ($sport_name != null){
//Получим все районы c id в диапазоне 1-149
$district = get_district($_GET['d_min'], $_GET['d_max']);

foreach ($district as $d) {
    //Все дома района
    $homes = get_homes($d['id']);
    //Количество домов района
    $h_district = count($homes);
    $y_district = 0;
    $koeff_count_zone = 0;
    //Анализ каждого дома
    foreach ($homes as $h) {
        $sportzone = get_sportzone($h['lat'], $h['lng'], $sport_id);
        $koeff_count_home = 0;
        $y = 0;
        //Анализ каждой спортзоны
        foreach ($sportzone as $s) {
            //Расстояние между двумя точками
            $distance = distance($s['lat'], $s['lng'], $h['lat'], $h['lng']);
            //Активация по доступности, например шаговая доступность действует только 500 метров, и далее не учитывается
            if ($s['km'] <=  $distance){
                //Коэффициент площади сложим с доступностью и 1 и разделим на квадрат расстояния
                $k =  ( log(10 + $s['sportzone_area'],10) + $s['km']/2) / (1 + 3*pow($distance,2))/10 ;
                $koeff_count_zone = $koeff_count_zone + 1;
                $koeff_count_home =  $koeff_count_home + 1;
            }else{
                $k = 0;
            }
            $y = $y + $k;
        }

        if ($y > 0){
            //Для оптимизации базы сохраняем только значения не нулевые
            set_data_analiz($h, $sport_id , $sport_name, $y, $koeff_count_home  );
        }
        $y_district =  $y_district + $y;
    }

    //Средний показатель по району
    $y_district_avg = $y_district / $h_district;

    //Среднее число активированных спортзон на дом
    $koeff_count_zone =  $koeff_count_zone / $h_district;
    set_data_analiz_district($d['id'], $sport_id , $sport_name, $d['district_name'] ,  $y_district_avg,  $koeff_count_zone );
}

}

echo 'Средний коэффициент района:' .  $y_district_avg . ' Домов: '.  $h_district . ' Вид споорта: ' . $sport_name;