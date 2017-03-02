<?php
$title = "기소 성공! - " . $title;
function printContent(){
    ?>
    <div style="padding:5px;">
        <h1>입력하신 내용이 성공적으로 저장되었습니다. </h1>

        <br />
        <br />
        <br />
        <form method="post" action="/intranet/check" id="downform_accuse" onsubmit="return true;">
            <input type="hidden" id="downform_accuse_action" name="action" value="default" />
            <div style="float:left">
                <button class="btn btn-default" style="margin-right:5px;border-radius:5px;" onclick="$('#downform_accuse_action').val('accuse');$('#downform_accuse').submit();"> 또 다시 기소하러 가기
                </button>
            </div>
        </form>
    </div>
    <?php
}