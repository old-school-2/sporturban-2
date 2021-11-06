<div class="page-content-wrapper ">

  <div class="container-fluid" style="background-color: #fff; min-height: 800px;">
    
    <?if(!empty($_SESSION['user_id']) && $_SESSION['group_id']==1):?>
    <!--
    <br />
    <form method="post" action="" id="form_addDocsPopup" class="docsAddButtonForm">
      <input type="hidden" name="module" value="docs" />
      <input type="hidden" name="component" value="" />
      
      <button class="send_form btn btn-primary waves-effect waves-light" id="addDocsPopup">Добавить документ</button>
    </form>
    -->
    <?endif;?>
    
    <div id="jsDocumentsList">
      <?require_once $_SERVER['DOCUMENT_ROOT'].'/modules/docs/includes/documentsList.inc.php';?>
    </div>
    
    <iframe style="margin-top: -80px;" src="https://datalens.yandex/gwwyeihwms529" width="100%" height="920" frameborder="0"></iframe>

  </div><!-- container fluid -->

</div> <!-- Page content Wrapper -->