<?php
$login = array();
$login['jid'] = $me['s_id'].'@kmlaonline.net';
$login['password'] = file_get_contents('/tmp/passwords/'.$me['s_id']);
echo json_encode($login);
