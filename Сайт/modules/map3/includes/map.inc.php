<div id="map" style="width: 100%; height: 600px; z-index: 1;"></div>

<script src="<?=DOMAIN;?>/lib/Leaflet/lib/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="<?=DOMAIN;?>/lib/Leaflet/lib/leaflet.markercluster.js"></script>

<script type="text/javascript">


var addressPoints = [
  <?foreach($objects as $val):?>
  <?$sportsList=null;  if(!empty($objSportType[ $val['object_id'] ])):?>
    <?$sportsList = '<p style="margin-bottom: 4px;"><b>Виды спорта:</b></p><div>';?>
    <?foreach($objSportType[ $val['object_id'] ] as $sport_id=>$value):?>
    <?$sportsList.='<p style="margin: 5px 0px 0px 0px;">'.$value['icon'].'&nbsp; '.$value['sport'].'</p>';?>
    <?endforeach;?>
    <?$sportsList .= '</div>';?>
  <?endif;?>
  
  [<?=$val['lat']?>, <?=$val['lng']?>, '<p style="font-size: 15px; margin-bottom: 10px"><?=$val["object"]?></p><?if(!empty($val["adm_area"])):?><?=$val["adm_area"];?><br><?endif;?><?if(!empty($val["district"])):?><?=$val["district"];?><?endif;?><p style="margin: 3px 0;"><b>Адрес: </b><?=$val["address"];?></p><p style="margin: 3px 0;"><b>Ведомственная организация: </b><?=$val["org_name"];?></p><p style="margin: 3px 0;"><b>Доступность: </b><?=$val["availability"];?></p><?=$sportsList;?>'],
  <?endforeach;?>
];

var map = L.map('map').setView([55.7522, 37.6156], 10);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; <a href="copyright">Openstreetmap</a>'
}).addTo(map);

var markers = L.markerClusterGroup({ chunkedLoading: true, });
var markerList = [];

for (var i = 0; i < addressPoints.length; i++) {
  var a = addressPoints[i];
  var title = a[2];
  var marker = L.marker(L.latLng(a[0], a[1]), { title: title });
  marker.bindPopup(title);
  markerList.push(marker);
}

markers.addLayers(markerList);
map.addLayer(markers);

L.Control.geocoder().addTo(map);

var popup = L.popup();

function onMapClick(e) {
    
    var text = '<b>Население по доступности:</b><br>Шаговая: 5061 человек<br>Окружная: 12323 человек<br>Районная: 45394 человек<br>Городская: 245401 человек<br><br><b>Удовлетворенность футбол:</b><br>Шаговая: 0.56<br>Окружная: 1.42<br>Районная: 2.63<br>Городская: 3.56';
    
    popup
        .setLatLng(e.latlng)
        .setContent(text)
        .openOn(map);
}

map.on('click', onMapClick);

</script>