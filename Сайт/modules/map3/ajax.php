<?

if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_updateMap') {
    
    $objSportType = array();
    $q = ' WHERE a.id>0';
    $qr = ' WHERE a.id>0';

     // поиск по районам
     if (!empty($_POST['districts'])){
            
       $q .= " AND (a.district_id IN (".implode(',', $_POST['districts']).")";

       // объединяем с поиском по административному округу
       if (!empty($_POST['adm_area'])){
          $q .= " OR a.id_adm_area IN (".implode(',', $_POST['adm_area']).")";
       }
       
       $q .= ")";
       
     } 
     
     else {
        if (!empty($_POST['adm_area'])) {
            // поиск по административному округу без поиска по районам
  	        $q .= " AND a.id_adm_area IN (".implode(',', $_POST['adm_area']).")";
        }
     }
        
     // поиск по ведомственной организации
     if (!empty($_POST['organization'])){
       $q .= " AND a.org_id IN (".implode(',', $_POST['organization']).")";
     }
     
     // поиск по доступности
     if (!empty($_POST['availability'])){
        $q .= " AND a.availability_id IN (".implode(',', $_POST['availability']).")";
     }
     
     // поиск по виду спорта
     if (!empty($_POST['type'])) {
        $qr .= " AND a.sport_id IN (".implode(',', $_POST['type']).") ";
     }
     
     // поиск по категории спорта
     if (!empty($_POST['category'])) {
        $qr .= " AND a.cat_id IN (".implode(',', $_POST['category']).") ";
     }

     // получаем из БД данные по спортивным объектам с учетом заданных фильтров
     $objects = db_query("SELECT a.object_id,
     a.object,
     a.address,
     a.lng,
     a.lat,
     a.district_id,
     a.id_adm_area,
     a.org_id,
     a.availability_id,
     mln_districts.district,
     mln_adm_area.adm_area,
     mos_organization.org_name,
     mos_availability.availability,
     mos_availability.km 
     FROM mos_objects AS a 
     LEFT JOIN mln_adm_area ON a.id_adm_area = mln_adm_area.id
     LEFT JOIN mln_districts ON a.district_id = mln_districts.id 
     LEFT JOIN mos_organization ON a.org_id = mos_organization.org_id 
     LEFT JOIN mos_availability ON a.availability_id = mos_availability.id ".$q);
     
     // виды спорта и категории
     $objects_sportzones_type_sport_obj = array();
     
     $ts = db_query("SELECT a.id,
     a.object_id,
     a.sport_id,
     a.cat_id,
     mln_type_sport.type,
     mln_type_sport.smile_html,
     mln_sport_category.name 
     FROM mos_objects_sportzone AS a 
     LEFT JOIN mln_type_sport ON a.sport_id = mln_type_sport.id 
     LEFT JOIN mln_sport_category ON a.cat_id = mln_sport_category.id ".$qr);
 
     if ($ts!=false) {
       foreach($ts as $b) {
          if (!empty($b['object_id'])) {
            $objSportType[ $b['object_id'] ][ $b['sport_id'] ] = array('sport' => $b['type'], 'icon' => $b['smile_html']);
            $objects_sportzones_type_sport_obj[] = $b['object_id'];
          }
       }
     }
    
     // формируем новый массив спортивных объектов с учетом найденных площадок по заданным фильтрам
     if (!empty($_POST['category']) || !empty($_POST['type'])){
        $aa = array();
		foreach ($objects as $b){
	    	if (in_array($b['object_id'], $objects_sportzones_type_sport_obj)){
	    		$aa[] = $b;
	    	}
	    }
        
	    $objects = $aa;
     }
     
     ob_start();
     require $_SERVER['DOCUMENT_ROOT'].'/modules/map3/includes/map.inc.php';
     $map = ob_get_clean();
     exit($map);
}