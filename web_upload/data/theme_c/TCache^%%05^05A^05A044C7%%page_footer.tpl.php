<?php /* Smarty version 2.6.29, created on 2018-09-18 17:04:04
         compiled from page_footer.tpl */ ?>
</div>
            </section>
        </section>
        
        <footer id="footer">
			<div id="sm">
				Создано <a class="footer_link" href="http://www.sourcemod.net" target="_blank">SourceMod</a> / <a class="footer_link" href="<?php echo $this->_tpl_vars['THEME_LINK']; ?>
" target="_blank">Поддержка</a>
			</div>
            
            <ul class="f-menu">
                <li>Версия <b><?php echo $this->_tpl_vars['THEME_VERSION']; ?>
</b> (<?php echo $this->_tpl_vars['UPDATE_NUM']; ?>
)</li>
                <li><a href="https://sbpp.github.io/" target="_blank" class="footer_link">Команда <b>SourceBans++</b></a></li>
                <li><a href="https://github.com/SB-MaterialAdmin" target="_blank" class="footer_link"><b>MATERIAL Admin</b></a></li>
            </ul>
            <?php if ($this->_tpl_vars['show_gendata']): ?>
            <ul class="f-menu">
                <li>Сгенерировано за <?php echo $this->_tpl_vars['gendata_time']; ?>
 секунд</li>
                <li>Выполнено <?php echo $this->_tpl_vars['gendata_queries']; ?>
 запросов к БД</li>
            </ul>
            <?php endif; ?>
        </footer>

        <!-- Page Loader -->
        <?php if ($this->_tpl_vars['splash_screen']): ?>
        <div class="page-loader">
            <div class="preloader pls-blue">
                <svg class="pl-circular" viewBox="25 25 50 50">
                    <circle class="plc-path" cx="50" cy="50" r="20" />
                </svg>
                <p>Please wait...</p>
            </div>
        </div>
        <?php endif; ?>
        
		<!-- Javascript Libraries -->
        <script type="text/javascript" src="theme/vendors/bower_components/jquery/dist/jquery.min.js" ></script>
        <script type="text/javascript" src="theme/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js" ></script>
        
        <script type="text/javascript" src="theme/vendors/bower_components/flot/jquery.flot.js" ></script>
        <script type="text/javascript" src="theme/vendors/bower_components/flot/jquery.flot.resize.js" ></script>
        <script type="text/javascript" src="theme/vendors/bower_components/flot.curvedlines/curvedLines.js" ></script>
        <script type="text/javascript" src="theme/vendors/sparklines/jquery.sparkline.min.js" ></script>
        <script type="text/javascript" src="theme/vendors/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js" ></script>
        
        <script type="text/javascript" src="theme/vendors/bower_components/moment/min/moment.min.js" ></script>
        <script type="text/javascript" src="theme/vendors/bower_components/fullcalendar/dist/fullcalendar.min.js" ></script>
        <script type="text/javascript" src="theme/vendors/bower_components/simpleWeather/jquery.simpleWeather.min.js" ></script>
        <script type="text/javascript" src="theme/vendors/bower_components/Waves/dist/waves.min.js" ></script>
        <script type="text/javascript" src="theme/vendors/bootstrap-growl/bootstrap-growl.min.js" ></script>
        <script type="text/javascript" src="theme/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js" ></script>
        <script type="text/javascript" src="theme/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js" ></script>
        
        <!-- Placeholder for IE9 -->
        <!--[if IE 9 ]>
            <script type="text/javascript" src="theme/vendors/bower_components/jquery-placeholder/jquery.placeholder.min.js"></script>
        <![endif]-->
        
        <script type="text/javascript" src="theme/js/flot-charts/curved-line-chart.js" ></script>
        <script type="text/javascript" src="theme/js/flot-charts/line-chart.js" ></script>
        <script type="text/javascript" src="theme/js/charts.js" ></script>
       
		
        <script type="text/javascript" src="theme/js/functions.js" ></script>
        <script type="text/javascript" src="theme/js/demo.js" ></script>
		
		<script type="text/javascript" src="theme/vendors/summernote/dist/summernote-updated.min.js" ></script>
		
        <script type="text/javascript" src="theme/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js" ></script>
        <script type="text/javascript" src="theme/vendors/bower_components/chosen/chosen.jquery.min.js" ></script>
		
        <script type="text/javascript" src="theme/vendors/input-mask/input-mask.min.js"></script>
        
        <script src="theme/vendors/fileinput/fileinput.min.js"></script>
		
		<script>
		  $.noConflict();
		</script>
        
        <script>setInterval(xajax_CSRF, 15000);</script>

        <!-- Cron -->
        <script type="text/javascript" src="theme/js/cron.js"></script>
        <script type="text/javascript">RunCron("<?php echo $this->_tpl_vars['cron_token']; ?>
");</script>
	