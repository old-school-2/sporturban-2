<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
  <thead>
    <tr>
      <th>Объект</th>
      <th>Район</th>
      <th>Адрес</th>
      <th>Доступность</th>
      <th>Добавил</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
            
  <tbody>
    <?foreach($objList as $b):?>
    <tr id="jsRemoveTr<?=$b['object_id'];?>">
      <td><?=$b['object']?></td>
      <td><?=$b['district']?></td>
      <td><?=$b['address']?></td>
      <td><?=$b['availability']?></td>
      <td><?=$b['username'];?></td>
      <td>
      
      <a href="<?=DOMAIN;?>/user-objects/edit/<?=$b['object_id'];?>" target="_blank">
      <div class="btn btn-primary" style="width: 97%; font-size: 13px;">
      Редактировать
      </div>
      </a>
      
      <form method="post" action="" id="form_jsDelObject<?=$b['object_id'];?>">
      <input type="hidden" name="module" value="user-objects" />
      <input type="hidden" name="component" value="" />
      <input type="hidden" name="object_id" value="<?=$b['object_id'];?>" />
      <input type="hidden" name="closeThisWindow" value="jsAddObjectWindow" />
      <input type="hidden" name="removeElement" value="jsRemoveTr<?=$b['object_id'];?>" />
      <input type="hidden" name="opaco" value="1" />
      <button type="button" name="del" data-text="Вы уверены, что хотите удалить этот объект?" style="width: 97%; font-size: 13px; margin-top: 10px;" id="jsDelObject<?=$b['object_id'];?>" class="send_form btn btn-light waves-effect">Удалить</button>
      </form>
       
      </td>
    </tr>
    <?endforeach;?>                                        
  </tbody>
</table>