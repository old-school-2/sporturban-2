    <div id="containerSearchMap" style="position: relative; width: 100%; height: 100%;">
        <div class="mapBoxInfo hidden">
        	<div class="mapBoxInfoInner">
            	<h3>Анализ выбранной зоны</h3>
            	<p><b>Доступность</b>: <span class="mapDataVal" id="textAvailability"></span></p>
            	<p><b>Население</b>: <span class="mapDataVal" id="textPopulation"></span></p>
            	<p><b>Плотность населения</b>: <span class="mapDataVal" id="textPopulationValue"></span></p>
            	<p><b>Целевая аудитория</b>: <span class="mapDataVal" id="textPopulationTarget"></span></p>
        	</div>
        </div>

        <div class="mapBoxInfoPolygon hidden">
        	<div class="mapBoxInfoInner">
            	<h3>Анализ выбранной зоны</h3>
                <form method="POST" action="" id="form_savePolygon">
                    <input type="hidden" name="module" value="map4"/>
                    <input type="hidden" name="component" value=""/>
                    <input type="hidden" name="opaco" value="1"/>
                    <input type="hidden" name="polygonArea" id="polygonArea" value=""/>
                    <input type="hidden" name="polygonPopulation" id="polygonPopulation" value=""/>
                    <input type="hidden" name="polygonTarget" id="polygonTarget" value=""/>
                    <input type="hidden" name="polygonSportzones" id="polygonSportzones" value=""/>
                    <input type="hidden" name="polygonSportzonesArea" id="polygonSportzonesArea" value=""/>
                    <input type="hidden" name="polygonSat" id="polygonSat" value=""/>
                    <input type="hidden" name="polygonCoords" id="polygonCoords" value=""/>

                    <input type="hidden" name="ok" value="Область сохранена"/>

                    <p><b>Площадь зоны</b>: <span class="mapDataVal" id="textPolygonArea"></span></p>
                	<p><b>Население</b>: <span class="mapDataVal" id="textPolygonPopulation"></span></p>
                    <p><b>Целевая аудитория</b>: <span class="mapDataVal" id="textPolygonTarget"></span></p>
                	<p><b>Спортзон</b>: <span class="mapDataVal" id="textPolygonSportzones"></span></p>
                    <p><b>Площадь спортзон</b>: <span class="mapDataVal" id="textPolygonSportzonesArea"></span></p>
                	<p><b>Удовлетворенность</b>: <span class="mapDataVal" id="textPolygonSat"></span></p>
                    <button class="btn btn-primary send_form hidden" id="savePolygon">Сохранить</button>
                </form>
        	</div>
        </div>
        <div id="map"></div>
        <canvas id="draw-canvas" style="position: absolute; left: 0; top: 0px; display: none; width: 100%; height: 100%; z-index: 9999999"></canvas>
        <button id="draw" class="hidden"></button>

    </div>

    <script>

    polygonOptions = {
      strokeColor: '#00ac27',
      fillColor: '#09d738',
      interactivityModel: 'default#transparent',
      strokeWidth: 3,
      opacity: 0.6
    };

    canvasOptions = {
      strokeStyle: '#00ac27',
      lineWidth: 3,
      opacity: 0.8
    };

    type_sport_popular = <?=json_encode($type_sport_popular)?>;

    //ymaps.ready(function () {
    ymaps.ready(['Map', 'Polygon', 'util.calculateArea']).then(function() {
        var availabilityData = {
            0: { 'radius': 500, 'low': '', 'medium': '', 'high': ''}
            <?foreach ($availability as $v):?>
            , <?=$v['id']?>: { 'radius': <?=round($v['km']*1000)?>, 'low': '<?=$v['red_people']?>', 'medium': '<?=$v['yellow_people']?>', 'high': '<?=$v['green_people']?>' }
            <?endforeach;?>
        };

        var availabilityColors = {
            'low': '#ff39527a',
            'medium': '#ffcf205e',
            'high': '#39ff487a'
        };

        // создаем яндекс-карту с координатами центра Москвы
        var myMap = new ymaps.Map('map', {
            center: [55.751574, 37.573856],
            zoom: 9,
            controls: ['zoomControl', 'searchControl']
        }, {
            searchControlProvider: 'yandex#search'
        }),

        polygon = null;

        var fullscreenControl = new ymaps.control.FullscreenControl();
        myMap.controls.add(fullscreenControl);

        clusterer = new ymaps.Clusterer({
            // Эта опция отвечает за размеры кластеров.
            // В данном случае для кластеров, содержащих до 10 элементов
            clusterNumbers: [10],
            //clusterIconContentLayout: customItemContentLayout
            // Макет метки кластера pieChart.
            clusterIconLayout: 'default#pieChart',
            // Радиус диаграммы в пикселях.
            clusterIconPieChartRadius: 24,
            // Радиус центральной части макета.
            clusterIconPieChartCoreRadius: 20,
            // Ширина линий-разделителей секторов и внешней обводки диаграммы.
            clusterIconPieChartStrokeWidth: 2,

        });

        // добавляем полигоны районов Москвы
        <?$i = 0; foreach ($districtVisible as $b):?>
        polygon = new ymaps.Polygon(<?=$b['polygons']?>, {
            hintContent: "<?=str_replace('"', '', $b['district'])?>"
        }, {
            fillColor: '#6699ff',
            interactivityModel: 'default#transparent',
            strokeWidth: 1,
            opacity: 0.2
        });
        myMap.geoObjects.add(polygon);
        myMap.setBounds(polygon.geometry.getBounds());
        <?$i++; endforeach;?>

        // добавляем метки с координатами спортивных объектов
        geoObjects = [];
        geoObjectsMy = [];
        <?$i = 0; foreach ($a as $b):?>
        placemarkColor = '<?=$b['color']?>',
        <?if (!empty($b['user_id'])):?>
        polygonLayout = ymaps.templateLayoutFactory.createClass('<div class="placemark placemarkUser"><div class="placemark_caption">{{properties.iconCaption}}</div></div>');
        <?else:?>
        polygonLayout = ymaps.templateLayoutFactory.createClass('<div class="placemark"><div class="placemark_image"><img src="<?if (!empty($type_sport_id[$objects_sportzones_id[$b['object_id']][0]['sport_id']]['img'])):?><?=$type_sport_id[$objects_sportzones_id[$b['object_id']][0]['sport_id']]['img']?><?else:?>/img/msk.png<?endif;?>" style="border-radius:50%" width="30" height="30"/></div><div class="placemark_caption" style="display:none">{{properties.iconCaption}}</div></div>');
        <?endif;?>

        geoObjects[<?=$i?>] = new ymaps.Placemark([<?=$b['lat']?>, <?=$b['lng']?>], {
            balloonContentBody: '<p style="font-size: 20px; margin-bottom: 15px"><?=$b['object']?></p><b>Адрес: </b><?=$b['address']?><br/><b>Ведомственная организация: </b><?=$organization_id[$b['org_id']]['org_name']?><br/><b>Доступность: </b><?=$availability_id[$b['availability_id']]?><br/><b>Виды спорта: </b><div><?$objects_sportzones_isset = array(); foreach ($objects_sportzones_id[$b['object_id']] as $sp):?><?if (in_array($sp['sport_id'], $objects_sportzones_isset) == false):?><p style="margin: 5px 0px 0px 0px;"><img style="width: 18px; height: 18px; display: inline; position: relative; top: -2px; margin-right: 5px" src="<?=$type_sport_id[$sp['sport_id']]['img']?>" /> <?=$sp['sport_name']?></p><?$objects_sportzones_isset[] = $sp['sport_id']; endif;?><?endforeach;?><a style="margin-top: 20px;" href="/user-objects/print/<?=$b['object_id']?>" target="_blank" class="btn btn-primary">Посмотреть</a></div>',
            clusterCaption: '<?=$b['object']?>',
            iconCaption: '<?=$b['object']?> (<?=$b['username']?>)'
        }, {
            iconLayout: polygonLayout,
            iconShape: {
                type: 'Circle',
                coordinates: [30, 30],
                radius: 30
            },
            iconOffset: [-15, -15],
            iconColor: '<?=$objects_sportzones_id[$b['object_id']][0]['color']?>'
            <?if (!empty($b['user_id'])):?>
            , visible: false
            <?endif;?>
        });
        geoObjects[<?=$i?>].type = 'sportzone';
        geoObjects[<?=$i?>].userId = <?if (!empty($b['user_id'])):?><?=$b['user_id']?><?else:?>0<?endif;?>;
        geoObjects[<?=$i?>].userName = '<?=$b['username']?>';
        geoObjects[<?=$i?>].availability = <?if (!empty($b['availability_id'])):?><?=$b['availability_id']?><?else:?>0<?endif;?>;
        geoObjects[<?=$i?>].availabilityName = '<?if (!empty($b['availability_id'])):?><?=$availability_id[$b['availability_id']]?><?else:?><?endif;?>';
        geoObjects[<?=$i?>].population = <?if (!empty($b['peoples'])):?><?=$b['peoples']?><?else:?>0<?endif;?>;
        geoObjects[<?=$i?>].targetPopulation = <?if (!empty($objects_sportzones_targetPeoples[$b['object_id']])):?><?=$objects_sportzones_targetPeoples[$b['object_id']]?><?else:?>0<?endif;?>;
        geoObjects[<?=$i?>].sportzoneArea = <?if (!empty($objects_sportzones_area[$b['object_id']])):?><?=$objects_sportzones_area[$b['object_id']]?><?else:?>0<?endif;?>;
        geoObjects[<?=$i?>].sport_id = [<?=implode(',', $objects_sportzones_isset)?>];
        <?$i++; endforeach;?>

        // кластеризуем метки спортивных объектов
        clusterer.add(geoObjects);
        myMap.geoObjects.add(clusterer);

        myMap.setBounds(clusterer.getBounds(), {
            checkZoomRange: true
        });


        <?$i = 0; foreach ($myObjects as $b):?>
        placemarkColor = '<?=$b['color']?>',
        polygonLayout = ymaps.templateLayoutFactory.createClass('<div class="placemark placemarkUser"><div class="placemark_caption">{{properties.iconCaption}}</div></div>');

        geoObjectsMy[<?=$i?>] = new ymaps.Placemark([<?=$b['lat']?>, <?=$b['lng']?>], {
            balloonContentBody: '<p style="font-size: 20px; margin-bottom: 15px"><?=$b['object']?></p><b>Адрес: </b><?=$b['address']?><br/><b>Ведомственная организация: </b><?=$organization_id[$b['org_id']]['org_name']?><br/><b>Доступность: </b><?=$availability_id[$b['availability_id']]?><br/><b>Виды спорта: </b><div><?$objects_sportzones_isset = array(); foreach ($objects_sportzones_id[$b['object_id']] as $sp):?><?if (in_array($sp['sport_id'], $objects_sportzones_isset) == false):?><p style="margin: 5px 0px 0px 0px;"><img style="width: 18px; height: 18px; display: inline; position: relative; top: -2px; margin-right: 5px" src="<?=$type_sport_id[$sp['sport_id']]['img']?>" /> <?=$sp['sport_name']?></p><?$objects_sportzones_isset[] = $sp['sport_id']; endif;?><?endforeach;?></div>',
            clusterCaption: '<?=$b['object']?>',
            iconCaption: '<?=$b['object']?> (<?=$b['username']?>)'
        }, {
            iconLayout: polygonLayout,
            iconShape: {
                type: 'Circle',
                coordinates: [30, 30],
                radius: 30
            },
            iconOffset: [-15, -15],
            iconColor: '<?=$objects_sportzones_id[$b['object_id']][0]['color']?>',
            visible: false
        });
        geoObjectsMy[<?=$i?>].type = 'sportzone';
        geoObjectsMy[<?=$i?>].userId = <?=$b['user_id']?>;
        geoObjectsMy[<?=$i?>].userName = '<?=$b['username']?>';
        geoObjectsMy[<?=$i?>].availability = <?if (!empty($b['availability_id'])):?><?=$b['availability_id']?><?else:?>0<?endif;?>;
        geoObjectsMy[<?=$i?>].availabilityName = '<?if (!empty($b['availability_id'])):?><?=$availability_id[$b['availability_id']]?><?else:?><?endif;?>';
        geoObjectsMy[<?=$i?>].population = <?if (!empty($b['peoples'])):?><?=$b['peoples']?><?else:?>0<?endif;?>;
        geoObjectsMy[<?=$i?>].targetPopulation = <?if (!empty($objects_sportzones_targetPeoples[$b['object_id']])):?><?=$objects_sportzones_targetPeoples[$b['object_id']]?><?else:?>0<?endif;?>;
        geoObjectsMy[<?=$i?>].sportzoneArea = <?if (!empty($objects_sportzones_area[$b['object_id']])):?><?=$objects_sportzones_area[$b['object_id']]?><?else:?>0<?endif;?>;
        geoObjectsMy[<?=$i?>].sport_id = [<?=implode(',', $objects_sportzones_isset)?>];
        myMap.geoObjects.add(geoObjectsMy[<?=$i?>]);
        <?$i++; endforeach;?>


        // добавляем кнопки на карту
        buttons = {
            heatmap: new ymaps.control.Button({
                data: {
                    content: 'Население',
                    image: '/modules/map4/img/heatmap_icon.png'
                },
                options: {
                    selectOnClick: true,
                    maxWidth: 250,
                    size: 'large'
                }
            }),
            circle_availability: new ymaps.control.Button({
                data: {
                    content: 'Доступность',
                    image: '/modules/map4/img/circle_availability.png'
                },
                options: {
                    selectOnClick: true,
                    maxWidth: 255,
                    size: 'large'
                }
            }),

            homes_button: new ymaps.control.Button({
                data: {
                    content: 'Дома',
                    image: '/modules/map4/img/homes_button.png'
                },
                options: {
                    selectOnClick: true,
                    maxWidth: 100,
                    size: 'large'
                }
            }),
            // если пользователь авторизован, добавляем кнопки создания произвольных областей на карте
            <?if ($_SESSION['user_id'] > 0):?>
            my_polygons_list: new ymaps.control.ListBox({
                data: {
                    content: 'Мои зоны',
                    image: '/modules/map4/img/my_objects_list.png'
                },
                items: [
                <?foreach ($myPolygons as $v):?>
		            new ymaps.control.ListBoxItem({
		            	data: {
		                    content: '<?=$v['name']?>',
                            coordinates: [<?=$v['coordinates']?>]
		                },
		                options: {
		                    selectOnClick: true
		                }
		            }),
		        <?endforeach;?>
                ],
                options: {
                    selectOnClick: true,
                    maxWidth: 190,
                    visible: true,
                    size: 'large'
                }
            }), my_objects_list: new ymaps.control.ListBox({
                data: {
                    content: 'Список объектов',
                    image: '/modules/map4/img/my_objects_list.png'
                },
                items: [
                <?foreach ($myObjects as $v):?>
		            new ymaps.control.ListBoxItem({
		            	data: {
		                    content: '<?=$v['object']?>',
                            lat: '<?=$v['lat']?>',
                            lng: '<?=$v['lng']?>'
		                },
		                options: {
		                    selectOnClick: true
		                }

		            }),
		        <?endforeach;?>
                ],
                options: {
                    selectOnClick: true,
                    maxWidth: 190,
                    visible: false,
                    size: 'large'
                }
            }), my_objects: new ymaps.control.Button({
                data: {
                    content: 'Мои объекты',
                    image: '/modules/map4/img/my_objects.png'
                },
                options: {
                    selectOnClick: true,
                    maxWidth: 190,
                    size: 'large'
                }
            }), circle_mode_button_save: new ymaps.control.Button({
                data: {
                    content: 'Сохранить область',
                    image: '/modules/map4/img/circle_mode_button_save.png'
                },
                options: {
                    selectOnClick: true,
                    maxWidth: 250,
                    visible: false,
                    size: 'large'
                }
            }),


            polygon_paint: new ymaps.control.Button({
                data: {
                    content: 'Нарисовать область',
                    image: '/modules/map4/img/polygon_paint.png'
                },
                options: {
                    selectOnClick: true,
                    maxWidth: 250,
                    visible: true,
                    size: 'large'
                }
            }),


            circle_mode_button: new ymaps.control.ListBox({
                data: {
                    content: 'Создать объект',
                    image: '/modules/map4/img/circle_mode_button.png'
                },
                items: [
                <?foreach ($availability as $v):?>
		            new ymaps.control.ListBoxItem({
		            	data: {
		                    content: '<?=$v['availability']?>'
		                },
		                options: {
		                    selectOnClick: true
		                }
		            }),
		        <?endforeach;?>
                ],
                options: {
                    selectOnClick: false,
                    maxWidth: 210,
                    expandOnClick: true,
                    size: 'large'
                }
            })

            <?endif;?>

        };


        // подключаем модуль тепловой карты
        ymaps.modules.require(['Heatmap'], function (Heatmap) {

            // загружаем в массив javascript из php-массива $mos_realty данные о плотности населения
            heatmap_data = {
                type: 'FeatureCollection',
                features: [
                <?$i = 0; foreach ($mos_realty as $b):?>
                    {
                        id: 'id<?=$b['id']?>',
                        type: 'Feature',
                        geometry: {
                            type: 'Point',
                            coordinates: [<?=$b['lat']?>, <?=$b['lng']?>]
                        },
                        properties: {
                            weight: <?=$b['area_residential']?>
                        }
                    }<?if (count($mos_realty) > $i):?>,<?endif;?>
                <?$i++; endforeach;?>
                ]
            };

            gradients = [{
                0.1: 'rgba(128, 255, 0, 0.7)',
                0.2: 'rgba(255, 255, 0, 0.8)',
                0.7: 'rgba(234, 72, 58, 0.9)',
                1.0: 'rgba(162, 36, 25, 1)'
            }, {
                0.1: 'rgba(162, 36, 25, 0.7)',
                0.2: 'rgba(234, 72, 58, 0.8)',
                0.7: 'rgba(255, 255, 0, 0.9)',
                1.0: 'rgba(128, 255, 0, 1)'
            }],
            radiuses = [5, 10, 20, 30],
            opacities = [0.4, 0.6, 0.8, 1];

            // создаем тепловую карту
            var heatmap = new Heatmap(heatmap_data,
             {
                gradient: gradients[0],
                radius: radiuses[2],
                opacity: opacities[2],
                dissipating: false
            });

            // создаем событие нажатия на кнопку "Плотность населения" для отображения тепловой карты
            buttons.heatmap.events.add('press', function () {
                heatmap.setMap(
                    heatmap.getMap() ? null : myMap
                );
            });

            var pixelCenter = myMap.getGlobalPixelCenter();
	        var geoCenter = myMap.options.get('projection').fromGlobalPixels(pixelCenter, myMap.getZoom());

	        myCircle = new ymaps.Circle([
	            [geoCenter[0], geoCenter[1]],
	            500
	        ], {}, {
	            fillColor: "#52c87c47",
	            strokeColor: "#52c87c",
	            strokeOpacity: 0.8,
	            strokeWidth: 1,
	            draggable: true
	        });

	        myCircle.population = 0;
            myCircle.targetPopulation = 0;
            myCircle.availability = 4;

            <?if ($_SESSION['user_id'] > 0):?>
            optionMyCircleData = [];
	        buttons.circle_mode_button.get(0).events.add('click', function () {
	        	buttons.circle_mode_button.get(1).deselect();
	        	buttons.circle_mode_button.get(2).deselect();
	        	buttons.circle_mode_button.get(3).deselect();
	        	optionCircleData = availabilityData[1];
	        	myCircle.geometry.setRadius(optionCircleData['radius']);
	        	myCircle.availability = 1;
                $('#textAvailability').text('Городская');
                $('input#availability').val(myCircle.availability);
			});
			buttons.circle_mode_button.get(1).events.add('click', function () {
				buttons.circle_mode_button.get(0).deselect();
	        	buttons.circle_mode_button.get(2).deselect();
	        	buttons.circle_mode_button.get(3).deselect();
			    optionCircleData = availabilityData[2];
			    myCircle.geometry.setRadius(optionCircleData['radius']);
			    myCircle.availability = 2;
                $('#textAvailability').text('Окружная');
                $('input#availability').val(myCircle.availability);
			});
			buttons.circle_mode_button.get(2).events.add('click', function () {
				buttons.circle_mode_button.get(0).deselect();
	        	buttons.circle_mode_button.get(1).deselect();
	        	buttons.circle_mode_button.get(3).deselect();
			    optionCircleData = availabilityData[3];
			    myCircle.geometry.setRadius(optionCircleData['radius']);
			    myCircle.availability = 3;
                $('#textAvailability').text('Районная');
                $('input#availability').val(myCircle.availability);
			});
	        buttons.circle_mode_button.get(3).events.add('click', function () {
	        	buttons.circle_mode_button.get(0).deselect();
	        	buttons.circle_mode_button.get(1).deselect();
	        	buttons.circle_mode_button.get(2).deselect();
			    optionCircleData = availabilityData[4];
			    myCircle.geometry.setRadius(optionCircleData['radius']);
			    myCircle.availability = 4;
                $('#textAvailability').text('Шаговая');
                $('input#availability').val(myCircle.availability);
			});

            <?$i = 0; foreach ($myObjects as $v):?>
            buttons.my_objects_list.get(<?=$i?>).events.add('click', function () {

                var lat = buttons.my_objects_list.get(<?=$i?>).data._data.lat;
                var lng = buttons.my_objects_list.get(<?=$i?>).data._data.lng;
                myMap.setCenter([lat, lng]).setZoom(16);

			});
	        <?$i++; endforeach;?>

	        <?$i = 0; foreach ($myPolygons as $v):?>
            buttons.my_polygons_list.get(<?=$i?>).events.add('click', function () {

                coords = [];
                coordsObj = buttons.my_polygons_list.get(<?=$i?>).data._data.coordinates;

				for(var i = 0; i < coordsObj.length; i += 2)
				{
				    coords.push(coordsObj.slice(i, i + 2));
				}

                polygonDataCreate(coords);

                setTimeout(function(){buttons.my_polygons_list.get(<?=$i?>).deselect();}, 10);

			});
	        <?$i++; endforeach;?>
            <?endif;?>


            // создаем событие изменения размеров и местоположения созданной окружности
	        myCircle.events.add('geometrychange', function () {

	        	myCircle.population = 0;
                myCircle.targetPopulation = 0;
                // ищем кластеры, попадающие в радиус окружности и скрываем не попадающие в радиус
		        for(var i in clusterer.getClusters()) {
			      cluster = clusterer.getClusters()[i];
			      if(myCircle.geometry.contains(cluster.geometry.getCoordinates())) {
			        cluster.options.set('visible', true).set('geoObjectVisible', true);
			      } else {
			        cluster.options.set('visible', false).set('geoObjectVisible', false);
			      }
			    }

                var sport_id_arr = [];
                // ищем метки, не попавшие в кластеры, попадающие в радиус окружности и скрываем не попадающие в радиус
                for(var i in geoObjects) {
			      object = geoObjects[i];
			      if(myCircle.geometry.contains(object.geometry.getCoordinates())) {
			        object.options.set('visible', true);
                    myCircle.targetPopulation += parseInt(object.targetPopulation);
                    sport_id_arr = sport_id_arr.concat(object.sport_id);
			      } else {
			        object.options.set('visible', false);
			      }
			    }

            	// удаляем дубли из видов спорта, присутствующих в области
            	sport_id_arr = arrayUnique(sport_id_arr);
                sport_popular = [];
                for(var i in sport_id_arr) {
			      sport_id = sport_id_arr[i];
			      sport_popular[i] = type_sport_popular[sport_id];
			    }

				// считаем целевую аудиторию по всем присутствующим площадкам и доступным видам спорта в сумме (коэффициенты популярности видов спорта в массиве type_sport_popular)
                sum = 0;
			    sport_popular.forEach(function(a, i, sport_popular){
			    	sum = sum + a;
			    	sport_popular.forEach(function(b, j, sport_popular){
			        	if (i != j){
			        		sum = sum - (a * b);
			        	}
                    })
            	});



			    // если активно отображение домов
       	    	for(var i in geoObjectsHomes) {
        	      object = geoObjectsHomes[i];
        	      if(myCircle.geometry.contains(object.geometry.getCoordinates())) {
        	        if (homesVisible == 1){
        	           object.options.set('visible', true);
                    }
        	        myCircle.population += parseInt(object.population);

        	      } else {
        	        if (homesVisible == 1){
                        object.options.set('visible', false);
        	        }
                  }
        	    }

        	    target = Math.round(myCircle.population*sum);

                // рассчитываем и выводим в виджет население, ЦА и плотность населения в зависимости от выбранной доступности объекта
           	    availabilityType = availabilityData[myCircle.availability];
       	        if (availabilityType['low'] >= myCircle.population){
       	      	  availabilityColor = availabilityColors['low'];
       	      	  availabilityText = 'Низкая';
       	        } else if (myCircle.population > availabilityType['low'] && myCircle.population <= availabilityType['medium']){
       	      	  availabilityColor = availabilityColors['medium'];
       	      	  availabilityText = 'Средняя';
       	        } else if (myCircle.population > availabilityType['medium']){
       	      	  availabilityColor = availabilityColors['high'];
       	      	  availabilityText = 'Высокая';
       	        }
       	        myCircle.options.set('fillColor', availabilityColor);
       	        myCircle.options.set('strokeColor', availabilityColor);

                $('#textPopulation').text(myCircle.population);
                $('#textPopulationTarget').text(target);
                $('#textPopulationValue').text(availabilityText);

	    });

            myObjectsVisible = 0;
            circleAvailabilityStatus = 0;
            homesVisible = 0;
            startEdit = 0;

        <?if ($_SESSION['user_id'] > 0):?>

            // создаем событие нажатия на кнопку "Рисовать область"

            buttons.circle_mode_button.events.add('press', function () {

	    		myCircle.population = 0;
                myCircle.targetPopulation = 0;

                // убираем полигон, если он нарисован
                if (polygonCanvas) {
                  myMap.geoObjects.remove(polygonCanvas);
                }

	    		if (startEdit == 0){
	    			$('.mapBoxInfo').removeClass('hidden');
                    $('.mapBoxInfoPolygon').addClass('hidden');

	    			buttons.circle_mode_button.get(3).select();
                    $('#textAvailability').text('Шаговая');
                    $('input#availability').val('4');

                    // если ранее вызывалась окружность наличия объектов по определенным координатам, удаляем ее
                    <?if ($changeZoom == true):?>
                    //myMap.geoObjects.remove(myCircle2);
                    // отображаем на карте ранее скрытые кластеры и метки
		    		for(var i in clusterer.getClusters()) {
				      cluster = clusterer.getClusters()[i];
				      cluster.options.set('visible', true).set('geoObjectVisible', true);
				    }
                    for(var i in geoObjects) {
            	      object = geoObjects[i];
            	      object.options.set('visible', true);
            	    }
                    <?endif;?>

                    // добавляем окружность на карту и задаем ей координаты центра карты
		    		myMap.geoObjects.add(myCircle);
		    		//myCircle.editor.startEditing();

                    var pixelCenter = myMap.getGlobalPixelCenter();
	                var geoCenter = myMap.options.get('projection').fromGlobalPixels(pixelCenter, myMap.getZoom());

                    myCircle.geometry.setCoordinates([geoCenter[0], geoCenter[1]]);

		    		startEdit = 1;
                    // ищем кластеры, попадающие в радиус окружности и скрываем не попадающие в радиус
		    		for(var i in clusterer.getClusters()) {
            	      cluster = clusterer.getClusters()[i];
            	      if(myCircle.geometry.contains(cluster.geometry.getCoordinates())) {
            	        cluster.options.set('visible', true).set('geoObjectVisible', true);
            	      } else {
            	        cluster.options.set('visible', false).set('geoObjectVisible', false);
            	      }
            	    }

                    // ищем метки, не попавшие в кластеры, попадающие в радиус окружности и скрываем не попадающие в радиус
                    for(var i in geoObjects) {
            	      object = geoObjects[i];
            	      if(myCircle.geometry.contains(object.geometry.getCoordinates())) {
            	        object.options.set('visible', true);
                        myCircle.targetPopulation += parseInt(object.targetPopulation); // суммируем показатель ЦА
            	      } else {
            	        object.options.set('visible', false);
            	      }
            	    }

            	    // если активно отображение домов
            	    if (homesVisible == 1){
            	    	for(var i in geoObjectsHomes) {
	            	      object = geoObjectsHomes[i];
	            	      if(myCircle.geometry.contains(object.geometry.getCoordinates())) {
	            	        object.options.set('visible', true);
	            	        myCircle.population += parseInt(object.population); // суммируем показатель населения
	            	      } else {
	            	        object.options.set('visible', false);
	            	      }
	            	    }
            	    }

            	    <?if ($_SESSION['user_id'] > 0):?>
                    // отображаем кнопку сохранения области
            	    buttons.circle_mode_button_save.options.set('visible', true);
                    <?endif;?>

                // удаляем окружность с карты при повторном нажатии кнопки "Рисовать область"
	    		} else if (startEdit == 2) {
	    			myMap.geoObjects.remove(myCircle);
		    		startEdit = 0;

                    // отображаем на карте ранее скрытые кластеры и метки
		    		for(var i in clusterer.getClusters()) {
				      cluster = clusterer.getClusters()[i];
				      cluster.options.set('visible', true).set('geoObjectVisible', true);
				    }
                    for(var i in geoObjects) {
            	      object = geoObjects[i];
            	      object.options.set('visible', true);
            	    }
            	    <?if ($_SESSION['user_id'] > 0):?>
                    // скрываем кнопку сохранения окружности
            	    buttons.circle_mode_button_save.options.set('visible', false);
                    <?endif;?>
	    		}
            });
            <?endif;?>

            <?if ($_SESSION['user_id'] > 0):?>
            // если нажата кнопка сохранения нарисованной окружности, отправляем данные формы на сервер
            buttons.circle_mode_button_save.events.add('press', function () {

            	if (polygonCanvas){            		$('button#savePolygon').click();

                } else {                	 var circleCoords = myCircle.geometry.getCoordinates();
	                $('#form_saveCircle #radius').val(myCircle.geometry.getRadius());
	                $('#form_saveCircle #lng').val(circleCoords[1]);
	                $('#form_saveCircle #lat').val(circleCoords[0]);
	                $('button#saveCircle').click();
                }
            });
            <?endif;?>


            myMap.events.add('boundschange', function () {

                if (startEdit == 1){

                    setTimeout(function(){
                    for(var i in clusterer.getClusters()) {
    			      cluster = clusterer.getClusters()[i];
    			      if(myCircle.geometry.contains(cluster.geometry.getCoordinates())) {
    			        cluster.options.set('visible', true).set('geoObjectVisible', true);
    			      } else {
    			        cluster.options.set('visible', false).set('geoObjectVisible', false);
    			      }
    			    }

                    for(var i in geoObjects) {
    			      object = geoObjects[i];
    			      if(myCircle.geometry.contains(object.geometry.getCoordinates())) {
    			        object.options.set('visible', true);
    			      } else {
    			        object.options.set('visible', false);
    			      }
    			    }
                    }, 100);
                }

		    });


            // добавляем на карту созданные кнопки
            for (var key in buttons) {
                if (buttons.hasOwnProperty(key)) {
                    myMap.controls.add(buttons[key]);
                }
            }

        });


        geoObjectsHomes = [];
        circleAvailability = [];

        // добавляем дома на карту
        <?$i = 0; foreach ($mos_realty as $b):?>
        polygonLayout = ymaps.templateLayoutFactory.createClass('<div class="placemarkHome"></div>');

        geoObjectsHomes[<?=$i?>] = new ymaps.Placemark([<?=$b['lat']?>, <?=$b['lng']?>], {
            balloonContentBody: '<b>Адрес: </b><?=$b['address']?><br/><b>Общая площадь: </b><?=$b['area_residential']?> м&sup2;<br/><b>Проживает человек: </b><?=round($b['area_residential']/18.5)?></div>',
        }, {
            iconLayout: polygonLayout,
            iconShape: {
                type: 'Circle',
                coordinates: [10, 10],
                radius: 5
            },
            iconOffset: [-5, -5],
            visible: false
        });
        geoObjectsHomes[<?=$i?>].population = <?if (!empty($b['area_residential'])):?><?=round($b['area_residential']/18.5)?><?else:?>0<?endif;?>;
        geoObjectsHomes[<?=$i?>].type = 'home';

        myMap.geoObjects.add(geoObjectsHomes[<?=$i?>]);
        <?$i++; endforeach;?>


        // подключаем радиусы доступности
        buttons.circle_availability.events.add('press', function () {

            if (circleAvailabilityStatus == 0){

                for(var i in geoObjects) {
        	      object = geoObjects[i];
        	      coords = object.geometry.getCoordinates();

        	      availabilityType = availabilityData[object.availability];
        	      if (availabilityType['low'] >= object.population){
        	      	  availabilityColor = availabilityColors['low'];
        	      } else if (object.population > availabilityType['low'] && object.population <= availabilityType['medium']){
        	      	  availabilityColor = availabilityColors['medium'];
        	      } else if (object.population > availabilityType['medium']){
        	      	  availabilityColor = availabilityColors['high'];
        	      }


                  circleAvailability[i] = new ymaps.Circle([
                        [coords[0], coords[1]],
                        availabilityData[object.availability]['radius']
                    ], {
                        balloonContentBody: ''
                    }, {
                        fillColor: availabilityColor,
                        strokeColor: '#FFFFFF',
                        strokeOpacity: 0.01,
                        strokeWidth: 1
                    });
                    circleAvailability[i].type = 'availabilityCircle';
                    circleAvailability[i].population = object.population;

                    myMap.geoObjects.add(circleAvailability[i]);

                    circleThis = circleAvailability[i];
                    // ищем дома, попадающие в радиус круга
                    for(var ii in geoObjectsHomes) {
        		      objectHome = geoObjectsHomes[ii];

        		      if(circleThis.geometry.contains(objectHome.geometry.getCoordinates())) {
        		        objectHome.options.set('visible', true);
                        objectHome.inCircle = true;
        		      }
        		    }

                    circleThis.properties.set('balloonContentBody', '<p style="font-size: 20px; margin-bottom: 15px">Доступность площадки</p><b>Тип: </b>'+object.availabilityName+'<br /><b>Население: </b> '+circleThis.population+'</div>');
                    circleThis.events.add(['mouseenter', 'mouseleave'], function (e) {
                        var target = e.get('target'),
                            type = e.get('type');
                        if (type == 'mouseenter') {
                            target.options.set('strokeOpacity', 1);
                        } else if (type == 'mouseleave') {
                            target.options.set('strokeOpacity', 0.01);
                        }
                    });

        	    }

                homesVisible = 1;
                circleAvailabilityStatus = 2;

            } else if (circleAvailabilityStatus == 1) {

                myMap.geoObjects.each(function(geoObject) {
                    if (geoObject.type == 'availabilityCircle'
                    || (geoObject.type == 'home' && geoObject.inCircle == true)){
                        geoObject.options.set('visible', true);
                    }
                });

                homesVisible = 1;
                circleAvailabilityStatus = 2;

            } else {

                myMap.geoObjects.each(function(geoObject) {
                    if (geoObject.type == 'availabilityCircle'){  //|| geoObject.type == 'home'
                        geoObject.options.set('visible', false);
                    }
                });

                circleAvailabilityStatus = 1;
            }
        });



        // подключаем видимость домов
        buttons.homes_button.events.add('press', function () {

            if (homesVisible == 0){

                myMap.geoObjects.each(function(geoObject) {
                    if (geoObject.type == 'home'){
                        geoObject.options.set('visible', true);
                    }
                });

                homesVisible = 1;

            } else if (homesVisible == 1) {

                myMap.geoObjects.each(function(geoObject) {
                    if (geoObject.type == 'home'){
                        geoObject.options.set('visible', false);
                    }
                });

                homesVisible = 0;

            }
        });


        // включаем / отключаем Мои объекты
        buttons.my_objects.events.add('press', function () {

            if (myObjectsVisible == 0){

                myMap.geoObjects.each(function(geoObject) {
                    if (geoObject.type == 'sportzone' && parseInt(geoObject.userId) > 0){
                        geoObject.options.set('visible', true);
                    }
                });
                /*
                var geoObjects = clusterer.getGeoObjects();
                for (var i = 0, l = geoObjects.length; i < l; i++) {
                    obj = geoObjects[i];
                    if (obj.type == 'sportzone' && parseInt(obj.userId) > 0){
                        obj.options.set('visible', true);
                    }
                }
                */
                myObjectsVisible = 1;
                buttons.my_objects_list.options.set('visible', true);

            } else if (myObjectsVisible == 1) {

                myMap.geoObjects.each(function(geoObject) {
                    if (geoObject.type == 'sportzone' && parseInt(geoObject.userId) > 0){
                        geoObject.options.set('visible', false);
                    }
                });
                /*
                var geoObjects = clusterer.getGeoObjects();
                for (var i = 0, l = geoObjects.length; i < l; i++) {
                    obj = geoObjects[i];
                    if (obj.type == 'sportzone' && parseInt(obj.userId) > 0){
                        obj.options.set('visible', false);
                    }
                }
                */
                myObjectsVisible = 0;
                buttons.my_objects_list.options.set('visible', false);

            }
        });



        // при переходе на страницу с переданными GET-параметрами lng и lat центрируем карту на указанных координатах
        <?if ($changeZoom == true):?>
        $('#opaco').removeClass('hidden').css({'height': $(window).height()});
        setTimeout(function(){

            // размещаем маркер на указанных координатах
            var placemark = new ymaps.Placemark([<?=$centerMap?>]);
            myMap.geoObjects.add(placemark);

            // приближаем карту и центрируем на указанных координатах
            myMap.setZoom(<?=$zoom?>);
            myMap.setCenter([<?=$centerMap?>]);

            var pixelCenter = myMap.getGlobalPixelCenter();
            var geoCenter = myMap.options.get('projection').fromGlobalPixels(pixelCenter, myMap.getZoom());

            var myCircle2 = new ymaps.Circle([
	            [geoCenter[0], geoCenter[1]],
	            500
	        ], {}, {
	            fillColor: "#52c87c47",
	            strokeColor: "#52c87c",
	            strokeOpacity: 0.8,
	            strokeWidth: 2
	        });

            myMap.geoObjects.add(myCircle2);

            // ищем кластеры, попадающие в радиус окружности и скрываем не попадающие в радиус
    		for(var i in clusterer.getClusters()) {
    	      cluster = clusterer.getClusters()[i];
    	      if(myCircle2.geometry.contains(cluster.geometry.getCoordinates())) {
    	        cluster.options.set('visible', true).set('geoObjectVisible', true);
    	      } else {
    	        cluster.options.set('visible', false).set('geoObjectVisible', false);
    	      }
    	    }

            // ищем метки, не попавшие в кластеры, попадающие в радиус окружности и скрываем не попадающие в радиус
            for(var i in geoObjects) {
    	      object = geoObjects[i];
    	      if(myCircle2.geometry.contains(object.geometry.getCoordinates())) {
    	        object.options.set('visible', true);
    	      } else {
    	        object.options.set('visible', false);
    	      }
    	    }

            $('#opaco').addClass('hidden');

        }, 2000);

        <?endif;?>

          polygonCanvas = null;

          var drawButton = document.querySelector('#draw');

          // клик по кнопке Рисования области
          buttons.polygon_paint.events.add('press', function () {

			// убираем окружность, если она нарисована
            if (myCircle != undefined) {
              myMap.geoObjects.remove(myCircle);
            }

            buttons.circle_mode_button.options.set('visible', false);

            drawButton.disabled = true;

            drawLineOverMap(myMap)
              .then(function(coordinates) {
                // Тут надо симплифицировать линию.
                // Для простоты я оставляю только каждую третью координату.
                coordinates = coordinates.filter(function (_, index) {
                  return index % 5 === 0;
                });

                polygonDataCreate(coordinates);

              });
        });

		// расчет данных после рисования полигона
        function polygonDataCreate(coordinates){
        		// Удаляем старый полигон.
                if (polygonCanvas) {
                  myMap.geoObjects.remove(polygonCanvas);
                }

                // Создаем новый полигон
                polygonCanvas = new ymaps.Polygon([coordinates], {}, polygonOptions);

                myMap.geoObjects.add(polygonCanvas);
                myMap.setBounds(polygonCanvas.geometry.getBounds());

                drawButton.disabled = false;

                var population = 0,
                    target = 0,
                    sportzones = 0,
                    sportzonesArea = 0,
                    satisfaction = 0;

                // выводим объекты, попадающие в данный полигон
                // ищем дома, попадающие в радиус круга
                for(var ii in geoObjectsHomes) {
    		      objectHome = geoObjectsHomes[ii];
    		      if(polygonCanvas.geometry.contains(objectHome.geometry.getCoordinates())) {
    		        objectHome.options.set('visible', true);
                    population += objectHome.population;
                    //objectHome.inCircle = true;
    		      } else {
    		        objectHome.options.set('visible', false);
    		      }
    		    }

                // ищем кластеры, попадающие в радиус окружности и скрываем не попадающие в радиус
        		for(var i in clusterer.getClusters()) {
        	      cluster = clusterer.getClusters()[i];
        	      if(polygonCanvas.geometry.contains(cluster.geometry.getCoordinates())) {
        	        cluster.options.set('visible', true).set('geoObjectVisible', true);
        	      } else {
        	        cluster.options.set('visible', false).set('geoObjectVisible', false);
        	      }
        	    }

                // ищем метки, не попавшие в кластеры, попадающие в радиус окружности и скрываем не попадающие в радиус
                var sport_id_arr = [];
                for(var i in geoObjects) {
			      object = geoObjects[i];
			      if(polygonCanvas.geometry.contains(object.geometry.getCoordinates())) {
			        object.options.set('visible', true);
			        sport_id_arr = sport_id_arr.concat(object.sport_id);
                    target += parseInt(object.targetPopulation);
                    sportzones += 1;
                    sportzonesArea += object.sportzoneArea;
                    //myCircle.targetPopulation += parseInt(object.targetPopulation);
			      } else {
			        object.options.set('visible', false);
			      }
			    }

				buttons.polygon_paint.deselect();
                buttons.circle_mode_button.options.set('visible', true);
            	buttons.circle_mode_button_save.options.set('visible', true);

            	// удаляем дубли из видов спорта, присутствующих в области
            	sport_id_arr = arrayUnique(sport_id_arr);
                sport_popular = [];
                for(var i in sport_id_arr) {
			      sport_id = sport_id_arr[i];
			      sport_popular[i] = type_sport_popular[sport_id];
			    }

				// считаем целевую аудиторию по всем присутствующим площадкам и доступным видам спорта в сумме (коэффициенты популярности видов спорта в массиве type_sport_popular)
                sum = 0;
			    sport_popular.forEach(function(a, i, sport_popular){
			    	sum = sum + a;
			    	sport_popular.forEach(function(b, j, sport_popular){
			        	if (i != j){
			        		sum = sum - (a * b);
			        	}
                    })
            	});
                target = Math.round(population*sum);

                // отображаем виджет с показателями выбранной области
                $('.mapBoxInfo').addClass('hidden');
                $('.mapBoxInfoPolygon').removeClass('hidden');
                // рассчитываем площадь выделенной области с помощью модуля Яндекс.Карт util.calculateArea
                var area = Math.round(ymaps.util.calculateArea(polygonCanvas));
                if (area <= 1e6) {
                    area += ' м²';
                } else {
                    area = (area / 1e6).toFixed(3) + ' км²';
                }

                satisfaction = (sportzonesArea/100000).toFixed(2);

                if (sportzonesArea > 0){
                	sportzonesArea += ' м²';
                } else {
                	sportzonesArea = '-';
                }

                if (satisfaction == 0){
                	satisfaction = '-';
                }

                $('#textPolygonArea').text(area);
                $('#textPolygonPopulation').text(population);
                $('#textPolygonTarget').text(target);
                $('#textPolygonSportzones').text(sportzones);
                $('#textPolygonSportzonesArea').text(sportzonesArea);
                $('#textPolygonSat').text(satisfaction);

                // сохраняем данные в форму
                $('#polygonArea').val(area);
                $('#polygonPopulation').val(population);
                $('#polygonTarget').val(target);
                $('#polygonSportzones').val(sportzones);
                $('#polygonSportzonesArea').val(sportzonesArea);
                $('#polygonSat').val(satisfaction);
                $('#polygonCoords').val(coordinates);

        }



        // функция рисования области на карте
        function drawLineOverMap(map) {
          var canvas = document.querySelector('#draw-canvas');
          var ctx2d = canvas.getContext('2d');
          var drawing = false;
          var coordinates = [];
            var offsets = [];

            // Задаем размеры канвасу как у карты.
            var rect = map.container.getParentElement().getBoundingClientRect();
            canvas.style.width = rect.width + 'px';
            canvas.style.height = rect.height + 'px';
            canvas.width = rect.width;
            canvas.height = rect.height;

            // Применяем стили.
            ctx2d.strokeStyle = canvasOptions.strokeStyle;
            ctx2d.lineWidth = canvasOptions.lineWidth;
            canvas.style.opacity = canvasOptions.opacity;

            ctx2d.clearRect(0, 0, canvas.width, canvas.height);

            // Показываем канвас. Он будет сверху карты из-за position: absolute.
            canvas.style.display = 'block';

            canvas.onmousedown = function(e) {
                // При нажатии мыши запоминаем, что мы начали рисовать и координаты.
                drawing = true;
                coordinates.push([e.pageX, e.pageY]);
                offsets.push([e.offsetX, e.offsetY]);
            };

            canvas.onmousemove = function(e) {
                // При движении мыши запоминаем координаты и рисуем линию.
                if (drawing) {
                    var last = offsets[offsets.length - 1];
                    ctx2d.beginPath();
                    ctx2d.moveTo(last[0], last[1]);
                    ctx2d.lineTo(e.offsetX, e.offsetY);
                    ctx2d.stroke();

                    coordinates.push([e.pageX, e.pageY]);
                    offsets.push([e.offsetX, e.offsetY]);
                }
            };

            return new Promise(function(resolve) {
                // При отпускании мыши запоминаем координаты и скрываем канвас.
                canvas.onmouseup = function(e) {

                    coordinates.push([e.pageX, e.pageY]);
                    canvas.style.display = 'none';
                    drawing = false;

                    var projection = map.options.get('projection');
                    coordinates = coordinates.map(function(x) {
                        return projection.fromGlobalPixels(
                            map.converter.pageToGlobal([x[0], x[1]]), map.getZoom()
                        );
                    });

                    resolve(coordinates);
                };
            });
        }

    });
    // функция удаления дублей из массива
    function arrayUnique(array) {
	    var a = array.concat();
	    for(var i=0; i<a.length; ++i) {
	        for(var j=i+1; j<a.length; ++j) {
	            if(a[i] === a[j])
	                a.splice(j--, 1);
	        }
	    }

	    return a;
	}

    </script>


