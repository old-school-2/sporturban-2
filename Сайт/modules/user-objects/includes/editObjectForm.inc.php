<?php defined('DOMAIN') or exit(header('Location: /'));?>

<div class="card" id="jsAddObjectWindow">
   <div class="card-body">
                                            
     <form class="form-horizontal m-t-20" action="" method="post" id="form_editThisObject">
                
        <div class="form-group">
          <label style="font-weight: bold !important;">Название объекта</label>
          <input class="form-control" type="text" name="object@" value='<?=$obj[0]['object'];?>' required="" placeholder="Название объекта" />
        </div>
                
        <div class="form-group">
          <label style="font-weight: bold !important;">Площадь, м2</label>
          <input class="form-control" type="text" name="area@" value="<?=$obj[0]['area'];?>" required="" placeholder="Площадь, м2" />
        </div>
        
        <div class="form-group">
          <label style="font-weight: bold !important;">Доступность</label>
          <select class="form-control" name="avl_id@">
            <?foreach($avl as $val):?>
            <?if(substr($val['km'],-1) == '0'):?>
            <?$val['km'] = substr($val['km'],0,-2);?>
            <?endif;?>
            <option value="<?=$val['id'];?>"<?if($val['id']==$obj[0]['availability_id']):?> selected="selected"<?endif;?>><?=$val['availability'];?> (<?=$val['km']?> км.)</option>
            <?endforeach;?>
          </select>
          
          
        </div>
                                                
        <input type="hidden" name="module" value="user-objects" />
        <input type="hidden" name="component" value="" />
        <input type="hidden" name="object_id" value="<?=$obj[0]['id'];?>" />
        <input type="hidden" name="closeThisWindow" value="jsAddObjectWindow" />
        <input type="hidden" name="callbackFunc" value="jsEditObjectFunc" />
        <input type="hidden" name="opaco" value="1" />
        <div class="form-group text-center row m-t-20">
           <div class="col-12">
             <button id="editThisObject" style="background-color: #EB1D37;" class="send_form btn btn-primary btn-block waves-effect waves-light" type="submit">
                Редактировать
             </button>
           </div>
        </div>
     </form>
                
   </div>
</div>