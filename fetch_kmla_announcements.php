<?php 
header("Content-Type: text/html; charset=utf-8");
$f=file_get_contents("http://www.minjok.hs.kr/app/kumla_notice/kumla_all.html");
if(preg_match_all('/<li>\[([^\]]+):([^\]]+)\](.*?)<br>/sim', $f, $m, PREG_SET_ORDER)){
	?><!doctype html><html><head>
	<meta charset="utf-8" /><meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
	<script type="text/javascript" src="/js/jquery.ticker.js" charset="utf-8"></script>
	<link rel="stylesheet" href="/theme/dev/theme.css" />
	<style type="text/css">
		*{overflow:hidden;}
		#ticker{
			width:100%;
			height:76px;
			box-sizing:border-box;
		}
		#ticker ul, #ticker ul li{
			width:100%;
			box-sizing:border-box;
		}
		li{
			font-size:10pt;
			height:<?php echo ((isset($_GET['lines']) && is_numeric($_GET['lines']))?$_GET['lines']:1)*10+7 ?>pt;
		}
	</style>
</head><body>
	<div id="ticker">
		<ul id="js-news" class="js-hidden">
			<?php
			foreach($m as $each){
				$type=trim($each[1]);
				$date=trim($each[2]);
				$msg=trim(strip_tags($each[3]));
				?><li class="news-item"><span style="font-size:8pt;color:orange;font-weight:bold;">&gt;</span> <a href="http://www.minjok.hs.kr/members/" target="_new" style="color:black"><?php echo "<b>[$date] $type</b>: $msg"?></a></li><?php
			}
			?>
			<li></li>
		</ul>
	</div>
	<script type="text/javascript">$(function () {$('#ticker').Vnewsticker({speed:500, pause:3000, mousePause:true, showItems:3, direction:"up"});});</script>
</body></html><?php
}else{
	$f=str_replace("<head>", "<head><meta charset=\"utf-8\" /><base href=\"http://www.minjok.hs.kr/app/kumla_notice/\" /><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />", $f);
	echo $f;
}
