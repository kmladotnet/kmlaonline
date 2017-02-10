<?php
$title = "기소";

function printContent(){ ?>
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js"</script>
    <script src="intranet/src/jquery_ui/jquery-ui.js"></script>
    <p>It works!!</p>
    <form action="ajax/user/accuse">
        <input type="text" placeholder="이름" id="name" class="ui-autocomplete-input" autocomplete="off" />
        <script type="text/javascript">
        $(document).ready(function($){
            $('#name').autocomplete({
                source:'suggest_name.php',
                minLength:1,
                select: function(event, ui){
                    var code = ui.item.id;
                    location.href = '/suggest_name.php?term=' + code;
                },
                html: true,
                open: function(event, ui) {
                    $(".ui-autocomplete").css("z-index", 1000);
                }
            });
        });
    </script>
    </form>

<?php
}