<?php
redirectLoginIfRequired();
$title = "외출 외박 신청서 작성 - " . $title;

function printContent(){
    global $member, $me;
    ?>
    <h1>외출 외박 신청서 작성</h1>
    <div class="table">
        <form name="newOutdoor">
            <table class="table table-striped table-bordered">
                <tr>
                    <div class="btn-group">
                        <input type="number" name="grade">
                        <label>학년</label>
                    </div>
                </tr>
            </table>
        </form>
    </div>
<?php
}
?>