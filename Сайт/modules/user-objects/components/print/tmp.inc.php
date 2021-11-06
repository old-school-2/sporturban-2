<div class="page-content-wrapper ">

  <div class="container-fluid">
  
    <div class="row" style="padding: 30px 30px;">
      <div class="col-lg-6">
      
      <div id="map" style="height: 450px;"></div>
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
          <div class="card-body" style="height: 450px;">
            
            <form action="#" novalidate="" method="post" id="form_editObject">
            <div class="form-group">
              <label>Название объекта</label>
              <div>
               <strong><?=$obj[0]['object'];?></strong>
              </div>
            </div>
            
            <div class="form-group">
              <label>Адрес</label><br />
                <strong><?=$obj[0]['address'];?></strong>
                <?if(!empty($obj[0]['district'])):?>
                <br /><strong><?=$obj[0]['district'];?></strong>
                <?endif;?>
                <?if(!empty($obj[0]['adm_area'])):?>
                <br /><strong><?=$obj[0]['adm_area'];?></strong>
                <?endif;?>
            </div>
            
            <div class="form-group">
              <label>Доступность</label>
              <div>
                  <?foreach($avlArr as $avl_id=>$avl):?>
                  <?if($avl_id==$obj[0]['availability_id']):?><strong><?=$avl;?></strong><?endif;?>
                  <?endforeach;?>
              </div>
            </div>
            
            <?if(!empty($orgList)):?>
            <div class="form-group">
              <label>Ведомственная организация</label>
              <div>
                <?foreach($orgList as $val):?>
                <?if($val['org_id']==$obj[0]['org_id']):?><strong><?=$val['org_name'];?></strong><?endif;?>
                <?endforeach;?>
              </div>
            </div>
            <?endif;?>
            
            <div class="form-group">
              <label>Координаты</label>
              <div>
                  <strong>Широта: <?=$obj[0]['lat'];?></strong><br />
                  <strong>Долгота: <?=$obj[0]['lng'];?></strong>
              </div>
            </div>

        </form>
        </div>
        </div>
        </div>
    </div>
    
    <div style="margin-top: -40px;">
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


  
</div>