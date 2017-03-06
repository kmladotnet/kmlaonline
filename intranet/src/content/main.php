<?php
$title="Main - " . $title;
function printContent(){
    global $student, $me, $is_morning, $is_afternoon, $is_night, $db, $board, $user;
    ?>
    <div>
        <form method="post" action="./check" id="downform_accuse" onsubmit="return true;">
            <input type="hidden" id="downform_accuse_action" name="action" value="default" />
            <div style="float:left">
                <button class="btn btn-default" style="margin-right:5px;border-radius:5px;" onclick="$('#downform_accuse_action').val('accuse_test');$('#downform_accuse').submit();">기소하기
                </button>
            </div>
        </form>
    </div>
<?php
}