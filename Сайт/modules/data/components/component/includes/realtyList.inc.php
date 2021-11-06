<div class="row">
<div class="col-sm-12 col-md-2">
 <div class="dataTables_info" id="datatable-buttons_info" role="status" aria-live="polite">
    Всего домов: <strong><?=number_format($col,0,'',' ');?></strong>
 </div>
</div>

<div class="col-sm-12 col-md-2">
<div class="dt-buttons btn-group" style="width: 100%;">
<a style="padding: .290rem .75rem;" class="btn btn-secondary buttons-copy buttons-html5" tabindex="0" aria-controls="datatable-buttons" href="<?=DOMAIN;?>/files/opendata/mos_realty.csv.zip"><span>Скачать CSV</span></a>
</div>
</div>

<div class="col-sm-12 col-md-8">
<form method="post" action="" id="form_jsHousesSearch">
  <div id="datatable-buttons_filter" class="dataTables_filter">
  <div class="row">
  
  
  <div class="col-sm-12 col-md-8">
    <label style="width: 100%;"><input type="text" id="houseAddress" name="address" value="<?=$address;?>" class="form-control form-control-sm jsClear jsClearInput jsSelectList" placeholder="Поиск по адресу" aria-controls="datatable-buttons"></label>
    
    <input type="hidden" id="houseAddress_mod" value="data" />
    <input type="hidden" id="houseAddress_com" value="component" />
    <input type="hidden" id="houseAddress_arr" value="" />
    <input type="hidden" name="house_id" id="houseAddress_id" class="jsClear" value="<?=$house_id;?>" />
     
    <div id="houseAddress2" class="tmpAjaxListDiv hidden" style="margin-top: -3px;"></div>

  </div>
  
  <div class="col-sm-12 col-md-4">
    <label style="width: 100%;"><input type="text" id="districtFilter" name="district" value="<?=$district;?>" class="form-control form-control-sm jsClear jsClearInput jsSelectList" placeholder="Поиск по району" aria-controls="datatable-buttons"></label>
    
    <input type="hidden" id="districtFilter_mod" value="data" />
    <input type="hidden" id="districtFilter_com" value="component" />
    <input type="hidden" id="districtFilter_arr" value="mos_realty" />
    <input type="hidden" name="district_id" id="districtFilter_id" class="jsClear" value="<?=$district_id;?>" />
     
    <div id="districtFilter2" class="tmpAjaxListDiv hidden" style="margin-top: -3px;"></div>
  
  </div> 
  
  <input type="hidden" name="module" value="data" />
  <input type="hidden" name="component" value="component" />
  <input type="hidden" name="ajaxLoad" value="jsDataAjaxLoadDiv" />
  <input type="hidden" name="opaco" value="1" />
  
  <button class="send_form hidden" id="jsHousesSearch"></button>
  
  </div> 
  </div>
  
  </form>
</div>

</div>

<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
  <thead>
    <tr>
      <th>Район</th>
      <th>Адрес</th>
      <th>Год постройки</th>
      <th>Кол-во квартир</th>
      <th>Площадь квартир, м<sup style="font-size: 9px;">2</sup></th>
      <th>На карте</th>
    </tr>
  </thead>
            
  <tbody>
    <?foreach($data as $b):?>
    <tr>
      <td><?=$b['district']?></td>
      <td><?=$b['address']?></td>
      <td><?=$b['built_year']?></td>
      <td><?=$b['living_quarters_count']?></td>
      <td><?=$b['area_residential']?></td>
      <td><a href="<?=DOMAIN;?>/?lat=<?=$b['lat'];?>&lng=<?=$b['lng'];?>" target="_blank">на карте</a></td>
    </tr>
    <?endforeach;?>                                        
  </tbody>
</table>
                                            
<?=$nav;?>