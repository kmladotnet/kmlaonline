<?php
mkdir('data/user/main-layout-backup');
copy("data/user/main_layout/{$me['n_id']}.txt", "data/user/main_layout-backup/{$me['n_id']}.txt");
?>
