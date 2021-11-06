<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/lib/convertapi-php/lib/ConvertApi/autoload.php';

use \ConvertApi\ConvertApi;

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
        'Url' => 'https://sporturban.ru/user-objects/print/200002',
        'FileName' => 'web-example'
    ],
    $fromFormat,
    $conversionTimeout
);

$savedFiles = $result->saveFiles($dir);

echo "The web page PDF saved to\n";

print_r($savedFiles);
