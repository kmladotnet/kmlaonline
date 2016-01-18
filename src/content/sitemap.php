<?php
function printContent(){
	global $board;
	?>
    <div style="padding:10px;">
        <?php if(!isset($_GET['linkSelector'])){ ?>
            <h1><i class="fa fa-sitemap"></i> 사이트맵</h1>
            <?php
		}
		$cat=array(
			""=>array("전체",array()),
			"/^club_.*$/"=>array("동아리",array()),
			"/^department_.*$/"=>array("부서",array()),
			"/^student_.*$/"=>array("교내",array()),
			"/^(site_suggestions|login_candidates|login_approved|site_kmlacafe|site_notice)$/"=>array("큼라온라인", array())
		);
		for($i=intval(date("Y"))-1995;$i>=1;$i--)
			$cat["/^wave{$i}_.*$/"]=array("{$i}기 게시판",array());
		foreach($board->getCategoryList(0,0) as $val){
			if($val['n_id']==1) continue;
			if(checkCategoryAccess($val['n_id'], "list")){
				$f=false;
				foreach($cat as $k=>$v){
					if($k==="") continue;
					if(preg_match($k, $val['s_id'])){
						$f=true;
						$cat[$k][1][]=$val;
					}
				}
				if($f===false)
					$cat[""][1][]=$val;
			}
		}
		$i=0;
		$cat2[]=array();
		foreach($cat as $c){
			$cat2[$i++]=$c;
		}
		$j=0;
		for($i=0;$i<count($cat2);$i++){
			$c=$cat2[$i];
			if(count($c[1])==0) continue;
			if(!isset($_GET['linkSelector']))
				echo "<div style='padding:15px;margin:5px;float:left;display:block;width:160px;line-height:200%'>";
			else
				echo "<div style='padding:3px;margin:2px;float:left;display:block;width:120px;line-height:140%'>";
			if($j==1){
				$i--;
			?>
                <h2 style='padding-bottom:5px;'>기능</h2>
                <a style='font-size:12pt' href="/contacts">연락망</a>
                <br />
                <a style='font-size:12pt' href="/util/lectureroom">공동강의실 신청</a>
                <br />
                <a style='font-size:12pt' href="/util/important">필수 공지</a>
                <br />
                <a style='font-size:12pt' href="/util/schedule">일정</a>
                <br />
                <a style='font-size:12pt' href="/util/kmlaboard">큼라보드</a>
                <br />
                <a style='font-size:12pt' href="/searchall">전체에서 찾기</a>
                <br />
                <a style='font-size:12pt' href="/board/special:list-mine">내 게시판</a>
                <br />
                <a style='font-size:12pt' href="/board/special:list-all">모든 게시판</a>
                <br />
                <a style='font-size:12pt' href="/user/notification">내 알림</a>
                <br />
                <a style='font-size:12pt' href="/user/settings">사용자 설정</a>
                <br />
                <a style='font-size:12pt' href="/user/manage">게시판 등 관리</a>
                <br />
                <?php
			}else{
				echo "<h2 style='padding-bottom:5px;'>".$c[0]."</h2>";
				foreach($c[1] as $b){
					echo "<a style='font-size:12pt;' href='/board/".urlencode($b['s_id'])."'>".htmlspecialchars($b['s_name'])."</a><br />";
				}
			}
			echo "</div>";
		}
		?>
    </div>
    <?php
}
