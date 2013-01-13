/**
 * Eva Javascript Core File
 * Mapping Zend Framework RESTFul resource to Javascript functions
 * UI depends on jQuery, jQuery UI, Tinymce, LabJs
 *
 *
 * @author     XuQian(AlloVince) <xuqian@easthv.com>
 * @copyright  2011 AlloVince
 * @version    1.10
 */
(function(){

var 

window = this,

undefined ,

config = {
	debug : true,
	params : {},
	login : false,
	username : false,
	lang : "en",
	dir : "",
	f : "",
	s : '',
	assets : '',
	ie : false
},

dirHandler = function(dir, configDir){
	if(dir === undefined) {
		return configDir;
	}

	if(dir instanceof Array){
		for(var i in dir){
			dir[i] = configDir + dir[i];
		}
		return dir;
	}
	return configDir + dir;
},

readyFuncs = [],

userReadyFuncs = [],

evaUser,

methods = window.eva = {
	module : {},

	config : {},

	debug : {
		showGrid : function(){
			jQuery(window).load(function(){
				var width = 950;
				var margin_left = 0 - width / 2;
				jQuery("body").append('<div id="grid" style="position:absolute;margin-left:' + margin_left + 'px;top:0;left:50%;width:' + width + 'px;height:' + jQuery("#page").height() + 'px;background:#CCC url(' + s('/skins/grid950.png') + ') repeat top left;z-index:9999;"></div>');
				jQuery("#grid").animate({opacity:0.5},0).click(function(){
					jQuery(this).hide();		
				});				
			});
		}		
	},

	

	d : function(dir) {
		return dirHandler(dir, config.dir);
	},

	s : function(dir){
		return dirHandler(dir, config.s);
	},

	f : function(dir){
		return dirHandler(dir, config.f);
	},

	sv : function(dir){
		return methods.s(dir) + '?v=' + eva.config.version;
	},

	assets : function(dir){
		return dirHandler(dir, config.assets);
	},

	p : function (m){
		if(typeof console === 'undefined')
			return alert(m);
		return console.log(m);
	},

	getConfig : function(options){
		var defaultOpt = $.extend({}, config);
		return $.extend(defaultOpt, options);
	},

	getRouterName : function(){
		return config.params.action + config.params.resource;
	},

	getRouter : function(){
		var actionName = config.params.action + config.params.resource;
		var module = config.params.module;
		
		if(module === undefined || module == 'default') {
			return eva[actionName] === undefined ? function(){} : eva[actionName];
		} else {
			var moduleActionName = module.charAt(0).toUpperCase() + module.substr(1) + '_' + actionName;
			return methods.module[moduleActionName] === undefined ? 
				( eva[actionName] === undefined ? function(){} : eva[actionName] ): eva.module[moduleActionName];
		}
	},

	setUser : function(user){
		evaUser = user;
	},

	getUser : function(){
		return evaUser;
	},

	loader : function(path, callback) {
		if($LAB === undefined || path === undefined || !path) {
			return false;
		}
		return $LAB.script(path).wait(callback);
	},

	trim : function(str){
		return jQuery.trim(str);
		/*
		if(!String.prototype.trim){
			return str.replace(/^\s+|\s+$/g, '');
		}

		return String.trim(str);
		*/
	},

	cookie : function(name, value, options) {
		if (typeof value != 'undefined') { // name and value given, set cookie
			options = options || {};
			if (value === null) {
				value = '';
				options.expires = -1;
			}
			var expires = '';
			if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
				var date;
				if (typeof options.expires == 'number') {
					date = new Date();
					date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
				} else {
					date = options.expires;
				}
				expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
			}
			var path = options.path ? '; path=' + (options.path) : '';
			var domain = options.domain ? '; domain=' + (options.domain) : '';
			var secure = options.secure ? '; secure' : '';
			document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
		} else { // only name given, get cookie
			var cookieValue = null;
			if (document.cookie && document.cookie !== '') {
				var cookies = document.cookie.split(';');
				for (var i = 0; i < cookies.length; i++) {
					var cookie = methods.trim(cookies[i]);
					// Does this cookie string begin with the name we want?
					if (cookie.substring(0, name.length + 1) == (name + '=')) {
						cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
						break;
					}
				}
			}
			return cookieValue;
		}

		return null;
	},

	/*
	preload : function() { 
		var cache = [];
		var argsLen = arguments.length;
		var i = argsLen;
		for (i; i > 0; i--) {
			var cacheImage = document.createElement('img');
			cacheImage.src = arguments[i];
			cache.push(cacheImage);
		}
	},
	*/

	loadcss : function(cssfile) {
		var head = document.getElementsByTagName("head")[0];
		if(cssfile instanceof Array){
			var len = cssfile.length;
			for(var i = 0; i < len; i++) {
				var css = document.createElement('link');
				css.type = 'text/css';
				css.rel = "stylesheet";
				css.href = cssfile[i];
				head.appendChild(css);
			}		
			return false;
		}
		var css = document.createElement('link');
		css.type = 'text/css';
		css.rel = "stylesheet";
		css.href = cssfile;
		head.appendChild(css);
		return false;
	},

	parseUri : function(url){
		function parseUri (str) {
			var	o   = parseUri.options,
				m   = o.parser[o.strictMode ? "strict" : "loose"].exec(str),
				uri = {},
				i   = 14;

			while (i) {
				i--;
				uri[o.key[i]] = m[i] || "";
			}

			uri[o.q.name] = {};
			uri[o.key[12]].replace(o.q.parser, function ($0, $1, $2) {
				if ($1) uri[o.q.name][$1] = $2;
			});

			return uri;
		}

		parseUri.options = {
			strictMode: false,
			key: ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],
			q:   {
				name:   "queryKey",
				parser: /(?:^|&)([^&=]*)=?([^&]*)/g
			},
			parser: {
				strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
				loose:  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/
			}
		};

		return url ? parseUri(url) : parseUri(window.location.href);
	},

	template : function(template, replace){
		template = template.replace(/{[^{}]+}/g, function(key){
			return replace[key.replace(/[{}]+/g, "")] || "";
		});
		return template;
	},

	thumb : function(url, params){
		url = url.split('.');
		var ext = url.pop();
		url[url.length - 1] += ',' + params;
		url.push(ext);
		url = url.join('.');
		return url;
	},

	callback : {},

	ready : function(func){
		if (typeof func !== 'function') {
			return false;
		} 
		readyFuncs.push(func);
	},

	userReady : function(func){
		if (typeof func !== 'function') {
			return false;
		} 
		userReadyFuncs.push(func);
	},

	callUserFuncs : function(){
		var i = 0;
		for(i in userReadyFuncs){
			userReadyFuncs[i]();
		}
	},

	init : function(setting){
		config = methods.getConfig(setting);
		methods.config = config;
		var router = methods.getRouter();
		var run = function(){
			if(eva.construct !== undefined) {
				eva.construct();
			}

			if(router) {
				router();
			}

			if(eva.runtime !== undefined) {
				eva.runtime();
			}

			var i = 0;
			for(i in readyFuncs){
				readyFuncs[i]();
			}

			if(eva.destruct !== undefined) {
				eva.destruct();
			}		
		};

		if (typeof jQuery === 'undefined') {
			window.onload = run;
		} else {
			jQuery(document).ready(function () {
				return run();
			});
		} 
	}
};
})();
