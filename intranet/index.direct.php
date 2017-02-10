<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta charset="utf-8" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <title>
        <?php echo formatTitle($title); ?>
    </title>

    <?php
    if(function_exists("printHead")) printHead();
    ?>

</head>
<body>
    <div>
        <div id="below-header-menu">
            <?php include "src/header/below-header.php"; ?>
        </div>
    </div>
</body>
</html>