nid = getId();

String.prototype.endsWith = function (suffix) {
    return this.indexOf(suffix, this.length - suffix.length) !== -1;
};

String.prototype.replaceAll = function (from, to) {
    return this.split(from).join(to);
};

function unixtime() {
    return Math.floor(new Date().valueOf() * 0.001);
}

function timeToString(d) {
    return Math.floor(d / 3600) + ":" + Math.floor((d % 3600) / 60) + ":" + (d % 60);
}

function htmlspecialchars(d) {
    var o = $("<div></div>");
    o.text(d);
    return o.html();
}

function removeAlertOnLeave() {
    window.onbeforeunload = function() {};
}

function putAlertOnLeave() {
    window.onbeforeunload = function () {
        return '정말로 페이지에서 나가시겠습니까?';
    };
}

function cancelAjaxSave() {
    if (window.ajax_write) window.ajax_write.abort();
    if (!window.noAlertOnLeave) {
        putAlertOnLeave();
    }
    hideLoading();
    window.ajax_write = undefined;
    try {
        delete window.ajax_write;
    } catch (e) {}
    window.ajaxing = false;
}

function checkAjaxReturnedData(ret) {
    var aa = false,
        err_compilation = "";
    if (ret["__other"]) {
        err_compilation += ret["__other"] + "\n";
        delete ret["__other"];
    }
    if (ret["__overriden"]) {
        delete ret["__overriden"];
    }
    for (var i in ret) {
        k = $("#cke_" + i);
        if (k.length == 0) {
            k = $('[name="' + i + '"]');
            if (k.length != 0) {
                k.css("backgroundColor", "#FDD");
                if (!aa) k.focus();
                aa = true;
                k.click(function () {
                    a = $('[name="' + this.name + '"]');
                    a.css("backgroundColor", "");
                    a.prop("onclick", "").unbind('click');
                });
            }
        }
        err_compilation += ret[i] + "\n";
    }
    if (window.callafter) window.callafter();
    window.callafter = null;
    hideLoading();
    alert("오류 사항들:\n\n" + err_compilation);
}

function showLoading(opername) {
    $("#divSavingIndicatorFiller").height($(document).height());
    $("#divSavingIndicatorFiller").css("display", "block");
    $("#divSavingIndicatorFiller").css('opacity', 0.7);
    $("#divSavingIndicator").height($(document).height());
    $("#divSavingIndicator").css("display", "block");
    $("#divSavingIndicator").css("opacity", 1);
    $("#spnWhatAmIDoing").html(opername);
    $("#cancelAjax").prop("disabled", "disabled");
    window.ajaxCancelTimer = setTimeout("$('#cancelAjax').removeAttr('disabled');", 5000);
    //$('html, body').velocity({ scrollTop: 0 }, 200);
}

function hideLoading() {
    if (window.ajaxCancelTimer) clearTimeout(window.ajaxCancelTimer);
    window.ajaxCancelTimer = null;
    $('#cancelAjax').removeAttr('disabled');
    $("#divSavingIndicatorFiller").velocity("fadeOut", {duration: 200});
    $("#divSavingIndicator").velocity("fadeOut", {duration: 200});
}

function saveAjax(obj, opername, ckeditor, callafter) {
    window.noAlertOnLeave = true;
    removeAlertOnLeave();
    if (ckeditor) CKEDITOR.instances[ckeditor].updateElement();
    if (window.ajaxing) {
        alert("처리 중입니다.");
        return false;
    }
    window.ajaxing = true;
    var string = "ajax=1&" + $(obj).serialize();
    showLoading(opername);
    window.callafter = callafter;
    window.ajax_write = $.ajax({
        type: $(obj).attr("method"),
        url: $(obj).attr("action"),
        data: string
    }).done(function (msg) {
        window.ajaxing = false;
        try {
            var ret = JSON.parse(msg);
            if (ret["error"] == 1) {
                delete ret["error"];
                checkAjaxReturnedData(ret);
            } else {
                if (ret["alert_message"])
                    alert(ret["alert_message"]);
                if (ret["redirect_to"]) {
                    doit = true;
                    if (ret["confirm_message"])
                        doit = confirm(ret["confirm_message"]);
                    if (doit) {
                        window.onbeforeunload = null;
                        location.href = ret["redirect_to"];
                        $(window).on("unload", function () {
                            if (window.leaving) clearTimeout(window.leaving);
                            window.leaving = null;
                        });
                        //window.leaving=setTimeout("window.location.reload(true);window.leaving=null;", 100);
                    }
                    cancelAjaxSave();
                    return;
                }
                if (window.callafter) window.callafter(ret, msg);
                window.callafter = null;
            }
            cancelAjaxSave();
        } catch (e) {
            alert(e + "\n" + msg);
            cancelAjaxSave();
        }
    }).fail(function (jqXHR, textStatus) {
        window.ajaxing = false;
        alert("Request failed: " + textStatus);
        cancelAjaxSave();
    });
    return false;
}

function showSlidedown(i, visib) {
    if (visib) {
        if ($("#slidedown" + i + "_sub").is(":visible")) return;
        showSlidedown(1, false);
        showSlidedown(2, false);
        showSlidedown(3, false);
        $("#slidedown" + i + "_button").addClass("slidedown_button_pressed");
        $("#slidedown" + i + "_sub").velocity("finish", true).velocity("slideDown", {duration: 200, easing: "ease"});
    } else {
        if (!$("#slidedown" + i + "_sub").is(":visible")) return;
        $("#slidedown" + i + "_button").removeClass("slidedown_button_pressed");
        $("#slidedown" + i + "_sub").velocity("finish", true).velocity("slideUp", {duration: 200, easing: "ease"});
    }
}

var upperHeaderVisible = false;

function askUser(title, text, onConfirm) {
    (new PNotify({
            title: title,
            text: text,
            icon: 'fa fa-question-circle',
            hide: false,
            confirm: {
                confirm: true
            },
            buttons: {
                closer: false,
                sticker: false
            },
            history: {
                history: false
            }
        })).get().on('pnotify.confirm', onConfirm);
}

function getId() {
    $.ajax({
        type: "GET",
        url: '/proc/user/nid',
        headers: {
            "x-content-only": "true"
        }
    }).done(function (msg) {
        nid = msg;
    }).fail(function (jqXHR, textStatus) {
        alert( "Notification 연결 실패. 관리자에게 문의하세요." );
    });
}

function loadUpperHeader(theurl, placeTo, immediate) {
    $.ajax({
        type: "GET",
        url: theurl,
        headers: {
            "x-content-only": "true"
        }
    }).done(function (msg) {
        p = $(placeTo);
        p.empty();
        obj = $(msg).find("div#total-content");
        obj.find('a').each(function () {
            if (this.onclick || $(this).is('.clickbound') || this.href == "") return;
            $(this).off("click");
            $(this).click(function () {
                if (this.rel == "navigate") {
                    loadUpperHeader(this.href, $("#upper-header-menu #total-content"), true);
                    return false;
                }
            });
        });
        if (immediate) {
            p.append(obj);
        } else {
            p.append(obj);
            obj.velocity("transition.slideDownIn", {duration: 400});
        }
        if(theurl.indexOf("contacts") != -1) {
            var $tbody = $('.contacts');
            $tbody.find('tr').sort(function(a, b) {
                var tda = $(a).find('td:eq(0)').text();
                var tdb = $(b).find('td:eq(0)').text();
                return tda > tdb ? 1
                       : tda < tdb ? -1
                       : 0;
            }).appendTo($tbody);
        }
    }).fail(function (jqXHR, textStatus) {
        //alert( "페이지를 불러 오는 데 실패하였습니다" );
    });
}

function showUpperHeader(itm) {
    $("#upper-header-menu").find(".upper-menus").not("#" + itm).velocity({
        opacity: 0,
        height: 0
    }, 300, function () {
        $(this).css("display", "none");
    });
    $("#" + itm).velocity("finish", true).css("display", "block").velocity({
        opacity: 1,
        height: $(window).height() + "px"
    }, 300, "swing", function () {
        $(this).height("auto");
    });
    $('html,body').velocity({
        scrollTop: 0
    }, 300);
    if ((toload = $("#" + itm).find(".ajax-holder")).length) {
        loadUpperHeader(toload.text(), toload.parent());
    }
    if (upperHeaderVisible) {
        hideUpperHeader();
        return;
    }

    $("#upper-header-menu-kept-visible").velocity({
        height: ($("#upper-header-menu-kept-visible").find(".menu2").length * 81) + "px"
    }, 300);

    $("#upper-header-menu").velocity("finish", true).velocity({
        opacity: 1,
        height: ($(window).height() - $("#total-header-menu").height()) + "px"
    }, 300, "swing", function () {
        $("#upper-header-menu").css("height", "auto");
        $("#total-header-menu").css("position", "fixed");
        $("#total-header-menu").css("width", "inherit");
        $("#total-header-menu").css("bottom", "0");
        $("#total-wrap").css("height", "100%");
        $("#total-wrap").css("background", "white");
    });

    $("#below-header-menu").velocity({
        height: 0
    }, 300);

    $("#below-header-menu").css("overflow", "hidden");

    $("#upper-header-menu-close").velocity({
        opacity: 1,
        height: "80px"
    }, 300);

    $("#total-header-menu-menus").children(".menu1").not(".menu1-logo").not("#upper-header-menu-kept-visible").velocity({
        width: 0,
        opacity: 0
    }, 300);
    $("div.menu1").off("mouseenter").off("mouseleave");
    $("div#total-header-menu .slidedown").css("top", "auto").css("bottom", "0");
    upperHeaderVisible = true;
    $(".hide-on-upper-panel").velocity("fadeOut", {duration: 300});
    $("#behind-total-wrap").velocity("fadeIn", {duration: 300});
}

function hideUpperHeader() {
    if (!upperHeaderVisible) return;
    $("#total-header-menu-menus").children(".menu1").not(".menu1-logo").velocity({
        width: 80,
        opacity: 1
    }, 300, function(){
        prepareHeader();
    });
    $("#total-wrap").css("background", "none");
    $("#total-wrap").css("height", "auto");
    $("#upper-header-menu").css("height", ($(window).height() - $("#total-header-menu").height()) + "px");
    $("#total-header-menu").css("position", "static");
    $("#upper-header-menu").velocity("finish", true).velocity({
        height: 0
    }, 300);
    $("#below-header-menu").velocity({
        height: $("#below-header-menu").children().height(),
        opacity: 1
    }, 300, "swing", function () {
        $(this).height("auto");
        $("#below-header-menu").css("overflow", "");
    });
    $("#upper-header-menu-close").velocity({
        height: 0
    }, 300);
    $("div#total-header-menu .slidedown").css("top", "40px").css("bottom", "auto");
    upperHeaderVisible = false;
    $("#total-header-menu").css("position", "fixed");
    $("#total-header-menu").css("bottom", "");
    $(".hide-on-upper-panel").velocity("fadeIn", {duration: 300});
    $("#behind-total-wrap").velocity("fadeOut", {duration: 300});
}

function closeMenu(ths, force) {
    var t = $(ths);
    var obj = t.find(".menu1_sub");
    var obj3 = t.find(".widthholder");
    if (obj.length) {
        t.velocity("stop", true).velocity({
            height: "40px",
        }, 200, "ease");
        obj.velocity("stop", true).velocity({opacity: 0}, {duration: 200});
    }
}

function hideUserButton() {
    showSlidedown(1, false);
    $("#slidedown1_button").velocity("stop", true).velocity({
        opacity: 0,
        width: 0,
        padding: 0,
    }, 150, "easeInOutQuad", function() { $(this).css("display", "none"); });
    userButtonShown = false;
}

function showUserButton() {
    $("#slidedown1_button").velocity("stop", true).css("display", "block").velocity({
        width: "40px",
        padding: "6px",
        opacity: 1,
    }, 150, "easeInOutQuad");
    userButtonShown = true;
}

function showHeader() {
    $("div.total-header-menu-extend").velocity("slideDown", {duration: 250, easing: "ease"});
    $("div.menu-shadow").velocity("slideDown", {duration: 250, easing: "ease"});
    $("#total-header-menu").velocity("slideDown", {duration: 250, easing: "ease"});
    $("div.menu1_text").velocity("finish", true).velocity("transition.slideDownIn", {display: null, duration: 250, stagger: 40});
    $("#menu-logo").velocity({
        opacity: 1
    }, 250, "ease");
    $("#menu-logo-2").velocity({
        opacity: 0
    }, 250, "ease");
}

var closeTimer;
var hovering;
var menuShown = true;
var userButtonShown = false;
var keepMenuShown = false;
function prepareHeader() {
    $("div.menu1").off("mouseenter").mouseenter(function () {
        var t = $(this);
        var obj = t.find(".menu1_sub");
        var obj2 = t.find(".widthholder");
        var a = t.find("a");
        if (obj.length) {
            clearTimeout(closeTimer);
            t.velocity("stop", true).velocity({
                height: obj.height() + 40,
            }, 300, "easeOutQuad");
            obj2.velocity("stop", true).velocity({
                height: "600px",
            }, 300);
            obj.velocity("stop", true).velocity({opacity: 1}, {duration: 200});
            $(".menu1").not(this).each(function (i) {
                closeMenu(this);
            });
            a.velocity("finish", true).velocity("transition.slideDownIn", {display: null, duration: 240, stagger: 40});
            hovering = true;
        }
    }).off("mouseleave").mouseleave(function () {
        clearTimeout(closeTimer);
        closeTimer = setTimeout(function () {
            $(".menu1").each(function (i) {
                closeMenu(this);
                hovering = false;
            });
        }, 300);
    });

    // Always show when scroll <= 160
    $(window).scroll(function (event) {
        var scroller = $(this).scrollTop();
        if ($("#total-header").length > 0) {
            if (scroller > 130) {
                if(!userButtonShown) {
                    showUserButton();
                }
            } else {
                if(userButtonShown) {
                    hideUserButton();
                }
            }
        } else {
            if(!userButtonShown) {
                showUserButton();
            }
        }
        if(scroller > ($("#total-header").length > 0 ? 160 : 0)) {
            if (!upperHeaderVisible && !keepMenuShown && !hovering && !$("#slidedown1_sub").is(":visible") && !$("#slidedown2_sub").is(":visible") && !$("#slidedown3_sub").is(":visible")) {
                if (menuShown) {
                    menuShown = false;
                    $("div.total-header-menu-extend").velocity("slideUp", {duration: 200, easing: "ease"});
                    $("div.menu-shadow").velocity("slideUp", {duration: 200, easing: "ease"});
                    $("#total-header-menu").velocity("slideUp", {duration: 200, easing: "ease"});
                    $("div.menu1_text").velocity("finish", true).velocity("transition.slideUpOut", {display: null, duration: 200});
                    $("#menu-logo").velocity("finish", true).velocity({
                        opacity: 0
                    }, 200, "ease", function () {});
                    $("#menu-logo-2").velocity({
                        opacity: 1
                    }, 200, "ease", function () {});
                }
            }
        } else {
            if (!menuShown) {
                menuShown = true;
                showHeader();
            }
        }
        previousScroll = scroller;
    });

    $("#menu-logo-2").hover(function () {
        if (!menuShown) {
            menuShown = true;
            showHeader();
        }
    }, function () {});

    $("#upper-header-holder").off("click").click(function () {
        $("#upper-header-holder").text("둘러보기");
        hideUpperHeader();
        return false;
    }).addClass("clickbound");
    $("#upper-header-menu-show-sitemap").off("click").click(function () {
        showUpperHeader("upper-menu-1");
        $("#upper-header-holder").text("닫기");
        return false;
    }).addClass("clickbound");
    $("#upper-header-menu-show-contacts").off("click").click(function () {
        showUpperHeader("upper-menu-2");
        $("#upper-header-holder").text("닫기");
        return false;
    }).addClass("clickbound");
    $("#upper-header-menu-close").off("click").click(function () {
        hideUpperHeader();
    });
    showSlidedown(1, false);
    showSlidedown(2, false);
    showSlidedown(3, false);
    for (var k = 1; k <= 3; k++) {
        if (!document.getElementById("slidedown" + k)) continue;
        $("#slidedown" + k).click(function (i2) {
            return function () {
                window.oneclicker = function (e) {
                    if (!$(e.target).is("#slidedown" + i2 + "_sub") && $(e.target).parents("#slidedown" + i2 + "_sub").length === 0)
                        showSlidedown(i2, false);
                };
                $(document).one("click", window.oneclicker);
                if ($("#slidedown" + i2 + "_sub").is(":visible"))
                    return;
                if (i2 == 2)
                    getNotifications();
                showSlidedown(i2, true);
                if (i2 == 3)
                    $('#txt_search_whole').focus();
                return false;
            }
        }(k));
    }


}

function editStatusMessageShow() {
    $("#status_message").css("display", "none");
    $("#status_message_edit").css("display", "block");
    $("#status_message_edit").focus();
    $("#status_message_edit").focusout(function () {
        doAjaxStatusMessageChange();
    });
    return false;
}

function doAjaxStatusMessageChange() {
    if ($('#status_message_edit').is(":disabled")) return false;
    if ($('#status_message_edit').val() == $('#status_message').html()) {
        $("#status_message_edit").css("display", "none");
        $("#status_message").css("display", "block");
        return false;
    }
    $('#status_message_edit').prop("disabled", "disabled");
    $.ajax({
        type: "POST",
        url: "ajax/user/statusmessage",
        data: {
            "s_status_message": $('#status_message_edit').val(),
            "ajax": "1"
        }
    }).done(function (msg) {
        try {
            var ret = JSON.parse(msg);
            $('#status_message_edit').removeAttr("disabled");
            if (ret["error"] == 0) {
                $('#status_message').html($('#status_message_edit').val());
                $("#status_message_edit").css("display", "none");
                $("#status_message").css("display", "block");
            } else {
                alert($ret['__other']);
            }
        } catch (e) {
            alert(msg);
        }
    }).fail(function (jqXHR, textStatus) {
        $('#status_message_edit').removeAttr("disabled");
        alert("Request failed: " + textStatus);
    });
    return false;
}

function changeTab(n, nohistoryedit) {
    for (i = 0; obj = document.getElementById("tab_menu_switch_" + i); i++) {
        obj = $(obj);
        if (i == n) {
            obj.addClass("tab_menu_selected");
            $("#tab_menu_" + i).velocity("finish", true).velocity("slideDown", {duration: 200, easing: "ease"}).velocity({
                opacity: 1
            }, {
                queue: false,
                duration: 200
            });
        } else {
            obj.removeClass("tab_menu_selected");
            $("#tab_menu_" + i).velocity("finish", true).velocity("slideUp", {duration: 200, easing: "ease"}).velocity({
                opacity: 0
            }, {
                queue: false,
                duration: 200
            });
        }
    }
    if (!nohistoryedit && window.history.replaceState) {
        newhref = location.href.replace(/([\?&]display=)[^\&]+/, '$1' + n);
        if (newhref.indexOf("?display=") == -1 && newhref.indexOf("&display=") == -1) {
            if (newhref.indexOf("?") != -1)
                newhref += "&display=" + n;
            else
                newhref += "?display=" + n;
        }
        window.history.replaceState(history.state, document.title, newhref);
    }
    return false;
}
var captchaStringRefIndex = 0;

function refreshCaptcha(obj) {
    if (!obj) obj = "#img_captcha";
    captchaStringRefIndex++;
    $(obj).prop("src", '/files/captcha/1.png?d=' + new Date().getTime() + "&a=" + captchaStringRefIndex);
}

function scrollToMiddle(top) {
    $('html,body').velocity({
        scrollTop: top - ($('html,body').height() / 3)
    }, 200);
}

function smoothToggleVisibility(itm, a) {
    itm = $(itm);
    if (a) {
        if (a == 1)
            itm.velocity("finish", true).velocity("slideUp", {duration: 200, easing: "ease"}).velocity({
                opacity: 0
            }, {
                queue: false,
                duration: 200
            });
        else
            itm.velocity("finish", true).velocity("slideDown", {duration: 200, easing: "ease"}).velocity({
                opacity: 1
            }, {
                queue: false,
                duration: 200
            });
    } else {
        if (!itm.is(":hidden"))
            itm.velocity("finish", true).velocity("slideUp", {duration: 200, easing: "ease"}).velocity({
                opacity: 0
            }, {
                queue: false,
                duration: 200
            });
        else
            itm.velocity("finish", true).velocity("slideDown", {duration: 200, easing: "ease"}).velocity({
                opacity: 1
            }, {
                queue: false,
                duration: 200
            });
    }
    return false;
}

function microtime(get_as_float) {
    var now = new Date().getTime() / 1000;
    var s = parseInt(now);
    return (get_as_float) ? now : (Math.round((now - s) * 1000) / 1000) + ' ' + s;
}

function basename(path) {
    return path.replace(/\\/g, '/').replace(/.*\//, '');
}

function dirname(path) {
    return path.replace(/\\/g, '/').replace(/\/[^\/]*$/, '');
}

function getNotifications() {
    $.ajax({
        type: "GET",
        url: "/ajax/user/getnotifications"
    }).done(function (msg) {
        try {
            var ret = JSON.parse(msg);
            $.each(ret, function (idx, val) {
                if ($("#notification_item_" + idx).length) return;
                obj = $(val);
                obj.prop("id", "notification_item_" + idx);
                $("#top_notification_list").prepend(obj);
            });
            $("#notification_item_loading").remove();
        } catch (e) {
            alert("Error occured: " + msg);
        }
    }).fail(function (jqXHR, textStatus) {});
}

function getNotificationCount() {
    $.ajax({
        type: "GET",
        url: "/ajax/user/getnotifications?count=yes"
    }).done(function (msg) {
        $('#notification-count').text(msg);
        if (msg > 0) {
            /*var stack_bottomright = {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25};
            (function () {
                var opts = {
                    title: "읽지 않은 알림이 있어요!",
                    type: "info",
                    addclass: "stack-bottomright translucent",
                    stack: stack_bottomright,
                    buttons: {
                        closer: false,
                        sticker: false
                    }
                };
                notice = new PNotify(opts);
            })(); */
            $('#notification-count').show();
        } else if(msg < 0){
            $('#notification-count').hide();
        }
        changeTitle(msg);
    }).fail(function (jqXHR, textStatus) {});
}

function changeTitle (note_num) {
    if(note_num === 0) {
        document.title = original_title;
    } else if(note_num > 0) {
        document.title = '(' + note_num + ') ' + original_title;
    }
}

function addPushNotification(href, profile_pic, desc) {
    var stack_bottomleft = {"dir1": "up", "dir2": "right", "firstpos1": 25, "firstpos2": 25};
    var push_notice = new PNotify({
        icon: false,
        width: '350px',
        type: 'info',
        text: '<a style="color: black; text-decoration: none;" href="' + href
            + '"><div style="display: block;"><div style="display: block; float: left;"><img alt="프로필" class="profile_pic" src="'
            + profile_pic + '" style="display: block; height: 48px; width: 48px; margin-right: 12px; border-radius: 50%;"></div><div style="text-align: left;">'
            + desc +'</div></div></a>',
        addclass: "stack-bottomleft translucent",
        stack: stack_bottomleft
    });
    push_notice.options.delay = 6000;
}

function addLoadEvent(func) {
    var oldonload = window.onload;
    if (typeof window.onload != 'function') {
        window.onload = func;
    } else {
        window.onload = function () {
            if (oldonload) {
                try {
                    oldonload();
                } catch (e) {}
            }
            try {
                func();
            } catch (e) {}
        }
    }
}
var slf = self;
var prt = parent;
var tp = top;
if (prt != slf) parent.location.href = slf.location.href;

function IsImageOk(img) {
    if (!img.complete) return false;
    if (typeof img.naturalWidth != "undefined" && img.naturalWidth == 0) return false;
    return true;
}

function main_changeFood(btn, id) {
    $(btn).parent().find("a").css("color", "");
    $(btn).css("color", "black");
    $('#food-breakfast').css("display", "none");
    $('#food-lunch').css("display", "none");
    $('#food-dinner').css("display", "none");
    $('#' + id).css("display", "block");
}

var falseReturnFunction = function () {
    return false;
};

function disableElement(div) {
    var chider = $('<div class="__hide_contents_link_change"></div>');
    chider.height($(div).height());
    $(div).click(falseReturnFunction).mousedown(falseReturnFunction).mouseup(falseReturnFunction).keydown(falseReturnFunction).keyup(falseReturnFunction).keypress(falseReturnFunction).prepend(chider);
    $(div).css("position", "relative");
    chider.velocity("finish", true);
}

function enableElement(div) {
    $(div).off("click").off("mousedown").off("mouseup").off("keydown").off("keyup").off("keypress").children(".__hide_contents_link_change").velocity("finish", true).css("opacity", 1).remove();
    $(div).css("position", "static");
}

function navigateBack() {
    history.go(-1);
    return false;
}

function unbindCkeditor() {
    var o = CKEDITOR.instances['s_data_ckeditor'];
    if (o) o.destroy();
}

function changeLinkTo(href, ajaxData) {
    location.href = href;
    return false;
}
function flashObject(elem, shortanim) {
    if(shortanim)
        return;
    $(elem).velocity("callout.shake", {duration: 500});
}
addLoadEvent(function () {
    if ($("#total-header").length == 0) {
        $("#slidedown1_button").velocity("stop", true).css({
            display: "block",
            width: "40px",
            padding: "6px",
            opacity: 1
        });
    }
    $("#slidedown2_button").click(function() {
        $('#notification-count').hide();
        changeTitle(0);
    });
    $('a').each(function () {
        if (this.onclick || $(this).is('.clickbound') || this.href == "" ||
            this.href.toLowerCase().indexOf("http://" + location.host.toLowerCase() + "/files/") == 0 ||
            this.href.toLowerCase().indexOf("https://" + location.host.toLowerCase() + "/files/") == 0 ||
            this.href.toLowerCase().indexOf("http://" + location.host.toLowerCase() + "/data/") == 0 ||
            this.href.toLowerCase().indexOf("https://" + location.host.toLowerCase() + "/data/") == 0
        )
            return;
        if(this.rel == "navigate") {
            $(this).off("click").click(function () {
                loadUpperHeader(this.href, $("#upper-header-menu #total-content"), true);
                return false;
            });
        }
    });
    $('form').each(function () {
        if (this.onsubmit) return;
        if (this.enctype == "multipart/form-data") return;
        $(this).off("submit").submit(function () {
            lnk = $(this).attr('action');
            if ($(this).attr("method").toLowerCase() == "get") lnk += "?" + $(this).serialize();
            return changeLinkTo(lnk, {
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                headers: {
                    "x-content-only": "true"
                },
                data: $(this).serialize()
            });
        });
    });
    $(".upper-file input[type=file]").change(function () {
        if (this.value) {
            p = this.value.replaceAll("\\", "/");
            if (p.indexOf("/") >= 0) p = p.substring(p.lastIndexOf("/") + 1);
            $(this).parent().find("span").text("선택됨: " + p);
        } else
            $(this).parent().find("span").text("파일 선택");
    });
    var elem = window.location.hash;
    if (elem && (elem = $(elem)) && elem.length > 0) {
        scrollToMiddle(elem.offset().top);
        flashObject(elem);
    }
    getId();
    getNotificationCount();
    original_title = document.title;
});


function upvote(id, down) {
    $.post("ajax/board/upvote", {
        "id" : id,
        "ajax": 1,
        "down" : down
    }, function () {
        if(down) {
            $('#upvote-' + id).css('display', 'table-cell');
            $('#vote-' + id).css('display', 'none');
            $('#downvote-' + id).css('display', 'none');
            $('#plus-' + id).addClass('active');
            $('#minus-' + id).removeClass('active');
        } else {
            $('#upvoted-' + id).css('display', 'table-cell');
            $('#unvoted-' + id).css('display', 'none');
        }
    });
}

function downvote(id) {
    $.post("ajax/board/downvote", {
        "id" : id,
        "ajax": 1
    }, function () {
        $('#upvote-' + id).css('display', 'none');
        $('#vote-' + id).css('display', 'none');
        $('#downvote-' + id).css('display', 'table-cell');
        $('#minus-' + id).addClass('active');
        $('#plus-' + id).removeClass('active');
    });
}

function unvote(id, down) {
    $.post("ajax/board/unvote", {
        "id" : id,
        "ajax": 1,
        "down" : down
    }, function () {
        if(down) {
            $('#upvote-' + id).css('display', 'none');
            $('#vote-' + id).css('display', 'table-cell');
            $('#downvote-' + id).css('display', 'none');
            $('#minus-' + id).removeClass('active');
            $('#plus-' + id).removeClass('active');
        } else {
            $('#upvoted-' + id).css('display', 'none');
            $('#unvoted-' + id).css('display', 'table-cell');
        }
    });
}

function hidePost(id) {
    $("#item_contents_" + id).velocity("slideUp", {duration: 100, easing: "ease"});
    $("#item_hidden_" + id).velocity("slideDown", {duration: 100, easing: "ease"});
    $("#collapse-" + id).css('display', 'none');
    $("#uncollapse-" + id).css('display', 'block');
}

function showPost(id) {
    $("#item_contents_" + id).velocity("slideDown", {duration: 100, easing: "ease"});
    $("#item_hidden_" + id).velocity("slideUp", {duration: 100, easing: "ease"});
    $("#collapse-" + id).css('display', 'block');
    $("#uncollapse-" + id).css('display', 'none');
}
