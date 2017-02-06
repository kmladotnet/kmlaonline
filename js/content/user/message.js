window.noAlertOnLeave=1;
function user_message_applyToList(){
	$(".user_search_results").each(function(idx,elem){
		if($(elem).is(":checked")){
			user_message_addRecepientUser(elem.value, $(elem).next("label").html());
		}
	});
}
function user_message_addRecepientUser(userIndex, userDesc){
	if($(".user_index_"+userIndex).length) return;
	s=$("#user_list_template").html();
	s=s.replaceAll("<%=USERINDEX%>", userIndex);
	s=s.replaceAll("<%=USERDESC%>", userDesc);
	obj=$(s);
	obj.css("display","none");
	$('.user_list').append(obj);
	smoothToggleVisibility(obj,2);
	obj.css("pointer","hand");
	return false;
}
function user_message_putQuote(from){
	var addd="<p><blockquote>"+$("#message_item_"+from).find(".view_data").html().replaceAll("\n","").replaceAll("\r","").replace(/(<\/\w+>)/i,"$1\n").replace(/<blockquote>.*<\/blockquote>/i, "")+"</blockquote></p>";
	if(CKEDITOR.instances['s_data_ckeditor']){
		CKEDITOR.instances['s_data_ckeditor'].insertHtml(addd); //Element( new CKEDITOR.dom.element(element) );
	}else{
		$("#s_data_ckeditor").val($("#s_data_ckeditor").val()+addd);
	}
}
function user_message_searchForUsers(restart){
	usr=$('#s_user_to_find').val();
	if(restart){
		$('#n_user_find_page').val(0);
		$('#id_find_results').empty();
		$('#cmd_search_more').removeAttr("disabled");
		pg=0;
	}else{
		pg=$('#n_user_find_page').val()+1;
		$('#n_user_find_page').val(pg);
	}
	if(usr=="") return alert("사용자 정보를 입력해 주세요.");
	$.ajax({
		type: "POST",
		url: "/ajax/user/search",
		data: "page="+pg+"&search="+encodeURIComponent(usr)
	}).done(function(msg) {
		window.ajaxing=false;
		try{
			var ret=JSON.parse(msg);
			if(ret["error"]==1){
				alert(ret["error"]);
				delete ret["error"];
			}else{
				if(msg=="[]"){
					$('#cmd_search_more').attr("disabled","disabled");
					obj=$("<li>검색을 마쳤습니다.</li>");
					obj.css("display","none");
					$("#id_find_results").append(obj);
					smoothToggleVisibility(obj,2);
				}else{
					jQuery.each(ret, function(index, element) {
						user_message_addCandidateUser(element['n_id'], element['s_id'], element['s_name']);
					});
				}
			}
		}catch(e){
			alert(e+"\n"+msg);
		}
		cancelAjaxSave();
	}).fail(function(jqXHR, textStatus) {
		window.ajaxing=false;
		alert( "Request failed: " + textStatus );
		cancelAjaxSave();
	});
	return false;
}
function user_message_addCandidateUser(n_id, s_id, s_name){
	s=$("#user_found_template").html();
	s=s.replaceAll("<%=USERINDEX%>", n_id);
	s=s.replaceAll("<%=USERDESC%>", s_id + " (" + s_name + ")");
	obj=$(s);
	obj.css("display","none");
	$("#id_find_results").append(obj);
	smoothToggleVisibility(obj,2);
}
function user_message_loadCompose(){
	smoothToggleVisibility($("#div_message").find(".div_message_item:visible"), 1);
	if((obj=$("#compose")).length){
		smoothToggleVisibility(obj, 2);
	}
}
function user_message_messageLoadComplete(msg, obj, id){
	try{
		var ret=JSON.parse(msg);
		if(ret["error"]==1){
			if(window.removing!=1)
				alert(ret["error"]);
			delete ret["error"];
		}else{
			obj.find("div").remove();
			k=$(ret["data"]);
			k.css("display", "none");
			obj.append(k);
			smoothToggleVisibility(k, 2);
			txt=$("#message_list_item_"+id).find(".preview");
			txt.text(ret['shortstr']);
			txt.css("font-weight","inherit");
			txt.css("color","inherit");
		}
	}catch(e){
		if(window.removing!=1)
			alert(e+"\n"+msg);
	}
}
function user_message_loadMessage(id){
	smoothToggleVisibility($("#div_message").find(".div_message_item:visible"), 1);
	if((obj=$("#message_item_"+id)).length){
		smoothToggleVisibility(obj, 2);
		return;
	}
	obj=$("<div id='message_item_"+id+"' class='div_message_item'><div style='text-align:center'>불러오는 중...</div></div>");
	obj.css("display","none");
	$("#div_message").append(obj);
	smoothToggleVisibility(obj, 2);
	$.ajax({
		type: "POST",
		url: "/ajax/user/getmessage",
		data: "id="+id
	}).done(function(msg) {
		user_message_messageLoadComplete(msg, obj, id);
	}).fail(function(jqXHR, textStatus) {
		if(window.removing!=1)
			alert( "Request failed: " + textStatus );
	});
}
function user_message_removeNote(idx){
	if(!confirm("정말로 이 쪽지를 제거하시겠습니까?")) return false;
	window.removing=1;
	$.ajax({
		type: "POST",
		url: "/ajax/user/removemessage",
		data: "id="+idx
	}).done(function(msg) {
		try{
			var ret=JSON.parse(msg);
			if(ret["error"]==1){
				alert(ret["error"]);
				delete ret["error"];
			}else{
				alert("쪽지가 제거되었습니다.");
				location.reload(true);
			}
		}catch(e){
			alert(e+"\n"+msg);
		}
	}).fail(function(jqXHR, textStatus) {
		alert( "Request failed: " + textStatus );
	});
	return false;
}
function user_message_confirmClearAll(){
	return confirm("정말로 모든 메시지를 지울까요?");
}