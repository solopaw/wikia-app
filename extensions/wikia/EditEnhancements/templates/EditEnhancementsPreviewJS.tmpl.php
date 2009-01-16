<script type="text/javascript">
EditEnhancementsPreview = {

	timestamp: 0,

	calculate: function() {
		jQuery.noConflict();

		editBarHeight = jQuery('#edit_enhancements_toolbar').outerHeight();
		scrollBottomOffset = YAHOO.util.Dom.getDocumentScrollTop() + YAHOO.util.Dom.getViewportHeight();

		if (typeof FCK == 'undefined') {
			textAreaOffset = jQuery('#wpTextbox1').offset().top + jQuery('#wpTextbox1').outerHeight();
		}
		else {
			textAreaOffset = jQuery('#wpTextbox1___Frame').offset().top + jQuery('#wpTextbox1___Frame').outerHeight();
		}

		// choose positioning method
		if ( scrollBottomOffset < (textAreaOffset + editBarHeight + 8) ) {
			bodyContentLeft = jQuery("#bodyContent").offset().left;
			bodyContentWidth = jQuery("#bodyContent").width();
			widthDiff = jQuery("#edit_enhancements_toolbar").outerWidth(true) - jQuery("#edit_enhancements_toolbar").width();

			jQuery("#edit_enhancements_toolbar").css({'left' : bodyContentLeft + 'px', 'width' : parseInt(bodyContentWidth) - parseInt(widthDiff) + 'px'}).removeClass('edit_enhancements_toolbar_static').addClass('edit_enhancements_toolbar_fixed');

			// add margin-top to #editpage-copywarn to keep the same scroll height
			jQuery('#editpage-copywarn').css('marginTop', editBarHeight + 10 + 'px');
		}
		else {
			jQuery("#edit_enhancements_toolbar").removeClass('edit_enhancements_toolbar_fixed').addClass('edit_enhancements_toolbar_static');

			// remove margin-top from #editpage-copywarn to keep the same scroll height
			jQuery('#editpage-copywarn').css('marginTop', '0px');
		}
	},

	onEvent: function() {
		// update timestamp - do interval for next x miliseconds
		this.timestamp = (new Date()).getTime() + 1*1000

		// recalculate position
		this.calculate();

		// start interval for smooth scrolling
		if (!this.interval) {
			this.interval = setInterval(this.loop, 25);
		}
	},

	interval: false,

	loop: function() {
		EditEnhancementsPreview.calculate();

		// we're done, clear interval
		if ( EditEnhancementsPreview.timestamp < (new Date()).getTime() ) {
			clearInterval(EditEnhancementsPreview.interval);
			EditEnhancementsPreview.interval = false;
		}
	}
};

YAHOO.util.Event.on(window, 'load',   function() {EditEnhancementsPreview.onEvent();});
YAHOO.util.Event.on(window, 'resize', function() {EditEnhancementsPreview.onEvent();});
YAHOO.util.Event.on(window, 'scroll', function() {EditEnhancementsPreview.onEvent();});
</script>
