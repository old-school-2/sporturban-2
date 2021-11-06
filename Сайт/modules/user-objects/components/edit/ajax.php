<?php defined('DOMAIN') or exit(header('Location: /'));

// редактирование объекта в базе
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_editObject') {
    
    $obj_id = intval($_POST['obj_id']);
    $object = clearData($_POST['object']);
    $address = clearData($_POST['address']);
    $availability_id = intval($_POST['availability_id']);
    $org_id = intval($_POST['org_id']);
    $objSports = array();
    
    $upd = db_query("UPDATE mos_objects 
    SET object='".$object."',
    address='".$address."',
    org_id='".$org_id."',
    availability_id='".$availability_id."' 
    WHERE object_id=".$obj_id." 
    LIMIT 1","u");
    
    
    // виды спорта
    if (empty($_POST['sports'])) {
        // значит нужно удалить все привязанные виды спорта
        db_query("DELETE FROM mos_objects_sportzone WHERE object_id='".$obj_id."'","d");
    }
    
    if (!empty($_POST['sports'])) {
        
        // определяем максимальное значение sportzone_id 
        $max = db_query("SELECT MAX(sportzone_id) AS max_id FROM mos_objects_sportzone LIMIT 1");
        $max_id = $max[0]['max_id'];
        // --------------------------------------------------------------------------------------
        
        // категории спорта
        $sportsArr = array();
        
        $c = db_query("SELECT id, type, popular, cat_id, category FROM mln_type_sport");
        
        foreach($c as $b) {
            $sportsArr[ $b['id'] ] = array(
              'name' => $b['type'], 
              'cat_id' => $b['cat_id'], 
              'category' => $b['category'],
              'popular' => $b['popular']);
        }
        // --------------------------------------------------------------------------------------
        
        // доп информация об объекте
        $ob = db_query("SELECT a.*, 
        mos_availability.availability,
        mos_availability.km,
        mos_organization.org_name,
        mln_districts.district_name,
        mln_adm_area.adm_area 
        FROM mos_objects AS a 
        LEFT JOIN mos_availability ON a.availability_id = mos_availability.id 
        LEFT JOIN mos_organization ON a.org_id = mos_organization.org_id 
        LEFT JOIN mln_districts ON a.district_id = mln_districts.id 
        LEFT JOIN mln_adm_area ON a.id_adm_area = mln_adm_area.id 
        WHERE a.object_id=".$obj_id." LIMIT 1");
        // --------------------------------------------------------------------------------------
        
        $sp = db_query("SELECT * 
        FROM mos_objects_sportzone 
        WHERE object_id='".$obj_id."'");
        
        // если ещё нет привязанных видов спорта к объекту
        if ($sp == false) {
            
            foreach($_POST['sports'] as $k=>$v) {
                
                $max_id++;
                $target_peoples = target_peoples($sportsArr[$v]['popular'],$ob[0]['peoples']);
                
                db_query("INSERT INTO mos_objects_sportzone (
                user_id,
                object_id,
                org_id,
                org_name,
                sportzone_id,
                availability_id,
                availability,
                sport_name,
                sport_id,
                object_name,
                category,
                cat_id,
                lng,
                lat,
                km,
                district_id,
                district_name,
                id_adm_area,
                adm_area,
                peoples,
                target_peoples
                ) VALUES (
                '".intval($_SESSION['user_id'])."',
                '".$obj_id."',
                '".$org_id."',
                '".$ob[0]['org_name']."',
                '".$max_id."',
                '".$availability_id."',
                '".$ob[0]['availability']."',
                '".$sportsArr[$v]['name']."',
                '".$v."',
                '".$object."',
                '".$sportsArr[$v]['category']."',
                '".$sportsArr[$v]['cat_id']."',
                '".$ob[0]['lng']."',
                '".$ob[0]['lat']."',
                '".$ob[0]['km']."',
                '".$ob[0]['district_id']."',
                '".$ob[0]['district_name']."',
                '".$ob[0]['id_adm_area']."',
                '".$ob[0]['adm_area']."',
                '".$ob[0]['peoples']."',
                '".$target_peoples."'
                )","i");
            }
            
        }
        
        else {
            // сначала удаляем те, которых нет в списке
            foreach($sp as $b) {
              
              $objSports[] = $b['sport_id'];  
              
              if (!in_array($b['sport_id'],$_POST['sports'])) {
                 db_query("DELETE FROM mos_objects_sportzone 
                 WHERE object_id='".$obj_id."' 
                 AND sport_id='".$b['sport_id']."' 
                 LIMIT 1","u");
              }
            }
            // ---------------------------------------
            
            // добавляем те, которых нет в базе
            foreach($_POST['sports'] as $k=>$v) {
                
                if (!in_array($v,$objSports)) {
                    
                $max_id++;
                $target_peoples = target_peoples($sportsArr[$v]['popular'],$ob[0]['peoples']);
                
                db_query("INSERT INTO mos_objects_sportzone (
                user_id,
                object_id,
                org_id,
                org_name,
                sportzone_id,
                availability_id,
                availability,
                sport_name,
                sport_id,
                object_name,
                category,
                cat_id,
                lng,
                lat,
                km,
                district_id,
                district_name,
                id_adm_area,
                adm_area,
                peoples,
                target_peoples
                ) VALUES (
                '".intval($_SESSION['user_id'])."',
                '".$obj_id."',
                '".$org_id."',
                '".$ob[0]['org_name']."',
                '".$max_id."',
                '".$availability_id."',
                '".$ob[0]['availability']."',
                '".$sportsArr[$v]['name']."',
                '".$v."',
                '".$object."',
                '".$sportsArr[$v]['category']."',
                '".$sportsArr[$v]['cat_id']."',
                '".$ob[0]['lng']."',
                '".$ob[0]['lat']."',
                '".$ob[0]['km']."',
                '".$ob[0]['district_id']."',
                '".$ob[0]['district_name']."',
                '".$ob[0]['id_adm_area']."',
                '".$ob[0]['adm_area']."',
                '".$ob[0]['peoples']."',
                '".$target_peoples."'
                )","i");
                    
                }
                
            }
            // --------------------------------------
        }
        
    }
    
    
    // достаём все виды спорта, привязанные к объекту
    $sportsList = null;    
    
    $sp = db_query("SELECT a.*,
    mln_type_sport.type,
    mln_type_sport.popular,
    mln_type_sport.smile_html,
    mos_users.username 
    FROM mos_objects_sportzone AS a 
    LEFT JOIN mln_type_sport ON a.sport_id = mln_type_sport.id 
    LEFT JOIN mos_users ON a.user_id = mos_users.id 
    WHERE a.object_id='".$obj_id."' ORDER BY mln_type_sport.type ASC"); 
    
    if ($sp != false) {
        
        ob_start();
        require_once $_SERVER['DOCUMENT_ROOT'].'/modules/user-objects/components/edit/includes/sportsListTab.inc.php';
        $sportsList = ob_get_clean();
        
    }
    // -----------------------------------------------------------
        
    $arr = array(
      'object' => $object,
      'sportzone' => $sportsList
    );
        
    $r = callbackFunction($arr);
    exit($r);
}