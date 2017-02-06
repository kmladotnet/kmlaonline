<?php
$title = lang("user", "test_reset_password", "title") . " - " . $title;
function printContent(){ ?>

    <script src="//cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.9.0/validator.min.js"></script>
    <!--script type="text/javascript">alert("테스트 중입니다.");location.href="/";</script-->
    <?php
    global $max_level;
    insertOnLoadScript("putAlertOnLeave();");
    ?>
    <?php
}