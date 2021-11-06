<?php defined('DOMAIN') or exit(header('Location: /'));?>
  <div style="padding: 20px;">
  
  <form method="post" action="" id="form_addDocument">
  <div>
  <label class="">Название документа</label>
  <div class="">
    <input class="form-control" name="name@" type="text" value="" id="example-text-input" />
  </div>
  </div>
  
  <div style="margin-top: 20px;">
    <input type="file" name="" id="addDocument_file" />
  
  
  <div style="margin-top: 20px;">
    <input type="hidden" name="module" value="docs" />
    <input type="hidden" name="component" value="" />
    <input type="hidden" name="loadImg" value="1" />
    <input type="hidden" name="ajaxLoad" value="jsDocumentsList" />
    <input type="hidden" name="closeWindow" value="1" />
    <input type="hidden" name="ok" value="Документ загружен!" />
    
    <button type="button" id="addDocument" class="send_form btn btn-primary waves-effect waves-light">Добавить документ</button>
  </div>
  </form>
  
  </div>                    