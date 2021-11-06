<?php defined('DOMAIN') or exit(header('Location: /'));

// добавление объекта в базу
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_addObject') {
    
    $api_key = 'a9955659-e011-4339-a899-4ba5c5b5e2e0';
    $radius = clearData($_POST['radius'],'area');
    $lng = clearData($_POST['lng'],'area');
    $lat = clearData($_POST['lat'],'area');
    
    $avl_id = intval($_POST['availability_id']);
    $object = clearData($_POST['object']);
    
    // определяем адрес
    $res = file_get_contents('https://geocode-maps.yandex.ru/1.x/?apikey='.$api_key.'&sco=longlat&geocode='.$lng.','.$lat);
    $parse = simplexml_load_string($res);
        
    $address = $parse->GeoObjectCollection->featureMember[0]->GeoObject->metaDataProperty->GeocoderMetaData->Address->formatted;
    $address = clearData($address);
    // -------------------------------------------------------------------------------------------
    
    // определяем район
    $res = file_get_contents('https://geocode-maps.yandex.ru/1.x/?apikey='.$api_key.'&sco=longlat&kind=district&geocode='.$lng.','.$lat);
    $parse = simplexml_load_string($res);
    
    $district = $parse->GeoObjectCollection->featureMember[0]->GeoObject->metaDataProperty->GeocoderMetaData->Address->Component[5]->name;
    $district = clearData($district);
    
    // определяем id районв
    $district_id = 0;
    $id_adm_area = 0;
    $district_name = null;
    
    $d = db_query("SELECT id, id_adm_area, district_name FROM mln_districts WHERE district='".$district."' LIMIT 1");
    
    if ($d != false) {
        $district_id = $d[0]['id'];
        $id_adm_area = $d[0]['id_adm_area'];
        $district_name = $d[0]['district_name'];
    }
    // -------------------------------------------------------------------------------------------
    
    // вычисляем количество населения в заданной области доступности
    $peoples = count_peoples($avl_id,$lng,$lat);
    // -------------------------------------------------------------------------------------------
    
    // определяем максимальное значение object_id
    $max = db_query("SELECT MAX(object_id) AS max_id FROM mos_objects LIMIT 1");
    $max_id = $max[0]['max_id'] + 1;
    // -------------------------------------------------------------------------------------------
    
    // добавляем в базу
    $add = db_query("INSERT INTO mos_objects (
    		user_id,
            object_id,
            object,
            address,
            availability_id,
    		lng,
    		lat,
            district2,
    		district_id,
            id_adm_area,
            peoples
    	)
    	VALUES
    	(
   		  '".intval($_SESSION['user_id'])."',
          '".$max_id."',
          '".$object."',
          '".$address."',
          '".$avl_id."',
   		  '".$lng."',
   		  '".$lat."',
   		  '".$district_name."',
          '".$district_id."',
          '".$id_adm_area."',
          '".$peoples."'
    	)","i");
        
     if (intval($add) > 0) {
        // переадресуем пользователя на стр. объекта
        $r = json_encode( array( 0 => 'redirect', 1 => DOMAIN.'/user-objects/edit/'.$max_id ) );
        exit($r);
     }
}
// ---------------------------------------------------------------------------

// удаление объекта
if (isset($_POST['form_id']) && preg_match('/form_jsDelObject/',$_POST['form_id'])) {
    
    $object_id = intval($_POST['object_id']);
    
    $del = db_query("DELETE FROM mos_objects 
    WHERE object_id=".$object_id." 
    LIMIT 1","d");
    
    if ($del == true) {
        
        // удаляем спортзоны (если есть)
        $del2 = db_query("DELETE FROM mos_objects_sportzone 
        WHERE object_id=".$object_id." ","d");
        
        exit('ok');
    }
    
}
// ---------------------------------------------------------------------------

// добавление в базу выбранной зоны
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_addPolygonObject') {
    
    $polygonArea = clearData($_POST['polygonArea'],'area');
    $population = intval($_POST['population']);
    $targetPeoples = intval($_POST['targetPeoples']);
    $sportzones = intval($_POST['sportzones']);
    $sportzonesArea = clearData($_POST['sportzonesArea'],'area');
    $sat = clearData($_POST['sat'],'area');
    $coordinates = $_POST['coordinates'];
    $name = clearData($_POST['name']);
    
    $add = db_query("INSERT INTO mos_users_area (
    name,
    coordinates,
    area,
    population,
    target_peoples,
    sportzones,
    sportzones_area,
   	satisfaction,
    user_id,
    datetime,
    date
    ) VALUES (
    '".$name."',
    '".$coordinates."',
    '".$polygonArea."',
    '".$population."',
    '".$targetPeoples."',
    '".$sportzones."',
    '".$sportzonesArea."',
    '".$sat."',
    '".intval($_SESSION['user_id'])."',
    '".time()."',
    '".date('Y-m-d')."'
    )","i");
    
    if (intval($add) > 0) {
        exit('ok');
    }

}
// ---------------------------------------------------------------------------

// всплывающая форма для редактирования объекта
if (isset($_POST['form_id']) && preg_match('/form_jsEditObject/',$_POST['form_id'])) {
    
    $object_id = intval($_POST['object_id']);
    
    $obj = db_query("SELECT * FROM mos_users_circles WHERE id=".$object_id." LIMIT 1");
    
    if ($obj != false) {
        
        $avl = db_query("SELECT * FROM mos_availability");
    
        ob_start();
        require_once $_SERVER['DOCUMENT_ROOT'].'/modules/user-objects/includes/editObjectForm.inc.php';
        $mess = ob_get_clean();
    
        if (MOBILE == true) {
          $html = popup_window($mess,'90%','90%',5500);
        }
        
        else {
          $html = popup_window($mess,400,370,5500); 
        }
        
        exit($html);
        
    }
    
}
// ---------------------------------------------------------------------------

// редактирование объекта в базе
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_editThisObject') {
    
    $object_id = intval($_POST['object_id']);
    $object = clearData($_POST['object']);
    $area = clearData($_POST['area'],'area');
    $avl_id = intval($_POST['avl_id']);
    
    $upd = db_query("UPDATE mos_users_circles 
    SET object='".$object."',
    area='".$area."',
    availability_id='".$avl_id."' 
    WHERE id=".$object_id." 
    LIMIT 1
    ","u");
    
    if ($upd == true) {
        
        $av = db_query("SELECT * FROM mos_availability");
        
        foreach($av as $b) {
            if ($b['id'] == $avl_id) {
                $avl = $b['availability'];
                break;
            }
        }
        
        $arr = array(
          'object_id' => 'objectData'.$object_id,
          'object' => stripcslashes($object),
          'area' => $area.' м<sup style="font-size: 7px;">2</sup>',
          'avl' => $avl
        );
        
        $r = callbackFunction($arr);
        exit($r);
        
    }
    
}
// ---------------------------------------------------------------------------
?>