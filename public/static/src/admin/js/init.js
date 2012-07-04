eva.construct = function(){
	$(".checkmulti").on("change", function(){
		var targetClass = $(this).attr("data-checkmulti");
		var targets = $("." + targetClass);
		//console.log(targets);
		var checked = $(this).attr("checked");
		if(checked){
			targets.attr("checked", "checked");
		} else {
			targets.attr("checked", false);
		}
	});
	return false;
};

eva.destruct = function(){
};

eva.init();
