<div class="container">
    <form method="POST" action="" id="form_updateMap">
        <input type="hidden" name="module" value="map2"/>
        <input type="hidden" name="component" value=""/>
        <input type="hidden" name="ajaxLoad" value="mapUpdate"/>

        <div class="row" style="padding-top: 30px">

            <div class="col-lg-3">
                <label>Район:</label>
                <select id="select2" name="districts[]" multiple="multiple" class="form-control js-example-basic-single">
                    <?foreach ($district as $d):?>
                    <option value="<?=$d['id']?>"><?=$d['district']?></option>
                    <?endforeach;?>
                </select>
            </div>

            <div class="col-lg-3">
                <label>Ведомственная организация:</label>
                <select id="select2" name="organization[]" multiple="multiple" class="form-control js-example-basic-single">
                    <?foreach ($organization as $d):?>
                    <option value="<?=$d['org_id']?>"><?=$d['org_name']?></option>
                    <?endforeach;?>
                </select>
            </div>

            <div class="col-lg-2">
                <label>Доступность:</label>
                <select id="select2" name="availability[]" multiple="multiple" class="form-control js-example-basic-single">
                    <?foreach ($availability as $d):?>
                    <option value="<?=$d['id']?>"><?=$d['availability']?></option>
                    <?endforeach;?>
                </select>
            </div>

            <div class="col-lg-2">
                <label>Вид спорта:</label>
                <select id="select2" name="type[]" multiple="multiple" class="form-control js-example-basic-single"> <!--with-image-->
                    <?foreach ($type_sport as $d):?>
                    <option value="<?=$d['id']?>"><?=$d['type']?></option>
                    <?endforeach;?>
                </select>
            </div>
            <div class="col-lg-2">
                <label> &nbsp; </label>
                <button class="btn btn-primary send_form" id="updateMap" style="margin-top: 32px;">Найти</button>
            </div>
        </div>
    </form>
</div>

<br /><br />

<div id="mapUpdate">
<?include($_SERVER['DOCUMENT_ROOT'].'/modules/map2/include/map.php');?>
</div>