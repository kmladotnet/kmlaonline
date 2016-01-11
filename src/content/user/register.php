<?php
$title=lang("user","register","title") . " - " . $title;
function printContent(){ ?>
	<!--<script type="text/javascript">alert("가입 기간이 아닙니다.");location.href="/";</script>-->
	<?php
	global $max_level;
	insertOnLoadScript("putAlertOnLeave();");
	?>
	<form action="/ajax/user/register" method="post" enctype="multipart/form-data" onsubmit="window.onbeforeunload=null;">
		<input type="hidden" name="prev_url" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'])?>" />
		<div style="text-align:center;width:100%">
			<h1><?php echo lang("user","register","title"); ?></h1>
			<h2><?php echo lang("generic", "tos"); ?></h2>
			<div style="width:640px;height:240px;overflow:auto;margin:0 auto;text-align:left;border:1px solid gray;padding:5px;">
				<?php echo lang("user","register","tos"); ?>
				<hr />
				<input type="checkbox" id="chk_n_tos_agree" name="n_tos_agree" value="yes" <?php echo (isset($_POST['n_tos_agree']) && $_POST['n_tos_agree']=="yes")?"checked='checked'":""?> /> <label for="chk_n_tos_agree"><?php echo lang("user","register","accept tos"); ?></label>
			</div>
			<table style="margin:0 auto;width:800px;" class="notableborder">
				<tr>
					<th style="width:400px;"><h2><?php echo lang("user","register","required"); ?></h2></th>
					<th style="width:20px;"></th>
					<th style="width:400px;"><h2><?php echo lang("user","register","optional"); ?></h2></th>
				</tr>
				<tr>
					<td style="vertical-align:top">
						<table style="width:100%" class="table-register-data">
							<tr>
								<th style="width:120px;"><?php echo lang("generic","id"); ?></th>
								<td style="width:240px;"><input class="form-control" autocomplete="off" type="text" name="s_id" style="width:100%" <?php echo isset($_POST['s_id'])?"value='".htmlspecialchars($_POST['s_id'])."'":""?> /></td>
							</tr>
							<tr>
								<th style="width:120px;"><?php echo lang("generic","password"); ?></th>
								<td><input class="form-control" type="password" autocomplete="off" name="s_pw" style="width:100%" /></td>
							</tr>
							<tr>
								<th style="width:120px;"><?php echo lang("user","register","password check"); ?></th>
								<td><input class="form-control" type="password" autocomplete="off" name="s_pw_check" style="width:100%" /></td>
							</tr>
							<tr>
								<th style="width:120px;"><?php echo lang("generic","email"); ?></th>
								<td><input class="form-control" type="email" name="s_email" style="width:100%" <?php echo isset($_POST['s_email'])?"value='".htmlspecialchars($_POST['s_email'])."'":""?> /></td>
							</tr>
							<tr>
								<th style="width:120px;"><?php echo lang("generic","level"); ?></th>
								<td>
									<select name="n_wave" class="selectpicker" data-size="5" data-width="100%" style="width:100%">
										<?php for($i=$max_level,$j=0;$i>=1;$i--,$j++){ ?>
											<option value="<?php echo $i?>" <?php echo (isset($_POST['n_wave']) && $_POST['n_wave']==$i)?"selected='selected'":""?>><?php echo $i . "기 " . ($j>=3?"졸업생":"학생") ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<th style="width:120px;"><?php echo lang("generic","name"); ?></th>
								<td><input class="form-control" type="text" name="s_kor_name" style="width:100%" <?php echo isset($_POST['s_kor_name'])?"value='".htmlspecialchars($_POST['s_kor_name'])."'":""?> /></td>
							</tr>
							<tr>
								<th style="width:120px;"><?php echo lang("generic","nick"); ?></th>
								<td><input class="form-control" type="text" name="s_eng_name" style="width:100%" <?php echo isset($_POST['s_eng_name'])?"value='".htmlspecialchars($_POST['s_eng_name'])."'":""?> /></td>
							</tr>
							<tr>
								<th style="width:120px;"><?php echo lang("generic","birthday"); ?></th>
								<td style="text-align:left">
									<input class="form-control" type="number" style="width:80px; display:inline-block" name="n_birth_date_yr" <?php echo isset($_POST['n_birth_date_yr'])?"value='".htmlspecialchars($_POST['n_birth_date_yr'])."'":""?> /><?php echo lang("generic","year"); ?>
									<input class="form-control" type="number" style="width:60px; display:inline-block" name="n_birth_date_month" <?php echo isset($_POST['n_birth_date_month'])?"value='".htmlspecialchars($_POST['n_birth_date_month'])."'":""?> /><?php echo lang("generic","month"); ?>
									<input class="form-control" type="number" style="width:60px; display:inline-block" name="n_birth_date_day" <?php echo isset($_POST['n_birth_date_day'])?"value='".htmlspecialchars($_POST['n_birth_date_day'])."'":""?> /><?php echo lang("generic","day"); ?>
								</td>
							</tr>
							<tr>
								<th style="width:120px;"><?php echo lang("generic","gender"); ?></th>
								<td>
									<select name="n_gender" class="selectpicker" data-width="100%" style="width:100%">
										<?php
										$opt_list=array(lang("generic","unspecified"), lang("generic","male"), lang("generic","female"), lang("generic","other"));
										foreach($opt_list as $key=>$val){
											?><option value="<?php echo $key?>" <?php echo (isset($_POST['n_gender']) && $_POST['n_gender']==$key)?"selected='selected'":""?>><?php echo htmlspecialchars($val)?></option><?php
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<th><?php echo lang("user","register","captcha")?></th>
								<td>
									<a onclick="return false"><img id="img_captcha" src="/files/captcha/0.png" onclick="return refreshCaptcha();" /></a>
									<input class="form-control" type="text" autocomplete="off" name="s_captcha" style="width:100%" />
								</td>
							</tr>
						</table>
					</td>
					<td></td>
					<td style="vertical-align:top">
						<table style="width:100%" class="table-register-data">
							<tr>
								<th style="width:120px;"><?php echo lang("generic","picture")?></th>
								<td><div class="upper-file"><input type="file" name="s_pic" style="width:100%" /><span><?php echo lang("generic","choose file")?></span></div></td>
							</tr>
							<tr>
								<th style="width:120px;"><?php echo lang("generic","icon")?></th>
								<td><div class="upper-file"><input type="file" name="s_icon" style="width:100%" /><span><?php echo lang("generic","choose file")?></span></div></td>
							</tr>
							<tr>
								<th style="width:120px;">학번 방</th>
								<td style="text-align:left">
									학번: <input class="form-control" type="text" name="n_student_id" style="width:80px; display:inline-block" <?php echo isset($_POST['n_student_id'])?"value='".htmlspecialchars($_POST['n_student_id'])."'":""?> />
									방: <input class="form-control" type="text" name="s_room" style="width:80px; display:inline-block" <?php echo isset($_POST['s_room'])?"value='".htmlspecialchars($_POST['s_room'])."'":""?> />
								</td>
							</tr>
							<tr>
								<th style="width:120px;">학년 반</th>
								<td style="text-align:left">
									<select name="n_grade" class="selectpicker" data-width="90px" style="width:80px">
										<?php
										$opt_list=array(10=>"10학년", 11=>"11학년", 12=>"12학년");
										foreach($opt_list as $key=>$val){
											?><option value="<?php echo $key?>" <?php echo (isset($_POST['n_grade']) && $_POST['n_grade']==$key)?"selected='selected'":""?>><?php echo htmlspecialchars($val)?></option><?php
										}
										?>
									</select>
									반: <input class="form-control" type="text" name="s_class" style="width:80px; display:inline-block" <?php echo isset($_POST['s_class'])?"value='".htmlspecialchars($_POST['s_class'])."'":""?> />
								</td>
							</tr>
							<tr>
								<th style="width:120px;"><?php echo lang("generic","status message")?></th>
								<td><input class="form-control" type="text" name="s_status_message" style="width:100%" <?php echo isset($_POST['s_status_message'])?"value='".htmlspecialchars($_POST['s_status_message'])."'":""?> /></td>
							</tr>
							<tr>
								<th style="width:120px;"><?php echo lang("generic","homepage")?></th>
								<td><input class="form-control" type="text" name="s_homepage" style="width:100%" <?php echo isset($_POST['s_homepage'])?"value='".htmlspecialchars($_POST['s_homepage'])."'":""?> /></td>
							</tr>
							<tr>
								<th style="width:120px;"><?php echo lang("generic","phone")?></th>
								<td><input class="form-control" type="text" name="s_phone" style="width:100%" <?php echo isset($_POST['s_phone'])?"value='".htmlspecialchars($_POST['s_phone'])."'":""?> /></td>
							</tr>
							<tr>
								<th style="width:120px;"><?php echo lang("generic","interest")?></th>
								<td><input class="form-control" type="text" name="s_interest" style="width:100%" <?php echo isset($_POST['s_interest'])?"value='".htmlspecialchars($_POST['s_interest'])."'":""?> /></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<input type="submit" value="<?php echo lang("user","register","ok")?>" style="height:30px;width:60px;" />
		</div>
	</form>
	<?php
}
