function serializeArray(elem) {
    var brokenSerialization = elem.serializeArray();
    var checkboxValues = elem.find('input[type=checkbox]').map(function () {
        return { 'name': this.name, 'value': this.checked };
    }).get();
    var checkboxKeys = $.map(checkboxValues, function (element) { return element.name; });
    var withoutCheckboxes = $.grep(brokenSerialization, function (element) {
        return $.inArray(element.name, checkboxKeys) == -1;
    });

    return $.merge(withoutCheckboxes, checkboxValues);
}

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

function simpleModuleToObject(module) {
    var result = new Object();
    result.name = module.data("module-name");
    result.options = new Object();
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

function rebindModules(mobile) {
    bindModuleReloadButton(mobile);
    if(!mobile) {
        bindModuleCloseButton();
        bindOptionsForm();
        $('.selectpicker').selectpicker();
    }
    if(editMode && !mobile) {
        $(".main-block-close").css("display", "inline-block");
        $(".main-block-options").css("display", "inline-block");
        $(".main-block-close").css({width: 28, "border-width": 1, opacity: 1});
        $(".main-block-options").css({width: 28, "border-width": 1, opacity: 1});
        $(".main-block-reload").css({width: 28, "border-radius": 0});
        $(".main-block-title").css("cursor", "move");
    }
}

function bindAddModuleButton() {
    $("#add-module").on('change', function(event) {
        var name = $("#add-module").val();
        $.post("ajax/user/getmoduledefaults", {
            "name": name,
            ajax: 1
        }, function (data) {
            var grid = $('.grid-stack').data('gridstack');
            var newModule = $(data).wrap("<div class='grid-stack-item-content'></div>").parent()
                .wrap("<div class='grid-stack-item'></div>").parent();
            newModule.attr("data-module-name", name).attr("data-module-options", "[]");
            grid.add_widget(newModule, 0, 0, 4, 4).velocity("transition.slideDownIn", {display: null, duration: 300});
            rebindModules();
        });
        $("#add-module").val('');
        return false;
    });
}
function bindExampleLayoutButton() {
    $("#example-layout").on('change', function(event) {
        (new PNotify({
            title: '정말로 예시 레이아웃을 적용할까요?',
            text: '지금 사용중인 레이아웃은 초기화됩니다.',
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
        })).get().on('pnotify.confirm', function () {
            $.post("ajax/user/examplelayout", {
                    "name": $("#example-layout").val(),
                    ajax: 1
            }).done(function () {
                $(".grid-stack-item").velocity("transition.slideUpOut", {stagger: 50, duration: 300, complete: function() {
                    location.reload(true);
                }});
            });
        });
    });
}

function backupLayout() {
    $.post("ajax/user/backuplayout", {
            ajax: 1
    }).done(function () {
        new PNotify({
            title: '백업이 성공했습니다.',
            text: '마지막 저장한 상태로 백업되었습니다.',
            type: 'success',
            buttons: {
                closer: false,
                sticker: false
            }
        });
    });
}

function restoreLayout() {
    (new PNotify({
        title: '레이아웃을 복구할까요?',
        text: '레이아웃이 마지막 백업 상태로 되돌려집니다.',
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
    })).get().on('pnotify.confirm', function () {
        $.post("ajax/user/restorelayout", {
                ajax: 1
        }).done(function () {
            $(".grid-stack-item").velocity("transition.slideUpOut", {stagger: 50, duration: 300, complete: function() {
                location.reload(true);
            }});
        });
    });
}

function bindModuleCloseButton() {
    $(".main-block-close").unbind("click").click(function () {
        var module = $(this).closest(".grid-stack-item");
        module.velocity("transition.slideUpOut", {display: null, duration: 300, complete: function() {
            $('.grid-stack').data('gridstack').remove_widget(module);
        }});
    });
}

function bindModuleReloadButton(mobile) {
    $(".main-block-reload").unbind("click").click(function () {
        var module = $(this).closest(".grid-stack-item");
        module.velocity("transition.slideUpOut", {display: null, duration: 300});
        $.post(mobile ? "ajax/user/getmodulelite" : "ajax/user/getmodule", {
            "json": JSON.stringify(moduleToObject(module)),
            "ajax": 1
        }, function (data) {
            module.velocity("transition.slideUpIn", {display: null, duration: 300});
            module.find(".grid-stack-item-content").html(data);
            rebindModules(mobile);
            if(mobile)
                return;
            new PNotify({
                title: '새로고침 했습니다.',
                type: 'success',
                buttons: {
                    closer: false,
                    sticker: false
                }
            });
        });
    });
}

function reloadAllModules(light) {
    $('.grid-stack-item').each(function() {
        var module = $(this);
        $.post(light ? "ajax/user/getmodulelite" : "ajax/user/getmodule", {
            "json": JSON.stringify(simpleModuleToObject(module)),
            "ajax": 1
        }, function (data) {
            module.velocity("transition.slideUpIn", {display: null, duration: 300});
            module.find(".grid-stack-item-content").html(data);
            rebindModules(light);
        });
    });
}

function saveOptionsForm(form) {
    var module = form.closest(".grid-stack-item");
    var options = module.data("module-options");
    var catFirst = true;
    $(serializeArray($(form))).each(function(i, field) {
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
        module.find(".grid-stack-item-content").html(data);
        rebindModules();
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
    })).get().on('pnotify.confirm', function () {
        $.post("ajax/user/resetlayout", {
                "ajax": "1"
            })
            .done(function () {
                $(".grid-stack-item").velocity("transition.slideUpOut", {stagger: 50, duration: 300, complete: function() {
                    location.reload(true);
                }});
            });
    });
}

function cancelLayout() {
    (new PNotify({
        title: '레이아웃 변경사항 취소',
        text: '레이아웃을 마지막으로 저장된 상태로 되돌릴까요?',
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
    })).get().on('pnotify.confirm', function () {
                location.reload(true);
    });
}

function updateModules() {
    $.post("ajax/user/updatelayout", {
            "json": mainGridToJSON(),
            "ajax": "1"
        }).done(function () {
            var notice = new PNotify({
                title: '레이아웃이 저장되었습니다!',
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
                title: '레이아웃을 저장하지 못했습니다. 인터넷 연결을 확인하세요.',
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
        var newModule = $(data).wrap("<div class='grid-stack-item-content'></div>");
        newModule.attr("data-module-name", dat["name"]).attr("data-module-options", JSON.stringify(dat["options"]["options"]));
        grid.add_widget(newModule, 0, 0, 4, 4).velocity("transition.slideDownIn", {display: null, duration: 300});
        rebindModules();
    });
}

var editMode = false;
function toggleLayoutEditing() {
    if(!editMode) {
        $("#main-edit-button").html("저장하고 편집 모드 종료");
        $("#main-edit-pane").velocity("slideDown", {duration: 100, complete: function() {
            $(".main-block-close").css({display: "inline-block", "border-width": "1px"});
            $(".main-block-options").css({display: "inline-block", "border-width": "1px"});
            $(".main-block-close").velocity({width: 28, opacity: 1}, 200);
            $(".main-block-options").velocity({width: 28, opacity: 1}, 200);
            $(".main-block-reload").velocity({width: 28, "border-radius": 0}, 200);
            $(".main-block-title").css("cursor", "move");
            $('.grid-stack').data('gridstack').enable();
        }});
    } else {
        $("#main-edit-button").html("편집 모드 시작");
        updateModules();
        $("#main-edit-pane").velocity("slideUp", {duration: 100, complete: function() {
            $(".main-block-close").velocity({width: 0, opacity: 0}, 200, function() {$(this).css({display: "none", "border-width": "0"})});
            $(".main-block-options").velocity({width: 0, opacity: 0}, 200, function() {$(this).css({display: "none", "border-width": "0"})});
            $(".main-block-reload").velocity({width: 24, "border-radius": 12}, 200);
            $(".main-block-title").css("cursor", "default");
        }});
        $('.grid-stack').data('gridstack').disable();
    }
    editMode = !editMode;
}

function saveTheme() {
    var options = {};
    options['dark'] = false;
    options['square'] = $("#square-option").parent().hasClass("active");
    options['gradients'] = $("#gradients-option").parent().hasClass("active");
    $.post("ajax/user/savetheme", {
        "json": JSON.stringify(options),
        "ajax": 1
    }, function (data) {
        new PNotify({
            title: '테마가 저장되었습니다!',
            text: '새로고침하면 적용됩니다.',
            type: 'success',
            buttons: {
                closer: false,
                sticker: false
            }
        });
    });
}

var themeMode = false;
function toggleThemeEditing() {
    if(!themeMode) {
        $("#main-theme-pane").velocity("slideDown", {duration: 200, easing: "easeOutCubic"});
        $("#main-theme-button").html("테마 설정 저장");
    } else {
        $("#main-theme-pane").velocity("slideUp", {duration: 200, easing: "easeOutCubic"});
        $("#main-theme-button").html("테마 설정");
        saveTheme();
    }
    themeMode = !themeMode;
}
