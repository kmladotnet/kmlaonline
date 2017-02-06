function settings_menu_checkItemCount(){
	if($("#menusorter").children().length>=8){
		alert("항목을 9개 이상 추가할 수 없습니다.");
		return true;
	}
	return false;
}
function settings_menu_addCategory(title){
	if(settings_menu_checkItemCount()) return;
	if($("#menusorter").find(".assigned").length>4)return alert("분류를 5개 이상 추가할 수 없습니다.");
	if(!title)title="분류 이름";
	var li=$($("#menuCategoryForm").html().replaceAll("<%MENUNAME%>",htmlspecialchars(title)));
	li.fadeTo(0,0);
	li.fadeTo(800,1);
	$("#menusorter").append(li);
	scrollToMiddle(li.offset().top);
	flashObject(li,true);
	li.css("display","");
}
function settings_menu_addSubItemWithValue(href,title){
	if(settings_menu_checkItemCount()) return;
	if($("#menusorter").find(".assigned").length>7)return alert("하위 항목을 7개 이상 추가할 수 없습니다.");
	var li=$($("#menuItemForm").html().replaceAll("<%MENUNAME%>",htmlspecialchars(title)).replace("<%MENUACTION%>",htmlspecialchars(href)));
	li.fadeTo(0,0);
	li.fadeTo(800,1);
	$("#menusorter").append(li);
	scrollToMiddle(li.offset().top);
	flashObject(li,true);
	li.css("display","");
}
function settings_menu_addSubItem(){
	if(settings_menu_checkItemCount()) return;
	if($("#menusorter").find(".assigned").length>7)return alert("하위 항목을 7개 이상 추가할 수 없습니다.");
	$("#compactLinkSelector").css("display","block");
	$.ajax({
		type: "GET",
		url: "/sitemap?linkSelector",
		headers: { "x-content-only": "true" }
	}).done(function(msg) {
		var p=$("#compactLinkSelector");
		p.empty();
		var obj=$(msg).find("div#total-content");
		obj.find('a').each(function(){
			if(this.onclick || $(this).is('.clickbound') || this.href=="") return;
			$(this).off("click");
			$(this).click(function(){
				if(this.rel=="closenow"){
					settings_menu_addSubItemWithValue("url:"+this.href,$(this).text());
					return false;
				}
			});
		});
		obj.fadeTo(0,0);
		p.append(obj);
		obj.fadeTo(1000,1);
	}).fail(function(jqXHR, textStatus) {
		//alert( "페이지를 불러 오는 데 실패하였습니다" );
	});
}
function settings_menu_removeSelf(t){
	if(confirm("정말로 이 항목을 제거하시겠습니까?"))
		$(t).parent().remove();
}
function settings_menu_reload(lst,lst2){
	$("#menusorter").children().remove();
	for(var i=0;i<lst.length;i++){
		if(lst2[i]=="divider"){
			settings_menu_addCategory(lst[i]);
		}else{
			settings_menu_addSubItemWithValue(lst2[i],lst[i]);
		}
	}
}