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
    <link rel="stylesheet" href="../src/jquery_ui/jquery-ui.structure.css"></link>
    <link rel="stylesheet" href="../src/jquery_ui/jquery-ui.theme.css"></link>
    <link rel="stylesheet" href="../src/jquery_ui/jquery-ui.css"></link>
    <script type="text/javascript">
    $(document).ready(function($){
        $('.auto_name').autocomplete({
            source:"../src/content/user/suggest_name.php",
            minLength:1,
            html: true,
            open: function(event, ui) {
                $(".ui-autocomplete").css("z-index", 5);
            }
        });
    });
    </script>

    <script type="text/javascript">
    $(document).ready(function($){
        $('.auto_article').autocomplete({
            source:"../src/content/user/suggest_article_kind.php",
            minLength:2,
            html: true,
            open: function(event, ui) {
                $(".ui-autocomplete").css("z-index", 5);
            }
        });
    });
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on("focus", '#article_table tr:last-child td:last-child', function() {
                var table = $("#article_table");
                table.append('<tr><p>WOW</p></tr>');
            });
        });
    </script>

    <!--link rel="stylesheet" href="../css/font.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../sass-compiled/screen.css?v=6" type="text/css" media="screen" /-->



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