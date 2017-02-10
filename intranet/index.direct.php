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

    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>
    <script src="../src/jquery_ui/jquery-ui.js"></script>
    <link rel="stylesheet" href="../src/jquery_ui/jquery-ui.css"></link>
    <script type="text/javascript">
    $(document).ready(function($){
        $('#name').autocomplete({
            source:"../src/content/user/suggest_name.php",
            minLength:1,
            select: function(event, ui){
                var code = ui.item.id;
                location.href = '../src/content/user/suggest_name.php?id=' + code;
            },
            html: true,
            open: function(event, ui) {
                $(".ui-autocomplete").css("z-index", 5);
            }
        });
    });
    </script>


    <title>
        <?php echo $title ?>
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