<?php defined('DOMAIN') or exit(header('Location: /'));

$num = 30; // количество объектовв на странице

// постраничная навигация по data_analiz_district
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_data_analiz_district') {
    
    $where = null;
    $sport = null;
    $district = null;
    
    if (!empty($_POST['filter'])) {
        
        $f = explode('=',$_POST['filter']);
        
        if ($f[0] == 'sport_id') {
            $where = " WHERE a.sport_id=".intval($f[1])." ";
            $sport_id = intval($f[1]);
            
            $s = db_query("SELECT type FROM mln_type_sport WHERE id=".$sport_id." LIMIT 1");
            $sport = $s[0]['type'];
        }
          
        if ($f[0] == 'district') {
            $where = " WHERE a.district_id=".intval($f[1])." ";
            $district_id = intval($f[1]);
            
            $s = db_query("SELECT district FROM mln_districts WHERE id=".$district_id." LIMIT 1");
            $district = $s[0]['district'];
        }
    }
    
    $page = intval($_POST['page']);

    if (empty($page) or $page <= 0)
      $page = 1;

    $start = $page * $num - $num;
    
    $data = db_query("SELECT SQL_CALC_FOUND_ROWS 
    a.koeff_y,
    a.koeff_count_zone,
    mln_type_sport.type,
    mln_type_sport.smile_html,
    mln_districts.district 
    FROM data_analiz_district AS a 
    LEFT JOIN mln_type_sport ON a.sport_id = mln_type_sport.id 
    LEFT JOIN mln_districts ON a.district_id = mln_districts.id 
    ".$where." 
    ORDER BY a.koeff_y DESC   
    LIMIT ".$start.", ".$num);
        
    if ($data != false) {
        
        $rows = db_query("SELECT FOUND_ROWS() AS cnt");
        $col = $rows[0]['cnt'];

        $nav = pagination($num, $col, $page, 'data_analiz_district');
        
        ob_start();
        require $_SERVER['DOCUMENT_ROOT'].'/modules/data/components/component/includes/analizDistrictsList.inc.php';
        $html = ob_get_clean();
        
        exit($html);
    }
   
}
// --------------------------------------------------------------------------------------------------------------

// постраничная навигация по районам
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_mln_districts') {
    
    $where = null;
    
    if (!empty($_POST['filter'])) {
        
        $f = explode('=',$_POST['filter']);
        
        if ($f[0] == 'adm_id') {
            $where = " AND id_adm_area=".intval($f[1])." ";
            $adm_area_id = intval($f[1]);
        }
          
        
    }
    
    $page = intval($_POST['page']);

    if (empty($page) or $page <= 0)
      $page = 1;

    $start = $page * $num - $num;
    
    $data = db_query("SELECT SQL_CALC_FOUND_ROWS 
    id,
    district,
    adm_area 
    FROM mln_districts 
    ".$where." 
    ORDER BY id  
    LIMIT ".$start.", ".$num);
        
    if ($data != false) {
        
        $rows = db_query("SELECT FOUND_ROWS() AS cnt");
        $col = $rows[0]['cnt'];

        $nav = pagination($num, $col, $page, 'mln_districts');
        
        $adm_area = $data[0]['adm_area'];
        
        ob_start();
        require $_SERVER['DOCUMENT_ROOT'].'/modules/data/components/component/includes/districtsList.inc.php';
        $html = ob_get_clean();
        
        exit($html);
    }
   
}
// --------------------------------------------------------------------------------------------------------------

// постраничная навигация по видам спорта
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_mln_type_sport') {
    
    $where = null;
    
    if (!empty($_POST['filter'])) {
        
        $f = explode('=',$_POST['filter']);
        
        if ($f[0] == 'cat_id') {
            $where = " AND cat_id=".intval($f[1])." ";
            $sport_cat_id = intval($f[1]);
        }
          
        
    }
    
    $page = intval($_POST['page']);

    if (empty($page) or $page <= 0)
      $page = 1;

    $start = $page * $num - $num;
    
    $data = db_query("SELECT SQL_CALC_FOUND_ROWS 
    id,
    type,
    popular,
    smile_html,
    category,
    season 
    FROM mln_type_sport 
    ".$where." 
    ORDER BY type ASC 
    LIMIT ".$start.", ".$num);
        
    if ($data != false) {
        
        $rows = db_query("SELECT FOUND_ROWS() AS cnt");
        $col = $rows[0]['cnt'];

        $nav = pagination($num, $col, $page, 'mln_type_sport');
        
        $sport_cat = $data[0]['category'];
        
        ob_start();
        require $_SERVER['DOCUMENT_ROOT'].'/modules/data/components/component/includes/sportTypeList.inc.php';
        $html = ob_get_clean();
        
        exit($html);
    }
   
}
// --------------------------------------------------------------------------------------------------------------

// постраничная навигация спортзон
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_mos_objects_sportzone') {
    
    $where = null;
    $stArr = array();
    
    if (!empty($_POST['filter'])) {
        
        $f = explode('=',$_POST['filter']);
        
        if ($f[0] == 'district') {
            $where = " WHERE a.district_id=".intval($f[1])." ";
            $district_id = intval($f[1]);
            
            $st = db_query("SELECT object_id 
            FROM mos_objects 
            WHERE district_id=".$district_id);
        
            if ($st != false) {
               foreach($st as $s) {
                 $stArr[] = $s['object_id'];
               }
            }
            
            $where = " WHERE a.object_id IN (".implode(',',$stArr).") ";
            
            $ds = db_query("SELECT district FROM mln_districts WHERE id=".$district_id." LIMIT 1");
            $district = $ds[0]['district'];
        }
        
        if ($f[0] == 'sport_id') {
             
            $sport_id = intval($f[1]);
            $where = " WHERE a.sport_id=".$sport_id." ";
            
            $ds = db_query("SELECT type FROM mln_type_sport WHERE id=".$sport_id." LIMIT 1");
            $sport = $ds[0]['type'];
        }
    }
    
    $page = intval($_POST['page']);

    if (empty($page) or $page <= 0)
      $page = 1;

    $start = $page * $num - $num;
    
    $districts = array();
    
    $dst = db_query("SELECT id, district FROM mln_districts");
    
    if ($dst != false) {
       foreach($dst as $b) {
         $districts[ $b['id'] ] = $b['district'];
       } 
    }
    
    $data = db_query("SELECT SQL_CALC_FOUND_ROWS 
    a.sportzone_id,
    a.sportzone_name,
    a.sportzone_type,
    a.sportzone_area,
    a.category,
    a.lng,
    a.lat,
    mos_objects.object,
    mos_objects.address,
    mos_objects.district_id,
    mos_organization.org_name,
    mos_availability.availability 
    FROM mos_objects_sportzone AS a 
    LEFT JOIN mos_objects ON a.object_id = mos_objects.object_id 
    LEFT JOIN mos_organization ON a.org_id = mos_organization.org_id 
    LEFT JOIN mos_availability ON a.availability_id = mos_availability.id 
    ".$where." 
    GROUP BY a.sportzone_id 
    ORDER BY a.id 
    LIMIT ".$start.", ".$num);
        
    if ($data != false) {
        
        $rows = db_query("SELECT FOUND_ROWS() AS cnt");
        $col = $rows[0]['cnt'];

        $nav = pagination($num, $col, $page, 'mos_objects_sportzone');
        
        $sportZone = array();
        $sportType = array();
    
    
        foreach($data as $b) {
            $sportZone[] = $b['sportzone_id'];
        }
        
        $spz = db_query("SELECT a.sportzone_id,
        a.sport_id,
        mln_type_sport.type  
        FROM mos_objects_sportzone AS a 
        LEFT JOIN mln_type_sport ON a.sport_id = mln_type_sport.id 
        WHERE a.sportzone_id IN (".implode(',',$sportZone).")");
        
        if ($spz != false) {
            foreach($spz as $b) {
                if (!empty($b['type'])) {
                    $sportType[$b['sportzone_id']][$b['sport_id']] = $b['type'];
                }
            }
        }
    
        ob_start();
        require $_SERVER['DOCUMENT_ROOT'].'/modules/data/components/component/includes/sportZoneList.inc.php';
        $html = ob_get_clean();
        
        exit($html);
    }
   
}
// --------------------------------------------------------------------------------------------------------------

// постраничная навигация спортивных объектов
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_mos_objects') {
    
    $where = null;
    
    
    if (!empty($_POST['filter'])) {
        
        $f = explode('=',$_POST['filter']);
        
        if ($f[0] == 'district') {
            $where = " WHERE a.district_id=".intval($f[1])." ";
            $district_id = intval($f[1]);
            
            $ds = db_query("SELECT district FROM mln_districts WHERE id=".$district_id." LIMIT 1");
            $district = $ds[0]['district'];
        }
          
        if ($f[0] == 'org_id') {
            $where = " WHERE a.org_id=".intval($f[1])." ";
            $org_id = intval($f[1]);
            
            $ds = db_query("SELECT org_name FROM mos_organization WHERE org_id=".$org_id." LIMIT 1");
            $organization = $ds[0]['org_name'];
        }
        
        if ($f[0] == 'sport_id') {
             
             $sport_id = intval($f[1]);
             $stArr = array();
             
             $st = db_query("SELECT object_id 
             FROM mos_objects_sportzone 
             WHERE sport_id=".$sport_id." 
             GROUP BY object_id");
        
             if ($st != false) {
               foreach($st as $s) {
                  $stArr[] = $s['object_id'];
               }
             }
            
            $where = " WHERE a.object_id IN (".implode(',',$stArr).") ";
            
            $ds = db_query("SELECT type FROM mln_type_sport WHERE id=".$sport_id." LIMIT 1");
            $sport = $ds[0]['type'];
        }
    }
    
    $page = intval($_POST['page']);

    if (empty($page) or $page <= 0)
      $page = 1;

    $start = $page * $num - $num;
    
    $data = db_query("SELECT SQL_CALC_FOUND_ROWS 
    a.object_id,
    a.object,
    a.address,
    a.lng,
    a.lat,
    mos_organization.org_name,
    mos_availability.availability,
    mln_districts.district 
    FROM mos_objects AS a 
    LEFT JOIN mos_organization ON a.org_id = mos_organization.org_id 
    LEFT JOIN mos_availability ON a.availability_id = mos_availability.id 
    LEFT JOIN mln_districts ON a.district_id = mln_districts.id 
    ".$where." 
    ORDER BY a.id 
    LIMIT ".$start.", ".$num);
        
    if ($data != false) {
        
        $rows = db_query("SELECT FOUND_ROWS() AS cnt");
        $col = $rows[0]['cnt'];

        $nav = pagination($num, $col, $page, 'mos_objects');
        
        $sportZone = array();
        $sportType = array();
    
    
        foreach($data as $b) {
            $sportZone[] = $b['object_id'];
        }
        
        $spz = db_query("SELECT a.object_id,
        a.sport_id,
        mln_type_sport.type  
        FROM mos_objects_sportzone AS a 
        LEFT JOIN mln_type_sport ON a.sport_id = mln_type_sport.id 
        WHERE a.object_id IN (".implode(',',$sportZone).")");
        
        if ($spz != false) {
            foreach($spz as $b) {
                if (!empty($b['type'])) {
                    $sportType[$b['object_id']][$b['sport_id']] = $b['type'];
                }
            }
        }
        
        ob_start();
        require $_SERVER['DOCUMENT_ROOT'].'/modules/data/components/component/includes/sportObjectsList.inc.php';
        $html = ob_get_clean();
        
        exit($html);
    }
   
}
// --------------------------------------------------------------------------------------------------------------

// постраничная навигация домов
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_mos_realty') {
    
    $where = null;
    
    if (!empty($_POST['filter'])) {
        
        $f = explode('=',$_POST['filter']);
        
        if ($f[0] == 'district') {
            $where = " AND a.district_id=".intval($f[1])." ";
            $district_id = intval($f[1]);
            
            $ds = db_query("SELECT district FROM mln_districts WHERE id=".$district_id." LIMIT 1");
            $district = $ds[0]['district'];
        }
          
        
    }
    
    $page = intval($_POST['page']);

    if (empty($page) or $page <= 0)
      $page = 1;

    $start = $page * $num - $num;
    
    $data = db_query("SELECT SQL_CALC_FOUND_ROWS 
    a.address,
    a.built_year,
    a.living_quarters_count,
    a.area_residential,
    a.lng,
    a.lat,
    mln_districts.district 
    FROM mos_realty AS a 
    LEFT JOIN mln_districts ON a.district_id = mln_districts.id 
    WHERE a.region_id='0c5b2444-70a0-4932-980c-b4dc0d3f02b5' 
    AND a.district_id!=0 
    ".$where." 
    ORDER BY a.district_id 
    LIMIT ".$start.", ".$num);
        
    if ($data != false) {
        
        $rows = db_query("SELECT FOUND_ROWS() AS cnt");
        $col = $rows[0]['cnt'];

        $nav = pagination($num, $col, $page, 'mos_realty');
        
        ob_start();
        require $_SERVER['DOCUMENT_ROOT'].'/modules/data/components/component/includes/realtyList.inc.php';
        $html = ob_get_clean();
        
        exit($html);
    }
   
}
// --------------------------------------------------------------------------------------------------------------

// подгружаемый список административных округов
if (isset($_POST['field']) && preg_match('/admArea/is',$_POST['field']) ) {
    
    $search = clearData($_POST['search']);
    $btn = 'jsDistrictSearch';
       
    $ds = db_query("SELECT id_adm_area, adm_area      
    FROM mln_districts      
    WHERE adm_area LIKE ('%".$search."%') 
    GROUP BY id_adm_area");
            
    if ($ds != false) {
      foreach($ds as $d) {
        $sp .= '<li class="jsSelect" style="cursor: pointer;" id="'.$_POST['field'].'" data-id="'.$d['id_adm_area'].'" data-click="'.$btn.'" data-input="jsClickFilter" data-input-val="adm_id='.$d['id_adm_area'].'">'.$d['adm_area'].'</li>';            
      }
    }
  
    exit($sp);
     
} 
// ------------------------------------------------------------------------------------------------

// подгружаемый список категорий спорта
if (isset($_POST['field']) && preg_match('/sportCat/is',$_POST['field']) ) {
    
    $table = $_POST['arr'];
    $search = clearData($_POST['search']);
    $org = array();
    $btn = 'jsCatSearch';
       
    $ds = db_query("SELECT cat_id, category     
    FROM mln_type_sport     
    WHERE category LIKE ('%".$search."%') 
    GROUP BY cat_id");
            
    if ($ds != false) {
      foreach($ds as $d) {
        $sp .= '<li class="jsSelect" style="cursor: pointer;" id="'.$_POST['field'].'" data-id="'.$d['cat_id'].'" data-click="'.$btn.'" data-input="jsClickFilter" data-input-val="cat_id='.$d['cat_id'].'">'.$d['category'].'</li>';            
      }
    }
  
    exit($sp);
     
} 
// ------------------------------------------------------------------------------------------------

// подгружаемый список ведомственных организаций
if (isset($_POST['field']) && preg_match('/organization/is',$_POST['field']) ) {
    
    $table = $_POST['arr'];
    $search = clearData($_POST['search']);
    $org = array();
    
     if ($table == 'mos_objects') {
       
       $a = db_query("SELECT org_id   
       FROM mos_objects 
       WHERE org_id!=0 
       GROUP BY org_id");
            
       $btn = 'jsObjectsSearch';
       
     }
                       
     if ($a != false) {
            
        $sp = null;
            
        foreach($a as $b) {
           $org[ $b['org_id'] ] = $b['org_id'];
        }
            
        $ds = db_query("SELECT org_id, org_name    
        FROM mos_organization    
        WHERE org_name LIKE ('%".$search."%')");
            
            if ($ds != false) {
                foreach($ds as $d) {
                    if (!empty($org[ $d['org_id'] ])) {
                        $sp .= '<li class="jsSelect" style="cursor: pointer;" id="'.$_POST['field'].'" data-id="'.$d['org_id'].'" data-click="'.$btn.'" data-input="jsClickFilter" data-input-val="org_id='.$d['org_id'].'">'.$d['org_name'].'</li>';
                    }
                }
            }
  
            exit($sp);
     }
} 
// ------------------------------------------------------------------------------------------------

// подгружаемый список видов спорта
if (isset($_POST['field']) && preg_match('/sportType/is',$_POST['field']) ) {
    
    $table = $_POST['arr'];
    $search = clearData($_POST['search']);
    $sportArr = array();
    
     if ($table == 'mos_objects') {
       
       $a = db_query("SELECT sport_id   
       FROM mos_objects_sportzone  
       WHERE sport_id!=0 
       GROUP BY sport_id");
            
       $btn = 'jsObjectsSearch';
       
     }
     
     if ($table == 'mos_objects_sportzone') {
       
       $a = db_query("SELECT sport_id   
       FROM mos_objects_sportzone  
       WHERE sport_id!=0 
       GROUP BY sport_id");
            
       $btn = 'jsSportZoneSearch';
       
     }
     
     if ($table == 'data_analiz_district') {
       
       $a = db_query("SELECT sport_id   
       FROM data_analiz_district   
       WHERE sport_id!=0 
       GROUP BY sport_id");
            
       $btn = 'jsAnalizDistrictsSearch';
       
     }
                       
     if ($a != false) {
            
        $sp = null;
            
        foreach($a as $b) {
           $sportArr[ $b['sport_id'] ] = $b['sport_id'];
        }
            
        $ds = db_query("SELECT id, type     
        FROM mln_type_sport     
        WHERE type LIKE ('%".$search."%')");
            
            if ($ds != false) {
                foreach($ds as $d) {
                    if (!empty($sportArr[ $d['id'] ])) {
                        $sp .= '<li class="jsSelect" style="cursor: pointer;" id="'.$_POST['field'].'" data-id="'.$d['id'].'" data-click="'.$btn.'" data-input="jsClickFilter" data-input-val="sport_id='.$d['id'].'">'.$d['type'].'</li>';
                    }
                }
            }
  
            exit($sp);
     }
} 
// ------------------------------------------------------------------------------------------------

// подгружаемый список адресов
if (isset($_POST['field']) && preg_match('/houseAddress/is',$_POST['field']) ) {
    
    $search = clearData($_POST['search']);
    
    if (strlen($search) > 2) {
        
        $a = db_query("SELECT id, address  
        FROM mos_realty  
        WHERE region_id='0c5b2444-70a0-4932-980c-b4dc0d3f02b5' 
        AND address LIKE ('%".$search."%') 
        LIMIT 100");
                       
        if ($a != false) {
            
            $sp = null;
            
            foreach($a as $r) {
                  $sp .= '<li class="jsSelect" style="cursor: pointer;" id="'.$_POST['field'].'" data-id="'.$r['id'].'" data-click="jsHousesSearch" data-input="jsClickFilter" data-input-val="house='.$r['id'].'">'.$r['address'].'</li>';
            }
                
            exit($sp);
        }
    }
} 
// ------------------------------------------------------------------------------------------------

// подгружаемый список районов
if (isset($_POST['field']) && preg_match('/districtFilter/is',$_POST['field']) ) {
    
    $table = $_POST['arr'];
    $search = clearData($_POST['search']);
    $districts = array();
    
    if (strlen($search) > 2) {
        
        if ($table == 'mos_realty') {
            $a = db_query("SELECT district_id   
            FROM mos_realty  
            WHERE region_id='0c5b2444-70a0-4932-980c-b4dc0d3f02b5' 
            AND district_id!=0 
            GROUP BY district_id");
            
            $btn = 'jsHousesSearch';
        }
        
        if ($table == 'mos_objects') {
            $a = db_query("SELECT district_id   
            FROM mos_objects 
            WHERE district_id!=0 
            GROUP BY district_id");
            
            $btn = 'jsObjectsSearch';
        }
        
         if ($table == 'mos_objects_sportzone') {
            $a = db_query("SELECT 
            a.id,
            mos_objects.district_id   
            FROM mos_objects_sportzone AS a 
            JOIN mos_objects ON a.object_id = mos_objects.object_id 
            WHERE mos_objects.district_id!=0 
            GROUP BY mos_objects.district_id");
            
            $btn = 'jsSportZoneSearch';
        }
        
        if ($table == 'data_analiz_district') {
            $a = db_query("SELECT district_id   
            FROM data_analiz_district  
            WHERE district_id!=0 
            GROUP BY district_id");
            
            $btn = 'jsAnalizDistrictsSearch';
        }
                       
        if ($a != false) {
            
            $sp = null;
            
            foreach($a as $b) {
                $districts[ $b['district_id'] ] = $b['district_id'];
            }
            
            $ds = db_query("SELECT id, district   
            FROM mln_districts   
            WHERE district LIKE ('%".$search."%')");
            
            if ($ds != false) {
                foreach($ds as $d) {
                    if (!empty($districts[ $d['id'] ])) {
                        $sp .= '<li class="jsSelect" style="cursor: pointer;" id="'.$_POST['field'].'" data-id="'.$d['id'].'" data-click="'.$btn.'" data-input="jsClickFilter" data-input-val="district='.$d['id'].'">'.$d['district'].'</li>';
                    }
                }
            }
  
            exit($sp);
        }
    }
} 
// ------------------------------------------------------------------------------------------------

// фильтры по домам
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_jsHousesSearch') {
    
    $where = null;
    $house_id = intval($_POST['house_id']);
    $district_id = intval($_POST['district_id']);
    
    $address = $_POST['address'];
    $district = $_POST['district'];
    
    if (!empty($house_id))
      $where = " AND a.id=".$house_id." ";
      
    if (!empty($district_id))
      $where = " AND a.district_id=".$district_id." ";
    
    $data = db_query("SELECT SQL_CALC_FOUND_ROWS 
    a.address,
    a.built_year,
    a.living_quarters_count,
    a.area_residential,
    a.lng,
    a.lat,
    mln_districts.district 
    FROM mos_realty AS a 
    LEFT JOIN mln_districts ON a.district_id = mln_districts.id 
    WHERE a.region_id='0c5b2444-70a0-4932-980c-b4dc0d3f02b5' 
    ".$where." 
    ORDER BY a.district_id 
    LIMIT 0, ".$num);
        
    if ($data != false) {
        
        $rows = db_query("SELECT FOUND_ROWS() AS cnt");
        $col = $rows[0]['cnt'];

        $nav = pagination($num, $col, 1, 'mos_realty');
        
        ob_start();
        require $_SERVER['DOCUMENT_ROOT'].'/modules/data/components/component/includes/realtyList.inc.php';
        $html = ob_get_clean();
        
        exit($html);
    }
   
}
// -------------------------------------------------------------------------------------------------------

// фильтры по спортивным объектам
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_jsObjectsSearch') {
    
    $where = null;
    $stArr = array();
    
    $district_id = intval($_POST['district_id']);
    $district = $_POST['district'];
    
    $org_id = $_POST['org_id'];
    $organization = $_POST['organization'];
    
    $sport_id = intval($_POST['sport_id']);
    $sport = $_POST['sport'];
      
    if (!empty($district_id))
      $where = " WHERE a.district_id=".$district_id." ";
      
    if (!empty($org_id))
      $where = " WHERE a.org_id=".$org_id." ";
      
    if (!empty($sport_id)) {
        
        $st = db_query("SELECT object_id 
        FROM mos_objects_sportzone 
        WHERE sport_id=".$sport_id." 
        GROUP BY object_id");
        
        if ($st != false) {
            foreach($st as $s) {
                $stArr[] = $s['object_id'];
            }
            
            $where = " WHERE a.object_id IN (".implode(',',$stArr).") ";
        }
        
    }
    
    $data = db_query("SELECT SQL_CALC_FOUND_ROWS 
    a.object_id,
    a.object,
    a.address,
    a.lng,
    a.lat,
    mos_organization.org_name,
    mos_availability.availability,
    mln_districts.district 
    FROM mos_objects AS a 
    LEFT JOIN mos_organization ON a.org_id = mos_organization.org_id 
    LEFT JOIN mos_availability ON a.availability_id = mos_availability.id 
    LEFT JOIN mln_districts ON a.district_id = mln_districts.id 
    ".$where." 
    ORDER BY a.id  
    LIMIT 0, ".$num);
        
    if ($data != false) {
        
        $rows = db_query("SELECT FOUND_ROWS() AS cnt");
        $col = $rows[0]['cnt'];

        $nav = pagination($num, $col, 1, 'mos_objects');
        
        $sportZone = array();
        $sportType = array();
    
    
        foreach($data as $b) {
            $sportZone[] = $b['object_id'];
        }
        
        $spz = db_query("SELECT a.object_id,
        a.sport_id,
        mln_type_sport.type  
        FROM mos_objects_sportzone AS a 
        LEFT JOIN mln_type_sport ON a.sport_id = mln_type_sport.id 
        WHERE a.object_id IN (".implode(',',$sportZone).")");
        
        if ($spz != false) {
            foreach($spz as $b) {
                if (!empty($b['type'])) {
                    $sportType[$b['object_id']][$b['sport_id']] = $b['type'];
                }
            }
        }
    
        ob_start();
        require $_SERVER['DOCUMENT_ROOT'].'/modules/data/components/component/includes/sportObjectsList.inc.php';
        $html = ob_get_clean();
        
        exit($html);
    }
   
}
// -------------------------------------------------------------------------------------------------------

// фильтры по спортзонам
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_jsSportZoneSearch') {
    
    $where = null;
    $stArr = array();
    
    $district_id = intval($_POST['district_id']);
    $district = $_POST['district'];
    
    $sport_id = intval($_POST['sport_id']);
    $sport = $_POST['sport'];
      
    if (!empty($district_id)) {
        
        $st = db_query("SELECT object_id 
        FROM mos_objects 
        WHERE district_id=".$district_id);
        
        if ($st != false) {
            foreach($st as $s) {
                $stArr[] = $s['object_id'];
            }
            
            $where = " WHERE a.object_id IN (".implode(',',$stArr).") ";
        }
        
    }
      
      
    if (!empty($sport_id)) {
        $where = " WHERE a.sport_id=".$sport_id." ";
    }
    
    $districts = array();
    
    $dst = db_query("SELECT id, district FROM mln_districts");
    
    if ($dst != false) {
       foreach($dst as $b) {
         $districts[ $b['id'] ] = $b['district'];
       } 
    }
    
    $data = db_query("SELECT SQL_CALC_FOUND_ROWS 
    a.sportzone_id,
    a.sportzone_name,
    a.sportzone_type,
    a.sportzone_area,
    a.category,
    a.lng,
    a.lat,
    mos_objects.object,
    mos_objects.address,
    mos_objects.district_id,
    mos_organization.org_name,
    mos_availability.availability 
    FROM mos_objects_sportzone AS a 
    LEFT JOIN mos_objects ON a.object_id = mos_objects.object_id 
    LEFT JOIN mos_organization ON a.org_id = mos_organization.org_id 
    LEFT JOIN mos_availability ON a.availability_id = mos_availability.id 
    ".$where." 
    GROUP BY a.sportzone_id 
    ORDER BY a.id 
    LIMIT 0, ".$num);
        
    if ($data != false) {
        
        $rows = db_query("SELECT FOUND_ROWS() AS cnt");
        $col = $rows[0]['cnt'];

        $nav = pagination($num, $col, 1, 'mos_objects_sportzone');
        
        $sportZone = array();
        $sportType = array();
    
        foreach($data as $b) {
            $sportZone[] = $b['sportzone_id'];
        }
        
        $spz = db_query("SELECT a.sportzone_id,
        a.sport_id,
        mln_type_sport.type  
        FROM mos_objects_sportzone AS a 
        LEFT JOIN mln_type_sport ON a.sport_id = mln_type_sport.id 
        WHERE a.sportzone_id IN (".implode(',',$sportZone).")");
        
        if ($spz != false) {
            foreach($spz as $b) {
                if (!empty($b['type'])) {
                    $sportType[$b['sportzone_id']][$b['sport_id']] = $b['type'];
                }
            }
        }
    
        ob_start();
        require $_SERVER['DOCUMENT_ROOT'].'/modules/data/components/component/includes/sportZoneList.inc.php';
        $html = ob_get_clean();
        
        exit($html);
    }
   
}
// -------------------------------------------------------------------------------------------------------

// фильтры по видам спорта
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_jsCatSearch') {
    
    $where = null;
    $cat_id = intval($_POST['sport_cat_id']);
    
    if (!empty($cat_id)) {
        $where = " WHERE cat_id=".$cat_id." ";
        $sport_cat_id = $cat_id;
    }
      
      
    
    $data = db_query("SELECT SQL_CALC_FOUND_ROWS 
    id,
    type,
    popular,
    smile_html,
    category,
    season 
    FROM mln_type_sport 
    ".$where."
    ORDER BY type ASC 
    LIMIT 0, ".$num." 
    ");
        
    if ($data != false) {
        
        $rows = db_query("SELECT FOUND_ROWS() AS cnt");
        $col = $rows[0]['cnt'];

        $nav = pagination($num, $col, 1, 'mln_type_sport');
        
        $sport_cat = $data[0]['category'];
        
        ob_start();
        require $_SERVER['DOCUMENT_ROOT'].'/modules/data/components/component/includes/sportTypeList.inc.php';
        $html = ob_get_clean();
        
        exit($html);
    }
   
}
// -------------------------------------------------------------------------------------------------------

// фильтры по районам 
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_jsDistrictSearch') {
    
    $where = null;
    $adm_area_id = intval($_POST['adm_area_id']);
    
    if (!empty($adm_area_id)) {
        $where = " WHERE id_adm_area=".$adm_area_id." ";
    }
      
    $data = db_query("SELECT SQL_CALC_FOUND_ROWS 
    id,
    district,
    adm_area 
    FROM mln_districts 
    ".$where." 
    ORDER BY id  
    LIMIT 0, ".$num);
        
    if ($data != false) {
        
        $rows = db_query("SELECT FOUND_ROWS() AS cnt");
        $col = $rows[0]['cnt'];

        $nav = pagination($num, $col, 1, 'mln_districts');
        
        $adm_area = $data[0]['adm_area'];
        
        ob_start();
        require $_SERVER['DOCUMENT_ROOT'].'/modules/data/components/component/includes/districtsList.inc.php';
        $html = ob_get_clean();
        
        exit($html);
    }
   
}
// -------------------------------------------------------------------------------------------------------

// фильтры по data_analiz_district
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_jsAnalizDistrictsSearch') {
    
    $where = null;
    $sport_id = intval($_POST['sport_id']);
    $district_id = intval($_POST['district_id']);
    
    if (!empty($sport_id)) {
        $where = " WHERE a.sport_id=".$sport_id." ";
    }
    
    if (!empty($district_id)) {
        $where = " WHERE a.district_id=".$district_id." ";
    }
      
    $data = db_query("SELECT SQL_CALC_FOUND_ROWS 
    a.koeff_y,
    a.koeff_count_zone,
    mln_type_sport.type,
    mln_type_sport.smile_html,
    mln_districts.district 
    FROM data_analiz_district AS a 
    LEFT JOIN mln_type_sport ON a.sport_id = mln_type_sport.id 
    LEFT JOIN mln_districts ON a.district_id = mln_districts.id 
    ".$where." 
    ORDER BY a.koeff_y DESC   
    LIMIT 0, ".$num);
        
    if ($data != false) {
        
        $rows = db_query("SELECT FOUND_ROWS() AS cnt");
        $col = $rows[0]['cnt'];

        $nav = pagination($num, $col, 1, 'data_analiz_district');
        
        $sport = $data[0]['type'];
        
        ob_start();
        require $_SERVER['DOCUMENT_ROOT'].'/modules/data/components/component/includes/analizDistrictsList.inc.php';
        $html = ob_get_clean();
        
        exit($html);
    }
   
}
// -------------------------------------------------------------------------------------------------------