"use strict";

function _classCallCheck(e, t) {
    if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
}

function _classCallCheck(e, t) {
    if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
}

function _classCallCheck(e, t) {
    if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
}

function _classCallCheck(e, t) {
    if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
}

function _classCallCheck(e, t) {
    if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
}

function _classCallCheck(e, t) {
    if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
}

var _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (e) {
    return typeof e
} : function (e) {
    return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
};
!function (e, t) {
    "object" === ("undefined" == typeof module ? "undefined" : _typeof(module)) && "object" === _typeof(module.exports) ? module.exports = e.document ? t(e, !0) : function (e) {
        if (!e.document) throw new Error("jQuery requires a window with a document");
        return t(e)
    } : t(e)
}("undefined" != typeof window ? window : void 0, function (e, t) {
    function n(e, t) {
        t = t || ne;
        var n = t.createElement("script");
        n.text = e, t.head.appendChild(n).parentNode.removeChild(n)
    }

    function i(e) {
        var t = !!e && "length" in e && e.length, n = ge.type(e);
        return "function" !== n && !ge.isWindow(e) && ("array" === n || 0 === t || "number" == typeof t && t > 0 && t - 1 in e)
    }

    function o(e, t) {
        return e.nodeName && e.nodeName.toLowerCase() === t.toLowerCase()
    }

    function s(e, t, n) {
        return ge.isFunction(t) ? ge.grep(e, function (e, i) {
            return !!t.call(e, i, e) !== n
        }) : t.nodeType ? ge.grep(e, function (e) {
            return e === t !== n
        }) : "string" != typeof t ? ge.grep(e, function (e) {
            return ae.call(t, e) > -1 !== n
        }) : Ce.test(t) ? ge.filter(t, e, n) : (t = ge.filter(t, e), ge.grep(e, function (e) {
            return ae.call(t, e) > -1 !== n && 1 === e.nodeType
        }))
    }

    function r(e, t) {
        for (; (e = e[t]) && 1 !== e.nodeType;) ;
        return e
    }

    function a(e) {
        var t = {};
        return ge.each(e.match(He) || [], function (e, n) {
            t[n] = !0
        }), t
    }

    function l(e) {
        return e
    }

    function c(e) {
        throw e
    }

    function d(e, t, n, i) {
        var o;
        try {
            e && ge.isFunction(o = e.promise) ? o.call(e).done(t).fail(n) : e && ge.isFunction(o = e.then) ? o.call(e, t, n) : t.apply(void 0, [e].slice(i))
        } catch (e) {
            n.apply(void 0, [e])
        }
    }

    function u() {
        ne.removeEventListener("DOMContentLoaded", u), e.removeEventListener("load", u), ge.ready()
    }

    function p() {
        this.expando = ge.expando + p.uid++
    }

    function f(e) {
        return "true" === e || "false" !== e && ("null" === e ? null : e === +e + "" ? +e : Ne.test(e) ? JSON.parse(e) : e)
    }

    function h(e, t, n) {
        var i;
        if (void 0 === n && 1 === e.nodeType) if (i = "data-" + t.replace(qe, "-$&").toLowerCase(), n = e.getAttribute(i), "string" == typeof n) {
            try {
                n = f(n)
            } catch (o) {
            }
            Pe.set(e, t, n)
        } else n = void 0;
        return n
    }

    function g(e, t, n, i) {
        var o, s = 1, r = 20, a = i ? function () {
                return i.cur()
            } : function () {
                return ge.css(e, t, "")
            }, l = a(), c = n && n[3] || (ge.cssNumber[t] ? "" : "px"),
            d = (ge.cssNumber[t] || "px" !== c && +l) && Re.exec(ge.css(e, t));
        if (d && d[3] !== c) {
            c = c || d[3], n = n || [], d = +l || 1;
            do s = s || ".5", d /= s, ge.style(e, t, d + c); while (s !== (s = a() / l) && 1 !== s && --r)
        }
        return n && (d = +d || +l || 0, o = n[1] ? d + (n[1] + 1) * n[2] : +n[2], i && (i.unit = c, i.start = d, i.end = o)), o
    }

    function v(e) {
        var t, n = e.ownerDocument, i = e.nodeName, o = Ue[i];
        return o ? o : (t = n.body.appendChild(n.createElement(i)), o = ge.css(t, "display"), t.parentNode.removeChild(t), "none" === o && (o = "block"), Ue[i] = o, o)
    }

    function m(e, t) {
        for (var n, i, o = [], s = 0, r = e.length; s < r; s++) i = e[s], i.style && (n = i.style.display, t ? ("none" === n && (o[s] = Fe.get(i, "display") || null, o[s] || (i.style.display = "")), "" === i.style.display && We(i) && (o[s] = v(i))) : "none" !== n && (o[s] = "none", Fe.set(i, "display", n)));
        for (s = 0; s < r; s++) null != o[s] && (e[s].style.display = o[s]);
        return e
    }

    function y(e, t) {
        var n;
        return n = "undefined" != typeof e.getElementsByTagName ? e.getElementsByTagName(t || "*") : "undefined" != typeof e.querySelectorAll ? e.querySelectorAll(t || "*") : [], void 0 === t || t && o(e, t) ? ge.merge([e], n) : n
    }

    function w(e, t) {
        for (var n = 0, i = e.length; n < i; n++) Fe.set(e[n], "globalEval", !t || Fe.get(t[n], "globalEval"))
    }

    function b(e, t, n, i, o) {
        for (var s, r, a, l, c, d, u = t.createDocumentFragment(), p = [], f = 0, h = e.length; f < h; f++) if (s = e[f], s || 0 === s) if ("object" === ge.type(s)) ge.merge(p, s.nodeType ? [s] : s); else if (Ge.test(s)) {
            for (r = r || u.appendChild(t.createElement("div")), a = (Ye.exec(s) || ["", ""])[1].toLowerCase(), l = Qe[a] || Qe._default, r.innerHTML = l[1] + ge.htmlPrefilter(s) + l[2], d = l[0]; d--;) r = r.lastChild;
            ge.merge(p, r.childNodes), r = u.firstChild, r.textContent = ""
        } else p.push(t.createTextNode(s));
        for (u.textContent = "", f = 0; s = p[f++];) if (i && ge.inArray(s, i) > -1) o && o.push(s); else if (c = ge.contains(s.ownerDocument, s), r = y(u.appendChild(s), "script"), c && w(r), n) for (d = 0; s = r[d++];) Ke.test(s.type || "") && n.push(s);
        return u
    }

    function k() {
        return !0
    }

    function T() {
        return !1
    }

    function x() {
        try {
            return ne.activeElement
        } catch (e) {
        }
    }

    function S(e, t, n, i, o, s) {
        var r, a;
        if ("object" === ("undefined" == typeof t ? "undefined" : _typeof(t))) {
            "string" != typeof n && (i = i || n, n = void 0);
            for (a in t) S(e, a, n, i, t[a], s);
            return e
        }
        if (null == i && null == o ? (o = n, i = n = void 0) : null == o && ("string" == typeof n ? (o = i, i = void 0) : (o = i, i = n, n = void 0)), o === !1) o = T; else if (!o) return e;
        return 1 === s && (r = o, o = function (e) {
            return ge().off(e), r.apply(this, arguments)
        }, o.guid = r.guid || (r.guid = ge.guid++)), e.each(function () {
            ge.event.add(this, t, o, i, n)
        })
    }

    function C(e, t) {
        return o(e, "table") && o(11 !== t.nodeType ? t : t.firstChild, "tr") ? ge(">tbody", e)[0] || e : e
    }

    function $(e) {
        return e.type = (null !== e.getAttribute("type")) + "/" + e.type, e
    }

    function A(e) {
        var t = ot.exec(e.type);
        return t ? e.type = t[1] : e.removeAttribute("type"), e
    }

    function _(e, t) {
        var n, i, o, s, r, a, l, c;
        if (1 === t.nodeType) {
            if (Fe.hasData(e) && (s = Fe.access(e), r = Fe.set(t, s), c = s.events)) {
                delete r.handle, r.events = {};
                for (o in c) for (n = 0, i = c[o].length; n < i; n++) ge.event.add(t, o, c[o][n])
            }
            Pe.hasData(e) && (a = Pe.access(e), l = ge.extend({}, a), Pe.set(t, l))
        }
    }

    function E(e, t) {
        var n = t.nodeName.toLowerCase();
        "input" === n && Xe.test(e.type) ? t.checked = e.checked : "input" !== n && "textarea" !== n || (t.defaultValue = e.defaultValue)
    }

    function D(e, t, i, o) {
        t = se.apply([], t);
        var s, r, a, l, c, d, u = 0, p = e.length, f = p - 1, h = t[0], g = ge.isFunction(h);
        if (g || p > 1 && "string" == typeof h && !fe.checkClone && it.test(h)) return e.each(function (n) {
            var s = e.eq(n);
            g && (t[0] = h.call(this, n, s.html())), D(s, t, i, o)
        });
        if (p && (s = b(t, e[0].ownerDocument, !1, e, o), r = s.firstChild, 1 === s.childNodes.length && (s = r), r || o)) {
            for (a = ge.map(y(s, "script"), $), l = a.length; u < p; u++) c = s, u !== f && (c = ge.clone(c, !0, !0), l && ge.merge(a, y(c, "script"))), i.call(e[u], c, u);
            if (l) for (d = a[a.length - 1].ownerDocument, ge.map(a, A), u = 0; u < l; u++) c = a[u], Ke.test(c.type || "") && !Fe.access(c, "globalEval") && ge.contains(d, c) && (c.src ? ge._evalUrl && ge._evalUrl(c.src) : n(c.textContent.replace(st, ""), d))
        }
        return e
    }

    function H(e, t, n) {
        for (var i, o = t ? ge.filter(t, e) : e, s = 0; null != (i = o[s]); s++) n || 1 !== i.nodeType || ge.cleanData(y(i)), i.parentNode && (n && ge.contains(i.ownerDocument, i) && w(y(i, "script")), i.parentNode.removeChild(i));
        return e
    }

    function O(e, t, n) {
        var i, o, s, r, a = e.style;
        return n = n || lt(e), n && (r = n.getPropertyValue(t) || n[t], "" !== r || ge.contains(e.ownerDocument, e) || (r = ge.style(e, t)), !fe.pixelMarginRight() && at.test(r) && rt.test(t) && (i = a.width, o = a.minWidth, s = a.maxWidth, a.minWidth = a.maxWidth = a.width = r, r = n.width, a.width = i, a.minWidth = o, a.maxWidth = s)), void 0 !== r ? r + "" : r
    }

    function z(e, t) {
        return {
            get: function () {
                return e() ? void delete this.get : (this.get = t).apply(this, arguments)
            }
        }
    }

    function L(e) {
        if (e in ht) return e;
        for (var t = e[0].toUpperCase() + e.slice(1), n = ft.length; n--;) if (e = ft[n] + t, e in ht) return e
    }

    function j(e) {
        var t = ge.cssProps[e];
        return t || (t = ge.cssProps[e] = L(e) || e), t
    }

    function F(e, t, n) {
        var i = Re.exec(t);
        return i ? Math.max(0, i[2] - (n || 0)) + (i[3] || "px") : t
    }

    function P(e, t, n, i, o) {
        var s, r = 0;
        for (s = n === (i ? "border" : "content") ? 4 : "width" === t ? 1 : 0; s < 4; s += 2) "margin" === n && (r += ge.css(e, n + Ie[s], !0, o)), i ? ("content" === n && (r -= ge.css(e, "padding" + Ie[s], !0, o)), "margin" !== n && (r -= ge.css(e, "border" + Ie[s] + "Width", !0, o))) : (r += ge.css(e, "padding" + Ie[s], !0, o), "padding" !== n && (r += ge.css(e, "border" + Ie[s] + "Width", !0, o)));
        return r
    }

    function N(e, t, n) {
        var i, o = lt(e), s = O(e, t, o), r = "border-box" === ge.css(e, "boxSizing", !1, o);
        return at.test(s) ? s : (i = r && (fe.boxSizingReliable() || s === e.style[t]), "auto" === s && (s = e["offset" + t[0].toUpperCase() + t.slice(1)]), s = parseFloat(s) || 0, s + P(e, t, n || (r ? "border" : "content"), i, o) + "px")
    }

    function q(e, t, n, i, o) {
        return new q.prototype.init(e, t, n, i, o)
    }

    function M() {
        vt && (ne.hidden === !1 && e.requestAnimationFrame ? e.requestAnimationFrame(M) : e.setTimeout(M, ge.fx.interval), ge.fx.tick())
    }

    function R() {
        return e.setTimeout(function () {
            gt = void 0
        }), gt = ge.now()
    }

    function I(e, t) {
        var n, i = 0, o = {height: e};
        for (t = t ? 1 : 0; i < 4; i += 2 - t) n = Ie[i], o["margin" + n] = o["padding" + n] = e;
        return t && (o.opacity = o.width = e), o
    }

    function W(e, t, n) {
        for (var i, o = (X.tweeners[t] || []).concat(X.tweeners["*"]), s = 0, r = o.length; s < r; s++) if (i = o[s].call(n, t, e)) return i
    }

    function B(e, t, n) {
        var i, o, s, r, a, l, c, d, u = "width" in t || "height" in t, p = this, f = {}, h = e.style,
            g = e.nodeType && We(e), v = Fe.get(e, "fxshow");
        n.queue || (r = ge._queueHooks(e, "fx"), null == r.unqueued && (r.unqueued = 0, a = r.empty.fire, r.empty.fire = function () {
            r.unqueued || a()
        }), r.unqueued++, p.always(function () {
            p.always(function () {
                r.unqueued--, ge.queue(e, "fx").length || r.empty.fire()
            })
        }));
        for (i in t) if (o = t[i], mt.test(o)) {
            if (delete t[i], s = s || "toggle" === o, o === (g ? "hide" : "show")) {
                if ("show" !== o || !v || void 0 === v[i]) continue;
                g = !0
            }
            f[i] = v && v[i] || ge.style(e, i)
        }
        if (l = !ge.isEmptyObject(t), l || !ge.isEmptyObject(f)) {
            u && 1 === e.nodeType && (n.overflow = [h.overflow, h.overflowX, h.overflowY], c = v && v.display, null == c && (c = Fe.get(e, "display")), d = ge.css(e, "display"), "none" === d && (c ? d = c : (m([e], !0), c = e.style.display || c, d = ge.css(e, "display"), m([e]))), ("inline" === d || "inline-block" === d && null != c) && "none" === ge.css(e, "float") && (l || (p.done(function () {
                h.display = c
            }), null == c && (d = h.display, c = "none" === d ? "" : d)), h.display = "inline-block")), n.overflow && (h.overflow = "hidden", p.always(function () {
                h.overflow = n.overflow[0], h.overflowX = n.overflow[1], h.overflowY = n.overflow[2]
            })), l = !1;
            for (i in f) l || (v ? "hidden" in v && (g = v.hidden) : v = Fe.access(e, "fxshow", {display: c}), s && (v.hidden = !g), g && m([e], !0), p.done(function () {
                g || m([e]), Fe.remove(e, "fxshow");
                for (i in f) ge.style(e, i, f[i])
            })), l = W(g ? v[i] : 0, i, p), i in v || (v[i] = l.start, g && (l.end = l.start, l.start = 0))
        }
    }

    function U(e, t) {
        var n, i, o, s, r;
        for (n in e) if (i = ge.camelCase(n), o = t[i], s = e[n], Array.isArray(s) && (o = s[1], s = e[n] = s[0]), n !== i && (e[i] = s, delete e[n]), r = ge.cssHooks[i], r && "expand" in r) {
            s = r.expand(s), delete e[i];
            for (n in s) n in e || (e[n] = s[n], t[n] = o)
        } else t[i] = o
    }

    function X(e, t, n) {
        var i, o, s = 0, r = X.prefilters.length, a = ge.Deferred().always(function () {
            delete l.elem
        }), l = function () {
            if (o) return !1;
            for (var t = gt || R(), n = Math.max(0, c.startTime + c.duration - t), i = n / c.duration || 0, s = 1 - i, r = 0, l = c.tweens.length; r < l; r++) c.tweens[r].run(s);
            return a.notifyWith(e, [c, s, n]), s < 1 && l ? n : (l || a.notifyWith(e, [c, 1, 0]), a.resolveWith(e, [c]), !1)
        }, c = a.promise({
            elem: e,
            props: ge.extend({}, t),
            opts: ge.extend(!0, {specialEasing: {}, easing: ge.easing._default}, n),
            originalProperties: t,
            originalOptions: n,
            startTime: gt || R(),
            duration: n.duration,
            tweens: [],
            createTween: function (t, n) {
                var i = ge.Tween(e, c.opts, t, n, c.opts.specialEasing[t] || c.opts.easing);
                return c.tweens.push(i), i
            },
            stop: function (t) {
                var n = 0, i = t ? c.tweens.length : 0;
                if (o) return this;
                for (o = !0; n < i; n++) c.tweens[n].run(1);
                return t ? (a.notifyWith(e, [c, 1, 0]), a.resolveWith(e, [c, t])) : a.rejectWith(e, [c, t]), this
            }
        }), d = c.props;
        for (U(d, c.opts.specialEasing); s < r; s++) if (i = X.prefilters[s].call(c, e, d, c.opts)) return ge.isFunction(i.stop) && (ge._queueHooks(c.elem, c.opts.queue).stop = ge.proxy(i.stop, i)), i;
        return ge.map(d, W, c), ge.isFunction(c.opts.start) && c.opts.start.call(e, c), c.progress(c.opts.progress).done(c.opts.done, c.opts.complete).fail(c.opts.fail).always(c.opts.always), ge.fx.timer(ge.extend(l, {
            elem: e,
            anim: c,
            queue: c.opts.queue
        })), c
    }

    function Y(e) {
        var t = e.match(He) || [];
        return t.join(" ")
    }

    function K(e) {
        return e.getAttribute && e.getAttribute("class") || ""
    }

    function Q(e, t, n, i) {
        var o;
        if (Array.isArray(t)) ge.each(t, function (t, o) {
            n || _t.test(e) ? i(e, o) : Q(e + "[" + ("object" === ("undefined" == typeof o ? "undefined" : _typeof(o)) && null != o ? t : "") + "]", o, n, i)
        }); else if (n || "object" !== ge.type(t)) i(e, t); else for (o in t) Q(e + "[" + o + "]", t[o], n, i)
    }

    function G(e) {
        return function (t, n) {
            "string" != typeof t && (n = t, t = "*");
            var i, o = 0, s = t.toLowerCase().match(He) || [];
            if (ge.isFunction(n)) for (; i = s[o++];) "+" === i[0] ? (i = i.slice(1) || "*", (e[i] = e[i] || []).unshift(n)) : (e[i] = e[i] || []).push(n)
        }
    }

    function V(e, t, n, i) {
        function o(a) {
            var l;
            return s[a] = !0, ge.each(e[a] || [], function (e, a) {
                var c = a(t, n, i);
                return "string" != typeof c || r || s[c] ? r ? !(l = c) : void 0 : (t.dataTypes.unshift(c), o(c), !1)
            }), l
        }

        var s = {}, r = e === Mt;
        return o(t.dataTypes[0]) || !s["*"] && o("*")
    }

    function J(e, t) {
        var n, i, o = ge.ajaxSettings.flatOptions || {};
        for (n in t) void 0 !== t[n] && ((o[n] ? e : i || (i = {}))[n] = t[n]);
        return i && ge.extend(!0, e, i), e
    }

    function Z(e, t, n) {
        for (var i, o, s, r, a = e.contents, l = e.dataTypes; "*" === l[0];) l.shift(), void 0 === i && (i = e.mimeType || t.getResponseHeader("Content-Type"));
        if (i) for (o in a) if (a[o] && a[o].test(i)) {
            l.unshift(o);
            break
        }
        if (l[0] in n) s = l[0]; else {
            for (o in n) {
                if (!l[0] || e.converters[o + " " + l[0]]) {
                    s = o;
                    break
                }
                r || (r = o)
            }
            s = s || r
        }
        if (s) return s !== l[0] && l.unshift(s), n[s]
    }

    function ee(e, t, n, i) {
        var o, s, r, a, l, c = {}, d = e.dataTypes.slice();
        if (d[1]) for (r in e.converters) c[r.toLowerCase()] = e.converters[r];
        for (s = d.shift(); s;) if (e.responseFields[s] && (n[e.responseFields[s]] = t), !l && i && e.dataFilter && (t = e.dataFilter(t, e.dataType)), l = s, s = d.shift()) if ("*" === s) s = l; else if ("*" !== l && l !== s) {
            if (r = c[l + " " + s] || c["* " + s], !r) for (o in c) if (a = o.split(" "), a[1] === s && (r = c[l + " " + a[0]] || c["* " + a[0]])) {
                r === !0 ? r = c[o] : c[o] !== !0 && (s = a[0], d.unshift(a[1]));
                break
            }
            if (r !== !0) if (r && e["throws"]) t = r(t); else try {
                t = r(t)
            } catch (u) {
                return {state: "parsererror", error: r ? u : "No conversion from " + l + " to " + s}
            }
        }
        return {state: "success", data: t}
    }

    var te = [], ne = e.document, ie = Object.getPrototypeOf, oe = te.slice, se = te.concat, re = te.push,
        ae = te.indexOf, le = {}, ce = le.toString, de = le.hasOwnProperty, ue = de.toString, pe = ue.call(Object),
        fe = {}, he = "3.2.1", ge = function Qt(e, t) {
            return new Qt.fn.init(e, t)
        }, ve = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, me = /^-ms-/, ye = /-([a-z])/g, we = function (e, t) {
            return t.toUpperCase()
        };
    ge.fn = ge.prototype = {
        jquery: he, constructor: ge, length: 0, toArray: function () {
            return oe.call(this)
        }, get: function (e) {
            return null == e ? oe.call(this) : e < 0 ? this[e + this.length] : this[e]
        }, pushStack: function (e) {
            var t = ge.merge(this.constructor(), e);
            return t.prevObject = this, t
        }, each: function (e) {
            return ge.each(this, e)
        }, map: function (e) {
            return this.pushStack(ge.map(this, function (t, n) {
                return e.call(t, n, t)
            }))
        }, slice: function () {
            return this.pushStack(oe.apply(this, arguments))
        }, first: function () {
            return this.eq(0)
        }, last: function () {
            return this.eq(-1)
        }, eq: function (e) {
            var t = this.length, n = +e + (e < 0 ? t : 0);
            return this.pushStack(n >= 0 && n < t ? [this[n]] : [])
        }, end: function () {
            return this.prevObject || this.constructor()
        }, push: re, sort: te.sort, splice: te.splice
    }, ge.extend = ge.fn.extend = function () {
        var e, t, n, i, o, s, r = arguments[0] || {}, a = 1, l = arguments.length, c = !1;
        for ("boolean" == typeof r && (c = r, r = arguments[a] || {}, a++), "object" === ("undefined" == typeof r ? "undefined" : _typeof(r)) || ge.isFunction(r) || (r = {}), a === l && (r = this, a--); a < l; a++) if (null != (e = arguments[a])) for (t in e) n = r[t], i = e[t], r !== i && (c && i && (ge.isPlainObject(i) || (o = Array.isArray(i))) ? (o ? (o = !1, s = n && Array.isArray(n) ? n : []) : s = n && ge.isPlainObject(n) ? n : {}, r[t] = ge.extend(c, s, i)) : void 0 !== i && (r[t] = i));
        return r
    }, ge.extend({
        expando: "jQuery" + (he + Math.random()).replace(/\D/g, ""), isReady: !0, error: function (e) {
            throw new Error(e)
        }, noop: function () {
        }, isFunction: function (e) {
            return "function" === ge.type(e)
        }, isWindow: function (e) {
            return null != e && e === e.window
        }, isNumeric: function (e) {
            var t = ge.type(e);
            return ("number" === t || "string" === t) && !isNaN(e - parseFloat(e))
        }, isPlainObject: function (e) {
            var t, n;
            return !(!e || "[object Object]" !== ce.call(e)) && (!(t = ie(e)) || (n = de.call(t, "constructor") && t.constructor, "function" == typeof n && ue.call(n) === pe))
        }, isEmptyObject: function (e) {
            var t;
            for (t in e) return !1;
            return !0
        }, type: function (e) {
            return null == e ? e + "" : "object" === ("undefined" == typeof e ? "undefined" : _typeof(e)) || "function" == typeof e ? le[ce.call(e)] || "object" : "undefined" == typeof e ? "undefined" : _typeof(e)
        }, globalEval: function (e) {
            n(e)
        }, camelCase: function (e) {
            return e.replace(me, "ms-").replace(ye, we)
        }, each: function (e, t) {
            var n, o = 0;
            if (i(e)) for (n = e.length; o < n && t.call(e[o], o, e[o]) !== !1; o++) ; else for (o in e) if (t.call(e[o], o, e[o]) === !1) break;
            return e
        }, trim: function (e) {
            return null == e ? "" : (e + "").replace(ve, "")
        }, makeArray: function (e, t) {
            var n = t || [];
            return null != e && (i(Object(e)) ? ge.merge(n, "string" == typeof e ? [e] : e) : re.call(n, e)), n
        }, inArray: function (e, t, n) {
            return null == t ? -1 : ae.call(t, e, n)
        }, merge: function (e, t) {
            for (var n = +t.length, i = 0, o = e.length; i < n; i++) e[o++] = t[i];
            return e.length = o, e
        }, grep: function (e, t, n) {
            for (var i, o = [], s = 0, r = e.length, a = !n; s < r; s++) i = !t(e[s], s), i !== a && o.push(e[s]);
            return o
        }, map: function (e, t, n) {
            var o, s, r = 0, a = [];
            if (i(e)) for (o = e.length; r < o; r++) s = t(e[r], r, n), null != s && a.push(s); else for (r in e) s = t(e[r], r, n), null != s && a.push(s);
            return se.apply([], a)
        }, guid: 1, proxy: function Gt(e, t) {
            var n, i, Gt;
            if ("string" == typeof t && (n = e[t], t = e, e = n), ge.isFunction(e)) return i = oe.call(arguments, 2), Gt = function () {
                return e.apply(t || this, i.concat(oe.call(arguments)))
            }, Gt.guid = e.guid = e.guid || ge.guid++, Gt
        }, now: Date.now, support: fe
    }), "function" == typeof Symbol && (ge.fn[Symbol.iterator] = te[Symbol.iterator]), ge.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "), function (e, t) {
        le["[object " + t + "]"] = t.toLowerCase()
    });
    var be = function (e) {
        function t(e, t, n, i) {
            var o, s, r, a, l, c, d, p = t && t.ownerDocument, h = t ? t.nodeType : 9;
            if (n = n || [], "string" != typeof e || !e || 1 !== h && 9 !== h && 11 !== h) return n;
            if (!i && ((t ? t.ownerDocument || t : R) !== z && O(t), t = t || z, j)) {
                if (11 !== h && (l = me.exec(e))) if (o = l[1]) {
                    if (9 === h) {
                        if (!(r = t.getElementById(o))) return n;
                        if (r.id === o) return n.push(r), n
                    } else if (p && (r = p.getElementById(o)) && q(t, r) && r.id === o) return n.push(r), n
                } else {
                    if (l[2]) return J.apply(n, t.getElementsByTagName(e)), n;
                    if ((o = l[3]) && T.getElementsByClassName && t.getElementsByClassName) return J.apply(n, t.getElementsByClassName(o)), n
                }
                if (T.qsa && !X[e + " "] && (!F || !F.test(e))) {
                    if (1 !== h) p = t, d = e; else if ("object" !== t.nodeName.toLowerCase()) {
                        for ((a = t.getAttribute("id")) ? a = a.replace(ke, Te) : t.setAttribute("id", a = M), c = $(e), s = c.length; s--;) c[s] = "#" + a + " " + f(c[s]);
                        d = c.join(","), p = ye.test(e) && u(t.parentNode) || t
                    }
                    if (d) try {
                        return J.apply(n, p.querySelectorAll(d)), n
                    } catch (g) {
                    } finally {
                        a === M && t.removeAttribute("id")
                    }
                }
            }
            return _(e.replace(ae, "$1"), t, n, i)
        }

        function n() {
            function e(n, i) {
                return t.push(n + " ") > x.cacheLength && delete e[t.shift()], e[n + " "] = i
            }

            var t = [];
            return e
        }

        function i(e) {
            return e[M] = !0, e
        }

        function o(e) {
            var t = z.createElement("fieldset");
            try {
                return !!e(t)
            } catch (n) {
                return !1
            } finally {
                t.parentNode && t.parentNode.removeChild(t), t = null
            }
        }

        function s(e, t) {
            for (var n = e.split("|"), i = n.length; i--;) x.attrHandle[n[i]] = t
        }

        function r(e, t) {
            var n = t && e, i = n && 1 === e.nodeType && 1 === t.nodeType && e.sourceIndex - t.sourceIndex;
            if (i) return i;
            if (n) for (; n = n.nextSibling;) if (n === t) return -1;
            return e ? 1 : -1
        }

        function a(e) {
            return function (t) {
                var n = t.nodeName.toLowerCase();
                return "input" === n && t.type === e
            }
        }

        function l(e) {
            return function (t) {
                var n = t.nodeName.toLowerCase();
                return ("input" === n || "button" === n) && t.type === e
            }
        }

        function c(e) {
            return function (t) {
                return "form" in t ? t.parentNode && t.disabled === !1 ? "label" in t ? "label" in t.parentNode ? t.parentNode.disabled === e : t.disabled === e : t.isDisabled === e || t.isDisabled !== !e && Se(t) === e : t.disabled === e : "label" in t && t.disabled === e
            }
        }

        function d(e) {
            return i(function (t) {
                return t = +t, i(function (n, i) {
                    for (var o, s = e([], n.length, t), r = s.length; r--;) n[o = s[r]] && (n[o] = !(i[o] = n[o]))
                })
            })
        }

        function u(e) {
            return e && "undefined" != typeof e.getElementsByTagName && e
        }

        function p() {
        }

        function f(e) {
            for (var t = 0, n = e.length, i = ""; t < n; t++) i += e[t].value;
            return i
        }

        function h(e, t, n) {
            var i = t.dir, o = t.next, s = o || i, r = n && "parentNode" === s, a = W++;
            return t.first ? function (t, n, o) {
                for (; t = t[i];) if (1 === t.nodeType || r) return e(t, n, o);
                return !1
            } : function (t, n, l) {
                var c, d, u, p = [I, a];
                if (l) {
                    for (; t = t[i];) if ((1 === t.nodeType || r) && e(t, n, l)) return !0
                } else for (; t = t[i];) if (1 === t.nodeType || r) if (u = t[M] || (t[M] = {}), d = u[t.uniqueID] || (u[t.uniqueID] = {}), o && o === t.nodeName.toLowerCase()) t = t[i] || t; else {
                    if ((c = d[s]) && c[0] === I && c[1] === a) return p[2] = c[2];
                    if (d[s] = p, p[2] = e(t, n, l)) return !0
                }
                return !1
            }
        }

        function g(e) {
            return e.length > 1 ? function (t, n, i) {
                for (var o = e.length; o--;) if (!e[o](t, n, i)) return !1;
                return !0
            } : e[0]
        }

        function v(e, n, i) {
            for (var o = 0, s = n.length; o < s; o++) t(e, n[o], i);
            return i
        }

        function m(e, t, n, i, o) {
            for (var s, r = [], a = 0, l = e.length, c = null != t; a < l; a++) (s = e[a]) && (n && !n(s, i, o) || (r.push(s), c && t.push(a)));
            return r
        }

        function y(e, t, n, o, s, r) {
            return o && !o[M] && (o = y(o)), s && !s[M] && (s = y(s, r)), i(function (i, r, a, l) {
                var c, d, u, p = [], f = [], h = r.length, g = i || v(t || "*", a.nodeType ? [a] : a, []),
                    y = !e || !i && t ? g : m(g, p, e, a, l), w = n ? s || (i ? e : h || o) ? [] : r : y;
                if (n && n(y, w, a, l), o) for (c = m(w, f), o(c, [], a, l), d = c.length; d--;) (u = c[d]) && (w[f[d]] = !(y[f[d]] = u));
                if (i) {
                    if (s || e) {
                        if (s) {
                            for (c = [], d = w.length; d--;) (u = w[d]) && c.push(y[d] = u);
                            s(null, w = [], c, l)
                        }
                        for (d = w.length; d--;) (u = w[d]) && (c = s ? ee(i, u) : p[d]) > -1 && (i[c] = !(r[c] = u))
                    }
                } else w = m(w === r ? w.splice(h, w.length) : w), s ? s(null, r, w, l) : J.apply(r, w)
            })
        }

        function w(e) {
            for (var t, n, i, o = e.length, s = x.relative[e[0].type], r = s || x.relative[" "], a = s ? 1 : 0, l = h(function (e) {
                return e === t
            }, r, !0), c = h(function (e) {
                return ee(t, e) > -1
            }, r, !0), d = [function (e, n, i) {
                var o = !s && (i || n !== E) || ((t = n).nodeType ? l(e, n, i) : c(e, n, i));
                return t = null, o
            }]; a < o; a++) if (n = x.relative[e[a].type]) d = [h(g(d), n)]; else {
                if (n = x.filter[e[a].type].apply(null, e[a].matches), n[M]) {
                    for (i = ++a; i < o && !x.relative[e[i].type]; i++) ;
                    return y(a > 1 && g(d), a > 1 && f(e.slice(0, a - 1).concat({value: " " === e[a - 2].type ? "*" : ""})).replace(ae, "$1"), n, a < i && w(e.slice(a, i)), i < o && w(e = e.slice(i)), i < o && f(e))
                }
                d.push(n)
            }
            return g(d)
        }

        function b(e, n) {
            var o = n.length > 0, s = e.length > 0, r = function (i, r, a, l, c) {
                var d, u, p, f = 0, h = "0", g = i && [], v = [], y = E, w = i || s && x.find.TAG("*", c),
                    b = I += null == y ? 1 : Math.random() || .1, k = w.length;
                for (c && (E = r === z || r || c); h !== k && null != (d = w[h]); h++) {
                    if (s && d) {
                        for (u = 0, r || d.ownerDocument === z || (O(d), a = !j); p = e[u++];) if (p(d, r || z, a)) {
                            l.push(d);
                            break
                        }
                        c && (I = b)
                    }
                    o && ((d = !p && d) && f--, i && g.push(d))
                }
                if (f += h, o && h !== f) {
                    for (u = 0; p = n[u++];) p(g, v, r, a);
                    if (i) {
                        if (f > 0) for (; h--;) g[h] || v[h] || (v[h] = G.call(l));
                        v = m(v)
                    }
                    J.apply(l, v), c && !i && v.length > 0 && f + n.length > 1 && t.uniqueSort(l)
                }
                return c && (I = b, E = y), g
            };
            return o ? i(r) : r
        }

        var k, T, x, S, C, $, A, _, E, D, H, O, z, L, j, F, P, N, q, M = "sizzle" + 1 * new Date, R = e.document, I = 0,
            W = 0, B = n(), U = n(), X = n(), Y = function (e, t) {
                return e === t && (H = !0), 0
            }, K = {}.hasOwnProperty, Q = [], G = Q.pop, V = Q.push, J = Q.push, Z = Q.slice, ee = function (e, t) {
                for (var n = 0, i = e.length; n < i; n++) if (e[n] === t) return n;
                return -1
            },
            te = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",
            ne = "[\\x20\\t\\r\\n\\f]", ie = "(?:\\\\.|[\\w-]|[^\0-\\xa0])+",
            oe = "\\[" + ne + "*(" + ie + ")(?:" + ne + "*([*^$|!~]?=)" + ne + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + ie + "))|)" + ne + "*\\]",
            se = ":(" + ie + ")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|" + oe + ")*)|.*)\\)|)",
            re = new RegExp(ne + "+", "g"), ae = new RegExp("^" + ne + "+|((?:^|[^\\\\])(?:\\\\.)*)" + ne + "+$", "g"),
            le = new RegExp("^" + ne + "*," + ne + "*"), ce = new RegExp("^" + ne + "*([>+~]|" + ne + ")" + ne + "*"),
            de = new RegExp("=" + ne + "*([^\\]'\"]*?)" + ne + "*\\]", "g"), ue = new RegExp(se),
            pe = new RegExp("^" + ie + "$"), fe = {
                ID: new RegExp("^#(" + ie + ")"),
                CLASS: new RegExp("^\\.(" + ie + ")"),
                TAG: new RegExp("^(" + ie + "|[*])"),
                ATTR: new RegExp("^" + oe),
                PSEUDO: new RegExp("^" + se),
                CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + ne + "*(even|odd|(([+-]|)(\\d*)n|)" + ne + "*(?:([+-]|)" + ne + "*(\\d+)|))" + ne + "*\\)|)", "i"),
                bool: new RegExp("^(?:" + te + ")$", "i"),
                needsContext: new RegExp("^" + ne + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + ne + "*((?:-\\d)?\\d*)" + ne + "*\\)|)(?=[^-]|$)", "i")
            }, he = /^(?:input|select|textarea|button)$/i, ge = /^h\d$/i, ve = /^[^{]+\{\s*\[native \w/,
            me = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/, ye = /[+~]/,
            we = new RegExp("\\\\([\\da-f]{1,6}" + ne + "?|(" + ne + ")|.)", "ig"), be = function (e, t, n) {
                var i = "0x" + t - 65536;
                return i !== i || n ? t : i < 0 ? String.fromCharCode(i + 65536) : String.fromCharCode(i >> 10 | 55296, 1023 & i | 56320)
            }, ke = /([\0-\x1f\x7f]|^-?\d)|^-$|[^\0-\x1f\x7f-\uFFFF\w-]/g, Te = function (e, t) {
                return t ? "\0" === e ? "�" : e.slice(0, -1) + "\\" + e.charCodeAt(e.length - 1).toString(16) + " " : "\\" + e
            }, xe = function () {
                O()
            }, Se = h(function (e) {
                return e.disabled === !0 && ("form" in e || "label" in e)
            }, {dir: "parentNode", next: "legend"});
        try {
            J.apply(Q = Z.call(R.childNodes), R.childNodes), Q[R.childNodes.length].nodeType
        } catch (Ce) {
            J = {
                apply: Q.length ? function (e, t) {
                    V.apply(e, Z.call(t))
                } : function (e, t) {
                    for (var n = e.length, i = 0; e[n++] = t[i++];) ;
                    e.length = n - 1
                }
            }
        }
        T = t.support = {}, C = t.isXML = function (e) {
            var t = e && (e.ownerDocument || e).documentElement;
            return !!t && "HTML" !== t.nodeName
        }, O = t.setDocument = function (e) {
            var t, n, i = e ? e.ownerDocument || e : R;
            return i !== z && 9 === i.nodeType && i.documentElement ? (z = i, L = z.documentElement, j = !C(z), R !== z && (n = z.defaultView) && n.top !== n && (n.addEventListener ? n.addEventListener("unload", xe, !1) : n.attachEvent && n.attachEvent("onunload", xe)), T.attributes = o(function (e) {
                return e.className = "i", !e.getAttribute("className")
            }), T.getElementsByTagName = o(function (e) {
                return e.appendChild(z.createComment("")), !e.getElementsByTagName("*").length
            }), T.getElementsByClassName = ve.test(z.getElementsByClassName), T.getById = o(function (e) {
                return L.appendChild(e).id = M, !z.getElementsByName || !z.getElementsByName(M).length
            }), T.getById ? (x.filter.ID = function (e) {
                var t = e.replace(we, be);
                return function (e) {
                    return e.getAttribute("id") === t
                }
            }, x.find.ID = function (e, t) {
                if ("undefined" != typeof t.getElementById && j) {
                    var n = t.getElementById(e);
                    return n ? [n] : []
                }
            }) : (x.filter.ID = function (e) {
                var t = e.replace(we, be);
                return function (e) {
                    var n = "undefined" != typeof e.getAttributeNode && e.getAttributeNode("id");
                    return n && n.value === t
                }
            }, x.find.ID = function (e, t) {
                if ("undefined" != typeof t.getElementById && j) {
                    var n, i, o, s = t.getElementById(e);
                    if (s) {
                        if (n = s.getAttributeNode("id"), n && n.value === e) return [s];
                        for (o = t.getElementsByName(e), i = 0; s = o[i++];) if (n = s.getAttributeNode("id"), n && n.value === e) return [s]
                    }
                    return []
                }
            }), x.find.TAG = T.getElementsByTagName ? function (e, t) {
                return "undefined" != typeof t.getElementsByTagName ? t.getElementsByTagName(e) : T.qsa ? t.querySelectorAll(e) : void 0
            } : function (e, t) {
                var n, i = [], o = 0, s = t.getElementsByTagName(e);
                if ("*" === e) {
                    for (; n = s[o++];) 1 === n.nodeType && i.push(n);
                    return i
                }
                return s
            }, x.find.CLASS = T.getElementsByClassName && function (e, t) {
                if ("undefined" != typeof t.getElementsByClassName && j) return t.getElementsByClassName(e)
            }, P = [], F = [], (T.qsa = ve.test(z.querySelectorAll)) && (o(function (e) {
                L.appendChild(e).innerHTML = "<a id='" + M + "'></a><select id='" + M + "-\r\\' msallowcapture=''><option selected=''></option></select>", e.querySelectorAll("[msallowcapture^='']").length && F.push("[*^$]=" + ne + "*(?:''|\"\")"), e.querySelectorAll("[selected]").length || F.push("\\[" + ne + "*(?:value|" + te + ")"), e.querySelectorAll("[id~=" + M + "-]").length || F.push("~="), e.querySelectorAll(":checked").length || F.push(":checked"), e.querySelectorAll("a#" + M + "+*").length || F.push(".#.+[+~]")
            }), o(function (e) {
                e.innerHTML = "<a href='' disabled='disabled'></a><select disabled='disabled'><option/></select>";
                var t = z.createElement("input");
                t.setAttribute("type", "hidden"), e.appendChild(t).setAttribute("name", "D"), e.querySelectorAll("[name=d]").length && F.push("name" + ne + "*[*^$|!~]?="), 2 !== e.querySelectorAll(":enabled").length && F.push(":enabled", ":disabled"), L.appendChild(e).disabled = !0, 2 !== e.querySelectorAll(":disabled").length && F.push(":enabled", ":disabled"), e.querySelectorAll("*,:x"), F.push(",.*:")
            })), (T.matchesSelector = ve.test(N = L.matches || L.webkitMatchesSelector || L.mozMatchesSelector || L.oMatchesSelector || L.msMatchesSelector)) && o(function (e) {
                T.disconnectedMatch = N.call(e, "*"), N.call(e, "[s!='']:x"), P.push("!=", se)
            }), F = F.length && new RegExp(F.join("|")), P = P.length && new RegExp(P.join("|")), t = ve.test(L.compareDocumentPosition), q = t || ve.test(L.contains) ? function (e, t) {
                var n = 9 === e.nodeType ? e.documentElement : e, i = t && t.parentNode;
                return e === i || !(!i || 1 !== i.nodeType || !(n.contains ? n.contains(i) : e.compareDocumentPosition && 16 & e.compareDocumentPosition(i)))
            } : function (e, t) {
                if (t) for (; t = t.parentNode;) if (t === e) return !0;
                return !1
            }, Y = t ? function (e, t) {
                if (e === t) return H = !0, 0;
                var n = !e.compareDocumentPosition - !t.compareDocumentPosition;
                return n ? n : (n = (e.ownerDocument || e) === (t.ownerDocument || t) ? e.compareDocumentPosition(t) : 1, 1 & n || !T.sortDetached && t.compareDocumentPosition(e) === n ? e === z || e.ownerDocument === R && q(R, e) ? -1 : t === z || t.ownerDocument === R && q(R, t) ? 1 : D ? ee(D, e) - ee(D, t) : 0 : 4 & n ? -1 : 1)
            } : function (e, t) {
                if (e === t) return H = !0, 0;
                var n, i = 0, o = e.parentNode, s = t.parentNode, a = [e], l = [t];
                if (!o || !s) return e === z ? -1 : t === z ? 1 : o ? -1 : s ? 1 : D ? ee(D, e) - ee(D, t) : 0;
                if (o === s) return r(e, t);
                for (n = e; n = n.parentNode;) a.unshift(n);
                for (n = t; n = n.parentNode;) l.unshift(n);
                for (; a[i] === l[i];) i++;
                return i ? r(a[i], l[i]) : a[i] === R ? -1 : l[i] === R ? 1 : 0
            }, z) : z
        }, t.matches = function (e, n) {
            return t(e, null, null, n)
        }, t.matchesSelector = function (e, n) {
            if ((e.ownerDocument || e) !== z && O(e), n = n.replace(de, "='$1']"), T.matchesSelector && j && !X[n + " "] && (!P || !P.test(n)) && (!F || !F.test(n))) try {
                var i = N.call(e, n);
                if (i || T.disconnectedMatch || e.document && 11 !== e.document.nodeType) return i
            } catch (o) {
            }
            return t(n, z, null, [e]).length > 0
        }, t.contains = function (e, t) {
            return (e.ownerDocument || e) !== z && O(e), q(e, t)
        }, t.attr = function (e, t) {
            (e.ownerDocument || e) !== z && O(e);
            var n = x.attrHandle[t.toLowerCase()],
                i = n && K.call(x.attrHandle, t.toLowerCase()) ? n(e, t, !j) : void 0;
            return void 0 !== i ? i : T.attributes || !j ? e.getAttribute(t) : (i = e.getAttributeNode(t)) && i.specified ? i.value : null
        }, t.escape = function (e) {
            return (e + "").replace(ke, Te)
        }, t.error = function (e) {
            throw new Error("Syntax error, unrecognized expression: " + e)
        }, t.uniqueSort = function (e) {
            var t, n = [], i = 0, o = 0;
            if (H = !T.detectDuplicates, D = !T.sortStable && e.slice(0), e.sort(Y), H) {
                for (; t = e[o++];) t === e[o] && (i = n.push(o));
                for (; i--;) e.splice(n[i], 1)
            }
            return D = null, e
        }, S = t.getText = function (e) {
            var t, n = "", i = 0, o = e.nodeType;
            if (o) {
                if (1 === o || 9 === o || 11 === o) {
                    if ("string" == typeof e.textContent) return e.textContent;
                    for (e = e.firstChild; e; e = e.nextSibling) n += S(e)
                } else if (3 === o || 4 === o) return e.nodeValue
            } else for (; t = e[i++];) n += S(t);
            return n
        }, x = t.selectors = {
            cacheLength: 50,
            createPseudo: i,
            match: fe,
            attrHandle: {},
            find: {},
            relative: {
                ">": {dir: "parentNode", first: !0},
                " ": {dir: "parentNode"},
                "+": {dir: "previousSibling", first: !0},
                "~": {dir: "previousSibling"}
            },
            preFilter: {
                ATTR: function (e) {
                    return e[1] = e[1].replace(we, be), e[3] = (e[3] || e[4] || e[5] || "").replace(we, be), "~=" === e[2] && (e[3] = " " + e[3] + " "), e.slice(0, 4)
                }, CHILD: function (e) {
                    return e[1] = e[1].toLowerCase(), "nth" === e[1].slice(0, 3) ? (e[3] || t.error(e[0]), e[4] = +(e[4] ? e[5] + (e[6] || 1) : 2 * ("even" === e[3] || "odd" === e[3])), e[5] = +(e[7] + e[8] || "odd" === e[3])) : e[3] && t.error(e[0]), e
                }, PSEUDO: function (e) {
                    var t, n = !e[6] && e[2];
                    return fe.CHILD.test(e[0]) ? null : (e[3] ? e[2] = e[4] || e[5] || "" : n && ue.test(n) && (t = $(n, !0)) && (t = n.indexOf(")", n.length - t) - n.length) && (e[0] = e[0].slice(0, t), e[2] = n.slice(0, t)), e.slice(0, 3))
                }
            },
            filter: {
                TAG: function (e) {
                    var t = e.replace(we, be).toLowerCase();
                    return "*" === e ? function () {
                        return !0
                    } : function (e) {
                        return e.nodeName && e.nodeName.toLowerCase() === t
                    }
                }, CLASS: function (e) {
                    var t = B[e + " "];
                    return t || (t = new RegExp("(^|" + ne + ")" + e + "(" + ne + "|$)")) && B(e, function (e) {
                        return t.test("string" == typeof e.className && e.className || "undefined" != typeof e.getAttribute && e.getAttribute("class") || "")
                    })
                }, ATTR: function (e, n, i) {
                    return function (o) {
                        var s = t.attr(o, e);
                        return null == s ? "!=" === n : !n || (s += "", "=" === n ? s === i : "!=" === n ? s !== i : "^=" === n ? i && 0 === s.indexOf(i) : "*=" === n ? i && s.indexOf(i) > -1 : "$=" === n ? i && s.slice(-i.length) === i : "~=" === n ? (" " + s.replace(re, " ") + " ").indexOf(i) > -1 : "|=" === n && (s === i || s.slice(0, i.length + 1) === i + "-"))
                    }
                }, CHILD: function (e, t, n, i, o) {
                    var s = "nth" !== e.slice(0, 3), r = "last" !== e.slice(-4), a = "of-type" === t;
                    return 1 === i && 0 === o ? function (e) {
                        return !!e.parentNode
                    } : function (t, n, l) {
                        var c, d, u, p, f, h, g = s !== r ? "nextSibling" : "previousSibling", v = t.parentNode,
                            m = a && t.nodeName.toLowerCase(), y = !l && !a, w = !1;
                        if (v) {
                            if (s) {
                                for (; g;) {
                                    for (p = t; p = p[g];) if (a ? p.nodeName.toLowerCase() === m : 1 === p.nodeType) return !1;
                                    h = g = "only" === e && !h && "nextSibling"
                                }
                                return !0
                            }
                            if (h = [r ? v.firstChild : v.lastChild], r && y) {
                                for (p = v, u = p[M] || (p[M] = {}), d = u[p.uniqueID] || (u[p.uniqueID] = {}), c = d[e] || [], f = c[0] === I && c[1], w = f && c[2], p = f && v.childNodes[f]; p = ++f && p && p[g] || (w = f = 0) || h.pop();) if (1 === p.nodeType && ++w && p === t) {
                                    d[e] = [I, f, w];
                                    break
                                }
                            } else if (y && (p = t, u = p[M] || (p[M] = {}), d = u[p.uniqueID] || (u[p.uniqueID] = {}), c = d[e] || [], f = c[0] === I && c[1], w = f), w === !1) for (; (p = ++f && p && p[g] || (w = f = 0) || h.pop()) && ((a ? p.nodeName.toLowerCase() !== m : 1 !== p.nodeType) || !++w || (y && (u = p[M] || (p[M] = {}), d = u[p.uniqueID] || (u[p.uniqueID] = {}),
                                d[e] = [I, w]), p !== t));) ;
                            return w -= o, w === i || w % i === 0 && w / i >= 0
                        }
                    }
                }, PSEUDO: function (e, n) {
                    var o, s = x.pseudos[e] || x.setFilters[e.toLowerCase()] || t.error("unsupported pseudo: " + e);
                    return s[M] ? s(n) : s.length > 1 ? (o = [e, e, "", n], x.setFilters.hasOwnProperty(e.toLowerCase()) ? i(function (e, t) {
                        for (var i, o = s(e, n), r = o.length; r--;) i = ee(e, o[r]), e[i] = !(t[i] = o[r])
                    }) : function (e) {
                        return s(e, 0, o)
                    }) : s
                }
            },
            pseudos: {
                not: i(function (e) {
                    var t = [], n = [], o = A(e.replace(ae, "$1"));
                    return o[M] ? i(function (e, t, n, i) {
                        for (var s, r = o(e, null, i, []), a = e.length; a--;) (s = r[a]) && (e[a] = !(t[a] = s))
                    }) : function (e, i, s) {
                        return t[0] = e, o(t, null, s, n), t[0] = null, !n.pop()
                    }
                }), has: i(function (e) {
                    return function (n) {
                        return t(e, n).length > 0
                    }
                }), contains: i(function (e) {
                    return e = e.replace(we, be), function (t) {
                        return (t.textContent || t.innerText || S(t)).indexOf(e) > -1
                    }
                }), lang: i(function (e) {
                    return pe.test(e || "") || t.error("unsupported lang: " + e), e = e.replace(we, be).toLowerCase(), function (t) {
                        var n;
                        do if (n = j ? t.lang : t.getAttribute("xml:lang") || t.getAttribute("lang")) return n = n.toLowerCase(), n === e || 0 === n.indexOf(e + "-"); while ((t = t.parentNode) && 1 === t.nodeType);
                        return !1
                    }
                }), target: function (t) {
                    var n = e.location && e.location.hash;
                    return n && n.slice(1) === t.id
                }, root: function (e) {
                    return e === L
                }, focus: function (e) {
                    return e === z.activeElement && (!z.hasFocus || z.hasFocus()) && !!(e.type || e.href || ~e.tabIndex)
                }, enabled: c(!1), disabled: c(!0), checked: function (e) {
                    var t = e.nodeName.toLowerCase();
                    return "input" === t && !!e.checked || "option" === t && !!e.selected
                }, selected: function (e) {
                    return e.parentNode && e.parentNode.selectedIndex, e.selected === !0
                }, empty: function (e) {
                    for (e = e.firstChild; e; e = e.nextSibling) if (e.nodeType < 6) return !1;
                    return !0
                }, parent: function (e) {
                    return !x.pseudos.empty(e)
                }, header: function (e) {
                    return ge.test(e.nodeName)
                }, input: function (e) {
                    return he.test(e.nodeName)
                }, button: function (e) {
                    var t = e.nodeName.toLowerCase();
                    return "input" === t && "button" === e.type || "button" === t
                }, text: function (e) {
                    var t;
                    return "input" === e.nodeName.toLowerCase() && "text" === e.type && (null == (t = e.getAttribute("type")) || "text" === t.toLowerCase())
                }, first: d(function () {
                    return [0]
                }), last: d(function (e, t) {
                    return [t - 1]
                }), eq: d(function (e, t, n) {
                    return [n < 0 ? n + t : n]
                }), even: d(function (e, t) {
                    for (var n = 0; n < t; n += 2) e.push(n);
                    return e
                }), odd: d(function (e, t) {
                    for (var n = 1; n < t; n += 2) e.push(n);
                    return e
                }), lt: d(function (e, t, n) {
                    for (var i = n < 0 ? n + t : n; --i >= 0;) e.push(i);
                    return e
                }), gt: d(function (e, t, n) {
                    for (var i = n < 0 ? n + t : n; ++i < t;) e.push(i);
                    return e
                })
            }
        }, x.pseudos.nth = x.pseudos.eq;
        for (k in{radio: !0, checkbox: !0, file: !0, password: !0, image: !0}) x.pseudos[k] = a(k);
        for (k in{submit: !0, reset: !0}) x.pseudos[k] = l(k);
        return p.prototype = x.filters = x.pseudos, x.setFilters = new p, $ = t.tokenize = function (e, n) {
            var i, o, s, r, a, l, c, d = U[e + " "];
            if (d) return n ? 0 : d.slice(0);
            for (a = e, l = [], c = x.preFilter; a;) {
                i && !(o = le.exec(a)) || (o && (a = a.slice(o[0].length) || a), l.push(s = [])), i = !1, (o = ce.exec(a)) && (i = o.shift(), s.push({
                    value: i,
                    type: o[0].replace(ae, " ")
                }), a = a.slice(i.length));
                for (r in x.filter) !(o = fe[r].exec(a)) || c[r] && !(o = c[r](o)) || (i = o.shift(), s.push({
                    value: i,
                    type: r,
                    matches: o
                }), a = a.slice(i.length));
                if (!i) break
            }
            return n ? a.length : a ? t.error(e) : U(e, l).slice(0)
        }, A = t.compile = function (e, t) {
            var n, i = [], o = [], s = X[e + " "];
            if (!s) {
                for (t || (t = $(e)), n = t.length; n--;) s = w(t[n]), s[M] ? i.push(s) : o.push(s);
                s = X(e, b(o, i)), s.selector = e
            }
            return s
        }, _ = t.select = function (e, t, n, i) {
            var o, s, r, a, l, c = "function" == typeof e && e, d = !i && $(e = c.selector || e);
            if (n = n || [], 1 === d.length) {
                if (s = d[0] = d[0].slice(0), s.length > 2 && "ID" === (r = s[0]).type && 9 === t.nodeType && j && x.relative[s[1].type]) {
                    if (t = (x.find.ID(r.matches[0].replace(we, be), t) || [])[0], !t) return n;
                    c && (t = t.parentNode), e = e.slice(s.shift().value.length)
                }
                for (o = fe.needsContext.test(e) ? 0 : s.length; o-- && (r = s[o], !x.relative[a = r.type]);) if ((l = x.find[a]) && (i = l(r.matches[0].replace(we, be), ye.test(s[0].type) && u(t.parentNode) || t))) {
                    if (s.splice(o, 1), e = i.length && f(s), !e) return J.apply(n, i), n;
                    break
                }
            }
            return (c || A(e, d))(i, t, !j, n, !t || ye.test(e) && u(t.parentNode) || t), n
        }, T.sortStable = M.split("").sort(Y).join("") === M, T.detectDuplicates = !!H, O(), T.sortDetached = o(function (e) {
            return 1 & e.compareDocumentPosition(z.createElement("fieldset"))
        }), o(function (e) {
            return e.innerHTML = "<a href='#'></a>", "#" === e.firstChild.getAttribute("href")
        }) || s("type|href|height|width", function (e, t, n) {
            if (!n) return e.getAttribute(t, "type" === t.toLowerCase() ? 1 : 2)
        }), T.attributes && o(function (e) {
            return e.innerHTML = "<input/>", e.firstChild.setAttribute("value", ""), "" === e.firstChild.getAttribute("value")
        }) || s("value", function (e, t, n) {
            if (!n && "input" === e.nodeName.toLowerCase()) return e.defaultValue
        }), o(function (e) {
            return null == e.getAttribute("disabled")
        }) || s(te, function (e, t, n) {
            var i;
            if (!n) return e[t] === !0 ? t.toLowerCase() : (i = e.getAttributeNode(t)) && i.specified ? i.value : null
        }), t
    }(e);
    ge.find = be, ge.expr = be.selectors, ge.expr[":"] = ge.expr.pseudos, ge.uniqueSort = ge.unique = be.uniqueSort, ge.text = be.getText, ge.isXMLDoc = be.isXML, ge.contains = be.contains, ge.escapeSelector = be.escape;
    var ke = function (e, t, n) {
            for (var i = [], o = void 0 !== n; (e = e[t]) && 9 !== e.nodeType;) if (1 === e.nodeType) {
                if (o && ge(e).is(n)) break;
                i.push(e)
            }
            return i
        }, Te = function (e, t) {
            for (var n = []; e; e = e.nextSibling) 1 === e.nodeType && e !== t && n.push(e);
            return n
        }, xe = ge.expr.match.needsContext, Se = /^<([a-z][^\/\0>:\x20\t\r\n\f]*)[\x20\t\r\n\f]*\/?>(?:<\/\1>|)$/i,
        Ce = /^.[^:#\[\.,]*$/;
    ge.filter = function (e, t, n) {
        var i = t[0];
        return n && (e = ":not(" + e + ")"), 1 === t.length && 1 === i.nodeType ? ge.find.matchesSelector(i, e) ? [i] : [] : ge.find.matches(e, ge.grep(t, function (e) {
            return 1 === e.nodeType
        }))
    }, ge.fn.extend({
        find: function (e) {
            var t, n, i = this.length, o = this;
            if ("string" != typeof e) return this.pushStack(ge(e).filter(function () {
                for (t = 0; t < i; t++) if (ge.contains(o[t], this)) return !0
            }));
            for (n = this.pushStack([]), t = 0; t < i; t++) ge.find(e, o[t], n);
            return i > 1 ? ge.uniqueSort(n) : n
        }, filter: function (e) {
            return this.pushStack(s(this, e || [], !1))
        }, not: function (e) {
            return this.pushStack(s(this, e || [], !0))
        }, is: function (e) {
            return !!s(this, "string" == typeof e && xe.test(e) ? ge(e) : e || [], !1).length
        }
    });
    var $e, Ae = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]+))$/, _e = ge.fn.init = function (e, t, n) {
        var i, o;
        if (!e) return this;
        if (n = n || $e, "string" == typeof e) {
            if (i = "<" === e[0] && ">" === e[e.length - 1] && e.length >= 3 ? [null, e, null] : Ae.exec(e), !i || !i[1] && t) return !t || t.jquery ? (t || n).find(e) : this.constructor(t).find(e);
            if (i[1]) {
                if (t = t instanceof ge ? t[0] : t, ge.merge(this, ge.parseHTML(i[1], t && t.nodeType ? t.ownerDocument || t : ne, !0)), Se.test(i[1]) && ge.isPlainObject(t)) for (i in t) ge.isFunction(this[i]) ? this[i](t[i]) : this.attr(i, t[i]);
                return this
            }
            return o = ne.getElementById(i[2]), o && (this[0] = o, this.length = 1), this
        }
        return e.nodeType ? (this[0] = e, this.length = 1, this) : ge.isFunction(e) ? void 0 !== n.ready ? n.ready(e) : e(ge) : ge.makeArray(e, this)
    };
    _e.prototype = ge.fn, $e = ge(ne);
    var Ee = /^(?:parents|prev(?:Until|All))/, De = {children: !0, contents: !0, next: !0, prev: !0};
    ge.fn.extend({
        has: function (e) {
            var t = ge(e, this), n = t.length;
            return this.filter(function () {
                for (var e = 0; e < n; e++) if (ge.contains(this, t[e])) return !0
            })
        }, closest: function (e, t) {
            var n, i = 0, o = this.length, s = [], r = "string" != typeof e && ge(e);
            if (!xe.test(e)) for (; i < o; i++) for (n = this[i]; n && n !== t; n = n.parentNode) if (n.nodeType < 11 && (r ? r.index(n) > -1 : 1 === n.nodeType && ge.find.matchesSelector(n, e))) {
                s.push(n);
                break
            }
            return this.pushStack(s.length > 1 ? ge.uniqueSort(s) : s)
        }, index: function (e) {
            return e ? "string" == typeof e ? ae.call(ge(e), this[0]) : ae.call(this, e.jquery ? e[0] : e) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
        }, add: function (e, t) {
            return this.pushStack(ge.uniqueSort(ge.merge(this.get(), ge(e, t))))
        }, addBack: function (e) {
            return this.add(null == e ? this.prevObject : this.prevObject.filter(e))
        }
    }), ge.each({
        parent: function Vt(e) {
            var Vt = e.parentNode;
            return Vt && 11 !== Vt.nodeType ? Vt : null
        }, parents: function (e) {
            return ke(e, "parentNode")
        }, parentsUntil: function (e, t, n) {
            return ke(e, "parentNode", n)
        }, next: function (e) {
            return r(e, "nextSibling")
        }, prev: function (e) {
            return r(e, "previousSibling")
        }, nextAll: function (e) {
            return ke(e, "nextSibling")
        }, prevAll: function (e) {
            return ke(e, "previousSibling")
        }, nextUntil: function (e, t, n) {
            return ke(e, "nextSibling", n)
        }, prevUntil: function (e, t, n) {
            return ke(e, "previousSibling", n)
        }, siblings: function (e) {
            return Te((e.parentNode || {}).firstChild, e)
        }, children: function (e) {
            return Te(e.firstChild)
        }, contents: function (e) {
            return o(e, "iframe") ? e.contentDocument : (o(e, "template") && (e = e.content || e), ge.merge([], e.childNodes))
        }
    }, function (e, t) {
        ge.fn[e] = function (n, i) {
            var o = ge.map(this, t, n);
            return "Until" !== e.slice(-5) && (i = n), i && "string" == typeof i && (o = ge.filter(i, o)), this.length > 1 && (De[e] || ge.uniqueSort(o), Ee.test(e) && o.reverse()), this.pushStack(o)
        }
    });
    var He = /[^\x20\t\r\n\f]+/g;
    ge.Callbacks = function (e) {
        e = "string" == typeof e ? a(e) : ge.extend({}, e);
        var t, n, i, o, s = [], r = [], l = -1, c = function () {
            for (o = o || e.once, i = t = !0; r.length; l = -1) for (n = r.shift(); ++l < s.length;) s[l].apply(n[0], n[1]) === !1 && e.stopOnFalse && (l = s.length, n = !1);
            e.memory || (n = !1), t = !1, o && (s = n ? [] : "")
        }, d = {
            add: function () {
                return s && (n && !t && (l = s.length - 1, r.push(n)), function i(t) {
                    ge.each(t, function (t, n) {
                        ge.isFunction(n) ? e.unique && d.has(n) || s.push(n) : n && n.length && "string" !== ge.type(n) && i(n)
                    })
                }(arguments), n && !t && c()), this
            }, remove: function () {
                return ge.each(arguments, function (e, t) {
                    for (var n; (n = ge.inArray(t, s, n)) > -1;) s.splice(n, 1), n <= l && l--
                }), this
            }, has: function (e) {
                return e ? ge.inArray(e, s) > -1 : s.length > 0
            }, empty: function () {
                return s && (s = []), this
            }, disable: function () {
                return o = r = [], s = n = "", this
            }, disabled: function () {
                return !s
            }, lock: function () {
                return o = r = [], n || t || (s = n = ""), this
            }, locked: function () {
                return !!o
            }, fireWith: function (e, n) {
                return o || (n = n || [], n = [e, n.slice ? n.slice() : n], r.push(n), t || c()), this
            }, fire: function () {
                return d.fireWith(this, arguments), this
            }, fired: function () {
                return !!i
            }
        };
        return d
    }, ge.extend({
        Deferred: function (t) {
            var n = [["notify", "progress", ge.Callbacks("memory"), ge.Callbacks("memory"), 2], ["resolve", "done", ge.Callbacks("once memory"), ge.Callbacks("once memory"), 0, "resolved"], ["reject", "fail", ge.Callbacks("once memory"), ge.Callbacks("once memory"), 1, "rejected"]],
                i = "pending", o = {
                    state: function () {
                        return i
                    }, always: function () {
                        return s.done(arguments).fail(arguments), this
                    }, "catch": function (e) {
                        return o.then(null, e)
                    }, pipe: function () {
                        var e = arguments;
                        return ge.Deferred(function (t) {
                            ge.each(n, function (n, i) {
                                var o = ge.isFunction(e[i[4]]) && e[i[4]];
                                s[i[1]](function () {
                                    var e = o && o.apply(this, arguments);
                                    e && ge.isFunction(e.promise) ? e.promise().progress(t.notify).done(t.resolve).fail(t.reject) : t[i[0] + "With"](this, o ? [e] : arguments)
                                })
                            }), e = null
                        }).promise()
                    }, then: function (t, i, o) {
                        function s(t, n, i, o) {
                            return function () {
                                var a = this, d = arguments, u = function () {
                                    var e, u;
                                    if (!(t < r)) {
                                        if (e = i.apply(a, d), e === n.promise()) throw new TypeError("Thenable self-resolution");
                                        u = e && ("object" === ("undefined" == typeof e ? "undefined" : _typeof(e)) || "function" == typeof e) && e.then, ge.isFunction(u) ? o ? u.call(e, s(r, n, l, o), s(r, n, c, o)) : (r++, u.call(e, s(r, n, l, o), s(r, n, c, o), s(r, n, l, n.notifyWith))) : (i !== l && (a = void 0, d = [e]), (o || n.resolveWith)(a, d))
                                    }
                                }, p = o ? u : function () {
                                    try {
                                        u()
                                    } catch (e) {
                                        ge.Deferred.exceptionHook && ge.Deferred.exceptionHook(e, p.stackTrace), t + 1 >= r && (i !== c && (a = void 0, d = [e]), n.rejectWith(a, d))
                                    }
                                };
                                t ? p() : (ge.Deferred.getStackHook && (p.stackTrace = ge.Deferred.getStackHook()), e.setTimeout(p))
                            }
                        }

                        var r = 0;
                        return ge.Deferred(function (e) {
                            n[0][3].add(s(0, e, ge.isFunction(o) ? o : l, e.notifyWith)), n[1][3].add(s(0, e, ge.isFunction(t) ? t : l)), n[2][3].add(s(0, e, ge.isFunction(i) ? i : c))
                        }).promise()
                    }, promise: function (e) {
                        return null != e ? ge.extend(e, o) : o
                    }
                }, s = {};
            return ge.each(n, function (e, t) {
                var r = t[2], a = t[5];
                o[t[1]] = r.add, a && r.add(function () {
                    i = a
                }, n[3 - e][2].disable, n[0][2].lock), r.add(t[3].fire), s[t[0]] = function () {
                    return s[t[0] + "With"](this === s ? void 0 : this, arguments), this
                }, s[t[0] + "With"] = r.fireWith
            }), o.promise(s), t && t.call(s, s), s
        }, when: function (e) {
            var t = arguments.length, n = t, i = Array(n), o = oe.call(arguments), s = ge.Deferred(), r = function (e) {
                return function (n) {
                    i[e] = this, o[e] = arguments.length > 1 ? oe.call(arguments) : n, --t || s.resolveWith(i, o)
                }
            };
            if (t <= 1 && (d(e, s.done(r(n)).resolve, s.reject, !t), "pending" === s.state() || ge.isFunction(o[n] && o[n].then))) return s.then();
            for (; n--;) d(o[n], r(n), s.reject);
            return s.promise()
        }
    });
    var Oe = /^(Eval|Internal|Range|Reference|Syntax|Type|URI)Error$/;
    ge.Deferred.exceptionHook = function (t, n) {
        e.console && e.console.warn && t && Oe.test(t.name) && e.console.warn("jQuery.Deferred exception: " + t.message, t.stack, n)
    }, ge.readyException = function (t) {
        e.setTimeout(function () {
            throw t
        })
    };
    var ze = ge.Deferred();
    ge.fn.ready = function (e) {
        return ze.then(e)["catch"](function (e) {
            ge.readyException(e)
        }), this
    }, ge.extend({
        isReady: !1, readyWait: 1, ready: function (e) {
            (e === !0 ? --ge.readyWait : ge.isReady) || (ge.isReady = !0, e !== !0 && --ge.readyWait > 0 || ze.resolveWith(ne, [ge]))
        }
    }), ge.ready.then = ze.then, "complete" === ne.readyState || "loading" !== ne.readyState && !ne.documentElement.doScroll ? e.setTimeout(ge.ready) : (ne.addEventListener("DOMContentLoaded", u), e.addEventListener("load", u));
    var Le = function Jt(e, t, n, i, o, s, r) {
        var a = 0, l = e.length, c = null == n;
        if ("object" === ge.type(n)) {
            o = !0;
            for (a in n) Jt(e, t, a, n[a], !0, s, r)
        } else if (void 0 !== i && (o = !0, ge.isFunction(i) || (r = !0), c && (r ? (t.call(e, i), t = null) : (c = t, t = function (e, t, n) {
                return c.call(ge(e), n)
            })), t)) for (; a < l; a++) t(e[a], n, r ? i : i.call(e[a], a, t(e[a], n)));
        return o ? e : c ? t.call(e) : l ? t(e[0], n) : s
    }, je = function (e) {
        return 1 === e.nodeType || 9 === e.nodeType || !+e.nodeType
    };
    p.uid = 1, p.prototype = {
        cache: function (e) {
            var t = e[this.expando];
            return t || (t = {}, je(e) && (e.nodeType ? e[this.expando] = t : Object.defineProperty(e, this.expando, {
                value: t,
                configurable: !0
            }))), t
        }, set: function (e, t, n) {
            var i, o = this.cache(e);
            if ("string" == typeof t) o[ge.camelCase(t)] = n; else for (i in t) o[ge.camelCase(i)] = t[i];
            return o
        }, get: function (e, t) {
            return void 0 === t ? this.cache(e) : e[this.expando] && e[this.expando][ge.camelCase(t)]
        }, access: function (e, t, n) {
            return void 0 === t || t && "string" == typeof t && void 0 === n ? this.get(e, t) : (this.set(e, t, n), void 0 !== n ? n : t)
        }, remove: function (e, t) {
            var n, i = e[this.expando];
            if (void 0 !== i) {
                if (void 0 !== t) {
                    Array.isArray(t) ? t = t.map(ge.camelCase) : (t = ge.camelCase(t), t = t in i ? [t] : t.match(He) || []), n = t.length;
                    for (; n--;) delete i[t[n]]
                }
                (void 0 === t || ge.isEmptyObject(i)) && (e.nodeType ? e[this.expando] = void 0 : delete e[this.expando])
            }
        }, hasData: function (e) {
            var t = e[this.expando];
            return void 0 !== t && !ge.isEmptyObject(t)
        }
    };
    var Fe = new p, Pe = new p, Ne = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/, qe = /[A-Z]/g;
    ge.extend({
        hasData: function (e) {
            return Pe.hasData(e) || Fe.hasData(e)
        }, data: function (e, t, n) {
            return Pe.access(e, t, n)
        }, removeData: function (e, t) {
            Pe.remove(e, t)
        }, _data: function (e, t, n) {
            return Fe.access(e, t, n)
        }, _removeData: function (e, t) {
            Fe.remove(e, t)
        }
    }), ge.fn.extend({
        data: function Zt(e, t) {
            var n, i, Zt, o = this[0], s = o && o.attributes;
            if (void 0 === e) {
                if (this.length && (Zt = Pe.get(o), 1 === o.nodeType && !Fe.get(o, "hasDataAttrs"))) {
                    for (n = s.length; n--;) s[n] && (i = s[n].name, 0 === i.indexOf("data-") && (i = ge.camelCase(i.slice(5)), h(o, i, Zt[i])));
                    Fe.set(o, "hasDataAttrs", !0)
                }
                return Zt
            }
            return "object" === ("undefined" == typeof e ? "undefined" : _typeof(e)) ? this.each(function () {
                Pe.set(this, e)
            }) : Le(this, function (t) {
                var n;
                if (o && void 0 === t) {
                    if (n = Pe.get(o, e), void 0 !== n) return n;
                    if (n = h(o, e), void 0 !== n) return n
                } else this.each(function () {
                    Pe.set(this, e, t)
                })
            }, null, t, arguments.length > 1, null, !0)
        }, removeData: function (e) {
            return this.each(function () {
                Pe.remove(this, e)
            })
        }
    }), ge.extend({
        queue: function en(e, t, n) {
            var en;
            if (e) return t = (t || "fx") + "queue", en = Fe.get(e, t), n && (!en || Array.isArray(n) ? en = Fe.access(e, t, ge.makeArray(n)) : en.push(n)), en || []
        }, dequeue: function (e, t) {
            t = t || "fx";
            var n = ge.queue(e, t), i = n.length, o = n.shift(), s = ge._queueHooks(e, t), r = function () {
                ge.dequeue(e, t)
            };
            "inprogress" === o && (o = n.shift(), i--), o && ("fx" === t && n.unshift("inprogress"), delete s.stop, o.call(e, r, s)), !i && s && s.empty.fire()
        }, _queueHooks: function (e, t) {
            var n = t + "queueHooks";
            return Fe.get(e, n) || Fe.access(e, n, {
                empty: ge.Callbacks("once memory").add(function () {
                    Fe.remove(e, [t + "queue", n])
                })
            })
        }
    }), ge.fn.extend({
        queue: function (e, t) {
            var n = 2;
            return "string" != typeof e && (t = e, e = "fx", n--), arguments.length < n ? ge.queue(this[0], e) : void 0 === t ? this : this.each(function () {
                var n = ge.queue(this, e, t);
                ge._queueHooks(this, e), "fx" === e && "inprogress" !== n[0] && ge.dequeue(this, e)
            })
        }, dequeue: function (e) {
            return this.each(function () {
                ge.dequeue(this, e)
            })
        }, clearQueue: function (e) {
            return this.queue(e || "fx", [])
        }, promise: function (e, t) {
            var n, i = 1, o = ge.Deferred(), s = this, r = this.length, a = function () {
                --i || o.resolveWith(s, [s])
            };
            for ("string" != typeof e && (t = e, e = void 0), e = e || "fx"; r--;) n = Fe.get(s[r], e + "queueHooks"), n && n.empty && (i++, n.empty.add(a));
            return a(), o.promise(t)
        }
    });
    var Me = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source, Re = new RegExp("^(?:([+-])=|)(" + Me + ")([a-z%]*)$", "i"),
        Ie = ["Top", "Right", "Bottom", "Left"], We = function (e, t) {
            return e = t || e, "none" === e.style.display || "" === e.style.display && ge.contains(e.ownerDocument, e) && "none" === ge.css(e, "display")
        }, Be = function (e, t, n, i) {
            var o, s, r = {};
            for (s in t) r[s] = e.style[s], e.style[s] = t[s];
            o = n.apply(e, i || []);
            for (s in t) e.style[s] = r[s];
            return o
        }, Ue = {};
    ge.fn.extend({
        show: function () {
            return m(this, !0)
        }, hide: function () {
            return m(this)
        }, toggle: function (e) {
            return "boolean" == typeof e ? e ? this.show() : this.hide() : this.each(function () {
                We(this) ? ge(this).show() : ge(this).hide()
            })
        }
    });
    var Xe = /^(?:checkbox|radio)$/i, Ye = /<([a-z][^\/\0>\x20\t\r\n\f]+)/i, Ke = /^$|\/(?:java|ecma)script/i, Qe = {
        option: [1, "<select multiple='multiple'>", "</select>"],
        thead: [1, "<table>", "</table>"],
        col: [2, "<table><colgroup>", "</colgroup></table>"],
        tr: [2, "<table><tbody>", "</tbody></table>"],
        td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
        _default: [0, "", ""]
    };
    Qe.optgroup = Qe.option, Qe.tbody = Qe.tfoot = Qe.colgroup = Qe.caption = Qe.thead, Qe.th = Qe.td;
    var Ge = /<|&#?\w+;/;
    !function () {
        var e = ne.createDocumentFragment(), t = e.appendChild(ne.createElement("div")), n = ne.createElement("input");
        n.setAttribute("type", "radio"), n.setAttribute("checked", "checked"), n.setAttribute("name", "t"), t.appendChild(n), fe.checkClone = t.cloneNode(!0).cloneNode(!0).lastChild.checked, t.innerHTML = "<textarea>x</textarea>", fe.noCloneChecked = !!t.cloneNode(!0).lastChild.defaultValue
    }();
    var Ve = ne.documentElement, Je = /^key/, Ze = /^(?:mouse|pointer|contextmenu|drag|drop)|click/,
        et = /^([^.]*)(?:\.(.+)|)/;
    ge.event = {
        global: {}, add: function (e, t, n, i, o) {
            var s, r, a, l, c, d, u, p, f, h, g, v = Fe.get(e);
            if (v) for (n.handler && (s = n, n = s.handler, o = s.selector), o && ge.find.matchesSelector(Ve, o), n.guid || (n.guid = ge.guid++), (l = v.events) || (l = v.events = {}), (r = v.handle) || (r = v.handle = function (t) {
                return "undefined" != typeof ge && ge.event.triggered !== t.type ? ge.event.dispatch.apply(e, arguments) : void 0
            }), t = (t || "").match(He) || [""], c = t.length; c--;) a = et.exec(t[c]) || [], f = g = a[1], h = (a[2] || "").split(".").sort(), f && (u = ge.event.special[f] || {}, f = (o ? u.delegateType : u.bindType) || f, u = ge.event.special[f] || {}, d = ge.extend({
                type: f,
                origType: g,
                data: i,
                handler: n,
                guid: n.guid,
                selector: o,
                needsContext: o && ge.expr.match.needsContext.test(o),
                namespace: h.join(".")
            }, s), (p = l[f]) || (p = l[f] = [], p.delegateCount = 0, u.setup && u.setup.call(e, i, h, r) !== !1 || e.addEventListener && e.addEventListener(f, r)), u.add && (u.add.call(e, d), d.handler.guid || (d.handler.guid = n.guid)), o ? p.splice(p.delegateCount++, 0, d) : p.push(d), ge.event.global[f] = !0)
        }, remove: function (e, t, n, i, o) {
            var s, r, a, l, c, d, u, p, f, h, g, v = Fe.hasData(e) && Fe.get(e);
            if (v && (l = v.events)) {
                for (t = (t || "").match(He) || [""], c = t.length; c--;) if (a = et.exec(t[c]) || [], f = g = a[1], h = (a[2] || "").split(".").sort(), f) {
                    for (u = ge.event.special[f] || {}, f = (i ? u.delegateType : u.bindType) || f, p = l[f] || [], a = a[2] && new RegExp("(^|\\.)" + h.join("\\.(?:.*\\.|)") + "(\\.|$)"), r = s = p.length; s--;) d = p[s], !o && g !== d.origType || n && n.guid !== d.guid || a && !a.test(d.namespace) || i && i !== d.selector && ("**" !== i || !d.selector) || (p.splice(s, 1), d.selector && p.delegateCount--, u.remove && u.remove.call(e, d));
                    r && !p.length && (u.teardown && u.teardown.call(e, h, v.handle) !== !1 || ge.removeEvent(e, f, v.handle), delete l[f])
                } else for (f in l) ge.event.remove(e, f + t[c], n, i, !0);
                ge.isEmptyObject(l) && Fe.remove(e, "handle events")
            }
        }, dispatch: function (e) {
            var t, n, i, o, s, r, a = ge.event.fix(e), l = new Array(arguments.length),
                c = (Fe.get(this, "events") || {})[a.type] || [], d = ge.event.special[a.type] || {};
            for (l[0] = a, t = 1; t < arguments.length; t++) l[t] = arguments[t];
            if (a.delegateTarget = this, !d.preDispatch || d.preDispatch.call(this, a) !== !1) {
                for (r = ge.event.handlers.call(this, a, c), t = 0; (o = r[t++]) && !a.isPropagationStopped();) for (a.currentTarget = o.elem, n = 0; (s = o.handlers[n++]) && !a.isImmediatePropagationStopped();) a.rnamespace && !a.rnamespace.test(s.namespace) || (a.handleObj = s, a.data = s.data, i = ((ge.event.special[s.origType] || {}).handle || s.handler).apply(o.elem, l), void 0 !== i && (a.result = i) === !1 && (a.preventDefault(), a.stopPropagation()));
                return d.postDispatch && d.postDispatch.call(this, a), a.result
            }
        }, handlers: function (e, t) {
            var n, i, o, s, r, a = [], l = t.delegateCount, c = e.target;
            if (l && c.nodeType && !("click" === e.type && e.button >= 1)) for (; c !== this; c = c.parentNode || this) if (1 === c.nodeType && ("click" !== e.type || c.disabled !== !0)) {
                for (s = [], r = {}, n = 0; n < l; n++) i = t[n], o = i.selector + " ", void 0 === r[o] && (r[o] = i.needsContext ? ge(o, this).index(c) > -1 : ge.find(o, this, null, [c]).length), r[o] && s.push(i);
                s.length && a.push({elem: c, handlers: s})
            }
            return c = this, l < t.length && a.push({elem: c, handlers: t.slice(l)}), a
        }, addProp: function (e, t) {
            Object.defineProperty(ge.Event.prototype, e, {
                enumerable: !0,
                configurable: !0,
                get: ge.isFunction(t) ? function () {
                    if (this.originalEvent) return t(this.originalEvent)
                } : function () {
                    if (this.originalEvent) return this.originalEvent[e]
                },
                set: function (t) {
                    Object.defineProperty(this, e, {enumerable: !0, configurable: !0, writable: !0, value: t})
                }
            })
        }, fix: function (e) {
            return e[ge.expando] ? e : new ge.Event(e)
        }, special: {
            load: {noBubble: !0}, focus: {
                trigger: function () {
                    if (this !== x() && this.focus) return this.focus(), !1
                }, delegateType: "focusin"
            }, blur: {
                trigger: function () {
                    if (this === x() && this.blur) return this.blur(), !1
                }, delegateType: "focusout"
            }, click: {
                trigger: function () {
                    if ("checkbox" === this.type && this.click && o(this, "input")) return this.click(), !1
                }, _default: function (e) {
                    return o(e.target, "a")
                }
            }, beforeunload: {
                postDispatch: function (e) {
                    void 0 !== e.result && e.originalEvent && (e.originalEvent.returnValue = e.result)
                }
            }
        }
    }, ge.removeEvent = function (e, t, n) {
        e.removeEventListener && e.removeEventListener(t, n)
    }, ge.Event = function (e, t) {
        return this instanceof ge.Event ? (e && e.type ? (this.originalEvent = e, this.type = e.type, this.isDefaultPrevented = e.defaultPrevented || void 0 === e.defaultPrevented && e.returnValue === !1 ? k : T, this.target = e.target && 3 === e.target.nodeType ? e.target.parentNode : e.target, this.currentTarget = e.currentTarget, this.relatedTarget = e.relatedTarget) : this.type = e, t && ge.extend(this, t), this.timeStamp = e && e.timeStamp || ge.now(), void(this[ge.expando] = !0)) : new ge.Event(e, t)
    }, ge.Event.prototype = {
        constructor: ge.Event,
        isDefaultPrevented: T,
        isPropagationStopped: T,
        isImmediatePropagationStopped: T,
        isSimulated: !1,
        preventDefault: function () {
            var e = this.originalEvent;
            this.isDefaultPrevented = k, e && !this.isSimulated && e.preventDefault()
        },
        stopPropagation: function () {
            var e = this.originalEvent;
            this.isPropagationStopped = k, e && !this.isSimulated && e.stopPropagation()
        },
        stopImmediatePropagation: function () {
            var e = this.originalEvent;
            this.isImmediatePropagationStopped = k, e && !this.isSimulated && e.stopImmediatePropagation(), this.stopPropagation()
        }
    }, ge.each({
        altKey: !0,
        bubbles: !0,
        cancelable: !0,
        changedTouches: !0,
        ctrlKey: !0,
        detail: !0,
        eventPhase: !0,
        metaKey: !0,
        pageX: !0,
        pageY: !0,
        shiftKey: !0,
        view: !0,
        "char": !0,
        charCode: !0,
        key: !0,
        keyCode: !0,
        button: !0,
        buttons: !0,
        clientX: !0,
        clientY: !0,
        offsetX: !0,
        offsetY: !0,
        pointerId: !0,
        pointerType: !0,
        screenX: !0,
        screenY: !0,
        targetTouches: !0,
        toElement: !0,
        touches: !0,
        which: function (e) {
            var t = e.button;
            return null == e.which && Je.test(e.type) ? null != e.charCode ? e.charCode : e.keyCode : !e.which && void 0 !== t && Ze.test(e.type) ? 1 & t ? 1 : 2 & t ? 3 : 4 & t ? 2 : 0 : e.which
        }
    }, ge.event.addProp), ge.each({
        mouseenter: "mouseover",
        mouseleave: "mouseout",
        pointerenter: "pointerover",
        pointerleave: "pointerout"
    }, function (e, t) {
        ge.event.special[e] = {
            delegateType: t, bindType: t, handle: function (e) {
                var n, i = this, o = e.relatedTarget, s = e.handleObj;
                return o && (o === i || ge.contains(i, o)) || (e.type = s.origType, n = s.handler.apply(this, arguments), e.type = t), n
            }
        }
    }), ge.fn.extend({
        on: function (e, t, n, i) {
            return S(this, e, t, n, i)
        }, one: function (e, t, n, i) {
            return S(this, e, t, n, i, 1)
        }, off: function (e, t, n) {
            var i, o;
            if (e && e.preventDefault && e.handleObj) return i = e.handleObj, ge(e.delegateTarget).off(i.namespace ? i.origType + "." + i.namespace : i.origType, i.selector, i.handler), this;
            if ("object" === ("undefined" == typeof e ? "undefined" : _typeof(e))) {
                for (o in e) this.off(o, t, e[o]);
                return this
            }
            return t !== !1 && "function" != typeof t || (n = t, t = void 0), n === !1 && (n = T), this.each(function () {
                ge.event.remove(this, e, n, t)
            })
        }
    });
    var tt = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([a-z][^\/\0>\x20\t\r\n\f]*)[^>]*)\/>/gi,
        nt = /<script|<style|<link/i, it = /checked\s*(?:[^=]|=\s*.checked.)/i, ot = /^true\/(.*)/,
        st = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g;
    ge.extend({
        htmlPrefilter: function (e) {
            return e.replace(tt, "<$1></$2>")
        }, clone: function tn(e, t, n) {
            var i, o, s, r, tn = e.cloneNode(!0), a = ge.contains(e.ownerDocument, e);
            if (!(fe.noCloneChecked || 1 !== e.nodeType && 11 !== e.nodeType || ge.isXMLDoc(e))) for (r = y(tn), s = y(e), i = 0, o = s.length; i < o; i++) E(s[i], r[i]);
            if (t) if (n) for (s = s || y(e), r = r || y(tn), i = 0, o = s.length; i < o; i++) _(s[i], r[i]); else _(e, tn);
            return r = y(tn, "script"), r.length > 0 && w(r, !a && y(e, "script")), tn
        }, cleanData: function (e) {
            for (var t, n, i, o = ge.event.special, s = 0; void 0 !== (n = e[s]); s++) if (je(n)) {
                if (t = n[Fe.expando]) {
                    if (t.events) for (i in t.events) o[i] ? ge.event.remove(n, i) : ge.removeEvent(n, i, t.handle);
                    n[Fe.expando] = void 0
                }
                n[Pe.expando] && (n[Pe.expando] = void 0)
            }
        }
    }), ge.fn.extend({
        detach: function (e) {
            return H(this, e, !0)
        }, remove: function (e) {
            return H(this, e)
        }, text: function (e) {
            return Le(this, function (e) {
                return void 0 === e ? ge.text(this) : this.empty().each(function () {
                    1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType || (this.textContent = e)
                })
            }, null, e, arguments.length)
        }, append: function () {
            return D(this, arguments, function (e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var t = C(this, e);
                    t.appendChild(e)
                }
            })
        }, prepend: function () {
            return D(this, arguments, function (e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var t = C(this, e);
                    t.insertBefore(e, t.firstChild)
                }
            })
        }, before: function () {
            return D(this, arguments, function (e) {
                this.parentNode && this.parentNode.insertBefore(e, this)
            })
        }, after: function () {
            return D(this, arguments, function (e) {
                this.parentNode && this.parentNode.insertBefore(e, this.nextSibling)
            })
        }, empty: function () {
            for (var e, t = 0; null != (e = this[t]); t++) 1 === e.nodeType && (ge.cleanData(y(e, !1)), e.textContent = "");
            return this
        }, clone: function (e, t) {
            return e = null != e && e, t = null == t ? e : t, this.map(function () {
                return ge.clone(this, e, t)
            })
        }, html: function (e) {
            return Le(this, function (e) {
                var t = this[0] || {}, n = 0, i = this.length;
                if (void 0 === e && 1 === t.nodeType) return t.innerHTML;
                if ("string" == typeof e && !nt.test(e) && !Qe[(Ye.exec(e) || ["", ""])[1].toLowerCase()]) {
                    e = ge.htmlPrefilter(e);
                    try {
                        for (; n < i; n++) t = this[n] || {}, 1 === t.nodeType && (ge.cleanData(y(t, !1)), t.innerHTML = e);
                        t = 0
                    } catch (o) {
                    }
                }
                t && this.empty().append(e)
            }, null, e, arguments.length)
        }, replaceWith: function () {
            var e = [];
            return D(this, arguments, function (t) {
                var n = this.parentNode;
                ge.inArray(this, e) < 0 && (ge.cleanData(y(this)), n && n.replaceChild(t, this))
            }, e)
        }
    }), ge.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    }, function (e, t) {
        ge.fn[e] = function (e) {
            for (var n, i = [], o = ge(e), s = o.length - 1, r = 0; r <= s; r++) n = r === s ? this : this.clone(!0), ge(o[r])[t](n), re.apply(i, n.get());
            return this.pushStack(i)
        }
    });
    var rt = /^margin/, at = new RegExp("^(" + Me + ")(?!px)[a-z%]+$", "i"), lt = function (t) {
        var n = t.ownerDocument.defaultView;
        return n && n.opener || (n = e), n.getComputedStyle(t)
    };
    !function () {
        function t() {
            if (a) {
                a.style.cssText = "box-sizing:border-box;position:relative;display:block;margin:auto;border:1px;padding:1px;top:1%;width:50%", a.innerHTML = "", Ve.appendChild(r);
                var t = e.getComputedStyle(a);
                n = "1%" !== t.top, s = "2px" === t.marginLeft, i = "4px" === t.width, a.style.marginRight = "50%", o = "4px" === t.marginRight, Ve.removeChild(r), a = null
            }
        }

        var n, i, o, s, r = ne.createElement("div"), a = ne.createElement("div");
        a.style && (a.style.backgroundClip = "content-box", a.cloneNode(!0).style.backgroundClip = "", fe.clearCloneStyle = "content-box" === a.style.backgroundClip, r.style.cssText = "border:0;width:8px;height:0;top:0;left:-9999px;padding:0;margin-top:1px;position:absolute", r.appendChild(a), ge.extend(fe, {
            pixelPosition: function () {
                return t(), n
            }, boxSizingReliable: function () {
                return t(), i
            }, pixelMarginRight: function () {
                return t(), o
            }, reliableMarginLeft: function () {
                return t(), s
            }
        }))
    }();
    var ct = /^(none|table(?!-c[ea]).+)/, dt = /^--/,
        ut = {position: "absolute", visibility: "hidden", display: "block"},
        pt = {letterSpacing: "0", fontWeight: "400"}, ft = ["Webkit", "Moz", "ms"], ht = ne.createElement("div").style;
    ge.extend({
        cssHooks: {
            opacity: {
                get: function (e, t) {
                    if (t) {
                        var n = O(e, "opacity");
                        return "" === n ? "1" : n
                    }
                }
            }
        },
        cssNumber: {
            animationIterationCount: !0,
            columnCount: !0,
            fillOpacity: !0,
            flexGrow: !0,
            flexShrink: !0,
            fontWeight: !0,
            lineHeight: !0,
            opacity: !0,
            order: !0,
            orphans: !0,
            widows: !0,
            zIndex: !0,
            zoom: !0
        },
        cssProps: {"float": "cssFloat"},
        style: function nn(e, t, n, i) {
            if (e && 3 !== e.nodeType && 8 !== e.nodeType && e.style) {
                var o, s, r, a = ge.camelCase(t), l = dt.test(t), nn = e.style;
                return l || (t = j(a)), r = ge.cssHooks[t] || ge.cssHooks[a], void 0 === n ? r && "get" in r && void 0 !== (o = r.get(e, !1, i)) ? o : nn[t] : (s = "undefined" == typeof n ? "undefined" : _typeof(n), "string" === s && (o = Re.exec(n)) && o[1] && (n = g(e, t, o), s = "number"), null != n && n === n && ("number" === s && (n += o && o[3] || (ge.cssNumber[a] ? "" : "px")), fe.clearCloneStyle || "" !== n || 0 !== t.indexOf("background") || (nn[t] = "inherit"), r && "set" in r && void 0 === (n = r.set(e, n, i)) || (l ? nn.setProperty(t, n) : nn[t] = n)), void 0)
            }
        },
        css: function (e, t, n, i) {
            var o, s, r, a = ge.camelCase(t), l = dt.test(t);
            return l || (t = j(a)), r = ge.cssHooks[t] || ge.cssHooks[a], r && "get" in r && (o = r.get(e, !0, n)), void 0 === o && (o = O(e, t, i)), "normal" === o && t in pt && (o = pt[t]), "" === n || n ? (s = parseFloat(o), n === !0 || isFinite(s) ? s || 0 : o) : o
        }
    }), ge.each(["height", "width"], function (e, t) {
        ge.cssHooks[t] = {
            get: function (e, n, i) {
                if (n) return !ct.test(ge.css(e, "display")) || e.getClientRects().length && e.getBoundingClientRect().width ? N(e, t, i) : Be(e, ut, function () {
                    return N(e, t, i)
                })
            }, set: function (e, n, i) {
                var o, s = i && lt(e), r = i && P(e, t, i, "border-box" === ge.css(e, "boxSizing", !1, s), s);
                return r && (o = Re.exec(n)) && "px" !== (o[3] || "px") && (e.style[t] = n, n = ge.css(e, t)), F(e, n, r)
            }
        }
    }), ge.cssHooks.marginLeft = z(fe.reliableMarginLeft, function (e, t) {
        if (t) return (parseFloat(O(e, "marginLeft")) || e.getBoundingClientRect().left - Be(e, {marginLeft: 0}, function () {
            return e.getBoundingClientRect().left
        })) + "px"
    }), ge.each({margin: "", padding: "", border: "Width"}, function (e, t) {
        ge.cssHooks[e + t] = {
            expand: function (n) {
                for (var i = 0, o = {}, s = "string" == typeof n ? n.split(" ") : [n]; i < 4; i++) o[e + Ie[i] + t] = s[i] || s[i - 2] || s[0];
                return o
            }
        }, rt.test(e) || (ge.cssHooks[e + t].set = F)
    }), ge.fn.extend({
        css: function (e, t) {
            return Le(this, function (e, t, n) {
                var i, o, s = {}, r = 0;
                if (Array.isArray(t)) {
                    for (i = lt(e), o = t.length; r < o; r++) s[t[r]] = ge.css(e, t[r], !1, i);
                    return s
                }
                return void 0 !== n ? ge.style(e, t, n) : ge.css(e, t)
            }, e, t, arguments.length > 1)
        }
    }), ge.Tween = q, q.prototype = {
        constructor: q, init: function (e, t, n, i, o, s) {
            this.elem = e, this.prop = n, this.easing = o || ge.easing._default, this.options = t, this.start = this.now = this.cur(), this.end = i, this.unit = s || (ge.cssNumber[n] ? "" : "px")
        }, cur: function () {
            var e = q.propHooks[this.prop];
            return e && e.get ? e.get(this) : q.propHooks._default.get(this)
        }, run: function (e) {
            var t, n = q.propHooks[this.prop];
            return this.options.duration ? this.pos = t = ge.easing[this.easing](e, this.options.duration * e, 0, 1, this.options.duration) : this.pos = t = e, this.now = (this.end - this.start) * t + this.start, this.options.step && this.options.step.call(this.elem, this.now, this), n && n.set ? n.set(this) : q.propHooks._default.set(this), this
        }
    }, q.prototype.init.prototype = q.prototype, q.propHooks = {
        _default: {
            get: function (e) {
                var t;
                return 1 !== e.elem.nodeType || null != e.elem[e.prop] && null == e.elem.style[e.prop] ? e.elem[e.prop] : (t = ge.css(e.elem, e.prop, ""), t && "auto" !== t ? t : 0)
            }, set: function (e) {
                ge.fx.step[e.prop] ? ge.fx.step[e.prop](e) : 1 !== e.elem.nodeType || null == e.elem.style[ge.cssProps[e.prop]] && !ge.cssHooks[e.prop] ? e.elem[e.prop] = e.now : ge.style(e.elem, e.prop, e.now + e.unit)
            }
        }
    }, q.propHooks.scrollTop = q.propHooks.scrollLeft = {
        set: function (e) {
            e.elem.nodeType && e.elem.parentNode && (e.elem[e.prop] = e.now)
        }
    }, ge.easing = {
        linear: function (e) {
            return e
        }, swing: function (e) {
            return .5 - Math.cos(e * Math.PI) / 2
        }, _default: "swing"
    }, ge.fx = q.prototype.init, ge.fx.step = {};
    var gt, vt, mt = /^(?:toggle|show|hide)$/, yt = /queueHooks$/;
    ge.Animation = ge.extend(X, {
        tweeners: {
            "*": [function (e, t) {
                var n = this.createTween(e, t);
                return g(n.elem, e, Re.exec(t), n), n
            }]
        }, tweener: function (e, t) {
            ge.isFunction(e) ? (t = e,
                e = ["*"]) : e = e.match(He);
            for (var n, i = 0, o = e.length; i < o; i++) n = e[i], X.tweeners[n] = X.tweeners[n] || [], X.tweeners[n].unshift(t)
        }, prefilters: [B], prefilter: function (e, t) {
            t ? X.prefilters.unshift(e) : X.prefilters.push(e)
        }
    }), ge.speed = function (e, t, n) {
        var i = e && "object" === ("undefined" == typeof e ? "undefined" : _typeof(e)) ? ge.extend({}, e) : {
            complete: n || !n && t || ge.isFunction(e) && e,
            duration: e,
            easing: n && t || t && !ge.isFunction(t) && t
        };
        return ge.fx.off ? i.duration = 0 : "number" != typeof i.duration && (i.duration in ge.fx.speeds ? i.duration = ge.fx.speeds[i.duration] : i.duration = ge.fx.speeds._default), null != i.queue && i.queue !== !0 || (i.queue = "fx"), i.old = i.complete, i.complete = function () {
            ge.isFunction(i.old) && i.old.call(this), i.queue && ge.dequeue(this, i.queue)
        }, i
    }, ge.fn.extend({
        fadeTo: function (e, t, n, i) {
            return this.filter(We).css("opacity", 0).show().end().animate({opacity: t}, e, n, i)
        }, animate: function (e, t, n, i) {
            var o = ge.isEmptyObject(e), s = ge.speed(t, n, i), r = function () {
                var t = X(this, ge.extend({}, e), s);
                (o || Fe.get(this, "finish")) && t.stop(!0)
            };
            return r.finish = r, o || s.queue === !1 ? this.each(r) : this.queue(s.queue, r)
        }, stop: function (e, t, n) {
            var i = function (e) {
                var t = e.stop;
                delete e.stop, t(n)
            };
            return "string" != typeof e && (n = t, t = e, e = void 0), t && e !== !1 && this.queue(e || "fx", []), this.each(function () {
                var t = !0, o = null != e && e + "queueHooks", s = ge.timers, r = Fe.get(this);
                if (o) r[o] && r[o].stop && i(r[o]); else for (o in r) r[o] && r[o].stop && yt.test(o) && i(r[o]);
                for (o = s.length; o--;) s[o].elem !== this || null != e && s[o].queue !== e || (s[o].anim.stop(n), t = !1, s.splice(o, 1));
                !t && n || ge.dequeue(this, e)
            })
        }, finish: function (e) {
            return e !== !1 && (e = e || "fx"), this.each(function () {
                var t, n = Fe.get(this), i = n[e + "queue"], o = n[e + "queueHooks"], s = ge.timers,
                    r = i ? i.length : 0;
                for (n.finish = !0, ge.queue(this, e, []), o && o.stop && o.stop.call(this, !0), t = s.length; t--;) s[t].elem === this && s[t].queue === e && (s[t].anim.stop(!0), s.splice(t, 1));
                for (t = 0; t < r; t++) i[t] && i[t].finish && i[t].finish.call(this);
                delete n.finish
            })
        }
    }), ge.each(["toggle", "show", "hide"], function (e, t) {
        var n = ge.fn[t];
        ge.fn[t] = function (e, i, o) {
            return null == e || "boolean" == typeof e ? n.apply(this, arguments) : this.animate(I(t, !0), e, i, o)
        }
    }), ge.each({
        slideDown: I("show"),
        slideUp: I("hide"),
        slideToggle: I("toggle"),
        fadeIn: {opacity: "show"},
        fadeOut: {opacity: "hide"},
        fadeToggle: {opacity: "toggle"}
    }, function (e, t) {
        ge.fn[e] = function (e, n, i) {
            return this.animate(t, e, n, i)
        }
    }), ge.timers = [], ge.fx.tick = function () {
        var e, t = 0, n = ge.timers;
        for (gt = ge.now(); t < n.length; t++) e = n[t], e() || n[t] !== e || n.splice(t--, 1);
        n.length || ge.fx.stop(), gt = void 0
    }, ge.fx.timer = function (e) {
        ge.timers.push(e), ge.fx.start()
    }, ge.fx.interval = 13, ge.fx.start = function () {
        vt || (vt = !0, M())
    }, ge.fx.stop = function () {
        vt = null
    }, ge.fx.speeds = {slow: 600, fast: 200, _default: 400}, ge.fn.delay = function (t, n) {
        return t = ge.fx ? ge.fx.speeds[t] || t : t, n = n || "fx", this.queue(n, function (n, i) {
            var o = e.setTimeout(n, t);
            i.stop = function () {
                e.clearTimeout(o)
            }
        })
    }, function () {
        var e = ne.createElement("input"), t = ne.createElement("select"),
            n = t.appendChild(ne.createElement("option"));
        e.type = "checkbox", fe.checkOn = "" !== e.value, fe.optSelected = n.selected, e = ne.createElement("input"), e.value = "t", e.type = "radio", fe.radioValue = "t" === e.value
    }();
    var wt, bt = ge.expr.attrHandle;
    ge.fn.extend({
        attr: function (e, t) {
            return Le(this, ge.attr, e, t, arguments.length > 1)
        }, removeAttr: function (e) {
            return this.each(function () {
                ge.removeAttr(this, e)
            })
        }
    }), ge.extend({
        attr: function (e, t, n) {
            var i, o, s = e.nodeType;
            if (3 !== s && 8 !== s && 2 !== s) return "undefined" == typeof e.getAttribute ? ge.prop(e, t, n) : (1 === s && ge.isXMLDoc(e) || (o = ge.attrHooks[t.toLowerCase()] || (ge.expr.match.bool.test(t) ? wt : void 0)), void 0 !== n ? null === n ? void ge.removeAttr(e, t) : o && "set" in o && void 0 !== (i = o.set(e, n, t)) ? i : (e.setAttribute(t, n + ""), n) : o && "get" in o && null !== (i = o.get(e, t)) ? i : (i = ge.find.attr(e, t), null == i ? void 0 : i))
        }, attrHooks: {
            type: {
                set: function (e, t) {
                    if (!fe.radioValue && "radio" === t && o(e, "input")) {
                        var n = e.value;
                        return e.setAttribute("type", t), n && (e.value = n), t
                    }
                }
            }
        }, removeAttr: function (e, t) {
            var n, i = 0, o = t && t.match(He);
            if (o && 1 === e.nodeType) for (; n = o[i++];) e.removeAttribute(n)
        }
    }), wt = {
        set: function (e, t, n) {
            return t === !1 ? ge.removeAttr(e, n) : e.setAttribute(n, n), n
        }
    }, ge.each(ge.expr.match.bool.source.match(/\w+/g), function (e, t) {
        var n = bt[t] || ge.find.attr;
        bt[t] = function (e, t, i) {
            var o, s, r = t.toLowerCase();
            return i || (s = bt[r], bt[r] = o, o = null != n(e, t, i) ? r : null, bt[r] = s), o
        }
    });
    var kt = /^(?:input|select|textarea|button)$/i, Tt = /^(?:a|area)$/i;
    ge.fn.extend({
        prop: function (e, t) {
            return Le(this, ge.prop, e, t, arguments.length > 1)
        }, removeProp: function (e) {
            return this.each(function () {
                delete this[ge.propFix[e] || e]
            })
        }
    }), ge.extend({
        prop: function (e, t, n) {
            var i, o, s = e.nodeType;
            if (3 !== s && 8 !== s && 2 !== s) return 1 === s && ge.isXMLDoc(e) || (t = ge.propFix[t] || t, o = ge.propHooks[t]), void 0 !== n ? o && "set" in o && void 0 !== (i = o.set(e, n, t)) ? i : e[t] = n : o && "get" in o && null !== (i = o.get(e, t)) ? i : e[t]
        }, propHooks: {
            tabIndex: {
                get: function (e) {
                    var t = ge.find.attr(e, "tabindex");
                    return t ? parseInt(t, 10) : kt.test(e.nodeName) || Tt.test(e.nodeName) && e.href ? 0 : -1
                }
            }
        }, propFix: {"for": "htmlFor", "class": "className"}
    }), fe.optSelected || (ge.propHooks.selected = {
        get: function (e) {
            var t = e.parentNode;
            return t && t.parentNode && t.parentNode.selectedIndex, null
        }, set: function (e) {
            var t = e.parentNode;
            t && (t.selectedIndex, t.parentNode && t.parentNode.selectedIndex)
        }
    }), ge.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function () {
        ge.propFix[this.toLowerCase()] = this
    }), ge.fn.extend({
        addClass: function (e) {
            var t, n, i, o, s, r, a, l = 0;
            if (ge.isFunction(e)) return this.each(function (t) {
                ge(this).addClass(e.call(this, t, K(this)))
            });
            if ("string" == typeof e && e) for (t = e.match(He) || []; n = this[l++];) if (o = K(n), i = 1 === n.nodeType && " " + Y(o) + " ") {
                for (r = 0; s = t[r++];) i.indexOf(" " + s + " ") < 0 && (i += s + " ");
                a = Y(i), o !== a && n.setAttribute("class", a)
            }
            return this
        }, removeClass: function (e) {
            var t, n, i, o, s, r, a, l = 0;
            if (ge.isFunction(e)) return this.each(function (t) {
                ge(this).removeClass(e.call(this, t, K(this)))
            });
            if (!arguments.length) return this.attr("class", "");
            if ("string" == typeof e && e) for (t = e.match(He) || []; n = this[l++];) if (o = K(n), i = 1 === n.nodeType && " " + Y(o) + " ") {
                for (r = 0; s = t[r++];) for (; i.indexOf(" " + s + " ") > -1;) i = i.replace(" " + s + " ", " ");
                a = Y(i), o !== a && n.setAttribute("class", a)
            }
            return this
        }, toggleClass: function (e, t) {
            var n = "undefined" == typeof e ? "undefined" : _typeof(e);
            return "boolean" == typeof t && "string" === n ? t ? this.addClass(e) : this.removeClass(e) : ge.isFunction(e) ? this.each(function (n) {
                ge(this).toggleClass(e.call(this, n, K(this), t), t)
            }) : this.each(function () {
                var t, i, o, s;
                if ("string" === n) for (i = 0, o = ge(this), s = e.match(He) || []; t = s[i++];) o.hasClass(t) ? o.removeClass(t) : o.addClass(t); else void 0 !== e && "boolean" !== n || (t = K(this), t && Fe.set(this, "__className__", t), this.setAttribute && this.setAttribute("class", t || e === !1 ? "" : Fe.get(this, "__className__") || ""))
            })
        }, hasClass: function (e) {
            var t, n, i = 0;
            for (t = " " + e + " "; n = this[i++];) if (1 === n.nodeType && (" " + Y(K(n)) + " ").indexOf(t) > -1) return !0;
            return !1
        }
    });
    var xt = /\r/g;
    ge.fn.extend({
        val: function (e) {
            var t, n, i, o = this[0];
            {
                if (arguments.length) return i = ge.isFunction(e), this.each(function (n) {
                    var o;
                    1 === this.nodeType && (o = i ? e.call(this, n, ge(this).val()) : e, null == o ? o = "" : "number" == typeof o ? o += "" : Array.isArray(o) && (o = ge.map(o, function (e) {
                        return null == e ? "" : e + ""
                    })), t = ge.valHooks[this.type] || ge.valHooks[this.nodeName.toLowerCase()], t && "set" in t && void 0 !== t.set(this, o, "value") || (this.value = o))
                });
                if (o) return t = ge.valHooks[o.type] || ge.valHooks[o.nodeName.toLowerCase()], t && "get" in t && void 0 !== (n = t.get(o, "value")) ? n : (n = o.value, "string" == typeof n ? n.replace(xt, "") : null == n ? "" : n)
            }
        }
    }), ge.extend({
        valHooks: {
            option: {
                get: function (e) {
                    var t = ge.find.attr(e, "value");
                    return null != t ? t : Y(ge.text(e))
                }
            }, select: {
                get: function (e) {
                    var t, n, i, s = e.options, r = e.selectedIndex, a = "select-one" === e.type, l = a ? null : [],
                        c = a ? r + 1 : s.length;
                    for (i = r < 0 ? c : a ? r : 0; i < c; i++) if (n = s[i], (n.selected || i === r) && !n.disabled && (!n.parentNode.disabled || !o(n.parentNode, "optgroup"))) {
                        if (t = ge(n).val(), a) return t;
                        l.push(t)
                    }
                    return l
                }, set: function (e, t) {
                    for (var n, i, o = e.options, s = ge.makeArray(t), r = o.length; r--;) i = o[r], (i.selected = ge.inArray(ge.valHooks.option.get(i), s) > -1) && (n = !0);
                    return n || (e.selectedIndex = -1), s
                }
            }
        }
    }), ge.each(["radio", "checkbox"], function () {
        ge.valHooks[this] = {
            set: function (e, t) {
                if (Array.isArray(t)) return e.checked = ge.inArray(ge(e).val(), t) > -1
            }
        }, fe.checkOn || (ge.valHooks[this].get = function (e) {
            return null === e.getAttribute("value") ? "on" : e.value
        })
    });
    var St = /^(?:focusinfocus|focusoutblur)$/;
    ge.extend(ge.event, {
        trigger: function (t, n, i, o) {
            var s, r, a, l, c, d, u, p = [i || ne], f = de.call(t, "type") ? t.type : t,
                h = de.call(t, "namespace") ? t.namespace.split(".") : [];
            if (r = a = i = i || ne, 3 !== i.nodeType && 8 !== i.nodeType && !St.test(f + ge.event.triggered) && (f.indexOf(".") > -1 && (h = f.split("."), f = h.shift(), h.sort()), c = f.indexOf(":") < 0 && "on" + f, t = t[ge.expando] ? t : new ge.Event(f, "object" === ("undefined" == typeof t ? "undefined" : _typeof(t)) && t), t.isTrigger = o ? 2 : 3, t.namespace = h.join("."), t.rnamespace = t.namespace ? new RegExp("(^|\\.)" + h.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, t.result = void 0, t.target || (t.target = i), n = null == n ? [t] : ge.makeArray(n, [t]), u = ge.event.special[f] || {}, o || !u.trigger || u.trigger.apply(i, n) !== !1)) {
                if (!o && !u.noBubble && !ge.isWindow(i)) {
                    for (l = u.delegateType || f, St.test(l + f) || (r = r.parentNode); r; r = r.parentNode) p.push(r), a = r;
                    a === (i.ownerDocument || ne) && p.push(a.defaultView || a.parentWindow || e)
                }
                for (s = 0; (r = p[s++]) && !t.isPropagationStopped();) t.type = s > 1 ? l : u.bindType || f, d = (Fe.get(r, "events") || {})[t.type] && Fe.get(r, "handle"), d && d.apply(r, n), d = c && r[c], d && d.apply && je(r) && (t.result = d.apply(r, n), t.result === !1 && t.preventDefault());
                return t.type = f, o || t.isDefaultPrevented() || u._default && u._default.apply(p.pop(), n) !== !1 || !je(i) || c && ge.isFunction(i[f]) && !ge.isWindow(i) && (a = i[c], a && (i[c] = null), ge.event.triggered = f, i[f](), ge.event.triggered = void 0, a && (i[c] = a)), t.result
            }
        }, simulate: function (e, t, n) {
            var i = ge.extend(new ge.Event, n, {type: e, isSimulated: !0});
            ge.event.trigger(i, null, t)
        }
    }), ge.fn.extend({
        trigger: function (e, t) {
            return this.each(function () {
                ge.event.trigger(e, t, this)
            })
        }, triggerHandler: function (e, t) {
            var n = this[0];
            if (n) return ge.event.trigger(e, t, n, !0)
        }
    }), ge.each("blur focus focusin focusout resize scroll click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup contextmenu".split(" "), function (e, t) {
        ge.fn[t] = function (e, n) {
            return arguments.length > 0 ? this.on(t, null, e, n) : this.trigger(t)
        }
    }), ge.fn.extend({
        hover: function (e, t) {
            return this.mouseenter(e).mouseleave(t || e)
        }
    }), fe.focusin = "onfocusin" in e, fe.focusin || ge.each({focus: "focusin", blur: "focusout"}, function (e, t) {
        var n = function (e) {
            ge.event.simulate(t, e.target, ge.event.fix(e))
        };
        ge.event.special[t] = {
            setup: function () {
                var i = this.ownerDocument || this, o = Fe.access(i, t);
                o || i.addEventListener(e, n, !0), Fe.access(i, t, (o || 0) + 1)
            }, teardown: function () {
                var i = this.ownerDocument || this, o = Fe.access(i, t) - 1;
                o ? Fe.access(i, t, o) : (i.removeEventListener(e, n, !0), Fe.remove(i, t))
            }
        }
    });
    var Ct = e.location, $t = ge.now(), At = /\?/;
    ge.parseXML = function (t) {
        var n;
        if (!t || "string" != typeof t) return null;
        try {
            n = (new e.DOMParser).parseFromString(t, "text/xml")
        } catch (i) {
            n = void 0
        }
        return n && !n.getElementsByTagName("parsererror").length || ge.error("Invalid XML: " + t), n
    };
    var _t = /\[\]$/, Et = /\r?\n/g, Dt = /^(?:submit|button|image|reset|file)$/i,
        Ht = /^(?:input|select|textarea|keygen)/i;
    ge.param = function (e, t) {
        var n, i = [], o = function (e, t) {
            var n = ge.isFunction(t) ? t() : t;
            i[i.length] = encodeURIComponent(e) + "=" + encodeURIComponent(null == n ? "" : n)
        };
        if (Array.isArray(e) || e.jquery && !ge.isPlainObject(e)) ge.each(e, function () {
            o(this.name, this.value)
        }); else for (n in e) Q(n, e[n], t, o);
        return i.join("&")
    }, ge.fn.extend({
        serialize: function () {
            return ge.param(this.serializeArray())
        }, serializeArray: function () {
            return this.map(function () {
                var e = ge.prop(this, "elements");
                return e ? ge.makeArray(e) : this
            }).filter(function () {
                var e = this.type;
                return this.name && !ge(this).is(":disabled") && Ht.test(this.nodeName) && !Dt.test(e) && (this.checked || !Xe.test(e))
            }).map(function (e, t) {
                var n = ge(this).val();
                return null == n ? null : Array.isArray(n) ? ge.map(n, function (e) {
                    return {name: t.name, value: e.replace(Et, "\r\n")}
                }) : {name: t.name, value: n.replace(Et, "\r\n")}
            }).get()
        }
    });
    var Ot = /%20/g, zt = /#.*$/, Lt = /([?&])_=[^&]*/, jt = /^(.*?):[ \t]*([^\r\n]*)$/gm,
        Ft = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/, Pt = /^(?:GET|HEAD)$/, Nt = /^\/\//, qt = {},
        Mt = {}, Rt = "*/".concat("*"), It = ne.createElement("a");
    It.href = Ct.href, ge.extend({
        active: 0,
        lastModified: {},
        etag: {},
        ajaxSettings: {
            url: Ct.href,
            type: "GET",
            isLocal: Ft.test(Ct.protocol),
            global: !0,
            processData: !0,
            async: !0,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            accepts: {
                "*": Rt,
                text: "text/plain",
                html: "text/html",
                xml: "application/xml, text/xml",
                json: "application/json, text/javascript"
            },
            contents: {xml: /\bxml\b/, html: /\bhtml/, json: /\bjson\b/},
            responseFields: {xml: "responseXML", text: "responseText", json: "responseJSON"},
            converters: {"* text": String, "text html": !0, "text json": JSON.parse, "text xml": ge.parseXML},
            flatOptions: {url: !0, context: !0}
        },
        ajaxSetup: function (e, t) {
            return t ? J(J(e, ge.ajaxSettings), t) : J(ge.ajaxSettings, e)
        },
        ajaxPrefilter: G(qt),
        ajaxTransport: G(Mt),
        ajax: function (t, n) {
            function i(t, n, i, a) {
                var c, p, f, b, k, T = n;
                d || (d = !0, l && e.clearTimeout(l), o = void 0, r = a || "", x.readyState = t > 0 ? 4 : 0, c = t >= 200 && t < 300 || 304 === t, i && (b = Z(h, x, i)), b = ee(h, b, x, c), c ? (h.ifModified && (k = x.getResponseHeader("Last-Modified"), k && (ge.lastModified[s] = k), k = x.getResponseHeader("etag"), k && (ge.etag[s] = k)), 204 === t || "HEAD" === h.type ? T = "nocontent" : 304 === t ? T = "notmodified" : (T = b.state, p = b.data, f = b.error, c = !f)) : (f = T, !t && T || (T = "error", t < 0 && (t = 0))), x.status = t, x.statusText = (n || T) + "", c ? m.resolveWith(g, [p, T, x]) : m.rejectWith(g, [x, T, f]), x.statusCode(w), w = void 0, u && v.trigger(c ? "ajaxSuccess" : "ajaxError", [x, h, c ? p : f]), y.fireWith(g, [x, T]), u && (v.trigger("ajaxComplete", [x, h]), --ge.active || ge.event.trigger("ajaxStop")))
            }

            "object" === ("undefined" == typeof t ? "undefined" : _typeof(t)) && (n = t, t = void 0), n = n || {};
            var o, s, r, a, l, c, d, u, p, f, h = ge.ajaxSetup({}, n), g = h.context || h,
                v = h.context && (g.nodeType || g.jquery) ? ge(g) : ge.event, m = ge.Deferred(),
                y = ge.Callbacks("once memory"), w = h.statusCode || {}, b = {}, k = {}, T = "canceled", x = {
                    readyState: 0, getResponseHeader: function (e) {
                        var t;
                        if (d) {
                            if (!a) for (a = {}; t = jt.exec(r);) a[t[1].toLowerCase()] = t[2];
                            t = a[e.toLowerCase()]
                        }
                        return null == t ? null : t
                    }, getAllResponseHeaders: function () {
                        return d ? r : null
                    }, setRequestHeader: function (e, t) {
                        return null == d && (e = k[e.toLowerCase()] = k[e.toLowerCase()] || e, b[e] = t), this
                    }, overrideMimeType: function (e) {
                        return null == d && (h.mimeType = e), this
                    }, statusCode: function (e) {
                        var t;
                        if (e) if (d) x.always(e[x.status]); else for (t in e) w[t] = [w[t], e[t]];
                        return this
                    }, abort: function (e) {
                        var t = e || T;
                        return o && o.abort(t), i(0, t), this
                    }
                };
            if (m.promise(x), h.url = ((t || h.url || Ct.href) + "").replace(Nt, Ct.protocol + "//"), h.type = n.method || n.type || h.method || h.type, h.dataTypes = (h.dataType || "*").toLowerCase().match(He) || [""], null == h.crossDomain) {
                c = ne.createElement("a");
                try {
                    c.href = h.url, c.href = c.href, h.crossDomain = It.protocol + "//" + It.host != c.protocol + "//" + c.host
                } catch (S) {
                    h.crossDomain = !0
                }
            }
            if (h.data && h.processData && "string" != typeof h.data && (h.data = ge.param(h.data, h.traditional)), V(qt, h, n, x), d) return x;
            u = ge.event && h.global, u && 0 === ge.active++ && ge.event.trigger("ajaxStart"), h.type = h.type.toUpperCase(), h.hasContent = !Pt.test(h.type), s = h.url.replace(zt, ""), h.hasContent ? h.data && h.processData && 0 === (h.contentType || "").indexOf("application/x-www-form-urlencoded") && (h.data = h.data.replace(Ot, "+")) : (f = h.url.slice(s.length), h.data && (s += (At.test(s) ? "&" : "?") + h.data, delete h.data), h.cache === !1 && (s = s.replace(Lt, "$1"), f = (At.test(s) ? "&" : "?") + "_=" + $t++ + f), h.url = s + f), h.ifModified && (ge.lastModified[s] && x.setRequestHeader("If-Modified-Since", ge.lastModified[s]), ge.etag[s] && x.setRequestHeader("If-None-Match", ge.etag[s])), (h.data && h.hasContent && h.contentType !== !1 || n.contentType) && x.setRequestHeader("Content-Type", h.contentType), x.setRequestHeader("Accept", h.dataTypes[0] && h.accepts[h.dataTypes[0]] ? h.accepts[h.dataTypes[0]] + ("*" !== h.dataTypes[0] ? ", " + Rt + "; q=0.01" : "") : h.accepts["*"]);
            for (p in h.headers) x.setRequestHeader(p, h.headers[p]);
            if (h.beforeSend && (h.beforeSend.call(g, x, h) === !1 || d)) return x.abort();
            if (T = "abort", y.add(h.complete), x.done(h.success), x.fail(h.error), o = V(Mt, h, n, x)) {
                if (x.readyState = 1, u && v.trigger("ajaxSend", [x, h]), d) return x;
                h.async && h.timeout > 0 && (l = e.setTimeout(function () {
                    x.abort("timeout")
                }, h.timeout));
                try {
                    d = !1, o.send(b, i)
                } catch (S) {
                    if (d) throw S;
                    i(-1, S)
                }
            } else i(-1, "No Transport");
            return x
        },
        getJSON: function (e, t, n) {
            return ge.get(e, t, n, "json")
        },
        getScript: function (e, t) {
            return ge.get(e, void 0, t, "script")
        }
    }), ge.each(["get", "post"], function (e, t) {
        ge[t] = function (e, n, i, o) {
            return ge.isFunction(n) && (o = o || i, i = n, n = void 0), ge.ajax(ge.extend({
                url: e,
                type: t,
                dataType: o,
                data: n,
                success: i
            }, ge.isPlainObject(e) && e))
        }
    }), ge._evalUrl = function (e) {
        return ge.ajax({url: e, type: "GET", dataType: "script", cache: !0, async: !1, global: !1, "throws": !0})
    }, ge.fn.extend({
        wrapAll: function (e) {
            var t;
            return this[0] && (ge.isFunction(e) && (e = e.call(this[0])), t = ge(e, this[0].ownerDocument).eq(0).clone(!0), this[0].parentNode && t.insertBefore(this[0]), t.map(function () {
                for (var e = this; e.firstElementChild;) e = e.firstElementChild;
                return e
            }).append(this)), this
        }, wrapInner: function (e) {
            return ge.isFunction(e) ? this.each(function (t) {
                ge(this).wrapInner(e.call(this, t))
            }) : this.each(function () {
                var t = ge(this), n = t.contents();
                n.length ? n.wrapAll(e) : t.append(e)
            })
        }, wrap: function (e) {
            var t = ge.isFunction(e);
            return this.each(function (n) {
                ge(this).wrapAll(t ? e.call(this, n) : e)
            })
        }, unwrap: function (e) {
            return this.parent(e).not("body").each(function () {
                ge(this).replaceWith(this.childNodes)
            }), this
        }
    }), ge.expr.pseudos.hidden = function (e) {
        return !ge.expr.pseudos.visible(e)
    }, ge.expr.pseudos.visible = function (e) {
        return !!(e.offsetWidth || e.offsetHeight || e.getClientRects().length)
    }, ge.ajaxSettings.xhr = function () {
        try {
            return new e.XMLHttpRequest
        } catch (t) {
        }
    };
    var Wt = {0: 200, 1223: 204}, Bt = ge.ajaxSettings.xhr();
    fe.cors = !!Bt && "withCredentials" in Bt, fe.ajax = Bt = !!Bt, ge.ajaxTransport(function (t) {
        var n, i;
        if (fe.cors || Bt && !t.crossDomain) return {
            send: function (o, s) {
                var r, a = t.xhr();
                if (a.open(t.type, t.url, t.async, t.username, t.password), t.xhrFields) for (r in t.xhrFields) a[r] = t.xhrFields[r];
                t.mimeType && a.overrideMimeType && a.overrideMimeType(t.mimeType), t.crossDomain || o["X-Requested-With"] || (o["X-Requested-With"] = "XMLHttpRequest");
                for (r in o) a.setRequestHeader(r, o[r]);
                n = function (e) {
                    return function () {
                        n && (n = i = a.onload = a.onerror = a.onabort = a.onreadystatechange = null, "abort" === e ? a.abort() : "error" === e ? "number" != typeof a.status ? s(0, "error") : s(a.status, a.statusText) : s(Wt[a.status] || a.status, a.statusText, "text" !== (a.responseType || "text") || "string" != typeof a.responseText ? {binary: a.response} : {text: a.responseText}, a.getAllResponseHeaders()))
                    }
                }, a.onload = n(), i = a.onerror = n("error"), void 0 !== a.onabort ? a.onabort = i : a.onreadystatechange = function () {
                    4 === a.readyState && e.setTimeout(function () {
                        n && i()
                    })
                }, n = n("abort");
                try {
                    a.send(t.hasContent && t.data || null)
                } catch (l) {
                    if (n) throw l
                }
            }, abort: function () {
                n && n()
            }
        }
    }), ge.ajaxPrefilter(function (e) {
        e.crossDomain && (e.contents.script = !1)
    }), ge.ajaxSetup({
        accepts: {script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},
        contents: {script: /\b(?:java|ecma)script\b/},
        converters: {
            "text script": function (e) {
                return ge.globalEval(e), e
            }
        }
    }), ge.ajaxPrefilter("script", function (e) {
        void 0 === e.cache && (e.cache = !1), e.crossDomain && (e.type = "GET")
    }), ge.ajaxTransport("script", function (e) {
        if (e.crossDomain) {
            var t, n;
            return {
                send: function (i, o) {
                    t = ge("<script>").prop({charset: e.scriptCharset, src: e.url}).on("load error", n = function (e) {
                        t.remove(), n = null, e && o("error" === e.type ? 404 : 200, e.type)
                    }), ne.head.appendChild(t[0])
                }, abort: function () {
                    n && n()
                }
            }
        }
    });
    var Ut = [], Xt = /(=)\?(?=&|$)|\?\?/;
    ge.ajaxSetup({
        jsonp: "callback", jsonpCallback: function () {
            var e = Ut.pop() || ge.expando + "_" + $t++;
            return this[e] = !0, e
        }
    }), ge.ajaxPrefilter("json jsonp", function (t, n, i) {
        var o, s, r,
            a = t.jsonp !== !1 && (Xt.test(t.url) ? "url" : "string" == typeof t.data && 0 === (t.contentType || "").indexOf("application/x-www-form-urlencoded") && Xt.test(t.data) && "data");
        if (a || "jsonp" === t.dataTypes[0]) return o = t.jsonpCallback = ge.isFunction(t.jsonpCallback) ? t.jsonpCallback() : t.jsonpCallback, a ? t[a] = t[a].replace(Xt, "$1" + o) : t.jsonp !== !1 && (t.url += (At.test(t.url) ? "&" : "?") + t.jsonp + "=" + o), t.converters["script json"] = function () {
            return r || ge.error(o + " was not called"), r[0]
        }, t.dataTypes[0] = "json", s = e[o], e[o] = function () {
            r = arguments
        }, i.always(function () {
            void 0 === s ? ge(e).removeProp(o) : e[o] = s, t[o] && (t.jsonpCallback = n.jsonpCallback, Ut.push(o)), r && ge.isFunction(s) && s(r[0]), r = s = void 0
        }), "script"
    }), fe.createHTMLDocument = function () {
        var e = ne.implementation.createHTMLDocument("").body;
        return e.innerHTML = "<form></form><form></form>", 2 === e.childNodes.length
    }(), ge.parseHTML = function (e, t, n) {
        if ("string" != typeof e) return [];
        "boolean" == typeof t && (n = t, t = !1);
        var i, o, s;
        return t || (fe.createHTMLDocument ? (t = ne.implementation.createHTMLDocument(""), i = t.createElement("base"), i.href = ne.location.href, t.head.appendChild(i)) : t = ne), o = Se.exec(e), s = !n && [], o ? [t.createElement(o[1])] : (o = b([e], t, s), s && s.length && ge(s).remove(), ge.merge([], o.childNodes))
    }, ge.fn.load = function (e, t, n) {
        var i, o, s, r = this, a = e.indexOf(" ");
        return a > -1 && (i = Y(e.slice(a)), e = e.slice(0, a)), ge.isFunction(t) ? (n = t, t = void 0) : t && "object" === ("undefined" == typeof t ? "undefined" : _typeof(t)) && (o = "POST"), r.length > 0 && ge.ajax({
            url: e,
            type: o || "GET",
            dataType: "html",
            data: t
        }).done(function (e) {
            s = arguments, r.html(i ? ge("<div>").append(ge.parseHTML(e)).find(i) : e)
        }).always(n && function (e, t) {
            r.each(function () {
                n.apply(this, s || [e.responseText, t, e])
            })
        }), this
    }, ge.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function (e, t) {
        ge.fn[t] = function (e) {
            return this.on(t, e)
        }
    }), ge.expr.pseudos.animated = function (e) {
        return ge.grep(ge.timers, function (t) {
            return e === t.elem
        }).length
    }, ge.offset = {
        setOffset: function (e, t, n) {
            var i, o, s, r, a, l, c, d = ge.css(e, "position"), u = ge(e), p = {};
            "static" === d && (e.style.position = "relative"), a = u.offset(), s = ge.css(e, "top"), l = ge.css(e, "left"), c = ("absolute" === d || "fixed" === d) && (s + l).indexOf("auto") > -1, c ? (i = u.position(), r = i.top, o = i.left) : (r = parseFloat(s) || 0, o = parseFloat(l) || 0), ge.isFunction(t) && (t = t.call(e, n, ge.extend({}, a))), null != t.top && (p.top = t.top - a.top + r), null != t.left && (p.left = t.left - a.left + o), "using" in t ? t.using.call(e, p) : u.css(p)
        }
    }, ge.fn.extend({
        offset: function (e) {
            if (arguments.length) return void 0 === e ? this : this.each(function (t) {
                ge.offset.setOffset(this, e, t)
            });
            var t, n, i, o, s = this[0];
            if (s) return s.getClientRects().length ? (i = s.getBoundingClientRect(), t = s.ownerDocument, n = t.documentElement, o = t.defaultView, {
                top: i.top + o.pageYOffset - n.clientTop,
                left: i.left + o.pageXOffset - n.clientLeft
            }) : {top: 0, left: 0}
        }, position: function () {
            if (this[0]) {
                var e, t, n = this[0], i = {top: 0, left: 0};
                return "fixed" === ge.css(n, "position") ? t = n.getBoundingClientRect() : (e = this.offsetParent(), t = this.offset(), o(e[0], "html") || (i = e.offset()), i = {
                    top: i.top + ge.css(e[0], "borderTopWidth", !0),
                    left: i.left + ge.css(e[0], "borderLeftWidth", !0)
                }), {
                    top: t.top - i.top - ge.css(n, "marginTop", !0),
                    left: t.left - i.left - ge.css(n, "marginLeft", !0)
                }
            }
        }, offsetParent: function () {
            return this.map(function () {
                for (var e = this.offsetParent; e && "static" === ge.css(e, "position");) e = e.offsetParent;
                return e || Ve
            })
        }
    }), ge.each({scrollLeft: "pageXOffset", scrollTop: "pageYOffset"}, function (e, t) {
        var n = "pageYOffset" === t;
        ge.fn[e] = function (i) {
            return Le(this, function (e, i, o) {
                var s;
                return ge.isWindow(e) ? s = e : 9 === e.nodeType && (s = e.defaultView), void 0 === o ? s ? s[t] : e[i] : void(s ? s.scrollTo(n ? s.pageXOffset : o, n ? o : s.pageYOffset) : e[i] = o)
            }, e, i, arguments.length)
        }
    }), ge.each(["top", "left"], function (e, t) {
        ge.cssHooks[t] = z(fe.pixelPosition, function (e, n) {
            if (n) return n = O(e, t), at.test(n) ? ge(e).position()[t] + "px" : n
        })
    }), ge.each({Height: "height", Width: "width"}, function (e, t) {
        ge.each({padding: "inner" + e, content: t, "": "outer" + e}, function (n, i) {
            ge.fn[i] = function (o, s) {
                var r = arguments.length && (n || "boolean" != typeof o),
                    a = n || (o === !0 || s === !0 ? "margin" : "border");
                return Le(this, function (t, n, o) {
                    var s;
                    return ge.isWindow(t) ? 0 === i.indexOf("outer") ? t["inner" + e] : t.document.documentElement["client" + e] : 9 === t.nodeType ? (s = t.documentElement, Math.max(t.body["scroll" + e], s["scroll" + e], t.body["offset" + e], s["offset" + e], s["client" + e])) : void 0 === o ? ge.css(t, n, a) : ge.style(t, n, o, a)
                }, t, r ? o : void 0, r)
            }
        })
    }), ge.fn.extend({
        bind: function (e, t, n) {
            return this.on(e, null, t, n)
        }, unbind: function (e, t) {
            return this.off(e, null, t)
        }, delegate: function (e, t, n, i) {
            return this.on(t, e, n, i)
        }, undelegate: function (e, t, n) {
            return 1 === arguments.length ? this.off(e, "**") : this.off(t, e || "**", n)
        }
    }), ge.holdReady = function (e) {
        e ? ge.readyWait++ : ge.ready(!0)
    }, ge.isArray = Array.isArray, ge.parseJSON = JSON.parse, ge.nodeName = o, "function" == typeof define && define.amd && define("jquery", [], function () {
        return ge
    });
    var Yt = e.jQuery, Kt = e.$;
    return ge.noConflict = function (t) {
        return e.$ === ge && (e.$ = Kt), t && e.jQuery === ge && (e.jQuery = Yt), ge
    }, t || (e.jQuery = e.$ = ge), ge
}), function (e, t) {
    "object" == typeof exports && "object" == typeof module ? module.exports = t() : "function" == typeof define && define.amd ? define("whatInput", [], t) : "object" == typeof exports ? exports.whatInput = t() : e.whatInput = t()
}(this, function () {
    return function (e) {
        function t(i) {
            if (n[i]) return n[i].exports;
            var o = n[i] = {exports: {}, id: i, loaded: !1};
            return e[i].call(o.exports, o, o.exports, t), o.loaded = !0, o.exports
        }

        var n = {};
        return t.m = e, t.c = n, t.p = "", t(0)
    }([function (e, t) {
        e.exports = function () {
            var e = document.documentElement, t = "initial", n = null, i = ["input", "select", "textarea"],
                o = [16, 17, 18, 91, 93], s = {
                    keyup: "keyboard",
                    mousedown: "mouse",
                    mousemove: "mouse",
                    MSPointerDown: "pointer",
                    MSPointerMove: "pointer",
                    pointerdown: "pointer",
                    pointermove: "pointer",
                    touchstart: "touch"
                }, r = [], a = !1, l = {2: "touch", 3: "touch", 4: "mouse"}, c = null, d = function () {
                    s[m()] = "mouse", u(), f()
                }, u = function () {
                    window.PointerEvent ? (e.addEventListener("pointerdown", p), e.addEventListener("pointermove", h)) : window.MSPointerEvent ? (e.addEventListener("MSPointerDown", p), e.addEventListener("MSPointerMove", h)) : (e.addEventListener("mousedown", p), e.addEventListener("mousemove", h), "ontouchstart" in window && e.addEventListener("touchstart", g)), e.addEventListener(m(), h), e.addEventListener("keydown", p), e.addEventListener("keyup", p)
                }, p = function (e) {
                    if (!a) {
                        var r = e.which, l = s[e.type];
                        if ("pointer" === l && (l = v(e)), t !== l || n !== l) {
                            var c = document.activeElement,
                                d = !(!c || !c.nodeName || i.indexOf(c.nodeName.toLowerCase()) !== -1);
                            ("touch" === l || "mouse" === l && o.indexOf(r) === -1 || "keyboard" === l && d) && (t = n = l, f())
                        }
                    }
                }, f = function () {
                    e.setAttribute("data-whatinput", t), e.setAttribute("data-whatintent", t), r.indexOf(t) === -1 && (r.push(t), e.className += " whatinput-types-" + t)
                }, h = function (t) {
                    if (!a) {
                        var i = s[t.type];
                        "pointer" === i && (i = v(t)), n !== i && (n = i, e.setAttribute("data-whatintent", n))
                    }
                }, g = function (e) {
                    window.clearTimeout(c), p(e), a = !0, c = window.setTimeout(function () {
                        a = !1
                    }, 200)
                }, v = function (e) {
                    return "number" == typeof e.pointerType ? l[e.pointerType] : "pen" === e.pointerType ? "touch" : e.pointerType
                }, m = function () {
                    return "onwheel" in document.createElement("div") ? "wheel" : void 0 !== document.onmousewheel ? "mousewheel" : "DOMMouseScroll"
                };
            return "addEventListener" in window && Array.prototype.indexOf && d(), {
                ask: function (e) {
                    return "loose" === e ? n : t
                }, types: function () {
                    return r
                }
            }
        }()
    }])
});
var _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (e) {
    return typeof e
} : function (e) {
    return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
};
!function (e) {
    function t(e) {
        if (void 0 === Function.prototype.name) {
            var t = /function\s([^(]{1,})\(/, n = t.exec(e.toString());
            return n && n.length > 1 ? n[1].trim() : ""
        }
        return void 0 === e.prototype ? e.constructor.name : e.prototype.constructor.name
    }

    function n(e) {
        return "true" === e || "false" !== e && (isNaN(1 * e) ? e : parseFloat(e))
    }

    function i(e) {
        return e.replace(/([a-z])([A-Z])/g, "$1-$2").toLowerCase()
    }

    var o = "6.3.1", s = {
        version: o, _plugins: {}, _uuids: [], rtl: function () {
            return "rtl" === e("html").attr("dir")
        }, plugin: function (e, n) {
            var o = n || t(e), s = i(o);
            this._plugins[s] = this[o] = e
        }, registerPlugin: function (e, n) {
            var o = n ? i(n) : t(e.constructor).toLowerCase();
            e.uuid = this.GetYoDigits(6, o), e.$element.attr("data-" + o) || e.$element.attr("data-" + o, e.uuid), e.$element.data("zfPlugin") || e.$element.data("zfPlugin", e), e.$element.trigger("init.zf." + o), this._uuids.push(e.uuid)
        }, unregisterPlugin: function (e) {
            var n = i(t(e.$element.data("zfPlugin").constructor));
            this._uuids.splice(this._uuids.indexOf(e.uuid), 1), e.$element.removeAttr("data-" + n).removeData("zfPlugin").trigger("destroyed.zf." + n);
            for (var o in e) e[o] = null
        }, reInit: function (t) {
            var n = t instanceof e;
            try {
                if (n) t.each(function () {
                    e(this).data("zfPlugin")._init()
                }); else {
                    var o = "undefined" == typeof t ? "undefined" : _typeof(t), s = this, r = {
                        object: function (t) {
                            t.forEach(function (t) {
                                t = i(t), e("[data-" + t + "]").foundation("_init")
                            })
                        }, string: function () {
                            t = i(t), e("[data-" + t + "]").foundation("_init")
                        }, undefined: function () {
                            this.object(Object.keys(s._plugins))
                        }
                    };
                    r[o](t)
                }
            } catch (a) {
                console.error(a)
            } finally {
                return t
            }
        }, GetYoDigits: function (e, t) {
            return e = e || 6, Math.round(Math.pow(36, e + 1) - Math.random() * Math.pow(36, e)).toString(36).slice(1) + (t ? "-" + t : "")
        }, reflow: function (t, i) {
            "undefined" == typeof i ? i = Object.keys(this._plugins) : "string" == typeof i && (i = [i]);
            var o = this;
            e.each(i, function (i, s) {
                var r = o._plugins[s], a = e(t).find("[data-" + s + "]").addBack("[data-" + s + "]");
                a.each(function () {
                    var t = e(this), i = {};
                    if (t.data("zfPlugin")) return void console.warn("Tried to initialize " + s + " on an element that already has a Foundation plugin.");
                    if (t.attr("data-options")) {
                        t.attr("data-options").split(";").forEach(function (e, t) {
                            var o = e.split(":").map(function (e) {
                                return e.trim()
                            });
                            o[0] && (i[o[0]] = n(o[1]))
                        })
                    }
                    try {
                        t.data("zfPlugin", new r(e(this), i))
                    } catch (o) {
                        console.error(o)
                    } finally {
                        return
                    }
                })
            })
        }, getFnName: t, transitionend: function (e) {
            var t, n = {
                transition: "transitionend",
                WebkitTransition: "webkitTransitionEnd",
                MozTransition: "transitionend",
                OTransition: "otransitionend"
            }, i = document.createElement("div");
            for (var o in n) "undefined" != typeof i.style[o] && (t = n[o]);
            return t ? t : (t = setTimeout(function () {
                e.triggerHandler("transitionend", [e])
            }, 1), "transitionend")
        }
    };
    s.util = {
        throttle: function (e, t) {
            var n = null;
            return function () {
                var i = this, o = arguments;
                null === n && (n = setTimeout(function () {
                    e.apply(i, o), n = null
                }, t))
            }
        }
    };
    var r = function (n) {
        var i = "undefined" == typeof n ? "undefined" : _typeof(n), o = e("meta.foundation-mq"), r = e(".no-js");
        if (o.length || e('<meta class="foundation-mq">').appendTo(document.head), r.length && r.removeClass("no-js"), "undefined" === i) s.MediaQuery._init(), s.reflow(this); else {
            if ("string" !== i) throw new TypeError("We're sorry, " + i + " is not a valid parameter. You must use a string representing the method you wish to invoke.");
            var a = Array.prototype.slice.call(arguments, 1), l = this.data("zfPlugin");
            if (void 0 === l || void 0 === l[n]) throw new ReferenceError("We're sorry, '" + n + "' is not an available method for " + (l ? t(l) : "this element") + ".");
            1 === this.length ? l[n].apply(l, a) : this.each(function (t, i) {
                l[n].apply(e(i).data("zfPlugin"), a)
            })
        }
        return this
    };
    window.Foundation = s, e.fn.foundation = r, function () {
        Date.now && window.Date.now || (window.Date.now = Date.now = function () {
            return (new Date).getTime()
        });
        for (var e = ["webkit", "moz"], t = 0; t < e.length && !window.requestAnimationFrame; ++t) {
            var n = e[t];
            window.requestAnimationFrame = window[n + "RequestAnimationFrame"], window.cancelAnimationFrame = window[n + "CancelAnimationFrame"] || window[n + "CancelRequestAnimationFrame"]
        }
        if (/iP(ad|hone|od).*OS 6/.test(window.navigator.userAgent) || !window.requestAnimationFrame || !window.cancelAnimationFrame) {
            var i = 0;
            window.requestAnimationFrame = function (e) {
                var t = Date.now(), n = Math.max(i + 16, t);
                return setTimeout(function () {
                    e(i = n)
                }, n - t)
            }, window.cancelAnimationFrame = clearTimeout
        }
        window.performance && window.performance.now || (window.performance = {
            start: Date.now(), now: function () {
                return Date.now() - this.start
            }
        })
    }(), Function.prototype.bind || (Function.prototype.bind = function (e) {
        if ("function" != typeof this) throw new TypeError("Function.prototype.bind - what is trying to be bound is not callable");
        var t = Array.prototype.slice.call(arguments, 1), n = this, i = function () {
        }, o = function () {
            return n.apply(this instanceof i ? this : e, t.concat(Array.prototype.slice.call(arguments)))
        };
        return this.prototype && (i.prototype = this.prototype), o.prototype = new i, o
    })
}(jQuery), !function (e) {
    function t(e, t, i, o) {
        var s, r, a, l, c = n(e);
        if (t) {
            var d = n(t);
            r = c.offset.top + c.height <= d.height + d.offset.top, s = c.offset.top >= d.offset.top, a = c.offset.left >= d.offset.left, l = c.offset.left + c.width <= d.width + d.offset.left
        } else r = c.offset.top + c.height <= c.windowDims.height + c.windowDims.offset.top,
            s = c.offset.top >= c.windowDims.offset.top, a = c.offset.left >= c.windowDims.offset.left, l = c.offset.left + c.width <= c.windowDims.width;
        var u = [r, s, a, l];
        return i ? a === l == !0 : o ? s === r == !0 : u.indexOf(!1) === -1
    }

    function n(e, t) {
        if (e = e.length ? e[0] : e, e === window || e === document) throw new Error("I'm sorry, Dave. I'm afraid I can't do that.");
        var n = e.getBoundingClientRect(), i = e.parentNode.getBoundingClientRect(),
            o = document.body.getBoundingClientRect(), s = window.pageYOffset, r = window.pageXOffset;
        return {
            width: n.width,
            height: n.height,
            offset: {top: n.top + s, left: n.left + r},
            parentDims: {width: i.width, height: i.height, offset: {top: i.top + s, left: i.left + r}},
            windowDims: {width: o.width, height: o.height, offset: {top: s, left: r}}
        }
    }

    function i(e, t, i, o, s, r) {
        var a = n(e), l = t ? n(t) : null;
        switch (i) {
            case"top":
                return {
                    left: Foundation.rtl() ? l.offset.left - a.width + l.width : l.offset.left,
                    top: l.offset.top - (a.height + o)
                };
            case"left":
                return {left: l.offset.left - (a.width + s), top: l.offset.top};
            case"right":
                return {left: l.offset.left + l.width + s, top: l.offset.top};
            case"center top":
                return {left: l.offset.left + l.width / 2 - a.width / 2, top: l.offset.top - (a.height + o)};
            case"center bottom":
                return {left: r ? s : l.offset.left + l.width / 2 - a.width / 2, top: l.offset.top + l.height + o};
            case"center left":
                return {left: l.offset.left - (a.width + s), top: l.offset.top + l.height / 2 - a.height / 2};
            case"center right":
                return {left: l.offset.left + l.width + s + 1, top: l.offset.top + l.height / 2 - a.height / 2};
            case"center":
                return {
                    left: a.windowDims.offset.left + a.windowDims.width / 2 - a.width / 2,
                    top: a.windowDims.offset.top + a.windowDims.height / 2 - a.height / 2
                };
            case"reveal":
                return {left: (a.windowDims.width - a.width) / 2, top: a.windowDims.offset.top + o};
            case"reveal full":
                return {left: a.windowDims.offset.left, top: a.windowDims.offset.top};
            case"left bottom":
                return {left: l.offset.left, top: l.offset.top + l.height + o};
            case"right bottom":
                return {left: l.offset.left + l.width + s - a.width, top: l.offset.top + l.height + o};
            default:
                return {
                    left: Foundation.rtl() ? l.offset.left - a.width + l.width : l.offset.left + s,
                    top: l.offset.top + l.height + o
                }
        }
    }

    Foundation.Box = {ImNotTouchingYou: t, GetDimensions: n, GetOffsets: i}
}(jQuery), !function (e) {
    function t(e) {
        var t = {};
        for (var n in e) t[e[n]] = e[n];
        return t
    }

    var n = {
        9: "TAB",
        13: "ENTER",
        27: "ESCAPE",
        32: "SPACE",
        37: "ARROW_LEFT",
        38: "ARROW_UP",
        39: "ARROW_RIGHT",
        40: "ARROW_DOWN"
    }, i = {}, o = {
        keys: t(n), parseKey: function (e) {
            var t = n[e.which || e.keyCode] || String.fromCharCode(e.which).toUpperCase();
            return t = t.replace(/\W+/, ""), e.shiftKey && (t = "SHIFT_" + t), e.ctrlKey && (t = "CTRL_" + t), e.altKey && (t = "ALT_" + t), t = t.replace(/_$/, "")
        }, handleKey: function (t, n, o) {
            var s, r, a, l = i[n], c = this.parseKey(t);
            if (!l) return console.warn("Component not defined!");
            if (s = "undefined" == typeof l.ltr ? l : Foundation.rtl() ? e.extend({}, l.ltr, l.rtl) : e.extend({}, l.rtl, l.ltr), r = s[c], a = o[r], a && "function" == typeof a) {
                var d = a.apply();
                (o.handled || "function" == typeof o.handled) && o.handled(d)
            } else (o.unhandled || "function" == typeof o.unhandled) && o.unhandled()
        }, findFocusable: function (t) {
            return !!t && t.find("a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, object, embed, *[tabindex], *[contenteditable]").filter(function () {
                return !(!e(this).is(":visible") || e(this).attr("tabindex") < 0)
            })
        }, register: function (e, t) {
            i[e] = t
        }, trapFocus: function (e) {
            var t = Foundation.Keyboard.findFocusable(e), n = t.eq(0), i = t.eq(-1);
            e.on("keydown.zf.trapfocus", function (e) {
                e.target === i[0] && "TAB" === Foundation.Keyboard.parseKey(e) ? (e.preventDefault(), n.focus()) : e.target === n[0] && "SHIFT_TAB" === Foundation.Keyboard.parseKey(e) && (e.preventDefault(), i.focus())
            })
        }, releaseFocus: function (e) {
            e.off("keydown.zf.trapfocus")
        }
    };
    Foundation.Keyboard = o
}(jQuery);
var _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (e) {
    return typeof e
} : function (e) {
    return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
};
!function (e) {
    function t(e) {
        var t = {};
        return "string" != typeof e ? t : (e = e.trim().slice(1, -1)) ? t = e.split("&").reduce(function (e, t) {
            var n = t.replace(/\+/g, " ").split("="), i = n[0], o = n[1];
            return i = decodeURIComponent(i), o = void 0 === o ? null : decodeURIComponent(o), e.hasOwnProperty(i) ? Array.isArray(e[i]) ? e[i].push(o) : e[i] = [e[i], o] : e[i] = o, e
        }, {}) : t
    }

    var n = {
        queries: [], current: "", _init: function () {
            var n, i = this, o = e(".foundation-mq").css("font-family");
            n = t(o);
            for (var s in n) n.hasOwnProperty(s) && i.queries.push({
                name: s,
                value: "only screen and (min-width: " + n[s] + ")"
            });
            this.current = this._getCurrentSize(), this._watcher()
        }, atLeast: function (e) {
            var t = this.get(e);
            return !!t && window.matchMedia(t).matches
        }, is: function (e) {
            return e = e.trim().split(" "), e.length > 1 && "only" === e[1] ? e[0] === this._getCurrentSize() : this.atLeast(e[0])
        }, get: function (e) {
            for (var t in this.queries) if (this.queries.hasOwnProperty(t)) {
                var n = this.queries[t];
                if (e === n.name) return n.value
            }
            return null
        }, _getCurrentSize: function () {
            for (var e, t = 0; t < this.queries.length; t++) {
                var n = this.queries[t];
                window.matchMedia(n.value).matches && (e = n)
            }
            return "object" === ("undefined" == typeof e ? "undefined" : _typeof(e)) ? e.name : e
        }, _watcher: function () {
            var t = this;
            e(window).on("resize.zf.mediaquery", function () {
                var n = t._getCurrentSize(), i = t.current;
                n !== i && (t.current = n, e(window).trigger("changed.zf.mediaquery", [n, i]))
            })
        }
    };
    Foundation.MediaQuery = n, window.matchMedia || (window.matchMedia = function () {
        var e = window.styleMedia || window.media;
        if (!e) {
            var t = document.createElement("style"), n = document.getElementsByTagName("script")[0], i = null;
            t.type = "text/css", t.id = "matchmediajs-test", n && n.parentNode && n.parentNode.insertBefore(t, n), i = "getComputedStyle" in window && window.getComputedStyle(t, null) || t.currentStyle, e = {
                matchMedium: function (e) {
                    var n = "@media " + e + "{ #matchmediajs-test { width: 1px; } }";
                    return t.styleSheet ? t.styleSheet.cssText = n : t.textContent = n, "1px" === i.width
                }
            }
        }
        return function (t) {
            return {matches: e.matchMedium(t || "all"), media: t || "all"}
        }
    }()), Foundation.MediaQuery = n
}(jQuery), !function (e) {
    function t(e, t, n) {
        function i(a) {
            r || (r = a), s = a - r, n.apply(t), s < e ? o = window.requestAnimationFrame(i, t) : (window.cancelAnimationFrame(o), t.trigger("finished.zf.animate", [t]).triggerHandler("finished.zf.animate", [t]))
        }

        var o, s, r = null;
        return 0 === e ? (n.apply(t), void t.trigger("finished.zf.animate", [t]).triggerHandler("finished.zf.animate", [t])) : void(o = window.requestAnimationFrame(i))
    }

    function n(t, n, s, r) {
        function a() {
            t || n.hide(), l(), r && r.apply(n)
        }

        function l() {
            n[0].style.transitionDuration = 0, n.removeClass(c + " " + d + " " + s)
        }

        if (n = e(n).eq(0), n.length) {
            var c = t ? i[0] : i[1], d = t ? o[0] : o[1];
            l(), n.addClass(s).css("transition", "none"), requestAnimationFrame(function () {
                n.addClass(c), t && n.show()
            }), requestAnimationFrame(function () {
                n[0].offsetWidth, n.css("transition", "").addClass(d)
            }), n.one(Foundation.transitionend(n), a)
        }
    }

    var i = ["mui-enter", "mui-leave"], o = ["mui-enter-active", "mui-leave-active"], s = {
        animateIn: function (e, t, i) {
            n(!0, e, t, i)
        }, animateOut: function (e, t, i) {
            n(!1, e, t, i)
        }
    };
    Foundation.Move = t, Foundation.Motion = s
}(jQuery), !function (e) {
    var t = {
        Feather: function (t) {
            var n = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : "zf";
            t.attr("role", "menubar");
            var i = t.find("li").attr({role: "menuitem"}), o = "is-" + n + "-submenu", s = o + "-item",
                r = "is-" + n + "-submenu-parent";
            i.each(function () {
                var t = e(this), i = t.children("ul");
                i.length && (t.addClass(r).attr({
                    "aria-haspopup": !0,
                    "aria-label": t.children("a:first").text()
                }), "drilldown" === n && t.attr({"aria-expanded": !1}), i.addClass("submenu " + o).attr({
                    "data-submenu": "",
                    role: "menu"
                }), "drilldown" === n && i.attr({"aria-hidden": !0})), t.parent("[data-submenu]").length && t.addClass("is-submenu-item " + s)
            })
        }, Burn: function (e, t) {
            var n = "is-" + t + "-submenu", i = n + "-item", o = "is-" + t + "-submenu-parent";
            e.find(">li, .menu, .menu > li").removeClass(n + " " + i + " " + o + " is-submenu-item submenu is-active").removeAttr("data-submenu").css("display", "")
        }
    };
    Foundation.Nest = t
}(jQuery), !function (e) {
    function t(e, t, n) {
        var i, o, s = this, r = t.duration, a = Object.keys(e.data())[0] || "timer", l = -1;
        this.isPaused = !1, this.restart = function () {
            l = -1, clearTimeout(o), this.start()
        }, this.start = function () {
            this.isPaused = !1, clearTimeout(o), l = l <= 0 ? r : l, e.data("paused", !1), i = Date.now(), o = setTimeout(function () {
                t.infinite && s.restart(), n && "function" == typeof n && n()
            }, l), e.trigger("timerstart.zf." + a)
        }, this.pause = function () {
            this.isPaused = !0, clearTimeout(o), e.data("paused", !0);
            var t = Date.now();
            l -= t - i, e.trigger("timerpaused.zf." + a)
        }
    }

    function n(t, n) {
        function i() {
            o--, 0 === o && n()
        }

        var o = t.length;
        0 === o && n(), t.each(function () {
            if (this.complete || 4 === this.readyState || "complete" === this.readyState) i(); else {
                var t = e(this).attr("src");
                e(this).attr("src", t + (t.indexOf("?") >= 0 ? "&" : "?") + (new Date).getTime()), e(this).one("load", function () {
                    i()
                })
            }
        })
    }

    Foundation.Timer = t, Foundation.onImagesLoaded = n
}(jQuery), function (e) {
    function t() {
        this.removeEventListener("touchmove", n), this.removeEventListener("touchend", t), c = !1
    }

    function n(n) {
        if (e.spotSwipe.preventDefault && n.preventDefault(), c) {
            var i, o = n.touches[0].pageX, r = (n.touches[0].pageY, s - o);
            l = (new Date).getTime() - a, Math.abs(r) >= e.spotSwipe.moveThreshold && l <= e.spotSwipe.timeThreshold && (i = r > 0 ? "left" : "right"), i && (n.preventDefault(), t.call(this), e(this).trigger("swipe", i).trigger("swipe" + i))
        }
    }

    function i(e) {
        1 == e.touches.length && (s = e.touches[0].pageX, r = e.touches[0].pageY, c = !0, a = (new Date).getTime(), this.addEventListener("touchmove", n, !1), this.addEventListener("touchend", t, !1))
    }

    function o() {
        this.addEventListener && this.addEventListener("touchstart", i, !1)
    }

    e.spotSwipe = {
        version: "1.0.0",
        enabled: "ontouchstart" in document.documentElement,
        preventDefault: !1,
        moveThreshold: 75,
        timeThreshold: 200
    };
    var s, r, a, l, c = !1;
    e.event.special.swipe = {setup: o}, e.each(["left", "up", "down", "right"], function () {
        e.event.special["swipe" + this] = {
            setup: function () {
                e(this).on("swipe", e.noop)
            }
        }
    })
}(jQuery), !function (e) {
    e.fn.addTouch = function () {
        this.each(function (n, i) {
            e(i).bind("touchstart touchmove touchend touchcancel", function () {
                t(event)
            })
        });
        var t = function (e) {
            var t, n = e.changedTouches, i = n[0],
                o = {touchstart: "mousedown", touchmove: "mousemove", touchend: "mouseup"}, s = o[e.type];
            "MouseEvent" in window && "function" == typeof window.MouseEvent ? t = new window.MouseEvent(s, {
                bubbles: !0,
                cancelable: !0,
                screenX: i.screenX,
                screenY: i.screenY,
                clientX: i.clientX,
                clientY: i.clientY
            }) : (t = document.createEvent("MouseEvent"), t.initMouseEvent(s, !0, !0, window, 1, i.screenX, i.screenY, i.clientX, i.clientY, !1, !1, !1, !1, 0, null)), i.target.dispatchEvent(t)
        }
    }
}(jQuery);
var _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (e) {
    return typeof e
} : function (e) {
    return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
};
!function (e) {
    function t() {
        s(), i(), o(), n()
    }

    function n(t) {
        var n = e("[data-yeti-box]"), i = ["dropdown", "tooltip", "reveal"];
        if (t && ("string" == typeof t ? i.push(t) : "object" === ("undefined" == typeof t ? "undefined" : _typeof(t)) && "string" == typeof t[0] ? i.concat(t) : console.error("Plugin names must be strings")), n.length) {
            var o = i.map(function (e) {
                return "closeme.zf." + e
            }).join(" ");
            e(window).off(o).on(o, function (t, n) {
                var i = t.namespace.split(".")[0], o = e("[data-" + i + "]").not('[data-yeti-box="' + n + '"]');
                o.each(function () {
                    var t = e(this);
                    t.triggerHandler("close.zf.trigger", [t])
                })
            })
        }
    }

    function i(t) {
        var n = void 0, i = e("[data-resize]");
        i.length && e(window).off("resize.zf.trigger").on("resize.zf.trigger", function (o) {
            n && clearTimeout(n), n = setTimeout(function () {
                r || i.each(function () {
                    e(this).triggerHandler("resizeme.zf.trigger")
                }), i.attr("data-events", "resize")
            }, t || 10)
        })
    }

    function o(t) {
        var n = void 0, i = e("[data-scroll]");
        i.length && e(window).off("scroll.zf.trigger").on("scroll.zf.trigger", function (o) {
            n && clearTimeout(n), n = setTimeout(function () {
                r || i.each(function () {
                    e(this).triggerHandler("scrollme.zf.trigger")
                }), i.attr("data-events", "scroll")
            }, t || 10)
        })
    }

    function s() {
        if (!r) return !1;
        var t = document.querySelectorAll("[data-resize], [data-scroll], [data-mutate]"), n = function (t) {
            var n = e(t[0].target);
            switch (t[0].type) {
                case"attributes":
                    "scroll" === n.attr("data-events") && "data-events" === t[0].attributeName && n.triggerHandler("scrollme.zf.trigger", [n, window.pageYOffset]), "resize" === n.attr("data-events") && "data-events" === t[0].attributeName && n.triggerHandler("resizeme.zf.trigger", [n]), "style" === t[0].attributeName && (n.closest("[data-mutate]").attr("data-events", "mutate"), n.closest("[data-mutate]").triggerHandler("mutateme.zf.trigger", [n.closest("[data-mutate]")]));
                    break;
                case"childList":
                    n.closest("[data-mutate]").attr("data-events", "mutate"), n.closest("[data-mutate]").triggerHandler("mutateme.zf.trigger", [n.closest("[data-mutate]")]);
                    break;
                default:
                    return !1
            }
        };
        if (t.length) for (var i = 0; i <= t.length - 1; i++) {
            var o = new r(n);
            o.observe(t[i], {
                attributes: !0,
                childList: !0,
                characterData: !1,
                subtree: !0,
                attributeFilter: ["data-events", "style"]
            })
        }
    }

    var r = function () {
        for (var e = ["WebKit", "Moz", "O", "Ms", ""], t = 0; t < e.length; t++) if (e[t] + "MutationObserver" in window) return window[e[t] + "MutationObserver"];
        return !1
    }(), a = function (t, n) {
        t.data(n).split(" ").forEach(function (i) {
            e("#" + i)["close" === n ? "trigger" : "triggerHandler"](n + ".zf.trigger", [t])
        })
    };
    e(document).on("click.zf.trigger", "[data-open]", function () {
        a(e(this), "open")
    }), e(document).on("click.zf.trigger", "[data-close]", function () {
        var t = e(this).data("close");
        t ? a(e(this), "close") : e(this).trigger("close.zf.trigger")
    }), e(document).on("click.zf.trigger", "[data-toggle]", function () {
        var t = e(this).data("toggle");
        t ? a(e(this), "toggle") : e(this).trigger("toggle.zf.trigger")
    }), e(document).on("close.zf.trigger", "[data-closable]", function (t) {
        t.stopPropagation();
        var n = e(this).data("closable");
        "" !== n ? Foundation.Motion.animateOut(e(this), n, function () {
            e(this).trigger("closed.zf")
        }) : e(this).fadeOut().trigger("closed.zf")
    }), e(document).on("focus.zf.trigger blur.zf.trigger", "[data-toggle-focus]", function () {
        var t = e(this).data("toggle-focus");
        e("#" + t).triggerHandler("toggle.zf.trigger", [e(this)])
    }), e(window).on("load", function () {
        t()
    }), Foundation.IHearYou = t
}(jQuery);
var _createClass = function () {
    function e(e, t) {
        for (var n = 0; n < t.length; n++) {
            var i = t[n];
            i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(e, i.key, i)
        }
    }

    return function (t, n, i) {
        return n && e(t.prototype, n), i && e(t, i), t
    }
}();
!function (e) {
    var t = function () {
        function t(n, i) {
            _classCallCheck(this, t), this.$element = n, this.options = e.extend({}, t.defaults, this.$element.data(), i), this._init(), Foundation.registerPlugin(this, "Accordion"), Foundation.Keyboard.register("Accordion", {
                ENTER: "toggle",
                SPACE: "toggle",
                ARROW_DOWN: "next",
                ARROW_UP: "previous"
            })
        }

        return _createClass(t, [{
            key: "_init", value: function () {
                var t = this;
                this.$element.attr("role", "tablist"), this.$tabs = this.$element.children("[data-accordion-item]"), this.$tabs.each(function (t, n) {
                    var i = e(n), o = i.children("[data-tab-content]"),
                        s = o[0].id || Foundation.GetYoDigits(6, "accordion"), r = n.id || s + "-label";
                    i.find("a:first").attr({
                        "aria-controls": s,
                        role: "tab",
                        id: r,
                        "aria-expanded": !1,
                        "aria-selected": !1
                    }), o.attr({role: "tabpanel", "aria-labelledby": r, "aria-hidden": !0, id: s})
                });
                var n = this.$element.find(".is-active").children("[data-tab-content]");
                this.firstTimeInit = !0, n.length && (this.down(n, this.firstTimeInit), this.firstTimeInit = !1), this._checkDeepLink = function () {
                    var n = window.location.hash;
                    if (n.length) {
                        var i = t.$element.find('[href$="' + n + '"]'), o = e(n);
                        if (i.length && o) {
                            if (i.parent("[data-accordion-item]").hasClass("is-active") || (t.down(o, t.firstTimeInit), t.firstTimeInit = !1), t.options.deepLinkSmudge) {
                                var s = t;
                                e(window).load(function () {
                                    var t = s.$element.offset();
                                    e("html, body").animate({scrollTop: t.top}, s.options.deepLinkSmudgeDelay)
                                })
                            }
                            t.$element.trigger("deeplink.zf.accordion", [i, o])
                        }
                    }
                }, this.options.deepLink && this._checkDeepLink(), this._events()
            }
        }, {
            key: "_events", value: function () {
                var t = this;
                this.$tabs.each(function () {
                    var n = e(this), i = n.children("[data-tab-content]");
                    i.length && n.children("a").off("click.zf.accordion keydown.zf.accordion").on("click.zf.accordion", function (e) {
                        e.preventDefault(), t.toggle(i)
                    }).on("keydown.zf.accordion", function (e) {
                        Foundation.Keyboard.handleKey(e, "Accordion", {
                            toggle: function () {
                                t.toggle(i)
                            }, next: function () {
                                var e = n.next().find("a").focus();
                                t.options.multiExpand || e.trigger("click.zf.accordion")
                            }, previous: function () {
                                var e = n.prev().find("a").focus();
                                t.options.multiExpand || e.trigger("click.zf.accordion")
                            }, handled: function () {
                                e.preventDefault(), e.stopPropagation()
                            }
                        })
                    })
                }), this.options.deepLink && e(window).on("popstate", this._checkDeepLink)
            }
        }, {
            key: "toggle", value: function (e) {
                if (e.parent().hasClass("is-active") ? this.up(e) : this.down(e), this.options.deepLink) {
                    var t = e.prev("a").attr("href");
                    this.options.updateHistory ? history.pushState({}, "", t) : history.replaceState({}, "", t)
                }
            }
        }, {
            key: "down", value: function (t, n) {
                var i = this;
                if (t.attr("aria-hidden", !1).parent("[data-tab-content]").addBack().parent().addClass("is-active"), !this.options.multiExpand && !n) {
                    var o = this.$element.children(".is-active").children("[data-tab-content]");
                    o.length && this.up(o.not(t))
                }
                t.slideDown(this.options.slideSpeed, function () {
                    i.$element.trigger("down.zf.accordion", [t])
                }), e("#" + t.attr("aria-labelledby")).attr({"aria-expanded": !0, "aria-selected": !0})
            }
        }, {
            key: "up", value: function (t) {
                var n = t.parent().siblings(), i = this;
                (this.options.allowAllClosed || n.hasClass("is-active")) && t.parent().hasClass("is-active") && (t.slideUp(i.options.slideSpeed, function () {
                    i.$element.trigger("up.zf.accordion", [t])
                }), t.attr("aria-hidden", !0).parent().removeClass("is-active"), e("#" + t.attr("aria-labelledby")).attr({
                    "aria-expanded": !1,
                    "aria-selected": !1
                }))
            }
        }, {
            key: "destroy", value: function () {
                this.$element.find("[data-tab-content]").stop(!0).slideUp(0).css("display", ""), this.$element.find("a").off(".zf.accordion"), this.options.deepLink && e(window).off("popstate", this._checkDeepLink), Foundation.unregisterPlugin(this)
            }
        }]), t
    }();
    t.defaults = {
        slideSpeed: 250,
        multiExpand: !1,
        allowAllClosed: !1,
        deepLink: !1,
        deepLinkSmudge: !1,
        deepLinkSmudgeDelay: 300,
        updateHistory: !1
    }, Foundation.plugin(t, "Accordion")
}(jQuery);
var _createClass = function () {
    function e(e, t) {
        for (var n = 0; n < t.length; n++) {
            var i = t[n];
            i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(e, i.key, i)
        }
    }

    return function (t, n, i) {
        return n && e(t.prototype, n), i && e(t, i), t
    }
}();
!function (e) {
    var t = function () {
        function t(n, i) {
            _classCallCheck(this, t), this.$element = n, this.options = e.extend({}, t.defaults, this.$element.data(), i), Foundation.Nest.Feather(this.$element, "accordion"), this._init(), Foundation.registerPlugin(this, "AccordionMenu"), Foundation.Keyboard.register("AccordionMenu", {
                ENTER: "toggle",
                SPACE: "toggle",
                ARROW_RIGHT: "open",
                ARROW_UP: "up",
                ARROW_DOWN: "down",
                ARROW_LEFT: "close",
                ESCAPE: "closeAll"
            })
        }

        return _createClass(t, [{
            key: "_init", value: function () {
                this.$element.find("[data-submenu]").not(".is-active").slideUp(0), this.$element.attr({
                    role: "menu",
                    "aria-multiselectable": this.options.multiOpen
                }), this.$menuLinks = this.$element.find(".is-accordion-submenu-parent"), this.$menuLinks.each(function () {
                    var t = this.id || Foundation.GetYoDigits(6, "acc-menu-link"), n = e(this),
                        i = n.children("[data-submenu]"), o = i[0].id || Foundation.GetYoDigits(6, "acc-menu"),
                        s = i.hasClass("is-active");
                    n.attr({
                        "aria-controls": o,
                        "aria-expanded": s,
                        role: "menuitem",
                        id: t
                    }), i.attr({"aria-labelledby": t, "aria-hidden": !s, role: "menu", id: o})
                });
                var t = this.$element.find(".is-active");
                if (t.length) {
                    var n = this;
                    t.each(function () {
                        n.down(e(this))
                    })
                }
                this._events()
            }
        }, {
            key: "_events", value: function () {
                var t = this;
                this.$element.find("li").each(function () {
                    var n = e(this).children("[data-submenu]");
                    n.length && e(this).children("a").off("click.zf.accordionMenu").on("click.zf.accordionMenu", function (e) {
                        e.preventDefault(), t.toggle(n)
                    })
                }).on("keydown.zf.accordionmenu", function (n) {
                    var i, o, s = e(this), r = s.parent("ul").children("li"), a = s.children("[data-submenu]");
                    r.each(function (t) {
                        if (e(this).is(s)) return i = r.eq(Math.max(0, t - 1)).find("a").first(), o = r.eq(Math.min(t + 1, r.length - 1)).find("a").first(), e(this).children("[data-submenu]:visible").length && (o = s.find("li:first-child").find("a").first()), e(this).is(":first-child") ? i = s.parents("li").first().find("a").first() : i.parents("li").first().children("[data-submenu]:visible").length && (i = i.parents("li").find("li:last-child").find("a").first()), void(e(this).is(":last-child") && (o = s.parents("li").first().next("li").find("a").first()))
                    }), Foundation.Keyboard.handleKey(n, "AccordionMenu", {
                        open: function () {
                            a.is(":hidden") && (t.down(a), a.find("li").first().find("a").first().focus())
                        }, close: function () {
                            a.length && !a.is(":hidden") ? t.up(a) : s.parent("[data-submenu]").length && (t.up(s.parent("[data-submenu]")), s.parents("li").first().find("a").first().focus())
                        }, up: function () {
                            return i.focus(), !0
                        }, down: function () {
                            return o.focus(), !0
                        }, toggle: function () {
                            s.children("[data-submenu]").length && t.toggle(s.children("[data-submenu]"))
                        }, closeAll: function () {
                            t.hideAll()
                        }, handled: function (e) {
                            e && n.preventDefault(), n.stopImmediatePropagation()
                        }
                    })
                })
            }
        }, {
            key: "hideAll", value: function () {
                this.up(this.$element.find("[data-submenu]"))
            }
        }, {
            key: "showAll", value: function () {
                this.down(this.$element.find("[data-submenu]"))
            }
        }, {
            key: "toggle", value: function (e) {
                e.is(":animated") || (e.is(":hidden") ? this.down(e) : this.up(e))
            }
        }, {
            key: "down", value: function (e) {
                var t = this;
                this.options.multiOpen || this.up(this.$element.find(".is-active").not(e.parentsUntil(this.$element).add(e))), e.addClass("is-active").attr({"aria-hidden": !1}).parent(".is-accordion-submenu-parent").attr({"aria-expanded": !0}), e.slideDown(t.options.slideSpeed, function () {
                    t.$element.trigger("down.zf.accordionMenu", [e])
                })
            }
        }, {
            key: "up", value: function (e) {
                var t = this;
                e.slideUp(t.options.slideSpeed, function () {
                    t.$element.trigger("up.zf.accordionMenu", [e])
                });
                var n = e.find("[data-submenu]").slideUp(0).addBack().attr("aria-hidden", !0);
                n.parent(".is-accordion-submenu-parent").attr("aria-expanded", !1)
            }
        }, {
            key: "destroy", value: function () {
                this.$element.find("[data-submenu]").slideDown(0).css("display", ""), this.$element.find("a").off("click.zf.accordionMenu"), Foundation.Nest.Burn(this.$element, "accordion"), Foundation.unregisterPlugin(this)
            }
        }]), t
    }();
    t.defaults = {slideSpeed: 250, multiOpen: !0}, Foundation.plugin(t, "AccordionMenu")
}(jQuery);
var _createClass = function () {
    function e(e, t) {
        for (var n = 0; n < t.length; n++) {
            var i = t[n];
            i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(e, i.key, i)
        }
    }

    return function (t, n, i) {
        return n && e(t.prototype, n), i && e(t, i), t
    }
}();
!function (e) {
    function t(e, t) {
        return e / t
    }

    function n(e, t, n, i) {
        return Math.abs(e.position()[t] + e[i]() / 2 - n)
    }

    function i(e, t) {
        return Math.log(t) / Math.log(e)
    }

    var o = function () {
        function o(t, n) {
            _classCallCheck(this, o), this.$element = t, this.options = e.extend({}, o.defaults, this.$element.data(), n), this._init(), Foundation.registerPlugin(this, "Slider"), Foundation.Keyboard.register("Slider", {
                ltr: {
                    ARROW_RIGHT: "increase",
                    ARROW_UP: "increase",
                    ARROW_DOWN: "decrease",
                    ARROW_LEFT: "decrease",
                    SHIFT_ARROW_RIGHT: "increase_fast",
                    SHIFT_ARROW_UP: "increase_fast",
                    SHIFT_ARROW_DOWN: "decrease_fast",
                    SHIFT_ARROW_LEFT: "decrease_fast"
                },
                rtl: {
                    ARROW_LEFT: "increase",
                    ARROW_RIGHT: "decrease",
                    SHIFT_ARROW_LEFT: "increase_fast",
                    SHIFT_ARROW_RIGHT: "decrease_fast"
                }
            })
        }

        return _createClass(o, [{
            key: "_init", value: function () {
                this.inputs = this.$element.find("input"), this.handles = this.$element.find("[data-slider-handle]"), this.$handle = this.handles.eq(0), this.$input = this.inputs.length ? this.inputs.eq(0) : e("#" + this.$handle.attr("aria-controls")), this.$fill = this.$element.find("[data-slider-fill]").css(this.options.vertical ? "height" : "width", 0);
                var t = !1;
                (this.options.disabled || this.$element.hasClass(this.options.disabledClass)) && (this.options.disabled = !0, this.$element.addClass(this.options.disabledClass)), this.inputs.length || (this.inputs = e().add(this.$input), this.options.binding = !0), this._setInitAttr(0), this.handles[1] && (this.options.doubleSided = !0, this.$handle2 = this.handles.eq(1), this.$input2 = this.inputs.length > 1 ? this.inputs.eq(1) : e("#" + this.$handle2.attr("aria-controls")), this.inputs[1] || (this.inputs = this.inputs.add(this.$input2)), t = !0, this._setInitAttr(1)), this.setHandles(), this._events()
            }
        }, {
            key: "setHandles", value: function () {
                var e = this;
                this.handles[1] ? this._setHandlePos(this.$handle, this.inputs.eq(0).val(), !0, function () {
                    e._setHandlePos(e.$handle2, e.inputs.eq(1).val(), !0)
                }) : this._setHandlePos(this.$handle, this.inputs.eq(0).val(), !0)
            }
        }, {
            key: "_reflow", value: function () {
                this.setHandles()
            }
        }, {
            key: "_pctOfBar", value: function (e) {
                var n = t(e - this.options.start, this.options.end - this.options.start);
                switch (this.options.positionValueFunction) {
                    case"pow":
                        n = this._logTransform(n);
                        break;
                    case"log":
                        n = this._powTransform(n)
                }
                return n.toFixed(2)
            }
        }, {
            key: "_value", value: function (e) {
                switch (this.options.positionValueFunction) {
                    case"pow":
                        e = this._powTransform(e);
                        break;
                    case"log":
                        e = this._logTransform(e)
                }
                var t = (this.options.end - this.options.start) * e + this.options.start;
                return t
            }
        }, {
            key: "_logTransform", value: function (e) {
                return i(this.options.nonLinearBase, e * (this.options.nonLinearBase - 1) + 1)
            }
        }, {
            key: "_powTransform", value: function (e) {
                return (Math.pow(this.options.nonLinearBase, e) - 1) / (this.options.nonLinearBase - 1)
            }
        }, {
            key: "_setHandlePos", value: function (e, n, i, o) {
                if (!this.$element.hasClass(this.options.disabledClass)) {
                    n = parseFloat(n), n < this.options.start ? n = this.options.start : n > this.options.end && (n = this.options.end);
                    var s = this.options.doubleSided;
                    if (s) if (0 === this.handles.index(e)) {
                        var r = parseFloat(this.$handle2.attr("aria-valuenow"));
                        n = n >= r ? r - this.options.step : n
                    } else {
                        var a = parseFloat(this.$handle.attr("aria-valuenow"));
                        n = n <= a ? a + this.options.step : n
                    }
                    this.options.vertical && !i && (n = this.options.end - n);
                    var l = this, c = this.options.vertical, d = c ? "height" : "width", u = c ? "top" : "left",
                        p = e[0].getBoundingClientRect()[d], f = this.$element[0].getBoundingClientRect()[d],
                        h = this._pctOfBar(n), g = (f - p) * h, v = (100 * t(g, f)).toFixed(this.options.decimal);
                    n = parseFloat(n.toFixed(this.options.decimal));
                    var m = {};
                    if (this._setValues(e, n), s) {
                        var y, w = 0 === this.handles.index(e), b = ~~(100 * t(p, f));
                        if (w) m[u] = v + "%", y = parseFloat(this.$handle2[0].style[u]) - v + b, o && "function" == typeof o && o(); else {
                            var k = parseFloat(this.$handle[0].style[u]);
                            y = v - (isNaN(k) ? (this.options.initialStart - this.options.start) / ((this.options.end - this.options.start) / 100) : k) + b
                        }
                        m["min-" + d] = y + "%"
                    }
                    this.$element.one("finished.zf.animate", function () {
                        l.$element.trigger("moved.zf.slider", [e])
                    });
                    var T = this.$element.data("dragging") ? 1e3 / 60 : this.options.moveTime;
                    Foundation.Move(T, e, function () {
                        isNaN(v) ? e.css(u, 100 * h + "%") : e.css(u, v + "%"), l.options.doubleSided ? l.$fill.css(m) : l.$fill.css(d, 100 * h + "%")
                    }), clearTimeout(l.timeout), l.timeout = setTimeout(function () {
                        l.$element.trigger("changed.zf.slider", [e])
                    }, l.options.changedDelay)
                }
            }
        }, {
            key: "_setInitAttr", value: function (e) {
                var t = 0 === e ? this.options.initialStart : this.options.initialEnd,
                    n = this.inputs.eq(e).attr("id") || Foundation.GetYoDigits(6, "slider");
                this.inputs.eq(e).attr({
                    id: n,
                    max: this.options.end,
                    min: this.options.start,
                    step: this.options.step
                }), this.inputs.eq(e).val(t), this.handles.eq(e).attr({
                    role: "slider",
                    "aria-controls": n,
                    "aria-valuemax": this.options.end,
                    "aria-valuemin": this.options.start,
                    "aria-valuenow": t,
                    "aria-orientation": this.options.vertical ? "vertical" : "horizontal",
                    tabindex: 0
                })
            }
        }, {
            key: "_setValues", value: function (e, t) {
                var n = this.options.doubleSided ? this.handles.index(e) : 0;
                this.inputs.eq(n).val(t), e.attr("aria-valuenow", t)
            }
        }, {
            key: "_handleEvent", value: function (i, o, s) {
                var r, a;
                if (s) r = this._adjustValue(null, s), a = !0; else {
                    i.preventDefault();
                    var l = this, c = this.options.vertical, d = c ? "height" : "width", u = c ? "top" : "left",
                        p = c ? i.pageY : i.pageX,
                        f = (this.$handle[0].getBoundingClientRect()[d] / 2, this.$element[0].getBoundingClientRect()[d]),
                        h = c ? e(window).scrollTop() : e(window).scrollLeft(), g = this.$element.offset()[u];
                    i.clientY === i.pageY && (p += h);
                    var v, m = p - g;
                    v = m < 0 ? 0 : m > f ? f : m;
                    var y = t(v, f);
                    if (r = this._value(y), Foundation.rtl() && !this.options.vertical && (r = this.options.end - r), r = l._adjustValue(null, r), a = !1, !o) {
                        var w = n(this.$handle, u, v, d), b = n(this.$handle2, u, v, d);
                        o = w <= b ? this.$handle : this.$handle2
                    }
                }
                this._setHandlePos(o, r, a)
            }
        }, {
            key: "_adjustValue", value: function (e, t) {
                var n, i, o, s, r = this.options.step, a = parseFloat(r / 2);
                return n = e ? parseFloat(e.attr("aria-valuenow")) : t, i = n % r, o = n - i, s = o + r, 0 === i ? n : n = n >= o + a ? s : o
            }
        }, {
            key: "_events", value: function () {
                this._eventsForHandle(this.$handle), this.handles[1] && this._eventsForHandle(this.$handle2)
            }
        }, {
            key: "_eventsForHandle", value: function (t) {
                var n, i = this;
                if (this.inputs.off("change.zf.slider").on("change.zf.slider", function (t) {
                        var n = i.inputs.index(e(this));
                        i._handleEvent(t, i.handles.eq(n), e(this).val())
                    }), this.options.clickSelect && this.$element.off("click.zf.slider").on("click.zf.slider", function (t) {
                        return !i.$element.data("dragging") && void(e(t.target).is("[data-slider-handle]") || (i.options.doubleSided ? i._handleEvent(t) : i._handleEvent(t, i.$handle)))
                    }), this.options.draggable) {
                    this.handles.addTouch();
                    var o = e("body");
                    t.off("mousedown.zf.slider").on("mousedown.zf.slider", function (s) {
                        t.addClass("is-dragging"), i.$fill.addClass("is-dragging"), i.$element.data("dragging", !0), n = e(s.currentTarget), o.on("mousemove.zf.slider", function (e) {
                            e.preventDefault(), i._handleEvent(e, n)
                        }).on("mouseup.zf.slider", function (e) {
                            i._handleEvent(e, n), t.removeClass("is-dragging"), i.$fill.removeClass("is-dragging"), i.$element.data("dragging", !1), o.off("mousemove.zf.slider mouseup.zf.slider")
                        })
                    }).on("selectstart.zf.slider touchmove.zf.slider", function (e) {
                        e.preventDefault()
                    })
                }
                t.off("keydown.zf.slider").on("keydown.zf.slider", function (t) {
                    var n, o = e(this), s = i.options.doubleSided ? i.handles.index(o) : 0,
                        r = parseFloat(i.inputs.eq(s).val());
                    Foundation.Keyboard.handleKey(t, "Slider", {
                        decrease: function () {
                            n = r - i.options.step
                        }, increase: function () {
                            n = r + i.options.step
                        }, decrease_fast: function () {
                            n = r - 10 * i.options.step
                        }, increase_fast: function () {
                            n = r + 10 * i.options.step
                        }, handled: function () {
                            t.preventDefault(), i._setHandlePos(o, n, !0)
                        }
                    })
                })
            }
        }, {
            key: "destroy", value: function () {
                this.handles.off(".zf.slider"), this.inputs.off(".zf.slider"), this.$element.off(".zf.slider"), clearTimeout(this.timeout), Foundation.unregisterPlugin(this)
            }
        }]), o
    }();
    o.defaults = {
        start: 0,
        end: 100,
        step: 1,
        initialStart: 0,
        initialEnd: 100,
        binding: !1,
        clickSelect: !0,
        vertical: !1,
        draggable: !0,
        disabled: !1,
        doubleSided: !1,
        decimal: 2,
        moveTime: 200,
        disabledClass: "disabled",
        invertVertical: !1,
        changedDelay: 500,
        nonLinearBase: 5,
        positionValueFunction: "linear"
    }, Foundation.plugin(o, "Slider")
}(jQuery);
var _createClass = function () {
    function e(e, t) {
        for (var n = 0; n < t.length; n++) {
            var i = t[n];
            i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(e, i.key, i)
        }
    }

    return function (t, n, i) {
        return n && e(t.prototype, n), i && e(t, i), t
    }
}();
!function (e) {
    function t(e) {
        return parseInt(window.getComputedStyle(document.body, null).fontSize, 10) * e
    }

    var n = function () {
        function n(t, i) {
            _classCallCheck(this, n), this.$element = t, this.options = e.extend({}, n.defaults, this.$element.data(), i), this._init(), Foundation.registerPlugin(this, "Sticky")
        }

        return _createClass(n, [{
            key: "_init", value: function () {
                var t = this.$element.parent("[data-sticky-container]"),
                    n = this.$element[0].id || Foundation.GetYoDigits(6, "sticky"), i = this;
                t.length || (this.wasWrapped = !0), this.$container = t.length ? t : e(this.options.container).wrapInner(this.$element), this.$container.addClass(this.options.containerClass), this.$element.addClass(this.options.stickyClass).attr({
                    "data-resize": n,
                    "data-mutate": n
                }), "" !== this.options.anchor && e("#" + i.options.anchor).attr({"data-mutate": n}), this.scrollCount = this.options.checkEvery, this.isStuck = !1, e(window).one("load.zf.sticky", function () {
                    i.containerHeight = "none" == i.$element.css("display") ? 0 : i.$element[0].getBoundingClientRect().height, i.$container.css("height", i.containerHeight), i.elemHeight = i.containerHeight, "" !== i.options.anchor ? i.$anchor = e("#" + i.options.anchor) : i._parsePoints(), i._setSizes(function () {
                        var e = window.pageYOffset;
                        i._calc(!1, e), i.isStuck || i._removeSticky(!(e >= i.topPoint))
                    }), i._events(n.split("-").reverse().join("-"))
                })
            }
        }, {
            key: "_parsePoints", value: function () {
                for (var t = "" == this.options.topAnchor ? 1 : this.options.topAnchor, n = "" == this.options.btmAnchor ? document.documentElement.scrollHeight : this.options.btmAnchor, i = [t, n], o = {}, s = 0, r = i.length; s < r && i[s]; s++) {
                    var a;
                    if ("number" == typeof i[s]) a = i[s]; else {
                        var l = i[s].split(":"), c = e("#" + l[0]);
                        a = c.offset().top, l[1] && "bottom" === l[1].toLowerCase() && (a += c[0].getBoundingClientRect().height)
                    }
                    o[s] = a
                }
                this.points = o
            }
        }, {
            key: "_events", value: function (t) {
                var n = this, i = this.scrollListener = "scroll.zf." + t;
                this.isOn || (this.canStick && (this.isOn = !0, e(window).off(i).on(i, function (e) {
                    0 === n.scrollCount ? (n.scrollCount = n.options.checkEvery,
                        n._setSizes(function () {
                            n._calc(!1, window.pageYOffset)
                        })) : (n.scrollCount--, n._calc(!1, window.pageYOffset))
                })), this.$element.off("resizeme.zf.trigger").on("resizeme.zf.trigger", function (e, i) {
                    n._eventsHandler(t)
                }), this.$element.on("mutateme.zf.trigger", function (e, i) {
                    n._eventsHandler(t)
                }), this.$anchor && this.$anchor.on("mutateme.zf.trigger", function (e, i) {
                    n._eventsHandler(t)
                }))
            }
        }, {
            key: "_eventsHandler", value: function (e) {
                var t = this, n = this.scrollListener = "scroll.zf." + e;
                t._setSizes(function () {
                    t._calc(!1), t.canStick ? t.isOn || t._events(e) : t.isOn && t._pauseListeners(n)
                })
            }
        }, {
            key: "_pauseListeners", value: function (t) {
                this.isOn = !1, e(window).off(t), this.$element.trigger("pause.zf.sticky")
            }
        }, {
            key: "_calc", value: function (e, t) {
                return e && this._setSizes(), this.canStick ? (t || (t = window.pageYOffset), void(t >= this.topPoint ? t <= this.bottomPoint ? this.isStuck || this._setSticky() : this.isStuck && this._removeSticky(!1) : this.isStuck && this._removeSticky(!0))) : (this.isStuck && this._removeSticky(!0), !1)
            }
        }, {
            key: "_setSticky", value: function () {
                var e = this, t = this.options.stickTo, n = "top" === t ? "marginTop" : "marginBottom",
                    i = "top" === t ? "bottom" : "top", o = {};
                o[n] = this.options[n] + "em", o[t] = 0, o[i] = "auto", this.isStuck = !0, this.$element.removeClass("is-anchored is-at-" + i).addClass("is-stuck is-at-" + t).css(o).trigger("sticky.zf.stuckto:" + t), this.$element.on("transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd", function () {
                    e._setSizes()
                })
            }
        }, {
            key: "_removeSticky", value: function (e) {
                var t = this.options.stickTo, n = "top" === t, i = {},
                    o = (this.points ? this.points[1] - this.points[0] : this.anchorHeight) - this.elemHeight,
                    s = n ? "marginTop" : "marginBottom", r = e ? "top" : "bottom";
                i[s] = 0, i.bottom = "auto", e ? i.top = 0 : i.top = o, this.isStuck = !1, this.$element.removeClass("is-stuck is-at-" + t).addClass("is-anchored is-at-" + r).css(i).trigger("sticky.zf.unstuckfrom:" + r)
            }
        }, {
            key: "_setSizes", value: function (e) {
                this.canStick = Foundation.MediaQuery.is(this.options.stickyOn), this.canStick || e && "function" == typeof e && e();
                var t = this.$container[0].getBoundingClientRect().width,
                    n = window.getComputedStyle(this.$container[0]), i = parseInt(n["padding-left"], 10),
                    o = parseInt(n["padding-right"], 10);
                this.$anchor && this.$anchor.length ? this.anchorHeight = this.$anchor[0].getBoundingClientRect().height : this._parsePoints(), this.$element.css({"max-width": t - i - o + "px"});
                var s = this.$element[0].getBoundingClientRect().height || this.containerHeight;
                if ("none" == this.$element.css("display") && (s = 0), this.containerHeight = s, this.$container.css({height: s}), this.elemHeight = s, !this.isStuck && this.$element.hasClass("is-at-bottom")) {
                    var r = (this.points ? this.points[1] - this.$container.offset().top : this.anchorHeight) - this.elemHeight;
                    this.$element.css("top", r)
                }
                this._setBreakPoints(s, function () {
                    e && "function" == typeof e && e()
                })
            }
        }, {
            key: "_setBreakPoints", value: function (e, n) {
                if (!this.canStick) {
                    if (!n || "function" != typeof n) return !1;
                    n()
                }
                var i = t(this.options.marginTop), o = t(this.options.marginBottom),
                    s = this.points ? this.points[0] : this.$anchor.offset().top,
                    r = this.points ? this.points[1] : s + this.anchorHeight, a = window.innerHeight;
                "top" === this.options.stickTo ? (s -= i, r -= e + i) : "bottom" === this.options.stickTo && (s -= a - (e + o), r -= a - o), this.topPoint = s, this.bottomPoint = r, n && "function" == typeof n && n()
            }
        }, {
            key: "destroy", value: function () {
                this._removeSticky(!0), this.$element.removeClass(this.options.stickyClass + " is-anchored is-at-top").css({
                    height: "",
                    top: "",
                    bottom: "",
                    "max-width": ""
                }).off("resizeme.zf.trigger").off("mutateme.zf.trigger"), this.$anchor && this.$anchor.length && this.$anchor.off("change.zf.sticky"), e(window).off(this.scrollListener), this.wasWrapped ? this.$element.unwrap() : this.$container.removeClass(this.options.containerClass).css({height: ""}), Foundation.unregisterPlugin(this)
            }
        }]), n
    }();
    n.defaults = {
        container: "<div data-sticky-container></div>",
        stickTo: "top",
        anchor: "",
        topAnchor: "",
        btmAnchor: "",
        marginTop: 1,
        marginBottom: 1,
        stickyOn: "medium",
        stickyClass: "sticky",
        containerClass: "sticky-container",
        checkEvery: -1
    }, Foundation.plugin(n, "Sticky")
}(jQuery);
var _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (e) {
    return typeof e
} : function (e) {
    return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
}, _createClass = function () {
    function e(e, t) {
        for (var n = 0; n < t.length; n++) {
            var i = t[n];
            i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(e, i.key, i)
        }
    }

    return function (t, n, i) {
        return n && e(t.prototype, n), i && e(t, i), t
    }
}();
!function (e) {
    var t = function () {
        function t(n, i) {
            _classCallCheck(this, t), this.$element = n, this.options = e.extend({}, t.defaults, this.$element.data(), i), this._init(), Foundation.registerPlugin(this, "Tabs"), Foundation.Keyboard.register("Tabs", {
                ENTER: "open",
                SPACE: "open",
                ARROW_RIGHT: "next",
                ARROW_UP: "previous",
                ARROW_DOWN: "next",
                ARROW_LEFT: "previous"
            })
        }

        return _createClass(t, [{
            key: "_init", value: function () {
                var t = this, n = this;
                if (this.$element.attr({role: "tablist"}), this.$tabTitles = this.$element.find("." + this.options.linkClass), this.$tabContent = e('[data-tabs-content="' + this.$element[0].id + '"]'), this.$tabTitles.each(function () {
                        var t = e(this), i = t.find("a"), o = t.hasClass("" + n.options.linkActiveClass),
                            s = i[0].hash.slice(1), r = i[0].id ? i[0].id : s + "-label", a = e("#" + s);
                        t.attr({role: "presentation"}), i.attr({
                            role: "tab",
                            "aria-controls": s,
                            "aria-selected": o,
                            id: r
                        }), a.attr({
                            role: "tabpanel",
                            "aria-hidden": !o,
                            "aria-labelledby": r
                        }), o && n.options.autoFocus && e(window).load(function () {
                            e("html, body").animate({scrollTop: t.offset().top}, n.options.deepLinkSmudgeDelay, function () {
                                i.focus()
                            })
                        })
                    }), this.options.matchHeight) {
                    var i = this.$tabContent.find("img");
                    i.length ? Foundation.onImagesLoaded(i, this._setHeight.bind(this)) : this._setHeight()
                }
                this._checkDeepLink = function () {
                    var n = window.location.hash;
                    if (n.length) {
                        var i = t.$element.find('[href$="' + n + '"]');
                        if (i.length) {
                            if (t.selectTab(e(n), !0), t.options.deepLinkSmudge) {
                                var o = t.$element.offset();
                                e("html, body").animate({scrollTop: o.top}, t.options.deepLinkSmudgeDelay)
                            }
                            t.$element.trigger("deeplink.zf.tabs", [i, e(n)])
                        }
                    }
                }, this.options.deepLink && this._checkDeepLink(), this._events()
            }
        }, {
            key: "_events", value: function () {
                this._addKeyHandler(), this._addClickHandler(), this._setHeightMqHandler = null, this.options.matchHeight && (this._setHeightMqHandler = this._setHeight.bind(this), e(window).on("changed.zf.mediaquery", this._setHeightMqHandler)), this.options.deepLink && e(window).on("popstate", this._checkDeepLink)
            }
        }, {
            key: "_addClickHandler", value: function () {
                var t = this;
                this.$element.off("click.zf.tabs").on("click.zf.tabs", "." + this.options.linkClass, function (n) {
                    n.preventDefault(), n.stopPropagation(), t._handleTabChange(e(this))
                })
            }
        }, {
            key: "_addKeyHandler", value: function () {
                var t = this;
                this.$tabTitles.off("keydown.zf.tabs").on("keydown.zf.tabs", function (n) {
                    if (9 !== n.which) {
                        var i, o, s = e(this), r = s.parent("ul").children("li");
                        r.each(function (n) {
                            if (e(this).is(s)) return void(t.options.wrapOnKeys ? (i = 0 === n ? r.last() : r.eq(n - 1), o = n === r.length - 1 ? r.first() : r.eq(n + 1)) : (i = r.eq(Math.max(0, n - 1)), o = r.eq(Math.min(n + 1, r.length - 1))))
                        }), Foundation.Keyboard.handleKey(n, "Tabs", {
                            open: function () {
                                s.find('[role="tab"]').focus(), t._handleTabChange(s)
                            }, previous: function () {
                                i.find('[role="tab"]').focus(), t._handleTabChange(i)
                            }, next: function () {
                                o.find('[role="tab"]').focus(), t._handleTabChange(o)
                            }, handled: function () {
                                n.stopPropagation(), n.preventDefault()
                            }
                        })
                    }
                })
            }
        }, {
            key: "_handleTabChange", value: function (e, t) {
                if (e.hasClass("" + this.options.linkActiveClass)) return void(this.options.activeCollapse && (this._collapseTab(e), this.$element.trigger("collapse.zf.tabs", [e])));
                var n = this.$element.find("." + this.options.linkClass + "." + this.options.linkActiveClass),
                    i = e.find('[role="tab"]'), o = i[0].hash, s = this.$tabContent.find(o);
                if (this._collapseTab(n), this._openTab(e), this.options.deepLink && !t) {
                    var r = e.find("a").attr("href");
                    this.options.updateHistory ? history.pushState({}, "", r) : history.replaceState({}, "", r)
                }
                this.$element.trigger("change.zf.tabs", [e, s]), s.find("[data-mutate]").trigger("mutateme.zf.trigger")
            }
        }, {
            key: "_openTab", value: function (e) {
                var t = e.find('[role="tab"]'), n = t[0].hash, i = this.$tabContent.find(n);
                e.addClass("" + this.options.linkActiveClass), t.attr({"aria-selected": "true"}), i.addClass("" + this.options.panelActiveClass).attr({"aria-hidden": "false"})
            }
        }, {
            key: "_collapseTab", value: function (t) {
                var n = t.removeClass("" + this.options.linkActiveClass).find('[role="tab"]').attr({"aria-selected": "false"});
                e("#" + n.attr("aria-controls")).removeClass("" + this.options.panelActiveClass).attr({"aria-hidden": "true"})
            }
        }, {
            key: "selectTab", value: function (e, t) {
                var n;
                n = "object" === ("undefined" == typeof e ? "undefined" : _typeof(e)) ? e[0].id : e, n.indexOf("#") < 0 && (n = "#" + n);
                var i = this.$tabTitles.find('[href$="' + n + '"]').parent("." + this.options.linkClass);
                this._handleTabChange(i, t)
            }
        }, {
            key: "_setHeight", value: function () {
                var t = 0, n = this;
                this.$tabContent.find("." + this.options.panelClass).css("height", "").each(function () {
                    var i = e(this), o = i.hasClass("" + n.options.panelActiveClass);
                    o || i.css({visibility: "hidden", display: "block"});
                    var s = this.getBoundingClientRect().height;
                    o || i.css({visibility: "", display: ""}), t = s > t ? s : t
                }).css("height", t + "px")
            }
        }, {
            key: "destroy", value: function () {
                this.$element.find("." + this.options.linkClass).off(".zf.tabs").hide().end().find("." + this.options.panelClass).hide(), this.options.matchHeight && null != this._setHeightMqHandler && e(window).off("changed.zf.mediaquery", this._setHeightMqHandler), this.options.deepLink && e(window).off("popstate", this._checkDeepLink), Foundation.unregisterPlugin(this)
            }
        }]), t
    }();
    t.defaults = {
        deepLink: !1,
        deepLinkSmudge: !1,
        deepLinkSmudgeDelay: 300,
        updateHistory: !1,
        autoFocus: !1,
        wrapOnKeys: !0,
        matchHeight: !1,
        activeCollapse: !1,
        linkClass: "tabs-title",
        linkActiveClass: "is-active",
        panelClass: "tabs-panel",
        panelActiveClass: "is-active"
    }, Foundation.plugin(t, "Tabs")
}(jQuery);
var _createClass = function () {
    function e(e, t) {
        for (var n = 0; n < t.length; n++) {
            var i = t[n];
            i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(e, i.key, i)
        }
    }

    return function (t, n, i) {
        return n && e(t.prototype, n), i && e(t, i), t
    }
}();
!function (e) {
    var t = function () {
        function t(n, i) {
            _classCallCheck(this, t), this.$element = n, this.options = e.extend({}, t.defaults, n.data(), i), this.className = "", this._init(), this._events(), Foundation.registerPlugin(this, "Toggler")
        }

        return _createClass(t, [{
            key: "_init", value: function () {
                var t;
                this.options.animate ? (t = this.options.animate.split(" "), this.animationIn = t[0], this.animationOut = t[1] || null) : (t = this.$element.data("toggler"), this.className = "." === t[0] ? t.slice(1) : t);
                var n = this.$element[0].id;
                e('[data-open="' + n + '"], [data-close="' + n + '"], [data-toggle="' + n + '"]').attr("aria-controls", n), this.$element.attr("aria-expanded", !this.$element.is(":hidden"))
            }
        }, {
            key: "_events", value: function () {
                this.$element.off("toggle.zf.trigger").on("toggle.zf.trigger", this.toggle.bind(this))
            }
        }, {
            key: "toggle", value: function () {
                this[this.options.animate ? "_toggleAnimate" : "_toggleClass"]()
            }
        }, {
            key: "_toggleClass", value: function () {
                this.$element.toggleClass(this.className);
                var e = this.$element.hasClass(this.className);
                e ? this.$element.trigger("on.zf.toggler") : this.$element.trigger("off.zf.toggler"), this._updateARIA(e), this.$element.find("[data-mutate]").trigger("mutateme.zf.trigger")
            }
        }, {
            key: "_toggleAnimate", value: function () {
                var e = this;
                this.$element.is(":hidden") ? Foundation.Motion.animateIn(this.$element, this.animationIn, function () {
                    e._updateARIA(!0), this.trigger("on.zf.toggler"), this.find("[data-mutate]").trigger("mutateme.zf.trigger")
                }) : Foundation.Motion.animateOut(this.$element, this.animationOut, function () {
                    e._updateARIA(!1), this.trigger("off.zf.toggler"), this.find("[data-mutate]").trigger("mutateme.zf.trigger")
                })
            }
        }, {
            key: "_updateARIA", value: function (e) {
                this.$element.attr("aria-expanded", !!e)
            }
        }, {
            key: "destroy", value: function () {
                this.$element.off(".zf.toggler"), Foundation.unregisterPlugin(this)
            }
        }]), t
    }();
    t.defaults = {animate: !1}, Foundation.plugin(t, "Toggler")
}(jQuery);
var _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (e) {
    return typeof e
} : function (e) {
    return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
};
!function (e) {
    "function" == typeof define && define.amd ? define(["jquery"], e) : "undefined" != typeof exports ? module.exports = e(require("jquery")) : e(jQuery)
}(function (e) {
    var t = window.Slick || {};
    (t = function () {
        var t = 0;
        return function (n, i) {
            var o, s = this;
            s.defaults = {
                accessibility: !0,
                adaptiveHeight: !1,
                appendArrows: e(n),
                appendDots: e(n),
                arrows: !0,
                asNavFor: null,
                prevArrow: '<button class="slick-prev" aria-label="Previous" type="button">Previous</button>',
                nextArrow: '<button class="slick-next" aria-label="Next" type="button">Next</button>',
                autoplay: !1,
                autoplaySpeed: 3e3,
                centerMode: !1,
                centerPadding: "50px",
                cssEase: "ease",
                customPaging: function (t, n) {
                    return e('<button type="button" />').text(n + 1)
                },
                dots: !1,
                dotsClass: "slick-dots",
                draggable: !0,
                easing: "linear",
                edgeFriction: .35,
                fade: !1,
                focusOnSelect: !1,
                focusOnChange: !1,
                infinite: !0,
                initialSlide: 0,
                lazyLoad: "ondemand",
                mobileFirst: !1,
                pauseOnHover: !0,
                pauseOnFocus: !0,
                pauseOnDotsHover: !1,
                respondTo: "window",
                responsive: null,
                rows: 1,
                rtl: !1,
                slide: "",
                slidesPerRow: 1,
                slidesToShow: 1,
                slidesToScroll: 1,
                speed: 500,
                swipe: !0,
                swipeToSlide: !1,
                touchMove: !0,
                touchThreshold: 5,
                useCSS: !0,
                useTransform: !0,
                variableWidth: !1,
                vertical: !1,
                verticalSwiping: !1,
                waitForAnimate: !0,
                zIndex: 1e3
            }, s.initials = {
                animating: !1,
                dragging: !1,
                autoPlayTimer: null,
                currentDirection: 0,
                currentLeft: null,
                currentSlide: 0,
                direction: 1,
                $dots: null,
                listWidth: null,
                listHeight: null,
                loadIndex: 0,
                $nextArrow: null,
                $prevArrow: null,
                scrolling: !1,
                slideCount: null,
                slideWidth: null,
                $slideTrack: null,
                $slides: null,
                sliding: !1,
                slideOffset: 0,
                swipeLeft: null,
                swiping: !1,
                $list: null,
                touchObject: {},
                transformsEnabled: !1,
                unslicked: !1
            }, e.extend(s, s.initials), s.activeBreakpoint = null, s.animType = null, s.animProp = null, s.breakpoints = [], s.breakpointSettings = [], s.cssTransitions = !1, s.focussed = !1, s.interrupted = !1, s.hidden = "hidden", s.paused = !0, s.positionProp = null, s.respondTo = null, s.rowCount = 1, s.shouldClick = !0, s.$slider = e(n), s.$slidesCache = null, s.transformType = null, s.transitionType = null, s.visibilityChange = "visibilitychange", s.windowWidth = 0, s.windowTimer = null, o = e(n).data("slick") || {}, s.options = e.extend({}, s.defaults, i, o), s.currentSlide = s.options.initialSlide, s.originalSettings = s.options, void 0 !== document.mozHidden ? (s.hidden = "mozHidden", s.visibilityChange = "mozvisibilitychange") : void 0 !== document.webkitHidden && (s.hidden = "webkitHidden", s.visibilityChange = "webkitvisibilitychange"), s.autoPlay = e.proxy(s.autoPlay, s), s.autoPlayClear = e.proxy(s.autoPlayClear, s), s.autoPlayIterator = e.proxy(s.autoPlayIterator, s), s.changeSlide = e.proxy(s.changeSlide, s), s.clickHandler = e.proxy(s.clickHandler, s), s.selectHandler = e.proxy(s.selectHandler, s), s.setPosition = e.proxy(s.setPosition, s), s.swipeHandler = e.proxy(s.swipeHandler, s), s.dragHandler = e.proxy(s.dragHandler, s), s.keyHandler = e.proxy(s.keyHandler, s), s.instanceUid = t++, s.htmlExpr = /^(?:\s*(<[\w\W]+>)[^>]*)$/, s.registerBreakpoints(), s.init(!0)
        }
    }()).prototype.activateADA = function () {
        this.$slideTrack.find(".slick-active").attr({"aria-hidden": "false"}).find("a, input, button, select").attr({tabindex: "0"})
    }, t.prototype.addSlide = t.prototype.slickAdd = function (t, n, i) {
        var o = this;
        if ("boolean" == typeof n) i = n, n = null; else if (n < 0 || n >= o.slideCount) return !1;
        o.unload(), "number" == typeof n ? 0 === n && 0 === o.$slides.length ? e(t).appendTo(o.$slideTrack) : i ? e(t).insertBefore(o.$slides.eq(n)) : e(t).insertAfter(o.$slides.eq(n)) : !0 === i ? e(t).prependTo(o.$slideTrack) : e(t).appendTo(o.$slideTrack), o.$slides = o.$slideTrack.children(this.options.slide), o.$slideTrack.children(this.options.slide).detach(), o.$slideTrack.append(o.$slides), o.$slides.each(function (t, n) {
            e(n).attr("data-slick-index", t)
        }), o.$slidesCache = o.$slides, o.reinit()
    }, t.prototype.animateHeight = function () {
        var e = this;
        if (1 === e.options.slidesToShow && !0 === e.options.adaptiveHeight && !1 === e.options.vertical) {
            var t = e.$slides.eq(e.currentSlide).outerHeight(!0);
            e.$list.animate({height: t}, e.options.speed)
        }
    }, t.prototype.animateSlide = function (t, n) {
        var i = {}, o = this;
        o.animateHeight(), !0 === o.options.rtl && !1 === o.options.vertical && (t = -t), !1 === o.transformsEnabled ? !1 === o.options.vertical ? o.$slideTrack.animate({left: t}, o.options.speed, o.options.easing, n) : o.$slideTrack.animate({top: t}, o.options.speed, o.options.easing, n) : !1 === o.cssTransitions ? (!0 === o.options.rtl && (o.currentLeft = -o.currentLeft), e({animStart: o.currentLeft}).animate({animStart: t}, {
            duration: o.options.speed,
            easing: o.options.easing,
            step: function (e) {
                e = Math.ceil(e), !1 === o.options.vertical ? (i[o.animType] = "translate(" + e + "px, 0px)", o.$slideTrack.css(i)) : (i[o.animType] = "translate(0px," + e + "px)", o.$slideTrack.css(i))
            },
            complete: function () {
                n && n.call()
            }
        })) : (o.applyTransition(), t = Math.ceil(t), !1 === o.options.vertical ? i[o.animType] = "translate3d(" + t + "px, 0px, 0px)" : i[o.animType] = "translate3d(0px," + t + "px, 0px)", o.$slideTrack.css(i), n && setTimeout(function () {
            o.disableTransition(), n.call()
        }, o.options.speed))
    }, t.prototype.getNavTarget = function () {
        var t = this, n = t.options.asNavFor;
        return n && null !== n && (n = e(n).not(t.$slider)), n
    }, t.prototype.asNavFor = function (t) {
        var n = this.getNavTarget();
        null !== n && "object" == ("undefined" == typeof n ? "undefined" : _typeof(n)) && n.each(function () {
            var n = e(this).slick("getSlick");
            n.unslicked || n.slideHandler(t, !0)
        })
    }, t.prototype.applyTransition = function (e) {
        var t = this, n = {};
        !1 === t.options.fade ? n[t.transitionType] = t.transformType + " " + t.options.speed + "ms " + t.options.cssEase : n[t.transitionType] = "opacity " + t.options.speed + "ms " + t.options.cssEase, !1 === t.options.fade ? t.$slideTrack.css(n) : t.$slides.eq(e).css(n)
    }, t.prototype.autoPlay = function () {
        var e = this;
        e.autoPlayClear(), e.slideCount > e.options.slidesToShow && (e.autoPlayTimer = setInterval(e.autoPlayIterator, e.options.autoplaySpeed))
    }, t.prototype.autoPlayClear = function () {
        var e = this;
        e.autoPlayTimer && clearInterval(e.autoPlayTimer)
    }, t.prototype.autoPlayIterator = function () {
        var e = this, t = e.currentSlide + e.options.slidesToScroll;
        e.paused || e.interrupted || e.focussed || (!1 === e.options.infinite && (1 === e.direction && e.currentSlide + 1 === e.slideCount - 1 ? e.direction = 0 : 0 === e.direction && (t = e.currentSlide - e.options.slidesToScroll, e.currentSlide - 1 == 0 && (e.direction = 1))), e.slideHandler(t))
    }, t.prototype.buildArrows = function () {
        var t = this;
        !0 === t.options.arrows && (t.$prevArrow = e(t.options.prevArrow).addClass("slick-arrow"), t.$nextArrow = e(t.options.nextArrow).addClass("slick-arrow"), t.slideCount > t.options.slidesToShow ? (t.$prevArrow.removeClass("slick-hidden").removeAttr("aria-hidden tabindex"), t.$nextArrow.removeClass("slick-hidden").removeAttr("aria-hidden tabindex"), t.htmlExpr.test(t.options.prevArrow) && t.$prevArrow.prependTo(t.options.appendArrows), t.htmlExpr.test(t.options.nextArrow) && t.$nextArrow.appendTo(t.options.appendArrows), !0 !== t.options.infinite && t.$prevArrow.addClass("slick-disabled").attr("aria-disabled", "true")) : t.$prevArrow.add(t.$nextArrow).addClass("slick-hidden").attr({
            "aria-disabled": "true",
            tabindex: "-1"
        }))
    }, t.prototype.buildDots = function () {
        var t, n, i = this;
        if (!0 === i.options.dots) {
            for (i.$slider.addClass("slick-dotted"), n = e("<ul />").addClass(i.options.dotsClass), t = 0; t <= i.getDotCount(); t += 1) n.append(e("<li />").append(i.options.customPaging.call(this, i, t)));
            i.$dots = n.appendTo(i.options.appendDots), i.$dots.find("li").first().addClass("slick-active")
        }
    }, t.prototype.buildOut = function () {
        var t = this;
        t.$slides = t.$slider.children(t.options.slide + ":not(.slick-cloned)").addClass("slick-slide"), t.slideCount = t.$slides.length, t.$slides.each(function (t, n) {
            e(n).attr("data-slick-index", t).data("originalStyling", e(n).attr("style") || "")
        }), t.$slider.addClass("slick-slider"), t.$slideTrack = 0 === t.slideCount ? e('<div class="slick-track"/>').appendTo(t.$slider) : t.$slides.wrapAll('<div class="slick-track"/>').parent(), t.$list = t.$slideTrack.wrap('<div class="slick-list"/>').parent(), t.$slideTrack.css("opacity", 0), !0 !== t.options.centerMode && !0 !== t.options.swipeToSlide || (t.options.slidesToScroll = 1), e("img[data-lazy]", t.$slider).not("[src]").addClass("slick-loading"), t.setupInfinite(), t.buildArrows(), t.buildDots(), t.updateDots(), t.setSlideClasses("number" == typeof t.currentSlide ? t.currentSlide : 0), !0 === t.options.draggable && t.$list.addClass("draggable")
    }, t.prototype.buildRows = function () {
        var e, t, n, i, o, s, r, a = this;
        if (i = document.createDocumentFragment(), s = a.$slider.children(), a.options.rows > 1) {
            for (r = a.options.slidesPerRow * a.options.rows, o = Math.ceil(s.length / r), e = 0; e < o; e++) {
                var l = document.createElement("div");
                for (t = 0; t < a.options.rows; t++) {
                    var c = document.createElement("div");
                    for (n = 0; n < a.options.slidesPerRow; n++) {
                        var d = e * r + (t * a.options.slidesPerRow + n);
                        s.get(d) && c.appendChild(s.get(d))
                    }
                    l.appendChild(c)
                }
                i.appendChild(l)
            }
            a.$slider.empty().append(i), a.$slider.children().children().children().css({
                width: 100 / a.options.slidesPerRow + "%",
                display: "inline-block"
            })
        }
    }, t.prototype.checkResponsive = function (t, n) {
        var i, o, s, r = this, a = !1, l = r.$slider.width(), c = window.innerWidth || e(window).width();
        if ("window" === r.respondTo ? s = c : "slider" === r.respondTo ? s = l : "min" === r.respondTo && (s = Math.min(c, l)), r.options.responsive && r.options.responsive.length && null !== r.options.responsive) {
            o = null;
            for (i in r.breakpoints) r.breakpoints.hasOwnProperty(i) && (!1 === r.originalSettings.mobileFirst ? s < r.breakpoints[i] && (o = r.breakpoints[i]) : s > r.breakpoints[i] && (o = r.breakpoints[i]));
            null !== o ? null !== r.activeBreakpoint ? (o !== r.activeBreakpoint || n) && (r.activeBreakpoint = o, "unslick" === r.breakpointSettings[o] ? r.unslick(o) : (r.options = e.extend({}, r.originalSettings, r.breakpointSettings[o]), !0 === t && (r.currentSlide = r.options.initialSlide), r.refresh(t)), a = o) : (r.activeBreakpoint = o, "unslick" === r.breakpointSettings[o] ? r.unslick(o) : (r.options = e.extend({}, r.originalSettings, r.breakpointSettings[o]), !0 === t && (r.currentSlide = r.options.initialSlide), r.refresh(t)), a = o) : null !== r.activeBreakpoint && (r.activeBreakpoint = null, r.options = r.originalSettings, !0 === t && (r.currentSlide = r.options.initialSlide), r.refresh(t), a = o), t || !1 === a || r.$slider.trigger("breakpoint", [r, a])
        }
    }, t.prototype.changeSlide = function (t, n) {
        var i, o, s, r = this, a = e(t.currentTarget);
        switch (a.is("a") && t.preventDefault(), a.is("li") || (a = a.closest("li")), s = r.slideCount % r.options.slidesToScroll != 0, i = s ? 0 : (r.slideCount - r.currentSlide) % r.options.slidesToScroll, t.data.message) {
            case"previous":
                o = 0 === i ? r.options.slidesToScroll : r.options.slidesToShow - i, r.slideCount > r.options.slidesToShow && r.slideHandler(r.currentSlide - o, !1, n);
                break;
            case"next":
                o = 0 === i ? r.options.slidesToScroll : i, r.slideCount > r.options.slidesToShow && r.slideHandler(r.currentSlide + o, !1, n);
                break;
            case"index":
                var l = 0 === t.data.index ? 0 : t.data.index || a.index() * r.options.slidesToScroll;
                r.slideHandler(r.checkNavigable(l), !1, n), a.children().trigger("focus");
                break;
            default:
                return
        }
    }, t.prototype.checkNavigable = function (e) {
        var t, n;
        if (t = this.getNavigableIndexes(), n = 0, e > t[t.length - 1]) e = t[t.length - 1]; else for (var i in t) {
            if (e < t[i]) {
                e = n;
                break
            }
            n = t[i]
        }
        return e
    }, t.prototype.cleanUpEvents = function () {
        var t = this;
        t.options.dots && null !== t.$dots && (e("li", t.$dots).off("click.slick", t.changeSlide).off("mouseenter.slick", e.proxy(t.interrupt, t, !0)).off("mouseleave.slick", e.proxy(t.interrupt, t, !1)), !0 === t.options.accessibility && t.$dots.off("keydown.slick", t.keyHandler)), t.$slider.off("focus.slick blur.slick"), !0 === t.options.arrows && t.slideCount > t.options.slidesToShow && (t.$prevArrow && t.$prevArrow.off("click.slick", t.changeSlide), t.$nextArrow && t.$nextArrow.off("click.slick", t.changeSlide), !0 === t.options.accessibility && (t.$prevArrow && t.$prevArrow.off("keydown.slick", t.keyHandler), t.$nextArrow && t.$nextArrow.off("keydown.slick", t.keyHandler))), t.$list.off("touchstart.slick mousedown.slick", t.swipeHandler), t.$list.off("touchmove.slick mousemove.slick", t.swipeHandler), t.$list.off("touchend.slick mouseup.slick", t.swipeHandler), t.$list.off("touchcancel.slick mouseleave.slick", t.swipeHandler), t.$list.off("click.slick", t.clickHandler), e(document).off(t.visibilityChange, t.visibility), t.cleanUpSlideEvents(), !0 === t.options.accessibility && t.$list.off("keydown.slick", t.keyHandler), !0 === t.options.focusOnSelect && e(t.$slideTrack).children().off("click.slick", t.selectHandler), e(window).off("orientationchange.slick.slick-" + t.instanceUid, t.orientationChange), e(window).off("resize.slick.slick-" + t.instanceUid, t.resize), e("[draggable!=true]", t.$slideTrack).off("dragstart", t.preventDefault), e(window).off("load.slick.slick-" + t.instanceUid, t.setPosition)
    }, t.prototype.cleanUpSlideEvents = function () {
        var t = this;
        t.$list.off("mouseenter.slick", e.proxy(t.interrupt, t, !0)), t.$list.off("mouseleave.slick", e.proxy(t.interrupt, t, !1))
    }, t.prototype.cleanUpRows = function () {
        var e, t = this;
        t.options.rows > 1 && ((e = t.$slides.children().children()).removeAttr("style"), t.$slider.empty().append(e))
    }, t.prototype.clickHandler = function (e) {
        !1 === this.shouldClick && (e.stopImmediatePropagation(), e.stopPropagation(), e.preventDefault())
    }, t.prototype.destroy = function (t) {
        var n = this;
        n.autoPlayClear(), n.touchObject = {}, n.cleanUpEvents(), e(".slick-cloned", n.$slider).detach(), n.$dots && n.$dots.remove(), n.$prevArrow && n.$prevArrow.length && (n.$prevArrow.removeClass("slick-disabled slick-arrow slick-hidden").removeAttr("aria-hidden aria-disabled tabindex").css("display", ""), n.htmlExpr.test(n.options.prevArrow) && n.$prevArrow.remove()), n.$nextArrow && n.$nextArrow.length && (n.$nextArrow.removeClass("slick-disabled slick-arrow slick-hidden").removeAttr("aria-hidden aria-disabled tabindex").css("display", ""), n.htmlExpr.test(n.options.nextArrow) && n.$nextArrow.remove()), n.$slides && (n.$slides.removeClass("slick-slide slick-active slick-center slick-visible slick-current").removeAttr("aria-hidden").removeAttr("data-slick-index").each(function () {
            e(this).attr("style", e(this).data("originalStyling"))
        }), n.$slideTrack.children(this.options.slide).detach(), n.$slideTrack.detach(), n.$list.detach(), n.$slider.append(n.$slides)), n.cleanUpRows(), n.$slider.removeClass("slick-slider"), n.$slider.removeClass("slick-initialized"), n.$slider.removeClass("slick-dotted"), n.unslicked = !0, t || n.$slider.trigger("destroy", [n])
    }, t.prototype.disableTransition = function (e) {
        var t = this, n = {};
        n[t.transitionType] = "", !1 === t.options.fade ? t.$slideTrack.css(n) : t.$slides.eq(e).css(n)
    }, t.prototype.fadeSlide = function (e, t) {
        var n = this;
        !1 === n.cssTransitions ? (n.$slides.eq(e).css({zIndex: n.options.zIndex}), n.$slides.eq(e).animate({opacity: 1}, n.options.speed, n.options.easing, t)) : (n.applyTransition(e), n.$slides.eq(e).css({
            opacity: 1,
            zIndex: n.options.zIndex
        }), t && setTimeout(function () {
            n.disableTransition(e), t.call()
        }, n.options.speed))
    }, t.prototype.fadeSlideOut = function (e) {
        var t = this;
        !1 === t.cssTransitions ? t.$slides.eq(e).animate({
            opacity: 0,
            zIndex: t.options.zIndex - 2
        }, t.options.speed, t.options.easing) : (t.applyTransition(e), t.$slides.eq(e).css({
            opacity: 0,
            zIndex: t.options.zIndex - 2
        }))
    }, t.prototype.filterSlides = t.prototype.slickFilter = function (e) {
        var t = this;
        null !== e && (t.$slidesCache = t.$slides, t.unload(), t.$slideTrack.children(this.options.slide).detach(), t.$slidesCache.filter(e).appendTo(t.$slideTrack), t.reinit())
    }, t.prototype.focusHandler = function () {
        var t = this;
        t.$slider.off("focus.slick blur.slick").on("focus.slick blur.slick", "*", function (n) {
            n.stopImmediatePropagation();
            var i = e(this);
            setTimeout(function () {
                t.options.pauseOnFocus && (t.focussed = i.is(":focus"), t.autoPlay())
            }, 0)
        })
    }, t.prototype.getCurrent = t.prototype.slickCurrentSlide = function () {
        return this.currentSlide
    }, t.prototype.getDotCount = function () {
        var e = this, t = 0, n = 0, i = 0;
        if (!0 === e.options.infinite) if (e.slideCount <= e.options.slidesToShow) ++i; else for (; t < e.slideCount;) ++i, t = n + e.options.slidesToScroll, n += e.options.slidesToScroll <= e.options.slidesToShow ? e.options.slidesToScroll : e.options.slidesToShow; else if (!0 === e.options.centerMode) i = e.slideCount; else if (e.options.asNavFor) for (; t < e.slideCount;) ++i, t = n + e.options.slidesToScroll, n += e.options.slidesToScroll <= e.options.slidesToShow ? e.options.slidesToScroll : e.options.slidesToShow; else i = 1 + Math.ceil((e.slideCount - e.options.slidesToShow) / e.options.slidesToScroll);
        return i - 1
    }, t.prototype.getLeft = function (e) {
        var t, n, i, o, s = this, r = 0;
        return s.slideOffset = 0, n = s.$slides.first().outerHeight(!0), !0 === s.options.infinite ? (s.slideCount > s.options.slidesToShow && (s.slideOffset = s.slideWidth * s.options.slidesToShow * -1, o = -1, !0 === s.options.vertical && !0 === s.options.centerMode && (2 === s.options.slidesToShow ? o = -1.5 : 1 === s.options.slidesToShow && (o = -2)), r = n * s.options.slidesToShow * o), s.slideCount % s.options.slidesToScroll != 0 && e + s.options.slidesToScroll > s.slideCount && s.slideCount > s.options.slidesToShow && (e > s.slideCount ? (s.slideOffset = (s.options.slidesToShow - (e - s.slideCount)) * s.slideWidth * -1, r = (s.options.slidesToShow - (e - s.slideCount)) * n * -1) : (s.slideOffset = s.slideCount % s.options.slidesToScroll * s.slideWidth * -1, r = s.slideCount % s.options.slidesToScroll * n * -1))) : e + s.options.slidesToShow > s.slideCount && (s.slideOffset = (e + s.options.slidesToShow - s.slideCount) * s.slideWidth, r = (e + s.options.slidesToShow - s.slideCount) * n), s.slideCount <= s.options.slidesToShow && (s.slideOffset = 0, r = 0), !0 === s.options.centerMode && s.slideCount <= s.options.slidesToShow ? s.slideOffset = s.slideWidth * Math.floor(s.options.slidesToShow) / 2 - s.slideWidth * s.slideCount / 2 : !0 === s.options.centerMode && !0 === s.options.infinite ? s.slideOffset += s.slideWidth * Math.floor(s.options.slidesToShow / 2) - s.slideWidth : !0 === s.options.centerMode && (s.slideOffset = 0, s.slideOffset += s.slideWidth * Math.floor(s.options.slidesToShow / 2)), t = !1 === s.options.vertical ? e * s.slideWidth * -1 + s.slideOffset : e * n * -1 + r, !0 === s.options.variableWidth && (i = s.slideCount <= s.options.slidesToShow || !1 === s.options.infinite ? s.$slideTrack.children(".slick-slide").eq(e) : s.$slideTrack.children(".slick-slide").eq(e + s.options.slidesToShow), t = !0 === s.options.rtl ? i[0] ? -1 * (s.$slideTrack.width() - i[0].offsetLeft - i.width()) : 0 : i[0] ? -1 * i[0].offsetLeft : 0, !0 === s.options.centerMode && (i = s.slideCount <= s.options.slidesToShow || !1 === s.options.infinite ? s.$slideTrack.children(".slick-slide").eq(e) : s.$slideTrack.children(".slick-slide").eq(e + s.options.slidesToShow + 1), t = !0 === s.options.rtl ? i[0] ? -1 * (s.$slideTrack.width() - i[0].offsetLeft - i.width()) : 0 : i[0] ? -1 * i[0].offsetLeft : 0, t += (s.$list.width() - i.outerWidth()) / 2)), t
    }, t.prototype.getOption = t.prototype.slickGetOption = function (e) {
        return this.options[e]
    }, t.prototype.getNavigableIndexes = function () {
        var e, t = this, n = 0, i = 0, o = [];
        for (!1 === t.options.infinite ? e = t.slideCount : (n = -1 * t.options.slidesToScroll, i = -1 * t.options.slidesToScroll, e = 2 * t.slideCount); n < e;) o.push(n), n = i + t.options.slidesToScroll, i += t.options.slidesToScroll <= t.options.slidesToShow ? t.options.slidesToScroll : t.options.slidesToShow;
        return o
    }, t.prototype.getSlick = function () {
        return this
    }, t.prototype.getSlideCount = function () {
        var t, n, i = this;
        return n = !0 === i.options.centerMode ? i.slideWidth * Math.floor(i.options.slidesToShow / 2) : 0, !0 === i.options.swipeToSlide ? (i.$slideTrack.find(".slick-slide").each(function (o, s) {
            if (s.offsetLeft - n + e(s).outerWidth() / 2 > -1 * i.swipeLeft) return t = s, !1
        }), Math.abs(e(t).attr("data-slick-index") - i.currentSlide) || 1) : i.options.slidesToScroll
    }, t.prototype.goTo = t.prototype.slickGoTo = function (e, t) {
        this.changeSlide({data: {message: "index", index: parseInt(e)}}, t)
    }, t.prototype.init = function (t) {
        var n = this;
        e(n.$slider).hasClass("slick-initialized") || (e(n.$slider).addClass("slick-initialized"), n.buildRows(), n.buildOut(), n.setProps(), n.startLoad(), n.loadSlider(), n.initializeEvents(), n.updateArrows(), n.updateDots(), n.checkResponsive(!0), n.focusHandler()), t && n.$slider.trigger("init", [n]), !0 === n.options.accessibility && n.initADA(), n.options.autoplay && (n.paused = !1, n.autoPlay())
    }, t.prototype.initADA = function () {
        var t = this, n = Math.ceil(t.slideCount / t.options.slidesToShow),
            i = t.getNavigableIndexes().filter(function (e) {
                return e >= 0 && e < t.slideCount
            });
        t.$slides.add(t.$slideTrack.find(".slick-cloned")).attr({
            "aria-hidden": "true",
            tabindex: "-1"
        }).find("a, input, button, select").attr({tabindex: "-1"}), null !== t.$dots && (t.$slides.not(t.$slideTrack.find(".slick-cloned")).each(function (n) {
            var o = i.indexOf(n);
            e(this).attr({
                role: "tabpanel",
                id: "slick-slide" + t.instanceUid + n,
                tabindex: -1
            }), -1 !== o && e(this).attr({"aria-describedby": "slick-slide-control" + t.instanceUid + o})
        }), t.$dots.attr("role", "tablist").find("li").each(function (o) {
            var s = i[o];
            e(this).attr({role: "presentation"}), e(this).find("button").first().attr({
                role: "tab",
                id: "slick-slide-control" + t.instanceUid + o,
                "aria-controls": "slick-slide" + t.instanceUid + s,
                "aria-label": o + 1 + " of " + n,
                "aria-selected": null,
                tabindex: "-1"
            })
        }).eq(t.currentSlide).find("button").attr({"aria-selected": "true", tabindex: "0"}).end());
        for (var o = t.currentSlide, s = o + t.options.slidesToShow; o < s; o++) t.$slides.eq(o).attr("tabindex", 0);
        t.activateADA()
    }, t.prototype.initArrowEvents = function () {
        var e = this;
        !0 === e.options.arrows && e.slideCount > e.options.slidesToShow && (e.$prevArrow.off("click.slick").on("click.slick", {message: "previous"}, e.changeSlide), e.$nextArrow.off("click.slick").on("click.slick", {message: "next"}, e.changeSlide), !0 === e.options.accessibility && (e.$prevArrow.on("keydown.slick", e.keyHandler), e.$nextArrow.on("keydown.slick", e.keyHandler)))
    }, t.prototype.initDotEvents = function () {
        var t = this;
        !0 === t.options.dots && (e("li", t.$dots).on("click.slick", {message: "index"}, t.changeSlide), !0 === t.options.accessibility && t.$dots.on("keydown.slick", t.keyHandler)), !0 === t.options.dots && !0 === t.options.pauseOnDotsHover && e("li", t.$dots).on("mouseenter.slick", e.proxy(t.interrupt, t, !0)).on("mouseleave.slick", e.proxy(t.interrupt, t, !1))
    }, t.prototype.initSlideEvents = function () {
        var t = this;
        t.options.pauseOnHover && (t.$list.on("mouseenter.slick", e.proxy(t.interrupt, t, !0)), t.$list.on("mouseleave.slick", e.proxy(t.interrupt, t, !1)))
    }, t.prototype.initializeEvents = function () {
        var t = this;
        t.initArrowEvents(), t.initDotEvents(), t.initSlideEvents(), t.$list.on("touchstart.slick mousedown.slick", {action: "start"}, t.swipeHandler), t.$list.on("touchmove.slick mousemove.slick", {action: "move"}, t.swipeHandler), t.$list.on("touchend.slick mouseup.slick", {action: "end"}, t.swipeHandler), t.$list.on("touchcancel.slick mouseleave.slick", {action: "end"}, t.swipeHandler), t.$list.on("click.slick", t.clickHandler), e(document).on(t.visibilityChange, e.proxy(t.visibility, t)), !0 === t.options.accessibility && t.$list.on("keydown.slick", t.keyHandler), !0 === t.options.focusOnSelect && e(t.$slideTrack).children().on("click.slick", t.selectHandler), e(window).on("orientationchange.slick.slick-" + t.instanceUid, e.proxy(t.orientationChange, t)), e(window).on("resize.slick.slick-" + t.instanceUid, e.proxy(t.resize, t)), e("[draggable!=true]", t.$slideTrack).on("dragstart", t.preventDefault), e(window).on("load.slick.slick-" + t.instanceUid, t.setPosition), e(t.setPosition)
    }, t.prototype.initUI = function () {
        var e = this;
        !0 === e.options.arrows && e.slideCount > e.options.slidesToShow && (e.$prevArrow.show(), e.$nextArrow.show()), !0 === e.options.dots && e.slideCount > e.options.slidesToShow && e.$dots.show()
    }, t.prototype.keyHandler = function (e) {
        var t = this;
        e.target.tagName.match("TEXTAREA|INPUT|SELECT") || (37 === e.keyCode && !0 === t.options.accessibility ? t.changeSlide({data: {message: !0 === t.options.rtl ? "next" : "previous"}}) : 39 === e.keyCode && !0 === t.options.accessibility && t.changeSlide({data: {message: !0 === t.options.rtl ? "previous" : "next"}}))
    }, t.prototype.lazyLoad = function () {
        function t(t) {
            e("img[data-lazy]", t).each(function () {
                var t = e(this), n = e(this).attr("data-lazy"), i = e(this).attr("data-srcset"),
                    o = e(this).attr("data-sizes") || s.$slider.attr("data-sizes"), r = document.createElement("img");
                r.onload = function () {
                    t.animate({opacity: 0}, 100, function () {
                        i && (t.attr("srcset", i), o && t.attr("sizes", o)), t.attr("src", n).animate({opacity: 1}, 200, function () {
                            t.removeAttr("data-lazy data-srcset data-sizes").removeClass("slick-loading")
                        }), s.$slider.trigger("lazyLoaded", [s, t, n])
                    })
                }, r.onerror = function () {
                    t.removeAttr("data-lazy").removeClass("slick-loading").addClass("slick-lazyload-error"), s.$slider.trigger("lazyLoadError", [s, t, n])
                }, r.src = n
            })
        }

        var n, i, o, s = this;
        if (!0 === s.options.centerMode ? !0 === s.options.infinite ? o = (i = s.currentSlide + (s.options.slidesToShow / 2 + 1)) + s.options.slidesToShow + 2 : (i = Math.max(0, s.currentSlide - (s.options.slidesToShow / 2 + 1)), o = s.options.slidesToShow / 2 + 1 + 2 + s.currentSlide) : (i = s.options.infinite ? s.options.slidesToShow + s.currentSlide : s.currentSlide, o = Math.ceil(i + s.options.slidesToShow), !0 === s.options.fade && (i > 0 && i--, o <= s.slideCount && o++)), n = s.$slider.find(".slick-slide").slice(i, o), "anticipated" === s.options.lazyLoad) for (var r = i - 1, a = o, l = s.$slider.find(".slick-slide"), c = 0; c < s.options.slidesToScroll; c++) r < 0 && (r = s.slideCount - 1), n = (n = n.add(l.eq(r))).add(l.eq(a)), r--, a++;
        t(n), s.slideCount <= s.options.slidesToShow ? t(s.$slider.find(".slick-slide")) : s.currentSlide >= s.slideCount - s.options.slidesToShow ? t(s.$slider.find(".slick-cloned").slice(0, s.options.slidesToShow)) : 0 === s.currentSlide && t(s.$slider.find(".slick-cloned").slice(-1 * s.options.slidesToShow))
    }, t.prototype.loadSlider = function () {
        var e = this;
        e.setPosition(), e.$slideTrack.css({opacity: 1}), e.$slider.removeClass("slick-loading"), e.initUI(), "progressive" === e.options.lazyLoad && e.progressiveLazyLoad()
    }, t.prototype.next = t.prototype.slickNext = function () {
        this.changeSlide({data: {message: "next"}})
    }, t.prototype.orientationChange = function () {
        var e = this;
        e.checkResponsive(), e.setPosition()
    }, t.prototype.pause = t.prototype.slickPause = function () {
        var e = this;
        e.autoPlayClear(), e.paused = !0
    }, t.prototype.play = t.prototype.slickPlay = function () {
        var e = this;
        e.autoPlay(), e.options.autoplay = !0, e.paused = !1, e.focussed = !1, e.interrupted = !1
    }, t.prototype.postSlide = function (t) {
        var n = this;
        n.unslicked || (n.$slider.trigger("afterChange", [n, t]), n.animating = !1, n.slideCount > n.options.slidesToShow && n.setPosition(), n.swipeLeft = null, n.options.autoplay && n.autoPlay(), !0 === n.options.accessibility && (n.initADA(), n.options.focusOnChange && e(n.$slides.get(n.currentSlide)).attr("tabindex", 0).focus()))
    }, t.prototype.prev = t.prototype.slickPrev = function () {
        this.changeSlide({data: {message: "previous"}})
    }, t.prototype.preventDefault = function (e) {
        e.preventDefault()
    }, t.prototype.progressiveLazyLoad = function (t) {
        t = t || 1;
        var n, i, o, s, r, a = this, l = e("img[data-lazy]", a.$slider);
        l.length ? (n = l.first(), i = n.attr("data-lazy"), o = n.attr("data-srcset"), s = n.attr("data-sizes") || a.$slider.attr("data-sizes"), (r = document.createElement("img")).onload = function () {
            o && (n.attr("srcset", o), s && n.attr("sizes", s)), n.attr("src", i).removeAttr("data-lazy data-srcset data-sizes").removeClass("slick-loading"), !0 === a.options.adaptiveHeight && a.setPosition(), a.$slider.trigger("lazyLoaded", [a, n, i]), a.progressiveLazyLoad()
        }, r.onerror = function () {
            t < 3 ? setTimeout(function () {
                a.progressiveLazyLoad(t + 1)
            }, 500) : (n.removeAttr("data-lazy").removeClass("slick-loading").addClass("slick-lazyload-error"), a.$slider.trigger("lazyLoadError", [a, n, i]), a.progressiveLazyLoad())
        }, r.src = i) : a.$slider.trigger("allImagesLoaded", [a])
    }, t.prototype.refresh = function (t) {
        var n, i, o = this;
        i = o.slideCount - o.options.slidesToShow, !o.options.infinite && o.currentSlide > i && (o.currentSlide = i), o.slideCount <= o.options.slidesToShow && (o.currentSlide = 0), n = o.currentSlide, o.destroy(!0), e.extend(o, o.initials, {currentSlide: n}), o.init(), t || o.changeSlide({
            data: {
                message: "index",
                index: n
            }
        }, !1)
    }, t.prototype.registerBreakpoints = function () {
        var t, n, i, o = this, s = o.options.responsive || null;
        if ("array" === e.type(s) && s.length) {
            o.respondTo = o.options.respondTo || "window";
            for (t in s) if (i = o.breakpoints.length - 1, s.hasOwnProperty(t)) {
                for (n = s[t].breakpoint; i >= 0;) o.breakpoints[i] && o.breakpoints[i] === n && o.breakpoints.splice(i, 1), i--;
                o.breakpoints.push(n), o.breakpointSettings[n] = s[t].settings
            }
            o.breakpoints.sort(function (e, t) {
                return o.options.mobileFirst ? e - t : t - e
            })
        }
    }, t.prototype.reinit = function () {
        var t = this;
        t.$slides = t.$slideTrack.children(t.options.slide).addClass("slick-slide"), t.slideCount = t.$slides.length, t.currentSlide >= t.slideCount && 0 !== t.currentSlide && (t.currentSlide = t.currentSlide - t.options.slidesToScroll), t.slideCount <= t.options.slidesToShow && (t.currentSlide = 0), t.registerBreakpoints(), t.setProps(), t.setupInfinite(), t.buildArrows(), t.updateArrows(), t.initArrowEvents(), t.buildDots(), t.updateDots(), t.initDotEvents(), t.cleanUpSlideEvents(), t.initSlideEvents(), t.checkResponsive(!1, !0), !0 === t.options.focusOnSelect && e(t.$slideTrack).children().on("click.slick", t.selectHandler), t.setSlideClasses("number" == typeof t.currentSlide ? t.currentSlide : 0), t.setPosition(), t.focusHandler(), t.paused = !t.options.autoplay, t.autoPlay(), t.$slider.trigger("reInit", [t])
    }, t.prototype.resize = function () {
        var t = this;
        e(window).width() !== t.windowWidth && (clearTimeout(t.windowDelay), t.windowDelay = window.setTimeout(function () {
            t.windowWidth = e(window).width(), t.checkResponsive(), t.unslicked || t.setPosition()
        }, 50))
    }, t.prototype.removeSlide = t.prototype.slickRemove = function (e, t, n) {
        var i = this;
        return e = "boolean" == typeof e ? !0 === (t = e) ? 0 : i.slideCount - 1 : !0 === t ? --e : e, !(i.slideCount < 1 || e < 0 || e > i.slideCount - 1) && (i.unload(), !0 === n ? i.$slideTrack.children().remove() : i.$slideTrack.children(this.options.slide).eq(e).remove(), i.$slides = i.$slideTrack.children(this.options.slide), i.$slideTrack.children(this.options.slide).detach(), i.$slideTrack.append(i.$slides), i.$slidesCache = i.$slides, i.reinit(), void 0)
    }, t.prototype.setCSS = function (e) {
        var t, n, i = this, o = {};
        !0 === i.options.rtl && (e = -e), t = "left" == i.positionProp ? Math.ceil(e) + "px" : "0px", n = "top" == i.positionProp ? Math.ceil(e) + "px" : "0px", o[i.positionProp] = e, !1 === i.transformsEnabled ? i.$slideTrack.css(o) : (o = {}, !1 === i.cssTransitions ? (o[i.animType] = "translate(" + t + ", " + n + ")", i.$slideTrack.css(o)) : (o[i.animType] = "translate3d(" + t + ", " + n + ", 0px)", i.$slideTrack.css(o)))
    }, t.prototype.setDimensions = function () {
        var e = this;
        !1 === e.options.vertical ? !0 === e.options.centerMode && e.$list.css({padding: "0px " + e.options.centerPadding}) : (e.$list.height(e.$slides.first().outerHeight(!0) * e.options.slidesToShow), !0 === e.options.centerMode && e.$list.css({padding: e.options.centerPadding + " 0px"})), e.listWidth = e.$list.width(), e.listHeight = e.$list.height(), !1 === e.options.vertical && !1 === e.options.variableWidth ? (e.slideWidth = Math.ceil(e.listWidth / e.options.slidesToShow), e.$slideTrack.width(Math.ceil(e.slideWidth * e.$slideTrack.children(".slick-slide").length))) : !0 === e.options.variableWidth ? e.$slideTrack.width(5e3 * e.slideCount) : (e.slideWidth = Math.ceil(e.listWidth), e.$slideTrack.height(Math.ceil(e.$slides.first().outerHeight(!0) * e.$slideTrack.children(".slick-slide").length)));
        var t = e.$slides.first().outerWidth(!0) - e.$slides.first().width();
        !1 === e.options.variableWidth && e.$slideTrack.children(".slick-slide").width(e.slideWidth - t)
    }, t.prototype.setFade = function () {
        var t, n = this;
        n.$slides.each(function (i, o) {
            t = n.slideWidth * i * -1, !0 === n.options.rtl ? e(o).css({
                position: "relative",
                right: t,
                top: 0,
                zIndex: n.options.zIndex - 2,
                opacity: 0
            }) : e(o).css({position: "relative", left: t, top: 0, zIndex: n.options.zIndex - 2, opacity: 0})
        }), n.$slides.eq(n.currentSlide).css({zIndex: n.options.zIndex - 1, opacity: 1})
    }, t.prototype.setHeight = function () {
        var e = this;
        if (1 === e.options.slidesToShow && !0 === e.options.adaptiveHeight && !1 === e.options.vertical) {
            var t = e.$slides.eq(e.currentSlide).outerHeight(!0);
            e.$list.css("height", t)
        }
    }, t.prototype.setOption = t.prototype.slickSetOption = function () {
        var t, n, i, o, s, r = this, a = !1;
        if ("object" === e.type(arguments[0]) ? (i = arguments[0], a = arguments[1], s = "multiple") : "string" === e.type(arguments[0]) && (i = arguments[0], o = arguments[1], a = arguments[2], "responsive" === arguments[0] && "array" === e.type(arguments[1]) ? s = "responsive" : void 0 !== arguments[1] && (s = "single")), "single" === s) r.options[i] = o; else if ("multiple" === s) e.each(i, function (e, t) {
            r.options[e] = t
        }); else if ("responsive" === s) for (n in o) if ("array" !== e.type(r.options.responsive)) r.options.responsive = [o[n]]; else {
            for (t = r.options.responsive.length - 1; t >= 0;) r.options.responsive[t].breakpoint === o[n].breakpoint && r.options.responsive.splice(t, 1), t--;
            r.options.responsive.push(o[n])
        }
        a && (r.unload(), r.reinit())
    }, t.prototype.setPosition = function () {
        var e = this;
        e.setDimensions(), e.setHeight(), !1 === e.options.fade ? e.setCSS(e.getLeft(e.currentSlide)) : e.setFade(), e.$slider.trigger("setPosition", [e])
    }, t.prototype.setProps = function () {
        var e = this, t = document.body.style;
        e.positionProp = !0 === e.options.vertical ? "top" : "left", "top" === e.positionProp ? e.$slider.addClass("slick-vertical") : e.$slider.removeClass("slick-vertical"), void 0 === t.WebkitTransition && void 0 === t.MozTransition && void 0 === t.msTransition || !0 === e.options.useCSS && (e.cssTransitions = !0), e.options.fade && ("number" == typeof e.options.zIndex ? e.options.zIndex < 3 && (e.options.zIndex = 3) : e.options.zIndex = e.defaults.zIndex), void 0 !== t.OTransform && (e.animType = "OTransform", e.transformType = "-o-transform", e.transitionType = "OTransition", void 0 === t.perspectiveProperty && void 0 === t.webkitPerspective && (e.animType = !1)), void 0 !== t.MozTransform && (e.animType = "MozTransform", e.transformType = "-moz-transform", e.transitionType = "MozTransition", void 0 === t.perspectiveProperty && void 0 === t.MozPerspective && (e.animType = !1)), void 0 !== t.webkitTransform && (e.animType = "webkitTransform", e.transformType = "-webkit-transform", e.transitionType = "webkitTransition", void 0 === t.perspectiveProperty && void 0 === t.webkitPerspective && (e.animType = !1)), void 0 !== t.msTransform && (e.animType = "msTransform", e.transformType = "-ms-transform", e.transitionType = "msTransition", void 0 === t.msTransform && (e.animType = !1)), void 0 !== t.transform && !1 !== e.animType && (e.animType = "transform", e.transformType = "transform", e.transitionType = "transition"), e.transformsEnabled = e.options.useTransform && null !== e.animType && !1 !== e.animType
    }, t.prototype.setSlideClasses = function (e) {
        var t, n, i, o, s = this;
        if (n = s.$slider.find(".slick-slide").removeClass("slick-active slick-center slick-current").attr("aria-hidden", "true"), s.$slides.eq(e).addClass("slick-current"), !0 === s.options.centerMode) {
            var r = s.options.slidesToShow % 2 == 0 ? 1 : 0;
            t = Math.floor(s.options.slidesToShow / 2), !0 === s.options.infinite && (e >= t && e <= s.slideCount - 1 - t ? s.$slides.slice(e - t + r, e + t + 1).addClass("slick-active").attr("aria-hidden", "false") : (i = s.options.slidesToShow + e, n.slice(i - t + 1 + r, i + t + 2).addClass("slick-active").attr("aria-hidden", "false")), 0 === e ? n.eq(n.length - 1 - s.options.slidesToShow).addClass("slick-center") : e === s.slideCount - 1 && n.eq(s.options.slidesToShow).addClass("slick-center")), s.$slides.eq(e).addClass("slick-center")
        } else e >= 0 && e <= s.slideCount - s.options.slidesToShow ? s.$slides.slice(e, e + s.options.slidesToShow).addClass("slick-active").attr("aria-hidden", "false") : n.length <= s.options.slidesToShow ? n.addClass("slick-active").attr("aria-hidden", "false") : (o = s.slideCount % s.options.slidesToShow, i = !0 === s.options.infinite ? s.options.slidesToShow + e : e, s.options.slidesToShow == s.options.slidesToScroll && s.slideCount - e < s.options.slidesToShow ? n.slice(i - (s.options.slidesToShow - o), i + o).addClass("slick-active").attr("aria-hidden", "false") : n.slice(i, i + s.options.slidesToShow).addClass("slick-active").attr("aria-hidden", "false"));
        "ondemand" !== s.options.lazyLoad && "anticipated" !== s.options.lazyLoad || s.lazyLoad()
    }, t.prototype.setupInfinite = function () {
        var t, n, i, o = this;
        if (!0 === o.options.fade && (o.options.centerMode = !1), !0 === o.options.infinite && !1 === o.options.fade && (n = null, o.slideCount > o.options.slidesToShow)) {
            for (i = !0 === o.options.centerMode ? o.options.slidesToShow + 1 : o.options.slidesToShow, t = o.slideCount; t > o.slideCount - i; t -= 1) n = t - 1, e(o.$slides[n]).clone(!0).attr("id", "").attr("data-slick-index", n - o.slideCount).prependTo(o.$slideTrack).addClass("slick-cloned");
            for (t = 0; t < i + o.slideCount; t += 1) n = t, e(o.$slides[n]).clone(!0).attr("id", "").attr("data-slick-index", n + o.slideCount).appendTo(o.$slideTrack).addClass("slick-cloned");
            o.$slideTrack.find(".slick-cloned").find("[id]").each(function () {
                e(this).attr("id", "")
            })
        }
    }, t.prototype.interrupt = function (e) {
        var t = this;
        e || t.autoPlay(), t.interrupted = e
    }, t.prototype.selectHandler = function (t) {
        var n = this, i = e(t.target).is(".slick-slide") ? e(t.target) : e(t.target).parents(".slick-slide"),
            o = parseInt(i.attr("data-slick-index"));
        o || (o = 0), n.slideCount <= n.options.slidesToShow ? n.slideHandler(o, !1, !0) : n.slideHandler(o)
    }, t.prototype.slideHandler = function (e, t, n) {
        var i, o, s, r, a, l = null, c = this;
        if (t = t || !1, !(!0 === c.animating && !0 === c.options.waitForAnimate || !0 === c.options.fade && c.currentSlide === e)) if (!1 === t && c.asNavFor(e), i = e, l = c.getLeft(i), r = c.getLeft(c.currentSlide), c.currentLeft = null === c.swipeLeft ? r : c.swipeLeft, !1 === c.options.infinite && !1 === c.options.centerMode && (e < 0 || e > c.getDotCount() * c.options.slidesToScroll)) !1 === c.options.fade && (i = c.currentSlide, !0 !== n ? c.animateSlide(r, function () {
            c.postSlide(i)
        }) : c.postSlide(i)); else if (!1 === c.options.infinite && !0 === c.options.centerMode && (e < 0 || e > c.slideCount - c.options.slidesToScroll)) !1 === c.options.fade && (i = c.currentSlide, !0 !== n ? c.animateSlide(r, function () {
            c.postSlide(i)
        }) : c.postSlide(i)); else {
            if (c.options.autoplay && clearInterval(c.autoPlayTimer), o = i < 0 ? c.slideCount % c.options.slidesToScroll != 0 ? c.slideCount - c.slideCount % c.options.slidesToScroll : c.slideCount + i : i >= c.slideCount ? c.slideCount % c.options.slidesToScroll != 0 ? 0 : i - c.slideCount : i, c.animating = !0, c.$slider.trigger("beforeChange", [c, c.currentSlide, o]), s = c.currentSlide, c.currentSlide = o, c.setSlideClasses(c.currentSlide), c.options.asNavFor && (a = (a = c.getNavTarget()).slick("getSlick")).slideCount <= a.options.slidesToShow && a.setSlideClasses(c.currentSlide), c.updateDots(), c.updateArrows(), !0 === c.options.fade) return !0 !== n ? (c.fadeSlideOut(s), c.fadeSlide(o, function () {
                c.postSlide(o)
            })) : c.postSlide(o), void c.animateHeight();
            !0 !== n ? c.animateSlide(l, function () {
                c.postSlide(o)
            }) : c.postSlide(o)
        }
    }, t.prototype.startLoad = function () {
        var e = this;
        !0 === e.options.arrows && e.slideCount > e.options.slidesToShow && (e.$prevArrow.hide(), e.$nextArrow.hide()), !0 === e.options.dots && e.slideCount > e.options.slidesToShow && e.$dots.hide(), e.$slider.addClass("slick-loading")
    }, t.prototype.swipeDirection = function () {
        var e, t, n, i, o = this;
        return e = o.touchObject.startX - o.touchObject.curX, t = o.touchObject.startY - o.touchObject.curY, n = Math.atan2(t, e), (i = Math.round(180 * n / Math.PI)) < 0 && (i = 360 - Math.abs(i)), i <= 45 && i >= 0 ? !1 === o.options.rtl ? "left" : "right" : i <= 360 && i >= 315 ? !1 === o.options.rtl ? "left" : "right" : i >= 135 && i <= 225 ? !1 === o.options.rtl ? "right" : "left" : !0 === o.options.verticalSwiping ? i >= 35 && i <= 135 ? "down" : "up" : "vertical"
    }, t.prototype.swipeEnd = function (e) {
        var t, n, i = this;
        if (i.dragging = !1, i.swiping = !1, i.scrolling) return i.scrolling = !1, !1;
        if (i.interrupted = !1, i.shouldClick = !(i.touchObject.swipeLength > 10), void 0 === i.touchObject.curX) return !1;
        if (!0 === i.touchObject.edgeHit && i.$slider.trigger("edge", [i, i.swipeDirection()]), i.touchObject.swipeLength >= i.touchObject.minSwipe) {
            switch (n = i.swipeDirection()) {
                case"left":
                case"down":
                    t = i.options.swipeToSlide ? i.checkNavigable(i.currentSlide + i.getSlideCount()) : i.currentSlide + i.getSlideCount(), i.currentDirection = 0;
                    break;
                case"right":
                case"up":
                    t = i.options.swipeToSlide ? i.checkNavigable(i.currentSlide - i.getSlideCount()) : i.currentSlide - i.getSlideCount(), i.currentDirection = 1
            }
            "vertical" != n && (i.slideHandler(t), i.touchObject = {}, i.$slider.trigger("swipe", [i, n]))
        } else i.touchObject.startX !== i.touchObject.curX && (i.slideHandler(i.currentSlide), i.touchObject = {})
    }, t.prototype.swipeHandler = function (e) {
        var t = this;
        if (!(!1 === t.options.swipe || "ontouchend" in document && !1 === t.options.swipe || !1 === t.options.draggable && -1 !== e.type.indexOf("mouse"))) switch (t.touchObject.fingerCount = e.originalEvent && void 0 !== e.originalEvent.touches ? e.originalEvent.touches.length : 1, t.touchObject.minSwipe = t.listWidth / t.options.touchThreshold, !0 === t.options.verticalSwiping && (t.touchObject.minSwipe = t.listHeight / t.options.touchThreshold), e.data.action) {
            case"start":
                t.swipeStart(e);
                break;
            case"move":
                t.swipeMove(e);
                break;
            case"end":
                t.swipeEnd(e)
        }
    }, t.prototype.swipeMove = function (e) {
        var t, n, i, o, s, r, a = this;
        return s = void 0 !== e.originalEvent ? e.originalEvent.touches : null, !(!a.dragging || a.scrolling || s && 1 !== s.length) && (t = a.getLeft(a.currentSlide), a.touchObject.curX = void 0 !== s ? s[0].pageX : e.clientX, a.touchObject.curY = void 0 !== s ? s[0].pageY : e.clientY, a.touchObject.swipeLength = Math.round(Math.sqrt(Math.pow(a.touchObject.curX - a.touchObject.startX, 2))), r = Math.round(Math.sqrt(Math.pow(a.touchObject.curY - a.touchObject.startY, 2))), !a.options.verticalSwiping && !a.swiping && r > 4 ? (a.scrolling = !0, !1) : (!0 === a.options.verticalSwiping && (a.touchObject.swipeLength = r), n = a.swipeDirection(), void 0 !== e.originalEvent && a.touchObject.swipeLength > 4 && (a.swiping = !0, e.preventDefault()), o = (!1 === a.options.rtl ? 1 : -1) * (a.touchObject.curX > a.touchObject.startX ? 1 : -1), !0 === a.options.verticalSwiping && (o = a.touchObject.curY > a.touchObject.startY ? 1 : -1), i = a.touchObject.swipeLength, a.touchObject.edgeHit = !1, !1 === a.options.infinite && (0 === a.currentSlide && "right" === n || a.currentSlide >= a.getDotCount() && "left" === n) && (i = a.touchObject.swipeLength * a.options.edgeFriction, a.touchObject.edgeHit = !0), !1 === a.options.vertical ? a.swipeLeft = t + i * o : a.swipeLeft = t + i * (a.$list.height() / a.listWidth) * o, !0 === a.options.verticalSwiping && (a.swipeLeft = t + i * o), !0 !== a.options.fade && !1 !== a.options.touchMove && (!0 === a.animating ? (a.swipeLeft = null, !1) : void a.setCSS(a.swipeLeft))))
    }, t.prototype.swipeStart = function (e) {
        var t, n = this;
        return n.interrupted = !0, 1 !== n.touchObject.fingerCount || n.slideCount <= n.options.slidesToShow ? (n.touchObject = {}, !1) : (void 0 !== e.originalEvent && void 0 !== e.originalEvent.touches && (t = e.originalEvent.touches[0]), n.touchObject.startX = n.touchObject.curX = void 0 !== t ? t.pageX : e.clientX, n.touchObject.startY = n.touchObject.curY = void 0 !== t ? t.pageY : e.clientY, n.dragging = !0, void 0)
    }, t.prototype.unfilterSlides = t.prototype.slickUnfilter = function () {
        var e = this;
        null !== e.$slidesCache && (e.unload(), e.$slideTrack.children(this.options.slide).detach(), e.$slidesCache.appendTo(e.$slideTrack), e.reinit())
    }, t.prototype.unload = function () {
        var t = this;
        e(".slick-cloned", t.$slider).remove(), t.$dots && t.$dots.remove(), t.$prevArrow && t.htmlExpr.test(t.options.prevArrow) && t.$prevArrow.remove(), t.$nextArrow && t.htmlExpr.test(t.options.nextArrow) && t.$nextArrow.remove(), t.$slides.removeClass("slick-slide slick-active slick-visible slick-current").attr("aria-hidden", "true").css("width", "")
    }, t.prototype.unslick = function (e) {
        var t = this;
        t.$slider.trigger("unslick", [t, e]), t.destroy()
    }, t.prototype.updateArrows = function () {
        var e = this;
        Math.floor(e.options.slidesToShow / 2), !0 === e.options.arrows && e.slideCount > e.options.slidesToShow && !e.options.infinite && (e.$prevArrow.removeClass("slick-disabled").attr("aria-disabled", "false"), e.$nextArrow.removeClass("slick-disabled").attr("aria-disabled", "false"), 0 === e.currentSlide ? (e.$prevArrow.addClass("slick-disabled").attr("aria-disabled", "true"), e.$nextArrow.removeClass("slick-disabled").attr("aria-disabled", "false")) : e.currentSlide >= e.slideCount - e.options.slidesToShow && !1 === e.options.centerMode ? (e.$nextArrow.addClass("slick-disabled").attr("aria-disabled", "true"), e.$prevArrow.removeClass("slick-disabled").attr("aria-disabled", "false")) : e.currentSlide >= e.slideCount - 1 && !0 === e.options.centerMode && (e.$nextArrow.addClass("slick-disabled").attr("aria-disabled", "true"), e.$prevArrow.removeClass("slick-disabled").attr("aria-disabled", "false")))
    }, t.prototype.updateDots = function () {
        var e = this;
        null !== e.$dots && (e.$dots.find("li").removeClass("slick-active").end(), e.$dots.find("li").eq(Math.floor(e.currentSlide / e.options.slidesToScroll)).addClass("slick-active"))
    }, t.prototype.visibility = function () {
        var e = this;
        e.options.autoplay && (document[e.hidden] ? e.interrupted = !0 : e.interrupted = !1)
    }, e.fn.slick = function () {
        var e, n, i = this, o = arguments[0], s = Array.prototype.slice.call(arguments, 1), r = i.length;
        for (e = 0; e < r; e++) if ("object" == ("undefined" == typeof o ? "undefined" : _typeof(o)) || void 0 === o ? i[e].slick = new t(i[e], o) : n = i[e].slick[o].apply(i[e].slick, s), void 0 !== n) return n;
        return i
    }
}),


    $(document).foundation();


var newsSettings = {
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: !1,
    autoplaySpeed: 3e3,
    accessibility: !1,
    draggable: !1,
    responsive: [{breakpoint: 1024, settings: {slidesToShow: 3, slidesToScroll: 1, infinite: !0}}, {
        breakpoint: 600,
        settings: {slidesToShow: 1, slidesToScroll: 1}
    }],

    nextArrow: '<button type="button" class="slick-next"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-next.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-next--hover.png"></button>',

        prevArrow: '<button type="button" class="slick-prev"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-prev.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-prev--hover.png"></button>'
},

    mainSliderSettings = {
    arrows: !1,
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: !0,
    autoplaySpeed: 3e3,
    accessibility: !1,
    draggable: !1
},

    productsSettings = {
    slidesToShow: 4,
    slidesToScroll: 1,
    autoplay: !1,
    autoplaySpeed: 3e3,
    accessibility: !1,
    draggable: !1,
    responsive: [{breakpoint: 1200, settings: {slidesToShow: 3, slidesToScroll: 1, infinite: !0}}, {
        breakpoint: 1024,
        settings: {slidesToShow: 2, slidesToScroll: 1, infinite: !0}
    }],

        nextArrow: '<button type="button" class="slick-next"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-next.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-next--hover.png"></button>',

        prevArrow: '<button type="button" class="slick-prev"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-prev.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-prev--hover.png"></button>'
},

    partnersSlider = {
    slidesToShow: 4,
    slidesToScroll: 1,
    autoplay: !0,
    autoplaySpeed: 3e3,
    accessibility: !1,
    draggable: !1,
    responsive: [{breakpoint: 1024, settings: {slidesToShow: 3, slidesToScroll: 1}}],
    nextArrow: '<button type="button" class="slick-next"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/right-arrow.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/right-arrow--hover.png"></button>',
    prevArrow: '<button type="button" class="slick-prev"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/left-arrow.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/left-arrow--hover.png"></button>'
};
!function (e) {
    function t() {
        n = e("#sliderOutput1").attr("aria-valuenow"), i = e("#sliderOutput2").attr("aria-valuenow"), o.text(n), s.text(i)
    }

    e(function () {
        var t = function () {
            e("#news-label").addClass("isActive").siblings().removeClass("isActive"), e("#publications").hide(), e("#news").show(), e(".my-slider_news").slick(newsSettings)
        }, n = function () {

            e("#publications-label").addClass("isActive").siblings().removeClass("isActive"), e("#news").hide(), e("#publications").show(),
            e(".my-slider_publication").slick(newsSettings)
        };
        t(),
            e("#publications-label").on("click", function () {
            n()
        }),
            e("#news-label").on("click", function () {
            t()
        }),
            e(".my-slider_main").slick(mainSliderSettings), e(".my-slider_product").slick(productsSettings),
            e(".my-slider_partners").slick(partnersSlider)
    });
    var n, i, o = e("#fstVal"), s = e("#secVal");
    t(), e("#sliderOutput1, #sliderOutput2").on("mousedown", function () {
        return e(document).on("mousemove", t).one("mouseup", function () {
            return e(document).off("mousemove", t)
        })
    })
}(jQuery);