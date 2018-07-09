<?php
if(isset($_SESSION['user'])) redirectTo((isset($_REQUEST['returnto']) && $_REQUEST['returnto'] != "") ? $_REQUEST['returnto'] : "/");
$title = "로그인 - " . $title;

function printFood($jsonData, $month, $day, $whichMeal) {
    if ($jsonData != NULL && array_key_exists($month, $jsonData) && array_key_exists($day, $jsonData)) {
        foreach($jsonData[$month][$day][$whichMeal] as $key => $value) {
            echo "$value <br />";
        }
    } else {
        echo "<span style='color:#a2a2a2'>(입력되지 않음) <br> kmlaonline 관리자에게 연락해주세요!</span>";
    }
}

function printContent(){
	global $board, $is_morning, $is_afternoon, $is_night, $mysqli;

	$att = array();
	$cat = $board->getCategory(false,"login_approved");
	foreach($board->getArticleList(array($cat['n_id'])) as $ar){
		foreach($board->getAttachments(false, $ar['n_id']) as $a){
			$att[] = $a;
		}
	}
    $weather = getWeather();
	if(count($att) > 0){
		$v = $att[array_rand($att)];
		$i = getimagesize($v['s_path']);
		$width = $i[0];
		$height = $i[1];
		insertOnLoadScript("initializeLoginBackgroundImage(\"".addslashes("/files/bbs/{$cat['n_id']}/{$v['n_parent']}/{$v['n_id']}/{$v['s_key']}/{$v['s_name']}")."\", $width, $height);".( (200 <= $weather->weather->id && $weather->weather->id < 600) ? '$("#no-login-bg").load(function() {var rain = new RainyDay({image: document.getElementById("no-login-bg")});rain.rain([[0, 2, 20], [4, 3, 1]], 30);$("canvas").css("z-index", 999999999);});' : ""));
	}

	?>
    <style>.ui-pnotify{z-index:99999999999!important}</style>
    <div style="position: fixed;top: 0;left: 0;width: 100%;height: 100%;background: white;z-index: 99999;"></div>
    <form method="post" action="./check" id="downform_login" onsubmit="return true;">
        <input type="hidden" id="downform_login_action" name="action" value="login" />
        <input type="hidden" id="return_loc" name="returnto" value="<?php echo ((isset($_REQUEST['returnto']) && $_REQUEST['returnto']!=" ")?$_REQUEST['returnto']:"/ ")?>" />
        <div style="text-align:center;width:100%">
            <a href="/">
                <h1 id="login_title" style="color:white">
					<img id="menu-logo-image-2" src="/images/kmlaonline-w.png" alt="KmlaOnline" style="height: 30px;margin: 10px;margin-top: 0;">
                </h1>
            </a>
            <?php if(isset($_GET['p1']) && $_GET['p1']=='bad'){
                insertOnLoadScript("setTimeout(function() {var badlogin = new PNotify({title: 'ID 또는 패스워드가 잘못되었습니다.',body: '실수로 ID 대신 이메일을 입력했는지 확인해주세요.',type: 'error',buttons: {closer: false,sticker: false}});badlogin.get().click(function () {badlogin.remove();});}, 500);");
			}else if(isset($_GET['p1']) && $_GET['p1']=='required'){
                insertOnLoadScript("setTimeout(function() {var requirelogin = new PNotify({title: '로그인해야 볼 수 있습니다.',type: 'error',buttons: {closer: false,sticker: false}});requirelogin.get().click(function () {requirelogin.remove();});}, 500);");
            } ?>
			<div style="width:222px;margin:0 auto;display:block;">
				<div class="form-group">
                    <input placeholder="ID로 로그인해주세요" type="text" name="id" class="login_input form-control" autofocus onkeydown="if (event.which || event.keyCode){if ((event.which == 13) || (event.keyCode == 13)) {document.getElementById('cmdLoginPage').click();}};">
                </div>
                <div class="form-group">
                    <input placeholder="비밀번호" type="password" name="pwd" class="login_input form-control" onkeydown="if (event.which || event.keyCode){if ((event.which == 13) || (event.keyCode == 13)) {document.getElementById('cmdLoginPage').click();}};">
                </div>
				<div style="float:right"><button class="btn btn-primary" onclick="$('#downform_login_action').val('login');$('#downform_login').submit();" id="cmdLoginPage">로그인</button></div>
				<!-- 회원가입 받을 시기에 comment out(uncomment). -->
				<!-- <div style="float:right"><button class="btn btn-default" style="margin-right:5px;border-radius:5px;" onclick="$('#downform_login_action').val('register');$('#downform_login').submit();">회원가입</button></div> -->
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
                    <div style="float:right;color:white;height:32px;vertical-align:middle;line-height:32px;margin-right:10px;">
                    <label for="chk_teacher" style="vertical-align:middle;">
                        <input type="checkbox" name="teacher" id="chk_teacher_me" style="vertical-align:middle;" onchange="
                            if(this.checked) {
                                document.getElementById('return_loc').value = '/teacher/main';
                            } else {
                                document.getElementById('return_loc').value = '/';
                            }" disabled> 교직원
                    </label>
                    </div>
                    <div style="clear:both;"></div>
			</div>
            <div style="text-align:center; background: rgba(255, 255, 255, 0.9); border-radius: 5px; padding: 5px; margin: 5px;">
                <?php
                $jsonData = NULL;
                echo file_exists("/srv/scripts/data.json");
                if (file_exists("/srv/scripts/data.json")) {
                    $jsonData = json_decode(file_get_contents("/srv/scripts/data.json"), true);
                }
                $curYear = date("Y"); // 2003, 1999..
                $curMonth = date("n"); // 1 ~ 12
                $curDay = date("j"); // 1 ~ 31
                $curWeekDay = strftime("%a"); // Sat ~ Sun

                if ($is_morning && date("H")>=22) {
                    $curYear = date("Y", strtotime("+1 day"));
                    $curMonth = date("n", strtotime("+1 day"));
                    $curDay = date("j", strtotime("+1 day"));
                    $curWeekDay = strftime("%a", strtotime("+1 day"));
                }
                $query="SELECT s_mode, s_data FROM kmlaonline_schedule_table WHERE n_year=$curYear AND n_month=$curMonth AND n_day=$curDay";
                if ($res=$mysqli->query($query)) {
                    while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
                        $scheduleData[$row['s_mode']]=$row['s_data'];
                    }
                    $res->close();
                    if ($mysqli->more_results()) $mysqli->next_result();
                }
                echo "<div style='font-weight:bold;font-size:12pt;padding:5px;'>{$curYear}년 {$curMonth}월 {$curDay}일 ({$curWeekDay})</div>";
                ?>

                <div style="font-size:10pt;height:15pt;margin:5px;">
                        <a <?php if($is_morning) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-breakfast');">아침</a> |
                        <a <?php if($is_afternoon) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-lunch');">점심</a> |
                        <a <?php if($is_night) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-dinner');">저녁</a>
                </div>
                <div id="food-breakfast" class="morning">
                    <?php
                        $voteData = getFoodVoteData($curYear, $curMonth, $curDay, 1);
						$voteCount = getFoodVoteCount($curYear, $curMonth, $curDay, 1);
                    ?>
                    <div class="food-votes">
                        <?php if($voteData['count'] > 0) { ?>
                            <span style="font-size: 1.2em"><?php echo(round($voteData['sum'] / $voteData['count'], 1));?>점</span>
                            (<?php echo $voteData['count']; ?>명)
                        <?php } else { ?>
                            평점 없음
                        <?php } ?>
                    </div>
                    로그인해서 평점을 매기세요.
                    <hr style="margin-top: 5px;margin-bottom: 5px;">
					<?php if($voteData['count'] > 0) { ?>
						<ul class="food-chart" style="display:block">
							<?php for($i = 5; $i >= 0; $i--) {
								echo '<li class="food-chart-item food-'.$i.'" style="width: '.intval(100 * $voteCount[$i] / $voteData['count']).'%"></li>';
							} ?>
						</ul>
					<?php }
                    printFood($jsonData, $curMonth, $curDay, "breakfast");
                    // echo isset($scheduleData['food:0']) ? nl2br($scheduleData['food:0']) : "<span style='color:#DDD'>(입력되지 않음)</span>";
                    ?>
                </div>
                <div id="food-lunch" class="afternoon">
                    <?php
                        $voteData = getFoodVoteData($curYear, $curMonth, $curDay, 2);
						$voteCount = getFoodVoteCount($curYear, $curMonth, $curDay, 2);
                    ?>
                    <div class="food-votes">
                        <?php if($voteData['count'] > 0) { ?>
                            <span style="font-size: 1.2em"><?php echo(round($voteData['sum'] / $voteData['count'], 1));?>점</span>
                            (<?php echo $voteData['count']; ?>명)
                        <?php } else { ?>
                            평점 없음
                        <?php } ?>
                    </div>
                    로그인해서 평점을 매기세요.
                    <hr style="margin-top: 5px;margin-bottom: 5px;">
					<?php if($voteData['count'] > 0) { ?>
						<ul class="food-chart" style="display:block">
							<?php for($i = 5; $i >= 0; $i--) {
								echo '<li class="food-chart-item food-'.$i.'" style="width: '.intval(100 * $voteCount[$i] / $voteData['count']).'%"></li>';
							} ?>
						</ul>
                    <?php }
                    printFood($jsonData, $curMonth, $curDay, "lunch");
                    // echo isset($scheduleData['food:1'])?nl2br($scheduleData['food:1']):"<span style='color:#DDD'>(입력되지 않음)</span>"; 
                    ?>
                </div>
                <div id="food-dinner" class="night">
                    <?php
                        $voteData = getFoodVoteData($curYear, $curMonth, $curDay, 3);
						$voteCount = getFoodVoteCount($curYear, $curMonth, $curDay, 3);
                    ?>
                    <div class="food-votes">
                        <?php if($voteData['count'] > 0) { ?>
                            <span style="font-size: 1.2em"><?php echo(round($voteData['sum'] / $voteData['count'], 1));?>점</span>
                            (<?php echo $voteData['count']; ?>명)
                        <?php } else { ?>
                            평점 없음
                        <?php } ?>
                    </div>
                    로그인해서 평점을 매기세요.
                    <hr style="margin-top: 5px;margin-bottom: 5px;">
					<?php if($voteData['count'] > 0) { ?>
						<ul class="food-chart" style="display:block">
							<?php for($i = 5; $i >= 0; $i--) {
								echo '<li class="food-chart-item food-'.$i.'" style="width: '.intval(100 * $voteCount[$i] / $voteData['count']).'%"></li>';
							} ?>
						</ul>
                    <?php }
                    printFood($jsonData, $curMonth, $curDay, "dinner");
                    // echo isset($scheduleData['food:2'])?nl2br($scheduleData['food:2']):"<span style='color:#DDD'>(입력되지 않음)</span>"; 
                    ?>
                </div>
                <br>
            </div>
		</div>
	</form>

    <script src="/js/content/user/login.js"></script>
    <?php
    if(!is_null($weather) && (200 <= $weather->weather->id) && ($weather->weather->id < 600)) {
        ?>
        <script src="//cdnjs.cloudflare.com/ajax/libs/rainyday.js/0.1.2/rainyday.min.js"></script>
        <?php
    }
}
