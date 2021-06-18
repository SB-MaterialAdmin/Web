<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Обновление SourceBans++</title>
        <script type="text/javascript" src="scripts/mootools.js"></script>
        <script type="text/javascript" src="scripts/sourcebans.js"></script>
        <link rel="Shortcut Icon" href="images/favicon.ico">
        
        <!-- Vendor CSS -->
        <link href="../themes/new_box/vendors/bower_components/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet">
        <link href="../themes/new_box/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="../themes/new_box/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        <link href="../themes/new_box/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="../themes/new_box/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Roboto:900italic,900,700italic,700,500italic,500,400italic,400,300italic,300' rel='stylesheet' type='text/css'>
        <link href="../themes/new_box/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">
        <link href="../themes/new_box/vendors/bower_components/chosen/chosen.min.css" rel="stylesheet">
        <link href="../themes/new_box/vendors/summernote/dist/summernote.css" rel="stylesheet">   
        <!-- CSS -->
        <link href="../themes/new_box/css/app.min.1.css" rel="stylesheet">
        <link href="../themes/new_box/css/app.min.2.css" rel="stylesheet">
        <link href="../themes/new_box/css/css_sup.css" rel="stylesheet">
    </head>
    <body class="toggled sw-toggled">
        <header id="header" class="clearfix" data-current-skin="blue">
            <ul class="header-inner">
                <li id="menu-trigger" data-trigger="#sidebar">
                    <div class="line-wrap">
                        <div class="line top"></div>
                        <div class="line center"></div>
                        <div class="line bottom"></div>
                    </div>
                </li>
                <li class="logo hidden-xs">
                    <a href="../index.php">
                        SourceBans :: Materials
                    </a>
                </li>
            </ul>
        </header>
        
        <section id="main" data-layout="layout-1">
            <aside id="sidebar" class="sidebar c-overflow">
                <div class="profile-menu">
                    <a href="#">
                        <div class="profile-pic">
                            <img src="../themes/new_box/img/profile-pics/1.jpg" />
                        </div>

                        <div class="profile-info">
                            Обновление
                        </div>
                    </a>
                </div>

                <ul class="main-menu">
                    <li class='nonactive'><a href="http://www.sourcebans.net" target="_blank"><i class='zmdi zmdi-globe'></i> SourceBans</a></li>
                    <li class='nonactive'><a href="http://www.sourcemod.net" target="_blank"><i class='zmdi zmdi-flower-alt'></i> SourceMod</a></li>
                </ul>
            </aside>
            <section id="content">
                <div class="container">
                    <div class="block-header">
                        <h2 id="content_title">
                            Обновление системы
                        </h2>
                    </div>
                <div id="msg-red-debug" style="display:none;" >
                    <i><img src="./images/warning.png" alt="Warning" /></i>
                    <b>Debug</b>
                    <br />
                    <div id="debug-text"></div></i>
                </div>
                <div class="card login-content go-social">
                    <div class="card-header">
                        <h2>
                            Обновление...
                        </h2>
                    </div>
                    <div class="card-body card-padding">
                        <p>{$setup}</p>
                        {if $progress}<br /><p>{$progress}</p>{/if}
                    </div>
                </div>
            </section>
        </section>
        <footer id="footer">
            <div id="sm">
                Создано <a class="footer_link" href="http://www.sourcemod.net" target="_blank">SourceMod</a>
            </div>
        </footer>

        <!--[if lt IE 7]>
        <script defer type="text/javascript" src="./scripts/pngfix.js"></script>
        <![endif]-->

		<!-- Javascript Libraries -->
        <script src="../themes/new_box/vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="../themes/new_box/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        
        <script src="../themes/new_box/vendors/bower_components/flot/jquery.flot.js"></script>
        <script src="../themes/new_box/vendors/bower_components/flot/jquery.flot.resize.js"></script>
        <script src="../themes/new_box/vendors/bower_components/flot.curvedlines/curvedLines.js"></script>
        <script src="../themes/new_box/vendors/sparklines/jquery.sparkline.min.js"></script>
        <script src="../themes/new_box/vendors/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
        
        <script src="../themes/new_box/vendors/bower_components/moment/min/moment.min.js"></script>
        <script src="../themes/new_box/vendors/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
        <script src="../themes/new_box/vendors/bower_components/simpleWeather/jquery.simpleWeather.min.js"></script>
        <script src="../themes/new_box/vendors/bower_components/Waves/dist/waves.min.js"></script>
        <script src="../themes/new_box/vendors/bootstrap-growl/bootstrap-growl.min.js"></script>
        <script src="../themes/new_box/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js"></script>
        <script src="../themes/new_box/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="../themes/new_box/js/flot-charts/curved-line-chart.js"></script>
        <script src="../themes/new_box/js/flot-charts/line-chart.js"></script>
        <script src="../themes/new_box/js/charts.js"></script>
        
        <script src="../themes/new_box/js/charts.js"></script>
        <script src="../themes/new_box/js/functions.js"></script>
        <script src="../themes/new_box/js/demo.js"></script>
        <script src="../themes/new_box/vendors/summernote/dist/summernote-updated.min.js"></script>
        <script src="../themes/new_box/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js"></script>
        <script src="../themes/new_box/vendors/bower_components/chosen/chosen.jquery.min.js"></script>
        <script>
            $.noConflict();
        </script>
    </body>
</html>
