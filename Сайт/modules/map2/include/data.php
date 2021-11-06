<?
    ini_set('memory_limit', '2048M');

	// формируем запросы к БД при отправленной форме поиска по карте
    $qr = '';
    if ($_POST['form_id'] == 'form_updateMap'){

        $q = ' WHERE mos_objects.id>0';

		// поиск по районам
        if (!empty($_POST['districts'])){
            $q .= " AND (mos_objects.district_id IN (".implode(',', $_POST['districts']).")";
            $qr .= " WHERE mos_realty.district_id IN (".implode(',', $_POST['districts']).")";

	        // объединяем с поиском по административному округу
	        if (!empty($_POST['adm_area'])){
	            $q .= " OR mos_objects.id_adm_area IN (".implode(',', $_POST['adm_area']).")";
	        }
            $q .= ")";
        } else {        	// поиск по административному округу без поиска по районам        	$q .= " AND mos_objects.id_adm_area IN (".implode(',', $_POST['adm_area']).")";
        }
        // поиск по ведомственной организации
        if (!empty($_POST['organization'])){
            $q .= " AND mos_objects.org_id IN (".implode(',', $_POST['organization']).")";
        }
        // поиск по доступности
        if (!empty($_POST['availability'])){
            $q .= " AND mos_objects.availability_id IN (".implode(',', $_POST['availability']).")";
        }

        // получаем из БД данные по спортивным объектам с учетом заданных фильтров
        $a = db_query("SELECT
        mos_objects.*
        FROM mos_objects
        ".$q."
        ORDER BY mos_objects.id ASC;");

    } else {

		// получаем данные из БД по умолчанию (без фильтров)
        $a = db_query("SELECT
        *
        FROM mos_objects
        WHERE id_adm_area='9'
        AND district_id='125'
        ORDER BY id ASC;");

        $qr = " WHERE mos_realty.district_id='125'";
    }


    // получаем спортивные площадки с учетом заданных фильтров
	$objects_sportzones_type_sport_obj = array();
	// по виду спорта
    if (!empty($_POST['type'])){
    	$objects_sportzones = db_query("SELECT * FROM mos_objects_sportzone WHERE sport_id IN (".implode(',', $_POST['type']).");");
    	foreach ($objects_sportzones as $b){
	    	$objects_sportzones_type_sport_obj[] = $b['object_id'];
	    }
    }
    // по категории спорта
    if (!empty($_POST['category'])){
    	$objects_sportzones = db_query("SELECT * FROM mos_objects_sportzone WHERE cat_id IN (".implode(',', $_POST['category']).");");
    	foreach ($objects_sportzones as $b){
	    	$objects_sportzones_type_sport_obj[] = $b['object_id'];
	    }
    }
    // формируем новый массив спортивных объектов с учетом найденных площадок по заданным фильтрам
    if (!empty($_POST['category']) or !empty($_POST['type'])){
        $aa = array();
		foreach ($a as $b){
	    	if (in_array($b['object_id'], $objects_sportzones_type_sport_obj)){
	    		$aa[] = $b;
	    	}
	    }
	    $a = $aa;
    }




    // если открывается карта с точкой на заданных координатах
    $changeZoom = false;
    if (!empty($_GET['lat']) and !empty($_GET['lng'])){
        $centerMap = $_GET['lat'].', '.$_GET['lng'];
        $zoom = '16';
        $changeZoom = true;
    }

	// получаем районы из БД
    $district = db_query("SELECT * FROM mln_districts ORDER BY district ASC;");
    // получаем категории спорта из БД
    $categories = db_query("SELECT * FROM mln_sport_category ORDER BY id ASC;");

    // получаем данные по спортивным площадкам
    $objects_sportzones = db_query("SELECT
    mos_objects_sportzone.*,
    mln_sport_category.color
    FROM mos_objects_sportzone
    LEFT JOIN mln_sport_category ON mln_sport_category.id=mos_objects_sportzone.cat_id
    ORDER BY mos_objects_sportzone.id ASC;");
    $objects_sportzones_id = array();
    foreach ($objects_sportzones as $b){
    	$objects_sportzones_id[$b['object_id']][] = $b;
    }

    // формируем массив данных по видам спорта
    $type_sport = db_query("SELECT * FROM mln_type_sport;");
    $type_sport_names = array();
    $type_sport_id = array();
    foreach ($type_sport as $ts){
    	$type_sport_names[$ts['type']] = $ts['id'];
    	$type_sport_id[$ts['id']] = $ts;
    }
    // формируем массив данных по доступности
    $availability = db_query("SELECT * FROM mos_availability ORDER BY id ASC;");
    $availability_id = array();
    foreach ($availability as $av){
    	$availability_id[$availability['id']] = $av['availability'];
    }
    // формируем массив данных по ведомственным организациям
    $organization = db_query("SELECT * FROM mos_organization ORDER BY org_name ASC;");
    $organization_id = array();
    foreach ($organization as $org){
    	$organization_id[$org['org_id']] = $org;
    }
    // получаем данные по домам Москвы и плотности населения
    $mos_realty = db_query("SELECT id, lng, lat, area_residential, address FROM mos_realty ".$qr.";");

    // получаем данные по административным округам
    $mln_adm_area = db_query("SELECT id, adm_area FROM mln_adm_area ORDER BY id ASC;");

?>