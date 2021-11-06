<div class="row">
<div class="col-sm-12 col-md-3">
 <div class="dataTables_info" id="datatable-buttons_info" role="status" aria-live="polite">
    Всего видов спорта: <strong><?=number_format($col,0,'',' ');?></strong>
 </div>
</div>

<div class="col-sm-12 col-md-3">
<div class="dt-buttons btn-group" style="width: 100%;">
<a style="padding: .290rem .75rem;" class="btn btn-secondary buttons-copy buttons-html5" tabindex="0" aria-controls="datatable-buttons" href="<?=DOMAIN;?>/files/opendata/type_sport.csv"><span>Скачать CSV</span></a>
</div>
</div>

<div class="col-sm-12 col-md-6">
<form method="post" action="" id="form_jsCatSearch">
  <div id="datatable-buttons_filter" class="dataTables_filter">
  <div class="row">
  
  <div class="col-sm-12 col-md-12">
    <label style="width: 100%;"><input type="text" id="sportCat" name="sport_cat" value="<?=$sport_cat;?>" class="form-control form-control-sm jsClear jsClearInput jsSelectList" placeholder="Категория" aria-controls="datatable-buttons"></label>
    
    <input type="hidden" id="sportCat_mod" value="data" />
    <input type="hidden" id="sportCat_com" value="component" />
    <input type="hidden" id="sportCat_arr" value="" />
    <input type="hidden" name="sport_cat_id" id="sportCat_id" class="jsClear" value="<?=$sport_cat_id;?>" />
     
    <div id="sportCat2" class="tmpAjaxListDiv hidden" style="margin-top: -3px;"></div>
  
  </div> 
  
  <input type="hidden" name="module" value="data" />
  <input type="hidden" name="component" value="component" />
  <input type="hidden" name="ajaxLoad" value="jsDataAjaxLoadDiv" />
  <input type="hidden" name="opaco" value="1" />
  
  <button class="send_form hidden" id="jsCatSearch"></button>
  
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
      <th>Категория</th>
      <th>Популярность</th>
      <th>Сезонность</th>
    </tr>
  </thead>
            
  <tbody>
    <?foreach($data as $b):?>
    <tr>
      <td><?=$b['smile_html']?></td>
      <td><?=$b['type']?></td>
      <td><?=$b['category']?></td>
      <td><?=$b['popular']?></td>
      <td><?=$b['season']?></td>
    </tr>
    <?endforeach;?>                                        
  </tbody>
</table>
                                            
<?=$nav;?>