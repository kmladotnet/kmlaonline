function user_manage_searchForUsers(restart){
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
}