<?php
redirectLoginIfRequired();
$errors=array();
function redirWithBody($failReason){
	?>
	<form method="post" action="<?php echo htmlspecialchars($_POST['prev_url'])?>" id="poster">
		<?php
		foreach($_POST as $key=>$val){
			if(is_array($val)){
				foreach($val as $val2){
					$val2=htmlspecialchars($val2);
					echo "<input type='hidden' name='{$key}[]' value='$val2' />";
				}
			}else{
				$val=htmlspecialchars($val);
				echo "<input type='hidden' name='$key' value='$val' />";
			}
		}
		?>
		<input type="hidden" name="error_occured" value="<?php echo htmlspecialchars(json_encode($failReason)) ?>" />
		<input type="submit" id="submitter" value="Click here if the page doesn't continue" />
	</form>
	<script type="text/javascript">$('#poster').submit();$('#submitter').css("visibility", "hidden");</script>
	<?php
}
if(!isset($_POST['n_id']))
	$errors["n_id"]=lang("board","article","unspecified");
else{
	$article=$board->getArticle($_POST['n_id']);
	if($article==false)
		$errors["n_id"]=lang("board","article","nonexist");
	else if(($article["n_writer"]!=$me['n_id']) && !checkCategoryAccess($article['n_cat'], "manage modify"))
		$errors["n_id"]=lang("board","article","notmine");
}
if(count($errors)==0){
	$cat=$board->getCategory($article['n_cat']);
	if($board->removeArticle($article['n_id'])===false)
		ajaxDie(array(), lang("generic","unknown error"));
}
if(count($errors)>0){
	if(isAjax()){
		ajaxDie($errors);
	}else
		redirectWith("redirWithBody",$errors);
}else{
	if($article['n_parent']){
		$top=$article;
		while($top['n_parent']){ $top=$board->getArticle($top['n_parent']); }
		$new_location="/board/{$cat['s_id']}/view/{$top['n_id']}";
	}else
		$new_location="/board/{$cat['s_id']}";
	if(isAjax()){
		ajaxOk(array(), $new_location);
	}else{
		redirectTo($new_location);
	}
}