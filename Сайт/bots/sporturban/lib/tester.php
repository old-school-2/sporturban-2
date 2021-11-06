<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/bots/sberhack/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/db.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/functions.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/bots/sberhack/lib/bd.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/bots/sberhack/lib/utilities.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/bots/sberhack/lib/buttons.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/bots/sberhack/lib/interface.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/bots/sberhack/lib/functions.php";

//function curl_post_async($url)
//{
//    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_URL, $url);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
//    curl_exec($ch);
//    curl_close($ch);
//}


echo  curl_post_async("https://afisha.live/bots/sberhack/bot.php?event=94");
