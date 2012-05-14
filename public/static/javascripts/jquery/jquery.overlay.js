/*
 * jQuery Simple Overlay
 * A jQuery Plugin for creating a simple, customizable overlay. Supports multiple instances,
 * custom callbacks, hide on click, glossy effect, and more.
 *
 * Copyright 2011 Tom McFarlin, http://tommcfarlin.com, @moretom
 * Released under the MIT License
 *
 * http://moreco.de/simple-overlay
 */

(function($) {

	$.fn.overlay = function(options) {

    var opts = $.extend({}, $.fn.overlay.defaults, options);
	// If overlayClass is defined we don't want to use opts.color since it will override 
	// the background from the element.
	if( opts.overlayClass ) {
		delete opts.color;
	}
	
		return this.each(function(evt) {
      if(!$(this).hasClass('overlay-trigger')) {
        show(create($(this), opts), opts);
      }
		});
    
	}; // end overlay

  /*--------------------------------------------------*
   * helper functions
   *--------------------------------------------------*/
  
  /**
   * Creates the overlay element, applies the styles as specified in the 
   * options, and sets up the event handlers for closing the overlay.
   *
   * opts The plugin's array options.
   */
  function create($src, opts) {
  
    // prevents adding multiple overlays to a container
    $src.addClass('overlay-trigger');
  
    // create the overlay and add it to the dom
    var iTop = 0;
    if($.browser.mozilla && opts.container.toString() === 'body') { 
      iTop = $('html').scrollTop();
    } else {
      iTop = $(opts.container).scrollTop();
    } // end if/else
    
    var overlay = null;
    if($src.css('position') === 'relative') {
    
    	overlay = $('<div></div>')
    	  .addClass(opts.overlayClass)
	      .css({
	        background: opts.color,
	        opacity: opts.opacity,
	        top: $src.offset().top,
	        left: $src.offset().left,
	        width: $src.width(),
	        height: $src.height(),
	        position: 'absolute',
	        zIndex: 1000,
	        display: 'none',
	        overflow: 'hidden'
	      });
    
    } else {
    
	    overlay = $('<div></div>')
	      .addClass(opts.overlayClass)
	      .css({
	        background: opts.color,
	        opacity: opts.opacity,
	        top: opts.container.toString() === 'body' ? iTop : $(opts.container).offset().top,
	        left: $(opts.container).offset().left,
	        width: opts.container === 'body' ? '100%' : $(opts.container).width(),
	        height: opts.container === 'body' ? '100%' : $(opts.container).height(),
	        position: 'absolute',
	        zIndex: 1000,
	        display: 'none',
	        overflow: 'hidden'
	      });
    
    } // end if/else
    

    // if specified, apply the gloss
    if(opts.glossy) {
      applyGloss(opts, overlay);     
    } // end if
    
    // setup the event handlers for closing the overlay
    if(opts.closeOnClick) {
      $(overlay).click(function() {
        close(overlay, opts);
        $src.removeClass('overlay-trigger');
      });
    } // end if
    
    // finally add the overlay
    $(opts.container).append(overlay);
   
    return overlay;
    
  } // end createOverlay
  
  /**
   * Displays the overlay using the effect specified in the options. Optionally
   * triggers the onShow callback function.
   *
   * opts The plugin's array options.
   */
  function show(overlay, opts) {
    
    switch(opts.effect.toString().toLowerCase()) {
    
      case 'fade':
        $(overlay).fadeIn('fast', opts.onShow);
        break;
      
      case 'slide':
        $(overlay).slideDown('fast', opts.onShow);
        break;
        
      default:
        $(overlay).show(opts.onShow);
        break;
    
    } // end switch/case
    
    $(opts.container).css('overflow', 'hidden');
    
  } // end show
  
  /**
   * Hides the overlay using the effect specified in the options. Optionally
   * triggers the onHide callback function.
   *
   * opts The plugin's array options.
   */
  function close(overlay, opts) {
    
    switch(opts.effect.toString().toLowerCase()) {
        
      case 'fade':
        $(overlay).fadeOut('fast', function() {
          if(opts.onHide) {
            opts.onHide();
          }
          $(this).remove();
        });
        break;
            
      case 'slide':
        $(overlay).slideUp('fast', function() {
          if(opts.onHide) {
            opts.onHide();
          }
          $(this).remove();
        });
        break;
            
      default:
        $(overlay).hide();
        if(opts.onHide) {
          opts.onHide();
        }
        $(overlay).remove();
        break;
            
    } // end switch/case
    
    $(opts.container).css('overflow', 'auto');
    
  } // end close
  
  /**
   * Adds the gloss effect to the overlay.
   *
   * opts     The plugin's options array
   * overlay  The overlay on which the gloss will be applied
   */
  function applyGloss(opts, overlay) {
  
    var gloss = $('<div></div>');
    $(gloss).css({
      background: '#fff',
      opacity: 0.2,
      width: '200%',
      height: '100%',
      position: 'absolute',
      zIndex: 1001,
      msTransform: 'rotate(45deg)',
      webkitTransform: 'rotate(45deg)',
      oTransform: 'rotate(45deg)'
    });
      
    // at the time of development, mozTransform didn't work with >= jQuery 1.5
    if($.browser.mozilla) {
     $(gloss).css('-moz-transform', 'rotate(45deg');
    } // end if
     
    $(overlay).append(gloss);
    
  } // end applyGloss
 
  /*--------------------------------------------------*
   * default settings
   *--------------------------------------------------*/
   
	$.fn.overlay.defaults = {
    color: '#000',
	overlayClass: 'overlay',
    opacity: 0.5,
    effect: 'none',
    onShow: null,
    onHide: null,
    closeOnClick: false,
    glossy: false,
    container: 'body'
	}; // end defaults

})(jQuery);