<?php defined('DOMAIN') or exit(header('Location: /'));

// всплывающее окно для добавления документов
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_addDocsPopup') {
    
       ob_start();
       require_once $_SERVER['DOCUMENT_ROOT'].'/modules/docs/includes/addDocsForm.inc.php';
       $mess = ob_get_clean();
       
       if (MOBILE == true) {
            $html = popup_window($mess,'90%','90%',5500);
        }
        
        else {
           $html = popup_window($mess,500,250,5500); 
        }
        
       exit($html);
}
// --------------------------------------------------------------------------------------------------------------

// добавление документа
if (isset($_POST['form_id']) && $_POST['form_id'] == 'form_addDocument') {
      
      /*
      ob_start();
      print_r($_FILES);
      $h = ob_get_clean();
      
      $html = popup_window($h,400,200,6000); 
            exit($html);
      */
      
      $name = clearData($_POST['name']);
      
      if (empty($_FILES['file-0']['name'])) {
        $html = popup_window('Вы не указали документ для загрузки',400,200,6000); 
        exit($html);
      }
        
      $nameDoc = time(). '.' . substr($_FILES['file-0']['name'], strrpos($_FILES['file-0']['name'], '.') + 1);
        
      $e = save_document($_FILES['file-0']['name'], $_FILES['file-0']['tmp_name'], $nameDoc, 'files/');

      if ($e !== true || !file_exists($_SERVER['DOCUMENT_ROOT'] .'/files/' . $nameDoc)) {
         $html = popup_window('Не получается загрузить документ. ' . $e,400,200,6000); 
         exit($html);
      }
           
      $document = $nameDoc;
      
      $add = db_query("INSERT INTO mos_documents (
      name,
      document,
      date
      ) VALUES (
      '".$name."',
      '".$document."',
      '".date("Y-m-d")."'
      )","i");
      
      if (intval($add) == 0) {
         $html = popup_window('Не получается загрузить документ. Ошибка: ' . $add,400,200,6000); 
         exit($html);
      }
      
      // вытаскиваем список всех документов
      $list = db_query("SELECT * FROM mos_documents ORDER BY id DESC");
      
      ob_start();
      require_once $_SERVER['DOCUMENT_ROOT'].'/modules/docs/includes/documentsList.inc.php';
      $html = ob_get_clean();
      
      exit($html);
    
}
// --------------------------------------------------------------------------------------------------------------