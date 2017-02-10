<?php
$title = "기소";

function printContent(){ ?>
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js"</script>
    <script src="intranet/src/jquery_ui/jquery-ui.js"></script>
    <p>It works!!</p>
    <form action="ajax/user/accuse">
        <input type="text" placeholder="이름" id="name" class="ui-autocomplete-input" autocomplete="off" />
    </form>

<?php
}