<?php

$mln_adm_area = array();
$district = array();
$organization = array();
$availability = array();
$categories = array();
$type_sport = array();

$objSportType = array();

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
 LEFT JOIN mos_availability ON a.availability_id = mos_availability.id");
 
 if ($objects != false) {
    foreach($objects as $b) {
        
        // список административных округов
        if ($b['id_adm_area']!=0) {
            $mln_adm_area[ $b['id_adm_area'] ] = $b['adm_area'];
        }
        // ----------------------------------------------------------
        
        // список районов
        if ($b['district_id']!=0) {
            $district[ $b['district_id'] ] = $b['district'];
        }
        // ----------------------------------------------------------
        
        // список ведомственных организаций
        if ($b['org_id']!=0) {
            $organization[ $b['org_id'] ] = $b['org_name'];
        }
        // ----------------------------------------------------------
        
        // доступность
        if ($b['availability_id']!=0) {
            
            if(substr($b['km'],-1) == '0') {
                $b['km'] = substr($b['km'],0,-2);
            }
            
            $availability[ $b['availability_id'] ] = $b['availability'].' ('.$b['km'].' км.)';
        }
        // ----------------------------------------------------------
    }
 }
 
 // виды спорта и категории
 $ts = db_query("SELECT a.id,
 a.object_id,
 a.sport_id,
 a.cat_id,
 mln_type_sport.type,
 mln_type_sport.smile_html,
 mln_sport_category.name 
 FROM mos_objects_sportzone AS a 
 LEFT JOIN mln_type_sport ON a.sport_id = mln_type_sport.id 
 LEFT JOIN mln_sport_category ON a.cat_id = mln_sport_category.id");
 
 if ($ts!=false) {
    foreach($ts as $b) {
        
        // список видов спорта
        if ($b['sport_id']!=0) {
            $type_sport[ $b['sport_id'] ] = $b['type'];
        }
        
        // список категорий
        if ($b['cat_id']!=0) {
            $categories[ $b['cat_id'] ] = $b['name'];
        }
        
        if (!empty($b['object_id'])) {
            $objSportType[ $b['object_id'] ][ $b['sport_id'] ] = array('sport' => $b['type'], 'icon' => $b['smile_html']);
        }
        
    }
 }