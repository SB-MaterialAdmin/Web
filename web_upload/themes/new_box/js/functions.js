/*
 * Detact Mobile Browser
 */
if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
   jQuery('html').addClass('ismobile');
}

jQuery(window).load(function () {
    /* --------------------------------------------------------
     Page Loader
     -----------------------------------------------------------*/
    if(!jQuery('html').hasClass('ismobile')) {
        if(jQuery('.page-loader')[0]) {
            setTimeout (function () {
                jQuery('.page-loader').fadeOut();
            }, 500);

        }
    }
})

jQuery(document).ready(function(){
	/* --------------------------------------------------------
        Layout
    -----------------------------------------------------------*/
    (function () {

        //Get saved layout type from LocalStorage
        var layoutStatus = localStorage.getItem('ma-layout-status');

        if(!jQuery('#header-2')[0]) {  //Make it work only on normal headers
            if (layoutStatus == 1) {
                jQuery('body').addClass('sw-toggled');
                jQuery('#tw-switch').prop('checked', true);
            }
        }

        jQuery('body').on('change', '#toggle-width input:checkbox', function () {
            if (jQuery(this).is(':checked')) {
                setTimeout(function () {
                    jQuery('body').addClass('toggled sw-toggled');
                    localStorage.setItem('ma-layout-status', 1);
                }, 250);
            }
            else {
                setTimeout(function () {
                    jQuery('body').removeClass('toggled sw-toggled');
                    localStorage.setItem('ma-layout-status', 0);
                }, 250);
            }
        });
    })();

    /* --------------------------------------------------------
        Scrollbar
    -----------------------------------------------------------*/
    function scrollBar(selector, theme, mousewheelaxis) {
        jQuery(selector).mCustomScrollbar({
            theme: theme,
            scrollInertia: 100,
            axis:'yx',
            mouseWheel: {
                enable: true,
                axis: mousewheelaxis,
                preventDefault: true
            }
        });
    }

    if (!jQuery('html').hasClass('ismobile')) {
        //On Custom Class
        if (jQuery('.c-overflow')[0]) {
            scrollBar('.c-overflow', 'minimal-dark', 'y');
        }
    }

    /*
     * Top Search
     */
    (function(){
        jQuery('body').on('click', '#top-search > a', function(e){
            e.preventDefault();

            jQuery('#header').addClass('search-toggled');
            //jQuery('#top-search-wrap input').focus();
        });

        jQuery('body').on('click', '#top-search-close', function(e){
            e.preventDefault();

            jQuery('#header').removeClass('search-toggled');
        });
    })();

    /*
     * Sidebar
     */
    (function(){
        //Toggle
        jQuery('body').on('click', '#menu-trigger, #chat-trigger', function(e){
            e.preventDefault();
            var x = jQuery(this).data('trigger');

            jQuery(x).toggleClass('toggled');
            jQuery(this).toggleClass('open');

    	    //Close opened sub-menus
    	    jQuery('.sub-menu.toggled').not('.active').each(function(){
        		jQuery(this).removeClass('toggled');
        		jQuery(this).find('ul').hide();
    	    });



	    jQuery('.profile-menu .main-menu').hide();

            if (x == '#sidebar') {

                jQueryelem = '#sidebar';
                jQueryelem2 = '#menu-trigger';

                jQuery('#chat-trigger').removeClass('open');

                if (!jQuery('#chat').hasClass('toggled')) {
                    jQuery('#header').toggleClass('sidebar-toggled');
                }
                else {
                    jQuery('#chat').removeClass('toggled');
                }
            }

            if (x == '#chat') {
                jQueryelem = '#chat';
                jQueryelem2 = '#chat-trigger';

                jQuery('#menu-trigger').removeClass('open');

                if (!jQuery('#sidebar').hasClass('toggled')) {
                    jQuery('#header').toggleClass('sidebar-toggled');
                }
                else {
                    jQuery('#sidebar').removeClass('toggled');
                }
            }

            //When clicking outside
            if (jQuery('#header').hasClass('sidebar-toggled')) {
                jQuery(document).on('click', function (e) {
                    if ((jQuery(e.target).closest(jQueryelem).length === 0) && (jQuery(e.target).closest(jQueryelem2).length === 0)) {
                        setTimeout(function(){
                            jQuery(jQueryelem).removeClass('toggled');
                            jQuery('#header').removeClass('sidebar-toggled');
                            jQuery(jQueryelem2).removeClass('open');
                        });
                    }
                });
            }
        })

        //Submenu
        jQuery('body').on('click', '.sub-menu > a', function(e){
            e.preventDefault();
            jQuery(this).next().slideToggle(200);
            jQuery(this).parent().toggleClass('toggled');
        });
    })();

    /*
     * Clear Notification
     */
    jQuery('body').on('click', '[data-clear="notification"]', function(e){
      e.preventDefault();

      var x = jQuery(this).closest('.listview');
      var y = x.find('.lv-item');
      var z = y.size();

      jQuery(this).parent().fadeOut();

      x.find('.list-group').prepend('<i class="grid-loading hide-it"></i>');
      x.find('.grid-loading').fadeIn(1500);


      var w = 0;
      y.each(function(){
          var z = jQuery(this);
          setTimeout(function(){
          z.addClass('animated fadeOutRightBig').delay(1000).queue(function(){
              z.remove();
          });
          }, w+=150);
      })

	//Popup empty message
	setTimeout(function(){
	    jQuery('#notifications').addClass('empty');
	}, (z*150)+200);
    });

    /*
     * Dropdown Menu
     */
    if(jQuery('.dropdown')[0]) {
	//Propagate
	jQuery('body').on('click', '.dropdown.open .dropdown-menu', function(e){
	    e.stopPropagation();
	});

	jQuery('.dropdown').on('shown.bs.dropdown', function (e) {
	    if(jQuery(this).attr('data-animation')) {
		jQueryanimArray = [];
		jQueryanimation = jQuery(this).data('animation');
		jQueryanimArray = jQueryanimation.split(',');
		jQueryanimationIn = 'animated '+jQueryanimArray[0];
		jQueryanimationOut = 'animated '+ jQueryanimArray[1];
		jQueryanimationDuration = ''
		if(!jQueryanimArray[2]) {
		    jQueryanimationDuration = 500; //if duration is not defined, default is set to 500ms
		}
		else {
		    jQueryanimationDuration = jQueryanimArray[2];
		}

		jQuery(this).find('.dropdown-menu').removeClass(jQueryanimationOut)
		jQuery(this).find('.dropdown-menu').addClass(jQueryanimationIn);
	    }
	});

	jQuery('.dropdown').on('hide.bs.dropdown', function (e) {
	    if(jQuery(this).attr('data-animation')) {
    		e.preventDefault();
    		jQuerythis = jQuery(this);
    		jQuerydropdownMenu = jQuerythis.find('.dropdown-menu');

    		jQuerydropdownMenu.addClass(jQueryanimationOut);
    		setTimeout(function(){
    		    jQuerythis.removeClass('open')

    		}, jQueryanimationDuration);
    	    }
    	});
    }

    /*
     * Calendar Widget
     */
    if(jQuery('#calendar-widget')[0]) {
        (function(){
            jQuery('#calendar-widget').fullCalendar({
		        contentHeight: 'auto',
		        theme: true,
                header: {
                    right: '',
                    center: 'prev, title, next',
                    left: ''
                },
                defaultDate: '2014-06-12',
                editable: true,
                events: [
                    {
                        title: 'All Day',
                        start: '2014-06-01',
                        className: 'bgm-cyan'
                    },
                    {
                        title: 'Long Event',
                        start: '2014-06-07',
                        end: '2014-06-10',
                        className: 'bgm-orange'
                    },
                    {
                        id: 999,
                        title: 'Repeat',
                        start: '2014-06-09',
                        className: 'bgm-lightgreen'
                    },
                    {
                        id: 999,
                        title: 'Repeat',
                        start: '2014-06-16',
                        className: 'bgm-lightblue'
                    },
                    {
                        title: 'Meet',
                        start: '2014-06-12',
                        end: '2014-06-12',
                        className: 'bgm-green'
                    },
                    {
                        title: 'Lunch',
                        start: '2014-06-12',
                        className: 'bgm-cyan'
                    },
                    {
                        title: 'Birthday',
                        start: '2014-06-13',
                        className: 'bgm-amber'
                    },
                    {
                        title: 'Google',
                        url: 'http://google.com/',
                        start: '2014-06-28',
                        className: 'bgm-amber'
                    }
                ]
            });
        })();
    }

    /*
     * Weather Widget
     */
    if (jQuery('#weather-widget')[0]) {
        jQuery.simpleWeather({
            location: 'Austin, TX',
            woeid: '',
            unit: 'f',
            success: function(weather) {
                html = '<div class="weather-status">'+weather.temp+'&deg;'+weather.units.temp+'</div>';
                html += '<ul class="weather-info"><li>'+weather.city+', '+weather.region+'</li>';
                html += '<li class="currently">'+weather.currently+'</li></ul>';
                html += '<div class="weather-icon wi-'+weather.code+'"></div>';
                html += '<div class="dash-widget-footer"><div class="weather-list tomorrow">';
                html += '<span class="weather-list-icon wi-'+weather.forecast[2].code+'"></span><span>'+weather.forecast[1].high+'/'+weather.forecast[1].low+'</span><span>'+weather.forecast[1].text+'</span>';
                html += '</div>';
                html += '<div class="weather-list after-tomorrow">';
                html += '<span class="weather-list-icon wi-'+weather.forecast[2].code+'"></span><span>'+weather.forecast[2].high+'/'+weather.forecast[2].low+'</span><span>'+weather.forecast[2].text+'</span>';
                html += '</div></div>';
                jQuery("#weather-widget").html(html);
            },
            error: function(error) {
                jQuery("#weather-widget").html('<p>'+error+'</p>');
            }
        });
    }

    /*
     * Todo Add new item
     */
    if (jQuery('#todo-lists')[0]) {
    	//Add Todo Item
    	jQuery('body').on('click', '#add-tl-item .add-new-item', function(){
    	    jQuery(this).parent().addClass('toggled');
    	});

            //Dismiss
            jQuery('body').on('click', '.add-tl-actions > a', function(e){
                e.preventDefault();
                var x = jQuery(this).closest('#add-tl-item');
                var y = jQuery(this).data('tl-action');

                if (y == "dismiss") {
                    x.find('textarea').val('');
                    x.removeClass('toggled');
                }

                if (y == "save") {
                    x.find('textarea').val('');
                    x.removeClass('toggled');
                }
    	});
    }

    /*
     * Auto Hight Textarea
     */
    if (jQuery('.auto-size')[0]) {
	   autosize(jQuery('.auto-size'));
    }

    /*
    * Profile Menu
    */
    jQuery('body').on('click', '.profile-menu > a', function(e){
        e.preventDefault();
        jQuery(this).parent().toggleClass('toggled');
	    jQuery(this).next().slideToggle(200);
    });

    /*
     * Text Feild
     */

    //Add blue animated border and remove with condition when focus and blur
    if(jQuery('.fg-line')[0]) {
        jQuery('body').on('focus', '.fg-line .form-control', function(){
            jQuery(this).closest('.fg-line').addClass('fg-toggled');
        })

        jQuery('body').on('blur', '.form-control', function(){
            var p = jQuery(this).closest('.form-group, .input-group');
            var i = p.find('.form-control').val();

            if (p.hasClass('fg-float')) {
                if (i.length == 0) {
                    jQuery(this).closest('.fg-line').removeClass('fg-toggled');
                }
            }
            else {
                jQuery(this).closest('.fg-line').removeClass('fg-toggled');
            }
        });
    }

    //Add blue border for pre-valued fg-flot text feilds
    if(jQuery('.fg-float')[0]) {
        jQuery('.fg-float .form-control').each(function(){
            var i = jQuery(this).val();

            if (!i.length == 0) {
                jQuery(this).closest('.fg-line').addClass('fg-toggled');
            }

        });
    }

    /*
     * Audio and Video
     */
    if(jQuery('audio, video')[0]) {
        jQuery('video,audio').mediaelementplayer();
    }

    /*
     * Tag Select
     */
    if(jQuery('.chosen')[0]) {
        jQuery('.chosen').chosen({
            width: '100%',
            allow_single_deselect: true
        });
    }

    /*
     * Input Slider
     */
    //Basic
    if(jQuery('.input-slider')[0]) {
        jQuery('.input-slider').each(function(){
            var isStart = jQuery(this).data('is-start');

            jQuery(this).noUiSlider({
                start: isStart,
                range: {
                    'min': 0,
                    'max': 100,
                }
            });
        });
    }

    //Range slider
    if(jQuery('.input-slider-range')[0]) {
	jQuery('.input-slider-range').noUiSlider({
	    start: [30, 60],
	    range: {
		    'min': 0,
		    'max': 100
	    },
	    connect: true
	});
    }

    //Range slider with value
    if(jQuery('.input-slider-values')[0]) {
	jQuery('.input-slider-values').noUiSlider({
	    start: [ 45, 80 ],
	    connect: true,
	    direction: 'rtl',
	    behaviour: 'tap-drag',
	    range: {
		    'min': 0,
		    'max': 100
	    }
	});

	jQuery('.input-slider-values').Link('lower').to(jQuery('#value-lower'));
        jQuery('.input-slider-values').Link('upper').to(jQuery('#value-upper'), 'html');
    }

    /*
     * Input Mask
     */
    if (jQuery('input-mask')[0]) {
        jQuery('.input-mask').mask();
    }

    /*
     * Color Picker
     */
    if (jQuery('.color-picker')[0]) {
	    jQuery('.color-picker').each(function(){
            var colorOutput = jQuery(this).closest('.cp-container').find('.cp-value');
            jQuery(this).farbtastic(colorOutput);
        });
    }

    /*
     * HTML Editor
     */
    if (jQuery('.html-editor')[0]) {
	   jQuery('.html-editor').summernote({
            height: 150
        });
    }

    if(jQuery('.html-editor-click')[0]) {
        //Edit
        jQuery('body').on('click', '.hec-button', function(){
            jQuery('.html-editor-click').summernote({
                focus: true
            });
            jQuery('.hec-save').show();
        })

        //Save
        jQuery('body').on('click', '.hec-save', function(){
            jQuery('.html-editor-click').code();
            jQuery('.html-editor-click').destroy();
            jQuery('.hec-save').hide();
            notify('Content Saved Successfully!', 'success');
        });
    }

    //Air Mode
    if(jQuery('.html-editor-airmod')[0]) {
        jQuery('.html-editor-airmod').summernote({
            airMode: true
        });
    }

    /*
     * Date Time Picker
     */

    //Date Time Picker
    if (jQuery('.date-time-picker')[0]) {
	   jQuery('.date-time-picker').datetimepicker();
    }

    //Time
    if (jQuery('.time-picker')[0]) {
    	jQuery('.time-picker').datetimepicker({
    	    format: 'LT'
    	});
    }

    //Date
    if (jQuery('.date-picker')[0]) {
    	jQuery('.date-picker').datetimepicker({
    	    format: 'DD/MM/YYYY'
    	});
    }

    /*
     * Form Wizard
     */

    if (jQuery('.form-wizard-basic')[0]) {
    	jQuery('.form-wizard-basic').bootstrapWizard({
    	    tabClass: 'fw-nav',
            'nextSelector': '.next',
            'previousSelector': '.previous'
    	});
    }

    /*
     * Bootstrap Growl - Notifications popups
     */
    function notify(message, type){
        jQuery.growl({
            message: message
        },{
            type: type,
            allow_dismiss: false,
            label: 'Cancel',
            className: 'btn-xs btn-inverse',
            placement: {
                from: 'top',
                align: 'right'
            },
            delay: 2500,
            animate: {
                    enter: 'animated bounceIn',
                    exit: 'animated bounceOut'
            },
            offset: {
                x: 20,
                y: 85
            }
        });
    };

    /*
     * Waves Animation
     */
    (function(){
         Waves.attach('.btn:not(.btn-icon):not(.btn-float)');
         Waves.attach('.btn-icon, .btn-float', ['waves-circle', 'waves-float']);
        Waves.init();
    })();

    /*
     * Lightbox
     */
    if (jQuery('.lightbox')[0]) {
        jQuery('.lightbox').lightGallery({
            enableTouch: true
        });
    }

    /*
     * Link prevent
     */
    jQuery('body').on('click', '.a-prevent', function(e){
        e.preventDefault();
    });

    /*
     * Collaspe Fix
     */
    if (jQuery('.collapse')[0]) {

        //Add active class for opened items
        jQuery('.collapse').on('show.bs.collapse', function (e) {
            jQuery(this).closest('.panel').find('.panel-heading').addClass('active');
        });

        jQuery('.collapse').on('hide.bs.collapse', function (e) {
            jQuery(this).closest('.panel').find('.panel-heading').removeClass('active');
        });

        //Add active class for pre opened items
        jQuery('.collapse.in').each(function(){
            jQuery(this).closest('.panel').find('.panel-heading').addClass('active');
        });
    }

    /*
     * Tooltips
     */
    if (jQuery('[data-toggle="tooltip"]')[0]) {
        jQuery('[data-toggle="tooltip"]').tooltip();
    }

    /*
     * Popover
     */
    if (jQuery('[data-toggle="popover"]')[0]) {
        jQuery('[data-toggle="popover"]').popover();
    }

    /*
     * Message
     */

    //Actions
    if (jQuery('.on-select')[0]) {
        var checkboxes = '.lv-avatar-content input:checkbox';
        var actions = jQuery('.on-select').closest('.lv-actions');

        jQuery('body').on('click', checkboxes, function() {
            if (jQuery(checkboxes+':checked')[0]) {
                actions.addClass('toggled');
            }
            else {
                actions.removeClass('toggled');
            }
        });
    }

    if(jQuery('#ms-menu-trigger')[0]) {
        jQuery('body').on('click', '#ms-menu-trigger', function(e){
            e.preventDefault();
            jQuery(this).toggleClass('open');
            jQuery('.ms-menu').toggleClass('toggled');
        });
    }

    /*
     * Login
     */
    if (jQuery('.login-content')[0]) {
        //Add class to HTML. This is used to center align the logn box
        //jQuery('html').addClass('login-content');

        jQuery('body').on('click', '.login-navigation > li', function(){
            var z = jQuery(this).data('block');
            var t = jQuery(this).closest('.lc-block');

            t.removeClass('toggled');

            setTimeout(function(){
                jQuery(z).addClass('toggled');
            });

        })
    }

    /*
     * Fullscreen Browsing
     */
    if (jQuery('[data-action="fullscreen"]')[0]) {
	var fs = jQuery("[data-action='fullscreen']");
	fs.on('click', function(e) {
	    e.preventDefault();

	    //Launch
	    function launchIntoFullscreen(element) {

		if(element.requestFullscreen) {
		    element.requestFullscreen();
		} else if(element.mozRequestFullScreen) {
		    element.mozRequestFullScreen();
		} else if(element.webkitRequestFullscreen) {
		    element.webkitRequestFullscreen();
		} else if(element.msRequestFullscreen) {
		    element.msRequestFullscreen();
		}
	    }

	    //Exit
	    function exitFullscreen() {

		if(document.exitFullscreen) {
		    document.exitFullscreen();
		} else if(document.mozCancelFullScreen) {
		    document.mozCancelFullScreen();
		} else if(document.webkitExitFullscreen) {
		    document.webkitExitFullscreen();
		}
	    }

	    launchIntoFullscreen(document.documentElement);
	    fs.closest('.dropdown').removeClass('open');
	});
    }

    /*
     * Clear Local Storage
     */
    if (jQuery('[data-action="clear-localstorage"]')[0]) {
        var cls = jQuery('[data-action="clear-localstorage"]');

        cls.on('click', function(e) {
            e.preventDefault();

            swal({
                title: "Are you sure?",
                text: "All your saved localStorage values will be removed",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function(){
                localStorage.clear();
                swal("Done!", "localStorage is cleared", "success");
            });
        });
    }

    /*
     * Profile Edit Toggle
     */
    if (jQuery('[data-pmb-action]')[0]) {
        jQuery('body').on('click', '[data-pmb-action]', function(e){
            e.preventDefault();
            var d = jQuery(this).data('pmb-action');

            if (d === "edit") {
                jQuery(this).closest('.pmb-block').toggleClass('toggled');
            }

            if (d === "reset") {
                jQuery(this).closest('.pmb-block').removeClass('toggled');
            }


        });
    }

    /*
     * IE 9 Placeholder
     */
    if(jQuery('html').hasClass('ie9')) {
        jQuery('input, textarea').placeholder({
            customClass: 'ie9-placeholder'
        });
    }


    /*
     * Listview Search
     */
    if (jQuery('.lvh-search-trigger')[0]) {


        jQuery('body').on('click', '.lvh-search-trigger', function(e){
            e.preventDefault();
            x = jQuery(this).closest('.lv-header-alt').find('.lvh-search');

            x.fadeIn(300);
            x.find('.lvhs-input').focus();
        });

        //Close Search
        jQuery('body').on('click', '.lvh-search-close', function(){
            x.fadeOut(300);
            setTimeout(function(){
                x.find('.lvhs-input').val('');
            }, 350);
        })
    }


    /*
     * Print
     */
    if (jQuery('[data-action="print"]')[0]) {
        jQuery('body').on('click', '[data-action="print"]', function(e){
            e.preventDefault();

            window.print();
        })
    }

    /*
     * Typeahead Auto Complete
     */
     if(jQuery('.typeahead')[0]) {

          var statesArray = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
            'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
            'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
            'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
            'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
            'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
            'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
            'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
            'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
          ];
        var states = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            local: statesArray
        });

        jQuery('.typeahead').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
          name: 'states',
          source: states
        });
    }


    /*
     * Wall
     */
    if (jQuery('.wcc-toggle')[0]) {
        var z = '<div class="wcc-inner">' +
                    '<textarea class="wcci-text auto-size" placeholder="Write Something..."></textarea>' +
                '</div>' +
                '<div class="m-t-15">' +
                    '<button class="btn btn-sm btn-primary">Post</button>' +
                    '<button class="btn btn-sm btn-link wcc-cencel">Cancel</button>' +
                '</div>'


        jQuery('body').on('click', '.wcc-toggle', function() {
            jQuery(this).parent().html(z);
            autosize(jQuery('.auto-size')); //Reload Auto size textarea
        });

        //Cancel
        jQuery('body').on('click', '.wcc-cencel', function(e) {
            e.preventDefault();

            jQuery(this).closest('.wc-comment').find('.wcc-inner').addClass('wcc-toggle').html('Write Something...')
        });

    }

    /*
     * Skin Change
     */
    jQuery('body').on('click', '[data-skin]', function() {
        var currentSkin = jQuery('[data-current-skin]').data('current-skin');
        var skin = jQuery(this).data('skin');

        jQuery('[data-current-skin]').attr('data-current-skin', skin)

    });

});