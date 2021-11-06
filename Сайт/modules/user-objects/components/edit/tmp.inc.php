<div class="page-content-wrapper ">

  <div class="container-fluid">
    
    <div class="row">
      <div class="col-sm-12" style="padding: 10px 43px;">
        <div class="float-right page-breadcrumb">
          
          <a class="jsDownloadFile" href="<?=DOMAIN?>/modules/user-objects/components/print/pdf.php?obj=<?=$obj_id;?>">
            <div style="margin-top: 10px;" class="btn btn-primary active">Отчёт PDF</div>
          </a>
          
        </div>
        
        <h5 class="page-title" id="jsUpdateTitle"><?=$obj[0]['object']?></h5>
      </div>
    </div>
  
    <div class="row" style="padding: 0 30px;">
      <div class="col-lg-6">
      
      <div id="map" style="height: 545px;"></div>
      <script type="text/javascript">
      window.onload = function () {
        var mapConfig = {
            center: [<?=$obj[0]['lat']?>,<?=$obj[0]['lng']?>],
            zoom: 16
        };

        ymaps.ready(function () {
            var rgkhMap = new ymaps.Map('map', mapConfig),
                balloonContentBody = "<?=$obj[0]['object']?>",
                balloonContentHeader = "<?=$obj[0]['address']?>",
                balloonContent = '<h3>' + balloonContentHeader + '</h3><p>' + balloonContentBody + '</p>';

            rgkhMap.controls.remove('mapTools')
                    .remove('geolocationControl')
                    .remove('searchControl')
                    .remove('trafficControl')
                    .remove('rulerControl');

            var myPlacemark = new ymaps.Placemark(
                    [<?=$obj[0]['lat']?>,<?=$obj[0]['lng']?>],
                    {balloonContent: balloonContent}
                );
                rgkhMap.geoObjects.add(myPlacemark);
                    });
    }
    </script>
      
      
      </div>
      
      <div class="col-lg-6">
        <div class="card m-b-30">
          <div class="card-body">
            <h4 class="mt-0 header-title">Редактирование объекта</h4>
            
            <form action="#" novalidate="" method="post" id="form_editObject">
            <div class="form-group">
              <label>Название объекта</label>
              <div>
               <input type="text" class="form-control" name="object@" value="<?=$obj[0]['object'];?>" placeholder="например Дворец Спорта" />
              </div>
            </div>
            
            <div class="form-group">
              <label>Адрес</label>
              <div>
                <input type="text" class="form-control" name="address" value="<?=$obj[0]['address'];?>" placeholder="" />
              </div>
            </div>
            
            <div class="form-group">
              <label>Доступность</label>
              <div>
                <select name="availability_id@" class="form-control">
                  <?foreach($avlArr as $avl_id=>$avl):?>
                  <option value="<?=$avl_id;?>"<?if($avl_id==$obj[0]['availability_id']):?> selected="selected"<?endif;?>><?=$avl;?></option>
                  <?endforeach;?>
                </select>
              </div>
            </div>
            
            <div class="form-group">
              <label>Ведомственная организация</label>
              <div>
                <select name="org_id" class="form-control">
                  <option value="">Выбрать</option>
                  <?if(!empty($orgList)):?>
                  <?foreach($orgList as $val):?>
                  <option value="<?=$val['org_id'];?>"<?if($val['org_id']==$obj[0]['org_id']):?> selected="selected"<?endif;?>><?=$val['org_name'];?></option>
                  <?endforeach;?>
                  <?endif;?>
                </select>
              </div>
            </div>
            
            <div class="form-group">
              <label>Виды спорта</label>
              <select id="select2" name="sports[]" multiple="multiple" class="form-control js-example-basic-single">
                    <?foreach ($spk as $spz):?>
                    <option value="<?=$spz['id'];?>"<?if(in_array($spz['id'],$objectSports)):?> selected="selected"<?endif;?>><?=$spz['smile_html'];?> &nbsp;<?=$spz['type'];?></option>
                    <?endforeach;?>
                </select>
            </div>
            
            <div class="form-group m-b-0">
              <div>
                <input type="hidden" name="module" value="user-objects" />
                <input type="hidden" name="component" value="edit" />
                <input type="hidden" name="obj_id" value="<?=$obj_id;?>" />
                <input type="hidden" name="callbackFunc" value="jsEditObjectFunc" />
                <input type="hidden" name="ok" value="Изменения сохранены!" />
                <button type="button" id="editObject" class="send_form btn btn-primary waves-effect waves-light">
                  Редактировать
                </button> 
              </div>
            </div>
        </form>
        </div>
        </div>
        </div>
    </div>
    
    <div id="jsUpdateSportZoneList">
    <?=$sportsList;?>
    </div>
      
     <div class="row" style="padding: 0 30px;">
    
      <div class="col-xl-3 col-md-6">
        <div class="card mini-stat m-b-30">
          <div class="p-3 bg-primary text-white">
            <div class="mini-stat-icon"><i class="mdi mdi-cube-outline float-right mb-0"></i></div>
            <h6 class="text-uppercase mb-0">Площадь м²</h6>
          </div>
          
        <div class="card-body">
          <div class="mt-0 text-muted">
             <?if(!empty($sportzoneArea)):?>
             <h3 class="m-0"><?=number_format($sportzoneArea,0,'',' ');?></h3>
             <?else:?>
             <span class="ml-2 text-muted">Не указаны площади спортзон</span>
             <div style="height: 7px;"></div>
             <?endif;?>
          </div>
        </div>
       </div>
      </div>
      
      <div class="col-xl-3 col-md-6">
        <div class="card mini-stat m-b-30">
          <div class="p-3 bg-primary text-white">
            <div class="mini-stat-icon"><i class="mdi mdi-account float-right mb-0"></i></div>
            <h6 class="text-uppercase mb-0">Население</h6>
          </div>
          
        <div class="card-body">
          <div class="mt-0 text-muted">
             <h3 class="m-0"><?=number_format($obj[0]['peoples'],0,'',' ');?></h3>
          </div>
        </div>
       </div>
      </div>
      
      <div class="col-xl-3 col-md-6">
        <div class="card mini-stat m-b-30">
          <div class="p-3 bg-primary text-white">
            <div class="mini-stat-icon"><i class="mdi mdi-account-multiple-plus float-right mb-0"></i></div>
            <h6 class="text-uppercase mb-0">Целевая аудитория</h6>
          </div>
          
        <div class="card-body">
          <div class="mt-0 text-muted">
            <?if(!empty($allTargetPeoples)):?>
             <h3 class="m-0"><?=number_format($allTargetPeoples,0,'',' ');?></h3>
            <?else:?>
            <span class="ml-2 text-muted">Не указаны виды спорта</span>
            <div style="height: 7px;"></div>
            <?endif;?>
          </div>
        </div>
       </div>
      </div>
      
      <div class="col-xl-3 col-md-6">
        <div class="card mini-stat m-b-30">
          <div class="p-3 bg-primary text-white">
            <div class="mini-stat-icon"><i class="mdi mdi-buffer float-right mb-0"></i></div>
            <h6 class="text-uppercase mb-0">Доступность</h6>
          </div>
          
        <div class="card-body">
          <div class="mt-0 text-muted">
             <h3 class="m-0"><?=$avlArr2[$obj[0]['availability_id']]?></h3>
          </div>
        </div>
       </div>
      </div>
      
    </div>    
     
   <div class="row" style="padding: 0 30px;">
      <div class="col-sm-12">
          <?if (!empty($obj[0]['district_id'])):?>
        <iframe src="https://datalens.yandex/7qfoptu4impg0?112813ca-ac3f-4e39-a3dc-fa7627c2b8cf=<?=$obj[0]['district_id'];?>" width="33%" height="500" frameborder="0"></iframe>
        <iframe src="https://datalens.yandex/9sj9tr6qg6n82?112813ca-ac3f-4e39-a3dc-fa7627c2b8cf=<?=$obj[0]['district_id'];?>" width="33%" height="500" frameborder="0"></iframe>
        <iframe src="https://datalens.yandex/7qhccssjk7u00?112813ca-ac3f-4e39-a3dc-fa7627c2b8cf=<?=$obj[0]['district_id'];?>" width="33%" height="500" frameborder="0"></iframe>
        <?endif;?>

      </div>
      
   </div>
   
   <?if (!empty($obj[0]['district_id'])):?>
   <div class="row" style="padding: 20px 30px;">
      <div class="col-sm-12">
         <iframe src="https://datalens.yandex/n6xjcikx9ggkg?112813ca-ac3f-4e39-a3dc-fa7627c2b8cf=<?=$obj[0]['district_id'];?>&<?=$sports_guid;?>" width="99.5%" height="800" frameborder="0"></iframe>
    </div>
   </div>
   <?endif;?>


      <?if (!empty($obj[0]['district2'])):?>
      <?if(!empty($sports_name)):?>
          <div class="row" style="padding: 20px 30px;">
              <div class="col-sm-12">
                  <?  $url_state = '{"map":{"center":[' . $obj[0]['lat'] . ',' . $obj[0]['lng'] . '],"zoom":14},"widgets":{"89ec7f92-f3c2-469a-99a6-f66987adb91a":{"normalized":true},"a3a454e3-8200-4dac-bb4d-7f44e1cddb3f":{"acceptedCategories":[' . $sports_name . ']},"3ba4303a-4a10-4cd7-b1a6-396906c9ecae":{"acceptedCategories":["' . $obj[0]['district2'] . '"]}}}';
                      $url_state = urlencode($url_state);
                  ?>
                  <iframe src="https://garbuzenko.carto.com/builder/ce1ae2f7-9699-406e-a86f-dc58966bf8c2/embed?state=<?=$url_state;?>" width="99.5%" height="800" frameborder="0"></iframe>
              </div>
          </div>
          <?endif;?>
      <?endif;?>
</div>