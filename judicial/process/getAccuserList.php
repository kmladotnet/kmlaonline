<?php

include('../lib.php');
session_start();
if(isset($_SESSION['user'])) echo json_encode(getAllAccusers());
?>