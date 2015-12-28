function main_scrollPreview(){
	if($("#main_scrollpreviewcont").length==0){
		clearTimeout(window.mainScrollPreviewTimeout);
		window.mainScrollPreviewTimeout=null;
		return;
	}
	var k=$("#main_scrollpreviewcont").attr("rel");
	var sz=$("#main_scrollpreviewcont").children().length;
	var onesz=$("#main_scrollpreviewcont").children(":first").width()+3;
	if(!k) k=0;
	if(k>=$("#main_scrollpreviewcont").width()-$("#main_scrollpreviewcont").parent().width()-onesz)
		k=0;
	else
		k=parseInt(k)+onesz*3;
	k=k % (sz*onesz);
	if(k>=$("#main_scrollpreviewcont").width()-$("#main_scrollpreviewcont").parent().width()-onesz)
		k=$("#main_scrollpreviewcont").width()-$("#main_scrollpreviewcont").parent().width()-onesz;
	$("#main_scrollpreviewcont").attr("rel",k);
	$("#main_scrollpreviewcont").animate({
		left: '-'+k+'px'
	}, 500, function() {
		window.mainScrollPreviewTimeout=setTimeout(function(){main_scrollPreview();}, 5000);
	});
}
function main_scrollAdInit(){
	if(window.mainScrollPreviewTimeout)
		clearTimeout(window.mainScrollPreviewTimeout);
	window.mainScrollPreviewTimeout=setTimeout(function(){main_scrollPreview();}, 5000);
}