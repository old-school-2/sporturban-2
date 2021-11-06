<?php

$pagename = clearData($_GET['com'],'get');

if (empty($pagename))
  exit(header('Location: /'));

$m = explode('-',$pagename);

$map_id = intval($m[0]);

if (empty($map_id))
  exit(header('Location: /'));
  
$map = db_query("SELECT * FROM mos_maps WHERE id=".$map_id." LIMIT 1");

if ($map == false)
  exit(header('Location: /'));