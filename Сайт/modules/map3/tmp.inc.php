<div class="" style="padding: 5px 30px;">
	<!-- фильтр поиска по интерактивной карте -->
    <form method="POST" action="" id="form_updateMap" style="opacity: 0">
        <input type="hidden" name="module" value="map3"/>
        <input type="hidden" name="component" value=""/>
        <input type="hidden" name="ajaxLoad" value="mapUpdate"/>
        <input type="hidden" name="opaco" value="1"/>
        <input type="hidden" name="scroll" value="mapUpdate"/>
        <div class="row" style="padding-top: 10px">

            <div class="col-lg-3" style="margin-bottom: 20px;">
                <label>Административный округ:</label>
                <select id="select2" name="adm_area[]" multiple="multiple" class="form-control js-example-basic-single">
                    <?foreach ($mln_adm_area as $adm_id=>$adm_name):?>
                    <option value="<?=$adm_id;?>"><?=$adm_name;?></option>
                    <?endforeach;?>
                </select>
            </div>

            <div class="col-lg-3" style="margin-bottom: 20px;">
                <label>Район:</label>
                <select id="select2" name="districts[]" multiple="multiple" class="form-control js-example-basic-single">
                    <?foreach ($district as $dist_id=>$dist):?>
                    <option value="<?=$dist_id;?>"><?=$dist;?></option>
                    <?endforeach;?>
                </select>
            </div>

            <div class="col-lg-3" style="margin-bottom: 20px;">
                <label>Ведомственная организация:</label>
                <select id="select2" name="organization[]" multiple="multiple" class="form-control js-example-basic-single">
                    <?foreach ($organization as $org_id=>$org_name):?>
                    <option value="<?=$org_id;?>"><?=$org_name;?></option>
                    <?endforeach;?>
                </select>
            </div>

            <div class="col-lg-3" style="margin-bottom: 20px;">
                <label>Доступность:</label>
                <select id="select2" name="availability[]" multiple="multiple" class="form-control js-example-basic-single">
                    <?foreach ($availability as $avl_id=>$avl_name):?>
                    <option value="<?=$avl_id;?>"><?=$avl_name;?></option>
                    <?endforeach;?>
                </select>
            </div>

            <div class="col-lg-3" style="margin-bottom: 20px;">
                <label>Категория спорта:</label>
                <select id="select2" name="category[]" multiple="multiple" class="form-control js-example-basic-single">
                    <?foreach ($categories as $cat_id=>$cat_name):?>
                    <option value="<?=$cat_id;?>"><?=$cat_name;?></option>
                    <?endforeach;?>
                </select>
            </div>

            <div class="col-lg-3" style="margin-bottom: 20px;">
                <label>Вид спорта:</label>
                <select id="select2" name="type[]" multiple="multiple" class="form-control js-example-basic-single"> <!--with-image-->
                    <?foreach ($type_sport as $sport_id=>$sport_name):?>
                    <option value="<?=$sport_id;?>"><?=$sport_name;?></option>
                    <?endforeach;?>
                </select>
            </div>
            <div class="col-lg-3" style="margin-bottom: 20px;">
                <label> &nbsp; </label>
                <button class="btn btn-primary send_form" id="updateMap" style="margin-top: 30px; height: 41px; margin-left: 0px;">Найти</button>
            </div>
        </div>
    </form>
</div>

<div id="mapUpdate">
<?require $_SERVER['DOCUMENT_ROOT'].'/modules/map3/includes/map.inc.php';?>
</div>