<?php
unvote($_POST['id'], $me['n_id'], isset($_POST['down']) && $_POST['down']);
