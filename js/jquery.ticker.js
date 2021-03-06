(function (a) {
    a.fn.Vnewsticker = function (b) {
        var c = {
            speed: 700,
            pause: 4000,
            showItems: 3,
            mousePause: true,
            isPaused: false,
            direction: "up",
            height: 0
        };
        b = a.extend(c, b);
        var moveUp = function (g, d, e) {
            if (e.isPaused) {
                return;
            }
            var f = g.children("ul");
            var h = f.children("li:first").clone(true);
            if (e.height > 0) {
                d = f.children("li:first").height();
            }
            f.animate({
                    top: "-=" + d + "px"
                },
                e.speed,
                function () {
                    a(this).children("li:first").remove();
                    a(this).css("top", "0px");
                });
            h.appendTo(f);
        };
        moveDown = function (g, d, e) {
            if (e.isPaused)
                return;
            var f = g.children("ul"),
                h = f.children("li:last").clone(true);
            if (e.height > 0) d = f.children("li:first").height();
            f.css("top", "-" + d + "px").prepend(h);
            f.animate({
                    top: 0
                },
                e.speed,
                function () {
                    a(this).children("li:last").remove();
                });
        };
        return this.each(function () {
            var f = a(this),
                e = 0;
            f.css({
                overflow: "hidden",
            }).children("ul").css({
                position: "absolute",
                margin: 0,
                padding: 0
            }).children("li").css({
                margin: 0
            });
            if (b.height == 0) {
                f.children("ul").children("li").each(function () {
                    if (a(this).height() > e) {
                        e = a(this).height();
                    }
                });
                f.children("ul").children("li").each(function () {
                    a(this).height(e);
                });
                f.height(e * b.showItems);
            } else {
                f.height(b.height);
            }
            var d = setInterval(function () {
                    moveUp(f, e, b);
                },
                b.pause);
            if (b.mousePause) {
                f.bind("mouseenter", function () {
                    b.isPaused = true;
                }).bind("mouseleave", function () {
                    b.isPaused = false;
                });
            }
        });
    };
}(jQuery));
