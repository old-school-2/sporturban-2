<?php

$obj_id = intval($_GET['com2']);

if (empty($obj_id))
  exit(header('Location: /'));
  
$obj = db_query("SELECT * FROM mos_objects WHERE object_id=".$obj_id." LIMIT 1");

if ($obj == false)
  exit(header('Location: /'));
  
 // достаём все виды спорта, привязанные к объекту
    $sportsList = null;
    $objectSports = array();  
    
    $sportzoneArea = 0;
    $allTargetPeoples = 0;
    
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
        
        foreach($sp as $b) {
            $objectSports[] = $b['sport_id'];
            
            $sportzoneArea += $b['sportzone_area'];
            $allTargetPeoples += $b['target_peoples'];
            
        }
        
        ob_start();
        require_once $_SERVER['DOCUMENT_ROOT'].'/modules/user-objects/components/edit/includes/sportsListTab.inc.php';
        $sportsList = ob_get_clean();
        
        if (substr($sportzoneArea,-2) == '00')
          $sportzoneArea = substr($sportzoneArea,-3);
        
    }
 // -----------------------------------------------------------
  
 // доступность
 $avlArr = array();
 $avlArr2 = array();

 $avlList = db_query("SELECT id,
 availability,
 km 
 FROM mos_availability");
 
 foreach($avlList as $b) {
    
    if (substr($b['km'],-1) == '0')
      $b['km'] = substr($b['km'],0,-2);
      
    $avlArr[$b['id']] = $b['availability'].' ('.$b['km'].' км.)';
    $avlArr2[$b['id']] = $b['km'].' км.';
 }
 // --------------------------------------------------------------
 
 // ведомственные организации
 $orgList = db_query("SELECT org_id,
 org_name 
 FROM mos_organization");
 // --------------------------------------------------------------
 
 // список видов спорта
 $sports_name = null;
 $sports_guid = null;
 
 $spk = db_query("SELECT id, type, smile_html FROM mln_type_sport ORDER BY type ASC");
 
 if ($spk != false) {
    foreach ($spk as $spz) {
       if(in_array($spz['id'],$objectSports)) {
          $sports_name .= '"'.$spz['type'].'",';
          $sports_guid .= '05be8607-117b-4028-8fb1-76380b9fbdb8='.$spz['id'].'&';
       }
    }
    
    $sports_name = substr($sports_name,0,-1);
    $sports_guid = substr($sports_guid,0,-1);
 }
 
 
 // --------------------------------------------------------------