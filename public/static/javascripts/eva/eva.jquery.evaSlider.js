/**
 * evaTip, a simple tooltip for jQuery
 * Eva Javascript Plugin
 *
 * @author     XuQian(AlloVince) <allo.vince@gmail.com>
 * @copyright  2011 AlloVince
 * @version    1.00
 */
(function( $ ){
 	var defaultOptions  = {
		prevSelector : '.carousel-control.left',
		nextSelector : '.carousel-control.right',
		numbersSelector : '.carousel-numbers',
		numberSelector : '.carousel-number',
		itemSelector : '.item',
		activeitemSelector : '.item.active',
		interval : 5000,
		effectSpeed : 1000,
		pause : 'hover',
		effect : 'slide',
		direction : 'right',
		autostart : true, //自动开始轮播
		initPrevNext : true,
		initNumbers : false,
		showPrevNext : true,
		showNumbers : true,
		width : null,
		height : null,
		beforeInit : null,
		beforeSlide : null,
		afterSlide : null
	},

	methods = {
		initPrevNext : function(slider){
			var id = slider.attr("id"),
			template = '<a class="carousel-control left" href="#' + id + '" data-slide="prev">&lsaquo;</a><a class="carousel-control right" href="#' + id + '" data-slide="next">&rsaquo;</a>';
			slider.append(template);
		},
		initNumbers : function(slider, options){
			var id = slider.attr("id"),
			itemHandler = slider.find(options.itemSelector),
			index = slider.find(options.activeitemSelector).index(),
			itemCount = itemHandler.length,
			numbers = [],
			i = 0,
			active = '',
			indexNumber = 0,
			template = '<div class="carousel-numbers">';

			for(i; i < itemCount; i++){
				active = i === index ? 'active' : '';
				indexNumber = i + 1;
				numbers.push('<a class="carousel-number ' + active + '" href="#' + id +'" data-slide="' + indexNumber +'">' + indexNumber +'</a>');
			}

			template += numbers.join("") + '</div>';
			slider.append(template);
		},
		init : function(opt) {
			//not binding elements;
			if(this === methods) {
				return false;
			}

			var sliders = $(this);
			var options = methods.getOptions(opt);

			sliders.each(function(){
				var slider = $(this);


				if(options.initPrevNext === true) { 
					methods.initPrevNext(slider);
				}

				if(options.initNumbers === true) {
					methods.initNumbers(slider, options);
				}

				var data = {
					options : $.extend({}, options),
					prevHandler : slider.find(options.prevSelector),
					nextHandler : slider.find(options.nextSelector),
					numbersHandler : slider.find(options.numbersSelector),
					numberHandler : slider.find(options.numberSelector),
					itemHandler : slider.find(options.itemSelector),
					index : 0,
					max : slider.find(options.itemSelector).length,
					timer : null
				};

				var index = slider.find(options.activeitemSelector).index();
				var width = options.width || data.itemHandler.eq(index).width(); 
				var height = options.height || data.itemHandler.eq(index).height(); 

				data.width = width;
				data.height = height;
				data.index = index;
				slider.width(width).height(height);
				slider.find(".carousel-inner").width(width).height(height);
				data.itemHandler.css({
					"display" : "none",
					"width" : width,
					"height" : height,
					"position" : "absolute",
					"top" : 0,
					"left" : 0
				});
				data.itemHandler.find("img").width(width).height(height);
				data.itemHandler.eq(index).show();



				if(options.showPrevNext === false) { 
					data.prevHandler.hide();
					data.nextHandler.hide();
				}

				if(options.showNumbers === false) {
					data.numbersHandler.hide();
				}

				data.prevHandler.on("click", slider, methods.events.onClickPrevHandler);
				data.nextHandler.on("click", slider, methods.events.onClickNextHandler);
				data.numberHandler.on("click", slider, methods.events.onClickNumberHandler);
				data.itemHandler.on("mouseover", slider, methods.events.onMouseenterItemHandler);
				data.itemHandler.on("mouseout", slider, methods.events.onMouseleaveItemHandler);
				
				slider.data("evaSlider", data);

				//Start timer at last
				if(options.autostart === true) {
					methods.start(slider);
				}
			});

			return false;
		},

		getOptions : function(options){
			var defaultOpt = $.extend({}, defaultOptions);
			return $.extend(defaultOpt, options);
		},

		getData : function(slider){
			return $(slider).data("evaSlider");
		},

		setData : function(slider, data) {
			$(slider).data("evaSlider", data);	  
		},

		resize : function(slider, opt){
			var data = methods.getData(slider),
				width = opt.width,
				height = opt.height;

			slider.width(width).height(height);
			slider.find(".carousel-inner").width(width).height(height);
			data.itemHandler.width(width).height(height);
			data.itemHandler.find("img").width(width).height(height);
			if(!width || !height) {
				return false;
			}
		},

		highlightNumber : function(slider, index){
			var data = methods.getData(slider);
			var control = data.numberHandler.eq(index);
			data.numberHandler.removeClass("active");
			control.addClass("active");
		},

		slide : function(slider, direction){
			slider = $(slider);
			var data = methods.getData(slider),
				index = data.index,
				nextIndex = index,
				item = null,
				newItem = null,
				callback = data.beforeSlide;
			
			if(typeof direction === 'number') {
				nextIndex = direction;
			} else {
				direction === 'prev' ? 'prev' : 'next';
				if(direction === 'prev') {
					nextIndex = index - 1;
					if(nextIndex === -1) {
						nextIndex = data.max - 1;
					}
				} else {
					nextIndex = index + 1;
					if(nextIndex === data.max){
						nextIndex = 0;
					}
				}
			}

			item = data.itemHandler.eq(index);
			newItem = data.itemHandler.eq(nextIndex);

			if(typeof callback == "function") {
				callback(slider);
			}
			
			switch(data.options.effect) {
				case 'slide' : 
					methods.effects.slide(item, newItem, slider);
					break;
				case 'fade' : 
					methods.effects.fade(item, newItem, slider);
					break;
				default:
					methods.effects.noEffect(item, newItem, slider);
					break;
			}

			data.index = nextIndex;
			methods.setData(slider, data);
			methods.highlightNumber(slider, nextIndex);
			return this;
		},

		stop : function(slider){
			var data = methods.getData(slider);
			var timer = data.timer;
			if(timer) {
				clearTimeout(timer);
				data.timer = null;
				methods.setData(slider, data);
			} 
		},

		start : function(slider){
			var data = methods.getData(slider);
			var timer = data.timer;
			if(timer) {
				clearTimeout(timer);
				methods.slide(slider);
			}
			timer = setTimeout(function() {
				methods.start(slider);
			}, data.options.interval);
			data.timer = timer;
			methods.setData(slider, data);
		},

		effects : {
			noEffect : function(item, newItem, slider) {
				item.hide();
				newItem.show();
			},
			slide : function(item, newItem, slider) {
				var data = methods.getData(slider);
				var width = data.width;
				var speed = data.options.effectSpeed;
				var callback = data.options.afterSlide;
				newItem.addClass("active").show().css({
					"margin-left" : width
				}).animate({
					"margin-left" : [0, 'easeOutExpo']
					}, speed, function(){
						if(typeof callback == "function") callback(slider);
				});
				item.css({}).animate({
					"margin-left" : 0 - width 
				}, speed, function(){
					item.removeClass("active").hide();
				});	
			},
			fade : function(item, newItem, slider) {
				var data = methods.getData(slider);
				var width = data.width;
				var speed = data.options.effectSpeed;
				var callback = data.options.afterSlide;
				newItem.addClass("active").show().css({
					"opacity" : 0
				}).animate({
					"opacity" : 1
					}, speed, function(){
				});
				item.css({
				}).animate({
					"opacity" : 0
				}, speed, function(){
					item.removeClass("active").hide();
					if(typeof callback == "function") callback(slider);
				});
			}
		},
		events : {
			onClickNextHandler : function(event){
				var slider = event.data;
				methods.stop(slider);
				methods.slide(slider, 'next');
				return false;
			},
			onClickPrevHandler : function(event){
				var slider = event.data;
				methods.stop(slider);
				methods.slide(slider, 'prev');
				return false;
			},
			onClickNumberHandler : function(event){
				var slider = event.data;
				methods.stop(slider);
				var control = $(this);
				var index = control.attr("data-slide");
				if(index) {
					index = parseInt(index, 0) - 1;
				} else {
					index = control.index();
				}
				methods.slide(slider, index);
				return false;
			},
			onMouseenterItemHandler : function(event){
				event.stopPropagation();
				var slider = event.data;
				methods.stop(slider);
			},
			onMouseleaveItemHandler : function(event){
				event.stopPropagation();
				var slider = event.data;
				methods.start(slider);				
			}
		}
	};
	
	$.fn.evaSlider = function( method ) {
		if ( methods[method] ) {
			methods.init();
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply(this, arguments);
		} else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.evaSlider' );
		}

		return false;
	};

	$.evaSlider = methods;
})( jQuery );
