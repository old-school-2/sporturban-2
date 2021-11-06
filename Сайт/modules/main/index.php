<?

 // подключаем модуль получения данных для интерактивной карты
 include($_SERVER['DOCUMENT_ROOT'].'/modules/map4/include/data.php');
 
 
 // объекты, добавленные вручную
 $objListTab = null;
 
 $objList = db_query("SELECT a.id, 
 a.object_id,
 a.object,
 a.address,
 a.lng,
 a.lat,
 mos_organization.org_name,
 mos_availability.availability,
 mln_districts.district,
 mos_users.username 
 FROM mos_objects AS a 
 LEFT JOIN mos_organization ON a.org_id = mos_organization.org_id 
 LEFT JOIN mos_availability ON a.availability_id = mos_availability.id 
 LEFT JOIN mln_districts ON a.district_id = mln_districts.id 
 LEFT JOIN mos_users ON a.user_id = mos_users.id 
 WHERE a.user_id!=0 
 ORDER BY a.id");
 
 if ($objList != false) {
    ob_start();
    require_once $_SERVER['DOCUMENT_ROOT'].'/modules/user-objects/tmp.inc.php';
    $objListTab = ob_get_clean();
 }
 
 
?>