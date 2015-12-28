function searchForUsers(restart){
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
}