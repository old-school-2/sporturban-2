<?

if ($_POST['form_id'] == 'form_updateMap'){

	// подключаем модуль получения данных по интерактивной карте с учетом отправленных POST параметров
    include($_SERVER['DOCUMENT_ROOT'].'/modules/map4/include/data.php');
    // подключаем модуль Яндекс.Карты
    include($_SERVER['DOCUMENT_ROOT'].'/modules/map4/include/map.php');

}

// добавление выбранной пользователем зоны
if (isset($_POST['form_id']) and $_POST['form_id'] == 'form_savePolygon' and $_SESSION['user_id'] > 0){
	 
      $polygonArea = clearData($_POST['polygonArea'],'area');
      $population = intval($_POST['polygonPopulation']);
      $targetPeoples = intval($_POST['polygonTarget']);
      $sportzones = intval($_POST['polygonSportzones']);
      $sportzonesArea = clearData($_POST['polygonSportzonesArea'],'area');
      $sat = clearData($_POST['polygonSat'],'area');
      $coordinates = $_POST['polygonCoords'];
      
      ob_start();
      require_once $_SERVER['DOCUMENT_ROOT'].'/modules/user-objects/includes/addPolygonForm.inc.php';
      $mess = ob_get_clean();
    
      if (MOBILE == true) {
        $html = popup_window($mess,'90%','90%',5500);
      }
        
      else {
        $html = popup_window($mess,360,230,5500); 
      }
        
      exit($html);
}

// нажатие кнопки Сохранить (форма для добавления объекта в базу)
if ($_POST['form_id'] == 'form_saveCircle' and $_SESSION['user_id'] > 0){
	 
    $radius = intval($_POST['radius']);
    $lng = clearData($_POST['lng']);
    $lat = clearData($_POST['lat']);
    $availability_id = intval($_POST['availability']);
    
    if (!empty($radius) and !empty($lng) and !empty($lat)) {
      
    
      ob_start();
      require_once $_SERVER['DOCUMENT_ROOT'].'/modules/user-objects/includes/addObjectForm.inc.php';
      $mess = ob_get_clean();
    
      if (MOBILE == true) {
        $html = popup_window($mess,'90%','90%',5500);
      }
        
      else {
        $html = popup_window($mess,360,230,5500); 
      }
        
      exit($html);
    
    }

}

?>