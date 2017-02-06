<?php
class Soreecaptcha{
	private $preset="ACEFGHKMNPRTWXYZ0123456789";
	private $sess_capt_name, $sess_capt_seed, $sess_capt_expire;
	private $font_dir;
	private $length;
	private function getRandomFile($dir=""){
		if($dir=="") $dir=$this->font_dir;
		$files = glob($dir . '/*.*');
		$file = array_rand($files);
		return $files[$file];
	}
	private function putRandomThings($im, $w, $h, $c=10,$m=false){
		for($i=0;$i<$c;$i++){
			switch($m===false?intval(rand(0,1)):$m){
				case 0: imageline($im, rand(0,$w), rand(0,$h), rand(0,$w), rand(0,$h), imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255))); break;
				case 1: $szx=rand(5,$w); $szy=rand(5,$h); imagefilledarc ( $im , rand(0,$w) , rand(0,$h) ,$szx,$szy ,rand(0,360) , rand(0,360) , imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255)) , IMG_ARC_PIE | IMG_ARC_CHORD ); break;
			}
		}
	}
	public function putImage(){
		//echo(nl2br(htmlspecialchars(print_r($this,true)))); die(nl2br(htmlspecialchars(print_r($_SESSION,true))));
		srand($_SESSION[$this->sess_capt_seed]);
		$string=$_SESSION[$this->sess_capt_name];
		$width=20+50*strlen($string);
		$height=30+30;
		header('Content-Type: image/png');
		header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		header("Progma: no-cache");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
		$im = imagecreatetruecolor($width, $height);
		$black = imagecolorallocate($im, 0, 0, 0);
		imagefilledrectangle($im, 0, 0, $width-1, $height-1, imagecolorallocate($im,rand(180,255),rand(180,255),rand(180,255)));
		$this->putRandomThings($im,$width,$height,10);
		$x=10;
		imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);
		imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);
		for($i=0;$i<strlen($string);$i++){
			$txt=substr($string,$i,1);
			$angle=rand(-30,30);
			$size=rand(12,18);
			$y=30+rand(12,18);
			imagettftext($im, $size, $angle, $x, $y, imagecolorallocate($im,rand(0,80),rand(0,80),rand(0,80)), $this->getRandomFile(), $txt);
			$x+=$size+rand(12,24);
		}
		$this->putRandomThings($im,$width,$height,1,0);
		imagestring($im,5,0,0,"no o,s,i",$black);
		$im2 = imagecreatetruecolor($x+10, $height);
		imagecopy ( $im2 , $im , 0 , 0 , 0 , 0 , $x+10 , $height );
		imagepng($im2);
		imagedestroy($im);
		imagedestroy($im2);
		srand();
	}
	public function genCaptchaString($length=5,$preset=false){
		$s="";
		if($preset==false) $preset=$this->preset;
		for($i=0;$i<$length;$i++){
			$s.=substr($preset,rand(0,strlen($this->preset)-1),1);
		}
		return $s;
	}
	public function checkCaptcha($str){
		return $str==$_SESSION[$this->sess_capt_name] && time()<$_SESSION[$this->sess_capt_expire];
	}
	public function renewCaptcha(){
		session_start();
		$_SESSION[$this->sess_capt_name]=$this->genCaptchaString($this->length);
		$_SESSION[$this->sess_capt_seed]=rand();
		$_SESSION[$this->sess_capt_expire]=time()+20*60; //20 minutes
		session_write_close();
	}
	function __construct($font_dir="", $force_renew=false, $length=5, $sess_capt_name="soree_engine_Soreecaptcha", $sess_capt_expire="soree_engine_Soreecaptcha_expire", $sess_capt_seed="soree_engine_Soreecaptcha_seed"){
		$this->font_dir=$font_dir;
		$this->length=$length;
		$this->sess_capt_name=$sess_capt_name; $this->sess_capt_expire=$sess_capt_expire; $this->sess_capt_seed=$sess_capt_seed;
		$renew=$force_renew;
		$renew=$renew || !isset($_SESSION[$sess_capt_name]);
		$renew=$renew || (isset($_SESSION[$sess_capt_name]) && (!isset($_SESSION[$sess_capt_expire]) || time()>$_SESSION[$sess_capt_expire]));
		if($renew){
			//echo "R";
			$this->renewCaptcha();
		}else{
			session_start();
			$_SESSION[$sess_capt_expire]=time()+20*60; //20 minutes
			session_write_close();
		}
	}
}