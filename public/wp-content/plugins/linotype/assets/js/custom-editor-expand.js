! function(a, b, c) {
    "use strict";
    var d = b(a),
        e = b(document),
        f = b("#wpadminbar"),
        g = b("#wpfooter");
    b(function() {
        function c() {
            var a = d.width();
            T = {
                windowHeight: d.height(),
                windowWidth: a,
               	adminBarHeight: 0,
                toolsHeight: t.outerHeight() || 0,
                menuBarHeight: A.outerHeight() || 0,
                visualTopHeight: u.outerHeight() || 0,
                textTopHeight: w.outerHeight() || 0,
                bottomHeight: z.outerHeight() || 0,
                statusBarHeight: B.outerHeight() || 0,
                sideSortablesHeight: C.height() || 0
            }, T.menuBarHeight < 3 && (T.menuBarHeight = 0)
        }

        function h(b) {
            var c, d, e, f, g, h = jQuery.ui.keyCode,
                i = b.keyCode,
                j = document.createRange(),
                k = x[0].selectionStart,
                l = x[0].selectionEnd,
                m = y[0].firstChild,
                n = 10;
            if (!k || !l || k === l) {
                try {
                    j.setStart(m, k), j.setEnd(m, l + 1)
                } catch (o) {}
                c = j.getBoundingClientRect(), c.height && (d = c.top - n, e = d + c.height + n, f = T.adminBarHeight + T.toolsHeight + T.textTopHeight, g = T.windowHeight - T.bottomHeight, f > d && (i === h.UP || i === h.LEFT || i === h.BACKSPACE) ? a.scrollTo(a.pageXOffset, d + a.pageYOffset - f) : e > g && a.scrollTo(a.pageXOffset, e + a.pageYOffset - g))
            }
        }

        function i() {
            if (!(p && !p.isHidden() || !p && "tinymce" === R)) {
                var a, b = x.height();
                y.width(x.width() - 22), y.text(x.val() + "&nbsp;"), a = y.height(), Q > a && (a = Q), a !== b && (x.height(a), j())
            }
        }

        function j(b) {
            if (!F || !F.settings.visible) {
                var f, h, j, k, l, m, n, o, q, r = d.scrollTop(),
                    G = b && b.type,
                    H = "scroll" !== G,
                    N = p && !p.isHidden(),
                    R = Q,
                    U = E.offset().top,
                    V = 1,
                    W = s.width();
                (H || !T.windowHeight) && c(), N || "resize" !== G || i(), N ? (f = u, h = v, n = T.visualTopHeight) : (f = w, h = x, n = T.textTopHeight), (N || f.length) && (m = f.parent().offset().top, o = h.offset().top, q = h.outerHeight(), l = N ? Q + n : Q + 20, l = q > l + 5, l ? ((!I || H) && r >= m - T.toolsHeight - T.adminBarHeight && r <= m - T.toolsHeight - T.adminBarHeight + q - R ? (I = !0, t.css({
                    position: "fixed",
                    top: T.adminBarHeight,
                    width: W
                }), N && A.length && A.css({
                    position: "fixed",
                    top: T.adminBarHeight + T.toolsHeight,
                    width: W - 2 * V - (N ? 0 : f.outerWidth() - f.width())
                }), f.css({
                    position: "fixed",
                    top: T.adminBarHeight + T.toolsHeight + T.menuBarHeight,
                    width: W - 2 * V - (N ? 0 : f.outerWidth() - f.width())
                })) : (I || H) && (r <= m - T.toolsHeight - T.adminBarHeight ? (I = !1, t.css({
                    position: "absolute",
                    top: 0,
                    width: W
                }), N && A.length && A.css({
                    position: "absolute",
                    top: 0,
                    width: W - 2 * V
                }), f.css({
                    position: "absolute",
                    top: T.menuBarHeight,
                    width: W - 2 * V - (N ? 0 : f.outerWidth() - f.width())
                })) : r >= m - T.toolsHeight - T.adminBarHeight + q - R && (I = !1, t.css({
                    position: "absolute",
                    top: q - R,
                    width: W
                }), N && A.length && A.css({
                    position: "absolute",
                    top: q - R,
                    width: W - 2 * V
                }), f.css({
                    position: "absolute",
                    top: q - R + T.menuBarHeight,
                    width: W - 2 * V - (N ? 0 : f.outerWidth() - f.width())
                }))), (!J || H && S) && r + T.windowHeight <= o + q + T.bottomHeight + T.statusBarHeight + V ? b && b.deltaHeight > 0 && b.deltaHeight < 100 ? a.scrollBy(0, b.deltaHeight) : S && (J = !0, B.css({
                    position: "fixed",
                    bottom: T.bottomHeight,
                    visibility: "",
                    width: W - 2 * V
                }), z.css({
                    position: "fixed",
                    bottom: 0,
                    width: W
                })) : (!S && J || (J || H) && r + T.windowHeight > o + q + T.bottomHeight + T.statusBarHeight - V) && (J = !1, B.attr("style", S ? "" : "visibility: hidden;"), z.attr("style", ""))) : H && (t.css({
                    position: "absolute",
                    top: 0,
                    width: W
                }), N && A.length && A.css({
                    position: "absolute",
                    top: 0,
                    width: W - 2 * V
                }), f.css({
                    position: "absolute",
                    top: T.menuBarHeight,
                    width: W - 2 * V - (N ? 0 : f.outerWidth() - f.width())
                }), B.attr("style", S ? "" : "visibility: hidden;"), z.attr("style", "")), D.width() < 300 && T.windowWidth > 600 && e.height() > C.height() + U + 120 && T.windowHeight < q ? (T.sideSortablesHeight + O + P > T.windowHeight || K || L ? U >= r + O ? (C.attr("style", ""), K = L = !1) : r > M ? K ? (K = !1, j = C.offset().top - T.adminBarHeight, k = g.offset().top, k < j + T.sideSortablesHeight + P && (j = k - T.sideSortablesHeight - 12), C.css({
                    position: "absolute",
                    top: j,
                    bottom: ""
                })) : !L && T.sideSortablesHeight + C.offset().top + P < r + T.windowHeight && (L = !0, C.css({
                    position: "fixed",
                    top: "auto",
                    bottom: P
                })) : M > r && (L ? (L = !1, j = C.offset().top - P, k = g.offset().top, k < j + T.sideSortablesHeight + P && (j = k - T.sideSortablesHeight - 12), C.css({
                    position: "absolute",
                    top: j,
                    bottom: ""
                })) : !K && C.offset().top >= r + O && (K = !0, C.css({
                    position: "fixed",
                    top: O,
                    bottom: ""
                }))) : (r >= U - O ? C.css({
                    position: "fixed",
                    top: O
                }) : C.attr("style", ""), K = L = !1), M = r) : (C.attr("style", ""), K = L = !1), H && (s.css({
                    paddingTop: T.toolsHeight
                }), N ? v.css({
                    paddingTop: T.visualTopHeight + T.menuBarHeight
                }) : (x.css({
                    marginTop: T.textTopHeight
                }), y.width(W - 20 - 2 * V))))
            }
        }

        function k() {
            i(), j()
        }

        function l(a) {
            for (var b = 1; 6 > b; b++) setTimeout(a, 500 * b)
        }

        function m() {
            clearTimeout(q), q = setTimeout(j, 100)
        }

        function n() {
            a.pageYOffset && a.pageYOffset > N && a.scrollTo(a.pageXOffset, 0), r.addClass("wp-editor-expand"), d.on("scroll.editor-expand resize.editor-expand", function(a) {
                j(a.type), m()
            }), e.on("wp-collapse-menu.editor-expand postboxes-columnchange.editor-expand editor-classchange.editor-expand", j).on("postbox-toggled.editor-expand", function() {
                !K && !L && a.pageYOffset > O && (L = !0, a.scrollBy(0, -1), j(), a.scrollBy(0, 1)), j()
            }).on("wp-window-resized.editor-expand", function() {
                p && !p.isHidden() ? p.execCommand("wpAutoResize") : i()
            }), x.on("focus.editor-expand input.editor-expand propertychange.editor-expand", i), x.on("keyup.editor-expand", h), G(), F && F.pubsub.subscribe("hidden", k), p && (p.settings.wp_autoresize_on = !0, p.execCommand("wpAutoResizeOn"), p.isHidden() || p.execCommand("wpAutoResize")), (!p || p.isHidden()) && i(), j(), e.trigger("editor-expand-on")
        }

        function o() {
            var c = parseInt(a.getUserSetting("ed_size", 300), 10);
            50 > c ? c = 50 : c > 5e3 && (c = 5e3), a.pageYOffset && a.pageYOffset > N && a.scrollTo(a.pageXOffset, 0), r.removeClass("wp-editor-expand"), d.off(".editor-expand"), e.off(".editor-expand"), x.off(".editor-expand"), H(), F && F.pubsub.unsubscribe("hidden", k), b.each([u, w, t, A, z, B, s, v, x, C], function(a, b) {
                b && b.attr("style", "")
            }), I = J = K = L = !1, p && (p.settings.wp_autoresize_on = !1, p.execCommand("wpAutoResizeOff"), p.isHidden() || (x.hide(), c && p.theme.resizeTo(null, c))), c && x.height(c), e.trigger("editor-expand-off")
        }
        var p, q, r = b("#postdivrich"),
            s = b("#wp-content-wrap"),
            t = b("#wp-content-editor-tools"),
            u = b(),
            v = b(),
            w = b("#ed_toolbar"),
            x = b("#content"),
            y = b('<div id="content-textarea-clone" class="wp-exclude-emoji"></div>'),
            z = b("#post-status-info"),
            A = b(),
            B = b(),
            C = b("#side-sortables"),
            D = b("#postbox-container-1"),
            E = b("#post-body"),
            F = a.wp.editor && a.wp.editor.fullscreen,
            G = function() {},
            H = function() {},
            I = !1,
            J = !1,
            K = !1,
            L = !1,
            M = 0,
            N = 130,
            O = 56,
            P = 20,
            Q = 300,
            R = s.hasClass("tmce-active") ? "tinymce" : "html",
            S = !!parseInt(a.getUserSetting("hidetb"), 10),
            T = {
                windowHeight: 0,
                windowWidth: 0,
                adminBarHeight: 0,
                toolsHeight: 0,
                menuBarHeight: 0,
                visualTopHeight: 0,
                textTopHeight: 0,
                bottomHeight: 0,
                statusBarHeight: 0,
                sideSortablesHeight: 0
            };
        y.insertAfter(x), y.css({
            "font-family": x.css("font-family"),
            "font-size": x.css("font-size"),
            "line-height": x.css("line-height"),
            "white-space": "pre-wrap",
            "word-wrap": "break-word"
        }), e.on("tinymce-editor-init.editor-expand", function(c, e) {
            function f() {
                var a, b, c, d = e.selection.getNode();
                if (e.wp && e.wp.getView && (b = e.wp.getView(d))) c = b.getBoundingClientRect();
                else {
                    a = e.selection.getRng();
                    try {
                        c = a.getClientRects()[0]
                    } catch (f) {}
                    c || (c = d.getBoundingClientRect())
                }
                return c.height ? c : !1
            }

            function g(a) {
                var b = a.keyCode;
                47 >= b && b !== q.SPACEBAR && b !== q.ENTER && b !== q.DELETE && b !== q.BACKSPACE && b !== q.UP && b !== q.LEFT && b !== q.DOWN && b !== q.UP || b >= 91 && 93 >= b || b >= 112 && 123 >= b || 144 === b || 145 === b || h(b)
            }

            function h(b) {
                var c, d, g, h, i = f(),
                    j = 50;
                i && (c = i.top + e.iframeElement.getBoundingClientRect().top, d = c + i.height, c -= j, d += j, g = T.adminBarHeight + T.toolsHeight + T.menuBarHeight + T.visualTopHeight, h = T.windowHeight - (S ? T.bottomHeight + T.statusBarHeight : 0), h - g < i.height || (g > c && (b === q.UP || b === q.LEFT || b === q.BACKSPACE) ? a.scrollTo(a.pageXOffset, c + a.pageYOffset - g) : d > h && a.scrollTo(a.pageXOffset, d + a.pageYOffset - h)))
            }

            function k(a) {
                a.state || j()
            }

            function m() {
                d.on("scroll.mce-float-panels", t), setTimeout(function() {
                    e.execCommand("wpAutoResize"), j()
                }, 300)
            }

            function n() {
                d.off("scroll.mce-float-panels"), setTimeout(function() {
                    var b = s.offset().top;
                    a.pageYOffset > b && a.scrollTo(a.pageXOffset, b - T.adminBarHeight), i(), j()
                }, 100), j()
            }

            function o() {
                S = !S
            }
            var q = a.tinymce.util.VK,
                t = _.debounce(function() {
                    !b(".mce-floatpanel:hover").length && a.tinymce.ui.FloatPanel.hideAll(), b(".mce-tooltip").hide()
                }, 1e3, !0);
            "content" === e.id && (p = e, e.settings.autoresize_min_height = Q, u = s.find(".mce-toolbar-grp"), v = s.find(".mce-edit-area"), B = s.find(".mce-statusbar"), A = s.find(".mce-menubar"), G = function() {
                e.on("keyup", g), e.on("show", m), e.on("hide", n), e.on("wp-toolbar-toggle", o), e.on("setcontent wp-autoresize wp-toolbar-toggle", j), e.on("undo redo", h), e.on("FullscreenStateChanged", k), d.off("scroll.mce-float-panels").on("scroll.mce-float-panels", t)
            }, H = function() {
                e.off("keyup", g), e.off("show", m), e.off("hide", n), e.off("wp-toolbar-toggle", o), e.off("setcontent wp-autoresize wp-toolbar-toggle", j), e.off("undo redo", h), e.off("FullscreenStateChanged", k), d.off("scroll.mce-float-panels")
            }, r.hasClass("wp-editor-expand") && (G(), l(j)))
        }), r.hasClass("wp-editor-expand") && (n(), s.hasClass("html-active") && l(function() {
            j(), i()
        })), b("#adv-settings .editor-expand").show(), b("#editor-expand-toggle").on("change.editor-expand", function() {
            b(this).prop("checked") ? (n(), a.setUserSetting("editor_expand", "on")) : (o(), a.setUserSetting("editor_expand", "off"))
        }), a.editorExpand = {
            on: n,
            off: o
        }
    }), b(function() {
        function c() {
            z = J.offset(), z.right = z.left + J.outerWidth(), z.bottom = z.top + J.outerHeight()
        }

        function h() {
            S || (S = !0, e.trigger("dfw-activate"), L.on("keydown.focus-shortcut", v))
        }

        function i() {
            S && (l(), S = !1, e.trigger("dfw-deactivate"), L.off("keydown.focus-shortcut"))
        }

        function j() {
            return S
        }

        function k() {
            !T && S && (T = !0, L.on("keydown.focus", o), K.add(L).on("blur.focus", q), o(), a.setUserSetting("post_dfw", "on"), e.trigger("dfw-on"))
        }

        function l() {
            T && (T = !1, K.add(L).off(".focus"), p(), J.off(".focus"), a.setUserSetting("post_dfw", "off"), e.trigger("dfw-off"))
        }

        function m() {
            T ? l() : k()
        }

        function n() {
            return T
        }

        function o(b) {
            var e = b && b.keyCode;
            return 27 === e || 87 === e && b.altKey && b.shiftKey ? void p(b) : void(b && (b.metaKey || b.ctrlKey && !b.altKey || b.altKey && b.shiftKey || e && (47 >= e && 8 !== e && 13 !== e && 32 !== e && 46 !== e || e >= 91 && 93 >= e || e >= 112 && 135 >= e || e >= 144 && 150 >= e || e >= 224)) || (w || (w = !0, clearTimeout(F), F = setTimeout(function() {
                M.show()
            }, 600), J.css("z-index", 9998), M.on("mouseenter.focus", function() {
                c(), d.on("scroll.focus", function() {
                    var b = a.pageYOffset;
                    D && C && D !== b && (C < z.top - W || C > z.bottom + W) && p(), D = b
                })
            }).on("mouseleave.focus", function() {
                A = B = null, U = V = 0, d.off("scroll.focus")
            }).on("mousemove.focus", function(b) {
                var c = b.clientX,
                    d = b.clientY,
                    e = a.pageYOffset,
                    f = a.pageXOffset;
                if (A && B && (c !== A || d !== B))
                    if (B >= d && d < z.top - e || d >= B && d > z.bottom - e || A >= c && c < z.left - f || c >= A && c > z.right - f) {
                        if (U += Math.abs(A - c), V += Math.abs(B - d), (d <= z.top - W - e || d >= z.bottom + W - e || c <= z.left - W - f || c >= z.right + W - f) && (U > 10 || V > 10)) return p(), A = B = null, void(U = V = 0)
                    } else U = V = 0;
                A = c, B = d
            }).on("touchstart.focus", function(a) {
                a.preventDefault(), p()
            }), J.off("mouseenter.focus"), E && (clearTimeout(E), E = null), H.addClass("focus-on").removeClass("focus-off")), r(), t()))
        }

        function p(a) {
            w && (w = !1, clearTimeout(F), F = setTimeout(function() {
                M.hide()
            }, 200), J.css("z-index", ""), M.off("mouseenter.focus mouseleave.focus mousemove.focus touchstart.focus"), "undefined" == typeof a && J.on("mouseenter.focus", function() {
                (b.contains(J.get(0), document.activeElement) || G) && o()
            }), E = setTimeout(function() {
                E = null, J.off("mouseenter.focus")
            }, 1e3), H.addClass("focus-off").removeClass("focus-on")), s(), u()
        }

        function q() {
            setTimeout(function() {
                function a(a) {
                    return b.contains(a.get(0), document.activeElement)
                }
                var c = document.activeElement.compareDocumentPosition(J.get(0));
                2 !== c && 4 !== c || !(a(P) || a(I) || a(g)) || p()
            }, 0)
        }

        function r() {
            !x && w && (x = !0, f.on("mouseenter.focus", function() {
                f.addClass("focus-off")
            }).on("mouseleave.focus", function() {
                f.removeClass("focus-off")
            }))
        }

        function s() {
            x && (x = !1, f.off(".focus"))
        }

        function t() {
            y || !w || N.find(":focus").length || (y = !0, N.stop().fadeTo("fast", .3).on("mouseenter.focus", u).off("mouseleave.focus"), O.on("focus.focus", u).off("blur.focus"))
        }

        function u() {
            y && (y = !1, N.stop().fadeTo("fast", 1).on("mouseleave.focus", t).off("mouseenter.focus"), O.on("blur.focus", t).off("focus.focus"))
        }

        function v(a) {
            a.altKey && a.shiftKey && 87 === a.keyCode && m()
        }
        var w, x, y, z, A, B, C, D, E, F, G, H = b(document.body),
            I = b("#wpcontent"),
            J = b("#post-body-content"),
            K = b("#title"),
            L = b("#content"),
            M = b(document.createElement("DIV")),
            N = b("#edit-slug-box"),
            O = N.find("a").add(N.find("button")).add(N.find("input")),
            P = b("#adminmenuwrap"),
            Q = b(),
            R = b(),
            S = "on" === a.getUserSetting("editor_expand", "on"),
            T = S ? "on" === a.getUserSetting("post_dfw") : !1,
            U = 0,
            V = 0,
            W = 20;
        H.append(M), M.css({
            display: "none",
            position: "fixed",
            top: f.height(),
            right: 0,
            bottom: 0,
            left: 0,
            "z-index": 9997
        }), J.css({
            position: "relative"
        }), d.on("mousemove.focus", function(a) {
            C = a.pageY
        }), b("#postdivrich").hasClass("wp-editor-expand") && L.on("keydown.focus-shortcut", v), e.on("tinymce-editor-setup.focus", function(a, b) {
            b.addButton("dfw", {
                active: T,
                classes: "wp-dfw btn widget",
                disabled: !S,
                onclick: m,
                onPostRender: function() {
                    var a = this;
                    e.on("dfw-activate.focus", function() {
                        a.disabled(!1)
                    }).on("dfw-deactivate.focus", function() {
                        a.disabled(!0)
                    }).on("dfw-on.focus", function() {
                        a.active(!0)
                    }).on("dfw-off.focus", function() {
                        a.active(!1)
                    })
                },
                tooltip: "Distraction-free writing mode",
                shortcut: "Alt+Shift+W"
            }), b.addCommand("wpToggleDFW", m), b.addShortcut("alt+shift+w", "", "wpToggleDFW")
        }), e.on("tinymce-editor-init.focus", function(a, d) {
            function f() {
                G = !0
            }

            function g() {
                G = !1
            }
            var h, i;
            "content" === d.id && (Q = b(d.getWin()), R = b(d.getContentAreaContainer()).find("iframe"), h = function() {
                d.on("keydown", o), d.on("blur", q), d.on("focus", f), d.on("blur", g), d.on("wp-autoresize", c)
            }, i = function() {
                d.off("keydown", o), d.off("blur", q), d.off("focus", f), d.off("blur", g), d.off("wp-autoresize", c)
            }, T && h(), e.on("dfw-on.focus", h).on("dfw-off.focus", i), d.on("click", function(a) {
                a.target === d.getDoc().documentElement && d.focus()
            }))
        }), e.on("quicktags-init", function(a, c) {
            var d;
            c.settings.buttons && -1 !== ("," + c.settings.buttons + ",").indexOf(",dfw,") && (d = b("#" + c.name + "_dfw"), b(document).on("dfw-activate", function() {
                d.prop("disabled", !1)
            }).on("dfw-deactivate", function() {
                d.prop("disabled", !0)
            }).on("dfw-on", function() {
                d.addClass("active")
            }).on("dfw-off", function() {
                d.removeClass("active")
            }))
        }), e.on("editor-expand-on.focus", h).on("editor-expand-off.focus", i), T && (L.on("keydown.focus", o), K.add(L).on("blur.focus", q)), a.wp = a.wp || {}, a.wp.editor = a.wp.editor || {}, a.wp.editor.dfw = {
            activate: h,
            deactivate: i,
            isActive: j,
            on: k,
            off: l,
            toggle: m,
            isOn: n
        }
    })
}(window, window.jQuery);