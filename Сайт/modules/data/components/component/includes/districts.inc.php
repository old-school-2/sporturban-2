<div class="row">
<div class="col-12">
<div class="card m-b-30">
<div class="card-body" id="jsDataAjaxLoadDiv2">

<?if($data!=false):?>

<div id="jsDataAjaxLoadDiv">
<?require_once $_SERVER['DOCUMENT_ROOT'].'/modules/data/components/component/includes/districtsList.inc.php';?>
</div>

<form method="post" action="" id="form_<?=$map[0]['data_table'];?>" style="display: none;">
<input type="hidden" name="module" value="data" />
<input type="hidden" name="component" value="component" />
<input type="hidden" name="ajaxLoad" value="jsDataAjaxLoadDiv" />
<input type="hidden" name="opaco" value="1" />
<input type="hidden" name="scroll" value="jsDataAjaxLoadDiv2" />
<input type="hidden" name="page" id="jsClickPage" value="" />
<input type="hidden" name="filter" id="jsClickFilter" value="" />
<button class="send_form" id="<?=$map[0]['data_table'];?>"></button>
</form>


<?endif;?>                                   
</div>
</div>
</div> <!-- end col -->
</div> <!-- end row -->