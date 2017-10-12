<?php
if(isset($_SESSION['user'])){
    echo phpinfo();
} else {
    http_response_code(404);
}
?>