<?php

include('../lib.php');
if(isset($_SESSION['user'])) echo json_encode(getAllAccusers());
?>