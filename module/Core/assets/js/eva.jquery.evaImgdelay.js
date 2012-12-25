/**
 * Eva Javascript Plugin
 *
 * @author     XuQian(AlloVince) <allo.vince@gmail.com>
 * @copyright  2011 AlloVince
 * @version    1.00
 */
(function( $ ){
 	var defaultOptions  = {
		callback : null
	},
	
	methods = {
		init : function(options) {
			var figures = $(this);
			options = methods.getOptions(options);
			figures.each(function(){
				var figure = $(this);
				if(figure.hasClass("loaded")) {
					return false;
				}
				var imgSrc = figure.attr("data-img");
				if(imgSrc) {
					methods.imgLoader.call(figure, imgSrc, function(){
						figure.html('<img src="' + imgSrc + '" alt="" class="hide" />');
						figure.addClass('loaded').find("img").fadeIn('slow');
					});
				}
				return true;
			});

			if(options.callback && typeof options.callback === 'function') {
				options.callback();
			}
			return false;
		},

		imgLoader : function(src, callback, image){
    		var self = this;
			image = image || new Image();
			setTimeout(function() {
				if (image.src != src) {
					image.src = src;
				}
				if (!image.complete) {
					return methods.imgLoader(src, callback, image);
				}
				
				$(self).attr('src',src);
				callback.call(self);
				return false;
			}, 50);
			return false; 
		},

		getOptions : function(options){
			var defaultOpt = $.extend({}, defaultOptions);
			return $.extend(defaultOpt, options);
		},

		effects : {
			noEffect : function(options){
			}
		},
		events : {
		}
	};
	
	$.fn.evaImgdelay = function( method ) {
		if ( methods[method] ) {
			methods.init();
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply(this, arguments);
		} else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.evaImgdelay' );
		}

		return false;
	};

	$.evaImgdelay = methods;
})( jQuery );
