<?

/*
$json = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/mo.geojson'), true);

echo '<pre>';
print_r($json);
echo '</pre>';

foreach($json['features'] as $k => $DISTRICT){

    if ($k == 132){
        $districtName = db_query("SELECT * FROM mln_districts WHERE id='67' LIMIT 1;");

    } else {
        $districtName = db_query("SELECT * FROM mln_districts WHERE district LIKE '%".$DISTRICT['properties']['NAME']."%' LIMIT 1;");

    }

    if ($districtName != false){

        $coordinates = array();
        foreach ($DISTRICT['geometry']['coordinates'] as $kk => $vv){
            if (!empty($vv[1])){
                foreach ($vv as $vvv){
                    $coordinates[$kk][] = array($vvv[1], $vvv[0]);
                }
            } else {
                foreach ($vv[0] as $vvv){
                    $coordinates[$kk][] = array($vvv[1], $vvv[0]);
                }
            }


        }
        $polygon = json_encode($coordinates);
        //$polygon = json_encode($DISTRICT['geometry']['coordinates']);
        db_query("UPDATE mln_districts SET polygons='".$polygon."' WHERE id='".$districtName[0]['id']."' LIMIT 1;");
    }
}
exit();
*/

include($_SERVER['DOCUMENT_ROOT'].'/modules/map4/include/data.php');


/*
function rand_color() {
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}

foreach ($type_sport as $ts){
   	if (empty($ts['color'])){
   		$color = rand_color();
   		db_query("UPDATE mln_type_sport SET color='".$color."' WHERE id='".$ts['id']."' LIMIT 1;");
   	}

}
*/

/*
foreach($objects_sportzones as $b){

	if (array_key_exists($b['sport_name'], $type_sport_names) == false){
		$id = db_query("INSERT INTO mln_type_sport (type) VALUES ('".$b['sport_name']."');");
		$type_sport_names[$b['sport_name']] = $id;
	} else {
		$id = $type_sport_names[$b['sport_name']];
	}

	db_query("UPDATE mos_objects_sportzone SET sport_id='".$id."' WHERE id='".$b['id']."' LIMIT 1;");

}
*/

?>