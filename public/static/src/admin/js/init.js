eva.multiform = function(){
	$("form[data-multiform]").each(function(){
		var form = $(this);
		var formName = form.attr("data-multiform");
		var checker = form.find(".multiform-checker");
		var containers = form.find('.multiform-container');
		var mainContainer = form.find('.multiform-container-main');
		var checkItems = $(".multiform-item-checkbox[data-multiform='" + formName + "']");
		var inputItems = $(".multiform-item-input[data-multiform='" + formName + "']");

		var updateContainer = function(){
			var onCheckItems = checkItems.filter("[checked='checked']");
			var ids = [];
			onCheckItems.each(function(){
				ids.push($(this).val());
			});
			containers.each(function(){
				var container = $(this);
				var containerName = container.attr("name");

				var connectItems = inputItems.filter("[name='" + containerName + "']");
				var values = [];

				for(var i in ids){
					var item = connectItems.filter("[data-multiform-itemid='" + ids[i] + "']");
					values.push(item.val());
				}
				container.val(values.join(","));
			});

			mainContainer.val(ids.join(","));
		}

		checker.on("change", function(){
			var checked = checker.attr("checked");
			if(checked){
				checkItems.attr("checked", "checked");
			} else {
				checkItems.attr("checked", false);
			}

			updateContainer();
		});

		checkItems.on("change", updateContainer);
		inputItems.on("change", updateContainer);


		var action = form.attr("action");
		var submiters = form.find(".multiform-submiter");

		submiters.on("click", function(){
			updateContainer();
			var submiter = $(this);
			var newAction = submiter.attr("data-multiform-action");
			form.attr("action", newAction);
			form.submit();
			return false;
		});

	});
};


eva.highlightmenu = function(){
	var url = eva.parseUri();
	var menuItems = $("li[data-highlight-url]");
	var path = url.path;

	menuItems.each(function(){
		var item = $(this);
		var pattern = item.attr("data-highlight-url");
		pattern = pattern.replace(/\//g,"\\/");
		var reg = new RegExp(pattern);
		var res = reg.exec(path);
		//eva.p(reg);
		//eva.p(res);

		if(res) {
			item.addClass("active");
			item.parent().removeClass('collapse');
			item.parent().parent().addClass("active");
			return false;
		}
	})
};

eva.construct = function(){

	$(".main-left-col").height($(document).height()).css("background", "#333232");

	eva.multiform();
	eva.highlightmenu();

	//$('.dropdown-toggle').dropdown();

	eva.ui.init();
	return false;
};

eva.destruct = function(){
};

eva.init();
