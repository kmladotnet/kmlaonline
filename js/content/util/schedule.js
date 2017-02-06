function util_schedule_cancelEdit(cancelBtn){
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
}