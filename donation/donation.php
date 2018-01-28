<?php
$mysqli = new mysqli('localhost', 'kmlaonline', 'n7h4eYWJ7vW59tT8', 'kmlaonline');

$mysqli -> query("CREATE TABLE IF NOT EXISTS donation_test (".
              "  n_num int(11),".
              "  n_category int(11),".
              "  s_title text,".
              "  n_who bigint(20) NOT NULL,".
              "  s_status text,".
              "  s_type text,".
              "  s_owner text".
              ") ENGINE=InnoDB");

$myfile = fopen('donation_output.txt', 'r');
while(!feof($myfile)) {
    $mysqli -> query(fgets($myfile));
    // echo fgets($myfile) . "<br />";s
}
fclose($myfile);
?>
