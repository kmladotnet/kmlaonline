<?php
include './XmppPrebind.php';

$username = "present42";
$password = "guswo1";

$xmppPrebind = new XmppPrebind('kmlaonline.net', 'http://kmlaonline.net:5280/admin/', 'test_resource' , false, true);
$xmppPrebind->connect($username, $password);
$xmppPrebind->auth();
$sessionInfo = $xmppPrebind->getSessionInfo();
?>