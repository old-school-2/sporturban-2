<?php


function init_button(){
    $array_buttons = array();
    array_unshift($array_buttons, array('name' => '🚑 Помощь', 'hide' => true));
    array_unshift($array_buttons, array('name' => '🛠 Настройки', 'hide' => true ));
    array_unshift($array_buttons, array('name' => '👋 Организатор', 'hide' => true));
    array_unshift($array_buttons, array('name' => '📅 Расписание' , 'hide' => true));
    array_unshift($array_buttons, array('name' => '🔍 Поиск' , 'hide' => true));
    return  $array_buttons;
}
function event_button($protocol, $data, $e, $array_buttons){

    if ($e['user_id'] == UserId($protocol, $data)){
        //Только организатор
        array_unshift($array_buttons, array('name' => 'Удалить'));
    }

    $telegram_url = get_telegram_url($protocol,$data, $e);

    if ($telegram_url != ''){
        array_unshift($array_buttons, array('name' => 'Телеграм чат', 'url' => $telegram_url , 'hide' => false ));
    }

    array_unshift($array_buttons, array('name' => 'На карте'    , 'url' => get_map_url($protocol,$data, $e), 'hide' => false ));

    //Возможно пользователь уже записан
    if ( check_schedule($protocol, $data, $e['id']) == null){
        array_unshift($array_buttons, array('name' => 'Записаться'));
    }else{
        array_unshift($array_buttons, array('name' => 'Отписаться'));
    }
    return  $array_buttons;
}

function type_sport_button($array_buttons ){
    array_unshift($array_buttons, array('name' => '🏓 ‍Настольный теннис'));
    array_unshift($array_buttons, array('name' => '🧘‍ Йога'));
    array_unshift($array_buttons, array('name' => '♟ Шахматы'));
    array_unshift($array_buttons, array('name' => '🚲 Велоспорт'));
    array_unshift($array_buttons, array('name' => '🏃 Бег'));
    array_unshift($array_buttons, array('name' => '💃 Танцы'));
    array_unshift($array_buttons, array('name' => '🏐 Волейбол'));
    array_unshift($array_buttons, array('name' => '🏀 Баскетбол'));
    array_unshift($array_buttons, array('name' => '⚽ Футбол'));
    return  $array_buttons;
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