<?

if ($_POST['form_id'] == 'form_updateMap'){

	// ���������� ������ ��������� ������ �� ������������� ����� � ������ ������������ POST ����������
    include($_SERVER['DOCUMENT_ROOT'].'/modules/map2/include/data.php');
    // ���������� ������ ������.�����
    include($_SERVER['DOCUMENT_ROOT'].'/modules/map2/include/map.php');

}

// ������� ������ ��������� (����� ��� ���������� ������� � ����)
if ($_POST['form_id'] == 'form_saveCircle' and $_SESSION['user_id'] > 0){
	
    $radius = clearData($_POST['radius']);
    $lng = clearData($_POST['lng']);
    $lat = clearData($_POST['lat']);
    
    if (!empty($radius) and !empty($lng) and !empty($lat)) {
    
      ob_start();
      require_once $_SERVER['DOCUMENT_ROOT'].'/modules/user-objects/includes/addObjectForm.inc.php';
      $mess = ob_get_clean();
    
      if (MOBILE == true) {
        $html = popup_window($mess,'90%','90%',5500);
      }
        
      else {
        $html = popup_window($mess,360,235,5500); 
      }
        
      exit($html);
    
    }

}

?>