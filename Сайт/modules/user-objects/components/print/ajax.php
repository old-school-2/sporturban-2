<?php


// редактирование объекта в базе
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_getPdf') {
    
    require_once $_SERVER['DOCUMENT_ROOT']. '/lib/convertapi-php/lib/ConvertApi/autoload.php';
    use \ConvertApi\ConvertApi;
    
    $obj_id = intval($_POST['obj_id']);
    
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
        'FileName' => 'web-example'
    ],
    $fromFormat,
    $conversionTimeout
    );

    $savedFiles = $result->saveFiles($dir);
    
    //print_r($savedFiles);
    
}