<?php
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
session_start();

date_default_timezone_set("UTC"); // Устанавливаем часовой пояс по Гринвичу
header('Content-Type: text/html; charset=utf-8'); // устанавливаем кодировку

require_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/db.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/functions.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/version.php";

$xc['url'] = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '?') + 1);
parse_str($xc['url'], $xc['url']);

// для всплывающих окон, которые показываются сразу после загрузки страницы
$xc['message'] = null;

if (!empty($_COOKIE['message'])) {
    $xc['message'] = $_COOKIE['message'];
    setcookie("message", "", time() - 9999999, "/");
}
// ------------------------------------------------------------------------

// определение мобильного браузера

//  ручное включение мобильной/пк версии
if (isset($xc['url']['version']))
    $_SESSION['version'] =  $xc['url']['version'];

if (!empty($_SESSION['version'])){
    
    if ($_SESSION['version'] == 'mobile') {
        define('MOBILE', true);   
    } 
    
    if ($_SESSION['version'] == 'pc') {
        define('MOBILE', false);
    } 
}  

else
  define('MOBILE', is_mobile($_SERVER['HTTP_USER_AGENT'])); //is_mobile($_SERVER['HTTP_USER_AGENT'])
// ----------------------------------------------------------------------------------------------------------------------

// ----------------------------------------- Ajax запросы ---------------------------------------------------------------
if (isset($_POST['action']) && $_POST['action'] == 'ajax' && !empty($_POST['module'])) {
    
    $xc['module'] = clearData($_POST['module'], 'get');
    $xc['component'] = null;

    if (!empty($_POST['component']))
        $xc['component'] = 'components/' . clearData($_POST['component'], 'get') . '/';
    
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/modules/' . $xc['module'] . '/' . $xc['component'] . 'ajax.php'))
        require_once $_SERVER['DOCUMENT_ROOT'] . '/modules/' . $xc['module'] . '/' . $xc['component'] .'ajax.php';

    exit();
}
// ----------------------------------------------------------------------------------------------------------------------

// собираем css и js файлы со всех модулей и объединяем их в один (1 css и 1 js файл)
$xc['style'] = get_file('css', $xc['css'], $xc['update']);
$xc['scripts'] = get_file('js', $xc['js'], $xc['update']);
// ---------------------------------------------------------------------------------------------------------------------

// если в url не указано название модуля, то по умолчанию загружается модуль главной страницы
if (empty($_GET['mod']))
    $xc['module'] = 'main';
    
else
    $xc['module'] = clearData($_GET['mod'], 'get');
// ---------------------------------------------------------------------------------------------------------------------

// если указанный в url модуль не существует, то по умолчанию загружается модуль главной страницы
if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/modules/' . $xc['module'] .'/index.php')) {
      $xc['module'] = 'main';
}
// ---------------------------------------------------------------------------------------------------------------------

// определение компонента модуля
$xc['component'] = null;

if (!empty($_GET['com']) && is_numeric($_GET['com']) == false)
  $xc['component'] = clearData($_GET['com'],'guid');

// ---------------------------------------------------------------------------------------------------------------------

// формируем меню
require_once $_SERVER['DOCUMENT_ROOT'] . "/modules/menu/index.php";
// ---------------------------------------------------------------------------------------------------------------------

// проверяем - зарегистрирован пользователь или нет
require_once $_SERVER['DOCUMENT_ROOT'] . "/modules/login/index.php";
// ---------------------------------------------------------------------------------------------------------------------


// подключение файлов модуля
if (!empty($xc['component'])) {
    $xc['component'] = 'components/' . $xc['component'] . '/';

    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/modules/' . $xc['module'] . '/' . $xc['component'] . 'index.php')) {
        $xc['component'] = 'components/component/';

        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/modules/' . $xc['module'] . '/' . $xc['component'] . 'index.php'))
           exit(header('Location: /'));
    }
}

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/modules/' . $xc['module'] . '/' . $xc['component'] . 'functions.php'))
    require_once $_SERVER['DOCUMENT_ROOT'] . '/modules/' . $xc['module'] . '/' . $xc['component'] . 'functions.php';

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/modules/' . $xc['module'] . '/' . $xc['component'] . 'index.php'))
    require_once $_SERVER['DOCUMENT_ROOT'] . '/modules/' . $xc['module'] . '/' . $xc['component'] . 'index.php';

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/modules/' . $xc['module'] . '/' . $xc['component'] . 'config.php'))
    require_once $_SERVER['DOCUMENT_ROOT'] . '/modules/' . $xc['module'] . '/' . $xc['component'] . 'config.php';

// html шаблон модуля
ob_start();

if (MOBILE == true && file_exists($_SERVER['DOCUMENT_ROOT'] . '/modules/' . $xc['module'] .'/' . $xc['component'] . 'mob.inc.php'))
    require_once $_SERVER['DOCUMENT_ROOT'] . '/modules/' . $xc['module'] . '/' . $xc['component'] . 'mob.inc.php';

else
    require_once $_SERVER['DOCUMENT_ROOT'] . '/modules/' . $xc['module'] . '/' . $xc['component'] . 'tmp.inc.php';

$xc['content'] = ob_get_clean();
// --------------------------------------------------------------------------------------------------------------

$xc['head'] = null;

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/modules/' . $xc['module'] . '/' . $xc['component'] . 'head.php'))
    $xc['head'] .= file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/modules/' . $xc['module'] .'/' . $xc['component'] . 'head.php');
// ---------------------------------------------------------------------------------------------------------------------

// подключение главного шаблона

if (MOBILE == true && file_exists($_SERVER['DOCUMENT_ROOT'] . '/mob.inc.php'))
    require_once $_SERVER['DOCUMENT_ROOT'] . '/mob.inc.php';

else
    require_once $_SERVER['DOCUMENT_ROOT'] . '/tmp.inc.php';
// ---------------------------------------------------------------------------------------------------------------------

?>