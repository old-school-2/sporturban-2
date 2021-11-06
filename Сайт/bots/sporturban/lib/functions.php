<?php

function main($protocol, $data)
{
    $character = $data['character']['id'];

    $log = get_log($protocol, $data);

    $array_buttons = init_button();
    $state   = get_state($log);
    $text = get_text( $protocol,  $data);

//    $text = cleardata($text);
    //Заранее известные команды
    //Событие
    $content = s_event($protocol, $data, $text, $array_buttons);
    if ($content != null){
        return $content;
    }

    //Запись
    $content = s_subscribe($protocol, $data, $text, $array_buttons, $log);
    if ($content != null){
        return $content;
    }

    //Запись
    $content = s_unsubscribe($protocol, $data, $text, $array_buttons, $log);
    if ($content != null){
        return $content;
    }

    //Глобальные кнопки
    $type = comands_bd($text);
    $content = s_global($protocol, $data, $type, $array_buttons, $character, $state, $log);
    if ($content != null){
        return $content;
    }

    //Создать вид спорта
    $content = s_create_sport($protocol, $data, $type, $array_buttons, $character, $state, $text);
    if ($content != null){
        return $content;
    }

    //Создать место
    $content = s_create_place($protocol, $data, $type, $array_buttons, $character, $state, $text);
    if ($content != null){
        return $content;
    }

    //Создать дату
    $content = s_create_date($protocol, $data, $type, $array_buttons, $character, $state, $text, $log);
    if ($content != null){
        return $content;
    }

    //Создать время
    $content = s_create_time($protocol, $data, $type, $array_buttons, $character, $state, $text, $log);
    if ($content != null){
        return $content;
    }

    //Удалить мероприятие
    $content = s_delete_event($protocol, $data, $type, $array_buttons, $character, $state, $text, $log);
    if ($content != null){
        return $content;
    }
    //Задать адрес
    $content = s_set_adress($protocol, $data, $type, $array_buttons, $character, $state, $text, $log);
    if ($content != null){
        return $content;
    }

    //Поиск
    $content = s_search_sport($protocol, $data, $type, $array_buttons, $character, $state, $text, $log);
    if ($content != null){
        return $content;
    }

    //Вид спорта
    $content = s_type_sport($protocol, $data, $type, $array_buttons, $character, $state, $text, $log);
    if ($content != null){
        return $content;
    }

    //Когда нечего отвечать
        $state = '';
        $array_buttons = type_sport_button($array_buttons);
        $buttons = get_buttons($protocol,  $array_buttons);
        $content = get_content_text($protocol,   answers_bd('помощь', $character) , $buttons, $data);// answers_bd('помощь', $character)
        set_field_log($protocol, $data, 'state' , $state);
        return  $content;
}


function s_subscribe($protocol, $data, $text, $array_buttons, $log){
    if  (preg_match('/записаться/', $text)){
        $id = $log['event_id'];
        $e = get_event( $id);

        $buttons = get_buttons($protocol,  $array_buttons);

        subscribe($protocol, $data, $id);
        $content =  get_content_text($protocol,  'Вы записались на мероприятие ' . $e['sport']  , $buttons, $data);
        $state = '';
        set_field_log($protocol, $data, 'state' , $state );
    }
    return $content;
}


function s_unsubscribe($protocol, $data, $text, $array_buttons, $log){
    if  (preg_match('/отписаться/', $text)){
        $id = $log['event_id'];
        $e = get_event( $id);

        $buttons = get_buttons($protocol,  $array_buttons);

        $qr = unsubscribe($protocol, $data, $id);
        $content =  get_content_text($protocol,  'Вы отписались от мероприятия ' . $e['sport'] , $buttons, $data);
        $state = '';
        set_field_log($protocol, $data, 'state' , $state );
    }
    return $content;
}

function s_event($protocol, $data, $text, $array_buttons){
    if  (preg_match('/событие/', $text)){
        $id = clearData($text,'phone');

        $e = get_event( $id);
        set_field_log($protocol, $data, 'event_id' , $id );

        $array_buttons = event_button($protocol, $data, $e, $array_buttons);

        $buttons = get_buttons($protocol,  $array_buttons);
        $content =  get_event_id( $protocol, $data,  $buttons, $e);
        $state = '';
        set_field_log($protocol, $data, 'state' , $state );

    }
    return $content;
}

function s_global($protocol, $data, $type, $array_buttons, $character, $state, $log){

    switch ($type) {
        case 'создать' :
            $state   = 'создать_вид_спорта';
            $array_buttons = type_sport_button($array_buttons);
            $buttons = get_buttons($protocol,  $array_buttons);
            $content = get_content_text($protocol,  answers_bd($type, $character) , $buttons, $data);
            break;

        case 'удалить' :
            $state   = 'удалить_мероприятие';
            array_unshift($array_buttons, array('name' => 'Да'));
            array_unshift($array_buttons, array('name' => 'Нет'));
            $buttons = get_buttons($protocol,  $array_buttons);
            $content = get_content_text($protocol,  'Подтвердите удаление мероприятия: ' , $buttons, $data);
            break;

        case 'главная' :
            $state   = $type;
            $array_buttons = type_sport_button($array_buttons);
            array_unshift($array_buttons, array('name' => '🔥 Любой'));
            $buttons = get_buttons($protocol,  $array_buttons);
            $content = get_content_text($protocol,  answers_bd($type, $character) , $buttons, $data);
            break;

        case 'расписание' :
            $state   = $type;

            $events = get_events_shedule($protocol, $data);
            $array_buttons = next_button($protocol, $data, $array_buttons, $events );
            $buttons = get_buttons($protocol,  $array_buttons);

            if ( $events  == null ){
                $content = get_content_text($protocol,  'Попробуйте другой вид спорта, или скажите "Создать", чтобы организовать своё мероприятие', $buttons, $data);
            }else{
                $content = get_content_list($protocol, 'Расписание для Вас', $buttons, $data,  $events, $log);
            }

            break;
        case 'поиск' :
            $state   = $type;
            $array_buttons = type_sport_button($array_buttons);
            array_unshift($array_buttons, array('name' => '🔥 Любой'));

            $buttons = get_buttons($protocol, $array_buttons);

            $content = get_content_text($protocol,  answers_bd($type, $character) , $buttons, $data);
            break;

        case 'организатор':
            $state   = $type;
            $events = get_events_array_autor($protocol, $data, $log['offset'] );
            $array_buttons = org_button($array_buttons);
            $array_buttons = next_button($protocol, $data, $array_buttons, $events );

            $buttons = get_buttons($protocol,  $array_buttons);
            if ( $events  == null ){
                $content = get_content_text($protocol,  'У Вас нет пока мероприятий', $buttons, $data);
            }else{
                $content = get_content_list($protocol, 'Ваши мероприятия', $buttons, $data,  $events , $log);
            }
            break;

        case 'настройки':
            $state   = $type;
            $settings = get_settings($protocol, $data);//. $settings['adress'] . $settings['telegram']
            array_unshift($array_buttons, array('name' => '🏠 Адрес ' . $settings['adress'], 'text' => 'Адрес'));
            array_unshift($array_buttons, array('name' => '🔗 Телеграм ' . $settings['telegram'], 'url' => "https://t.me/sporturbanBot?start=source-yandex-" . UserId($protocol, $data) , 'hide' => false));

            $buttons = get_buttons($protocol,  $array_buttons);
            $content = get_content_text($protocol,  answers_bd($type, $character) , $buttons, $data);
            break;

        case 'адрес':
            $state   = $type;
            $buttons = get_buttons($protocol,  $array_buttons);
            $content = get_content_text($protocol,  answers_bd($type, $character) , $buttons, $data);
            break;

        case 'помощь':
            $array_buttons = type_sport_button($array_buttons);
            $buttons = get_buttons($protocol,  $array_buttons);
            $content = get_content_text($protocol,   answers_bd($type, $character) , $buttons, $data);
            break;

        case 'ещё':
            $events = get_events($protocol, $data, $log['search_sql'], $log['offset']);
            $array_buttons = next_button($protocol, $data, $array_buttons, $events);
            $buttons = get_buttons($protocol,  $array_buttons);
            if (count($events) != 0){
//                $content = get_content_text($protocol,  'Ещё мероприятия:', null, $data);
               $content = get_content_list($protocol, 'Ещё мероприятия:', $buttons, $data,  $events, $log);
            }
            break;

        case 'выход':
            $content = get_content_text($protocol,  answers_bd($type, $character) , null, $data);
            break;

        default:
            break;
    }
    set_field_log($protocol, $data, 'state' , $state );

    return $content;
}

function s_create_sport($protocol, $data, $type, $array_buttons, $character, $state, $text){

    if ($state == 'создать_вид_спорта') {
            $type_sport =  check_sport($text);

            if  ($type_sport != '') {
                //Вид спорта определён, запишем в базу
                $state = 'создать_место';
                set_field_log($protocol, $data, 'org_sport' , $type_sport);

                $buttons = get_buttons($protocol,  $array_buttons);
                $content = get_content_text($protocol,  'Я люблю ' . $type_sport . "! Скажите Адрес или вставьте координаты" , $buttons, $data);

                set_field_log($protocol, $data, 'state' , $state );
            }else{
                //Вид спорта не определен
                $array_buttons = type_sport_button($array_buttons);
                $buttons = get_buttons($protocol,  $array_buttons);
                $content = get_content_text($protocol,  'Я не знаю такой вид спорта, поробуйте сказать иначе' , $buttons, $data);
            }
}
    return $content;
}

function s_create_place($protocol, $data, $type, $array_buttons, $character, $state, $text){

    if ($state == 'создать_место') {

        list( $geo, $place , $locality ) = check_adress($text);

            if ( $place != '') {
                //Место определено
                set_field_log($protocol, $data, 'org_place' , $place);
                set_field_log($protocol, $data, 'org_geo'   , $geo);
                set_field_log($protocol, $data, 'locality'  , $locality );

                array_unshift($array_buttons, array('name' => 'Воскресенье'));
                array_unshift($array_buttons, array('name' => 'Суббота'));
                array_unshift($array_buttons, array('name' => 'Пятница'));
                array_unshift($array_buttons, array('name' => 'Завтра'));
                array_unshift($array_buttons, array('name' => 'Сегодня'));
                $buttons = get_buttons($protocol,  $array_buttons);
                $content = get_content_text($protocol,  'Отличное место,  ' . $place . "! Укажите дату мероприятия", $buttons, $data);

                //Если место корректно - следующий шаг
                $state = 'создать_дата';
                set_field_log($protocol, $data, 'state' , $state );

            }else{
                //Вид спорта не определен
                $array_buttons = type_sport_button($array_buttons);
                $buttons = get_buttons($protocol,  $array_buttons);
                $content = get_content_text($protocol,  'Я не знаю такого места, поробуйте сказать иначе' , $buttons, $data);
            }
    }
    return $content;
}


function s_set_adress($protocol, $data, $type, $array_buttons, $character, $state, $text){

    if ($state == 'адрес') {
        $adress = $text;

        list( $geo, $adress_name, $locality  ) = check_adress($adress);
        if ( $adress_name != '') {

            array_unshift($array_buttons, array('name' => '🏠 Адрес ' . $adress_name, 'text' => 'Адрес'));
            //Место определено
            $buttons = get_buttons($protocol,  $array_buttons);

            $content = get_content_text($protocol,  "Адрес, сохранен", $buttons, $data);
            set_settings($protocol, $data, $geo, $adress_name);

            $state = 'настройки';
            set_field_log($protocol, $data, 'locality' , $locality);
            set_field_log($protocol, $data, 'state' , $state );

        }else{

            $buttons = get_buttons($protocol,  $array_buttons);
            $content = get_content_text($protocol,  'Я не знаю такого места, попробуйте сказать иначе' , $buttons, $data);
        }
    }
    return $content;
}

function s_create_date($protocol, $data, $type, $array_buttons, $character, $state, $text){

    if ($state == 'создать_дата') {

        $date = check_date($text);
        if ( $date == null ){
            $date = get_date_json($protocol, $data);
        }

        if ( $date != null ) {
            //Дата определены
            set_field_log($protocol, $data, 'org_date' , $date);

            array_unshift($array_buttons, array('name' => '18:00'));
            array_unshift($array_buttons, array('name' => '16:00'));
            array_unshift($array_buttons, array('name' => '14:00'));
            array_unshift($array_buttons, array('name' => '12:00'));
            array_unshift($array_buttons, array('name' => '10:00'));

            //Задать время
            $buttons = get_buttons($protocol,  $array_buttons);
            $content = get_content_text($protocol,  'Отлично!  ' . $date . "! Укажите время мероприятия", $buttons, $data);

            //Если место корректно - следующий шаг
            $state = 'создать_время';
            set_field_log($protocol, $data, 'state' , $state );

        }else{
            //Вид спорта не определен
            $buttons = get_buttons($protocol,  $array_buttons);
            $content = get_content_text($protocol,  'Я не знаю такой даты, скажите иначе', $buttons, $data);
        }
    }
    return $content;
}

function s_create_time($protocol, $data, $type, $array_buttons, $character, $state, $text, $log){
    if ($state == 'создать_время') {
            $time = $text;

            if ( $time != '') {
                //Время определено
                set_field_log($protocol, $data, 'org_time' , $time);
                $event_id = add_event($protocol, $data, $log['org_place'],  $log['org_sport'],  $log['org_date'], $time, $log['org_geo'],  get_img_by_sport($log['org_sport']) , $log['locality']);

                $array_buttons = org_button($array_buttons);
                $buttons = get_buttons($protocol,  $array_buttons);
                //Организатор автоматически подписывается на мероприятие
                subscribe($protocol, $data, $event_id );

                $content = get_content_text($protocol,  'Мероприятие сохранено ' . $log['org_sport'] . ' площадка '. $log['org_place'] . ' дата ' . $log['org_date'] . ' время ' . $time . "!" , $buttons, $data);
                $state = 'организатор';
                set_field_log($protocol, $data, 'state' , $state );

            }else{
                //Дата не определена
                $array_buttons = type_sport_button($array_buttons);
                $buttons = get_buttons($protocol,  $array_buttons);
                $content = get_content_text($protocol,  'Я не знаю такую дату, поробуйте сказать иначе', $buttons, $data);
            }
    }
    return $content;
}


function s_delete_event($protocol, $data, $type, $array_buttons, $character, $state, $text, $log){
    if ($state == 'удалить_мероприятие') {
        if ( $text == 'да') {
            $buttons = get_buttons($protocol,  $array_buttons);
            $content = get_content_text($protocol,  'Мероприятие удалено', $buttons, $data);
            event_delete($log['event_id']);
            $state = 'организатор';
            set_field_log($protocol, $data, 'state' , $state );

        }else{
            $state = 'организатор';
            set_field_log($protocol, $data, 'state' , $state );

            $buttons = get_buttons($protocol,  $array_buttons);
            $content = get_content_text($protocol,  'Удаление отменено', $buttons, $data);
        }
    }
    return $content;
}

function s_search_sport($protocol, $data, $type, $array_buttons, $character, $state, $text, $log){
    if ($state == 'поиск') {
        $content = s_type_sport($protocol, $data, $type, $array_buttons, $character, $state, $text, $log);
    }
    return $content;
}

function s_type_sport($protocol, $data, $type, $array_buttons, $character, $state, $text, $log){
    if  ($text == 'любой' ){
        $type_sport = 'любой';
    }else{
        $type_sport = check_sport($text);
    }

    if ($type_sport != '' ){

        $events = get_events_array($protocol, $data, $type_sport, $log['offset'] );
        $array_buttons = next_button($protocol, $data, $array_buttons, $events );

        if ( $events  == null ){
            array_unshift($array_buttons, array('name' => '😎 Создать'));
            $buttons = get_buttons($protocol,  $array_buttons);
            $content = get_content_text($protocol,  'Ничего не нашлось, укажите другую категорию, или создайте своё мероприятие', $buttons, $data);
        }else{
            $buttons = get_buttons($protocol,  $array_buttons);
            $content = get_content_list($protocol, 'Мероприятия по запросу ' . $type_sport, $buttons, $data,  $events, $log);
        }

    }
    return $content;
}