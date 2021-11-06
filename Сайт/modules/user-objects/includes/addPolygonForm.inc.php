<?php defined('DOMAIN') or exit(header('Location: /'));?>

<div class="card" id="jsAddObjectWindow">
   <div class="card-body">
                
   <h4 class="text-muted text-center font-18"><b>Добавление зоны</b></h4>
                
   <div class="p-2">
                                            
     <form class="form-horizontal m-t-20" action="" method="post" id="form_addPolygonObject">
                
        <div class="form-group row">
          <div class="col-12">
          <input class="form-control" type="text" name="name@" required="" placeholder="назовите выбранную зону" />
          </div>
        </div>
                                                   
        <input type="hidden" name="module" value="user-objects" />
        <input type="hidden" name="component" value="" />
        
        <input type="hidden" name="polygonArea" value="<?=$polygonArea;?>" />
        <input type="hidden" name="population" value="<?=$population;?>" />
        <input type="hidden" name="targetPeoples" value="<?=$targetPeoples;?>" />
        <input type="hidden" name="sportzones" value="<?=$sportzones;?>" />
        <input type="hidden" name="sportzonesArea" value="<?=$sportzonesArea;?>" />
        <input type="hidden" name="sat" value="<?=$sat;?>" />
        <input type="hidden" name="coordinates" value="<?=$coordinates;?>" />
        
        <input type="hidden" name="ok" value="Выбранная зона добавлена!" />
        <input type="hidden" name="closeThisWindow" value="jsAddObjectWindow" />
        <div class="form-group text-center row m-t-20">
           <div class="col-12">
             <button id="addPolygonObject" style="background-color: #EB1D37;" class="send_form btn btn-primary btn-block waves-effect waves-light" type="submit">
                Добавить
             </button>
           </div>
        </div>
     </form>
   </div>
                
   </div>
</div>