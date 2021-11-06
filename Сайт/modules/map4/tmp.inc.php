<div class="" style="padding: 5px 30px;">
	<!-- фильтр поиска по интерактивной карте -->
    <form method="POST" action="" id="form_updateMap" style="opacity: 0">
        <input type="hidden" name="module" value="map4"/>
        <input type="hidden" name="component" value=""/>
        <input type="hidden" name="ajaxLoad" value="mapUpdate"/>
        <input type="hidden" name="opaco" value="1"/>
        <div class="row" style="padding-top: 10px">

            <div class="col-lg-3" style="margin-bottom: 20px;">
                <label>Административный округ:</label>
                <select id="select2" name="adm_area[]" multiple="multiple" class="form-control js-example-basic-single">
                    <?foreach ($mln_adm_area as $d):?>
                    <option value="<?=$d['id']?>" <?if (empty($_POST['form_id']) and $d['id'] == 9):?>selected="selected"<?endif;?>><?=$d['adm_area']?></option>
                    <?endforeach;?>
                </select>
            </div>

            <div class="col-lg-3" style="margin-bottom: 20px;">
                <label>Район:</label>
                <select id="select2" name="districts[]" multiple="multiple" class="form-control js-example-basic-single">
                    <?foreach ($district as $d):?>
                    <option value="<?=$d['id']?>" <?if (empty($_POST['form_id']) and $d['id'] == 125):?>selected="selected"<?endif;?>><?=$d['district']?></option>
                    <?endforeach;?>
                </select>
            </div>

            <div class="col-lg-3" style="margin-bottom: 20px;"> 
                <label>Ведомственная организация:</label>
                <select id="select2" name="organization[]" multiple="multiple" class="form-control js-example-basic-single">
                    <?foreach ($organization as $d):?>
                    <option value="<?=$d['org_id']?>"><?=$d['org_name']?></option>
                    <?endforeach;?>
                </select>
            </div>

            <div class="col-lg-3" style="margin-bottom: 20px;">
                <label>Доступность:</label>
                <select id="select2" name="availability[]" multiple="multiple" class="form-control js-example-basic-single">
                    <?foreach ($availability as $d):?>
                    <option value="<?=$d['id']?>"><?=$d['availability']?></option>
                    <?endforeach;?>
                </select>
            </div>

            <div class="col-lg-3" style="margin-bottom: 20px;">
                <label>Категория спорта:</label>
                <select id="select2" name="category[]" multiple="multiple" class="form-control js-example-basic-single">
                    <?foreach ($categories as $d):?>
                    <option value="<?=$d['id']?>"><?=$d['name']?></option>
                    <?endforeach;?>
                </select>
            </div>

            <div class="col-lg-3" style="margin-bottom: 20px;">
                <label>Вид спорта:</label>
                <select id="select2" name="type[]" multiple="multiple" class="form-control js-example-basic-single"> <!--with-image-->
                    <?foreach ($type_sport as $d):?>
                    <option value="<?=$d['id']?>"><?=$d['type']?></option>
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

<br />

<div id="mapUpdate">
<div class="preloaderMap" style="text-align:center"><img src="/img/preloader.gif" /></div>
<!-- подключаем модуль Яндекс.Карты -->
<?include($_SERVER['DOCUMENT_ROOT'].'/modules/map4/include/map.php');?>
</div>

<!--
<div class="row" style="padding: 20px 30px;">
<div class="col-lg-12"><b>Категории спорта по цветам</b><br /><br /></div>
<div class="clearfix"></div>
    <?foreach ($categories as $k => $v):?>
    <div class="col-lg-3">
    <div class="mapColorBox" style="background: <?=$v['color']?>;"></div>
    <?=$v['name']?>
    </div>
    <?endforeach;?>
</div>
-->

<!-- если пользователь авторизован, показываем форму сохранения заданной окружности -->
<?if ($_SESSION['user_id'] > 0):?>

<form method="POST" action="" id="form_saveCircle" class="hidden">
    <input type="hidden" name="module" value="map4"/>
    <input type="hidden" name="component" value=""/>
    <input type="hidden" name="opaco" value="1"/>
    <input type="hidden" name="radius" id="radius" value=""/>
    <input type="hidden" name="availability" id="availability" value=""/>
    <input type="hidden" name="lng" id="lng" value=""/>
    <input type="hidden" name="lat" id="lat" value=""/>
    <input type="hidden" name="ok" value="Окружность сохранена"/>
    <button class="btn btn-primary send_form" id="saveCircle"></button>
</form>
<?endif;?>
