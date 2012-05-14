/**
 * Validate 
 * 
 * @author     XuQian(AlloVince) <allo.vince@gmail.com>
 * @copyright  2011 AlloVince
 * @version    1.00
 */
(function( $ ){
 	var defaultOptions  = {
		lang : "zh",
		tipOptions : {
			'direction' : "above"
		},
		errorType : "tip",
		validOptions : {
			errorClass : 'error',
			onfocusin: function(element) {
				if(!$(element).hasClass("error"))
					return;

				this.element(element);
			},
			errorPlacement: function(error, element) {
				lastValidHandler = $(element);
				if(lastValidHandler.hasClass("valid")) {
					return false;
				}
				lastValidHandler.evaTip("show", {
					'direction' : "above",
					content : error	
				});
				return false;
			},
			success: function(label) {
				lastValidHandler.evaTip("hide", {
					effect : null
				});
			}
		}
	};
	var text = {
		"zh" : {
				required: "这是一个必填项",
				remote: "输入已被占用",
				email: "请输入正确格式的电子邮件",
				url: "请输入正确格式的网址",
				date: "请输入合法的日期",
				dateISO: "请输入ISO格式的日期 ",
				number: "请输入数字",
				digits: "只能输入整数",
				creditcard: "请输入合法的信用卡号",
				equalTo: "两次输入不一致",
				accept: "请输入拥有合法后缀名的字符串",
				maxlength: jQuery.validator.format("请输入一个长度最多是 {0} 的字符串"),
				minlength: jQuery.validator.format("请输入一个长度最少是 {0} 的字符串"),
				rangelength: jQuery.validator.format("请输入一个长度介于 {0} 和 {1} 之间的字符串"),
				range: jQuery.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),
				max: jQuery.validator.format("请输入一个最大为 {0} 的值"),
				min: jQuery.validator.format("请输入一个最小为 {0} 的值")
		}
	};

	var lastValidHandler = null;

	var _rules = [];

	var validBy = {
		hint : {
			errorPlacement: function(error, element) {
				lastValidHandler = $(element);
				var errorText = error.text();
				if(lastValidHandler.hasClass("valid") || !errorText) {
					return false;
				}

				var holder = lastValidHandler.parent();
				if(holder.hasClass('holder') === false || lastValidHandler.is(":radio") || lastValidHandler.is(":checkbox") || lastValidHandler.is(":button")) {
					lastValidHandler.evaTip("show", {
						'direction' : "above",
						content : errorText	
					});
					return false;				
				}


				var hint = holder.find(".formhint");
				if(!hint[0]) {
					holder.append('<p class="formhint">' + errorText + '</p>');
					hint = holder.find(".formhint");
					hint.data("orgHint", '');
				} else {
					//save original form hint
					if(!holder.hasClass("error")) {
						hint.data("orgHint", hint.html());
					}
					hint.html(errorText);
				}
				holder.addClass("error");

				return false;
			},
			success: function(label) {
				var holder = lastValidHandler.parent();
				if(holder.hasClass('holder')) {
					var hint = holder.find(".formhint");
					holder.removeClass("error");
					hint.html(hint.data("orgHint"));
				}
				lastValidHandler.evaTip("hide", {
					effect : null
				});
			}				   
		}
	};

	var fixedRemote = function(value, element, param) {
		if ( this.optional(element) )
			return "dependency-mismatch";

		var previous = this.previousValue(element);
		if (!this.settings.messages[element.name] )
			this.settings.messages[element.name] = {};
		previous.originalMessage = this.settings.messages[element.name].remote;
		this.settings.messages[element.name].remote = previous.message;

		param = typeof param == "string" && {url:param} || param;

		if ( this.pending[element.name] ) {
			return "pending";
		}
		if ( previous.old === value ) {
			return previous.valid;
		}

		previous.old = value;
		var validator = this;
		this.startRequest(element);
		var data = {};
		data[element.name] = value;
		$.ajax($.extend(true, {
			url: param,
			mode: "abort",
			port: "validate" + element.name,
			dataType: "json",
			data: data,
			success: function(response) {
				validator.settings.messages[element.name].remote = previous.originalMessage;
				//EVA FIX
				var valid = response.data === '';
				if ( valid ) {
					var submitted = validator.formSubmitted;
					validator.prepareElement(element);
					validator.formSubmitted = submitted;
					validator.successList.push(element);
					validator.showErrors();
				} else {
					var errors = {};
					//EVA FIX
					var message = validator.defaultMessage( element, "remote" );
					errors[element.name] = previous.message = $.isFunction(message) ? message(value) : message;
					validator.showErrors(errors);
				}
				previous.valid = valid;
				validator.stopRequest(element, valid);
			}
		}, param));
		return "pending";
	};

	var methods = {

		getOptions : function(options){
			var defaultOpt = $.extend({}, defaultOptions);
			return $.extend(defaultOpt, options);
		},

		getValidOptions : function(options){
			var validOptions = $.extend({}, defaultOptions.validOptions);
			return validOptions;
		},

		addValidOptions : function(options){

		},

		addRules : function(ruleFunc){
			if(this === methods) {
				return false;
			}

			var form = $(this);
			var validRules = form.data("evaValidateRules");
			if(!validRules) {
				form.data("evaValidateRules", [ruleFunc]);
			} else {
				validRules.push(ruleFunc);
				form.data("evaValidateRules", validRules);
			}
			
			return false;
		},

		addInlineRules : function(form){
			var inputs = form.find("[data-v]");
			var rule = {};
			var input = {};

			inputs.each(function(){
				input = $(this);
				rule = $.parseJSON(input.attr("data-v"));
				input.rules("add", rule);
			});
		},

		initForm : function(form){
			//form.attr("novalidate", "novalidate");
			var validOptions = methods.getValidOptions();

			for(var i in validBy.hint) {
				validOptions[i] = validBy.hint[i];
			}

			//IE required input fix
			if($.browser.msie === true) {
				form.find("[required]").each(function(){
					$(this).addClass("required");		
				});
			}

			form.validate(validOptions);
			var validRules = form.data("evaValidateRules");
			if(validRules) {
				for(i in validRules) {
					validRules[i]();
				}
			}
			methods.addInlineRules(form);				   
		},

		init : function(options) {
			options = methods.getOptions(options);
			$.validator.messages = text[options.lang];
			$.validator.methods.remote = fixedRemote;

			var supportForms = {};
			if(this === methods) {
				supportForms = $("form:not(.novalidate)");
			} else {
				supportForms = $(this);
			}

			supportForms.each(function(){
				var form = $(this);
				var inited  = form.data("evaValidateInited");
				methods.initForm(form);
			});
		},

		events : {
			onSubmitForm : function(){}
		}
	};
	
	$.fn.evaValidate = function( method ) {
		if ( methods[method] ) {
			methods.init();
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply(this, arguments);
		} else {
			$.error( 'Method ' +  method + ' does not exist on evaValidate' );
		}

		return false;
	};

	$.evaValidate = methods;
})( jQuery );
