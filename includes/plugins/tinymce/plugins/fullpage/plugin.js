! function() {
	"use strict";
	var l = function(e) {
		var t = e,
		n = function() {
			return t
		};
		return {
			get: n,
			set: function(e) {
				t = e
			},
			clone: function() {
				return l(n())
			}
		}
	},
	e = tinymce.util.Tools.resolve("tinymce.PluginManager"),
	g = tinymce.util.Tools.resolve("tinymce.util.Tools"),
	t = tinymce.util.Tools.resolve("tinymce.html.DomParser"),
	f = tinymce.util.Tools.resolve("tinymce.html.Node"),
	m = tinymce.util.Tools.resolve("tinymce.html.Serializer"),
	h = function(e) {
		return e.getParam("fullpage_hide_in_source_view")
	},
	r = function(e) {
		return e.getParam("fullpage_default_xml_pi")
	},
	o = function(e) {
		return e.getParam("fullpage_default_encoding")
	},
	a = function(e) {
		return e.getParam("fullpage_default_font_family")
	},
	c = function(e) {
		return e.getParam("fullpage_default_font_size")
	},
	s = function(e) {
		return e.getParam("fullpage_default_text_color")
	},
	u = function(e) {
		return e.getParam("fullpage_default_title")
	},
	d = function(e) {
		return e.getParam("fullpage_default_doctype", "<!DOCTYPE html>")
	},
	p = function(e) {
		return t({
			validate: !1,
			root_name: "#document"
		}).parse(e)
	},
	y = p,
	v = function(e, t) {
		var n, l, i = p(t),
		r = {};

		function o(e, t) {
			return e.attr(t) || ""
		}
		return r.fontface = a(e), r.fontsize = c(e), 7 === (n = i.firstChild).type && (r.xml_pi = !0, (l = /encoding="([^"]+)"/.exec(n.value)) && (r.docencoding = l[1])), (n = i.getAll("#doctype")[0]) && (r.doctype = "<!DOCTYPE" + n.value + ">"), (n = i.getAll("title")[0]) && n.firstChild && (r.title = n.firstChild.value), g.each(i.getAll("meta"), function(e) {
			var t, n = e.attr("name"),
			l = e.attr("http-equiv");
			n ? r[n.toLowerCase()] = e.attr("content") : "Content-Type" === l && (t = /charset\s*=\s*(.*)\s*/gi.exec(e.attr("content"))) && (r.docencoding = t[1])
		}), (n = i.getAll("html")[0]) && (r.langcode = o(n, "lang") || o(n, "xml:lang")), r.stylesheets = [], g.each(i.getAll("link"), function(e) {
			"stylesheet" === e.attr("rel") && r.stylesheets.push(e.attr("href"))
		}), (n = i.getAll("body")[0]) && (r.langdir = o(n, "dir"), r.style = o(n, "style"), r.visited_color = o(n, "vlink"), r.link_color = o(n, "link"), r.active_color = o(n, "alink")), r
	},
	_ = function(e, r, t) {
		var o, n, l, a, i, c = e.dom;

		function s(e, t, n) {
			e.attr(t, n || undefined)
		}

		function u(e) {
			n.firstChild ? n.insert(e, n.firstChild) : n.append(e)
		}
		o = p(t), (n = o.getAll("head")[0]) || (a = o.getAll("html")[0], n = new f("head", 1), a.firstChild ? a.insert(n, a.firstChild, !0) : a.append(n)), a = o.firstChild, r.xml_pi ? (i = 'version="1.0"', r.docencoding && (i += ' encoding="' + r.docencoding + '"'), 7 !== a.type && (a = new f("xml", 7), o.insert(a, o.firstChild, !0)), a.value = i) : a && 7 === a.type && a.remove(), a = o.getAll("#doctype")[0], r.doctype ? (a || (a = new f("#doctype", 10), r.xml_pi ? o.insert(a, o.firstChild) : u(a)), a.value = r.doctype.substring(9, r.doctype.length - 1)) : a && a.remove(), a = null, g.each(o.getAll("meta"), function(e) {
			"Content-Type" === e.attr("http-equiv") && (a = e)
		}), r.docencoding ? (a || ((a = new f("meta", 1)).attr("http-equiv", "Content-Type"), a.shortEnded = !0, u(a)), a.attr("content", "text/html; charset=" + r.docencoding)) : a && a.remove(), a = o.getAll("title")[0], r.title ? (a ? a.empty() : u(a = new f("title", 1)), a.append(new f("#text", 3)).value = r.title) : a && a.remove(), g.each("keywords,description,author,copyright,robots".split(","), function(e) {
			var t, n, l = o.getAll("meta"),
			i = r[e];
			for (t = 0; t < l.length; t++)
				if ((n = l[t]).attr("name") === e) return void(i ? n.attr("content", i) : n.remove());
			i && ((a = new f("meta", 1)).attr("name", e), a.attr("content", i), a.shortEnded = !0, u(a))
		});
		var d = {};
		return g.each(o.getAll("link"), function(e) {
			"stylesheet" === e.attr("rel") && (d[e.attr("href")] = e)
		}), g.each(r.stylesheets, function(e) {
			d[e] || ((a = new f("link", 1)).attr({
				rel: "stylesheet",
				text: "text/css",
				href: e
			}), a.shortEnded = !0, u(a)), delete d[e]
		}), g.each(d, function(e) {
			e.remove()
		}), (a = o.getAll("body")[0]) && (s(a, "dir", r.langdir), s(a, "style", r.style), s(a, "vlink", r.visited_color), s(a, "link", r.link_color), s(a, "alink", r.active_color), c.setAttribs(e.getBody(), {
			style: r.style,
			dir: r.dir,
			vLink: r.visited_color,
			link: r.link_color,
			aLink: r.active_color
		})), (a = o.getAll("html")[0]) && (s(a, "lang", r.langcode), s(a, "xml:lang", r.langcode)), n.firstChild || n.remove(), (l = m({
			validate: !1,
			indent: !0,
			apply_source_formatting: !0,
			indent_before: "head,html,body,meta,title,script,link,style",
			indent_after: "head,html,body,meta,title,script,link,style"
		}).serialize(o)).substring(0, l.indexOf("</body>"))
	},
	n = function(n, l) {
		var i = v(n, l.get());
		n.windowManager.open({
			title: "Document properties",
			data: i,
			defaults: {
				type: "textbox",
				size: 40
			},
			body: [{
				name: "title",
				label: "Title"
			}, {
				name: "keywords",
				label: "Keywords"
			}, {
				name: "description",
				label: "Description"
			}, {
				name: "robots",
				label: "Robots"
			}, {
				name: "author",
				label: "Author"
			}, {
				name: "docencoding",
				label: "Encoding"
			}],
			onSubmit: function(e) {
				var t = _(n, g.extend(i, e.data), l.get());
				l.set(t)
			}
		})
	},
	i = function(e, t) {
		e.addCommand("mceFullPageProperties", function() {
			n(e, t)
		})
	},
	b = function(e, t) {
		return g.each(e, function(e) {
			t = t.replace(e, function(e) {
				return "\x3c!--mce:protected " + escape(e) + "--\x3e"
			})
		}), t
	},
	x = function(e) {
		return e.replace(/<!--mce:protected ([\s\S]*?)-->/g, function(e, t) {
			return unescape(t)
		})
	},
	k = g.each,
	C = function(e) {
		return e.replace(/<\/?[A-Z]+/g, function(e) {
			return e.toLowerCase()
		})
	},
	A = function(e) {
		var t, n = "",
		l = "";
		if (r(e)) {
			var i = o(e);
			n += '<?xml version="1.0" encoding="' + (i || "ISO-8859-1") + '" ?>\n'
		}
		return n += d(e), n += "\n<html>\n<head>\n", (t = u(e)) && (n += "<title>" + t + "</title>\n"), (t = o(e)) && (n += '<meta http-equiv="Content-Type" content="text/html; charset=' + t + '" />\n'), (t = a(e)) && (l += "font-family: " + t + ";"), (t = c(e)) && (l += "font-size: " + t + ";"), (t = s(e)) && (l += "color: " + t + ";"), n += "</head>\n<body" + (l ? ' style="' + l + '"' : "") + ">\n"
	},
	w = function(r, o, a) {
		r.on("BeforeSetContent", function(e) {
			! function(e, t, n, l) {
				var i, r, o, a, c, s = "",
				u = e.dom;
				if (!(l.selection || (o = b(e.settings.protect, l.content), "raw" === l.format && t.get() || l.source_view && h(e)))) {
					0 !== o.length || l.source_view || (o = g.trim(t.get()) + "\n" + g.trim(o) + "\n" + g.trim(n.get())), -1 !== (i = (o = o.replace(/<(\/?)BODY/gi, "<$1body")).indexOf("<body")) ? (i = o.indexOf(">", i), t.set(C(o.substring(0, i + 1))), -1 === (r = o.indexOf("</body", i)) && (r = o.length), l.content = g.trim(o.substring(i + 1, r)), n.set(C(o.substring(r)))) : (t.set(A(e)), n.set("\n</body>\n</html>")), a = y(t.get()), k(a.getAll("style"), function(e) {
						e.firstChild && (s += e.firstChild.value)
					}), (c = a.getAll("body")[0]) && u.setAttribs(e.getBody(), {
						style: c.attr("style") || "",
						dir: c.attr("dir") || "",
						vLink: c.attr("vlink") || "",
						link: c.attr("link") || "",
						aLink: c.attr("alink") || ""
					}), u.remove("fullpage_styles");
					var d = e.getDoc().getElementsByTagName("head")[0];
					s && (u.add(d, "style", {
						id: "fullpage_styles"
					}, s), (c = u.get("fullpage_styles")).styleSheet && (c.styleSheet.cssText = s));
					var f = {};
					g.each(d.getElementsByTagName("link"), function(e) {
						"stylesheet" === e.rel && e.getAttribute("data-mce-fullpage") && (f[e.href] = e)
					}), g.each(a.getAll("link"), function(e) {
						var t = e.attr("href");
						if (!t) return !0;
						f[t] || "stylesheet" !== e.attr("rel") || u.add(d, "link", {
							rel: "stylesheet",
							text: "text/css",
							href: t,
							"data-mce-fullpage": "1"
						}), delete f[t]
					}), g.each(f, function(e) {
						e.parentNode.removeChild(e)
					})
				}
			}(r, o, a, e)
		}), r.on("GetContent", function(e) {
			var t, n, l, i;
			t = r, n = o.get(), l = a.get(), (i = e).selection || i.source_view && h(t) || (i.content = x(g.trim(n) + "\n" + g.trim(i.content) + "\n" + g.trim(l)))
		})
	},
	P = function(e) {
		e.addButton("fullpage", {
			title: "Document properties",
			cmd: "mceFullPageProperties"
		}), e.addMenuItem("fullpage", {
			text: "Document properties",
			cmd: "mceFullPageProperties",
			context: "file"
		})
	};
	e.add("fullpage", function(e) {
		var t = l(""),
		n = l("");
		i(e, t), P(e), w(e, t, n)
	})
}();
