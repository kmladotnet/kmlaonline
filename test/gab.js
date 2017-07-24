var Gab = {
    connection: null,

    pending_subscriber: null,

    on_roster: function(iq){
        $(iq).find('item').each(function(){
            var jid = $(this).attr('jid');
            var name = $(this).attr('name') || jid;

            var jid_id = Gab.jid_to_id(jid);

            var contact = $("<li id='" + jid_id + "'>" +
                            "<div class='roster-contact offline'>" +
                            "<div class='roster-name'>" +
                            name +
                            "</div><div class='roster-jid'>" +
                            jid +
                            "</div></div></li>");

            Gab.insert_contact(contact);

            // Set up presence handler and send initial presence
            Gab.connection.addHandler(Gab.on_presence, null, "presence");
            Gab.connection.send($pres());
        });
    },

    on_roster_changed: function(iq){
        $(iq).find('item').each(function(){
            var sub = $(this).attr('subscription');
            var jid = $(this).attr('jid');
            var name = $(this).attr('name');
            var jid_id = Gab.jid_to_id(jid);

            if(sub === 'remove'){
                //contact is being removed
                $('#' + jid_id).remove();
            } else {
                //contact is being added or modified
                var contact_html = "<li id='" + jid_id + "'>" +
                    "<div class'" + ($('#' + jid_id).attr('class') || "roster-contact offline") +
                    "'>" +
                    "<div class='roster-name'>" +
                    name +
                    "</div><div class='roster-jid'>" +
                    jid +
                    "</div></div></li>";

                    if($('#' + jid_id).length > 0){
                        $('#' + jid_id).replaceWith(contact_html);
                    } else {
                        Gab.insert_contact(contact_html);
                    }
            }
        });
        return true;
    },

    on_presence: function(presence){
        var ptype = $(presence).attr('type');
        var from = $(presence).attr('from');
        if(ptype === 'subscribe'){
            //populate pending_subscriber, the approve-jid span, and
            //open the dialog

            Gab.pending_subscriber = from;
            $("#approve-jid").text(Strophe.getBareJidFromJid(from));
            $("approve-dialog").dialoge('open');
        } else if(ptype !== 'error'){
            var contact = $('#roster-area li#' + Gab.jid_to_id(from))
                .removeClass("online")
                .removeClass("away")
                .removeClass("offline");
            if(ptype === 'unavailable') {
                contact.addClass('offline');
            } else {
                var show = $(presence).find("show").text();
                if (show === "" || show === "chat") {
                    contact.addClass("online");
                } else {
                    contact.addClass("away");
                }
            }

            var li = contact.parent();
            li.remove();
            Gab.insert_contact(li);
        }

        return true;
    },
    jid_to_id: function(jid){
        return Strophe.getBareJidFromJid(jid)
                .replace("@", "-")
                .replace(".", "-");
    },
    presence_value: function(elem){
        if(elem.hasClass('online')) {
            return 2;
        } else if (elem.hasClass('away')) {
            return 1;
        }

        return 0;
    },
    insert_contact: function(elem){
        var jid = elem.find('.roster-jid').text();
        var pres = Gab.presence_value(elem.find('.roster-contact'));

        var contacts = $('#roster-area li');
        if(contacts.length > 0){
            var inserted = false;
            contacts.each(function(){
                var cmp_press = Gab.presence_value(
                    $(this).find('.roster-contact'));
                var cmp_jid = $(this).find('.roster-jid').text();

                if(pres > cmp_press) {
                    $(this).before(elem);
                    inserted = true;
                    return false;
                } else {
                    if (jid < cmp_jid){
                        $(this).before(elem);
                        inserted = true;
                        return false;
                    }
                }
            });

            if(!inserted) {
                $('#roster-area ul').append(elem);
            }
        } else {
            $('#roster-area ul').append(elem);
        }
    }
};

$(document).ready(function(){
    $('#login_dialog').dialog({
        autoOpen: true,
        draggable: false,
        modal: true,
        title: 'Connect to XMPP',
        buttons: {
            'Connect': function(){
                $(document).trigger('connect', {
                    jid: $('#jid').val(),
                    password: $('#password').val()
                });

                $('#password').val('');
                $(this).dialog('close');
            }
        }
    });

    $('#contact_dialog').dialog({
        autoOpen: false,
        draggable: false,
        modal: true,
        title: "Add a Contact",
        buttons: {
            "Add": function() {
                $(document).trigger('contact-added', {
                    jid: $("#contact-jid").val(),
                    name: $("#contact-name").val()
                });

                $('#contact-jid').val('');
                $('#contact-name').val('');

                $(this).dialog('close');
            }
        }
    });

    $('#new-contact').click(function (en) {
        $('#contact_dialog').dialog('open');
    });

    $('#approve-dialog').dialog({
        autoOpen: false,
        draggable: false,
        modal: true,
        title: "Subscription Request",
        buttons: {
            "Deny": function() {
                Gab.connection.send($pres({
                    to: Gab.pending_subscriber,
                    type: "unsubscribed"}));
                Gab.pending_subscriber = null;

                $(this).dialog('close');
            },

            "Approve": function(){
                Gab.connection.send($pres({
                    to: Gab.pending_subscriber,
                    type: "subscribed"}));
                //Make sure that the connection is bi-directional
                Gab.connection.send($pres({
                    to: Gab.pending_subscriber,
                    type: "subscribe"}));

                Gab.pending_subscriber = null;

                $(this).dialog('close');
            }
        }
    });
});

$(document).bind('connect', function(en, data){
    var conn = new Strophe.Connection('https://kmlaonline.net:5281/http-bind');
    conn.connect(data.jid, data.password, function(status){
        if(status === Stophe.Connection.CONNECTED) {
            $(document).trigger('connected');
        } else if(status === Stophe.Connection.DISCONNECTED){
            $(document).trigger('disconnected');
        }
    });

    Gab.connection = conn;
});

$(document).bind('connected', function(){
    //Retrieve the roster
    var iq = $iq({type: 'get'}).c('query', {xmlns: 'jabber:iq:roster'});
    Gab.connection.sendIQ(iq, Gab.on_roster);

    Gab.connection.addHandler(Gab.on_roster_changed, "jabber:iq:roster", "iq", "set");
});

$(document).bind('disconnected', function(){
    //Nothing here yet
});

$(document).bind('contact_added', function(ev, data){
    var iq = $iq({type: 'set'}).c('query', {xmlns: 'jabber:iq:roster'})
                .c("item", data);
    Gab.connection.sendIQ(iq);

    var subscribe = $pres({to: data.jid, type: "subscribe"});
    Gab.connection.send(subscribe);
});