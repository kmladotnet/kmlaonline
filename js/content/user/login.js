function initializeLoginBackgroundImage(url,w,h){
	$("#no-login-bg").attr("src",url).css("display","block");
	var aspRatio=w/h;
	$(document).ready(function () {
		$(window).on('resize', function(){
			var win=$(this);
			if(win.width()/win.height()<aspRatio){
				$("#no-login-bg").css("height","100%").css("width","auto");
			}else{
				$("#no-login-bg").css("width","100%").css("height","auto");
			}
		});
	});
	var win=$(window);
	if(win.width()/win.height()<aspRatio){
		$("#no-login-bg").css("height","100%").css("width","auto");
	}else{
		$("#no-login-bg").css("width","100%").css("height","auto");
	}
}