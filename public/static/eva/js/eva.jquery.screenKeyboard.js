/**
 * Eva Javascript Plugin
 *
 * @author     XuQian(AlloVince) <allo.vince@gmail.com>
 * @copyright  2011 AlloVince
 * @version    1.00
 */
(function( $ ){
 	var defaultOptions  = {
		connectTo : null,
		keyboardSelector : null,
		offsetX : 0,
		offsetY : 0,
		callback : function(){}
	},

	keyboard = null,
	inputHandler = null,
	connectHandler = null,
	
	methods = {
		init : function(opt) {
			//not binding elements;
			if(this === methods) {
				return false;
			}
			var options = methods.getOptions(opt);

			inputHandler = $(this);
			keyboard = $(options.keyboardSelector);
			connectHandler = $(options.connectTo);

			connectHandler.on("click", methods.events.onClickConnectHandler);
			keyboard.find("tbody a").on("click", methods.events.onClickKey);
			keyboard.find("thead .tojiru").on("click", methods.events.onClickCloseKey);
			keyboard.find("thead .hirakana").on("click", methods.events.onClickHirakanaKey);
			keyboard.find("thead .katagana").on("click", methods.events.onClickKataganaKey);
			keyboard.find("thead .switch_youon").on("click", methods.events.onClickYouonKey);
			keyboard.find("thead .switch_kana").on("click", methods.events.onClickKanaKey);
			keyboard.find("thead .back_space").on("click", methods.events.onClickBackspaceKey);

			keyboard.find("tr").find("td:gt(9)").hide();	

			$(window).on("resize", methods.events.onResizeWindow);

			return false;
		},

		insertAtCaret: function(myValue) {
			textarea = inputHandler[0];
			if (document.selection) {
				textarea.focus();
				sel = document.selection.createRange();
				sel.text = myValue;
				textarea.focus();
			} else if (textarea.selectionStart || textarea.selectionStart == '0') {
				var startPos = textarea.selectionStart;
				var endPos = textarea.selectionEnd;
				var scrollTop = textarea.scrollTop;
				textarea.value = textarea.value.substring(0, startPos)+myValue+textarea.value.substring(endPos,textarea.value.length);
				textarea.focus();
				textarea.selectionStart = startPos + myValue.length;
				textarea.selectionEnd = startPos + myValue.length;
				textarea.scrollTop = scrollTop;
			} else {
				textarea.value += myValue;
				textarea.focus();
			}
		},

		deleteAtCaret: function() {
			textarea = inputHandler[0];
			if (textarea.selectionStart || textarea.selectionStart == '0') {

				var length = textarea.value.length;
				var startPos = textarea.selectionStart;
				var endPos = textarea.selectionEnd;
				var scrollTop = textarea.scrollTop;

				if(length === startPos) {
					textarea.value = textarea.value.substring(0, length - 1);
				} else {
					textarea.value = textarea.value.substring(0, startPos) + textarea.value.substring(endPos,textarea.value.length);
				}
				textarea.focus();
				textarea.selectionStart = startPos;
				textarea.selectionEnd = startPos;
				textarea.scrollTop = scrollTop;
			} else {
				textarea.focus();
			}
		},

		resetKeyboardPosition : function(){
			var pos = connectHandler.offset();
			keyboard.css({
				'top' : pos.top + connectHandler.outerHeight(),
				'left' : pos.left - keyboard.outerWidth() + 35
			});

			/*
			if($(window).width() < keyboard.outerWidth()) {
				keyboard.find("tbody tr").each(function(){
					$(this).find("td:gt(9)").hide();		
				});
			}
			*/
		},

		switchYouon : function(){
			keyboard.find("tbody").each(function(){
				if($(this).hasClass("youon")) {
					$(this).find("tr").find("td:lt(10)").show();
					$(this).find("tr").find("td:gt(9)").hide();	
					$(this).removeClass("youon");
				} else {
					$(this).find("tr").find("td:lt(10)").hide();
					$(this).find("tr").find("td:gt(9)").show();		
					$(this).addClass("youon");
				}	
			});

			methods.resetKeyboardPosition();

		},

		hideKeyboard : function(){
			keyboard.hide();			   
		},

		getOptions : function(options){
			var defaultOpt = $.extend({}, defaultOptions);
			return $.extend(defaultOpt, options);
		},

		effects : {

		},
		events : {
			onClickConnectHandler : function(){
				methods.resetKeyboardPosition();
				keyboard.is(":visible") ? keyboard.hide() : keyboard.show();
				return false;
			},
			onClickYouonKey : function(){
				methods.switchYouon();				  
				return false;
			},
			onClickHirakanaKey : function(){
				keyboard.find("tbody.hirakana").show();					 
				keyboard.find("tbody.katagana").hide();					 
				return false;
			},
			onClickKataganaKey : function(){
				keyboard.find("tbody.katagana").show();					 
				keyboard.find("tbody.hirakana").hide();					 
				return false;
			},
			onResizeWindow : function(){
				methods.resetKeyboardPosition();
			},
			onClickCloseKey : function(){
				keyboard.hide();
				return false;
			},
			onClickKanaKey : function(){
				var hirakana = keyboard.find("tbody.hirakana");
				var katagana = keyboard.find("tbody.katagana");
				var key = $(this);
				var oldText = key.html();
				var newText = key.attr("data-text");

				if(hirakana.is(":visible")) {
					hirakana.hide();
					katagana.show();
				} else {
					katagana.hide();
					hirakana.show();
				}

				key.html(newText);
				key.attr("data-text", oldText);
				return false;					 
			},
			onClickBackspaceKey : function(){
				methods.deleteAtCaret();
				return false;
			},
			onClickKey : function(){
				var key = $(this);
				methods.insertAtCaret(key.html());
				return false;
			}

		}
	};
	
	$.fn.screenKeyboard = function( method ) {
		if ( methods[method] ) {
			methods.init();
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply(this, arguments);
		} else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.jpScreenKeyboard' );
		}

		return false;
	};

	$.screenKeyboard = methods;
})( jQuery );
