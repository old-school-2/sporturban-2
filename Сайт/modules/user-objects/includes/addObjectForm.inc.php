<?php defined('DOMAIN') or exit(header('Location: /'));?>

<div class="card" id="jsAddObjectWindow">
   <div class="card-body">
                
   <h4 class="text-muted text-center font-18"><b>Добавление объекта</b></h4>
                
   <div class="p-2">
                                            
     <form class="form-horizontal m-t-20" action="" method="post" id="form_addObject">
                
        <div class="form-group row">
          <div class="col-12">
          <input class="form-control" type="text" name="object@" required="" placeholder="Название объекта" />
          </div>
        </div>
                                                   
        <input type="hidden" name="module" value="user-objects" />
        <input type="hidden" name="component" value="" />
        
        <input type="hidden" name="lng" value="<?=$lng;?>" />
        <input type="hidden" name="lat" value="<?=$lat;?>" />
        <input type="hidden" name="radius" value="<?=$radius;?>" />
        <input type="hidden" name="availability_id" value="<?=$availability_id;?>" />
        
        <input type="hidden" name="ok" value="Объект добавлен!" />
        <input type="hidden" name="closeThisWindow" value="jsAddObjectWindow" />
        <div class="form-group text-center row m-t-20">
           <div class="col-12">
             <button id="addObject" style="background-color: #EB1D37;" class="send_form btn btn-primary btn-block waves-effect waves-light" type="submit">
                Добавить
             </button>
           </div>
        </div>
     </form>
   </div>
                
   </div>
</div>