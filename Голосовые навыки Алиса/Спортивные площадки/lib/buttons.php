<?php


function init_button(){
    $array_buttons = array();
    array_unshift($array_buttons, array('name' => '🔍 Поиск' , 'hide' => true));
    array_unshift($array_buttons, array('name' => '🏠 Адрес', 'hide' => true ));
    return  $array_buttons;
}
function event_button($protocol, $data, $e, $array_buttons){

    if ($e['user_id'] == UserId($protocol, $data)){
        //Только организатор
        array_unshift($array_buttons, array('name' => 'Удалить'));
    }

    $telegram_url = get_telegram_url($protocol,$data, $e);

//    if ($telegram_url != ''){
//        array_unshift($array_buttons, array('name' => 'Телеграм чат', 'url' => $telegram_url , 'hide' => false ));
//    }

    array_unshift($array_buttons, array('name' => 'На карте'    , 'url' => get_map_url($protocol,$data, $e), 'hide' => false ));

    //Возможно пользователь уже записан
//    if ( check_schedule($protocol, $data, $e['id']) == null){
//        array_unshift($array_buttons, array('name' => 'Записаться'));
//    }else{
//        array_unshift($array_buttons, array('name' => 'Отписаться'));
//    }
    return  $array_buttons;
}



function sportzone_button($array_buttons, $sportzone ){
//    array_unshift($array_buttons, array('name' => '🏓 ‍Настольный теннис'));

    $array_sports = array();
    //Соберем уникальные виды спорта
    foreach ($sportzone as $sz) {
        if (in_array($sz['sport_name'] , $array_sports)) {

        }else{
            array_unshift($array_sports, $sz['sport_name']);
        }
    }
   sort($array_sports);

   foreach ($array_sports as $sport) {
      $count = sport_analize($sportzone,  $sport);
              $text = get_smile_html_by_sport($sport) . $sport . " " . sport_analize($sportzone,  $sport) ;
              array_unshift($array_buttons, array('name' => $text));
    }

    return  $array_buttons;
}




function sport_analize($sportzone, $sport ){

    foreach ($sportzone as $sz) {
        if ($sz['sport_name'] == $sport) {
            $i++;
        }
    }

    return  $i;
}




function org_button($array_buttons ){
    array_unshift($array_buttons, array('name' => 'Создать 😎'));
    return  $array_buttons;
}

function next_button($protocol, $data, $array_buttons, $events ){
    if ( count($events)  > 5 ){
        array_unshift($array_buttons, array('name' => 'Ещё 👉', 'hide' => true ) );
    }
    return  $array_buttons;
}