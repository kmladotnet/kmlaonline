var Peek = {
    connection: null,

    show_traffic: function(body, type){
        if(body.childNodes.length > 0) {
            var console = $('#console').get(0);
            var at_bottom = console.scrollTop >= console.scrollHeight - console.clientHeight;

            $.each(body.childNodes, function (){
                $('#console').append("<div class='" + type + "'>" + Peek.xml2html(Strophe.serialize(this)) + "</div>");
            });

            if(at_bottom) {
                console.scrollTop = console.scrollHeight;
            }
        }
    },

    xml2html: function (s){
        return s.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
    }
};

$(document).ready(function(){
    $('#login_dialog').dialog({
        autoOpen: true,
        draggable: false,
        modal: true,
        title: 'Connect to XMPP',
        buttons: {
            "Connect": function (){
                $(document).trigger('connect', {
                    jid: $('#jid').val(),
                    password: $('#password').val()
                });

                $('#password').val('');
                $(this).dialog('close');
            }
        }
    });
});

$('#disconnect_button').click(function(){
    Peek.connection.disconnect();
});


$(document).bind('connect', function(ev, data){
    var conn = new Strophe.Connection('https://kmlaonline.net:5281/http-bind');

    conn.xmlInput = function(body){
        Peek.show_traffic(body, 'incoming');
    };

    conn.xmlOuput = function(body){
        Peek.show_traffic(body, 'outgoing');
    };

    conn.connect(data.jid, data.password, function(status){
        if(status === Strophe.Status.CONNECTED){
            $(document).trigger('connected');
        } else if (status === Strophe.Status.DISCONNECTED){
            $(document).trigger('disconnected');
        }
    });
    Peek.connection = conn;
});

$(document).bind('connected', function(){
    $('#disconnect_button').removeAttr('disabled');
});

$(document).bind('disconnected', function(){
    $('#disconnect_button').attr('disabled', 'disabled');
});