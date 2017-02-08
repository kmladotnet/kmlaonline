<!DOCTYPE html>
<html>
<head>
    <title>Test Page</title>
</head>
<body>
    <p>This is just a test page.</p>
    <?php
        include 'lib_real.php';

        if($db) echo "WOW";
        else echo "There is a problem";
    ?>
</body>
</html>