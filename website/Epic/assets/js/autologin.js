(function(){

var 

window = this,

undefined ,

methods = {
	getCookie : function(name) {
		var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
		if(arr != null) return unescape(arr[2]); return null;
	},
	autologin : function(){
		var loginUrl =  '/login/auto/';
		if (typeof eva_autologin != 'undefined') {
			loginUrl = eva_autologin;
		}
		if(methods.getCookie('realm')){
			var callback = encodeURIComponent(window.location.href);
			window.location.href = loginUrl + '?realm=' + realm + '&callback=' +  callback;
		}
	}
};
})();
