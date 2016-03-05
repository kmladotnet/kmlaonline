<?php
if(isset($_SESSION['user'])) redirectTo((isset($_REQUEST['returnto']) && $_REQUEST['returnto']!="")?$_REQUEST['returnto']:"/");
$title="로그인 - " . $title;
function printContent(){
	global $board, $is_morning, $is_afternoon, $is_night, $mysqli;

	$att=array();
	$cat=$board->getCategory(false,"login_approved");
	foreach($board->getArticleList(array($cat['n_id'])) as $ar){
		foreach($board->getAttachments(false, $ar['n_id']) as $a){
			$att[]=$a;
		}
	}
    $weather = getWeather();
	if(count($att)>0){
		$v=$att[array_rand($att)];
		$i=getimagesize($v['s_path']);
		$width=$i[0];
		$height=$i[1];
		insertOnLoadScript("initializeLoginBackgroundImage(\"".addslashes("/files/bbs/{$cat['n_id']}/{$v['n_parent']}/{$v['n_id']}/{$v['s_key']}/{$v['s_name']}")."\", $width, $height);".( (200 <= $weather->weather->id && $weather->weather->id < 600) ? '$("#no-login-bg").load(function() {var rain = new RainyDay({image: document.getElementById("no-login-bg")});rain.rain([[0, 2, 20], [4, 3, 1]], 30);$("canvas").css("z-index", 999999999);});' : ""));
	}

	?>
    <div style="position: fixed;top: 0;left: 0;width: 100%;height: 100%;background: white;z-index: 99999;"></div>
    <form method="post" action="./check" id="downform_login" onsubmit="return true;">
        <input type="hidden" id="downform_login_action" name="action" value="login" />
        <input type="hidden" name="returnto" value="<?php echo ((isset($_REQUEST['returnto']) && $_REQUEST['returnto']!=" ")?$_REQUEST['returnto']:"/ ")?>" />
        <div style="text-align:center;width:100%">
            <a href="http://kmlaonline.net"><h1 id="login_title" style="color:white">KMLAONLINE</h1></a>
            <?php if(isset($_GET['p1']) && $_GET['p1']=='bad'){ ?>
                <div style="color:red;font-weight:bold;size:15pt;text-align:center;">ID 또는 패스워드가 잘못되었습니다.
                    <br /><b>E-Mail이 아니라 ID로 로그인하세요.</div>
				<br />
			<?php }else if(isset($_GET['p1']) && $_GET['p1']=='required'){ ?>
				<div style="color:red;font-weight:bold;size:15pt;text-align:center;">로그인해야 볼 수 있는 페이지입니다.</div>
			<?php } ?>
			<div style="width:222px;margin:0 auto;display:block;">
				<div class="form-group">
                    <input placeholder="ID로 로그인해주세요" type="text" name="id" class="login_input form-control" autofocus onkeydown="if (event.which || event.keyCode){if ((event.which == 13) || (event.keyCode == 13)) {document.getElementById('cmdLoginPage').click();}};">
                </div>
                <div class="form-group">
                    <input placeholder="비밀번호" type="password" name="pwd" class="login_input form-control" onkeydown="if (event.which || event.keyCode){if ((event.which == 13) || (event.keyCode == 13)) {document.getElementById('cmdLoginPage').click();}};">
                </div>
				<div style="float:right"><button class="btn btn-primary" onclick="$('#downform_login_action').val('login');$('#downform_login').submit();" id="cmdLoginPage">로그인</button></div>
				<div style="float:right"><button class="btn btn-default" style="margin-right:5px;border-radius:5px;" onclick="$('#downform_login_action').val('register');$('#downform_login').submit();">회원가입</button></div>
				<div style="float:right;color:white;height:32px;vertical-align:middle;line-height:32px;margin-right:10px;"><label for="chk_remember_me" style="vertical-align:middle;"><input type="checkbox" name="remember_me" id="chk_remember_me" style="vertical-align:middle;" onchange="if(this.checked) {
                        var check = this;
                        (new PNotify({
                            title: '자동 로그인',
                            text: '브라우저를 껐다가 켜도 로그인되어있게 하는 기능으로, 개인용 장치에서만 사용해야 하며 공공 장소에서는 이용하면 안 됩니다. 계속하시겠습니까?',
                            icon: 'fa fa-question-circle',
                            hide: false,
                            confirm: {
                                confirm: true
                            },
                            buttons: {
                                closer: false,
                                sticker: false
                            },
                            history: {
                                history: false
                            }
                        })).get().on('pnotify.confirm', function() {
                        }).on('pnotify.cancel', function() {
                            check.checked = '';
                        });
                    }" /> 기억하기</label></div>
				<div style="clear:both;"></div>
			</div>
			<div class="main-block panel panel-default" style="margin-top:20px;height:auto;position:relative;">
				<div class="main-block-title panel-heading" style="text-align:left">
					<div style="display:block;background:url('/images/food.png') no-repeat 0px 6px; background-size: 32px;padding-left:36px;">
						식단
					</div>
				</div>
                <div class="panel-body">
                    <div style="text-align:center">
                        <?php
                        $curYear=date("Y"); $curMonth=date("n"); $curDay=date("j");
                        if ($is_morning && date("H")>=22) {
                            $curYear = date("Y", strtotime("+1 day"));
                            $curMonth = date("m", strtotime("+1 day"));
                            $curDay = date("d", strtotime("+1 day"));
                        }
                        $query="SELECT s_mode, s_data FROM kmlaonline_schedule_table WHERE n_year=$curYear AND n_month=$curMonth AND n_day=$curDay";
                        if ($res=$mysqli->query($query)) {
                            while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
                                $scheduleData[$row['s_mode']]=$row['s_data'];
                            }
                            $res->close();
                            if ($mysqli->more_results()) $mysqli->next_result();
                        }
                        echo "<div style='font-weight:bold;font-size:11pt;padding:4px;'>{$curYear}년 {$curMonth}월 {$curDay}일</div>";
                        ?>

                        <div style="font-size:9pt;height:15pt;padding-top:3pt;">
                                <a <?php if($is_morning) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-breakfast');">아침</a> |
                                <a <?php if($is_afternoon) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-lunch');">점심</a> |
                                <a <?php if($is_night) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-dinner');">저녁</a>
                        </div>
                        <div id="food-breakfast" class="morning"><?php echo isset($scheduleData['food:0'])?nl2br($scheduleData['food:0']):"<span style='color:#DDD'>(입력되지 않음)</span>"; ?></div>
                        <div id="food-lunch" class="afternoon"><?php echo isset($scheduleData['food:1'])?nl2br($scheduleData['food:1']):"<span style='color:#DDD'>(입력되지 않음)</span>"; ?></div>
                        <div id="food-dinner" class="night"><?php echo isset($scheduleData['food:2'])?nl2br($scheduleData['food:2']):"<span style='color:#DDD'>(입력되지 않음)</span>"; ?></div>
                        <br>
                    </div>
                </div>
			</div>
		</div>
	</form>
    <script src="/js/content/user/login.js"></script>
    <?php
    if(200 <= $weather->weather->id && $weather->weather->id < 600) {
        ?>
        <script src="//cdnjs.cloudflare.com/ajax/libs/rainyday.js/0.1.2/rainyday.min.js"></script>
        <?php
    }
}
