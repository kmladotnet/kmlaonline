<!DOCTYPE html>
<html>
<head>
    <title>Test Page</title>
</head>
<body>
    <p>This is just a test page.</p>
    <?
        include 'lib.php';

        if($db) echo "WOW";
        else echo "There is a problem";
    ?>
</body>
</html>