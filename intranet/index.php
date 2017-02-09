<!DOCTYPE html>
<html>
<head>
    <title>Test Page - Let's accuse somebody!</title>
</head>
<body>
    <p>This is just a test page.</p>
    <?php
        include 'lib_real.php';

        if($db) echo "WOW";
        else echo "There is a problem";
    ?>
    <a href="https://kmlaonline.net/intranet/user/accuse">기소하기(테스트)</a>
</body>
</html>