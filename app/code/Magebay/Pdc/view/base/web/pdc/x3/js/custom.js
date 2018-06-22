var mst = jQuery.noConflict();
mst(document).ready(function($){
    /* Hide the app content expanded as default of first load on small screen size device */
    if($(top.window).width()< 750){
        $(".pdc-area-left .tab-content, .pdc-area-main").addClass('expand-main');
        $(".pdc-area-left").addClass('collapse-left');
    }

    /* Button collapse the app content */
    $("#toggle-app-button").click(function(){
        $(".pdc-area-left .tab-content, .pdc-area-main").addClass('expand-main');
        $(".pdc-area-left").addClass('collapse-left');
		$(".pdc-area-left .pdc-tabs ul.tabs-left li").removeClass("active");
    });
	if($(top.window).width()< 750){
		$(".pdc-area-main").click(function(){ 
			$(".pdc-area-left .tab-content, .pdc-area-main").addClass('expand-main');
			$(".pdc-area-left").addClass('collapse-left');
			$(".pdc-area-left .pdc-tabs ul.tabs-left li").removeClass("active");
		});
	 }
    /* Expanded the app content by click on the left app */
    $(".pdc-area-left .pdc-tabs > ul.nav > li > a").click(function(){
        $(".pdc-area-left .tab-content, .pdc-area-main").removeClass('expand-main');
        $(".pdc-area-left").removeClass('collapse-left');
    });	

    //End
    //Fancybox
    $('.fancybox').fancybox({
        closeBtn   : false,
        title      :false
    });
    $(".pdc-close-popup").click(function(){
        $.fancybox.close({});
    });
    //
    // imagelistexpander
    /* $('.gallery-items').imagelistexpander({
            prefix: "gallery-"
        }); */
    $(".pdc-item-tool ul.pdc-color-list").hide();
    $(".pdc-item-tool > h3").click(function(event) {
        event.stopPropagation();
        $(this).next().addClass('current').toggle();
        ObjEvents.hide_popup_block();
    });	

    $(".pdc-fonts-size ul li a").click(function() {
        var text = $(this).html();
        $(".pdc-fonts-size h3 span").html(text);
        $(".pdc-item-tool ul").hide();
        ObjEvents.editText('fontSize',text);
    });	
    $(".pdc-fonts-family ul li a").click(function() {
        var text = $(this).html();
        ObjEvents.editText();
        if(text.length > 9) {
            text = text.substring(0,9)+' ...';
        }
        $(".pdc-fonts-family h3 span").html(text);
        $(".pdc-item-tool ul").hide();
    });					
    $(document).click( function(){			
        //$(".pdc-item-tool > ul").hide();
    });
    //
    //
    $(".pdc-area-left .tab-content .tab-pane > ul > li").click(function(){
        if ( $(this).hasClass("active") ) {
            $(".pdc-area-left .tab-content .tab-pane > ul > li").addClass('opacity');
        }else{
            $(".pdc-area-left .tab-content .tab-pane > ul > li").removeClass('opacity');
        }
    });
    $(".pdc-area-left .tab-content .tab-pane > ul > li").hover(
        function(){
            $(this).addClass('over');
            $(".pdc-area-left .tab-content .tab-pane > ul > li").addClass('opacity');
        }, 
        function(){
            $(this).removeClass('over');
            $(".pdc-area-left .tab-content .tab-pane > ul > li").removeClass('opacity');
        }
    );
    // tab on QRcode
    $('.pdc-show-content-detail .nav-tabs a').click(function (e) {
      e.preventDefault()
      $(this).tab('show')
    });
    $(".pdc-toolbar ul li").click(function(){
        if ( $(".pdc-toolbar .tab-content > .tab-pane").hasClass("active") ) {
            $(".pdc-scroll").addClass('expanded');
        }else{
            $(".pdc-scroll").removeClass('expanded');
        }
    });
	// Toggle Sides Tab
	$('.sides-tab span.icon').click(function (e) {
      e.preventDefault();
      $(this).prev().slideToggle("fast");
      $(this).parent().next().slideToggle("fast");
    });
	//Tooltip
	 $("body").tooltip({ selector: '[data-x3=tooltip]' });
	
	// Toggle FullScreen
	$('.topbar-content .show-full-screen').click(function (e) {
		e.preventDefault();
		$("body").toggleClass("small-popup");	  
      /* $(this).toggleClass("");	 */  
    });	
	
	/* If only one tab in Product Design then hide the tab button */
	/* if ( $('#p-design-tab ul.nav-tabs li').length <2 ) { $('#p-design-tab').addClass('hide-tab-btn');
	console.log('Tab length:'+$('#p-design-tab ul.nav-tabs li').length);
	} */
	$('#accordion_design .panel:first-child .panel-title > a').removeClass('collapsed');
	$('#accordion_design .panel:first-child .panel-collapse').addClass('in');
	//
	// Dropdown text effects
	$("ul.dropdown-effects").on("click", ".init", function() {
		$(this).closest("ul.dropdown-effects").children('li:not(.init)').toggle();
	});

	var allOptions = $("ul.dropdown-effects").children('li:not(.init)');
	$("ul.dropdown-effects").on("click", "li:not(.init)", function() {
		allOptions.removeClass('selected');
		$(this).addClass('selected');
		$("ul.dropdown-effects").children('.init').html($(this).html());
		allOptions.toggle();
	});
	//

	$('.pdc-edit-close').click(function() {
		$(this).parent().hide();
	});
});

