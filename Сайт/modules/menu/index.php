<?php

// список карт
$mapsMenu = db_query("SELECT * FROM mos_maps ORDER BY id");

// список данных
$dataList = db_query("SELECT * FROM mos_data ORDER BY id");