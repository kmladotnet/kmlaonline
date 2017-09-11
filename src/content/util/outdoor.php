<?php
redirectLoginIfRequired();
$title = "외출 외박 신청서 작성 - " . $title;

function printContent(){
    ?>
    <div ng-app="outdoor" ng-controller="outdoorCtrl" ng-init="fetch()">
        <h1>외출 외박 신청서 작성</h1>
        <ng-include src="src/content/template/outdoor.html"></ng-include>
        <form>
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td style="width: 10%; vertical-align: middle; text-align:center;">인적 사항
                        </td>
                        <td style="width: 8%; vertical-align: middle; text-align:right">
                            학년
                        </td>
                        <td style="width: 10%; vertical-align: middle; text-align:center">
                            <input class="form-control" style="text-align:center" ng-model="info.grade">
                        </td>
                        <td style="width: 8%; vertical-align: middle; text-align:right">
                            반
                        </td>
                        <td style="width: 10%; vertical-align: middle; text-align:center">
                            <input class="form-control" style="text-align:center" ng-model="info.class">
                        </td>
                        <td style="width: 8%; vertical-align: middle; text-align:right">
                            성명
                        </td>
                        <td style="width: 20%; vertical-align: middle; text-align:center">
                            <input class="form-control" style="text-align:center" ng-model="info.name">
                        </td>
                        <td style="width: 8%; vertical-align: middle; text-align:right">
                            호실
                        </td>
                        <td style="width: 18%; vertical-align: middle; text-align:center">
                            <input class="form-control" style="text-align:center" ng-model="info.room">
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle; text-align:center;">기간
                        </td>
                        <td colspan="3" style="vertical-align: middle; text-align:center">
                            <input class="form-control" type="datetime" style="text-align:center">
                        </td>
                        <td style="vertical-align: middle; text-align:left">
                            부터
                        </td>
                        <td colspan="2" style="vertical-align: middle; text-align:center">
                            <input class="form-control" type="datetime" style="text-align:center">
                        </td>
                        <td colspan="2" style="vertical-align: middle; text-align:left">
                            까지
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle; text-align:center;">사유
                        </td>
                        <td colspan="8">
                            <textarea class="form-control" rows="3" type="text"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle; text-align:center;">행선지
                        </td>
                        <td colspan="4">
                            <input class="form-control" type="text"></input>
                        </td>
                        <td style="vertical-align: middle; text-align:center;">교통편
                        </td>
                        <td colspan="3">
                            <input class="form-control" type="text"></input>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle; text-align:center;">핸드폰<br/>번호</td>
                        <td colspan="4">
                            <input class="form-control" type="text" ng-model="info.phone_number"></input>
                        </td>
                        <td style="vertical-align: middle; text-align:center;">부모님<br/>번호
                        </td>
                        <td colspan="3">
                            <input class="form-control" type="text"></input>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle; text-align:center;">종류</td>
                        <td colspan="8">
                            <label class="radio-inline"><input type="radio" name="optradio">질병 귀가</label>
                            <label class="radio-inline"><input type="radio" name="optradio">대회/시험</label>
                            <label class="radio-inline"><input type="radio" name="optradio">동아리 활동</label>
                            <label class="radio-inline"><input type="radio" name="optradio">해당 사항 없음</label>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle; text-align:center;">빠지는<br>수업이</td>
                        <td colspan="8">
                            <label class="radio-inline"><input type="radio" name="op">있다</label>
                            <label class="radio-inline"><input type="radio" name="op">없다</label>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="text-align: center;">
                <button class="btn btn-info">제출</button>
            </div>
            <h2>{{"STATUS : " + status}}</h2>
        </form>
    </div>
<?php
echo $_SERVER["REQUEST_URI"];
}
?>