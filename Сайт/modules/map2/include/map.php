    <div id="containerSearchMap" style="position: relative; width: 100%; height: 100%;">
        <div id="map"></div>
        <canvas id="draw-canvas" style="position: absolute; left: 0; top: 0px; display: none; width: 100%; height: 100%"></canvas>
    </div>

    <script>

    ymaps.ready(function () {

        // создаем яндекс-карту с координатами центра Москвы
        var myMap = new ymaps.Map('map', {
            center: [55.751574, 37.573856],
            zoom: 9,
            controls: ['zoomControl', 'searchControl']
        }, {
            searchControlProvider: 'yandex#search'
        }),

        polygon = null;

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
        <?$i = 0; foreach ($district as $b):?>
        <?if ((!empty($_POST['districts']) and in_array($b['id'], $_POST['districts'])) or empty($_POST['districts'])):?>
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
        <?endif;?>
        <?$i++; endforeach;?>

        // добавляем метки с координатами спортивных объектов
        geoObjects = [];
        <?$i = 0; foreach ($a as $b):?>
        placemarkColor = '<?=$b['color']?>',
        polygonLayout = ymaps.templateLayoutFactory.createClass('<div class="placemark"><div class="placemark_image"><img src="<?if (!empty($type_sport_id[$objects_sportzones_id[$b['object_id']][0]['sport_id']]['img'])):?><?=$type_sport_id[$objects_sportzones_id[$b['object_id']][0]['sport_id']]['img']?><?else:?>/img/msk.png<?endif;?>" style="border-radius:50%" width="30" height="30"/></div><div class="placemark_caption" style="display:none">{{properties.iconCaption}}</div></div>');

        geoObjects[<?=$i?>] = new ymaps.Placemark([<?=$b['lat']?>, <?=$b['lng']?>], {
            balloonContentBody: '<p style="font-size: 20px; margin-bottom: 15px"><?=$b['object']?></p><b>Адрес: </b><?=$b['address']?><br/><b>Ведомственная организация: </b><?=$organization_id[$b['org_id']]['org_name']?><br/><b>Доступность: </b><?=$availability_id[$b['aviability_id']]?><br/><b>Виды спорта: </b><div><?$objects_sportzones_isset = array(); foreach ($objects_sportzones_id[$b['object_id']] as $sp):?><?if (in_array($sp['sport_name'], $objects_sportzones_isset) == false):?><p style="margin: 5px 0px 0px 0px;"><img style="width: 18px; height: 18px; display: inline; position: relative; top: -2px; margin-right: 5px" src="<?=$type_sport_id[$sp['sport_id']]['img']?>" /> <?=$sp['sport_name']?></p><?$objects_sportzones_isset[] = $sp['sport_name']; endif;?><?endforeach;?></div>',
            clusterCaption: '<?=$b['object']?>',
            iconCaption: '<?=$b['object']?>'
        }, {
            iconLayout: polygonLayout,
            iconShape: {
                type: 'Circle',
                coordinates: [30, 30],
                radius: 30
            },
            iconOffset: [-30, -30],
            iconColor: '<?=$objects_sportzones_id[$b['object_id']][0]['color']?>'
        });
        <?$i++; endforeach;?>

        // кластеризуем метки спортивных объектов
        clusterer.add(geoObjects);
        myMap.geoObjects.add(clusterer);

        myMap.setBounds(clusterer.getBounds(), {
            checkZoomRange: true
        });

        // добавляем кнопки на карту
        buttons = {
            heatmap: new ymaps.control.Button({
                data: {
                    content: 'Плотность населения'
                },
                options: {
                    selectOnClick: false,
                    maxWidth: 250
                }
            }),
            circle_mode_button: new ymaps.control.Button({
                data: {
                    content: 'Рисовать область'
                },
                options: {
                    selectOnClick: true,
                    maxWidth: 250
                }
            })
            // если пользователь авторизован, добавляем кнопку сохранения заданной окружности
            <?if ($_SESSION['user_id'] > 0):?>
            ,circle_mode_button_save: new ymaps.control.Button({
                data: {
                    content: 'Сохранить область'
                },
                options: {
                    selectOnClick: true,
                    maxWidth: 250,
                    visible: false
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

	        var myCircle = new ymaps.Circle([
	            [geoCenter[0], geoCenter[1]],
	            1000
	        ], {}, {
	            fillColor: "#52c87c47",
	            strokeColor: "#52c87c",
	            strokeOpacity: 0.8,
	            strokeWidth: 2
	        });

            // создаем событие изменения размеров и местоположения созданной окружности
	        myCircle.events.add('geometrychange', function () {

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
			      } else {
			        object.options.set('visible', false);
			      }
			    }
		    });

            // создаем событие нажатия на кнопку "Рисовать область"
			startEdit = 0;
            buttons.circle_mode_button.events.add('press', function () {
	    		if (startEdit == 0){

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
		    		myCircle.editor.startEditing();

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
            	      } else {
            	        object.options.set('visible', false);
            	      }
            	    }
            	    <?if ($_SESSION['user_id'] > 0):?>
                    // отображаем кнопку сохранения окружности
            	    buttons.circle_mode_button_save.options.set('visible', true);
                    <?endif;?>

                // удаляем окружность с карты при повторном нажатии кнопки "Рисовать область"
	    		} else {
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


            <?if ($_SESSION['user_id'] > 0):?>
            // если нажата кнопка сохранения нарисованной окружности, отправляем данные формы на сервер
            buttons.circle_mode_button_save.events.add('press', function () {
                var circleCoords = myCircle.geometry.getCoordinates();
                $('#form_saveCircle #radius').val(myCircle.geometry.getRadius());
                $('#form_saveCircle #lng').val(circleCoords[1]);
                $('#form_saveCircle #lat').val(circleCoords[0]);
                $('button#saveCircle').click();
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

    });
    </script>