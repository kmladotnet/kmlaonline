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

function getURLParameter(name) {
    //https://stackoverflow.com/questions/11582512/how-to-get-url-parameters-with-javascript/11582513#11582513
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null;
}

function htmlspecialchars(d) {
    var o = $("<div></div>");
    o.text(d);
    return o.html();
}

function randstring(len) {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for (var i = 0; i < len; i++) text += possible.charAt(Math.floor(Math.random() * possible.length));
    return text;
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
                        $(window).unload(function () {
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
        showSlidedown(2, false);
        showSlidedown(3, false);
        $("#slidedown" + i + "_button").addClass("slidedown_button_pressed");
        $("#slidedown" + i + "_sub").velocity("finish", true).velocity("slideDown", {duration: 200, easing: "easeOutCubic"});
    } else {
        if (!$("#slidedown" + i + "_sub").is(":visible")) return;
        $("#slidedown" + i + "_button").removeClass("slidedown_button_pressed");
        $("#slidedown" + i + "_sub").velocity("finish", true).velocity("slideUp", {duration: 200, easing: "easeOutCubic"});
    }
}
var upperHeaderVisible = false;

function initContacts() {
    $(".contacts").DataTable( {
        autoWidth: false,
        language: {
           "sEmptyTable":     "데이터가 없습니다",
           "sInfo":           "_START_ - _END_ / _TOTAL_",
           "sInfoEmpty":      "0 - 0 / 0",
           "sInfoFiltered":   "(총 _MAX_ 개)",
           "sInfoPostFix":    "",
           "sInfoThousands":  ",",
           "sLengthMenu":     "페이지당 줄수 _MENU_",
           "sLoadingRecords": "읽는중...",
           "sProcessing":     "처리중...",
           "sSearch":         "검색:",
           "sZeroRecords":    "검색 결과가 없습니다",
           "oPaginate": {
               "sFirst":    "처음",
               "sLast":     "마지막",
               "sNext":     "다음",
               "sPrevious": "이전"
           },
           "oAria": {
               "sSortAscending":  ": 오름차순 정렬",
               "sSortDescending": ": 내림차순 정렬"
           }
        }
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
                if (this.rel == "closenow") hideUpperHeader();
            });
        });
        if (immediate) {
            p.append(obj);
        } else {
            obj.fadeTo(0, 0);
            p.append(obj);
            obj.fadeTo(1000, 1);
        }
        initContacts();
    }).fail(function (jqXHR, textStatus) {
        //alert( "페이지를 불러 오는 데 실패하였습니다" );
    });
}

function showUpperHeader(itm) {
    $("#upper-header-menu").find(".upper-menus").not("#" + itm).velocity("finish", true).velocity({
        opacity: 0,
        height: 0
    }, 500, function () {
        $(this).css("display", "none");
    });
    $("#" + itm).velocity("finish", true).css("display", "block").velocity({
        opacity: 1,
        height: $(window).height() + "px"
    }, 500, "swing", function () {
        $(this).height("auto");
    });
    $('html,body').velocity({
        scrollTop: 0
    }, 500);
    if ((toload = $("#" + itm).find(".ajax-holder")).length) {
        loadUpperHeader(toload.text(), toload.parent());
    }
    if (upperHeaderVisible) {
        hideUpperHeader();
        return;
    }

    $("#upper-header-menu-kept-visible").velocity({
        height: ($("#upper-header-menu-kept-visible").find(".menu2").length * 81) + "px"
    }, 500);

    $("#upper-header-menu").velocity("finish", true).velocity({
        opacity: 1,
        height: ($(window).height() - $("#total-header-menu").height()) + "px"
    }, 500, "swing", function () {
        $("#upper-header-menu").css("height", "auto");
        $("#total-header-menu").css("position", "fixed");
        $("#total-header-menu").css("width", "inherit");
        $("#total-header-menu").css("bottom", "0");
        $("#total-wrap").css("height", "100%");
        $("#total-wrap").css("background", "white");
    });

    $("#below-header-menu").velocity({
        height: 0,
        opacity: 0.7
    }, 500);

    $("#upper-header-menu-close").velocity({
        opacity: 1,
        height: "80px"
    }, 500);

    $("#total-header-menu-menus").children(".menu1").not("#upper-header-menu-kept-visible").velocity({
        height: 0,
        opacity: 0.7
    }, 500);
    $("div.menu1").off("mouseenter").off("mouseleave");
    $("div#total-header-menu .slidedown").css("top", "auto").css("bottom", "0");
    upperHeaderVisible = true;
    $(".hide-on-upper-panel").velocity("fadeOut", {duration: 500});
    $("#behind-total-wrap").velocity("fadeOut", {duration: 500});
}

function hideUpperHeader() {
    if (!upperHeaderVisible) return;
    $("#total-wrap").css("background", "none");
    $("#total-wrap").css("height", "auto");
    $("#upper-header-menu").css("height", ($(window).height() - $("#total-header-menu").height()) + "px");
    $("#total-header-menu").css("position", "static");
    $("#upper-header-menu").velocity("finish", true).velocity({
        opacity: 0.7,
        height: 0
    }, 500);
    $("#below-header-menu").velocity({
        height: $("#below-header-menu").children().height() + "px",
        opacity: 1
    }, 500, "swing", function () {
        $(this).height("auto");
    });
    $("#upper-header-menu-close").velocity({
        opacity: 0.7,
        height: 0
    }, 500);
    $("#total-header-menu-menus").children(".menu1").velocity({
        height: "80px",
        opacity: 1
    }, 500);
    closeMenu("#upper-header-menu-kept-visible", true);
    $("div#total-header-menu .slidedown").css("top", "40px").css("bottom", "auto");
    upperHeaderVisible = false;
    $("#total-header-menu").css("position", "fixed");
    $("#total-header-menu").css("bottom", "");
    $(".hide-on-upper-panel").velocity("fadeIn", {duration: 500});
    $("#behind-total-wrap").velocity("fadeOut", {duration: 500});
    prepareHeader();
}

function closeMenu(ths, force) {
    var t = $(ths);
    var obj = t.find(".menu1_sub");
    var obj3 = t.find(".widthholder");
    if (obj.length) {
        t.velocity("stop", true).velocity({
            height: "40px",
        }, 200, "easeOutCubic");
        obj.velocity("stop", true).velocity({opacity: 0}, {duration: 200});
    }
}

function showHeader() {
    $("div.total-header-menu-extend").velocity("slideDown", {duration: 250, easing: "easeOutCubic"});
    $("div.menu-shadow").velocity("slideDown", {duration: 250, easing: "easeOutCubic"});
    $("#total-header-menu").velocity("slideDown", {duration: 250, easing: "easeOutCubic"});
    $("div.menu1_text").velocity("finish", true).velocity("transition.slideLeftIn", {display: null, duration: 250, stagger: 40});
    $("#menu-logo").velocity({
        left: 0,
        opacity: 1
    }, 250, "easeOutCubic");
    $("#menu-logo-2").velocity({
        left: 0,
        opacity: 0
    }, 250, "easeOutCubic");
}

var closeTimer;
var hovering;
var menuShown = true;

function prepareHeader() {
    $("div.menu1").off("mouseenter").mouseenter(function () {
        var t = $(this);
        var obj = t.find(".menu1_sub");
        var obj2 = t.find(".widthholder");
        if (obj.length) {
            clearTimeout(closeTimer);
            t.velocity("stop", true).velocity({
                height: obj.height() + 40,
            }, 200, "easeOutCubic");
            obj2.velocity("stop", true).velocity({
                height: "600px",
            }, 200);
            obj.velocity("stop", true).velocity({opacity: 1}, {duration: 200});
            $(".menu1").not(this).each(function (i) {
                closeMenu(this);
            });
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
        if (!upperHeaderVisible && !hovering && !$("#slidedown2_sub").is(":visible") && !$("#slidedown3_sub").is(":visible")) {
            if (scroller > 160) {
                if (menuShown) {
                    menuShown = false;
                    $("div.total-header-menu-extend").velocity("slideUp", {duration: 200, easing: "easeOutCubic"});
                    $("div.menu-shadow").velocity("slideUp", {duration: 200, easing: "easeOutCubic"});
                    $("#total-header-menu").velocity("slideUp", {duration: 200, easing: "easeOutCubic"});
                    $("div.menu1_text").velocity("finish", true).velocity("transition.slideUpOut", {display: null, duration: 200});
                    $("#menu-logo").velocity("finish", true).velocity({
                        left: -40,
                        opacity: 0
                    }, 200, "easeOutCubic", function () {});
                    $("#menu-logo-2").velocity({
                        left: -40,
                        opacity: 1
                    }, 200, "easeOutCubic", function () {});
                }
            } else {
                if (!menuShown) {
                    menuShown = true;
                    showHeader();
                }
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
    showSlidedown(2, false);
    showSlidedown(3, false);
    for (var k = 2; k <= 3; k++) {
        if (!document.getElementById("slidedown" + k)) continue;
        $("#slidedown" + k).click(function (i2) {
            return function () {
                window.oneclicker = function (e) {
                    if ($(e.target).parents("#slidedown" + i2 + "_sub").length === 0)
                        showSlidedown(i2, false);
                };
                $(document).one("click", window.oneclicker);
                if ($("#slidedown" + i2 + "_sub").is(":visible"))
                    return;
                if (i2 == 2)
                    getNotifications();
                if (i2 == 3)
                    $('#txt_search_whole').focus();
                showSlidedown(i2, true);
                return false;
            }
        }(k));
    }
}

var originalSerializeArray = $.fn.serializeArray;
$.fn.extend({
    serializeArray: function () {
        var brokenSerialization = originalSerializeArray.apply(this);
        var checkboxValues = $(this).find('input[type=checkbox]').map(function () {
            return { 'name': this.name, 'value': this.checked };
        }).get();
        var checkboxKeys = $.map(checkboxValues, function (element) { return element.name; });
        var withoutCheckboxes = $.grep(brokenSerialization, function (element) {
            return $.inArray(element.name, checkboxKeys) == -1;
        });

        return $.merge(withoutCheckboxes, checkboxValues);
    }
});

function moduleToObject(module) {
    var result = new Object();
    result.name = module.data("module-name");
    result.options = new Object();
    var node = module.data('_gridstack_node');
    result.options.x = node.x;
    result.options.y = node.y;
    result.options.w = node.width;
    result.options.h = node.height;
    result.options.options = module.data("module-options");
    return result;
}

function mainGridToJSON() {
    var result = [];
    $(".grid-stack-item").each(function (index) {
        result[index] = moduleToObject($(this));
    });
    return JSON.stringify(result);
}

function rebindModules() {
    bindModuleCloseButton();
    bindModuleReloadButton();
    bindOptionsForm();
    $('.selectpicker').selectpicker();
}

function bindAddModuleButton() {
    $("#add-module").on('change', function(event) {
        var name = $("#add-module").val();
        $.post("ajax/user/getmoduledefaults", {
            "name": name,
            ajax: 1
        }, function (data) {
            var grid = $('.grid-stack').data('gridstack');
            grid.add_widget($(data), 0, 0, 4, 4);
            rebindModules();
            $(".main-block-close").css({width: 24, "margin-left": 5, "border-width": 1, opacity: 1});
            $(".grid-stack-item:not([data-module-name])").attr("data-module-name", name);
            $(".grid-stack-item:not([data-module-name])").attr("data-module-options", "[]");
            updateModules();
        });
        $("#add-module").val('');
        return false;
    });
}

function bindModuleCloseButton() {
    $(".main-block-close").unbind("click").click(function () {
        $('.grid-stack').data('gridstack').remove_widget($(this).closest(".grid-stack-item"));
        updateModules();
    });
}

function bindModuleReloadButton() {
    $(".main-block-reload").unbind("click").click(function () {
        var module = $(this).closest(".grid-stack-item");
        $.post("ajax/user/getmodule", {
            "json": JSON.stringify(moduleToObject(module)),
            "ajax": 1
        }, function (data) {
            module.closest(".grid-stack-item-content").html(data);
            rebindModules();
            if($("#main-edit-button").hasClass('active')) {
                $(".main-block-close").css({width: 24, "margin-left": 5, "border-width": 1, opacity: 1});
            }
        });
        new PNotify({
            text: '새로고침 했습니다.',
            type: 'success',
            buttons: {
                closer: false,
                sticker: false
            }
        });
    });
}

function saveOptionsForm(form) {
    var module = form.closest(".grid-stack-item");
    var options = module.data("module-options");
    var catFirst = true;
    $(form.serializeArray()).each(function(i, field) {
        if(field.name === "cat") {
            if(catFirst || !("cat" in options) || !Array.isArray(options["cat"])) {
                options["cat"] = new Array();
                catFirst = false;
            }
            options["cat"].push(field.value);
        } else {
            options[field.name] = field.value;
        }
    });
    module.attr("data-module-options", options);
    $.post("ajax/user/getmodule", {
        "json": JSON.stringify(moduleToObject(module)),
        "ajax": 1
    }, function (data) {
        module.closest(".grid-stack-item-content").html(data);
        rebindModules();
        if($("#main-edit-button").hasClass('active')) {
            $(".main-block-close").css({width: 24, "margin-left": 5, "border-width": 1, opacity: 1});
        }
        updateModules();
    });
}

function bindOptionsForm() {
    $(".main-block-options-form").submit(false);
    $(".main-block-options-submit").unbind("click").click(function() {
        saveOptionsForm($(this).closest("form"));
    });
    $(".main-block-options-cancel").unbind("click").click(function() {
        toggleOptions(false, $(this), true);
    });
}

function toggleOptions(show, element, force) {
    var mainBlock = element.closest(".main-block");
    if(!show && force) {
        mainBlock.find(".main-block-options").removeClass("active");
    }
    var toShow = mainBlock.find(show ? ".main-block-options-pane" : ".main-block-content");
    var toHide = mainBlock.find(show ? ".main-block-content" : ".main-block-options-pane");
    toHide.velocity("fadeOut", {duration: 150, complete: function() { toShow.velocity("fadeIn", {duration: 150}); }});
}

function resetMainLayout() {
    (new PNotify({
        title: '정말로 레이아웃을 초기화할까요?',
        icon: 'glyphicon glyphicon-question-sign',
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
    })).get().on('pnotify.confirm', function () {
        $.post("ajax/user/resetlayout", {
                "ajax": "1"
            })
            .done(function () {
                location.reload(true);
            });
    }).on('pnotify.cancel', function () {
        new PNotify({
            text: '취소했습니다.',
            type: 'info',
            buttons: {
                closer: false,
                sticker: false
            }
        });
    });
}

function updateModules() {
    $.post("ajax/user/updatelayout", {
            "json": mainGridToJSON(),
            "ajax": "1"
        })
        .done(function () {
            var notice = new PNotify({
                text: '레이아웃이 저장되었습니다!',
                type: 'success',
                buttons: {
                    closer: false,
                    sticker: false
                }
            });
            notice.get().click(function () {
                notice.remove();
            });
        }).fail(function () {
            var notice = new PNotify({
                text: '레이아웃을 저장하지 못했습니다.',
                type: 'error',
                buttons: {
                    closer: false,
                    sticker: false
                }
            });
            notice.get().click(function () {
                notice.remove();
            });
        });
}

function addModule(json) {
    $.post("ajax/user/getmodule", {
        "json": json,
        "ajax": 1
    }, function (data) {
        var grid = $('.grid-stack').data('gridstack');
        var dat = JSON.parse(json);
        grid.add_widget($(data), 0, 0, 4, 4);
        rebindModules();
        $(".grid-stack-item:not([data-module-name])").attr("data-module-name", dat["name"]).attr("data-module-options", JSON.stringify(dat["options"]["options"]));
        updateModules();
    });
}

function toggleLayoutEditing(set) {
    if(set) {
        $(".main-block-close").velocity({width: 24, "margin-left": 5, "border-width": 1, opacity: 1}, 300);
        $(".main-block-title").css("cursor", "move");
        $("#main-edit-pane").velocity("slideDown", {duration: 300, easing: "easeOutCubic"});
        $('.grid-stack').data('gridstack').enable();
    } else {
        $(".main-block-close").velocity({width: 0, "margin-left":0, "border-width": 0, opacity: 0}, 300);
        $(".main-block-title").css("cursor", "default");
        $("#main-edit-pane").velocity("slideUp", {duration: 300, easing: "easeOutCubic"});
        $('.grid-stack').data('gridstack').disable();
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
            $("#tab_menu_" + i).velocity("finish", true).velocity("slideDown", {duration: 200, easing: "easeOutCubic"}).velocity({
                opacity: 1
            }, {
                queue: false,
                duration: 200
            });
        } else {
            obj.removeClass("tab_menu_selected");
            $("#tab_menu_" + i).velocity("finish", true).velocity("slideUp", {duration: 200, easing: "easeOutCubic"}).velocity({
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
            itm.velocity("finish", true).velocity("slideUp", {duration: 200, easing: "easeOutCubic"}).velocity({
                opacity: 0
            }, {
                queue: false,
                duration: 200
            });
        else
            itm.velocity("finish", true).velocity("slideDown", {duration: 200, easing: "easeOutCubic"}).velocity({
                opacity: 1
            }, {
                queue: false,
                duration: 200
            });
    } else {
        if (!itm.is(":hidden"))
            itm.velocity("finish", true).velocity("slideUp", {duration: 200, easing: "easeOutCubic"}).velocity({
                opacity: 0
            }, {
                queue: false,
                duration: 200
            });
        else
            itm.velocity("finish", true).velocity("slideDown", {duration: 200, easing: "easeOutCubic"}).velocity({
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
        if (msg > 0)
            $("#notificationCount").css("display", "block");
        else
            $("#notificationCount").css("display", "none");
        $("#notificationCount").text(msg);
    }).fail(function (jqXHR, textStatus) {});
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

function isExternal(url) {
    if (url.indexOf("/") < url.indexOf("//")) return false;
    k = url.indexOf("?");
    a = url.indexOf("#");
    if (a < k) k = a;
    if (k == -1)
        if (url.indexOf("//") == -1) return false;
    if (url.indexOf("//") >= k) return false;
    var match = url.match(/^([^:\/?#]+:)?(?:\/\/([^\/?#]*))?([^?#]+)?(\?[^#]*)?(#.*)?/);
    if (typeof match[1] === "string" && match[1].length > 0 && match[1].toLowerCase() !== location.protocol) return true;
    if (typeof match[2] === "string" && match[2].length > 0 && match[2].replace(new RegExp(":(" + {
            "http:": 80,
            "https:": 443
        }[location.protocol] + ")?$"), "") !== location.host) return true;
    return false;
}

function unbindCkeditor() {
    var o = CKEDITOR.instances['s_data_ckeditor'];
    if (o) o.destroy();
}

function changeLinkTo(href, ajaxData) {
    location.href = href;
    return false;

    if (!history.pushState) {
        location.href = href;
        return false;
    }
    if (href === "") return true;
    if (isExternal(href)) {
        window.open(href);
        return true;
    }
    if (window.onbeforeunload) window.onbeforeunload();
    if (!window.move_page) {
        history.pushState({
            head: $("head").html(),
            body: $("#below-header-menu #total-content").html(),
            header: $("#below-header-menu #total-header").html(),
            footer: $("#below-header-menu #total-footer").html(),
            ttitle: document.title,
            script: $("#onload-script").html()
        }, document.title, href);
    } else {
        history.replaceState({
            head: $("head").html(),
            body: $("#below-header-menu #total-content").html(),
            header: $("#below-header-menu #total-header").html(),
            footer: $("#below-header-menu #total-footer").html(),
            ttitle: document.title,
            script: $("#onload-script").html()
        }, document.title, href);
    }
    enableElement("#below-header-menu #total-content");
    disableElement("#below-header-menu #total-content");
    if (window.move_page) window.move_page.abort();
    if (!ajaxData)
        ajaxData = {
            type: "GET",
            url: href,
            headers: {
                "x-content-only": "true"
            }
        };
    window.move_page = $.ajax(ajaxData).done(function (msg) {
        unbindCkeditor();
        window.onbeforeunload = null;
        enableElement("#below-header-menu #total-content");
        if (msg.indexOf(":") != -1) {
            var action = msg.substring(0, msg.indexOf(":"));
            if (action == "redirect") {
                location.href = msg.substring(action.length + 1);
                return;
            }
        } else if (msg.indexOf("<html>") != -1 && msg.indexOf("<head>") != -1 && msg.indexOf("<body>") != -1) {
            location.href = href;
            return;
        }
        newDoc = $(msg);
        var removeStarted = false;
        document.title = newDoc.find("div#html-title:first").text();
        $("head").children().each(function (i) {
            if (removeStarted) {
                $(this).remove();
            } else {
                if (this.name == "changeable-start")
                    removeStarted = true;
            }
        });
        $("head").append(newDoc.find("div#html-head:first").children());
        $("#below-header-menu #total-content").empty();
        $("#below-header-menu #total-content").append(newDoc.find("#below-header-menu #total-content").children());
        $("#below-header-menu #total-header").empty();
        $("#below-header-menu #total-header").append(newDoc.find("#below-header-menu #total-header").children());
        $("#below-header-menu #total-footer").html(newDoc.find("#below-header-menu #total-footer").html());
        eval(newDoc.find('#onload-scripts:first').html());
        if (window.onload) window.onload();
        if (document.onload) document.onload();
        window.move_page = false;
        $("#onload-scripts").html(newDoc.find('#onload-scripts:first').html());
        history.replaceState({
            head: $("head").html(),
            body: $("#below-header-menu #total-content").html(),
            header: $("#below-header-menu #total-header").html(),
            footer: $("#below-header-menu #total-footer").html(),
            ttitle: document.title,
            script: $("#onload-scripts").html()
        }, document.title, href);
    }).fail(function (jqXHR, textStatus) {
        //alert( "페이지를 불러 오는 데 실패하였습니다" );
        enableElement("#below-header-menu #total-content");
        window.move_page = false;
        history.go(-1);
    });
    if (event && event.preventDefault) event.preventDefault();
    return false;
}
var popped = ('state' in window.history && window.history.state !== null),
    initialURL = location.href;
if (history.pushState && false) {
    window.addEventListener("popstate", function (s) {
        var initialPop = !popped && location.href == initialURL;
        popped = true;
        if (initialPop) {
            history.replaceState({
                head: $("head").html(),
                body: $("#below-header-menu #total-content").html(),
                header: $("#below-header-menu #total-header").html(),
                footer: $("#below-header-menu #total-footer").html(),
                ttitle: document.title,
                script: ""
            }, document.title, location.href);
            if (event && event.preventDefault) event.preventDefault();
            return false;
        }
        if (s.state) {
            location.reload();
            return;
            hideUpperHeader();
            unbindCkeditor();
            window.onbeforeunload = null;
            var removeStarted = false;
            $("head").children().each(function (i) {
                if (this.name == "kmlaonline-changeable-end")
                    removeStarted = false;
                else if (removeStarted) {
                    $(this).remove();
                } else {
                    if (this.name == "kmlaonline-changeable-start")
                        removeStarted = true;
                }
            });
            document.title = s.state.ttitle;
            removeStarted = false;
            $("<div>" + s.state.head + "</div>").children().each(function (i) {
                if (removeStarted) {
                    $("head").append(this);
                } else {
                    if (this.name == "changeable-start")
                        removeStarted = true;
                }
            });
            $("#below-header-menu #total-content").empty();
            $("#below-header-menu #total-content").append($("<div>" + s.state.body + "</div>").children());
            $("#below-header-menu #total-header").empty();
            $("#below-header-menu #total-header").append($("<div>" + s.state.header + "</div>").children());
            $("#below-header-menu #total-footer").html("<div>" + s.state.footer + "</div>");
            $("#onload-scripts").html(s.state.script);
            eval(s.state.script);
            if (window.onload) window.onload();
            if (document.onload) document.onload();
            enableElement("#below-header-menu #total-content");
        }
        if (event && event.preventDefault) event.preventDefault();
        return false;
    });
}

function flashObject(elem, shortanim) {
    objid = "flashObject_" + randstring(512);
    notifier = $("<div id='" + objid + "' style='background:black;display:block;z-index:10;position:absolute;top:0;left:0;width:100%;'></div>");
    totalwrap = $("#total-wrap");
    totalwrap.prepend(notifier);
    notifier.css({opacity: 0, left: elem.offset().left - totalwrap.offset().left, top: elem.offset().top - totalwrap.offset().top});
    notifier.width(elem.width());
    notifier.height(elem.height());
    if (shortanim)
        setTimeout("$('#" + objid + "').remove();", 400);
    else
        setTimeout("$('#" + objid + "').remove();", 800);
    notifier.velocity({
        opacity: 1
    }, 200, function () {
        notifier.velocity({
            opacity: 0
        }, 200, function () {
            if (!shortanim)
                notifier.velocity({
                    opacity: 0.4
                }, 200, function () {
                    notifier.velocity({
                        opacity: 0
                    }, 200, function () {
                        notifier.remove();
                    });
                });
            else
                notifier.remove();
        });
    });
}
addLoadEvent(function () {
    $('a').each(function () {
        if (this.onclick || $(this).is('.clickbound') || this.href == "") return;
        if (this.href.toLowerCase().indexOf("http://" + location.host.toLowerCase() + "/files/") == 0 ||
            this.href.toLowerCase().indexOf("https://" + location.host.toLowerCase() + "/files/") == 0 ||
            this.href.toLowerCase().indexOf("http://" + location.host.toLowerCase() + "/data/") == 0 ||
            this.href.toLowerCase().indexOf("https://" + location.host.toLowerCase() + "/data/") == 0
        )
            return;
        $(this).off("click");
        $(this).click(function () {
            if (this.rel == "navigate") {
                loadUpperHeader(this.href, $("#upper-header-menu #total-content"), true);
                return false;
            }
            if (this.rel == "closenow") hideUpperHeader();
        });
    });
    $('form').each(function () {
        if (this.onsubmit) return;
        if (this.enctype == "multipart/form-data") return;
        $(this).off("submit");
        $(this).submit(function () {
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
    $("a.likebutton").bind("dragstart", function () {
        return false;
    }).bind("selectstart", function () {
        return false;
    });
    var elem = window.location.hash;
    if (elem && (elem = $(elem)) && elem.length > 0) {
        scrollToMiddle(elem.offset().top);
        flashObject(elem);
    }

    window.notificationCountGetter = function () {
        getNotificationCount();
        setTimeout(function () {
            window.notificationCountGetter
        }, 10000);
    };
    window.notificationCountGetter();
});
