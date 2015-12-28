<?php
$title="설문조사 만들기- " . $title;
/*
	[
		[survey][name]				=Survey Name 																						(default SURVEYKEY)
		[survey][anonymous]		=t/1/true or f/0/false																				(default FALSE, unset DISABLED)
		[survey][oneperuser]		=true																									(default TRUE, reset DISABLED)
		[items][key]					=item4																									(default TIME)
		[items][dupefirst]			=newest / oldest																					(default OLDEST FIRST)
		[items][condition]			=PHP Expresions: ex) item1+item2+item3==3											(default TRUE)
		[items][0][name]			=Item Names: AAA
		[items][0][type]				=numeric																								(default STRING)
		[items][0][min]				=0																										(default NOT SET)
		[items][0][max]				=3																										(default NOT SET)
		[items][1][name]			=Item Names: BBB
		[items][1][type]				=numeric
		[items][2][name]			=Item Names: CCC
		[items][2][type]				=numeric
		[items][3][name]			=Item Names: Room Number
		[items][3][type]				=numeric
		[items][3][regexp]			=[0-9]{3,4}																								(default .*)
		[items][4][name]			=Special Instructions
		[items][4][regexp]			=.{0,128}
		[items][4][optional]		=true																									(default FALSE)
	]
*/

function printContent(){
	?>
	<h1 style="padding:9px">설문 만들기</h1>
	<form action="/proc/util/create-survey" method="post" onsubmit="return saveAjax(this,'생성 중...',null,survey_gotData);">
		<table class="notableborder-direct" style="width:100%">
			<tr>
				<th style="width:120px;">설문 이름</th>
				<td>
					<label for="survey.anonymous" style="float:right;margin-left:16px;padding-top:10px;"><input type="checkbox" id="survey.anonymous" name="survey_anonymous" /> 익명 설문</label>
					<label for="survey.oneperuser" style="float:right;padding-top:10px;"><input type="checkbox" id="survey.oneperuser" name="survey_oneperuser" /> 사용자당 하나씩</label>
					<input type="text" id="survey.name" name="survey_name" style="width:730px" />
					<div style="clear:both"></div>
				</td>
			</tr>
			<tr>
				<th>자료 중복 시</th>
				<td>
					<select name="items_orderby">
						<option value="newest" selected="selected">새로운 항목</option>
						<option value="oldest">기존 항목</option>
					</select> 우선
				</td>
			</tr>
			<tr>
				<th>조건식</th>
				<td><input type="text" name="survey_condition" style="width:970px" /></td>
			</tr>
		</table>
		<input type="button" onclick="return survey_addSurvey();" value="항목 추가" /><br />
		<label for="items.key[_].iskey"><input type="radio" id="items.key[_].iskey" name="items_key" checked="checked" value="-1" style="margin:6px 0;padding:6px 0;" /> 정렬하지 않음</label>
		<ul id="surveysorter" class="ui-sortable">
		</ul>
		<input type="submit" style="height:32px;width:80px;" value="생성" />
	</form>
	<div style="margin:5px">
		<textarea id="return_data" readonly="readonly" style="box-sizing:border-box;width:100%;height:96px;"></textarea>
	</div>
	<script type="text/javascript">
		function survey_reorderRadio(){
		}
		function survey_addSurvey(){
			var str=$("#survey_item_form").html();
			str=str.replaceAll("%UNIQID%", Math.random() + new Date().toString());
			$("#surveysorter").append($(str));
		}
		function survey_gotData(ret, msg){
			$("#return_data").text(ret['data']);
		}
		$( "#surveysorter" ).sortable({
			start: function( event, ui ) {
				ui.item.css("border","1px solid gray");
				ui.item.css("padding","6px");
				$("#surveysorter").height($("#surveysorter").height());
			},
			stop: function( event, ui ) {
				ui.item.css("border","none");
				ui.item.css("border-top","1px solid gray");
				ui.item.css("padding","6px 0");
			},
			axis: "y",
			distance: 8
		});
	</script>
	<script id="survey_item_form" type="text/html">
		<div style="margin:6px 0;padding:6px 0;border-top:1px solid gray;background:white;">
			<div style="float:left;margin-right:8px;">
				<label for="items.key[%UNIQID%].iskey"><input type="radio" id="items.key[%UNIQID%].iskey" name="items_key" id="radio_items_key" value="0" /> 이것으로 정렬</label>
				<div style="margin-left:48px;">
					<label for="items.key[%UNIQID%].optional"><input type="checkbox" id="items.key[%UNIQID%].optional" name="items_optional[]" /> 입력하지 않아도 됨</label><br />
					종류: <select name="items_type[]">
						<option value="numeric" selected="selected">정수</option>
						<option value="real">실수</option>
						<option value="string">문자열</option>
					</select>
				</div>
			</div>
			항목 이름: <input type="text" id="items.key[%UNIQID%].name" name="items_name[]" style="width:800px;" /><br />
			길이/범위: <input type="text" id="items.key[%UNIQID%].min" name="items_min[]" /> ~ <input type="text" id="items.key[%UNIQID%].max" name="items.max[]" />
			정규식: <input type="text" id="items.key[%UNIQID%].regexp" name="items_regexp[]" style="width:460px;" />
			<div style="clear:both"></div>
		</div>
	</script>
	<?php
}