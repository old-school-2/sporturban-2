<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/bots/sberhack/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/db.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/functions.php";
// ----------------------------------------------------------------------------------------------

require_once $_SERVER['DOCUMENT_ROOT'] . "/bots/sberhack/lib/bd.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/bots/sberhack/lib/utilities.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/bots/sberhack/lib/buttons.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/bots/sberhack/lib/interface.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/bots/sberhack/lib/functions.php";

$dataRow = file_get_contents('php://input');

try {
    if (!empty($dataRow)) {
        //Преобразуем запрос пользователя в массив
        $data     = json_decode($dataRow, true);
        //Определим протокол (Сбер или Алиса)
        $protocol = get_protocol($data);

        file_put_contents('log/'. $protocol .'_input.txt', date('Y-m-d H:i:s') . PHP_EOL . $dataRow . PHP_EOL, FILE_APPEND);
        //Получим ответ
        $content =  main($protocol, $data);

        $result = get_response( $protocol, $data, $content);
        file_put_contents('log/'. $protocol . '_output.txt', date('Y-m-d H:i:s') . PHP_EOL .   $result . PHP_EOL, FILE_APPEND);
        echo   $result ;
    }
    else {
        echo 'Empty data';
    }
}
    catch (Exception $e) {
        echo '["Error occured"]';
}