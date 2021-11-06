<?php

// подгружаемый список категорий спорта
if (isset($_POST['field']) && preg_match('/jsSearchObjects/is',$_POST['field']) ) {
    
    $search = clearData($_POST['search']);
    
    if (strlen($search) > 0) {
        
        $ds = db_query("SELECT object_id, object      
        FROM mos_objects      
        WHERE object LIKE ('%".$search."%') 
        LIMIT 100");
            
        if ($ds != false) {
          foreach($ds as $d) {
            $sp .= '<a class="dataObjectLink" style="text-decoration: none;" href="'.DOMAIN.'/user-objects/print/'.$d['object_id'].'" target="_blank"><li style="font-size: 13px;" id="'.$_POST['field'].'" data-id="'.$d['object_id'].'">'.$d['object'].'</li></a>';            
          }
        }
  
        exit($sp);
        
    }
    
    
     
} 
// ------------------------------------------------------------------------------------------------