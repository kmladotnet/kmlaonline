// create a namespace object for Hello
// add a connection property and the log() function
var Hello = {
    // connection will store the active connection object so that it can be accessed later
    connection: null,

    //log() function simply updates the logging area with a message
    log: function(msg){
        $('#log').append("<p>" + msg + "</p>");
    }
};

$(document).ready(function (){
    $('#login_dialog').dialog({
        autoOpen: true,
        draggable: false,
        //modal : if set to true, the dialog will have modal behavior; other items on the page will be disabled
        modal: true,
        title: 'Connect to XMPP',
        //buttons defines the dialog box's buttons and the actions to take when those buttons are clicked
        buttons: {
            "Connect": function() {
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

//create a handler for the connect event that creates a new Strophe.Connection object
//and calls the connect() method. also need to provide a callback that can respond
//to changes in the connection status

$(document).bind('connect', function(ev, data){

    //creates a new connection object
    var conn = new Strophe.Connection("https://kmlaonline.net:5281/http-bind");

    //calls connect() with a callback function that merely triggers new custom events
    conn.connect(data.jid, data.password, function(status){
        if(status === Strophe.Status.CONNECTED) {
            $(document).trigger('connected');
        } else if(status === Strophe.Status.DISCONNECTED) {
            $(document).trigger('disconnected');
        }
    });

    Hello.connection = conn;
});

$(document).bind('connected', function(){
    // inform the user
    Hello.log("Connection established");
});

$(document).bind('disconnected', function(){
    Hello.log("Connection terminated");

    //remove dead connection object
    Hello.connection = null;
});
