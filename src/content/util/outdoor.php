<?php
redirectLoginIfRequired();
$title = "외출 외박 신청서 작성 - " . $title;

function printContent(){
    global $member, $me;
    ?>
    <h1>외출 외박 신청서 작성</h1>
    <div class="table" style="margin-top:20px">
        <form name="newOutdoor">
            <table class="table table-striped table-bordered">
                <tr>
                    <td>
                        <p>인적 정보</p>
                    </td>
                    <td>
                        <div class="btn-group">
                            <input type="number" name="grade" class="form-control">
                            <label>학년</label>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
<?php
}
?>