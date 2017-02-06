<?php
//die(print_r($_POST));
$dat=array(
	"survey"=>array(
		"name"=>$_POST['survey_name']==""?"Untitled":$_POST['survey_name'],
		"anonymous"=>isset($_POST['survey_anonymous'])?1:0,
		"oneperuser"=>isset($_POST['survey_oneperuser'])?1:0
	),
	"items"=>array(
		"key"=>md5(time().uniqid().serialize($_SERVER)),
		"dupefirst"=>$_POST['items_orderby']=="newest"?"newest":"oldest",
		"condition"=>$_POST['survey_condition']!==""?"1":$_POST['survey_condition']
	)
);
for($i=0;isset($_POST['items_name'][$i]);$i++){
	$dat['items'][$i]=array(
		"name"=>$_POST['items_name'][$i]==""?"Untitled item $i":$_POST['items_name'][$i],
		"type"=>$_POST['items_type'][$i]=="numeric"?"numeric":($_POST['items_type'][$i]=="real"?"real":"string"),
		"min"=>$_POST['items_min'][$i]===""?0:$_POST['items_min'][$i],
		"max"=>$_POST['items_max'][$i]===""?0:$_POST['items_max'][$i],
		"regexp"=>$_POST['items_regexp'][$i]===""?".*":$_POST['items_regexp'][$i]
	);
}
$ret=base64_encode(json_encode($dat));
$ret="<div id='survey=$ret' style='border:1px solid black;display:inline-block;padding:3px;margin:3px;' contentEditable='false'>Survey <b>".htmlspecialchars($dat['survey']['name'])."</b> goes here</div>";
die(json_encode(array("data"=>$ret)));
?>
Array
(
    [ajax] => 1
    [survey_anonymous] => on
    [survey_oneperuser] => on
    [survey_name] => 123
    [items_orderby] => newest
    [survey_condition] => 456
    [items_key] => 0
    [items_optional] => Array
        (
            [0] => on
        )

    [items_type] => Array
        (
            [0] => string
        )

    [items_name] => Array
        (
            [0] => test
        )

    [items_min] => Array
        (
            [0] => 34
        )

    [items_max] => Array
        (
            [0] => 56
        )

    [items_regexp] => Array
        (
            [0] => 123
        )

)
1