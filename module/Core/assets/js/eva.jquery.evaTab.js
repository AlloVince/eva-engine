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
		tab : null, 
		tabActiveClass : 'active',
		tabHandler : null, 
		tabHandlerActiveClass : 'active',
		bindingHandler : false, //直接调用methods方法，这里需要指定为false
		effect : 'slide',
		beforeActive : null,
		afterActive : null,
		activeIndex : 0,
		effectSpeed : 300,
		callback : function(){}
	},
	
	currentHandler = null,
	
	methods = {
		init : function(options) {
			//not binding elements;
			if(this === methods) {
				return false;
			}

			var tabHandler = $(this);

			options = methods.getOptions(options);
			options.tabHandler = tabHandler;
			options.bindingHandler = true;
			tabHandler.data("evaTabOpt", options);

			tabHandler.live("click", methods.events.onClickTabhandler);

			return false;
		},

		getTab : function(options){
			if(options.tab) {
				return $(options.tab);
			}
			
			return $("#" + options.tipId);
				 
		},

		getTabHandler : function(){
			return currentHandler;				
		},

		getOptions : function(options){
			var defaultOpt = $.extend({}, defaultOptions);
			return $.extend(defaultOpt, options);
		},

		active : function(options) {
			if(options.bindingHandler === false) {
				options = methods.getOptions(options);
			}
			
			if(typeof options.beforeActive == 'function'){
				options.beforeActive(currentHandler, options);	
			}
			var effect = options.effect;
			if(methods.effects[effect]) {
				methods.effects[effect](options);
			} else {
				methods.effects.noEffect(options);
			}
		},

		effects : {
			noEffect : function(options){
				var tab = options.tab;
				var currentTab = tab.eq(options.activeIndex);

				tab.filter(":visible").hide(options.effectSpeed);

				if(currentTab.attr("data-tab") && !currentTab.data("evaTabAjaxLoad")) {
					$.ajax({
						url: currentTab.attr("data-tab"),
						success: function(htm){
							currentTab.html(htm);
							currentTab.data("evaTabAjaxLoad", true);

							currentTab.show(options.effectSpeed, function(){
								if(typeof options.afterActive == 'function'){
									options.afterActive(currentTab);
								}
							});	
						}
					});
				} else {
					currentTab.show(options.effectSpeed, function(){
						if(typeof options.afterActive == 'function'){
							options.afterActive(currentTab);
						}
					});					
				}

				options.tabHandler.removeClass("active");
				currentHandler.addClass('active');
			},
			slide : function(options){
				var tab = options.tab;
				var currentTab = tab.eq(options.activeIndex);

				tab.filter(":visible").slideUp(options.effectSpeed);

				if(currentTab.attr("data-tab") && !currentTab.data("evaTabAjaxLoad")) {
					$.ajax({
						url: currentTab.attr("data-tab"),
						success: function(htm){
							currentTab.html(htm);
							currentTab.data("evaTabAjaxLoad", true);

							currentTab.slideDown(options.effectSpeed, function(){
								if(typeof options.afterActive == 'function'){
									options.afterActive(currentTab);
								}
							});	
						}
					});
				} else {
					currentTab.slideDown(options.effectSpeed, function(){
						if(typeof options.afterActive == 'function'){
							options.afterActive(currentTab);
						}
					});					
				}

				options.tabHandler.removeClass("active");
				currentHandler.addClass('active');
			},
			fade : function(options){
				var tab = options.tab;
				var currentTab = tab.eq(options.activeIndex);

				tab.filter(":visible").fadeOut(options.effectSpeed);

				if(currentTab.attr("data-tab") && !currentTab.data("evaTabAjaxLoad")) {
					$.ajax({
						url: currentTab.attr("data-tab"),
						success: function(htm){
							currentTab.html(htm);
							currentTab.data("evaTabAjaxLoad", true);

							currentTab.fadeIn(options.effectSpeed, function(){
								if(typeof options.afterActive == 'function'){
									options.afterActive(currentTab);
								}
							});	
						}
					});
				} else {
					currentTab.fadeIn(options.effectSpeed, function(){
						if(typeof options.afterActive == 'function'){
							options.afterActive(currentTab);
						}
					});					
				}

				options.tabHandler.removeClass("active");
				currentHandler.addClass('active');
			}
		},
		events : {
			onClickTabhandler : function(){
				currentHandler = $(this);

				var options = currentHandler.data("evaTabOpt");
				options.activeIndex = currentHandler.index();

				if(typeof options.beforeActive == "function") {
					options.beforeActive(currentHandler, options);
				}
				methods.active(options);
				return false;
			}

		}
	};
	
	$.fn.evaTab = function( method ) {
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

	$.evaTab = methods;
})( jQuery );
