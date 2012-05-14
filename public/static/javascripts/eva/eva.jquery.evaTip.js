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
		tip : null, //可以指定一个dom作为tip
		tipHandler : null, //可以指定一个dom作为tipHandler
		tipId : "evatip", //单tipid
		arrowVisible : true,
		arrowOffset : 5, //箭头位置偏移
		multiTips : false, //支持复数个Tip同时出现
		tipContentAttr : 'data-tip', //tip content的attr名
		content : '', //tip content，会覆盖tipContentAttr
		autoDirection : true, //在边界位置自动调整方向
		x : 0, //tip出现位置x （如果没有绑定任何元素）
		y : 0,
		offsetX : 0, //位置偏移x
		offsetY : 0,  //位置偏移y
		effect : 'fade', //动画效果
		eventType : 'hover',
		hideDelay : 500, //隐藏延迟
		direction : 'right', //tip默认位置
		beforeshow : null,
		aftershow : null,
		beforehide : null,
		afterhide : null
	},

	tipStatus = {
		inited : false,
		visible : false
	},

	getTipHtml = function(tipId){
		return '<div id="' + tipId + '" class="tooltip hide"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>';
	},

	lastTiphandler = null,

	methods = {
		init : function(options) {
			//not binding elements;
			if(this === methods) {
				return false;
			}

			options = methods.getOptions(options);
			var tipHandler = this;
			tipHandler.data('evaTip', options);

			var tip = methods.getTip(options);
			tip.css("opacity", "100");

			if(options.eventType == 'click') {
				tipHandler.live("click", methods.events.onClickTiphandler);	
			} else {
				tipHandler.live({
					'mouseenter' : methods.events.onMouseenterTiphandler, 
					'mouseleave' : methods.events.onMouseleaveTiphandler
				});	
				tip.live({
					'mouseenter' : methods.events.onMouseenterTip, 
					'mouseleave' : methods.events.onMouseleaveTip
				});	
			}

			return false;
		},

		getTip : function(options){
			if(options.tip) {
				return $(options.tip);
			}

			if(!options.tipId) {
				$.error( 'No tip id found' );
				return false;
			}

			//init single tip
			if(tipStatus.inited === false) {
				$('body').append(getTipHtml(options.tipId));
				tipStatus.inited = true;
			}
			
			return $("#" + options.tipId);
				 
		},

		getOptions : function(options){
			var defaultOpt = $.extend({}, defaultOptions);
			return $.extend(defaultOpt, options);
		},

		getLastHandler : function(){
			return lastTiphandler;				 
		},

		getHandler : function(tipHandler){
			//not bind any handler
			if(tipHandler === methods) {
				return {
					obj : false,
					left : 0,
					top : 0,
					width : 0,
					height : 0,
					content : ''
				};
			}
			
			tipHandler = $(tipHandler);
			var pos = tipHandler.offset();
			var left = 0;
			var top = 0;
			if(pos) {
				left = pos.left;
				top = pos.top;
			}
			
			return {
				obj : tipHandler,
				width : tipHandler.outerWidth(),
				height : tipHandler.outerHeight(),
				left : left,
				top : top,
				content : tipHandler.attr("data-tip") || tipHandler.attr("title")
			};
		},

		show : function(options) {
			var tipHandler = methods.getHandler(this);
			if(false === tipHandler.obj) {
				options = methods.getOptions(options);
			} else {
				options = tipHandler.obj.data("evaTip") || methods.getOptions(options);
			}

			var tipContent = options.content || tipHandler.content;
			var css = {
				'top' : tipHandler.top,
				'left' : tipHandler.left
			};

			//not binding to any element
			if(false === tipHandler.obj) {
				css = {
					top : options.y,
					left : options.x
				};
			}

			var tip = methods.getTip(options);
			tip.removeClass('top bottom left right');

			//default tip
			if(options.tip === null) {
				if(!tipContent) {
					return false;
				}
				tip.find(" .tooltip-inner").html(tipContent);
			}

			var tipWidth = tip.outerWidth();
			var tipHeight = tip.outerHeight();
			var direction = options.direction;
			var directionClass = direction;
			switch(direction) {
				case 'above' : 
					directionClass = "top";
					css.left += (tipHandler.width - tipWidth) / 2;
					css.top -= tipHeight + options.arrowOffset;
					break;
				case 'right' : 
					css.left += (tipHandler.width  + options.arrowOffset);
					css.top += (tipHandler.height - tipHeight) / 2;
					break;
				case 'left' : 
					css.left -= (tipWidth + options.arrowOffset);
					css.top += (tipHandler.height - tipHeight) / 2;
					break;
				case 'below' : 
					directionClass = "bottom";
					css.left += (tipHandler.width - tipWidth) / 2;
					css.top += tipHandler.height + options.arrowOffset;
					break;

				default: 
					break;
			}

			//tooltip offset fix
			css.left += options.offsetX;
			css.top += options.offsetY;

			//Add arrow
			if(true === options.arrowVisible) {
				tip.addClass(directionClass);
			}

			tip.css(css);

			if(typeof options.beforeshow == "function") {
				options.beforeshow();
			}
			var effect = options.effect;
			if(methods.effects[effect]) {
				methods.effects[effect].show(tip, options.aftershow, tipHandler);
			} else {
				methods.effects.noEffect.show(tip, options.aftershow, tipHandler);
			}

			return this;
		},

		hide : function(options){
			var tipHandler = methods.getHandler(this);
			if(false === tipHandler.obj) {
				options = methods.getOptions(options);
			} else {
				options = tipHandler.obj.data("evaTip") || methods.getOptions(options);
			}
			var tip = methods.getTip(options);
			var effect = options.effect;

			if(typeof options.beforehide == "function") {
				options.beforehide();
			}
			if(methods.effects[effect]) {
				methods.effects[effect].hide(tip, options.afterhide, tipHandler);
			} else {
				methods.effects.noEffect.hide(tip, options.afterhide, tipHandler);
			}

			return this;
		},

		effects : {
			noEffect : {
				show : function(tip, callback, tipHandler){
					tip.show();
					if(typeof callback == "function") callback(tip, tipHandler);

				},
				hide : function(tip, callback, tipHandler){
					tip.hide();
					if(typeof callback == "function") callback(tip, tipHandler);
				}						   
			},
			fade : {
				show : function(tip, callback, tipHandler){
					callback = typeof callback == "function" ? callback : function(){};
					tip.fadeIn('fast', callback(tip, tipHandler));
				},
				hide : function(tip, callback, tipHandler){
					callback = typeof callback == "function" ? callback : function(){};
					tip.fadeOut('fast', callback(tip, tipHandler));
				}
			}
		},
		events : {
			onClickTiphandler : function(){
				if($(this).data("evaTipVisible") === true) {
					methods.hide.apply(this);
					$(this).data("evaTipVisible", false);
				} else {
					methods.show.apply(this);
					$(this).data("evaTipVisible", true);
				}
				return false;
			},
			onMouseleaveTiphandler : function(){
				var _self = this;
				lastTiphandler = this;
				var showTimer = $(this).data("evaTipShowTimer");
				if(showTimer) {
					clearTimeout(showTimer);
				}
				$(this).data("evaTipHideTimer", setTimeout(function() {
					methods.hide.apply(_self);
				}, 300));
			},
			onMouseenterTiphandler : function(){
				var _self = this;
				lastTiphandler = this;
				var hideTimer = $(this).data("evaTipHideTimer");
				if(hideTimer) {
					clearTimeout(hideTimer);
				}
				$(this).data("evaTipShowTimer", setTimeout(function() {
					methods.show.apply(_self);
				}, 300));
			},
			onMouseenterTip : function(){
				if(!lastTiphandler) {
					return false;
				}
				var tip = $(this);
				var hideTimer = $(lastTiphandler).data("evaTipHideTimer");
				if(hideTimer) {
					clearTimeout(hideTimer);
				}
				return false;
			},
			onMouseleaveTip : function(){
				if(!lastTiphandler) {
					return false;
				}	

				var showTimer = $(lastTiphandler).data("evaTipShowTimer");
				//fix bug, last handler maybe change before timer start
				var lastTiphandlerTemp = lastTiphandler;
				if(showTimer) {
					clearTimeout(showTimer);
				}
				$(lastTiphandler).data("evaTipHideTimer", setTimeout(function() {
					methods.hide.apply(lastTiphandlerTemp);
				}, 300));

				return false;
			}
		}
	};
	
	$.fn.evaTip = function( method ) {
		if ( methods[method] ) {
			methods.init();
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply(this, arguments);
		} else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
		}

		return false;
	};

	$.evaTip = methods;
})( jQuery );

/*
jQuery(document).ready(function () {
	
	//$(".showtip").evaTip();
	//$(".showtip").evaTip('show', {direction : 'above'});
	//$(".showtip").evaTip('show', {direction : 'above', offsetY : -50});
	//$(".showtip").evaTip({tip : $("#addfavor") , direction : 'right'});



	$.evaTip.init().show({
		content : 'abc',
		direction : 'below',
		x : 100,
		y : 100,
		callback : function(){
			alert(1);
			//$.evaTip.hide();
		}
	});
});
*/
