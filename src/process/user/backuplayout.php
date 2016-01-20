<?php
mkdir("data/user/main_layout_backup");
copy("data/user/main_layout/{$me['n_id']}.txt", "data/user/main_layout_backup/{$me['n_id']}.txt");
?>
