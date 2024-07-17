var FeedItemDetail = function() {
    var uiHelperSlick = function(){
        // Get each slider element (with .js-slider class)
        jQuery('.js-slider:not(.js-slider-enabled)').each(function(){
            var el = jQuery(this);

            // Add .js-slider-enabled class to tag it as activated
            el.addClass('js-slider-enabled');

            // Init slick slider
            el.slick({
                arrows: el.data('arrows') || false,
                dots: el.data('dots') || false,
                slidesToShow: el.data('slides-to-show') || 1,
                slidesToScroll: el.data('slides-to-scroll') || 1,
                centerMode: el.data('center-mode') || false,
                autoplay: el.data('autoplay') || false,
                autoplaySpeed: el.data('autoplay-speed') || 3000,
                infinite: typeof el.data('infinite') === 'undefined' ? true : el.data('infinite'),
            });
        });
    };
    return {
        init: function() {
        	uiHelperSlick();
        }
    };
}();

// Initialize when page loads
jQuery(function() { 
    FeedItemDetail.init();
});