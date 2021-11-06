<?php defined('DOMAIN') or exit(header('Location: /'));

// выход из админки
if (!empty($_POST['exit']) or !empty($_GET['exit'])) {
    
    $a = db_query("UPDATE mos_users 
    SET hash='' 
    WHERE id='" . intval($_SESSION['user_id']) . "' 
    LIMIT 1", "u");
    
    setcookie("hash", "", time() - 9999999, "/");
    session_destroy();
    
    exit(header('Location: '.DOMAIN.$_GET['url']));
}
// -------------------------------------------------------------------------------------

// автоматическая авторизация
if (isset($_COOKIE['hash']) && !empty($_COOKIE["hash"])) {

    $hash = clearData($_COOKIE['hash'], 'guid');

    $login = db_query("SELECT id, 
    username,
    group_id,
    avatar 
    FROM mos_users   
    WHERE hash='".$hash."' 
    LIMIT 1");

    if ($login != false) {
        $_SESSION['user_id'] = $login[0]['id'];  
        $_SESSION['group_id'] = $login[0]['group_id'];  
        $_SESSION['username'] = $login[0]['username'];  
        
        if (!empty($login[0]['avatar']) && file_exists($_SERVER['DOCUMENT_ROOT'].'/img/users/'.$login[0]['avatar']))
          $_SESSION['avatar'] = $login[0]['avatar'];
          
        else
          $_SESSION['avatar'] = 'no_user2.jpg';
    }
} 
// -------------------------------------------------------------------------------------