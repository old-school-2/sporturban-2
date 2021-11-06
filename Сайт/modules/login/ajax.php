<?php defined('DOMAIN') or exit(header('Location: /'));

// всплывающее окно с формой авторизации
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_getLoginPopup') {
    
       ob_start();
       require_once $_SERVER['DOCUMENT_ROOT'].'/modules/login/includes/modalLogin.inc.php';
       $mess = ob_get_clean();
       
       if (MOBILE == true) {
            $html = popup_window($mess,'90%','90%',5500);
        }
        
        else {
           $html = popup_window($mess,360,335,5500); 
        }
        
       exit($html);
}
// --------------------------------------------------------------------------------------------------------------

// авторизация
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_getLogin') {
    
    $username = clearData($_POST['username']);
    $pass = encrypt_pass($_POST['pass']);
    
    $a = db_query("SELECT id  
    FROM mos_users 
    WHERE username='".$username."' 
    AND pass='".$pass."' 
    LIMIT 1");
    
    if ($a == false) {
        
        $mess = 'Неправильный логин или пароль';
        
        if (MOBILE == true) {
           $html = popup_window($mess,'90%',200,6000);
        }
        
        else {
           $html = popup_window($mess); 
        }
        
        exit($html);
        
    }
    
    // если такой пользователь есть
    $hash = get_hash($username);
    
    // добавляем hash строку в базу
    $upd = db_query("UPDATE mos_users 
    SET hash='".$hash."' 
    WHERE id=".$a[0]['id']." 
    LIMIT 1","u");
    
    if ($upd == true) {
        
        // если поставлена галочка в пункте "запомнить меня", то ставим куки на 30 дней
        setcookie('hash', $hash, time() + 3600 * 24, '/');
          
        // переадресуем на главную
        $r = json_encode( array( 0 => 'redirect', 1 => $_POST['url'] ) );
        exit($r);
        
    }
      
}
// --------------------------------------------------------------------------------------------------------------