<?php
$title = "최초 채팅 앱 - $title";
function printContent() {
    global $me;
    $date1 = new DateTime("now");
    $date2 = new DateTime("2016-03-04");
    if($date1 >= $date2) {
        @redirectAlert("/", "채팅 앱 접속시간이 아닙니다.");
    }
?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.3/handlebars.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>

    <link rel="stylesheet" href="css/content/util/chat.css">
    <div id="wrapper">
        <div id="user-container">
            <label for="user">나의 이름은..</label>
            <input type="text" id="user" name="user" value="<?php putUserCard($me); ?>" disabled>
            <button class="btn btn-info" type="button" id="join-chat">Join Chat</button>
        </div>

        <div id="main-container" class="hidden">
            <button class="btn btn-warning" type="button" id="leave-room">나가기</button>
            <div id="messages">

            </div>

            <div id="msg-container">
                <div class="input-group">
                    <input class="form-control" type="text" id="msg" name="msg" placeholder="메시지를 입력하세요!">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" id="send-msg">전송!</button>
                    </span>
                </div>
            </div>
        </div>

    </div>

    <script id="messages-template" type="text/x-handlebars-template">
        {{#each messages}}
        <div class="msg">
            <div class="details">
                <span class="user">{{user}}</span>:
            </div>
            <div class="details">
                <span class="text">{{text}}</span>
            </div>
            <div class="time">{{time}}</div>
        </div>
        {{/each}}
    </script>

    <script src="js/content/util/chat.js"></script>
    <?php
}
