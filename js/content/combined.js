/*TEST*//* [./util/schedule.js] */function util_schedule_cancelEdit(cancelBtn){
	frm=$(cancelBtn).parent().parent();
	frm.next().css("display","block");
	frm.css("display","none");
	frm.each(function(){ this.reset(); });
	return false;
}
function util_schedule_goEdit(editBtn){
	frm=$(editBtn).parent().parent().find("form");
	frm.css("display","block");
	frm.next().css("display","none");
}/* [./admin.js] */function searchForUsers(restart){
	window.noAlertOnLeave=true;
	usr=$('#s_user_to_find').val();
	$('#search_results').empty();
	$('#n_user_select').empty();
	if(restart){
		$('#n_user_find_page').val(0);
		pg=0;
	}else{
		pg=$('#n_user_find_page').val()+1;
		$('#n_user_find_page').val(pg);
	}
	if(usr=="") usr="%";
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
					$('#cmd_search_more').prop("disabled","disabled");
					alert("검색을 마쳤습니다.");
				}else{
					jQuery.each(ret, function(index, usr) {
						o=$("<tr></tr>");
						o.append($("<td><input type=\"radio\" name=\"user_selection\" id=\"user_selection_"+usr['n_id']+"\" />"));
						o.append($("<td></td>").text(usr['n_id']));
						o.append($("<td></td>").text(usr['s_id']));
						o.append($("<td></td>").text(usr['n_level']));
						o.append($("<td></td>").text(usr['s_name']));
						o.append($("<td></td>").text(usr['s_email']));
						$('#search_results').append(o);
						o=$("<option></option>");
						o.text(usr['n_level'] + "기 " + usr['s_name'] + " (" + usr['s_id'] + ")");
						o.val(usr['n_id']);
						$('#n_user_select').append(o);
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
function flipChecked(cls){
	$("."+cls).each(function(){
		if($(this).is(":checked"))
			$(this).removeProp("checked");
		else
			$(this).prop("checked","checked");
	});
}
function changeCategoryPage(curr,max){
	for(i=0;i<=max;i++){
		smoothToggleVisibility("#class_group_"+i,i==curr?2:1);
	}
}
function findCategoryPage(wat, max){
	if($("#searchby_"+wat).length){
		changeCategoryPage($("#searchby_"+wat).val(),max);
	}else
		alert(wat + " 카테고리가 없습니다.");
	return false;
}
function setAdminOverride(chk){
	chk.prop("disabled", "disabled");
	$.ajax({
		type: "POST",
		url: "/check",
		data: "action=admin&admin_act=setadminoverride" + (chk.is(':checked')?"&checked=checked":"")
	}).done(function(msg){
		chk.removeProp("disabled");
		try{
			var ret=JSON.parse(msg);
			if(ret["error"]==1){
				alert(ret["__other"]);
			}
		}catch(e){
			alert(msg + "\n" + e);
		}
	}).fail(function(jqXHR, textStatus) {
		chk.removeProp("disabled");
		msg="Request failed: " + textStatus;
		alert(msg);
	});
}
function setChecked(cls, chk){
	if(!chk)
		$("."+cls).removeProp("checked");
	else
		$("."+cls).prop("checked","checked");
}
function confirmCategoryAction(frm){
	act=$(frm).find("#cat_act_what").val();
	if(act!="remove" && act!="truncate" && act!="movedata")
		return false;
	return confirm("정말로 " + act + " 작업을 실행하시겠습니까?");;
}
function prepareRemove(){ $('#cat_act_what').val("remove"); }
function prepareTruncate(){ $('#cat_act_what').val("truncate"); }
function prepareMove(){
	k=prompt("옮길 카테고리의 번호를 입력해 주세요.");
	if(k){
		$('#cat_act_to').val(k);
		$('#cat_act_what').val("movedata");
		return true;
	}
	return false;
}
function prepareCategoryPermission(cat,nam){
	$("#cat_perm_what").val(cat);
	$("#cat_act_from").val(cat);
	$("#cat_perm_what_desc").text(nam);
	$("#categoryPermissionChangeDiv").css("display", "block");
	$("#n_permission_type_category").prop("checked","checked");
	$('#n_level_select').removeProp('disabled');
	$('#n_user_select').prop('disabled','disabled');
	fetchCategoryPermission();
}
function fetchCategoryPermission(){
	cat=$("#cat_perm_what").val();
	radio=$('#n_level_select').val();
	return fetchPermission("/check?action=admin&fetch=category&sub=permissions&cat="+cat+"&level="+radio);
}
function fetchUserPermission(){
	cat=$("#cat_perm_what").val();
	radio=$('#n_user_select').val();
	return fetchPermission("/check?action=admin&fetch=category&sub=permissions&cat="+cat+"&level=user&user="+radio);
}
function fetchPermission(theurl){
	showLoading("불러오는 중...");
	window.ajax_write=$.ajax({
		type: "GET",
		url: theurl
	}).done(function(msg) {
		hideLoading();
		try{
			var ret=JSON.parse(msg);
			if(ret["error"]==1){
				alert(ret["__other"]);
			}else{
				$("#categoryPermissionSelectDiv").css("display", "block");
				jQuery.each(ret, function(index, element) {
					index=index.substring(4);
					if(element==1)
						$("#cat_perm_each_"+index).prop("checked", true);
					else
						$("#cat_perm_each_"+index).prop("checked", false);
				});
			}
		}catch(e){
			alert(msg + "\n" + e);
		}
	}).fail(function(jqXHR, textStatus) {
		hideLoading();
		msg="Request failed: " + textStatus;
		alert(msg);
	});
	return false;
}
function fetchSpecialPermissioners(key){
	showLoading("불러오는 중...");
	window.ajax_write=$.ajax({
		type: "GET",
		url: "/check?action=admin&fetch=special_permission&type="+key
	}).done(function(msg) {
		hideLoading();
		try{
			var ret=JSON.parse(msg);
			if(ret["error"]==1){
				alert(ret["__other"]);
			}else{
				$("#div_special_permissioners").html(ret["permissioners"]);
				$('#grant_permission_found_users').children().remove();
			}
		}catch(e){
			alert(msg + "\n" + e);
		}
	}).fail(function(jqXHR, textStatus) {
		hideLoading();
		msg="Request failed: " + textStatus;
		alert(msg);
	});
	return false;
}
var findSpecialPermissionUser_pg=0;
function findSpecialPermissionUser(srch, keepgoing){
	showLoading("불러오는 중...");
	if(!keepgoing)findSpecialPermissionUser_pg=0;
	window.ajax_write=$.ajax({
		type: "POST",
		url: "/ajax/user/search",
		data: "page="+findSpecialPermissionUser_pg+"&search="+encodeURIComponent(srch)
	}).done(function(msg) {
		hideLoading();
		try{
			var ret=JSON.parse(msg);
			if(ret["error"]==1){
				alert(ret["__other"]);
			}else{
				jQuery.each(ret, function(index, usr) {
					a=$("<input type='checkbox' id='grant_permission_"+usr['n_id']+"' name='grant_permission[]' value='"+usr['n_id']+"' />");
					b=$("<label for='grant_permission_"+usr['n_id']+"'></label>");
					b.text(" "+usr['n_level'] + "기 " + usr['s_name']);
					if( $('#grant_permission_found_users').find("#grant_permission_"+usr['n_id']).length) return;
					$('#grant_permission_found_users').append(a).append(b).append($("<br />"));
				});
			}
		}catch(e){
			alert(msg + "\n" + e);
		}
	}).fail(function(jqXHR, textStatus) {
		hideLoading();
		msg="Request failed: " + textStatus;
		alert(msg);
	});
	findSpecialPermissionUser_pg++;
	return false;
}/* [./main.js] */function main_scrollPreview(){
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
}/* [./user/login.js] */function initializeLoginBackgroundImage(url,w,h){
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
}/* [./user/message.js] */window.noAlertOnLeave=1;
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
		CKEDITOR.instances['s_data_ckeditor'].insertHtml(addd); 
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
}/* [./user/manage.js] */function user_manage_searchForUsers(restart){
	window.noAlertOnLeave=true;
	usr=$('#s_user_to_find').val();
	$('#search_results').empty();
	$('#n_user_select').empty();
	if(restart){
		$('#n_user_find_page').val(0);
		pg=0;
	}else{
		pg=$('#n_user_find_page').val()+1;
		$('#n_user_find_page').val(pg);
	}
	if(usr=="") usr="%";
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
					$('#cmd_search_more').prop("disabled","disabled");
					alert("검색을 마쳤습니다.");
				}else{
					jQuery.each(ret, function(index, usr) {
						o=$("<option></option>");
						o.text(usr['n_level'] + "기 " + usr['s_name'] + " (" + usr['s_id'] + ")");
						o.val(usr['n_id']);
						$('#n_user_select').append(o);
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

function user_manage_flipChecked(cls){
	$("."+cls).each(function(){
		if($(this).is(":checked"))
			$(this).removeProp("checked");
		else
			$(this).prop("checked","checked");
	});
}
function user_manage_changeCategoryPage(curr,max){
	for(i=0;i<=max;i++){
		smoothToggleVisibility("#class_group_"+i,i==curr?2:1);
	}
}
function user_manage_findCategoryPage(wat, max){
	if($("#searchby_"+wat).length){
		user_manage_changeCategoryPage($("#searchby_"+wat).val(),max);
	}else
		alert(wat + " 카테고리가 없습니다.");
	return false;
}
function user_manage_setChecked(cls, chk){
	if(!chk)
		$("."+cls).removeProp("checked");
	else
		$("."+cls).prop("checked","checked");
}
function user_manage_prepareCategoryPermission(cat,nam){
	$("#cat_perm_what").val(cat);
	$("#cat_perm_what_desc").text(nam);
	$("#categoryPermissionChangeDiv").css("display", "block");
	$("#n_permission_type_category").prop("checked","checked");
	$('#n_level_select').removeProp('disabled');
	$('#n_user_select').prop('disabled','disabled');
	fetchCategoryPermission();
}
function user_manage_fetchCategoryPermission(){
	cat=$("#cat_perm_what").val();
	radio=$('#n_level_select').val();
	return user_manage_fetchPermission("/check?action=manage&fetch=category&sub=permissions&cat="+cat+"&level="+radio);
}
function user_manage_fetchUserPermission(){
	cat=$("#cat_perm_what").val();
	radio=$('#n_user_select').val();
	return user_manage_fetchPermission("/check?action=manage&fetch=category&sub=permissions&cat="+cat+"&level=user&user="+radio);
}
function user_manage_fetchPermission(theurl){
	showLoading("불러오는 중...");
	window.ajax_write=$.ajax({
		type: "GET",
		url: theurl
	}).done(function(msg) {
		hideLoading();
		try{
			var ret=JSON.parse(msg);
			if(ret["error"]==1){
				alert(ret["__other"]);
			}else{
				$("#categoryPermissionSelectDiv").css("display", "block");
				jQuery.each(ret, function(index, element) {
					index=index.substring(4);
					if(element==1)
						$("#cat_perm_each_"+index).prop("checked", "checked");
					else
						$("#cat_perm_each_"+index).removeProp("checked");
				});
			}
		}catch(e){
			alert(msg + "\n" + e);
		}
	}).fail(function(jqXHR, textStatus) {
		hideLoading();
		msg="Request failed: " + textStatus;
		alert(msg);
	});
	return false;
}/* [./user/settings.js] */function settings_menu_checkItemCount(){
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
}/* [./board.js] */function board_askImportant(obj,aid){
	obj=$(obj);
	if(obj.css("opacity")==0.5){
		alert("신청 진행 중입니다.");
		return false;
	}
	var name=prompt("필공 신청 이유를 적어 주세요.","");
	if(name==null) return;
	if(name==""){
		alert("이유를 입력하셔야 합니다.");
		return false;
	}
	if (name!="") {
		obj.fadeTo(200,0.5);
		$.ajax({
			type: "POST",
			url: "ajax/util/important",
			data: "util_action=add&article_id="+aid+"&reason="+encodeURIComponent(name)
		}).done(function(msg){
			try{
				var ret=JSON.parse(msg);
				if(ret["error"]==0){
					alert("요청하였습니다.");
					obj.parent().append("<span style='color:gray'>필공 요청함</span>");
					obj.remove();
				}else{
					alert($ret['__other']);
				}
			}catch(e){
				alert(msg);
			}
			obj.fadeTo(200,1);
		}).fail(function(jqXHR, textStatus) {
			alert( "오류가 발생하였습니다: " + textStatus );
			obj.fadeTo(200,1);
		});
	}
	return false;
}
function board_checkSearchFrom(){
	arr=[];
	if($("#chk_search_title").is(":checked")) arr.push("제목");
	if($("#chk_search_data").is(":checked")) arr.push("내용");
	if($("#chk_search_tag").is(":checked")) arr.push("태그");
	if($("#chk_search_writer").is(":checked")) arr.push("글쓴이");
	if(arr.length==0){
		$("#search_from_toggler").html("(검색하지 않음)");
		$("#search_button").attr("disabled","disabled");
	}else{
		$("#search_from_toggler").html(arr.join(", ") + "에서");
		$("#search_button").removeAttr("disabled");
	}
}
function board_putCommentForm(parent){
	curr=$("#article_comment_write_"+parent);
	if(curr.length>0){
		if(curr.find("[name='s_data']").text()){
			if(confirm("댓글 쓰기를 취소하시겠습니까?"))
				curr.remove();
		}else{
			curr.remove();
		}
	}else{
		obj=$("#article_comment_"+parent);
		toadd=$($("#article_comment_template").html().split("<%=ARTICLEID%>").join(parent));
		obj.append(toadd);
		
		scrollToMiddle(toadd.offset().top);
		toadd.find("textarea").focus();
	}
	return false;
}
function board_checkSearchMethod(){
	arr=[];
	if($("#chk_search_mode_and").is(":checked")) arr.push("모든 조건을 만족할 시");
	if($("#chk_search_mode_or").is(":checked")) arr.push("하나라도 만족할 시");
	$("#search_method_toggler").html(arr.join(", "));
}
function board_prepareSwfUploadBoardWrite(sessid){
	var settings = {
		flash_url : "/swfupload/swfupload.swf",
		upload_url: "/ajax/board/upload",
		post_params: {"_CUSTOM_PHPSESSID" : sessid},
		file_size_limit : $("#uploaded_file_maximum_size").val(),
		file_types : "*.*",
		file_types_description : "모든 파일",
		file_upload_limit : 1024,
		file_queue_limit : 0,
		custom_settings : {
			progressTarget : "fsUploadProgress",
			cancelButtonId : "btnCancel"
		},
		debug: false,

		
		button_image_url: "/images/upload.gif",
		button_width: "61",
		button_height: "22",
		button_placeholder_id: "spanButtonPlaceHolder",
		button_text: '<span class="theFont"></span>',
		button_text_style: ".theFont { font-size: 16; }",
		button_text_left_padding: 12,
		button_text_top_padding: 3,
		
		
		file_queued_handler : fileQueued,
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadComplete,
		queue_complete_handler : queueComplete	
	};
	swfu = new SWFUpload(settings);
}
function board_refreshUploadedItemTimes(){
	if(window.uploadedItemTimeChecker)
		clearTimeout(window.uploadedItemTimeChecker);
	$(".time_left").each(function(){
		timeLeft=$(this).find(".val").val()-unixtime();
		if(timeLeft<0)
			removeItem($(this).parent().parent().attr("id").replace("uploaded_item_",""));
		$(this).find(".disp").text(timeToString(timeLeft))
	});
	window.uploadedItemTimeChecker=setTimeout("board_refreshUploadedItemTimes();" , 1000);
}
function board_addFileToWriteList(fn,fdn,comm,no_expire){
	var idx=parseInt($("#uploaded_files_index").val());
	var cnt=parseInt($("#uploaded_files_count").html());
	var key="_img_list_item_" + idx;
	var fileTypes=[
		"bmp;png;jpg;jpeg;tif;tiff;gif;svg", 
		"mp3;wav;ogg;mp2;mpa;flac;wavpack;ape;alac;ra;mid",
		"avi;mp4;mkv;flv;mov;mpeg;mpg;3gp;ts;wmv;asf;ogm;ogv;rm;rmvb;aac;ac3;m4a"
	];
	var fileDesc=[
		"",
		"/images/sound.gif",
		"/images/movie.gif"
	];
	dat=$("#uploadedItemTemplete").html();
	dat=dat.split("<%=key%>").join(key);
	var item=$(dat);
	if(no_expire){
		item.find(".time_left").parent().append($("<div>업로드됨</div>"));
		item.find(".time_left").remove();
	}else
		item.find(".time_left").find(".val").val( unixtime()+86400 );
	$("#fsUploadedFiles").append(item);
	var ext=(fdn.indexOf(".")>-1)?fdn.split('.').pop():"_";
	$("#uploadedItemImgDesc_"+key).attr("src","/images/unknown.gif");
	$("#uploadedItemImgDesc_"+key).css("visibility","visible");
	$("#uploadedItemImg_"+key).css("visibility","hidden");
	$("#uploadedItemImgA_"+key).css("visibility", "hidden");
	$("#uploadedItemAction_"+key).val(-1);
	for(i=0;i<fileTypes.length;i++){
		if(fileTypes[i].indexOf(ext.toLowerCase())>-1){
			if(i==0){
				$("#uploadedItemImg_"+key).attr("src",fn);
				$("#uploadedItemImgDesc_"+key).css("visibility","hidden");
				$("#uploadedItemImg_"+key).css("visibility","visible");
				$("#uploadedItemImgA_"+key).attr("href",fn);
				$("#uploadedItemImgA_"+key).css("visibility", "hidden");
				$("#uploadedItemImgA_"+key).prop("rel","lightbox[uploaded_images]");
			}else{
				$("#uploadedItemImgDesc_"+key).attr("src",fileDesc[i]);
			}
			$("#uploadedItemAction_"+key).val(i);
			break;
		}
	}
	if(comm)$("#uploadedItemComment_"+key).val(comm);
	$("#uploadedItemUrl_"+key).val(fn);
	$("#uploadedItemName_"+key).val(fdn);
	$("#fileName_"+key).text(fdn);
	$("#uploaded_files_count").text(cnt+1);
	$("#uploaded_files_index").val(idx+1);
	item.fadeTo(0,0.0);
	item.fadeTo(300,0.5);
	board_refreshUploadedItemTimes();
	$(".uploaded_item_li").mouseenter(function(){
		$(this).stop(true,false).fadeTo(200,1.0);
		$(this).find(".act").stop(true,false).fadeTo(200,1.0);
		$(this).find(".info").stop(true,false).fadeTo(200,1.0);
	}).mouseleave(function(){
		$(this).stop(true,false).fadeTo(200,0.5);
		$(this).find(".act").stop(true,false).fadeTo(200,0.0);
		$(this).find(".info").stop(true,false).fadeTo(200,0.5);
	}).on("dragstart",function(){
		$(this).stop(true,false).fadeTo(200,0.8);
	}).on("dragend",function(){
		$(this).stop(true,false).fadeTo(200,1);
	});
}
function board_insertItem(urlObj){
	var url=$('#uploadedItemUrl_'+urlObj).val();
	var act2=$('#uploadedItemAction_'+urlObj).val();
	if(act2==0){
		element=new Image();
		element.src=url;
	}else{
		element=$("<a></a>");
		element.attr("href",url);
		fn=$("#fileName_"+urlObj).text();
		element.text(fn);
		element=element.get();
	}
	ele2=$("<span></span>");
	ele2.append(element);
	CKEDITOR.instances['s_data_ckeditor'].insertHtml(ele2.html()); 
}
function board_removeItem(urlObj){
	var url=$('#uploadedItemUrl_'+urlObj).val();
	elems=CKEDITOR.instances['s_data_ckeditor'].document.getElementsByTag("img");
	cnt=elems.count();
	for(i=cnt-1;i>=0;i--){
		if(elems.getItem(i).$.src.endsWith(url))
			elems.getItem(i).remove();
	}
	elems=CKEDITOR.instances['s_data_ckeditor'].document.getElementsByTag("a");
	cnt=elems.count();
	for(i=cnt-1;i>=0;i--){
		if(elems.getItem(i).$.href.endsWith(url))
			elems.getItem(i).remove();
	}
	$("#uploaded_item_"+urlObj).remove();
	var cnt=parseInt($("#uploaded_files_count").html());
	$("#uploaded_files_count").text(cnt-1);
}
function board_uploadedItemAction(urlObj, act){
	switch(act){
		case 0:
			board_insertItem(urlObj);
			break;
		case 1:
			if(confirm("정말 이 파일을 제거하시겠습니까?")) board_removeItem(urlObj);
			break;
		case 2:
			var comm=$('#uploadedItemComment_'+urlObj);
			var name=prompt("설명을 입력해 주세요.",comm.val());
			if (name!=null && name!="") {
				comm.val(name);
			}
	}
	return false;
}
/*
	A simple class for displaying file information and progress
	Note: This is a demonstration only and not part of SWFUpload.
	Note: Some have had problems adapting this class in IE7. It may not be suitable for your application.
*/





function FileProgress(file, targetID) {
	this.fileProgressID = file.id;

	this.opacity = 100;
	this.height = 0;
	

	this.fileProgressWrapper = document.getElementById(this.fileProgressID);
	if (!this.fileProgressWrapper) {
		this.fileProgressWrapper = document.createElement("div");
		this.fileProgressWrapper.className = "progressWrapper";
		this.fileProgressWrapper.id = this.fileProgressID;

		this.fileProgressElement = document.createElement("div");
		this.fileProgressElement.className = "progressContainer";

		var progressCancel = document.createElement("a");
		progressCancel.className = "progressCancel";
		progressCancel.href = "#";
		progressCancel.style.visibility = "hidden";
		progressCancel.appendChild(document.createTextNode(" "));

		var progressText = document.createElement("div");
		progressText.className = "progressName";
		progressText.appendChild(document.createTextNode(file.name));

		var progressBar = document.createElement("div");
		progressBar.className = "progressBarInProgress";

		var progressStatus = document.createElement("div");
		progressStatus.className = "progressBarStatus";
		progressStatus.innerHTML = "&nbsp;";

		this.fileProgressElement.appendChild(progressCancel);
		this.fileProgressElement.appendChild(progressText);
		this.fileProgressElement.appendChild(progressStatus);
		this.fileProgressElement.appendChild(progressBar);

		this.fileProgressWrapper.appendChild(this.fileProgressElement);

		document.getElementById(targetID).appendChild(this.fileProgressWrapper);
	} else {
		this.fileProgressElement = this.fileProgressWrapper.firstChild;
		this.reset();
	}

	this.height = this.fileProgressWrapper.offsetHeight;
	this.setTimer(null);


}

FileProgress.prototype.setTimer = function (timer) {
	this.fileProgressElement["FP_TIMER"] = timer;
};
FileProgress.prototype.getTimer = function (timer) {
	return this.fileProgressElement["FP_TIMER"] || null;
};

FileProgress.prototype.reset = function () {
	this.fileProgressElement.className = "progressContainer";

	this.fileProgressElement.childNodes[2].innerHTML = "&nbsp;";
	this.fileProgressElement.childNodes[2].className = "progressBarStatus";
	
	this.fileProgressElement.childNodes[3].className = "progressBarInProgress";
	this.fileProgressElement.childNodes[3].style.width = "0%";
	
	this.appear();	
};

FileProgress.prototype.setProgress = function (percentage) {
	this.fileProgressElement.className = "progressContainer green";
	this.fileProgressElement.childNodes[3].className = "progressBarInProgress";
	this.fileProgressElement.childNodes[3].style.width = percentage + "%";

	this.appear();	
};
FileProgress.prototype.setComplete = function () {
	this.fileProgressElement.className = "progressContainer blue";
	this.fileProgressElement.childNodes[3].className = "progressBarComplete";
	this.fileProgressElement.childNodes[3].style.width = "";

	var oSelf = this;
	this.setTimer(setTimeout(function () {
		oSelf.disappear();
	}, 10000));
};
FileProgress.prototype.setError = function () {
	this.fileProgressElement.className = "progressContainer red";
	this.fileProgressElement.childNodes[3].className = "progressBarError";
	this.fileProgressElement.childNodes[3].style.width = "";

	var oSelf = this;
	this.setTimer(setTimeout(function () {
		oSelf.disappear();
	}, 5000));
};
FileProgress.prototype.setCancelled = function () {
	this.fileProgressElement.className = "progressContainer";
	this.fileProgressElement.childNodes[3].className = "progressBarError";
	this.fileProgressElement.childNodes[3].style.width = "";

	var oSelf = this;
	this.setTimer(setTimeout(function () {
		oSelf.disappear();
	}, 2000));
};
FileProgress.prototype.setStatus = function (status) {
	this.fileProgressElement.childNodes[2].innerHTML = status;
};


FileProgress.prototype.toggleCancel = function (show, swfUploadInstance) {
	this.fileProgressElement.childNodes[0].style.visibility = show ? "visible" : "hidden";
	if (swfUploadInstance) {
		var fileID = this.fileProgressID;
		this.fileProgressElement.childNodes[0].onclick = function () {
			swfUploadInstance.cancelUpload(fileID);
			return false;
		};
	}
};

FileProgress.prototype.appear = function () {
	if (this.getTimer() !== null) {
		clearTimeout(this.getTimer());
		this.setTimer(null);
	}
	
	if (this.fileProgressWrapper.filters) {
		try {
			this.fileProgressWrapper.filters.item("DXImageTransform.Microsoft.Alpha").opacity = 100;
		} catch (e) {
			
			this.fileProgressWrapper.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity=100)";
		}
	} else {
		this.fileProgressWrapper.style.opacity = 1;
	}
		
	this.fileProgressWrapper.style.height = "";
	
	this.height = this.fileProgressWrapper.offsetHeight;
	this.opacity = 100;
	this.fileProgressWrapper.style.display = "";
	
};


FileProgress.prototype.disappear = function () {

	var reduceOpacityBy = 15;
	var reduceHeightBy = 4;
	var rate = 30;	

	if (this.opacity > 0) {
		this.opacity -= reduceOpacityBy;
		if (this.opacity < 0) {
			this.opacity = 0;
		}

		if (this.fileProgressWrapper.filters) {
			try {
				this.fileProgressWrapper.filters.item("DXImageTransform.Microsoft.Alpha").opacity = this.opacity;
			} catch (e) {
				
				this.fileProgressWrapper.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity=" + this.opacity + ")";
			}
		} else {
			this.fileProgressWrapper.style.opacity = this.opacity / 100;
		}
	}

	if (this.height > 0) {
		this.height -= reduceHeightBy;
		if (this.height < 0) {
			this.height = 0;
		}

		this.fileProgressWrapper.style.height = this.height + "px";
	}

	if (this.height > 0 || this.opacity > 0) {
		var oSelf = this;
		this.setTimer(setTimeout(function () {
			oSelf.disappear();
		}, rate));
	} else {
		this.fileProgressWrapper.style.display = "none";
		this.setTimer(null);
	}
};

var total_upload_progress_max=0, total_upload_progress_value=0;
function updateProgressBar(val, max){
	total_upload_progress_max+=max;
	total_upload_progress_value+=val;
	if(total_upload_progress_max<total_upload_progress_value) total_upload_progress_value=total_upload_progress_max;
	$("#total_upload_progress_bar").css("width",(360*total_upload_progress_value/total_upload_progress_max)+"px");
	$("#total_upload_progress").text(total_upload_progress_value + "/" + total_upload_progress_max + " (" + (100*total_upload_progress_value/total_upload_progress_max).toFixed(2) +"%)");
}

/* **********************
   Event Handlers
   These are my custom event handlers to make my
   web application behave the way I went when SWFUpload
   completes different tasks.  These aren't part of the SWFUpload
   package.  They are part of my application.  Without these none
   of the actions SWFUpload makes will show up in my application.
   ********************** */
 
function fileQueued(file) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setStatus("대기 중...");
		updateProgressBar(0,1);
		progress.toggleCancel(true, this);
	} catch (ex) {
		this.debug(ex);
	}

}

function fileQueueError(file, errorCode, message) {
	try {
		if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) { alert("한번에 너무 많은 파일을 선택하였습니다.\n" + (message === 0 ? "업로드 제한에 도달하였습니다." : "한번에 " + (message > 1 ? "최고 " + message + " 파일만 업로드할 수 있습니다." : "하나씩만 업로드할 수 있습니다."))); return; }

		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setError();
		progress.toggleCancel(false);

		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT: progress.setStatus("파일이 너무 큽니다."); break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE: progress.setStatus("빈 파일은 업로드할 수 없습니다."); break;
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE: progress.setStatus("허용하지 않는 파일 형식입니다."); break;
		default: if (file !== null) progress.setStatus("알 수 없는 오류입니다.");  break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}

function fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (numFilesSelected > 0) {
			document.getElementById(this.customSettings.cancelButtonId).disabled = false;
		}
		
		/* I want auto start the upload and I can do that here */
		this.startUpload();
	} catch (ex)  {
        this.debug(ex);
	}
}

function uploadStart(file) {
	try {
		/* I don't want to do any file validation or anything,  I'll just update the UI and
		return true to indicate that the upload should start.
		It's important to update the UI here because in Linux no uploadProgress events are called. The best
		we can do is say we are uploading.
		 */
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setStatus("업로드 중...");
		progress.toggleCancel(true, this);
	}
	catch (ex) {}
	
	return true;
}

function uploadProgress(file, bytesLoaded, bytesTotal) {
	try {
		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);

		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setProgress(percent);
		progress.setStatus("업로드 중...");
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadSuccess(file, serverData) {
	updateProgressBar(1,0);
	var err;
	try {
		try{
			var ret=JSON.parse(serverData);
		}catch(e){
			alert(serverData);
			err="잘못된 응답을 받았습니다.";
		}
		if(ret){
			if(ret["error"]==0){
				board_addFileToWriteList(ret["filename"], ret["disp_filename"]);
				var progress = new FileProgress(file, this.customSettings.progressTarget);
				progress.setComplete();
				progress.setStatus("업로드하였습니다.");
				progress.toggleCancel(false);
				return;
			}else{
				err=ret["error"];
			}
		}
	} catch (ex) {
		err=ex;
		alert(ex);
	}
	var progress = new FileProgress(file, this.customSettings.progressTarget);
	progress.setError();
	progress.setStatus(err);
	progress.toggleCancel(false);
}

function uploadError(file, errorCode, message) {
	updateProgressBar(0,-1);
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setError();
		progress.toggleCancel(false);

		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
			progress.setStatus("업로드 실패: " + message);
			this.debug("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
			progress.setStatus("업로드에 실패하였습니다..");
			this.debug("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.IO_ERROR:
			progress.setStatus("서버에서 오류가 발생하였습니다.");
			this.debug("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
			progress.setStatus("보안 오류가 발생하였습니다.");
			this.debug("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			progress.setStatus("업로드 제한을 초과하였습니다.");
			this.debug("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
			progress.setStatus("파일 검증에 실패하였습니다.");
			this.debug("Error Code: File Validation Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			
			if (this.getStats().files_queued === 0) {
				document.getElementById(this.customSettings.cancelButtonId).disabled = true;
			}
			progress.setStatus("취소됨");
			progress.setCancelled();
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			progress.setStatus("중단됨");
			break;
		default:
			progress.setStatus("알 수 없는 오류: " + errorCode);
			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}

function uploadComplete(file) {
	if (this.getStats().files_queued === 0) {
		document.getElementById(this.customSettings.cancelButtonId).disabled = true;
	}
}


function queueComplete(numFilesUploaded) {
}
