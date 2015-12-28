<?php
redirectLoginIfRequired();
$errors=array();
$member->removeNoteOfUser($me['n_id']);
redirectTo("/user/message");