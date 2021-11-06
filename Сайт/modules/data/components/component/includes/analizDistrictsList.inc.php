<div class="row">
<div class="col-sm-12 col-md-2">
 <div class="dataTables_info" id="datatable-buttons_info" role="status" aria-live="polite">
    Всего: <strong><?=number_format($col,0,'',' ');?></strong>
 </div>
</div>

<div class="col-sm-12 col-md-2">
<div class="dt-buttons btn-group" style="width: 100%;">
<a style="padding: .290rem .75rem;" class="btn btn-secondary buttons-copy buttons-html5" tabindex="0" aria-controls="datatable-buttons" href="<?=DOMAIN;?>/files/opendata/mos_analiz_districts.csv"><span>Скачать CSV</span></a>
</div>
</div>

<div class="col-sm-12 col-md-8">
<form method="post" action="" id="form_jsAnalizDistrictsSearch">
  <div id="datatable-buttons_filter" class="dataTables_filter">
  <div class="row">
  
  <div class="col-sm-12 col-md-6">
    <label style="width: 100%;"><input type="text" id="districtFilter" name="district" value="<?=$district;?>" class="form-control form-control-sm jsClear jsClearInput jsSelectList" placeholder="Поиск по району" aria-controls="datatable-buttons"></label>
    
    <input type="hidden" id="districtFilter_mod" value="data" />
    <input type="hidden" id="districtFilter_com" value="component" />
    <input type="hidden" id="districtFilter_arr" value="data_analiz_district" />
    <input type="hidden" name="district_id" id="districtFilter_id" class="jsClear" value="<?=$district_id;?>" />
     
    <div id="districtFilter2" class="tmpAjaxListDiv hidden" style="margin-top: -3px;"></div>
  
  </div> 
  
  <div class="col-sm-12 col-md-6">
    <label style="width: 100%;"><input type="text" id="sportType" name="sport" value="<?=$sport;?>" class="form-control form-control-sm jsClear jsClearInput jsSelectList" placeholder="Вид спорта" aria-controls="datatable-buttons"></label>
    
    <input type="hidden" id="sportType_mod" value="data" />
    <input type="hidden" id="sportType_com" value="component" />
    <input type="hidden" id="sportType_arr" value="data_analiz_district" />
    <input type="hidden" name="sport_id" id="sportType_id" class="jsClear" value="<?=$sport_id;?>" />
     
    <div id="sportType2" class="tmpAjaxListDiv hidden" style="margin-top: -3px;"></div>
  
  </div> 
  
  <input type="hidden" name="module" value="data" />
  <input type="hidden" name="component" value="component" />
  <input type="hidden" name="ajaxLoad" value="jsDataAjaxLoadDiv" />
  <input type="hidden" name="opaco" value="1" />
  
  <button class="send_form hidden" id="jsAnalizDistrictsSearch"></button>
  
  </div> 
  </div>
  
  </form>
</div>

</div>

<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
  <thead>
    <tr>
      <th>#</th>
      <th>Вид спорта</th>
      <th>Район</th>
      <th>Число активированных спортзон по доступности</th>
      <th>Удовлетворенность спортзонами</th>
    </tr>
  </thead>
            
  <tbody>
    <?foreach($data as $b):?>
    <tr>
      <td><?=$b['smile_html']?></td>
      <td><?=$b['type']?></td>
      <td><?=$b['district']?></td>
      <td><?=$b['koeff_count_zone']?></td>
      <td><?=$b['koeff_y']?></td>
    </tr>
    <?endforeach;?>                                        
  </tbody>
</table>
                                            
<?=$nav;?>