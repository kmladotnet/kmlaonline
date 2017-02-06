<?php
$captcha=new Soreecaptcha("data/fonts", isset($_GET['renew']));
$captcha->putImage();