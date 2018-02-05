<?php
require_once('test-config.php');

if(isset($_GET['user']) && $_GET['user'] == 'paco')
{
    $mysqli -> query("DROP TABLE kmlaonline_donation_new");
    $mysqli -> query("CREATE TABLE IF NOT EXISTS kmlaonline_donation_new (".
                  "  n_num int(11),".
                  "  n_category int(11),".
                  "  s_title text,".
                  "  n_who bigint(20) NOT NULL,".
                  "  s_status text,".
                  "  s_type text,".
                  "  s_owner text".
                  ") ENGINE=InnoDB");

    $myfile = fopen('../../../scripts/donation/donation_output.txt', 'r');
    while(!feof($myfile)) {
        $mysqli -> query(fgets($myfile));
        // echo fgets($myfile) . "<br />";s
    }
    fclose($myfile);
}
?>
