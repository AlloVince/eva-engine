(function($){
	$.fn.backToTop = function(options) {

 		var defaults = {
    			text: '<i class="icon-chevron-up"></i>',
    			min: 200,
    			inDelay:600,
    			outDelay:400,
      			containerID: 'to-top',
    			containerHoverID: 'to-top-hover',
    			scrollSpeed: 1200,
    			easingType: 'linear'
 		    },
            settings = $.extend(defaults, options),
            containerIDhash = '#' + settings.containerID,
            containerHoverIDHash = '#'+settings.containerHoverID;
		
		$('body').append('<a href="#" id="'+settings.containerID+'">'+settings.text+'</a>');
		$(containerIDhash).hide().on('click.UItoTop',function(){
			$('html, body').animate({scrollTop:0}, settings.scrollSpeed, settings.easingType);
			$('#'+settings.containerHoverID, this).stop().animate({'opacity': 0 }, settings.inDelay, settings.easingType);
			return false;
		})
		.prepend('<span id="'+settings.containerHoverID+'"></span>')
		.hover(function() {
				$(containerHoverIDHash, this).stop().animate({
					'opacity': 1
				}, 600, 'linear');
			}, function() { 
				$(containerHoverIDHash, this).stop().animate({
					'opacity': 0
				}, 700, 'linear');
			});
					
		$(window).scroll(function() {
			var sd = $(window).scrollTop();
			if(typeof document.body.style.maxHeight === "undefined") {
				$(containerIDhash).css({
					'position': 'absolute',
					'top': sd + $(window).height() - 50
				});
			}
			if ( sd > settings.min ) 
				$(containerIDhash).fadeIn(settings.inDelay);
			else 
				$(containerIDhash).fadeOut(settings.Outdelay);
		});
};
})(jQuery);

eva.gallery = function(){
	if(!$('.gallery')[0]){
		return false;
	}

	eva.loadcss(eva.s('/lib/js/jquery/colorbox/colorbox.css'));
	eva.loader(eva.s('/lib/js/jquery/colorbox/jquery.colorbox.js'), function(){
		$('.gallery').colorbox();
	});
};

eva.highlightmenu = function(){
	var url = eva.parseUri();
	var menuItems = $("li[data-highlight-url]");
	menuItems.each(function(){
		var item = $(this);
		var pattern = item.attr("data-highlight-url");
		pattern = pattern.replace(/\//g,"\\/");
			var reg = new RegExp(pattern);
		var mode = item.attr('data-highlight-mode');
		mode = mode == '' ? 'path' : mode;
		var execString = '';
		switch(mode){
			case 'full' : 
				execString = url.relative;
			break;
			default :
				execString = url.path;
		}
		var res = reg.exec(execString);
		if(res) {
			item.addClass("active");
			item.parent().removeClass('collapse');
			item.parent().parent().addClass("active");
			return true;
		}
	})
};

eva.miniCalendar = function(){
	if(!$('.calendar-wrap')[0]){
		return false;
	}
	var calendarUrl = eva.d('/event/calendar/');
	$('.calendar-wrap').load(calendarUrl);
	$('.calendar-wrap thead a').live("click", function(){
		$('.calendar-wrap').load($(this).attr('href'));
		return false;
	});
};


eva.templates = function(){
	$('script[data-url]').each(function(){
		var template = $(this);
		var url = template.attr('data-url');
		var dataQuery = template.attr('data-url-forlogin');
		if(dataQuery){
			eva.userReady(function(){
				var user = eva.getUser();
				$.ajax({
					url : url,
					data : {
						user_id : user.id
					},
					dataType : 'json',
					success : function(response){
						var t = tmpl(template.html(), response);
						template.after(t);
					}
				});
			});
		} else {
			$.ajax({
				url : url,
				dataType : 'json',
				success : function(response){
					var t = tmpl(template.html(), response);
					template.after(t);
				}
			});		
		}
	});
}

eva.select2 = function(){
	if(!$('.select2')[0]){
		return false;
	}

	eva.loadcss(eva.s('/lib/js/jquery/jquery.select2/select2.css'));
	eva.loader(eva.s('/lib/js/jquery/jquery.select2/select2.js'), function(){
		$('.select2').select2();
	});
}

eva.checkMessage = function(){
	var counter = $(".notice-count-message");
	if(!counter[0]) {
		return false;
	}

	var counterNumber = counter.find('.count-number');
	var updateNumber = function(response){
		if(response.count > 0) {
			counterNumber.html(response.count).show();
			var title = $('title').text();
			if(title.match(/^\(\d+\)/)){
				title = title.replace(/^\(\d+\)/, '(' + response.count + ') ');
				$('title').html(title);
			} else {
				$('title').prepend('(' + response.count + ') ');
			}				
		} else {
			counterNumber.hide();
			var title = $('title').text();
			if(title.match(/^\(\d+\)/)){
				title = title.replace(/^\(\d+\)/, '');
				$('title').html(title);
			}		
		}	
	};

	var checkNewUnread = function(){
		$.ajax({
			'url' : eva.d('/message/messages/unreadcount'),
			'type' : 'get',
			'dataType' : 'json',
			'success' : updateNumber
		});
	};
	checkNewUnread();
	setInterval(function(){ checkNewUnread() }, 50000);
};


eva.checkNotification = function(){
	var counter = $(".notice-count-notification");
	if(!counter[0]) {
		return false;
	}

	var counterNumber = counter.find('.count-number');
	var updateNumber = function(response){
		if(response.count > 0) {
			counterNumber.html(response.count).show();
		} else {
			counterNumber.hide();
		}	
	};

	var checkNewUnread = function(){
		$.ajax({
			'url' : eva.d('/data/noticecount'),
			'type' : 'get',
			'dataType' : 'json',
			'success' : updateNumber
		});
	};
	checkNewUnread();
};

eva.checkPermission = function(){
	var user = eva.getUser();
	var roles = user.Roles;
	$(".role-checker").each(function(){
		var roleString = $(this).attr("data-role");
		var roleArray = roleString.split('|');
		for(var i in roleArray){
			if($.inArray(roleArray[i], roles) > -1){
				$(this).removeClass('role-checker');
				break;
			}
		}
	});
};

eva.checkRequest = function(){
	var counter = $(".notice-count-request");
	if(!counter[0]) {
		return false;
	}

	var counterNumber = counter.find('.count-number');
	var user = eva.getUser();
	var updateNumber = function(response){
		if(response.items.length > 0) {
			var newItems = [];
			for(var i in response.items){
				if(user.id != response.items[i].request_user_id){
					newItems.push(response.items[i]);
				}
			}
			response.items = newItems;
		}
		if(response.items.length > 0) {
			counterNumber.html(response.items.length).show();
			counter.after(tmpl($("#notice-count-request").html(), response));
		} else {
			counterNumber.hide();
		}	
	};

	var checkNewUnread = function(){
		$.ajax({
			'url' : eva.d('/data/friend/'),
			'type' : 'get',
			'data' : {
				user_id : user.id,
				status : 'pending'
			},
			'dataType' : 'json',
			'success' : updateNumber
		});
	};
	checkNewUnread();
};



eva.checkFollow = function(){
	var checker = $(".follow-check");
	if(!checker[0]){
		return false;
	}

	var userid = checker.find('input[name=user_id]').val();
	var url = checker.attr('data-url');
	var user = eva.getUser();
	if(userid == user.id){
		$(".follow-form").addClass('hide');
		return;
	}

	$.ajax({
		url : url,
		dataType : 'json',
		type : 'get',
		data : {"user_id" : userid},
		success : function(response){
			if(!response.item || response.item.length < 1) {
				return false;
			}
			$(".follow-form").toggleClass('hide');
			$(".unfollow-form").toggleClass('hide');
		}
	});	
};

eva.checkFriend = function(){
	var checker = $(".friend-check");
	if(!checker[0]){
		return false;
	}

	var userid = checker.find('input[name=friend_id]').val();
	var url = checker.attr('data-url');
	var forms = $(".relationship-form");
	var user = eva.getUser();
	if(userid == user.id){
		return;
	}

	var switchForms = function(response){
		if(!response.item || response.item.length < 1) {
			forms.eq(0).show();
			return false;
		}
		var relationship = response.item[0].relationshipStatus;
		var requestUserId = response.item[0].request_user_id;
		forms.filter('.showon-' + relationship).show();
		if(relationship == 'pending' && user.id == requestUserId){
			$(".approve-form, .refuse-form").hide();
			$(".unfriend-form").show();
			$unfriendBtn = $(".unfriend-form button");
			$unfriendBtn.text($unfriendBtn.attr("data-text"));
		}
	};

	$.ajax({
		url : url,
		dataType : 'json',
		type : 'get',
		data : {"user_id" : userid},
		success : switchForms
	});	
};


eva.checkEvent = function(){
	var checkers = $(".event-checker");
	if(!checkers[0]){
		return false;
	}

	var checkIds = [];
	var url = eva.d('/data/eventjoin/');

	checkers.each(function(){
		var checker = $(this);
		checkIds.push(checker.attr('data-checker'));
	});
	$.unique(checkIds);

	var user = eva.getUser();
	var now = moment();

	var checkerDisplay = function(checker, relationship){
		if(checker.hasClass('event-join-form')){
			//eva.p(now.diff(moment(relationship.startDay + ' ' + relationship.startTime)) / 1000 / 60);
			if(!relationship.role &&
			   relationship.memberEnable > 0 &&
			   //allow join time is after event start time 1 hour (3600000 milliseconds)
			   now.diff(moment(relationship.startDay + ' ' + relationship.startTime, "YYYY-MM-DD HH:mm:ss")) < 3600000  &&
			   (relationship.memberLimit == '0' || relationship.memberLimit < relationship.memberCount)
			  ){
				checker.show();
			}
		} else if(checker.hasClass('event-quit-form')) {
			if(relationship.role && !relationship.isCreator){
				checker.show();
			}
		} else if(checker.hasClass('event-edit-btn')) {
			if(relationship.isCreator){
				checker.show();
			}
		} else if(checker.hasClass('event-remove-btn')) {
			if(relationship.isCreator){
				checker.show();
			}
		}
	}
	var switchForms = function(response){
		if(!response.items || response.items.length <= 0){
			return false;
		}

		var relationships = response.items;
		var i = 0;
		checkers.each(function(){
			var checker = $(this);
			for(i in relationships){
				if(relationships[i].id == checker.attr('data-checker')){
					checkerDisplay(checker, relationships[i]);
				}
			}
		});
	};

	$.ajax({
		url : url,
		dataType : 'json',
		type : 'get',
		data : {"id" : checkIds.join('-')},
		success : switchForms
	});	
};


eva.checkGroup = function(){
	var checkers = $(".group-checker");
	if(!checkers[0]){
		return false;
	}

	var checkIds = [];
	var url = eva.d('/data/groupjoin/');

	checkers.each(function(){
		var checker = $(this);
		checkIds.push(checker.attr('data-checker'));
	});
	$.unique(checkIds);

	var user = eva.getUser();
	var now = moment();

	var checkerDisplay = function(checker, relationship){
		if(checker.hasClass('group-join-form')){
			//eva.p(now.diff(moment(relationship.startDay + ' ' + relationship.startTime)) / 1000 / 60);
			if(!relationship.role &&
			   (relationship.memberLimit == '0' || relationship.memberLimit < relationship.memberCount)
			  ){
				checker.show();
			}
		} else if(checker.hasClass('group-quit-form')) {
			if(relationship.role && !relationship.isCreator){
				checker.show();
			}
		} else if(checker.hasClass('group-edit-btn')) {
			if(relationship.isCreator){
				checker.show();
			}
		} else if(checker.hasClass('group-remove-btn')) {
			if(relationship.isCreator){
				checker.show();
			}
		} else if(checker.hasClass('group-create-event-btn')) {
			if(relationship.isCreator){
				checker.show();
			}
		} else if(checker.hasClass('group-create-post-btn')) {
			if(relationship.isCreator || relationship.role){
				checker.show();
			}
		}
	}
	var switchForms = function(response){
		if(!response.items || response.items.length <= 0){
			return false;
		}

		var relationships = response.items;
		var i = 0;
		checkers.each(function(){
			var checker = $(this);
			for(i in relationships){
				if(relationships[i].id == checker.attr('data-checker')){
					checkerDisplay(checker, relationships[i]);
				}
			}
		});
	};

	$.ajax({
		url : url,
		dataType : 'json',
		type : 'get',
		data : {"id" : checkIds.join('-')},
		success : switchForms
	});	
};



eva.preview = function(){
	$(document).on('click', '.item-preview', function(){
		var btn = $(this);
		var replace = {
			url : btn.attr('data-url'),
			width : btn.attr('data-width'),
			height : btn.attr('data-height')
		};
		if(btn.hasClass('video')){
			btn.html(eva.template('<embed src="{url}" quality="high" width="{width}" height="{height}" align="middle" allowScriptAccess="always" allowFullScreen="true" mode="transparent" type="application/x-shockwave-flash"></embed>', replace));
		} else {
			btn.html(eva.template('<img class="img-polaroid" alt="" src="{url}" width="{width}" />', replace));
		}
		btn.off('click', '*');
		return false;
	});
}

eva.story = function(){

	if(!$("#feed-wall")[0]){
		return false;
	}

	var startStory = function(){
		var maxLoaded = 10;
		var loadTimes = 1;
		var container = $("#feed-wall");
		var loader = $(".load-more");
		$("body").append('<div id="load-area"></div>');
		var loadArea = $("#load-area");
		var loaded = [];

		var initStory = function(items){

			items.each(function(){
				var item = $(this);
				item.addClass("inited");
				return true;
			});

		};

		$(window).resize(function(){
			container.masonry({
				itemSelector : '.box',
				//columnWidth : $(window).width() > 800 ? 320 : 260,
				isAnimated: true
			}).masonry( 'reload' );
		});

		container.imagesLoaded( function(){
			container.masonry({
				itemSelector : '.box',
				//columnWidth : $(window).width() > 800 ? 320 : 260,
				isAnimated: true
			});

			var items = container.find(".box:not(.inited)");
			//eva.p(items.length);
			initStory(items);
		});

		loadArea.hide();
		function inArray(stringToSearch, arrayToSearch) {
			if(arrayToSearch.length < 1) {
				return false;
			}
			for (var s = 0; s < arrayToSearch.length; s++) {
				var thisEntry = arrayToSearch[s];
				if (thisEntry == stringToSearch) {
					return true;
				}
			}
			return false;
		}

		var loadNewStory = function(loader){

			if(loadTimes > maxLoaded) {
				return false;
			}

			var url = loader.attr("href");
			if(inArray(url, loaded)){
				return false;
			}

			//loader.addClass("disabled").html(" （；^ω^） 正在努力载入...");
			loaded.push(url);

			loadArea.load(url + ' #feed-wall', function() {
				var newUrl = loadArea.find(".load-more").attr("href");
				loader.attr("href", newUrl); 
				var content = loadArea.find(".box");
				loadArea.imagesLoaded( function(){
					container.append(content).masonry( 'appended', content, true);
					initStory(container.find(".box:not(.inited)"));
					loadArea.html('');
					loader.removeClass("disabled").html("More");
				});
				loadTimes++;
			});

			return false;
		};

		$(window).scroll(function () { 
			var pageH = $(document).height(); //页面总高度 
			var scrollT = $(window).scrollTop(); //滚动条top 
			var winH = $(window).height(); 
			var offset = pageH - scrollT - winH;
			if(offset < 300){
				loadNewStory(loader);
			}
		}); 
	}

	eva.loader(eva.s([
					 '/lib/js/jquery/jquery.masonry.js',
					 '/lib/js/jquery/jquery.mousewheel.js',
					 '/lib/js/jquery/jquery.jscrollpane.js'
	]), startStory);
};


eva.refreshOnline = function(){
	var refreshOnline = function(){
		$.ajax({
			'url' : eva.d('/user/refresh/online/'),
			'type' : 'get',
			'dataType' : 'json',
			'success' : function(response){
			}
		});
	}

	refreshOnline();
	setInterval(function(){ refreshOnline() }, 50000);

};


eva.topSearch = function(){
	if(!$(".top-search")[0]){
		return false;
	}

	var form = $(".top-search");
	form.find(".dropdown-menu a").on("click", function(){
		var link = $(this);
		form.find(".current").html(link.html());
		form.attr("action", link.attr("data-url"));
	});
};

eva.city = function(){

	if($("#current-location")[0]){
		if($.cookie('city') != null){
			$("#current-location").html($.cookie('city'));
		} else {
			$.ajax({
				url : eva.d('/data/geo/'),
				dataType : 'json',
				success : function(response){
					$("#current-location").html(response.item);
				}
			});
		}
	}

	$(".switch-city a").on('click', function(){
		$.cookie('city', $(this).text(), { expires: 365, path: '/'});
		window.location.href = eva.d('/events/');
	});

	if($.cookie('city') != null){
		$("#create-new-event").on('click', function(){
			$(this).attr("href", $(this).attr("href") + '?city=' +  $.cookie('city'));
			return true;
		});
		$(".my-city").html($.cookie('city'));
	}
}

eva.autoLogin = function(){
	if(!$(".auto-login")[0]){
		return false;
	}

	var realm = eva.cookie('realm');
	if(!realm || realm == ''){
		return false;
	}

	var url = eva.parseUri();
	var callback = '';
	if(typeof url.queryKey.callback !== 'undefined'){
		callback = '?callback=' + url.queryKey.callback;
	}
	window.location.href = eva.d('/login/auto/') + callback;
}

eva.ready(function(){

	eva.autoLogin();

	$("#lang").on("change", function(){
		window.location.href = $(this).val();
	});


	$().backToTop({ easingType: 'easeOutQuart' });

	eva.highlightmenu();
	eva.miniCalendar();
	eva.select2();

	eva.gallery();

	eva.userReady(function(){
		var user = eva.getUser();
		if(!user){
			return false;
		}
		eva.checkPermission();
		eva.checkFollow();
		eva.checkFriend();
		eva.checkMessage();	
		eva.checkRequest();
		eva.checkNotification();
		eva.refreshOnline();
		eva.checkEvent();
		eva.checkGroup();
	});

	eva.preview();

	eva.city();

	eva.story();

	eva.topSearch();

	var lang = eva.config.lang;
	var langMap = {
		'en' : 'en',
		'fr' : 'fr',
		'zh' : 'zh_CN',
		'zh_TW' : 'zh_TW',
		'ja' : 'ja'
	}
	var jsLang = langMap[lang];
	eva.loader(eva.s('/lib/js/jquery/jquery.validationEngine/jquery.validationEngine-' + jsLang + '.js'), function(){
		$("form").validationEngine();
	});

	eva.loader(eva.s('/lib/js/jstemplates/tmpl.js'), function(){
		eva.templates();
	});	

	if($(".epicsns")[0]){
		$.ajax({
			url : eva.d('/data/my/'),
			dataType : 'json',
			success : function(response){
				eva.setUser(response.item);
				eva.callUserFuncs();
			}
		});
	}

	return false;
});
