<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title><?=$xc['title'];?></title>
        <meta content="<?=$xc['description'];?>" name="description" />
        <meta content="ThemeDesign" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <link rel="shortcut icon" href="<?=DOMAIN;?>/img/favicon.ico">

        <!--Morris Chart CSS -->
        <link rel="stylesheet" href="<?=DOMAIN;?>/tmp/assets/plugins/morris/morris.css">

        <link href="<?=DOMAIN;?>/tmp/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="<?=DOMAIN;?>/tmp/assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="<?=DOMAIN;?>/tmp/assets/css/style.css" rel="stylesheet" type="text/css">
        <link href="/css/select2.css?v=2" rel="stylesheet">
        
        <link href="<?=DOMAIN;?>/css/<?=$xc['style'];?>" rel="stylesheet" type="text/css">
        
        <?=$xc['head'];?>
        
        <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey=fcd54206-bdf2-4405-999c-6bae67631f97" type="text/javascript"></script>

        <script src="https://yastatic.net/s3/mapsapi-jslibs/heatmap/0.0.1/heatmap.min.js" type="text/javascript"></script>
        <script src="https://yastatic.net/s3/mapsapi-jslibs/area/0.0.1/util.calculateArea.min.js" type="text/javascript"></script>
        
    </head>


    <body class="fixed-left">

        <?require_once $_SERVER['DOCUMENT_ROOT'].'/modules/popup/includes/popupWindows.inc.php';?>

        <!-- Loader
        <div id="preloader"><div id="status"><div class="spinner"></div></div></div>
        -->
        <?if($xc['noMainTmp']==false):?>
        <!-- Begin page -->
        <div id="wrapper" class="enlarged">

            <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
                    <i class="ion-close"></i>
                </button>

                <div class="left-side-logo d-block d-lg-none">
                    <div class="text-center">

                        <a href="/" class="logo" style="font-size: 24px; color: #000;">
                          Old School
                        </a>
                    </div>
                </div>

                <div class="sidebar-inner slimscrollleft">

                    <div id="sidebar-menu">
                        <ul>
                            <li class="menu-title">Меню</li>

                            <li>
                                <a href="/" class="waves-effect">
                                    <i class="dripicons-location"></i>
                                    <span> Карта спорта </span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="/map3" class="waves-effect">
                                    <i class="dripicons-location"></i>
                                    <span> Карта OSM </span>
                                </a>
                            </li>

                            <?if($mapsMenu!=false):?>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-meter"></i><span> Дашборды </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="list-unstyled">
                                    <?foreach($mapsMenu as $val):?>
                                    <li><a href="<?=DOMAIN?>/map/<?=$val['id'];?>-<?=$val['pagename']?>"> <?=$val['title'];?></a></li>
                                    <?endforeach;?>
                                </ul>
                            </li>
                            <?endif;?>

                            <?if($dataList!=false):?>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-briefcase"></i> <span> Данные </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="list-unstyled">
                                    <?foreach($dataList as $val):?>
                                    <li><a href="<?=DOMAIN?>/data/<?=$val['id'];?>-<?=$val['pagename']?>"> <?=$val['title'];?></a></li>
                                    <?endforeach;?>
                                </ul>
                            </li>
                            <?endif;?>

                            <li>
                                <a href="<?=DOMAIN;?>/docs" class="waves-effect">
                                    <i class="dripicons-document"></i>
                                    <span> Документация </span>
                                </a>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-device-mobile"></i> <span> Сервисы </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="list-unstyled">
                                    <li><a target="_blank" href="https://dialogs.yandex.ru/store/skills/3c638471-sportzony-moskvy?utm_source=site&utm_medium=badge&utm_campaign=v1&utm_term=d1">&#127967; Спорт площадки</a></li>
                                    <li><a target="_blank" href="https://dialogs.yandex.ru/store/skills/41e6e58b-sportivnaya-moskv?utm_source=site&utm_medium=badge&utm_campaign=v1&utm_term=d1">&#127936; Спортивная Москва</a></li>
                                </ul>
                            </li>

                            <!--
                            <li>
                                <a href="<?=DOMAIN;?>/services" class="waves-effect">
                                    <i class="dripicons-device-mobile"></i>
                                    <span> Сервисы </span>
                                </a>
                            </li>
                            -->
                            
                            <li>
                                <a href="/calendar" class="waves-effect"><i class="dripicons-calendar"></i><span> Календарь </span></a>
                            </li>

                            <li>
                                <a href="/about" class="waves-effect">
                                    <i class="dripicons-trophy"></i>
                                    <span> О проекте </span>
                                </a>
                            </li>
                        </ul>

                        <div style="margin: 15px 0 0 30px;">
                        <!--
                        <a href="https://dialogs.yandex.ru/store/skills/3c638471-sportzony-moskvy?utm_source=site&utm_medium=badge&utm_campaign=v1&utm_term=d1" target="_blank"><img alt="Спортивные площадки" src="https://dialogs.s3.yandex.net/badges/v1-term1.svg"/></a>
                        <br /><br />
                        -->
                        <a href="https://dialogs.yandex.ru/store/skills/41e6e58b-sportivnaya-moskv?utm_source=site&utm_medium=badge&utm_campaign=v1&utm_term=d1" target="_blank"><img alt="Спортивная Москва" src="https://dialogs.s3.yandex.net/badges/v1-term1.svg"/></a>

                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div> <!-- end sidebarinner -->
            </div>
            <!-- Left Sidebar End -->

            <!-- Start right Content here -->

            <div class="content-page">
                <!-- Start content -->
                <div class="content" style="padding: 0 0 0 0;">

                    <!-- Top Bar Start -->
                    <div class="topbar">

                        <div class="topbar-left	d-none d-lg-block">
                            <div class="text-center">

                                <a href="/" class="logo" style="font-size: 24px; color: #fff; font-weight: bold;">
                                  <img src="<?=DOMAIN?>/img/logo3.png" width="100%" height="70" alt="Old School" />
                                </a>
                            </div>
                        </div>

                        <nav class="navbar-custom">

                            <ul class="list-inline float-right mb-0">
                                <li class="list-inline-item notification-list dropdown d-none d-sm-inline-block">
                                    <form method="post" action="" role="search" class="app-search">
                                        <div class="form-group mb-0">
                                            <input style="width: 300px;" type="search" id="jsSearchObjects" class="form-control jsSelectList" placeholder="Поиск объектов..." />
                                            <input type="hidden" id="jsSearchObjects_mod" value="user-objects" />
                                            <input type="hidden" id="jsSearchObjects_com" value="search" />
                                            <input type="hidden" id="jsSearchObjects_arr" value="" />
                                            <input type="hidden" name="sport_object_id" id="jsSearchObjects_id" class="jsClear" value="" />
     
                                            <div id="jsSearchObjects2" class="tmpAjaxListDiv hidden" style="border-radius: 5px; margin-top: 3px; width: 400px;"></div>
                                            <button type="button"><i class="fa fa-search"></i></button>
                                        </div>
                                    </form>
                                </li>

                                <li class="list-inline-item dropdown notification-list">
                                    <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
                                       aria-haspopup="false" aria-expanded="false">
                                        <?if(!empty($_SESSION['user_id'])):?>
                                          <img src="<?=DOMAIN;?>/img/users/<?=$_SESSION['avatar'];?>" alt="<?=$_SESSION['username'];?>" class="rounded-circle">
                                        <?else:?>
                                          <img src="<?=DOMAIN;?>/img/user3.jpg" alt="user" class="rounded-circle">
                                        <?endif;?>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown ">
                                        <?if(!empty($_SESSION['user_id'])):?>
                                        <a class="dropdown-item" href="#"><i class="mdi mdi-account-circle m-r-5 text-muted"></i> Профиль</a>
                                        <a class="dropdown-item" href="#"><i class="mdi mdi-settings m-r-5 text-muted"></i> Настройки</a>
                                        <a class="dropdown-item" href="<?=DOMAIN;?>/index.php?exit=1&url=<?=$_SERVER['REQUEST_URI'];?>"><i class="mdi mdi-logout m-r-5 text-muted"></i> Выход</a>
                                        <?else:?>
                                        <form method="post" action="" id="form_getLoginPopup">
                                          <input type="hidden" name="module" value="login" />
                                          <input type="hidden" name="component" value="" />
                                          <input type="hidden" name="url" value="<?=$_SERVER['REQUEST_URI'];?>" />
                                          <button class="send_form dropdown-item" id="getLoginPopup"><i class="mdi mdi-logout m-r-5 text-muted"></i> Войти</button>
                                        </form>
                                        <?endif;?>
                                    </div>
                                </li>

                            </ul>

                            <ul class="list-inline menu-left mb-0">
                                <li class="list-inline-item">
                                    <button type="button" class="button-menu-mobile open-left waves-effect">
                                        <i class="ion-navicon"></i>
                                    </button>
                                </li>
                            </ul>

                            <div class="clearfix"></div>

                        </nav>

                    </div>
                    <!-- Top Bar End -->

                    <?=$xc['content'];?>

                </div> <!-- content -->


                <footer class="footer">
                     © <?=date('Y')?> <b>Old School</b>
                </footer>

            </div>
            <!-- End Right content here -->

        </div>
        <!-- END wrapper -->


        <!-- jQuery  -->
        <script src="<?=DOMAIN;?>/tmp/assets/js/jquery.min.js"></script>
        <script src="/js/select2.full.min.js"></script>
        <script src="<?=DOMAIN;?>/js/scripts<?=$xc['js'];?>.js"></script>
        <script src="<?=DOMAIN;?>/tmp/assets/js/bootstrap.bundle.min.js"></script>
        <script src="<?=DOMAIN;?>/tmp/assets/js/modernizr.min.js"></script>
        <script src="<?=DOMAIN;?>/tmp/assets/js/detect.js"></script>
        <script src="<?=DOMAIN;?>/tmp/assets/js/fastclick.js"></script>
        <script src="<?=DOMAIN;?>/tmp/assets/js/jquery.slimscroll.js"></script>
        <script src="<?=DOMAIN;?>/tmp/assets/js/jquery.blockUI.js"></script>
        <script src="<?=DOMAIN;?>/tmp/assets/js/waves.js"></script>
        <script src="<?=DOMAIN;?>/tmp/assets/js/jquery.nicescroll.js"></script>
        <script src="<?=DOMAIN;?>/tmp/assets/js/jquery.scrollTo.min.js"></script>
        <script src="<?=DOMAIN;?>/tmp/assets/plugins/dropzone/dist/dropzone.js"></script>

        <!-- skycons -->
        <script src="<?=DOMAIN;?>/tmp/assets/plugins/skycons/skycons.min.js"></script>

        <!-- skycons -->
        <script src="<?=DOMAIN;?>/tmp/assets/plugins/peity/jquery.peity.min.js"></script>

        <!--Morris Chart-->
        <script src="<?=DOMAIN;?>/tmp/assets/plugins/morris/morris.min.js"></script>
        <script src="<?=DOMAIN;?>/tmp/assets/plugins/raphael/raphael-min.js"></script>

        <!-- dashboard -->
        <script src="<?=DOMAIN;?>/tmp/assets/pages/dashboard.js"></script>
        
        <!-- Jquery-Ui -->
        <?if($xc['module']=='calendar'):?>
        <script src="<?=DOMAIN;?>/tmp/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?=DOMAIN;?>/tmp/assets/plugins/moment/moment.js"></script>
        <script src='<?=DOMAIN;?>/tmp/assets/plugins/fullcalendar/js/fullcalendar.min.js'></script>
        <script src="<?=DOMAIN;?>/tmp/assets/pages/calendar-init.js"></script>
        <?endif;?>

        <!-- App js -->
        <script src="<?=DOMAIN;?>/tmp/assets/js/app.js"></script>
        
        <?else:?>
          <?=$xc['content'];?>
        <?endif;?>

    </body>
</html>