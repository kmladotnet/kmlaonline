<?php
    require_once('config.php');
    echo "<b> " . DB_USERNAME . " </b>";
    $db = initializePresenTools('DB_HOST', 'DB_USERNAME', 'DB_PASSWORD', 'DB_NAME');
?>