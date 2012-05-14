(function(){

	var methods = {

		config : {
			pathJqueryUi : ["/javascripts/jquery/jquery-ui.js", "/javascripts/jquery/jquery-ui-i18n.js", "/javascripts/jquery/jquery-ui-custom.js"],
			pathCodeMirror : ["/javascripts/codemirror/lib/codemirror.js"],
			pathSwfUploader : ["/javascripts/jquery/jquery.swfupload.js", "/javascripts/swfupload/swfupload.js", "/javascripts/swfupload/swfupload.queue.js"],
			pathTinymce : "/javascripts/jquery/jquery.tinymce.js"
		},

		_itemClass : {
			accordion : '.accordion',
			button : '.button',
			//buttonset : '.buttonset',
			progressbar : '.progressbar',
			dialog : '.dialog',
			panel : '.panel',
			tabs : '.tabs',
			grid : '.grid',
			datepicker : '.datepicker',
			codeeditor : '.codeeditor',
			swfuploader : '.swfuploader',
			htmleditor : '.htmleditor'
		},

		_getOption : function(item, setting) {
			var option = setting ? setting : {};
			var innerOption = $(item).attr("data-ui");
			setting = innerOption === undefined ? {} : $.parseJSON(innerOption);
			for(var i in setting) {
				option[i] = setting[i];
			}
			return option;
		},

		callback : function(item){
			 var option = methods._getOption(item);
			 if(!option || option.callback === undefined || !option.callback) {
				 return false;
			 }
			 try {
				 eval(option.callback + '(item)');
			 } catch(err){
				 eva.p(err);
			 }
			 return false;
		},

		initAccordion : function(setting){
			$(this._itemClass.accordion).each(function(){
				var option = methods._getOption(this, setting);
				$(this).accordion(option);
				methods.callback(this);
			});						
		},

		initButton : function(setting) {
			$(this._itemClass.button).each(function(){
				$(this).button(methods._getOption(this, setting));
			});					 
		},

		initButtonset : function(setting){
			$(this._itemClass.buttonset).each(function(){
				$(this).buttonset(methods._getOption(this, setting));
			});						
		},

		initDialog : function(setting){
			$(this._itemClass.dialog).each(function(){
				$(this).dialog(methods._getOption(this, setting));
			});	

			$(".dialog_handler").click(function(){
				var dialog = $(this).attr("data-rel");
				if(dialog === undefined) {
					return false;
				}
				$(dialog).dialog("open");
				return false;
			});
		},

		initTabs : function(setting){
			$(this._itemClass.tabs).each(function(){
				$(this).tabs(methods._getOption(this, setting));
			});						
		},

		initProgressbar : function(setting){
			$(this._itemClass.progressbar).each(function(){
				$(this).progressbar(methods._getOption(this, setting));
			});						
		},

		initDatepicker : function(setting){
			$(this._itemClass.datepicker).each(function(){
				$(this).datepicker(methods._getOption(this, setting));
			});						
		},

		//beta
		initPanel : function(setting){
			$(this._itemClass.panel).each(function(){
				$(this).panel(methods._getOption(this, setting));
			});						
		},

		//beta
		initGrid : function(setting) {
			var getWidth = function(td){
				var className = jQuery(td).prop("className");
				return parseInt(className.replace(/(.*)width_(\d+)(.*)/,"$2"), 10);		
			};

			$(this._itemClass.grid).each(function(){
				$(this).grid(methods._getOption(this), setting);

				$(" td, th", this).each(function(){
					var width = getWidth(this);
					if(width > 0) {
						jQuery(this).attr("width",width).css("width",width);
					}		
				});

			});	
		},

		//beta
		initHtmleditor : function(setting) {
			if(!$(this._itemClass.htmleditor)[0]) {
				return false;
			}

			var mceGlobelConfig = {
				mode : "exact",
				theme : "simple",
				//skin_variant : "silver",
				//use absolute url
				relative_urls : false,
				//plugins :"autosave,contextmenu,fullscreen,inlinepopups,insertdatetime,save,searchreplace,safari,preview,style,layer,table,spellchecker,media",
				
				plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
				theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
				theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
				theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
				theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",


				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true,
				remove_linebreaks : false,
				extended_valid_elements : "pre[cols|rows|disabled|name|readonly|class]",
				script_url : eva.s('/javascripts/tiny_mce/tiny_mce.js'),
				//script_url : '/javascripts/tiny_mce/tiny_mce_gzip.php',
				//content_css: eva.s("/skins/tinymce.css"),
				spellchecker_rpc_url : eva.s('/javascripts/tiny_mce/plugins/spellchecker/rpc.php')
			};

			eva.loader(methods.config.pathTinymce, function(){
				$(methods._itemClass.htmleditor).each(function(){
					var opt = methods._getOption(this);
					var mceconfig = mceGlobelConfig;
					if(opt) {
						for(var i in opt){
							mceconfig[i] = opt[i];
						}
					}
					mceconfig.editor_selector = this;
					$(this).width("100%").tinymce(mceconfig);
				});				
			});

			return false;
		},

		initCodeeditor : function(){
			if(!$(this._itemClass.codeeditor)[0]) {
				return false;
			}

			var modeMapping = {
				'htmlmixed' : ['xml', 'css', 'javascript'],
				'php' : ['xml', 'css', 'clike', 'javascript']
			};

			eva.loadcss('/javascripts/codemirror/lib/codemirror.css');

			eva.loader(methods.config.pathCodeMirror, function(){
				$(methods._itemClass.codeeditor).each(function(){
					var opt = methods._getOption(this);
					var mode = opt.mode;
					if(!mode) {
						return false;
					}

					//load theme
					var theme = opt.theme ? opt.theme : 'default';
					eva.loadcss('/javascripts/codemirror/theme/' + theme + '.css');

					var modePath = ["/javascripts/codemirror/mode/" + mode + "/" + mode + ".js"];
					if(modeMapping[mode]) {
						for(var i in modeMapping[mode]) {
							modePath.push("/javascripts/codemirror/mode/" + modeMapping[mode][i] + "/" + modeMapping[mode][i] + ".js");
						}
					}
					 
					var textarea = this;
					eva.loader(modePath, function(){
						CodeMirror.fromTextArea(textarea, opt);						
					});

					return true;

				});
			});						 

			return false;
		},

		//beta
		initSwfuploader : function(){
			if(!$(this._itemClass.swfuploader)[0]) {
				return false;
			}

			var defaultOpt = {
				upload_url: eva.d("/files"),
				file_size_limit : "10240",
				file_types : "*.*",
				file_types_description : "All Files",
				file_upload_limit : "10",
				flash_url : eva.s("/javascripts/swfupload/swfupload.swf"),
				flash9_url : eva.s("/javascripts/swfupload/swfupload_fp9.swf"),
				button_image_url : eva.s( '/javascripts/swfupload/button_en.png'),
				button_width : 61,
				button_height : 22,
				debug: false
			};

			function getCookie( name ) {
				var start = document.cookie.indexOf( name + "=" );
				var len = start + name.length + 1;
				if ( ( !start ) && ( name != document.cookie.substring( 0, name.length ) ) ) {
					return null;
				}
				if ( start == -1 ) return null;
				var end = document.cookie.indexOf( ';', len );
				if ( end == -1 ) end = document.cookie.length;
				return unescape( document.cookie.substring( len, end ) );
			}


			eva.loader(methods.config.pathSwfUploader, function(){
				$(methods._itemClass.swfuploader).each(function(){
					var opt = methods._getOption(this);
					var optCurrent = defaultOpt;
					for(var i in opt) {
						optCurrent[i] = opt[i];
					}

					optCurrent.button_placeholder = this;
					if(!optCurrent.file_post_name) {
						optCurrent.file_post_name = $(this).attr("name");
					}

					var sessionId = getCookie("PHPSESSID");

					if(sessionId) {
						optCurrent.post_params = {"PHPSESSID" : sessionId};
					}
					$(this).swfupload(optCurrent);
				});
			});
			return false;
		},

		_inited : false,

		_init : function(){
			for(var func in methods._itemClass) {
				func = 'init' + func.charAt(0).toUpperCase() + func.substr(1);
				if(eva.ui[func]){
					eva.ui[func]();
				}
			}	
		},

		init : function(){
			if(false === this._inited) {
				eva.loader(this.config.pathJqueryUi, this._init);
				this._inited = true;
			}
		}
	};

	eva.ui = methods;
})();
