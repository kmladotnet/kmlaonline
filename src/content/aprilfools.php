<?php
function printContent() {
    global $april_link;
    ?>

  <div class="well">
    <h1>
        <i class="fa fa-exclamation-triangle" style="color:crimson"></i>
        안전하게 kmlaonline.net의 서비스를 이용하기 위해 ActiveX 보안 프로그램을 설치해야 합니다.
        아래의 ActiveX를 모두 설치해 주세요.
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
            <td><strong>표준 보안 모듈(EPKIWCtl)</strong></td>
            <td class="t_left">로그인과 중요정보에 대한 전자서명통신 프로그램입니다.</td>
            <td>
                미설치
                <button class="btn btn-danger">설치하기</button>
            </td>
        </tr>
        <tr>
            <td><strong>공인인증서 보안(INISAFE CrossWeb EX)</strong></td>
            <td class="t_left">공인인증서 로그인과 거래내역에 대한 전자서명을 위한 프로그램입니다.</td>
            <td>
                미설치
                <button class="btn btn-danger">설치하기</button>
            </td>
        </tr>
        <tr>
            <td><strong>개인PC방화벽(AhnLab Safe Transaction)</strong></td>
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
            <td><strong>포럼 보호(KmlaForumPrttr)</strong></td>
            <td class="t_left">익명으로 올려진 여러가지 악성 글을 차단해주는 프로그램입니다.</td>
            <td>
                미설치
                <button class="btn btn-danger">설치하기</button>
            </td>
        </tr>
        <tr>
            <td><strong>웹DRM 보안 모듈(MaWebSAFER_KERIS)</strong></td>
            <td class="t_left">불법적인 웹컨텐츠 접근으로부터 웹페이지를 보호하는 프로그램입니다.</td>
            <td>
                미설치
                <button class="btn btn-danger">설치하기</button>
            </td>
        </tr>
        <tr>
            <td><strong>보안 브라우저(INISAFE SandBox)</strong></td>
            <td class="t_left">악성 프로그램에 의해 웹 페이지가 변조 되는 것을 차단해주는 프로그램입니다.</td>
            <td>
                미설치
                <button class="btn btn-danger">설치하기</button>
            </td>
        </tr>
        <tr>
            <td><strong>큼온 고스톱(data/game/gostop.swf)</strong></td>
            <td class="t_left">닷넷 선배님들이 남기고 가신 유산, 고스톱 게임을 실행할 수 있습니다.</td>
            <td>
                미설치
                <a href="/data/game/gostop.swf" class="btn btn-danger">설치하기</a>
            </td>
        </tr>
        <tr>
            <td><strong>키보드 보안 모듈(Aos키보드 보안)</strong></td>
            <td class="t_left">키보드를 통해 입력되는 정보가 유출되거나 변조되지 않도록 보호해 주는 프로그램입니다.</td>
            <td>
                미설치
                <button class="btn btn-danger">설치하기</button>
            </td>
        </tr>
        <tr>
            <td><strong>날짜 확인(PleaseCheckDate v.4.01)</strong></td>
            <td class="t_left">오늘의 날짜 확인을 유도하는 프로그램입니다.</td>
            <td>
                미설치
                <button class="btn btn-danger">설치하기</button>
            </td>
        </tr>
    </tbody>
</table>

    <a class="btn btn-primary" href="april-fools/KmlaActiveX.exe">모두 설치하기(권장)</a>
      <a class="btn btn-default aprilfools-button" style="position: relative" href="<?php echo $april_link;?>">설치하지 않고 계속 (권장하지 않음)</a>
  </div>
<script>
$(".aprilfools-button").mouseover(function() {$(this).css("left", Math.random() * 1000)});
</script>
<?php
}
?>
