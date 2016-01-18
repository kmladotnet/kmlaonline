<?php
function printContent() {
    ?>

  <div class="well">
    <h1>
        <i class="fa fa-exclamation-triangle"></i> kmlaonline.net에 안전하게 접속하기 위해 ActiveX 보안 프로그램이 필요합니다.
    </h1>
  </div>
  <div>
      <table class="table">
    <colgroup>
    <col style="width:27%;">
    <col style="width:auto;">
    <col style="width:13%;">
    </colgroup>
    <thead>
        <tr>
            <th>프로그램명</th>
            <th>기능</th>
            <th style="border-right-style: none;">설치상태</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>통합설치 프로그램(Veraport)</strong></td>
            <td class="t_left">보안프로그램을 한번에 다운받기 위한 프로그램입니다.</td>
            <td>
                미설치
                <button class="btn btn-danger">설치하기</button>
            </td>
        </tr>
        <tr>
            <td><strong>공인인증서 보안
(INISAFE CrossWeb EX)</strong></td>
            <td class="t_left">공인인증서 로그인과 거래내역에 대한 전자서명을 위한 프로그램입니다.</td>
            <td>
                미설치
                <button class="btn btn-danger">설치하기</button>
            </td>
        </tr>
        <tr>
            <td><strong>개인PC방화벽(ASTx)
(AhnLab Safe Transaction)</strong></td>
            <td class="t_left">비인가된 접근을 차단하고 해킹툴 및 바이러스를 검색하고 치료해 주는 프로그램입니다.</td>
            <td>
                미설치
                <button class="btn btn-danger">설치하기</button>
            </td>
        </tr>
        <tr>
            <td><strong>키보드 보안(TouchEnNxKey)</strong></td>
            <td class="t_left">키보드를 통해 입력되는 정보가 유출되거나 변조되지 않도록 보호해 주는 프로그램입니다.</td>
            <td>
                미설치
                <button class="btn btn-danger">설치하기</button>
            </td>
        </tr>
        <tr>
            <td><strong>보안 브라우저
(INISAFE SandBox)</strong></td>
            <td class="t_left">악성 프로그램에 의해 웹 페이지가 변조 되는 것을 차단해주는 프로그램입니다.</td>
            <td>
                미설치
                <button class="btn btn-danger">설치하기</button>
            </td>
        </tr>
        <tr>
            <td><strong>저격글 차단(ANTI Keyboard-Warrior</strong></td>
            <td class="t_left">큼포에 익명으로 저격글을 올리는 것을 차단해주는 프로그램입니다.</td>
            <td>
                미설치
                <button class="btn btn-danger">설치하기</button>
            </td>
        </tr>
        <tr>
            <td><strong>날짜 확인(DateChecker v.4.01)</strong></td>
            <td class="t_left">오늘의 날짜를 확인하는 프로그램입니다.</td>
            <td>
                미설치
                <button class="btn btn-danger">설치하기</button>
            </td>
        </tr>
    </tbody>
</table>

    <button class="btn btn-primary">모두 설치하기(권장)</button>
      <a class="btn btn-warning aprilfools-button" style="color: white; position: relative" href="?action=main">설치하지 않고 계속 (권장하지 않음)</a>
  </div>
<script>
$(".aprilfools-button").mouseover(function() {$(this).css("left", Math.random() * 1000)});
</script>
<?php
}
?>
