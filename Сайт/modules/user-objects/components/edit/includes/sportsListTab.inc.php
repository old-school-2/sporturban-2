<div class="row" style="padding: 20px 0;">
      <div class="col-lg-12">
<div class="card-body" style="width: 95%; margin: 0 auto; background-color: #fff;">
<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="margin: 0 auto; border-collapse: collapse; border-spacing: 0;">
  <thead>
    <tr>
      <th>#</th>
      <th>Вид спорта</th>
      <th>Популярность</th>
      <th>Население (в радиусе <?=$b['km'];?> км.)</th>
      <th>Целевая аудитория</th>
      <th>Добавил</th>
    </tr>
  </thead>
            
  <tbody>
    <?foreach($sp as $b):?>
    <tr>
      <td><?=$b['smile_html']?></td>
      <td><?=$b['type']?></td>
      <td><?=$b['popular']?></td>
      <td>
      <?if(!empty($b['peoples'])):?>
        <?=number_format($b['peoples'],0,'',' ');?>
      <?endif;?>
      </td>
      
      <td>
      <?if(!empty($b['target_peoples'])):?>
        <?=number_format($b['target_peoples'],0,'',' ');?>
      <?endif;?>
      </td>
      
      <td><?=$b['username'];?></td>
    </tr>
    <?endforeach;?>                                        
  </tbody>
</table>

</div>
</div>
</div>