conn = new WebSocket('wss://kmlaonline.net/chat/');

(function setupWebSocket() {
    conn = new WebSocket('wss://kmlaonline.net/chat/');
    conn.onopen = function(e) {
        console.log("Connection established!");
    };

    conn.onmessage = function(e) {
        var msg = JSON.parse(e.data);
        updateMessages(msg);
    };

    conn.onclose = function(e) {
        setupWebSocket();
    }
})();

var user;
var messages = [];

var messages_template = Handlebars.compile($('#messages-template').html());

function updateMessages(msg){
    messages.push(msg);
    var messages_html = messages_template({'messages': messages});
    $('#messages').html(messages_html);
    $("#messages").animate({ scrollTop: $('#messages')[0].scrollHeight}, 1000);
}

function send (){
    var text = $('#msg').val();
    var msg = {
        'user': user,
        'text': text,
        'time': moment().format('hh:mm a')
    };
    updateMessages(msg);
    conn.send(JSON.stringify(msg));

    $('#msg').val('');
}


$("#msg").on("keyup", function(e){
    if (e.which == 13){
        send();
    }
});

$('#join-chat').click(function(){
    user = $('#user').val();
    $('#user-container').addClass('hidden');
    $('#main-container').removeClass('hidden');

    var msg = {
        'user': user,
        'text': user + '님이 입장하셨습니다.',
        'time': moment().format('hh:mm a')
    };

    updateMessages(msg);
    conn.send(JSON.stringify(msg));

    $('#user').val('');
});

$('#send-msg').click(send);


$('#leave-room').click(function(){

    var msg = {
        'user': user,
        'text': user + '님이 퇴장하셨습니다.',
        'time': moment().format('hh:mm a')
    };
    updateMessages(msg);
    conn.send(JSON.stringify(msg));

    $('#messages').html('');
    messages = [];

    $('#main-container').addClass('hidden');
    $('#user-container').removeClass('hidden');


});