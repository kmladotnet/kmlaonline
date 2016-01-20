<?php
if(file_exists("data/user/main_layout-backup/{$me['n_id']}.txt"))
    copy("data/user/main_layout-backup/{$me['n_id']}.txt", "data/user/main_layout/{$me['n_id']}.txt");
?>
