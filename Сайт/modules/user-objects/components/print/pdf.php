<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/lib/convertapi-php/lib/ConvertApi/autoload.php';
use \ConvertApi\ConvertApi;
    
    $obj_id = $_GET['obj'];
    
    # set your api secret
    ConvertApi::setApiSecret('6bkZn3PtKY9OyJNs');

    # Example of converting Web Page URL to PDF file
    # https://www.convertapi.com/web-to-pdf

    $fromFormat = 'web';
    $conversionTimeout = 180;
    $dir = $_SERVER['DOCUMENT_ROOT'].'/files/pdf/';//sys_get_temp_dir();

    $result = ConvertApi::convert(
    'pdf',
    [
        'Url' => 'https://sporturban.ru/user-objects/print/'.$obj_id,
        'FileName' => 'otchet-'.$obj_id,
        'ConversionDelay' => 30
    ],
    $fromFormat,
    $conversionTimeout
    );

    $savedFiles = $result->saveFiles($dir);
    
    //$root = dirname(__FILE__);
    //$request = $_SERVER['REQUEST_URI'];
    $filename = 'otchet-'.$obj_id.'.pdf';
    $path = $_SERVER['DOCUMENT_ROOT'].'/files/pdf/'.$filename;
  
    if (file_exists($path)) {
    if (ob_get_level()) {
        ob_end_clean();
    }
    header("Content-Type: application/pdf; charset=UTF-8");
    header("Content-Length: ".filesize($path));
    header("Content-Disposition: attachment; filename=\"{$filename}\"");
    header("Content-Transfer-Encoding: binary");
    header("Cache-Control: must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
    readfile($path);
}