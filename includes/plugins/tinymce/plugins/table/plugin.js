! function() {
	"use strict";
	var e = tinymce.util.Tools.resolve("tinymce.PluginManager"),
	y = function() {
		for (var e = [], t = 0; t < arguments.length; t++) e[t] = arguments[t]
	},
	x = function(n, r) {
		return function() {
			for (var e = [], t = 0; t < arguments.length; t++) e[t] = arguments[t];
			return n(r.apply(null, e))
		}
	},
	C = function(e) {
		return function() {
			return e
		}
	},
	o = function(e) {
		return e
	};

	function b(r) {
		for (var o = [], e = 1; e < arguments.length; e++) o[e - 1] = arguments[e];
		return function() {
			for (var e = [], t = 0; t < arguments.length; t++) e[t] = arguments[t];
			var n = o.concat(e);
			return r.apply(null, n)
		}
	}
	var t, n, r, i, u, m = function(n) {
		return function() {
			for (var e = [], t = 0; t < arguments.length; t++) e[t] = arguments[t];
			return !n.apply(null, e)
		}
	},
	c = function(e) {
		return e()
	},
	a = C(!1),
	l = C(!0),
	f = a,
	s = l,
	d = function() {
		return g
	},
	g = (i = {
			fold: function(e, t) {
				return e()
			},
			is: f,
			isSome: f,
			isNone: s,
			getOr: r = function(e) {
				return e
			},
			getOrThunk: n = function(e) {
				return e()
			},
			getOrDie: function(e) {
				throw new Error(e || "error: getOrDie called on none.")
			},
			getOrNull: function() {
				return null
			},
			getOrUndefined: function() {
				return undefined
			},
			or: r,
			orThunk: n,
			map: d,
			ap: d,
			each: function() {},
			bind: d,
			flatten: d,
			exists: f,
			forall: s,
			filter: d,
			equals: t = function(e) {
				return e.isNone()
			},
			equals_: t,
			toArray: function() {
				return []
			},
			toString: C("none()")
	}, Object.freeze && Object.freeze(i), i),
	h = function(n) {
		var e = function() {
			return n
		},
		t = function() {
			return o
		},
		r = function(e) {
			return e(n)
		},
		o = {
				fold: function(e, t) {
					return t(n)
				},
				is: function(e) {
					return n === e
				},
				isSome: s,
				isNone: f,
				getOr: e,
				getOrThunk: e,
				getOrDie: e,
				getOrNull: e,
				getOrUndefined: e,
				or: t,
				orThunk: t,
				map: function(e) {
					return h(e(n))
				},
				ap: function(e) {
					return e.fold(d, function(e) {
						return h(e(n))
					})
				},
				each: function(e) {
					e(n)
				},
				bind: r,
				flatten: e,
				exists: r,
				forall: r,
				filter: function(e) {
					return e(n) ? o : g
				},
				equals: function(e) {
					return e.is(n)
				},
				equals_: function(e, t) {
					return e.fold(f, function(e) {
						return t(n, e)
					})
				},
				toArray: function() {
					return [n]
				},
				toString: function() {
					return "some(" + n + ")"
				}
		};
		return o
	},
	R = {
			some: h,
			none: d,
			from: function(e) {
				return null === e || e === undefined ? g : h(e)
			}
	},
	p = function(t) {
		return function(e) {
			return function(e) {
				if (null === e) return "null";
				var t = typeof e;
				return "object" === t && Array.prototype.isPrototypeOf(e) ? "array" : "object" === t && String.prototype.isPrototypeOf(e) ? "string" : t
			}(e) === t
		}
	},
	v = p("string"),
	w = p("array"),
	S = p("boolean"),
	T = p("function"),
	D = p("number"),
	O = (u = Array.prototype.indexOf) === undefined ? function(e, t) {
		return _(e, t)
	} : function(e, t) {
		return u.call(e, t)
	},
	k = function(e, t) {
		return -1 < O(e, t)
	},
	N = function(e, t) {
		return M(e, t).isSome()
	},
	E = function(e, t) {
		for (var n = e.length, r = new Array(n), o = 0; o < n; o++) {
			var i = e[o];
			r[o] = t(i, o, e)
		}
		return r
	},
	A = function(e, t) {
		for (var n = 0, r = e.length; n < r; n++) t(e[n], n, e)
	},
	P = function(e, t) {
		for (var n = [], r = 0, o = e.length; r < o; r++) {
			var i = e[r];
			t(i, r, e) && n.push(i)
		}
		return n
	},
	I = function(e, t, n) {
		return function(e, t) {
			for (var n = e.length - 1; 0 <= n; n--) t(e[n], n, e)
		}(e, function(e) {
			n = t(n, e)
		}), n
	},
	B = function(e, t, n) {
		return A(e, function(e) {
			n = t(n, e)
		}), n
	},
	W = function(e, t) {
		for (var n = 0, r = e.length; n < r; n++) {
			var o = e[n];
			if (t(o, n, e)) return R.some(o)
		}
		return R.none()
	},
	M = function(e, t) {
		for (var n = 0, r = e.length; n < r; n++)
			if (t(e[n], n, e)) return R.some(n);
		return R.none()
	},
	_ = function(e, t) {
		for (var n = 0, r = e.length; n < r; ++n)
			if (e[n] === t) return n;
		return -1
	},
	L = Array.prototype.push,
	j = function(e) {
		for (var t = [], n = 0, r = e.length; n < r; ++n) {
			if (!Array.prototype.isPrototypeOf(e[n])) throw new Error("Arr.flatten item " + n + " was not an array, input: " + e);
			L.apply(t, e[n])
		}
		return t
	},
	F = function(e, t) {
		var n = E(e, t);
		return j(n)
	},
	z = function(e, t) {
		for (var n = 0, r = e.length; n < r; ++n)
			if (!0 !== t(e[n], n, e)) return !1;
		return !0
	},
	H = Array.prototype.slice,
	U = function(e) {
		var t = H.call(e, 0);
		return t.reverse(), t
	},
	q = (T(Array.from) && Array.from, Object.keys),
	V = function(e, t) {
		for (var n = q(e), r = 0, o = n.length; r < o; r++) {
			var i = n[r];
			t(e[i], i, e)
		}
	},
	G = function(e, r) {
		return Y(e, function(e, t, n) {
			return {
				k: t,
				v: r(e, t, n)
			}
		})
	},
	Y = function(r, o) {
		var i = {};
		return V(r, function(e, t) {
			var n = o(e, t, r);
			i[n.k] = n.v
		}), i
	},
	X = function() {
		for (var t = [], e = 0; e < arguments.length; e++) t[e] = arguments[e];
		return function() {
			for (var n = [], e = 0; e < arguments.length; e++) n[e] = arguments[e];
			if (t.length !== n.length) throw new Error('Wrong number of arguments to struct. Expected "[' + t.length + ']", got ' + n.length + " arguments");
			var r = {};
			return A(t, function(e, t) {
				r[e] = C(n[t])
			}), r
		}
	},
	K = function(e) {
		return e.slice(0).sort()
	},
	J = function(e, t) {
		throw new Error("All required keys (" + K(e).join(", ") + ") were not specified. Specified keys were: " + K(t).join(", ") + ".")
	},
	$ = function(e) {
		throw new Error("Unsupported keys for object: " + K(e).join(", "))
	},
	Q = function(t, e) {
		if (!w(e)) throw new Error("The " + t + " fields must be an array. Was: " + e + ".");
		A(e, function(e) {
			if (!v(e)) throw new Error("The value " + e + " in the " + t + " fields was not a string.")
		})
	},
	Z = function(e) {
		var n = K(e);
		W(n, function(e, t) {
			return t < n.length - 1 && e === n[t + 1]
		}).each(function(e) {
			throw new Error("The field: " + e + " occurs more than once in the combined fields: [" + n.join(", ") + "].")
		})
	},
	ee = function(o, i) {
		var u = o.concat(i);
		if (0 === u.length) throw new Error("You must specify at least one required or optional field.");
		return Q("required", o), Q("optional", i), Z(u),
		function(t) {
			var n = q(t);
			z(o, function(e) {
				return k(n, e)
			}) || J(o, n);
			var e = P(n, function(e) {
				return !k(u, e)
			});
			0 < e.length && $(e);
			var r = {};
			return A(o, function(e) {
				r[e] = C(t[e])
			}), A(i, function(e) {
				r[e] = C(Object.prototype.hasOwnProperty.call(t, e) ? R.some(t[e]) : R.none())
			}), r
		}
	},
	te = X("width", "height"),
	ne = X("rows", "columns"),
	re = X("row", "column"),
	oe = X("x", "y"),
	ie = X("element", "rowspan", "colspan"),
	ue = X("element", "rowspan", "colspan", "isNew"),
	ae = {
		dimensions: te,
		grid: ne,
		address: re,
		coords: oe,
		extended: X("element", "rowspan", "colspan", "row", "column"),
		detail: ie,
		detailnew: ue,
		rowdata: X("element", "cells", "section"),
		elementnew: X("element", "isNew"),
		rowdatanew: X("element", "cells", "section", "isNew"),
		rowcells: X("cells", "section"),
		rowdetails: X("details", "section"),
		bounds: X("startRow", "startCol", "finishRow", "finishCol")
	},
	ce = function(e) {
		if (null === e || e === undefined) throw new Error("Node cannot be null or undefined");
		return {
			dom: C(e)
		}
	},
	le = {
			fromHtml: function(e, t) {
				var n = (t || document).createElement("div");
				if (n.innerHTML = e, !n.hasChildNodes() || 1 < n.childNodes.length) throw console.error("HTML does not have a single root node", e), "HTML must have a single root node";
				return ce(n.childNodes[0])
			},
			fromTag: function(e, t) {
				var n = (t || document).createElement(e);
				return ce(n)
			},
			fromText: function(e, t) {
				var n = (t || document).createTextNode(e);
				return ce(n)
			},
			fromDom: ce,
			fromPoint: function(e, t, n) {
				var r = e.dom();
				return R.from(r.elementFromPoint(t, n)).map(ce)
			}
	},
	fe = (Node.ATTRIBUTE_NODE, Node.CDATA_SECTION_NODE, Node.COMMENT_NODE),
	se = Node.DOCUMENT_NODE,
	de = (Node.DOCUMENT_TYPE_NODE, Node.DOCUMENT_FRAGMENT_NODE, Node.ELEMENT_NODE),
	me = Node.TEXT_NODE,
	ge = (Node.PROCESSING_INSTRUCTION_NODE, Node.ENTITY_REFERENCE_NODE, Node.ENTITY_NODE, Node.NOTATION_NODE, de),
	he = se,
	pe = function(e, t) {
		var n = e.dom();
		if (n.nodeType !== ge) return !1;
		if (n.matches !== undefined) return n.matches(t);
		if (n.msMatchesSelector !== undefined) return n.msMatchesSelector(t);
		if (n.webkitMatchesSelector !== undefined) return n.webkitMatchesSelector(t);
		if (n.mozMatchesSelector !== undefined) return n.mozMatchesSelector(t);
		throw new Error("Browser lacks native selectors")
	},
	ve = function(e) {
		return e.nodeType !== ge && e.nodeType !== he || 0 === e.childElementCount
	},
	be = "undefined" != typeof window ? window : Function("return this;")(),
			we = function(e, t) {
		return function(e, t) {
			for (var n = t !== undefined && null !== t ? t : be, r = 0; r < e.length && n !== undefined && null !== n; ++r) n = n[e[r]];
			return n
		}(e.split("."), t)
	},
	ye = function(e, t) {
		var n = we(e, t);
		if (n === undefined || null === n) throw e + " not available on this browser";
		return n
	},
	xe = function() {
		return ye("Node")
	},
	Ce = function(e, t, n) {
		return 0 != (e.compareDocumentPosition(t) & n)
	},
	Re = function(e, t) {
		return Ce(e, t, xe().DOCUMENT_POSITION_CONTAINED_BY)
	},
	Se = function(n) {
		var r, o = !1;
		return function() {
			for (var e = [], t = 0; t < arguments.length; t++) e[t] = arguments[t];
			return o || (o = !0, r = n.apply(null, e)), r
		}
	},
	Te = function(e, t) {
		var n = function(e, t) {
			for (var n = 0; n < e.length; n++) {
				var r = e[n];
				if (r.test(t)) return r
			}
			return undefined
		}(e, t);
		if (!n) return {
			major: 0,
			minor: 0
		};
		var r = function(e) {
			return Number(t.replace(n, "$" + e))
		};
		return Oe(r(1), r(2))
	},
	De = function() {
		return Oe(0, 0)
	},
	Oe = function(e, t) {
		return {
			major: e,
			minor: t
		}
	},
	ke = {
			nu: Oe,
			detect: function(e, t) {
				var n = String(t).toLowerCase();
				return 0 === e.length ? De() : Te(e, n)
			},
			unknown: De
	},
	Ne = "Firefox",
	Ee = function(e, t) {
		return function() {
			return t === e
		}
	},
	Ae = function(e) {
		var t = e.current;
		return {
			current: t,
			version: e.version,
			isEdge: Ee("Edge", t),
			isChrome: Ee("Chrome", t),
			isIE: Ee("IE", t),
			isOpera: Ee("Opera", t),
			isFirefox: Ee(Ne, t),
			isSafari: Ee("Safari", t)
		}
	},
	Pe = {
			unknown: function() {
				return Ae({
					current: undefined,
					version: ke.unknown()
				})
			},
			nu: Ae,
			edge: C("Edge"),
			chrome: C("Chrome"),
			ie: C("IE"),
			opera: C("Opera"),
			firefox: C(Ne),
			safari: C("Safari")
	},
	Ie = "Windows",
	Be = "Android",
	We = "Solaris",
	Me = "FreeBSD",
	_e = function(e, t) {
		return function() {
			return t === e
		}
	},
	Le = function(e) {
		var t = e.current;
		return {
			current: t,
			version: e.version,
			isWindows: _e(Ie, t),
			isiOS: _e("iOS", t),
			isAndroid: _e(Be, t),
			isOSX: _e("OSX", t),
			isLinux: _e("Linux", t),
			isSolaris: _e(We, t),
			isFreeBSD: _e(Me, t)
		}
	},
	je = {
			unknown: function() {
				return Le({
					current: undefined,
					version: ke.unknown()
				})
			},
			nu: Le,
			windows: C(Ie),
			ios: C("iOS"),
			android: C(Be),
			linux: C("Linux"),
			osx: C("OSX"),
			solaris: C(We),
			freebsd: C(Me)
	},
	Fe = function(e, t) {
		var n = String(t).toLowerCase();
		return W(e, function(e) {
			return e.search(n)
		})
	},
	ze = function(e, n) {
		return Fe(e, n).map(function(e) {
			var t = ke.detect(e.versionRegexes, n);
			return {
				current: e.name,
				version: t
			}
		})
	},
	He = function(e, n) {
		return Fe(e, n).map(function(e) {
			var t = ke.detect(e.versionRegexes, n);
			return {
				current: e.name,
				version: t
			}
		})
	},
	Ue = function(e, t) {
		return -1 !== e.indexOf(t)
	},
	qe = /.*?version\/\ ?([0-9]+)\.([0-9]+).*/,
			Ve = function(t) {
		return function(e) {
			return Ue(e, t)
		}
	},
	Ge = [{
		name: "Edge",
		versionRegexes: [/.*?edge\/ ?([0-9]+)\.([0-9]+)$/],
		search: function(e) {
			return Ue(e, "edge/") && Ue(e, "chrome") && Ue(e, "safari") && Ue(e, "applewebkit")
		}
	}, {
		name: "Chrome",
		versionRegexes: [/.*?chrome\/([0-9]+)\.([0-9]+).*/, qe],
		search: function(e) {
			return Ue(e, "chrome") && !Ue(e, "chromeframe")
		}
	}, {
		name: "IE",
		versionRegexes: [/.*?msie\ ?([0-9]+)\.([0-9]+).*/, /.*?rv:([0-9]+)\.([0-9]+).*/],
		search: function(e) {
			return Ue(e, "msie") || Ue(e, "trident")
		}
	}, {
		name: "Opera",
		versionRegexes: [qe, /.*?opera\/([0-9]+)\.([0-9]+).*/],
		search: Ve("opera")
	}, {
		name: "Firefox",
		versionRegexes: [/.*?firefox\/\ ?([0-9]+)\.([0-9]+).*/],
		search: Ve("firefox")
	}, {
		name: "Safari",
		versionRegexes: [qe, /.*?cpu os ([0-9]+)_([0-9]+).*/],
		search: function(e) {
			return (Ue(e, "safari") || Ue(e, "mobile/")) && Ue(e, "applewebkit")
		}
	}],
	Ye = [{
		name: "Windows",
		search: Ve("win"),
		versionRegexes: [/.*?windows\ nt\ ?([0-9]+)\.([0-9]+).*/]
	}, {
		name: "iOS",
		search: function(e) {
			return Ue(e, "iphone") || Ue(e, "ipad")
		},
		versionRegexes: [/.*?version\/\ ?([0-9]+)\.([0-9]+).*/, /.*cpu os ([0-9]+)_([0-9]+).*/, /.*cpu iphone os ([0-9]+)_([0-9]+).*/]
	}, {
		name: "Android",
		search: Ve("android"),
		versionRegexes: [/.*?android\ ?([0-9]+)\.([0-9]+).*/]
	}, {
		name: "OSX",
		search: Ve("os x"),
		versionRegexes: [/.*?os\ x\ ?([0-9]+)_([0-9]+).*/]
	}, {
		name: "Linux",
		search: Ve("linux"),
		versionRegexes: []
	}, {
		name: "Solaris",
		search: Ve("sunos"),
		versionRegexes: []
	}, {
		name: "FreeBSD",
		search: Ve("freebsd"),
		versionRegexes: []
	}],
	Xe = {
			browsers: C(Ge),
			oses: C(Ye)
	},
	Ke = function(e) {
		var t, n, r, o, i, u, a, c, l, f, s, d = Xe.browsers(),
		m = Xe.oses(),
		g = ze(d, e).fold(Pe.unknown, Pe.nu),
		h = He(m, e).fold(je.unknown, je.nu);
		return {
			browser: g,
			os: h,
			deviceType: (n = g, r = e, o = (t = h).isiOS() && !0 === /ipad/i.test(r), i = t.isiOS() && !o, u = t.isAndroid() && 3 === t.version.major, a = t.isAndroid() && 4 === t.version.major, c = o || u || a && !0 === /mobile/i.test(r), l = t.isiOS() || t.isAndroid(), f = l && !c, s = n.isSafari() && t.isiOS() && !1 === /safari/i.test(r), {
				isiPad: C(o),
				isiPhone: C(i),
				isTablet: C(c),
				isPhone: C(f),
				isTouch: C(l),
				isAndroid: t.isAndroid,
				isiOS: t.isiOS,
				isWebView: C(s)
			})
		}
	},
	Je = {
			detect: Se(function() {
				var e = navigator.userAgent;
				return Ke(e)
			})
	},
	$e = function(e, t) {
		return e.dom() === t.dom()
	},
	Qe = Je.detect().browser.isIE() ? function(e, t) {
		return Re(e.dom(), t.dom())
	} : function(e, t) {
		var n = e.dom(),
		r = t.dom();
		return n !== r && n.contains(r)
	},
	Ze = pe,
	et = function(e) {
		return le.fromDom(e.dom().ownerDocument)
	},
	tt = function(e) {
		var t = e.dom();
		return R.from(t.parentNode).map(le.fromDom)
	},
	nt = function(e, t) {
		for (var n = T(t) ? t : C(!1), r = e.dom(), o = []; null !== r.parentNode && r.parentNode !== undefined;) {
			var i = r.parentNode,
			u = le.fromDom(i);
			if (o.push(u), !0 === n(u)) break;
			r = i
		}
		return o
	},
	rt = function(e) {
		var t = e.dom();
		return R.from(t.previousSibling).map(le.fromDom)
	},
	ot = function(e) {
		var t = e.dom();
		return R.from(t.nextSibling).map(le.fromDom)
	},
	it = function(e) {
		var t = e.dom();
		return E(t.childNodes, le.fromDom)
	},
	ut = function(e, t) {
		var n = e.dom().childNodes;
		return R.from(n[t]).map(le.fromDom)
	},
	at = (X("element", "offset"), function(e, t, n) {
		return F(it(e), function(e) {
			return pe(e, t) ? n(e) ? [e] : [] : at(e, t, n)
		})
	}),
	ct = {
		firstLayer: function(e, t) {
			return at(e, t, C(!0))
		},
		filterFirstLayer: at
	},
	lt = function(e) {
		return e.dom().nodeName.toLowerCase()
	},
	ft = function(e) {
		return e.dom().nodeType
	},
	st = function(t) {
		return function(e) {
			return ft(e) === t
		}
	},
	dt = function(e) {
		return ft(e) === fe || "#comment" === lt(e)
	},
	mt = st(de),
	gt = st(me),
	ht = st(se),
	pt = function(e, t, n) {
		if (!(v(n) || S(n) || D(n))) throw console.error("Invalid call to Attr.set. Key ", t, ":: Value ", n, ":: Element ", e), new Error("Attribute value was not simple");
		e.setAttribute(t, n + "")
	},
	vt = function(e, t, n) {
		pt(e.dom(), t, n)
	},
	bt = function(e, t) {
		var n = e.dom();
		V(t, function(e, t) {
			pt(n, t, e)
		})
	},
	wt = function(e, t) {
		var n = e.dom().getAttribute(t);
		return null === n ? undefined : n
	},
	yt = function(e, t) {
		var n = e.dom();
		return !(!n || !n.hasAttribute) && n.hasAttribute(t)
	},
	xt = function(e, t) {
		e.dom().removeAttribute(t)
	},
	Ct = function(e) {
		return B(e.dom().attributes, function(e, t) {
			return e[t.name] = t.value, e
		}, {})
	},
	Rt = function(e) {
		var t = gt(e) ? e.dom().parentNode : e.dom();
		return t !== undefined && null !== t && t.ownerDocument.body.contains(t)
	},
	St = Se(function() {
		return Tt(le.fromDom(document))
	}),
	Tt = function(e) {
		var t = e.dom().body;
		if (null === t || t === undefined) throw "Body is not available yet";
		return le.fromDom(t)
	},
	Dt = function(e, t) {
		var n = [];
		return A(it(e), function(e) {
			t(e) && (n = n.concat([e])), n = n.concat(Dt(e, t))
		}), n
	},
	Ot = function(e, t, n) {
		return r = function(e) {
			return pe(e, t)
		}, P(nt(e, n), r);
		var r
	},
	kt = function(e, t) {
		return n = function(e) {
			return pe(e, t)
		}, P(it(e), n);
		var n
	},
	Nt = function(e, t) {
		return n = t, o = (r = e) === undefined ? document : r.dom(), ve(o) ? [] : E(o.querySelectorAll(n), le.fromDom);
		var n, r, o
	};

	function Et(e, t, n, r, o) {
		return e(n, r) ? R.some(n) : T(o) && o(n) ? R.none() : t(n, r, o)
	}
	var At, Pt, It, Bt, Wt, Mt = function(e, t, n) {
		for (var r = e.dom(), o = T(n) ? n : C(!1); r.parentNode;) {
			r = r.parentNode;
			var i = le.fromDom(r);
			if (t(i)) return R.some(i);
			if (o(i)) break
		}
		return R.none()
	},
	_t = function(e, t, n) {
		return Mt(e, function(e) {
			return pe(e, t)
		}, n)
	},
	Lt = function(e, t) {
		return n = function(e) {
			return pe(e, t)
		}, W(e.dom().childNodes, x(n, le.fromDom)).map(le.fromDom);
		var n
	},
	jt = function(e, t) {
		return n = t, o = (r = e) === undefined ? document : r.dom(), ve(o) ? R.none() : R.from(o.querySelector(n)).map(le.fromDom);
		var n, r, o
	},
	Ft = function(e, t, n) {
		return Et(pe, _t, e, t, n)
	},
	zt = function(e, t, n) {
		var r = n !== undefined ? n : C(!1);
		return r(t) ? R.none() : k(e, lt(t)) ? R.some(t) : _t(t, e.join(","), function(e) {
			return pe(e, "table") || r(e)
		})
	},
	Ht = function(t, e) {
		return tt(e).map(function(e) {
			return kt(e, t)
		})
	},
	Ut = b(Ht, "th,td"),
	qt = b(Ht, "tr"),
	Vt = function(e, t) {
		return parseInt(wt(e, t), 10)
	},
	Gt = {
			cell: function(e, t) {
				return zt(["td", "th"], e, t)
			},
			firstCell: function(e) {
				return jt(e, "th,td")
			},
			cells: function(e) {
				return ct.firstLayer(e, "th,td")
			},
			neighbourCells: Ut,
			table: function(e, t) {
				return Ft(e, "table", t)
			},
			row: function(e, t) {
				return zt(["tr"], e, t)
			},
			rows: function(e) {
				return ct.firstLayer(e, "tr")
			},
			notCell: function(e, t) {
				return zt(["caption", "tr", "tbody", "tfoot", "thead"], e, t)
			},
			neighbourRows: qt,
			attr: Vt,
			grid: function(e, t, n) {
				var r = Vt(e, t),
				o = Vt(e, n);
				return ae.grid(r, o)
			}
	},
	Yt = function(e) {
		var t = Gt.rows(e);
		return E(t, function(e) {
			var t = e,
			n = tt(t).map(function(e) {
				var t = lt(e);
				return "tfoot" === t || "thead" === t || "tbody" === t ? t : "tbody"
			}).getOr("tbody"),
			r = E(Gt.cells(e), function(e) {
				var t = yt(e, "rowspan") ? parseInt(wt(e, "rowspan"), 10) : 1,
						n = yt(e, "colspan") ? parseInt(wt(e, "colspan"), 10) : 1;
						return ae.detail(e, t, n)
			});
			return ae.rowdata(t, r, n)
		})
	},
	Xt = function(e, n) {
		return E(e, function(e) {
			var t = E(Gt.cells(e), function(e) {
				var t = yt(e, "rowspan") ? parseInt(wt(e, "rowspan"), 10) : 1,
						n = yt(e, "colspan") ? parseInt(wt(e, "colspan"), 10) : 1;
						return ae.detail(e, t, n)
			});
			return ae.rowdata(e, t, n.section())
		})
	},
	Kt = function(e, t) {
		return e + "," + t
	},
	Jt = function(e, t) {
		var n = F(e.all(), function(e) {
			return e.cells()
		});
		return P(n, t)
	},
	$t = {
			generate: function(e) {
				var f = {},
				t = [],
				n = e.length,
				s = 0;
				A(e, function(e, c) {
					var l = [];
					A(e.cells(), function(e, t) {
						for (var n = 0; f[Kt(c, n)] !== undefined;) n++;
						for (var r = ae.extended(e.element(), e.rowspan(), e.colspan(), c, n), o = 0; o < e.colspan(); o++)
							for (var i = 0; i < e.rowspan(); i++) {
								var u = n + o,
								a = Kt(c + i, u);
								f[a] = r, s = Math.max(s, u + 1)
							}
						l.push(r)
					}), t.push(ae.rowdata(e.element(), l, e.section()))
				});
				var r = ae.grid(n, s);
				return {
					grid: C(r),
					access: C(f),
					all: C(t)
				}
			},
			getAt: function(e, t, n) {
				var r = e.access()[Kt(t, n)];
				return r !== undefined ? R.some(r) : R.none()
			},
			findItem: function(e, t, n) {
				var r = Jt(e, function(e) {
					return n(t, e.element())
				});
				return 0 < r.length ? R.some(r[0]) : R.none()
			},
			filterItems: Jt,
			justCells: function(e) {
				var t = E(e.all(), function(e) {
					return e.cells()
				});
				return j(t)
			}
	},
	Qt = function(e) {
		return e.style !== undefined
	},
	Zt = function(e, t, n) {
		if (!v(n)) throw console.error("Invalid call to CSS.set. Property ", t, ":: Value ", n, ":: Element ", e), new Error("CSS value must be a string: " + n);
		Qt(e) && e.style.setProperty(t, n)
	},
	en = function(e, t, n) {
		var r = e.dom();
		Zt(r, t, n)
	},
	tn = function(e, t) {
		var n = e.dom();
		V(t, function(e, t) {
			Zt(n, t, e)
		})
	},
	nn = function(e, t) {
		var n = e.dom(),
		r = window.getComputedStyle(n).getPropertyValue(t),
		o = "" !== r || Rt(e) ? r : rn(n, t);
		return null === o ? undefined : o
	},
	rn = function(e, t) {
		return Qt(e) ? e.style.getPropertyValue(t) : ""
	},
	on = function(e, t) {
		var n = e.dom(),
		r = rn(n, t);
		return R.from(r).filter(function(e) {
			return 0 < e.length
		})
	},
	un = function(e, t) {
		var n, r, o = e.dom();
		r = t, Qt(n = o) && n.style.removeProperty(r), yt(e, "style") && "" === wt(e, "style").replace(/^\s+|\s+$/g, "") && xt(e, "style")
	},
	an = function(t, n) {
		tt(t).each(function(e) {
			e.dom().insertBefore(n.dom(), t.dom())
		})
	},
	cn = function(e, t) {
		ot(e).fold(function() {
			tt(e).each(function(e) {
				fn(e, t)
			})
		}, function(e) {
			an(e, t)
		})
	},
	ln = function(t, n) {
		ut(t, 0).fold(function() {
			fn(t, n)
		}, function(e) {
			t.dom().insertBefore(n.dom(), e.dom())
		})
	},
	fn = function(e, t) {
		e.dom().appendChild(t.dom())
	},
	sn = function(e, t) {
		an(e, t), fn(t, e)
	},
	dn = function(r, o) {
		A(o, function(e, t) {
			var n = 0 === t ? r : o[t - 1];
			cn(n, e)
		})
	},
	mn = function(t, e) {
		A(e, function(e) {
			fn(t, e)
		})
	},
	gn = function(e) {
		e.dom().textContent = "", A(it(e), function(e) {
			hn(e)
		})
	},
	hn = function(e) {
		var t = e.dom();
		null !== t.parentNode && t.parentNode.removeChild(t)
	},
	pn = function(e) {
		var t, n = it(e);
		0 < n.length && (t = e, A(n, function(e) {
			an(t, e)
		})), hn(e)
	},
	vn = X("minRow", "minCol", "maxRow", "maxCol"),
	bn = function(e, t) {
		var n, i, r, u, a, c, l, o, f, s, d = function(e) {
			return pe(e.element(), t)
		},
		m = Yt(e),
		g = $t.generate(m),
		h = (i = d, r = (n = g).grid().columns(), u = n.grid().rows(), a = r, l = c = 0, V(n.access(), function(e) {
			if (i(e)) {
				var t = e.row(),
				n = t + e.rowspan() - 1,
				r = e.column(),
				o = r + e.colspan() - 1;
				t < u ? u = t : c < n && (c = n), r < a ? a = r : l < o && (l = o)
			}
		}), vn(u, a, c, l)),
		p = "th:not(" + t + "),td:not(" + t + ")",
		v = ct.filterFirstLayer(e, "th,td", function(e) {
			return pe(e, p)
		});
		return A(v, hn),
		function(e, t, n, r) {
			for (var o, i, u, a = t.grid().columns(), c = t.grid().rows(), l = 0; l < c; l++)
				for (var f = !1, s = 0; s < a; s++) l < n.minRow() || l > n.maxRow() || s < n.minCol() || s > n.maxCol() || ($t.getAt(t, l, s).filter(r).isNone() ? (o = f, i = e[l].element(), u = le.fromTag("td"), fn(u, le.fromTag("br")), (o ? fn : ln)(i, u)) : f = !0)
		}(m, g, h, d), o = e, f = h, s = P(ct.firstLayer(o, "tr"), function(e) {
			return 0 === e.dom().childElementCount
		}), A(s, hn), f.minCol() !== f.maxCol() && f.minRow() !== f.maxRow() || A(ct.firstLayer(o, "th,td"), function(e) {
			xt(e, "rowspan"), xt(e, "colspan")
		}), xt(o, "width"), xt(o, "height"), un(o, "width"), un(o, "height"), e
	},
	wn = function(e, t) {
		return le.fromDom(e.dom().cloneNode(t))
	},
	yn = function(e) {
		return wn(e, !1)
	},
	xn = function(e) {
		return wn(e, !0)
	},
	Cn = function(e, t) {
		var n, r, o, i, u = (n = e, r = t, o = le.fromTag(r), i = Ct(n), bt(o, i), o),
		a = it(xn(e));
		return mn(u, a), u
	},
	Rn = (At = gt, Pt = "text", It = function(e) {
		return At(e) ? R.from(e.dom().nodeValue) : R.none()
	}, Bt = Je.detect().browser, {
		get: function(e) {
			if (!At(e)) throw new Error("Can only get " + Pt + " value of a " + Pt + " node");
			return Wt(e).getOr("")
		},
		getOption: Wt = Bt.isIE() && 10 === Bt.version.major ? function(e) {
			try {
				return It(e)
			} catch (t) {
				return R.none()
			}
		} : It,
		set: function(e, t) {
			if (!At(e)) throw new Error("Can only set raw " + Pt + " value of a " + Pt + " node");
			e.dom().nodeValue = t
		}
	}),
	Sn = function(e) {
		return Rn.get(e)
	},
	Tn = function(e) {
		return Rn.getOption(e)
	},
	Dn = function(e, t) {
		Rn.set(e, t)
	},
	On = function(e) {
		return "img" === lt(e) ? 1 : Tn(e).fold(function() {
			return it(e).length
		}, function(e) {
			return e.length
		})
	},
	kn = ["img", "br"],
	Nn = function(e) {
		return Tn(e).filter(function(e) {
			return 0 !== e.trim().length || -1 < e.indexOf("\xa0")
		}).isSome() || k(kn, lt(e))
	},
	En = function(e) {
		return r = Nn, (o = function(e) {
			for (var t = 0; t < e.childNodes.length; t++) {
				if (r(le.fromDom(e.childNodes[t]))) return R.some(le.fromDom(e.childNodes[t]));
				var n = o(e.childNodes[t]);
				if (n.isSome()) return n
			}
			return R.none()
		})(e.dom());
		var r, o
	},
	An = function(e) {
		return Pn(e, Nn)
	},
	Pn = function(e, i) {
		var u = function(e) {
			for (var t = it(e), n = t.length - 1; 0 <= n; n--) {
				var r = t[n];
				if (i(r)) return R.some(r);
				var o = u(r);
				if (o.isSome()) return o
			}
			return R.none()
		};
		return u(e)
	},
	In = function() {
		var e = le.fromTag("td");
		return fn(e, le.fromTag("br")), e
	},
	Bn = function(e, t, n) {
		var r = Cn(e, t);
		return V(n, function(e, t) {
			null === e ? xt(r, t) : vt(r, t, e)
		}), r
	},
	Wn = function(e) {
		return e
	},
	Mn = function(e) {
		return function() {
			return le.fromTag("tr", e.dom())
		}
	},
	_n = function(d, e, m) {
		return {
			row: Mn(e),
			cell: function(e) {
				var r, o, i, t, n, u, a, c = et(e.element()),
				l = le.fromTag(lt(e.element()), c.dom()),
				f = m.getOr(["strong", "em", "b", "i", "span", "font", "h1", "h2", "h3", "h4", "h5", "h6", "p", "div"]),
				s = 0 < f.length ? (r = e.element(), o = l, i = f, En(r).map(function(e) {
					var t = i.join(","),
					n = Ot(e, t, function(e) {
						return $e(e, r)
					});
					return I(n, function(e, t) {
						var n = yn(t);
						return xt(n, "contenteditable"), fn(e, n), n
					}, o)
				}).getOr(o)) : l;
				return fn(s, le.fromTag("br")), t = e.element(), n = l, u = t.dom(), a = n.dom(), Qt(u) && Qt(a) && (a.style.cssText = u.style.cssText), un(l, "height"), 1 !== e.colspan() && un(e.element(), "width"), d(e.element(), l), l
			},
			replace: Bn,
			gap: In
		}
	},
	Ln = function(e) {
		return {
			row: Mn(e),
			cell: In,
			replace: Wn,
			gap: In
		}
	},
	jn = ["body", "p", "div", "article", "aside", "figcaption", "figure", "footer", "header", "nav", "section", "ol", "ul", "li", "table", "thead", "tbody", "tfoot", "caption", "tr", "td", "th", "h1", "h2", "h3", "h4", "h5", "h6", "blockquote", "pre", "address"];

	function Fn() {
		return {
			up: C({
				selector: _t,
				closest: Ft,
				predicate: Mt,
				all: nt
			}),
			down: C({
				selector: Nt,
				predicate: Dt
			}),
			styles: C({
				get: nn,
				getRaw: on,
				set: en,
				remove: un
			}),
			attrs: C({
				get: wt,
				set: vt,
				remove: xt,
				copyTo: function(e, t) {
					var n = Ct(e);
					bt(t, n)
				}
			}),
			insert: C({
				before: an,
				after: cn,
				afterAll: dn,
				append: fn,
				appendAll: mn,
				prepend: ln,
				wrap: sn
			}),
			remove: C({
				unwrap: pn,
				remove: hn
			}),
			create: C({
				nu: le.fromTag,
				clone: function(e) {
					return le.fromDom(e.dom().cloneNode(!1))
				},
				text: le.fromText
			}),
			query: C({
				comparePosition: function(e, t) {
					return e.dom().compareDocumentPosition(t.dom())
				},
				prevSibling: rt,
				nextSibling: ot
			}),
			property: C({
				children: it,
				name: lt,
				parent: tt,
				isText: gt,
				isComment: dt,
				isElement: mt,
				getText: Sn,
				setText: Dn,
				isBoundary: function(e) {
					return !!mt(e) && ("body" === lt(e) || k(jn, lt(e)))
				},
				isEmptyTag: function(e) {
					return !!mt(e) && k(["br", "img", "hr", "input"], lt(e))
				}
			}),
			eq: $e,
			is: Ze
		}
	}
	var zn = X("left", "right"),
	Hn = function(e, t, n) {
		var r = e.property().children(t);
		return M(r, b(e.eq, n)).map(function(e) {
			return {
				before: C(r.slice(0, e)),
				after: C(r.slice(e + 1))
			}
		})
	},
	Un = function(n, r, o) {
		return Hn(n, r, o).map(function(e) {
			var t = n.create().clone(r);
			return n.insert().appendAll(t, e.before().concat([o])), n.insert().appendAll(r, e.after()), n.insert().before(r, t), zn(t, r)
		})
	},
	qn = function(n, r, e) {
		return Hn(n, r, e).map(function(e) {
			var t = n.create().clone(r);
			return n.insert().appendAll(t, e.after()), n.insert().after(r, t), zn(r, t)
		})
	},
	Vn = function(i, e, u, a) {
		var r = X("first", "second", "splits"),
		c = function(e, t, o) {
			var n = r(e, R.none(), o);
			return u(e) ? r(e, t, o) : i.property().parent(e).bind(function(r) {
				return a(i, r, e).map(function(e) {
					var t = [{
						first: e.left,
						second: e.right
					}],
					n = u(r) ? r : e.left();
					return c(n, R.some(e.right()), o.concat(t))
				}).getOr(n)
			})
		};
		return c(e, R.none(), [])
	},
	Gn = function(r, o, e, t) {
		var n = o(r, e);
		return I(t, function(e, t) {
			var n = o(r, t);
			return Yn(r, e, n)
		}, n)
	},
	Yn = function(t, e, n) {
		return e.bind(function(e) {
			return n.filter(b(t.eq, e))
		})
	},
	Xn = function(e, t, n) {
		return 0 < n.length ? Gn(e, t, (r = n)[0], r.slice(1)) : R.none();
		var r
	},
	Kn = function(e, t) {
		return b(e.eq, t)
	},
	Jn = function(t, e, n, r) {
		var o = r !== undefined ? r : C(!1),
				i = [e].concat(t.up().all(e)),
				u = [n].concat(t.up().all(n)),
				a = function(t) {
			return M(t, o).fold(function() {
				return t
			}, function(e) {
				return t.slice(0, e + 1)
			})
		},
		c = a(i),
		l = a(u),
		f = W(c, function(e) {
			return N(l, Kn(t, e))
		});
		return {
			firstpath: C(c),
			secondpath: C(l),
			shared: C(f)
		}
	},
	$n = function(t, e, n) {
		var r = Jn(t, e, n);
		return r.shared().bind(function(e) {
			return function(o, i, e, t) {
				var u = o.property().children(i);
				if (o.eq(i, e[0])) return R.some([e[0]]);
				if (o.eq(i, t[0])) return R.some([t[0]]);
				var n = function(e) {
					var t = U(e),
					n = M(t, Kn(o, i)).getOr(-1),
					r = n < t.length - 1 ? t[n + 1] : t[n];
					return M(u, Kn(o, r))
				},
				r = n(e),
				a = n(t);
				return r.bind(function(r) {
					return a.map(function(e) {
						var t = Math.min(r, e),
						n = Math.max(r, e);
						return u.slice(t, n + 1)
					})
				})
			}(t, e, r.firstpath(), r.secondpath())
		})
	},
	Qn = Jn,
	Zn = function(e, t, n) {
		return Xn(e, t, n)
	},
	er = function(e, t, n) {
		return $n(e, t, n)
	},
	tr = function(e, t, n, r) {
		return Qn(e, t, n, r)
	},
	nr = function(e, t, n) {
		return Un(e, t, n)
	},
	rr = function(e, t, n) {
		return qn(e, t, n)
	},
	or = function(e, t, n, r) {
		return Vn(e, t, n, r)
	},
	ir = Fn(),
	ur = {
		sharedOne: function(n, e) {
			return Zn(ir, function(e, t) {
				return n(t)
			}, e)
		},
		subset: function(e, t) {
			return er(ir, e, t)
		},
		ancestors: function(e, t, n) {
			return tr(ir, e, t, n)
		},
		breakToLeft: function(e, t) {
			return nr(ir, e, t)
		},
		breakToRight: function(e, t) {
			return rr(ir, e, t)
		},
		breakPath: function(e, t, r) {
			return or(ir, e, t, function(e, t, n) {
				return r(t, n)
			})
		}
	},
	ar = function(e, t) {
		return t.column() >= e.startCol() && t.column() + t.colspan() - 1 <= e.finishCol() && t.row() >= e.startRow() && t.row() + t.rowspan() - 1 <= e.finishRow()
	},
	cr = function(e, t) {
		var n = t.column(),
		r = t.column() + t.colspan() - 1,
		o = t.row(),
		i = t.row() + t.rowspan() - 1;
		return n <= e.finishCol() && r >= e.startCol() && o <= e.finishRow() && i >= e.startRow()
	},
	lr = function(e, t) {
		for (var n = !0, r = b(ar, t), o = t.startRow(); o <= t.finishRow(); o++)
			for (var i = t.startCol(); i <= t.finishCol(); i++) n = n && $t.getAt(e, o, i).exists(r);
		return n ? R.some(t) : R.none()
	},
	fr = function(e, t, n) {
		var r = $t.findItem(e, t, $e),
		o = $t.findItem(e, n, $e);
		return r.bind(function(r) {
			return o.map(function(e) {
				return t = r, n = e, ae.bounds(Math.min(t.row(), n.row()), Math.min(t.column(), n.column()), Math.max(t.row() + t.rowspan() - 1, n.row() + n.rowspan() - 1), Math.max(t.column() + t.colspan() - 1, n.column() + n.colspan() - 1));
				var t, n
			})
		})
	},
	sr = fr,
	dr = function(t, e, n) {
		return fr(t, e, n).bind(function(e) {
			return lr(t, e)
		})
	},
	mr = function(r, e, o, i) {
		return $t.findItem(r, e, $e).bind(function(e) {
			var t = 0 < o ? e.row() + e.rowspan() - 1 : e.row(),
					n = 0 < i ? e.column() + e.colspan() - 1 : e.column();
					return $t.getAt(r, t + o, n + i).map(function(e) {
						return e.element()
					})
		})
	},
	gr = function(n, e, t) {
		return sr(n, e, t).map(function(e) {
			var t = $t.filterItems(n, b(cr, e));
			return E(t, function(e) {
				return e.element()
			})
		})
	},
	hr = function(e, t) {
		return $t.findItem(e, t, function(e, t) {
			return Qe(t, e)
		}).bind(function(e) {
			return e.element()
		})
	},
	pr = function(e) {
		var t = Yt(e);
		return $t.generate(t)
	},
	vr = function(n, r, o) {
		return Gt.table(n).bind(function(e) {
			var t = pr(e);
			return mr(t, n, r, o)
		})
	},
	br = function(e, t, n) {
		var r = pr(e);
		return gr(r, t, n)
	},
	wr = function(e, t, n, r, o) {
		var i = pr(e),
		u = $e(e, n) ? t : hr(i, t),
				a = $e(e, o) ? r : hr(i, r);
		return gr(i, u, a)
	},
	yr = function(e, t, n) {
		var r = pr(e);
		return dr(r, t, n)
	},
	xr = function(e, t) {
		return _t(e, "table")
	},
	Cr = ee(["boxes", "start", "finish"], []),
	Rr = function(a, c, r) {
		var l = function(t) {
			return function(e) {
				return r(e) || $e(e, t)
			}
		};
		return $e(a, c) ? R.some(Cr({
			boxes: R.some([a]),
			start: a,
			finish: c
		})) : xr(a).bind(function(u) {
			return xr(c).bind(function(i) {
				if ($e(u, i)) return R.some(Cr({
					boxes: br(u, a, c),
					start: a,
					finish: c
				}));
				if (Qe(u, i)) {
					var e = 0 < (t = Ot(c, "td,th", l(u))).length ? t[t.length - 1] : c;
					return R.some(Cr({
						boxes: wr(u, a, u, c, i),
						start: a,
						finish: e
					}))
				}
				if (Qe(i, u)) {
					var t, n = 0 < (t = Ot(a, "td,th", l(i))).length ? t[t.length - 1] : a;
					return R.some(Cr({
						boxes: wr(i, a, u, c, i),
						start: a,
						finish: n
					}))
				}
				return ur.ancestors(a, c).shared().bind(function(e) {
					return Ft(e, "table", r).bind(function(e) {
						var t = Ot(c, "td,th", l(e)),
						n = 0 < t.length ? t[t.length - 1] : c,
								r = Ot(a, "td,th", l(e)),
								o = 0 < r.length ? r[r.length - 1] : a;
								return R.some(Cr({
									boxes: wr(e, a, u, c, i),
									start: o,
									finish: n
								}))
					})
				})
			})
		})
	},
	Sr = Rr,
	Tr = function(e, t) {
		var n = Nt(e, t);
		return 0 < n.length ? R.some(n) : R.none()
	},
	Dr = function(e, t, n, r, o) {
		return (i = e, u = o, W(i, function(e) {
			return pe(e, u)
		})).bind(function(e) {
			return vr(e, t, n).bind(function(e) {
				return n = r, _t(t = e, "table").bind(function(e) {
					return jt(e, n).bind(function(e) {
						return Rr(e, t).bind(function(t) {
							return t.boxes().map(function(e) {
								return {
									boxes: C(e),
									start: C(t.start()),
									finish: C(t.finish())
								}
							})
						})
					})
				});
				var t, n
			})
		});
		var i, u
	},
	Or = function(e, t, r) {
		return jt(e, t).bind(function(n) {
			return jt(e, r).bind(function(t) {
				return ur.sharedOne(xr, [n, t]).map(function(e) {
					return {
						first: C(n),
						last: C(t),
						table: C(e)
					}
				})
			})
		})
	},
	kr = function(e, t) {
		return Tr(e, t)
	},
	Nr = function(o, e, t) {
		return Or(o, e, t).bind(function(n) {
			var e = function(e) {
				return $e(o, e)
			},
			t = _t(n.first(), "thead,tfoot,tbody,table", e),
			r = _t(n.last(), "thead,tfoot,tbody,table", e);
			return t.bind(function(t) {
				return r.bind(function(e) {
					return $e(t, e) ? yr(n.table(), n.first(), n.last()) : R.none()
				})
			})
		})
	},
	Er = "data-mce-selected",
	Ar = "data-mce-first-selected",
	Pr = "data-mce-last-selected",
	Ir = {
			selected: C(Er),
			selectedSelector: C("td[data-mce-selected],th[data-mce-selected]"),
			attributeSelector: C("[data-mce-selected]"),
			firstSelected: C(Ar),
			firstSelectedSelector: C("td[data-mce-first-selected],th[data-mce-first-selected]"),
			lastSelected: C(Pr),
			lastSelectedSelector: C("td[data-mce-last-selected],th[data-mce-last-selected]")
	},
	Br = function(u) {
		if (!w(u)) throw new Error("cases must be an array");
		if (0 === u.length) throw new Error("there must be at least one case");
		var a = [],
		n = {};
		return A(u, function(e, r) {
			var t = q(e);
			if (1 !== t.length) throw new Error("one and only one name per case");
			var o = t[0],
			i = e[o];
			if (n[o] !== undefined) throw new Error("duplicate key detected:" + o);
			if ("cata" === o) throw new Error("cannot have a case named cata (sorry)");
			if (!w(i)) throw new Error("case arguments must be an array");
			a.push(o), n[o] = function() {
				var e = arguments.length;
				if (e !== i.length) throw new Error("Wrong number of arguments to case " + o + ". Expected " + i.length + " (" + i + "), got " + e);
				for (var n = new Array(e), t = 0; t < n.length; t++) n[t] = arguments[t];
				return {
					fold: function() {
						if (arguments.length !== u.length) throw new Error("Wrong number of arguments to fold. Expected " + u.length + ", got " + arguments.length);
						return arguments[r].apply(null, n)
					},
					match: function(e) {
						var t = q(e);
						if (a.length !== t.length) throw new Error("Wrong number of arguments to match. Expected: " + a.join(",") + "\nActual: " + t.join(","));
						if (!z(a, function(e) {
							return k(t, e)
						})) throw new Error("Not all branches were specified when using match. Specified: " + t.join(", ") + "\nRequired: " + a.join(", "));
						return e[o].apply(null, n)
					},
					log: function(e) {
						console.log(e, {
							constructors: a,
							constructor: o,
							params: n
						})
					}
				}
			}
		}), n
	},
	Wr = Br([{
		none: []
	}, {
		multiple: ["elements"]
	}, {
		single: ["selection"]
	}]),
	Mr = {
		cata: function(e, t, n, r) {
			return e.fold(t, n, r)
		},
		none: Wr.none,
		multiple: Wr.multiple,
		single: Wr.single
	},
	_r = function(e, t) {
		return Mr.cata(t.get(), C([]), o, C([e]))
	},
	Lr = function(n, e) {
		return Mr.cata(e.get(), R.none, function(t, e) {
			return 0 === t.length ? R.none() : Nr(n, Ir.firstSelectedSelector(), Ir.lastSelectedSelector()).bind(function(e) {
				return 1 < t.length ? R.some({
					bounds: C(e),
					cells: C(t)
				}) : R.none()
			})
		}, R.none)
	},
	jr = function(e, t) {
		var n = _r(e, t);
		return 0 < n.length && z(n, function(e) {
			return yt(e, "rowspan") && 1 < parseInt(wt(e, "rowspan"), 10) || yt(e, "colspan") && 1 < parseInt(wt(e, "colspan"), 10)
		}) ? R.some(n) : R.none()
	},
	Fr = _r,
	zr = function(e) {
		return {
			element: C(e),
			mergable: R.none,
			unmergable: R.none,
			selection: C([e])
		}
	},
	Hr = X("element", "clipboard", "generators"),
	Ur = {
		noMenu: zr,
		forMenu: function(e, t, n) {
			return {
				element: C(n),
				mergable: C(Lr(t, e)),
				unmergable: C(jr(n, e)),
				selection: C(Fr(n, e))
			}
		},
		notCell: function(e) {
			return zr(e)
		},
		paste: Hr,
		pasteRows: function(e, t, n, r, o) {
			return {
				element: C(n),
				mergable: R.none,
				unmergable: R.none,
				selection: C(Fr(n, e)),
				clipboard: C(r),
				generators: C(o)
			}
		}
	},
	qr = function(f, e, s, d) {
		f.on("BeforeGetContent", function(n) {
			!0 === n.selection && Mr.cata(e.get(), y, function(e) {
				var t;
				n.preventDefault(), (t = e, Gt.table(t[0]).map(xn).map(function(e) {
					return [bn(e, Ir.attributeSelector())]
				})).each(function(e) {
					var t;
					n.content = "text" === n.format ? E(e, function(e) {
						return e.dom().innerText
					}).join("") : (t = f, E(e, function(e) {
						return t.selection.serializer.serialize(e.dom(), {})
					}).join(""))
				})
			}, y)
		}), f.on("BeforeSetContent", function(l) {
			!0 === l.selection && !0 === l.paste && R.from(f.dom.getParent(f.selection.getStart(), "th,td")).each(function(e) {
				var c = le.fromDom(e);
				Gt.table(c).bind(function(t) {
					var e, n, r, o = P((e = l.content, (r = (n || document).createElement("div")).innerHTML = e, it(le.fromDom(r))), function(e) {
						return "meta" !== lt(e)
					});
					if (1 === o.length && "table" === lt(o[0])) {
						l.preventDefault();
						var i = le.fromDom(f.getDoc()),
						u = Ln(i),
						a = Ur.paste(c, o[0], u);
						s.pasteCells(t, a).each(function(e) {
							f.selection.setRng(e), f.focus(), d.clear(t)
						})
					}
				})
			})
		})
	};

	function Vr(r, o) {
		var e = function(e) {
			var t = o(e);
			if (t <= 0 || null === t) {
				var n = nn(e, r);
				return parseFloat(n) || 0
			}
			return t
		},
		i = function(o, e) {
			return B(e, function(e, t) {
				var n = nn(o, t),
				r = n === undefined ? 0 : parseInt(n, 10);
				return isNaN(r) ? e : e + r
			}, 0)
		};
		return {
			set: function(e, t) {
				if (!D(t) && !t.match(/^[0-9]+$/)) throw r + ".set accepts only positive integer values. Value was " + t;
				var n = e.dom();
				Qt(n) && (n.style[r] = t + "px")
			},
			get: e,
			getOuter: e,
			aggregate: i,
			max: function(e, t, n) {
				var r = i(e, n);
				return r < t ? t - r : 0
			}
		}
	}
	var Gr = Vr("height", function(e) {
		var t = e.dom();
		return Rt(e) ? t.getBoundingClientRect().height : t.offsetHeight
	}),
	Yr = function(e) {
		return Gr.get(e)
	},
	Xr = function(e) {
		return Gr.getOuter(e)
	},
	Kr = Vr("width", function(e) {
		return e.dom().offsetWidth
	}),
	Jr = function(e) {
		return Kr.get(e)
	},
	$r = function(e) {
		return Kr.getOuter(e)
	},
	Qr = Je.detect(),
	Zr = function(e, t, n) {
		return r = nn(e, t), o = n, i = parseFloat(r), isNaN(i) ? o : i;
		var r, o, i
	},
	eo = function(e) {
		return Qr.browser.isIE() || Qr.browser.isEdge() ? (n = Zr(t = e, "padding-top", 0), r = Zr(t, "padding-bottom", 0), o = Zr(t, "border-top-width", 0), i = Zr(t, "border-bottom-width", 0), u = t.dom().getBoundingClientRect().height, "border-box" === nn(t, "box-sizing") ? u : u - n - r - (o + i)) : Zr(e, "height", Yr(e));
		var t, n, r, o, i, u
	},
	to = /(\d+(\.\d+)?)(\w|%)*/,
	no = /(\d+(\.\d+)?)%/,
	ro = /(\d+(\.\d+)?)px|em/,
	oo = function(e, t) {
		en(e, "height", t + "px")
	},
	io = function(e, t, n, r) {
		var o, i, u, a, c, l, f, s, d, m = parseInt(e, 10);
		return s = l = "%", d = (f = e).length - l.length, "" !== s && (f.length < s.length || f.substr(d, d + s.length) !== s) || "table" === lt(t) ? m : (o = t, i = m, u = n, a = r, c = Gt.table(o).map(function(e) {
			var t = u(e);
			return Math.floor(i / 100 * t)
		}).getOr(i), a(o, c), c)
	},
	uo = function(e) {
		var t, n = on(t = e, "height").getOrThunk(function() {
			return eo(t) + "px"
		});
		return n ? io(n, e, Yr, oo) : Yr(e)
	},
	ao = function(e, t) {
		return yt(e, t) ? parseInt(wt(e, t), 10) : 1
	},
	co = function(e) {
		return on(e, "width").fold(function() {
			return R.from(wt(e, "width"))
		}, function(e) {
			return R.some(e)
		})
	},
	lo = function(e, t) {
		return e / t.pixelWidth() * 100
	},
	fo = {
			percentageBasedSizeRegex: C(no),
			pixelBasedSizeRegex: C(ro),
			setPixelWidth: function(e, t) {
				en(e, "width", t + "px")
			},
			setPercentageWidth: function(e, t) {
				en(e, "width", t + "%")
			},
			setHeight: oo,
			getPixelWidth: function(t, n) {
				return co(t).fold(function() {
					return Jr(t)
				}, function(e) {
					return function(e, t, n) {
						if (ro.test(t)) {
							var r = ro.exec(t);
							return parseInt(r[1], 10)
						}
						if (no.test(t)) {
							var o = no.exec(t),
							i = parseFloat(o[1]);
							return i / 100 * n.pixelWidth()
						}
						return Jr(e)
					}(t, e, n)
				})
			},
			getPercentageWidth: function(t, n) {
				return co(t).fold(function() {
					var e = Jr(t);
					return lo(e, n)
				}, function(e) {
					return function(e, t, n) {
						if (no.test(t)) {
							var r = no.exec(t);
							return parseFloat(r[1])
						}
						var o = Jr(e);
						return lo(o, n)
					}(t, e, n)
				})
			},
			getGenericWidth: function(e) {
				return co(e).bind(function(e) {
					if (to.test(e)) {
						var t = to.exec(e);
						return R.some({
							width: C(t[1]),
							unit: C(t[3])
						})
					}
					return R.none()
				})
			},
			setGenericWidth: function(e, t, n) {
				en(e, "width", t + n)
			},
			getHeight: function(e) {
				return n = "rowspan", uo(t = e) / ao(t, n);
				var t, n
			},
			getRawWidth: co
	},
	so = function(n, r) {
		fo.getGenericWidth(n).each(function(e) {
			var t = e.width() / 2;
			fo.setGenericWidth(n, t, e.unit()), fo.setGenericWidth(r, t, e.unit())
		})
	},
	mo = function(n, r) {
		return {
			left: C(n),
			top: C(r),
			translate: function(e, t) {
				return mo(n + e, r + t)
			}
		}
	},
	go = mo,
	ho = function(e, t) {
		return e !== undefined ? e : t !== undefined ? t : 0
	},
	po = function(e) {
		var t, n, r = e.dom().ownerDocument,
		o = r.body,
		i = (t = le.fromDom(r), (n = t.dom()) === n.window && t instanceof Window ? t : ht(t) ? n.defaultView || n.parentWindow : null),
		u = r.documentElement,
		a = ho(i.pageYOffset, u.scrollTop),
		c = ho(i.pageXOffset, u.scrollLeft),
		l = ho(u.clientTop, o.clientTop),
		f = ho(u.clientLeft, o.clientLeft);
		return vo(e).translate(c - f, a - l)
	},
	vo = function(e) {
		var t, n, r, o = e.dom(),
		i = o.ownerDocument,
		u = i.body,
		a = le.fromDom(i.documentElement);
		return u === o ? go(u.offsetLeft, u.offsetTop) : (t = e, n = a || le.fromDom(document.documentElement), Mt(t, b($e, n)).isSome() ? (r = o.getBoundingClientRect(), go(r.left, r.top)) : go(0, 0))
	},
	bo = X("row", "y"),
	wo = X("col", "x"),
	yo = function(e) {
		return po(e).left() + $r(e)
	},
	xo = function(e) {
		return po(e).left()
	},
	Co = function(e, t) {
		return wo(e, xo(t))
	},
	Ro = function(e, t) {
		return wo(e, yo(t))
	},
	So = function(e) {
		return po(e).top()
	},
	To = function(n, t, r) {
		if (0 === r.length) return [];
		var e = E(r.slice(1), function(e, t) {
			return e.map(function(e) {
				return n(t, e)
			})
		}),
		o = r[r.length - 1].map(function(e) {
			return t(r.length - 1, e)
		});
		return e.concat([o])
	},
	Do = {
			delta: o,
			positions: b(To, function(e, t) {
				return bo(e, So(t))
			}, function(e, t) {
				return bo(e, So(t) + Xr(t))
			}),
			edge: So
	},
	Oo = {
			delta: o,
			edge: xo,
			positions: b(To, Co, Ro)
	},
	ko = {
			height: Do,
			rtl: {
				delta: function(e, t) {
					return -e
				},
				edge: yo,
				positions: b(To, Ro, Co)
			},
			ltr: Oo
	},
	No = {
			ltr: ko.ltr,
			rtl: ko.rtl
	};

	function Eo(t) {
		var n = function(e) {
			return t(e).isRtl() ? No.rtl : No.ltr
		};
		return {
			delta: function(e, t) {
				return n(t).delta(e, t)
			},
			edge: function(e) {
				return n(e).edge(e)
			},
			positions: function(e, t) {
				return n(t).positions(e, t)
			}
		}
	}
	var Ao, Po = function(e) {
		var t = Yt(e);
		return $t.generate(t).grid()
	},
	Io = function(e) {
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
				return Io(n())
			}
		}
	},
	Bo = function(r, o, i) {
		if (0 === o.length) throw new Error("You must specify at least one required field.");
		return Q("required", o), Z(o),
		function(t) {
			var n = q(t);
			z(o, function(e) {
				return k(n, e)
			}) || J(o, n), r(o, n);
			var e = P(o, function(e) {
				return !i.validate(t[e], e)
			});
			return 0 < e.length && function(e, t) {
				throw new Error("All values need to be of type: " + t + ". Keys (" + K(e).join(", ") + ") were not.")
			}(e, i.label), t
		}
	},
	Wo = function(t, e) {
		var n = P(e, function(e) {
			return !k(t, e)
		});
		0 < n.length && $(n)
	},
	Mo = function(e) {
		return Bo(Wo, e, {
			validate: T,
			label: "function"
		})
	},
	_o = function(e) {
		var t = yt(e, "colspan") ? parseInt(wt(e, "colspan"), 10) : 1,
				n = yt(e, "rowspan") ? parseInt(wt(e, "rowspan"), 10) : 1;
				return {
					element: C(e),
					colspan: C(t),
					rowspan: C(n)
				}
	},
	Lo = Mo(["cell", "row", "replace", "gap"]),
	jo = function(r, e) {
		Lo(r);
		var n = Io(R.none()),
		o = e !== undefined ? e : _o,
				i = function(e) {
			var t, n = o(e);
			return t = n, r.cell(t)
		},
		u = function(e) {
			var t = i(e);
			return n.get().isNone() && n.set(R.some(t)), a = R.some({
				item: e,
				replacement: t
			}), t
		},
		a = R.none();
		return {
			getOrInit: function(t, n) {
				return a.fold(function() {
					return u(t)
				}, function(e) {
					return n(t, e.item) ? e.replacement : u(t)
				})
			},
			cursor: n.get
		}
	},
	Fo = function(o, a) {
		return function(n) {
			var r = Io(R.none());
			Lo(n);
			var i = [],
			u = function(e) {
				var t = n.replace(e, a, {
					scope: o
				});
				return i.push({
					item: e,
					sub: t
				}), r.get().isNone() && r.set(R.some(t)), t
			};
			return {
				replaceOrInit: function(t, n) {
					return (r = t, o = n, W(i, function(e) {
						return o(e.item, r)
					})).fold(function() {
						return u(t)
					}, function(e) {
						return n(t, e.item) ? e.sub : u(t)
					});
					var r, o
				},
				cursor: r.get
			}
		}
	},
	zo = function(n) {
		Lo(n);
		var e = Io(R.none());
		return {
			combine: function(t) {
				return e.get().isNone() && e.set(R.some(t)),
				function() {
					var e = n.cell({
						element: C(t),
						colspan: C(1),
						rowspan: C(1)
					});
					return un(e, "width"), un(t, "width"), e
				}
			},
			cursor: e.get
		}
	},
	Ho = ["body", "p", "div", "article", "aside", "figcaption", "figure", "footer", "header", "nav", "section", "ol", "ul", "table", "thead", "tfoot", "tbody", "caption", "tr", "td", "th", "h1", "h2", "h3", "h4", "h5", "h6", "blockquote", "pre", "address"],
	Uo = function(e, t) {
		var n = e.property().name(t);
		return k(Ho, n)
	},
	qo = function(e, t) {
		return k(["br", "img", "hr", "input"], e.property().name(t))
	},
	Vo = Uo,
	Go = function(e, t) {
		var n = e.property().name(t);
		return k(["ol", "ul"], n)
	},
	Yo = qo,
	Xo = Fn(),
	Ko = function(e) {
		return Vo(Xo, e)
	},
	Jo = function(e) {
		return Go(Xo, e)
	},
	$o = function(e) {
		return Yo(Xo, e)
	},
	Qo = function(e) {
		var t, i = function(e) {
			return "br" === lt(e)
		},
		n = function(o) {
			return An(o).bind(function(n) {
				var r = ot(n).map(function(e) {
					return !!Ko(e) || ($o(e) ? "img" !== lt(e) : void 0)
				}).getOr(!1);
				return tt(n).map(function(e) {
					return !0 === r || "li" === lt(t = e) || Mt(t, Jo).isSome() || i(n) || Ko(e) && !$e(o, e) ? [] : [le.fromTag("br")];
					var t
				})
			}).getOr([])
		},
		r = 0 === (t = F(e, function(e) {
			var t = it(e);
			return z(t, function(e) {
				return i(e) || gt(e) && 0 === Sn(e).trim().length
			}) ? [] : t.concat(n(e))
		})).length ? [le.fromTag("br")] : t;
		gn(e[0]), mn(e[0], r)
	},
	Zo = Object.prototype.hasOwnProperty,
	ei = (Ao = function(e, t) {
		return t
	}, function() {
		for (var e = new Array(arguments.length), t = 0; t < e.length; t++) e[t] = arguments[t];
		if (0 === e.length) throw new Error("Can't merge zero objects");
		for (var n = {}, r = 0; r < e.length; r++) {
			var o = e[r];
			for (var i in o) Zo.call(o, i) && (n[i] = Ao(n[i], o[i]))
		}
		return n
	}),
	ti = function(e) {
		for (var t = [], n = function(e) {
			t.push(e)
		}, r = 0; r < e.length; r++) e[r].each(n);
		return t
	},
	ni = function(e, t) {
		for (var n = 0; n < e.length; n++) {
			var r = t(e[n], n);
			if (r.isSome()) return r
		}
		return R.none()
	},
	ri = function(e, t) {
		return ae.rowcells(t, e.section())
	},
	oi = function(e, t) {
		return e.cells()[t]
	},
	ii = {
			addCell: function(e, t, n) {
				var r = e.cells(),
				o = r.slice(0, t),
				i = r.slice(t),
				u = o.concat([n]).concat(i);
				return ri(e, u)
			},
			setCells: ri,
			mutateCell: function(e, t, n) {
				e.cells()[t] = n
			},
			getCell: oi,
			getCellElement: function(e, t) {
				return oi(e, t).element()
			},
			mapCells: function(e, t) {
				var n = e.cells(),
				r = E(n, t);
				return ae.rowcells(r, e.section())
			},
			cellLength: function(e) {
				return e.cells().length
			}
	},
	ui = function(e, t) {
		if (0 === e.length) return 0;
		var n = e[0];
		return M(e, function(e) {
			return !t(n.element(), e.element())
		}).fold(function() {
			return e.length
		}, function(e) {
			return e
		})
	},
	ai = function(e, t, n, r) {
		var o, i, u, a, c = (o = e, i = t, o[i]).cells().slice(n),
		l = ui(c, r),
		f = (u = e, a = n, E(u, function(e) {
			return ii.getCell(e, a)
		})).slice(t),
		s = ui(f, r);
		return {
			colspan: C(l),
			rowspan: C(s)
		}
	},
	ci = function(o, i) {
		var u = E(o, function(e, t) {
			return E(e.cells(), function(e, t) {
				return !1
			})
		});
		return E(o, function(e, r) {
			var t = F(e.cells(), function(e, t) {
				if (!1 === u[r][t]) {
					var n = ai(o, r, t, i);
					return function(e, t, n, r) {
						for (var o = e; o < e + n; o++)
							for (var i = t; i < t + r; i++) u[o][i] = !0
					}(r, t, n.rowspan(), n.colspan()), [ae.detailnew(e.element(), n.rowspan(), n.colspan(), e.isNew())]
				}
				return []
			});
			return ae.rowdetails(t, e.section())
		})
	},
	li = function(e, t, n) {
		for (var r = [], o = 0; o < e.grid().rows(); o++) {
			for (var i = [], u = 0; u < e.grid().columns(); u++) {
				var a = $t.getAt(e, o, u).map(function(e) {
					return ae.elementnew(e.element(), n)
				}).getOrThunk(function() {
					return ae.elementnew(t.gap(), !0)
				});
				i.push(a)
			}
			var c = ae.rowcells(i, e.all()[o].section());
			r.push(c)
		}
		return r
	},
	fi = function(e, t, n, r) {
		n === r ? xt(e, t) : vt(e, t, n)
	},
	si = function(o, e) {
		var i = [],
		u = [],
		t = function(e, t) {
			0 < e.length ? function(e, t) {
				var n = Lt(o, t).getOrThunk(function() {
					var e = le.fromTag(t, et(o).dom());
					return fn(o, e), e
				});
				gn(n);
				var r = E(e, function(e) {
					e.isNew() && i.push(e.element());
					var t = e.element();
					return gn(t), A(e.cells(), function(e) {
						e.isNew() && u.push(e.element()), fi(e.element(), "colspan", e.colspan(), 1), fi(e.element(), "rowspan", e.rowspan(), 1), fn(t, e.element())
					}), t
				});
				mn(n, r)
			}(e, t) : Lt(o, t).each(hn)
		},
		n = [],
		r = [],
		a = [];
		return A(e, function(e) {
			switch (e.section()) {
			case "thead":
				n.push(e);
				break;
			case "tbody":
				r.push(e);
				break;
			case "tfoot":
				a.push(e)
			}
		}), t(n, "thead"), t(r, "tbody"), t(a, "tfoot"), {
			newRows: C(i),
			newCells: C(u)
		}
	},
	di = function(e) {
		return E(e, function(e) {
			var n = yn(e.element());
			return A(e.cells(), function(e) {
				var t = xn(e.element());
				fi(t, "colspan", e.colspan(), 1), fi(t, "rowspan", e.rowspan(), 1), fn(n, t)
			}), n
		})
	},
	mi = function(e, t) {
		for (var n = [], r = 0; r < e; r++) n.push(t(r));
		return n
	},
	gi = function(e, t) {
		for (var n = [], r = e; r < t; r++) n.push(r);
		return n
	},
	hi = function(t, n) {
		if (n < 0 || n >= t.length - 1) return R.none();
		var e = t[n].fold(function() {
			var e = U(t.slice(0, n));
			return ni(e, function(e, t) {
				return e.map(function(e) {
					return {
						value: e,
						delta: t + 1
					}
				})
			})
		}, function(e) {
			return R.some({
				value: e,
				delta: 0
			})
		}),
		r = t[n + 1].fold(function() {
			var e = t.slice(n + 1);
			return ni(e, function(e, t) {
				return e.map(function(e) {
					return {
						value: e,
						delta: t + 1
					}
				})
			})
		}, function(e) {
			return R.some({
				value: e,
				delta: 1
			})
		});
		return e.bind(function(n) {
			return r.map(function(e) {
				var t = e.delta + n.delta;
				return Math.abs(e.value - n.value) / t
			})
		})
	},
	pi = function(e, t, n) {
		var r = e();
		return W(r, t).orThunk(function() {
			return R.from(r[0]).orThunk(n)
		}).map(function(e) {
			return e.element()
		})
	},
	vi = function(n) {
		var e = n.grid(),
		t = gi(0, e.columns()),
		r = gi(0, e.rows());
		return E(t, function(t) {
			return pi(function() {
				return F(r, function(e) {
					return $t.getAt(n, e, t).filter(function(e) {
						return e.column() === t
					}).fold(C([]), function(e) {
						return [e]
					})
				})
			}, function(e) {
				return 1 === e.colspan()
			}, function() {
				return $t.getAt(n, 0, t)
			})
		})
	},
	bi = function(n) {
		var e = n.grid(),
		t = gi(0, e.rows()),
		r = gi(0, e.columns());
		return E(t, function(t) {
			return pi(function() {
				return F(r, function(e) {
					return $t.getAt(n, t, e).filter(function(e) {
						return e.row() === t
					}).fold(C([]), function(e) {
						return [e]
					})
				})
			}, function(e) {
				return 1 === e.rowspan()
			}, function() {
				return $t.getAt(n, t, 0)
			})
		})
	},
	wi = function(e, t, n, r, o) {
		var i = le.fromTag("div");
		return tn(i, {
			position: "absolute",
			left: t - r / 2 + "px",
			top: n + "px",
			height: o + "px",
			width: r + "px"
		}), bt(i, {
			"data-column": e,
			role: "presentation"
		}), i
	},
	yi = function(e, t, n, r, o) {
		var i = le.fromTag("div");
		return tn(i, {
			position: "absolute",
			left: t + "px",
			top: n - o / 2 + "px",
			height: o + "px",
			width: r + "px"
		}), bt(i, {
			"data-row": e,
			role: "presentation"
		}), i
	},
	xi = function(e) {
		var t = e.replace(/\./g, "-");
		return {
			resolve: function(e) {
				return t + "-" + e
			}
		}
	},
	Ci = {
			resolve: xi("ephox-snooker").resolve
	},
	Ri = function(e, t) {
		var n = wt(e, t);
		return n === undefined || "" === n ? [] : n.split(" ")
	},
	Si = function(e) {
		return e.dom().classList !== undefined
	},
	Ti = function(e, t) {
		return o = t, i = Ri(n = e, r = "class").concat([o]), vt(n, r, i.join(" ")), !0;
		var n, r, o, i
	},
	Di = function(e, t) {
		return o = t, 0 < (i = P(Ri(n = e, r = "class"), function(e) {
			return e !== o
		})).length ? vt(n, r, i.join(" ")) : xt(n, r), !1;
		var n, r, o, i
	},
	Oi = function(e, t) {
		Si(e) ? e.dom().classList.add(t) : Ti(e, t)
	},
	ki = function(e) {
		0 === (Si(e) ? e.dom().classList : Ri(e, "class")).length && xt(e, "class")
	},
	Ni = function(e, t) {
		return Si(e) && e.dom().classList.contains(t)
	},
	Ei = Ci.resolve("resizer-bar"),
	Ai = Ci.resolve("resizer-rows"),
	Pi = Ci.resolve("resizer-cols"),
	Ii = function(e) {
		var t = Nt(e.parent(), "." + Ei);
		A(t, hn)
	},
	Bi = function(n, e, r) {
		var o = n.origin();
		A(e, function(e, t) {
			e.each(function(e) {
				var t = r(o, e);
				Oi(t, Ei), fn(n.parent(), t)
			})
		})
	},
	Wi = function(e, t, n, r, o, i) {
		var u, a, c, l, f = po(t),
		s = 0 < n.length ? o.positions(n, t) : [];
		u = e, a = s, c = f, l = $r(t), Bi(u, a, function(e, t) {
			var n = yi(t.row(), c.left() - e.left(), t.y() - e.top(), l, 7);
			return Oi(n, Ai), n
		});
		var d, m, g, h, p = 0 < r.length ? i.positions(r, t) : [];
		d = e, m = p, g = f, h = Xr(t), Bi(d, m, function(e, t) {
			var n = wi(t.col(), t.x() - e.left(), g.top() - e.top(), 7, h);
			return Oi(n, Pi), n
		})
	},
	Mi = function(e, t) {
		var n = Nt(e.parent(), "." + Ei);
		A(n, t)
	},
	_i = {
			refresh: function(e, t, n, r) {
				Ii(e);
				var o = Yt(t),
				i = $t.generate(o),
				u = bi(i),
				a = vi(i);
				Wi(e, t, u, a, n, r)
			},
			hide: function(e) {
				Mi(e, function(e) {
					en(e, "display", "none")
				})
			},
			show: function(e) {
				Mi(e, function(e) {
					en(e, "display", "block")
				})
			},
			destroy: Ii,
			isRowBar: function(e) {
				return Ni(e, Ai)
			},
			isColBar: function(e) {
				return Ni(e, Pi)
			}
	},
	Li = function(e, r) {
		return E(e, function(e) {
			var t, n = (t = e.details(), ni(t, function(e) {
				return tt(e.element()).map(function(e) {
					var t = tt(e).isNone();
					return ae.elementnew(e, t)
				})
			}).getOrThunk(function() {
				return ae.elementnew(r.row(), !0)
			}));
			return ae.rowdatanew(n.element(), e.details(), e.section(), n.isNew())
		})
	},
	ji = function(e, t) {
		var n = ci(e, $e);
		return Li(n, t)
	},
	Fi = function(e, t) {
		var n = j(E(e.all(), function(e) {
			return e.cells()
		}));
		return W(n, function(e) {
			return $e(t, e.element())
		})
	},
	zi = function(a, c, l, f, s) {
		return function(n, r, e, o, i) {
			var t = Yt(r),
			u = $t.generate(t);
			return c(u, e).map(function(e) {
				var t = li(u, o, !1),
				n = a(t, e, $e, s(o)),
				r = ji(n.grid(), o);
				return {
					grid: C(r),
					cursor: n.cursor
				}
			}).fold(function() {
				return R.none()
			}, function(e) {
				var t = si(r, e.grid());
				return l(r, e.grid(), i), f(r), _i.refresh(n, r, ko.height, i), R.some({
					cursor: e.cursor,
					newRows: t.newRows,
					newCells: t.newCells
				})
			})
		}
	},
	Hi = ji,
	Ui = function(t, e) {
		return Gt.cell(e.element()).bind(function(e) {
			return Fi(t, e)
		})
	},
	qi = function(t, e) {
		var n = E(e.selection(), function(e) {
			return Gt.cell(e).bind(function(e) {
				return Fi(t, e)
			})
		}),
		r = ti(n);
		return 0 < r.length ? R.some(r) : R.none()
	},
	Vi = function(t, n) {
		return Gt.cell(n.element()).bind(function(e) {
			return Fi(t, e).map(function(e) {
				return ei(e, {
					generators: n.generators,
					clipboard: n.clipboard
				})
			})
		})
	},
	Gi = function(t, e) {
		var n = E(e.selection(), function(e) {
			return Gt.cell(e).bind(function(e) {
				return Fi(t, e)
			})
		}),
		r = ti(n);
		return 0 < r.length ? R.some(ei({
			cells: r
		}, {
			generators: e.generators,
			clipboard: e.clipboard
		})) : R.none()
	},
	Yi = function(e, t) {
		return t.mergable()
	},
	Xi = function(e, t) {
		return t.unmergable()
	},
	Ki = function(n) {
		return {
			is: function(e) {
				return n === e
			},
			isValue: l,
			isError: a,
			getOr: C(n),
			getOrThunk: C(n),
			getOrDie: C(n),
			or: function(e) {
				return Ki(n)
			},
			orThunk: function(e) {
				return Ki(n)
			},
			fold: function(e, t) {
				return t(n)
			},
			map: function(e) {
				return Ki(e(n))
			},
			mapError: function(e) {
				return Ki(n)
			},
			each: function(e) {
				e(n)
			},
			bind: function(e) {
				return e(n)
			},
			exists: function(e) {
				return e(n)
			},
			forall: function(e) {
				return e(n)
			},
			toOption: function() {
				return R.some(n)
			}
		}
	},
	Ji = function(n) {
		return {
			is: a,
			isValue: a,
			isError: l,
			getOr: o,
			getOrThunk: function(e) {
				return e()
			},
			getOrDie: function() {
				return e = String(n),
				function() {
					throw new Error(e)
				}();
				var e
			},
			or: function(e) {
				return e
			},
			orThunk: function(e) {
				return e()
			},
			fold: function(e, t) {
				return e(n)
			},
			map: function(e) {
				return Ji(n)
			},
			mapError: function(e) {
				return Ji(e(n))
			},
			each: y,
			bind: function(e) {
				return Ji(n)
			},
			exists: a,
			forall: l,
			toOption: R.none
		}
	},
	$i = {
			value: Ki,
			error: Ji
	},
	Qi = function(e, t) {
		return E(e, function() {
			return ae.elementnew(t.cell(), !0)
		})
	},
	Zi = function(t, e, n) {
		return t.concat(mi(e, function(e) {
			return ii.setCells(t[t.length - 1], Qi(t[t.length - 1].cells(), n))
		}))
	},
	eu = function(e, t, n) {
		return E(e, function(e) {
			return ii.setCells(e, e.cells().concat(Qi(gi(0, t), n)))
		})
	},
	tu = function(e, t, n) {
		if (e.row() >= t.length || e.column() > ii.cellLength(t[0])) return $i.error("invalid start address out of table bounds, row: " + e.row() + ", column: " + e.column());
		var r = t.slice(e.row()),
		o = r[0].cells().slice(e.column()),
		i = ii.cellLength(n[0]),
		u = n.length;
		return $i.value({
			rowDelta: C(r.length - u),
			colDelta: C(o.length - i)
		})
	},
	nu = function(e, t) {
		var n = ii.cellLength(e[0]),
		r = ii.cellLength(t[0]);
		return {
			rowDelta: C(0),
			colDelta: C(n - r)
		}
	},
	ru = function(e, t, n) {
		var r = t.colDelta() < 0 ? eu : o;
		return (t.rowDelta() < 0 ? Zi : o)(r(e, Math.abs(t.colDelta()), n), Math.abs(t.rowDelta()), n)
	},
	ou = function(e, t, n, r) {
		if (0 === e.length) return e;
		for (var o = t.startRow(); o <= t.finishRow(); o++)
			for (var i = t.startCol(); i <= t.finishCol(); i++) ii.mutateCell(e[o], i, ae.elementnew(r(), !1));
		return e
	},
	iu = function(e, t, n, r) {
		for (var o = !0, i = 0; i < e.length; i++)
			for (var u = 0; u < ii.cellLength(e[0]); u++) {
				var a = n(ii.getCellElement(e[i], u), t);
				!0 === a && !1 === o ? ii.mutateCell(e[i], u, ae.elementnew(r(), !0)) : !0 === a && (o = !1)
			}
		return e
	},
	uu = function(i, u, a, c) {
		if (0 < u && u < i.length) {
			var e = i[u - 1].cells(),
			t = (n = a, B(e, function(e, t) {
				return N(e, function(e) {
					return n(e.element(), t.element())
				}) ? e : e.concat([t])
			}, []));
			A(t, function(e) {
				for (var t = R.none(), n = u; n < i.length; n++)
					for (var r = 0; r < ii.cellLength(i[0]); r++) {
						var o = i[n].cells()[r];
						a(o.element(), e.element()) && (t.isNone() && (t = R.some(c())), t.each(function(e) {
							ii.mutateCell(i[n], r, ae.elementnew(e, !0))
						}))
					}
			})
		}
		var n;
		return i
	},
	au = function(n, r, o, i, u) {
		return tu(n, r, o).map(function(e) {
			var t = ru(r, e, i);
			return function(e, t, n, r, o) {
				for (var i, u, a, c, l, f = e.row(), s = e.column(), d = f + n.length, m = s + ii.cellLength(n[0]), g = f; g < d; g++)
					for (var h = s; h < m; h++) {
						i = t, u = g, a = h, c = void 0, c = b(o, ii.getCell(i[u], a).element()), l = i[u], 1 < i.length && 1 < ii.cellLength(l) && (0 < a && c(ii.getCellElement(l, a - 1)) || a < l.length - 1 && c(ii.getCellElement(l, a + 1)) || 0 < u && c(ii.getCellElement(i[u - 1], a)) || u < i.length - 1 && c(ii.getCellElement(i[u + 1], a))) && iu(t, ii.getCellElement(t[g], h), o, r.cell);
						var p = ii.getCellElement(n[g - f], h - s),
						v = r.replace(p);
						ii.mutateCell(t[g], h, ae.elementnew(v, !0))
					}
				return t
			}(n, t, o, i, u)
		})
	},
	cu = function(e, t, n, r, o) {
		uu(t, e, o, r.cell);
		var i = nu(n, t),
		u = ru(n, i, r),
		a = nu(t, u),
		c = ru(t, a, r);
		return c.slice(0, e).concat(u).concat(c.slice(e, c.length))
	},
	lu = function(n, r, e, o, i) {
		var t = n.slice(0, r),
		u = n.slice(r),
		a = ii.mapCells(n[e], function(e, t) {
			return 0 < r && r < n.length && o(ii.getCellElement(n[r - 1], t), ii.getCellElement(n[r], t)) ? ii.getCell(n[r], t) : ae.elementnew(i(e.element(), o), !0)
		});
		return t.concat([a]).concat(u)
	},
	fu = function(e, n, r, o, i) {
		return E(e, function(e) {
			var t = 0 < n && n < ii.cellLength(e) && o(ii.getCellElement(e, n - 1), ii.getCellElement(e, n)) ? ii.getCell(e, n) : ae.elementnew(i(ii.getCellElement(e, r), o), !0);
			return ii.addCell(e, n, t)
		})
	},
	su = function(e, r, o, i, u) {
		var a = o + 1;
		return E(e, function(e, t) {
			var n = t === r ? ae.elementnew(u(ii.getCellElement(e, o), i), !0) : ii.getCell(e, o);
			return ii.addCell(e, a, n)
		})
	},
	du = function(e, t, n, r, o) {
		var i = t + 1,
		u = e.slice(0, i),
		a = e.slice(i),
		c = ii.mapCells(e[t], function(e, t) {
			return t === n ? ae.elementnew(o(e.element(), r), !0) : e
		});
		return u.concat([c]).concat(a)
	},
	mu = function(e, t, n) {
		return e.slice(0, t).concat(e.slice(n + 1))
	},
	gu = function(e, n, r) {
		var t = E(e, function(e) {
			var t = e.cells().slice(0, n).concat(e.cells().slice(r + 1));
			return ae.rowcells(t, e.section())
		});
		return P(t, function(e) {
			return 0 < e.cells().length
		})
	},
	hu = function(e, n, r, o) {
		return E(e, function(e) {
			return ii.mapCells(e, function(e) {
				return t = e, N(n, function(e) {
					return r(t.element(), e.element())
				}) ? ae.elementnew(o(e.element(), r), !0) : e;
				var t
			})
		})
	},
	pu = function(e, t, n, r) {
		return ii.getCellElement(e[t], n) !== undefined && 0 < t && r(ii.getCellElement(e[t - 1], n), ii.getCellElement(e[t], n))
	},
	vu = function(e, t, n) {
		return 0 < t && n(ii.getCellElement(e, t - 1), ii.getCellElement(e, t))
	},
	bu = function(n, r, o, e) {
		var t = F(n, function(e, t) {
			return pu(n, t, r, o) || vu(e, r, o) ? [] : [ii.getCell(e, r)]
		});
		return hu(n, t, o, e)
	},
	wu = function(n, r, o, e) {
		var i = n[r],
		t = F(i.cells(), function(e, t) {
			return pu(n, r, t, o) || vu(i, t, o) ? [] : [e]
		});
		return hu(n, t, o, e)
	},
	yu = function(e) {
		return {
			fold: e
		}
	},
	xu = function() {
		return yu(function(e, t, n, r, o) {
			return e()
		})
	},
	Cu = function(i) {
		return yu(function(e, t, n, r, o) {
			return t(i)
		})
	},
	Ru = function(i, u) {
		return yu(function(e, t, n, r, o) {
			return n(i, u)
		})
	},
	Su = function(i, u, a) {
		return yu(function(e, t, n, r, o) {
			return r(i, u, a)
		})
	},
	Tu = function(i, u) {
		return yu(function(e, t, n, r, o) {
			return o(i, u)
		})
	},
	Du = function(e, t, i, u) {
		var n, r, a = e.slice(0),
		o = (r = t, 0 === (n = e).length ? xu() : 1 === n.length ? Cu(0) : 0 === r ? Ru(0, 1) : r === n.length - 1 ? Tu(r - 1, r) : 0 < r && r < n.length - 1 ? Su(r - 1, r, r + 1) : xu()),
		c = function(e) {
			return E(e, C(0))
		},
		l = C(c(a)),
		f = function(e, t) {
			if (0 <= i) {
				var n = Math.max(u.minCellWidth(), a[t] - i);
				return c(a.slice(0, e)).concat([i, n - a[t]]).concat(c(a.slice(t + 1)))
			}
			var r = Math.max(u.minCellWidth(), a[e] + i),
			o = a[e] - r;
			return c(a.slice(0, e)).concat([r - a[e], o]).concat(c(a.slice(t + 1)))
		},
		s = f;
		return o.fold(l, function(e) {
			return u.singleColumnWidth(a[e], i)
		}, s, function(e, t, n) {
			return f(t, n)
		}, function(e, t) {
			if (0 <= i) return c(a.slice(0, t)).concat([i]);
			var n = Math.max(u.minCellWidth(), a[t] + i);
			return c(a.slice(0, t)).concat([n - a[t]])
		})
	},
	Ou = function(e, t) {
		return yt(e, t) && 1 < parseInt(wt(e, t), 10)
	},
	ku = {
			hasColspan: function(e) {
				return Ou(e, "colspan")
			},
			hasRowspan: function(e) {
				return Ou(e, "rowspan")
			},
			minWidth: C(10),
			minHeight: C(10),
			getInt: function(e, t) {
				return parseInt(nn(e, t), 10)
			}
	},
	Nu = function(e, t, n) {
		return on(e, t).fold(function() {
			return n(e) + "px"
		}, function(e) {
			return e
		})
	},
	Eu = function(e) {
		return Nu(e, "width", fo.getPixelWidth)
	},
	Au = function(e) {
		return Nu(e, "height", fo.getHeight)
	},
	Pu = function(e, t, n, r, o) {
		var i = vi(e),
		u = E(i, function(e) {
			return e.map(t.edge)
		});
		return E(i, function(e, t) {
			return e.filter(m(ku.hasColspan)).fold(function() {
				var e = hi(u, t);
				return r(e)
			}, function(e) {
				return n(e, o)
			})
		})
	},
	Iu = function(e) {
		return e.map(function(e) {
			return e + "px"
		}).getOr("")
	},
	Bu = function(e, t, n, r) {
		var o = bi(e),
		i = E(o, function(e) {
			return e.map(t.edge)
		});
		return E(o, function(e, t) {
			return e.filter(m(ku.hasRowspan)).fold(function() {
				var e = hi(i, t);
				return r(e)
			}, function(e) {
				return n(e)
			})
		})
	},
	Wu = {
			getRawWidths: function(e, t) {
				return Pu(e, t, Eu, Iu)
			},
			getPixelWidths: function(e, t, n) {
				return Pu(e, t, fo.getPixelWidth, function(e) {
					return e.getOrThunk(n.minCellWidth)
				}, n)
			},
			getPercentageWidths: function(e, t, n) {
				return Pu(e, t, fo.getPercentageWidth, function(e) {
					return e.fold(function() {
						return n.minCellWidth()
					}, function(e) {
						return e / n.pixelWidth() * 100
					})
				}, n)
			},
			getPixelHeights: function(e, t) {
				return Bu(e, t, fo.getHeight, function(e) {
					return e.getOrThunk(ku.minHeight)
				})
			},
			getRawHeights: function(e, t) {
				return Bu(e, t, Au, Iu)
			}
	},
	Mu = function(e, t, n) {
		for (var r = 0, o = e; o < t; o++) r += n[o] !== undefined ? n[o] : 0;
		return r
	},
	_u = function(e, n) {
		var t = $t.justCells(e);
		return E(t, function(e) {
			var t = Mu(e.column(), e.column() + e.colspan(), n);
			return {
				element: e.element,
				width: C(t),
				colspan: e.colspan
			}
		})
	},
	Lu = function(e, n) {
		var t = $t.justCells(e);
		return E(t, function(e) {
			var t = Mu(e.row(), e.row() + e.rowspan(), n);
			return {
				element: e.element,
				height: C(t),
				rowspan: e.rowspan
			}
		})
	},
	ju = function(e, n) {
		return E(e.all(), function(e, t) {
			return {
				element: e.element,
				height: C(n[t])
			}
		})
	},
	Fu = function(e) {
		var t = parseInt(e, 10),
		n = o;
		return {
			width: C(t),
			pixelWidth: C(t),
			getWidths: Wu.getPixelWidths,
			getCellDelta: n,
			singleColumnWidth: function(e, t) {
				return [Math.max(ku.minWidth(), e + t) - e]
			},
			minCellWidth: ku.minWidth,
			setElementWidth: fo.setPixelWidth,
			setTableWidth: function(e, t, n) {
				var r = I(t, function(e, t) {
					return e + t
				}, 0);
				fo.setPixelWidth(e, r)
			}
		}
	},
	zu = function(e, t) {
		if (fo.percentageBasedSizeRegex().test(t)) {
			var n = fo.percentageBasedSizeRegex().exec(t);
			return o = n[1], i = e, u = parseFloat(o), a = Jr(i), {
				width: C(u),
				pixelWidth: C(a),
				getWidths: Wu.getPercentageWidths,
				getCellDelta: function(e) {
					return e / a * 100
				},
				singleColumnWidth: function(e, t) {
					return [100 - e]
				},
				minCellWidth: function() {
					return ku.minWidth() / a * 100
				},
				setElementWidth: fo.setPercentageWidth,
				setTableWidth: function(e, t, n) {
					var r = u + n;
					fo.setPercentageWidth(e, r)
				}
			}
		}
		if (fo.pixelBasedSizeRegex().test(t)) {
			var r = fo.pixelBasedSizeRegex().exec(t);
			return Fu(r[1])
		}
		var o, i, u, a, c = Jr(e);
		return Fu(c)
	},
	Hu = function(t) {
		return fo.getRawWidth(t).fold(function() {
			var e = Jr(t);
			return Fu(e)
		}, function(e) {
			return zu(t, e)
		})
	},
	Uu = function(e) {
		return $t.generate(e)
	},
	qu = function(e) {
		var t = Yt(e);
		return Uu(t)
	},
	Vu = function(e, t, n, r) {
		var o = Hu(e),
		i = o.getCellDelta(t),
		u = qu(e),
		a = o.getWidths(u, r, o),
		c = Du(a, n, i, o),
		l = E(c, function(e, t) {
			return e + a[t]
		}),
		f = _u(u, l);
		A(f, function(e) {
			o.setElementWidth(e.element(), e.width())
		}), n === u.grid().columns() - 1 && o.setTableWidth(e, l, i)
	},
	Gu = function(e, n, r, t) {
		var o = qu(e),
		i = Wu.getPixelHeights(o, t),
		u = E(i, function(e, t) {
			return r === t ? Math.max(n + e, ku.minHeight()) : e
		}),
		a = Lu(o, u),
		c = ju(o, u);
		A(c, function(e) {
			fo.setHeight(e.element(), e.height())
		}), A(a, function(e) {
			fo.setHeight(e.element(), e.height())
		});
		var l = I(u, function(e, t) {
			return e + t
		}, 0);
		fo.setHeight(e, l)
	},
	Yu = function(e, t, n) {
		var r = Hu(e),
		o = Uu(t),
		i = r.getWidths(o, n, r),
		u = _u(o, i);
		A(u, function(e) {
			r.setElementWidth(e.element(), e.width())
		});
		var a = I(i, function(e, t) {
			return t + e
		}, 0);
		0 < u.length && r.setElementWidth(e, a)
	},
	Xu = function(e) {
		0 === Gt.cells(e).length && hn(e)
	},
	Ku = X("grid", "cursor"),
	Ju = function(e, t, n) {
		return $u(e, t, n).orThunk(function() {
			return $u(e, 0, 0)
		})
	},
	$u = function(e, t, n) {
		return R.from(e[t]).bind(function(e) {
			return R.from(e.cells()[n]).bind(function(e) {
				return R.from(e.element())
			})
		})
	},
	Qu = function(e, t, n) {
		return Ku(e, $u(e, t, n))
	},
	Zu = function(e) {
		return B(e, function(e, t) {
			return N(e, function(e) {
				return e.row() === t.row()
			}) ? e : e.concat([t])
		}, []).sort(function(e, t) {
			return e.row() - t.row()
		})
	},
	ea = function(e) {
		return B(e, function(e, t) {
			return N(e, function(e) {
				return e.column() === t.column()
			}) ? e : e.concat([t])
		}, []).sort(function(e, t) {
			return e.column() - t.column()
		})
	},
	ta = function(e, t, n) {
		var r = Xt(e, n),
		o = $t.generate(r);
		return li(o, t, !0)
	},
	na = Yu,
	ra = {
			insertRowBefore: zi(function(e, t, n, r) {
				var o = t.row(),
				i = t.row(),
				u = lu(e, i, o, n, r.getOrInit);
				return Qu(u, i, t.column())
			}, Ui, y, y, jo),
			insertRowsBefore: zi(function(e, t, n, r) {
				var o = t[0].row(),
				i = t[0].row(),
				u = Zu(t),
				a = B(u, function(e, t) {
					return lu(e, i, o, n, r.getOrInit)
				}, e);
				return Qu(a, i, t[0].column())
			}, qi, y, y, jo),
			insertRowAfter: zi(function(e, t, n, r) {
				var o = t.row(),
				i = t.row() + t.rowspan(),
				u = lu(e, i, o, n, r.getOrInit);
				return Qu(u, i, t.column())
			}, Ui, y, y, jo),
			insertRowsAfter: zi(function(e, t, n, r) {
				var o = Zu(t),
				i = o[o.length - 1].row(),
				u = o[o.length - 1].row() + o[o.length - 1].rowspan(),
				a = B(o, function(e, t) {
					return lu(e, u, i, n, r.getOrInit)
				}, e);
				return Qu(a, u, t[0].column())
			}, qi, y, y, jo),
			insertColumnBefore: zi(function(e, t, n, r) {
				var o = t.column(),
				i = t.column(),
				u = fu(e, i, o, n, r.getOrInit);
				return Qu(u, t.row(), i)
			}, Ui, na, y, jo),
			insertColumnsBefore: zi(function(e, t, n, r) {
				var o = ea(t),
				i = o[0].column(),
				u = o[0].column(),
				a = B(o, function(e, t) {
					return fu(e, u, i, n, r.getOrInit)
				}, e);
				return Qu(a, t[0].row(), u)
			}, qi, na, y, jo),
			insertColumnAfter: zi(function(e, t, n, r) {
				var o = t.column(),
				i = t.column() + t.colspan(),
				u = fu(e, i, o, n, r.getOrInit);
				return Qu(u, t.row(), i)
			}, Ui, na, y, jo),
			insertColumnsAfter: zi(function(e, t, n, r) {
				var o = t[t.length - 1].column(),
				i = t[t.length - 1].column() + t[t.length - 1].colspan(),
				u = ea(t),
				a = B(u, function(e, t) {
					return fu(e, i, o, n, r.getOrInit)
				}, e);
				return Qu(a, t[0].row(), i)
			}, qi, na, y, jo),
			splitCellIntoColumns: zi(function(e, t, n, r) {
				var o = su(e, t.row(), t.column(), n, r.getOrInit);
				return Qu(o, t.row(), t.column())
			}, Ui, y, y, jo),
			splitCellIntoRows: zi(function(e, t, n, r) {
				var o = du(e, t.row(), t.column(), n, r.getOrInit);
				return Qu(o, t.row(), t.column())
			}, Ui, y, y, jo),
			eraseColumns: zi(function(e, t, n, r) {
				var o = ea(t),
				i = gu(e, o[0].column(), o[o.length - 1].column()),
				u = Ju(i, t[0].row(), t[0].column());
				return Ku(i, u)
			}, qi, na, Xu, jo),
			eraseRows: zi(function(e, t, n, r) {
				var o = Zu(t),
				i = mu(e, o[0].row(), o[o.length - 1].row()),
				u = Ju(i, t[0].row(), t[0].column());
				return Ku(i, u)
			}, qi, y, Xu, jo),
			makeColumnHeader: zi(function(e, t, n, r) {
				var o = bu(e, t.column(), n, r.replaceOrInit);
				return Qu(o, t.row(), t.column())
			}, Ui, y, y, Fo("row", "th")),
			unmakeColumnHeader: zi(function(e, t, n, r) {
				var o = bu(e, t.column(), n, r.replaceOrInit);
				return Qu(o, t.row(), t.column())
			}, Ui, y, y, Fo(null, "td")),
			makeRowHeader: zi(function(e, t, n, r) {
				var o = wu(e, t.row(), n, r.replaceOrInit);
				return Qu(o, t.row(), t.column())
			}, Ui, y, y, Fo("col", "th")),
			unmakeRowHeader: zi(function(e, t, n, r) {
				var o = wu(e, t.row(), n, r.replaceOrInit);
				return Qu(o, t.row(), t.column())
			}, Ui, y, y, Fo(null, "td")),
			mergeCells: zi(function(e, t, n, r) {
				var o = t.cells();
				Qo(o);
				var i = ou(e, t.bounds(), n, C(o[0]));
				return Ku(i, R.from(o[0]))
			}, Yi, y, y, zo),
			unmergeCells: zi(function(e, t, n, r) {
				var o = I(t, function(e, t) {
					return iu(e, t, n, r.combine(t))
				}, e);
				return Ku(o, R.from(t[0]))
			}, Xi, y, y, zo),
			pasteCells: zi(function(e, n, t, r) {
				var o, i, u, a, c = (o = n.clipboard(), i = n.generators(), u = Yt(o), a = $t.generate(u), li(a, i, !0)),
				l = ae.address(n.row(), n.column());
				return au(l, e, c, n.generators(), t).fold(function() {
					return Ku(e, R.some(n.element()))
				}, function(e) {
					var t = Ju(e, n.row(), n.column());
					return Ku(e, t)
				})
			}, Vi, na, y, jo),
			pasteRowsBefore: zi(function(e, t, n, r) {
				var o = e[t.cells[0].row()],
				i = t.cells[0].row(),
				u = ta(t.clipboard(), t.generators(), o),
				a = cu(i, e, u, t.generators(), n),
				c = Ju(a, t.cells[0].row(), t.cells[0].column());
				return Ku(a, c)
			}, Gi, y, y, jo),
			pasteRowsAfter: zi(function(e, t, n, r) {
				var o = e[t.cells[0].row()],
				i = t.cells[t.cells.length - 1].row() + t.cells[t.cells.length - 1].rowspan(),
				u = ta(t.clipboard(), t.generators(), o),
				a = cu(i, e, u, t.generators(), n),
				c = Ju(a, t.cells[0].row(), t.cells[0].column());
				return Ku(a, c)
			}, Gi, y, y, jo)
	},
	oa = function(e) {
		return le.fromDom(e.getBody())
	},
	ia = function(e) {
		return e.getBoundingClientRect().width
	},
	ua = function(e) {
		return e.getBoundingClientRect().height
	},
	aa = function(t) {
		return function(e) {
			return $e(e, oa(t))
		}
	},
	ca = function(e) {
		return /^[0-9]+$/.test(e) && (e += "px"), e
	},
	la = function(e) {
		var t = Nt(e, "td[data-mce-style],th[data-mce-style]");
		xt(e, "data-mce-style"), A(t, function(e) {
			xt(e, "data-mce-style")
		})
	},
	fa = {
			isRtl: C(!1)
	},
	sa = {
			isRtl: C(!0)
	},
	da = {
			directionAt: function(e) {
				return "rtl" == ("rtl" === nn(e, "direction") ? "rtl" : "ltr") ? sa : fa
			}
	},
	ma = ["tableprops", "tabledelete", "|", "tableinsertrowbefore", "tableinsertrowafter", "tabledeleterow", "|", "tableinsertcolbefore", "tableinsertcolafter", "tabledeletecol"],
	ga = {
			"border-collapse": "collapse",
			width: "100%"
	},
	ha = {
			border: "1"
	},
	pa = function(e) {
		return e.getParam("table_cell_advtab", !0, "boolean")
	},
	va = function(e) {
		return e.getParam("table_row_advtab", !0, "boolean")
	},
	ba = function(e) {
		return e.getParam("table_advtab", !0, "boolean")
	},
	wa = function(e) {
		return e.getParam("table_style_by_css", !1, "boolean")
	},
	ya = function(e) {
		return e.getParam("table_cell_class_list", [], "array")
	},
	xa = function(e) {
		return e.getParam("table_row_class_list", [], "array")
	},
	Ca = function(e) {
		return e.getParam("table_class_list", [], "array")
	},
	Ra = function(e) {
		return !1 === e.getParam("table_responsive_width")
	},
	Sa = function(e, t) {
		return e.fire("newrow", {
			node: t
		})
	},
	Ta = function(e, t) {
		return e.fire("newcell", {
			node: t
		})
	},
	Da = function(e, t, n, r) {
		e.fire("ObjectResizeStart", {
			target: t,
			width: n,
			height: r
		})
	},
	Oa = function(e, t, n, r) {
		e.fire("ObjectResized", {
			target: t,
			width: n,
			height: r
		})
	},
	ka = function(f, e) {
		var t, n = function(e) {
			return "table" === lt(oa(e))
		},
		s = (t = f.getParam("table_clone_elements"), v(t) ? R.some(t.split(/[ ,]/)) : Array.isArray(t) ? R.some(t) : R.none()),
		r = function(u, a, c, l) {
			return function(e, t) {
				la(e);
				var n = l(),
				r = le.fromDom(f.getDoc()),
				o = Eo(da.directionAt),
				i = _n(c, r, s);
				return a(e) ? u(n, e, t, i, o).bind(function(e) {
					return A(e.newRows(), function(e) {
						Sa(f, e.dom())
					}), A(e.newCells(), function(e) {
						Ta(f, e.dom())
					}), e.cursor().map(function(e) {
						var t = f.dom.createRng();
						return t.setStart(e.dom(), 0), t.setEnd(e.dom(), 0), t
					})
				}) : R.none()
			}
		};
		return {
			deleteRow: r(ra.eraseRows, function(e) {
				var t = Po(e);
				return !1 === n(f) || 1 < t.rows()
			}, y, e),
			deleteColumn: r(ra.eraseColumns, function(e) {
				var t = Po(e);
				return !1 === n(f) || 1 < t.columns()
			}, y, e),
			insertRowsBefore: r(ra.insertRowsBefore, l, y, e),
			insertRowsAfter: r(ra.insertRowsAfter, l, y, e),
			insertColumnsBefore: r(ra.insertColumnsBefore, l, so, e),
			insertColumnsAfter: r(ra.insertColumnsAfter, l, so, e),
			mergeCells: r(ra.mergeCells, l, y, e),
			unmergeCells: r(ra.unmergeCells, l, y, e),
			pasteRowsBefore: r(ra.pasteRowsBefore, l, y, e),
			pasteRowsAfter: r(ra.pasteRowsAfter, l, y, e),
			pasteCells: r(ra.pasteCells, l, y, e)
		}
	},
	Na = function(e, t, r) {
		var n = Yt(e),
		o = $t.generate(n);
		return qi(o, t).map(function(e) {
			var t = li(o, r, !1).slice(e[0].row(), e[e.length - 1].row() + e[e.length - 1].rowspan()),
			n = Hi(t, r);
			return di(n)
		})
	},
	Ea = tinymce.util.Tools.resolve("tinymce.util.Tools"),
	Aa = function(e, t, n) {
		n && e.formatter.apply("align" + n, {}, t)
	},
	Pa = function(e, t, n) {
		n && e.formatter.apply("valign" + n, {}, t)
	},
	Ia = function(t, n) {
		Ea.each("left center right".split(" "), function(e) {
			t.formatter.remove("align" + e, {}, n)
		})
	},
	Ba = function(t, n) {
		Ea.each("top middle bottom".split(" "), function(e) {
			t.formatter.remove("valign" + e, {}, n)
		})
	},
	Wa = function(o, e, i) {
		var t;
		return t = function(e, t) {
			for (var n = 0; n < t.length; n++) {
				var r = o.getStyle(t[n], i);
				if (void 0 === e && (e = r), e !== r) return ""
			}
			return e
		}(t, o.select("td,th", e))
	},
	Ma = function(e, t) {
		var n = e.dom,
		r = t.control.rootControl,
		o = r.toJSON(),
		i = n.parseStyle(o.style);
		i["border-style"] = o.borderStyle, i["border-color"] = o.borderColor, i["background-color"] = o.backgroundColor, i.width = o.width ? ca(o.width) : "", i.height = o.height ? ca(o.height) : "", r.find("#style").value(n.serializeStyle(n.parseStyle(n.serializeStyle(i))))
	},
	_a = function(e, t) {
		var n = e.dom,
		r = t.control.rootControl,
		o = r.toJSON(),
		i = n.parseStyle(o.style);
		r.find("#borderStyle").value(i["border-style"] || ""), r.find("#borderColor").value(i["border-color"] || ""), r.find("#backgroundColor").value(i["background-color"] || ""), r.find("#width").value(i.width || ""), r.find("#height").value(i.height || "")
	},
	La = {
			createStyleForm: function(n) {
				var e = function() {
					var e = n.getParam("color_picker_callback");
					if (e) return function(t) {
						return e.call(n, function(e) {
							t.control.value(e).fire("change")
						}, t.control.value())
					}
				};
				return {
					title: "Advanced",
					type: "form",
					defaults: {
						onchange: b(Ma, n)
					},
					items: [{
						label: "Style",
						name: "style",
						type: "textbox",
						onchange: b(_a, n)
					}, {
						type: "form",
						padding: 0,
						formItemDefaults: {
							layout: "grid",
							alignH: ["start", "right"]
						},
						defaults: {
							size: 7
						},
						items: [{
							label: "Border style",
							type: "listbox",
							name: "borderStyle",
							width: 90,
							onselect: b(Ma, n),
							values: [{
								text: "Select...",
								value: ""
							}, {
								text: "Solid",
								value: "solid"
							}, {
								text: "Dotted",
								value: "dotted"
							}, {
								text: "Dashed",
								value: "dashed"
							}, {
								text: "Double",
								value: "double"
							}, {
								text: "Groove",
								value: "groove"
							}, {
								text: "Ridge",
								value: "ridge"
							}, {
								text: "Inset",
								value: "inset"
							}, {
								text: "Outset",
								value: "outset"
							}, {
								text: "None",
								value: "none"
							}, {
								text: "Hidden",
								value: "hidden"
							}]
						}, {
							label: "Border color",
							type: "colorbox",
							name: "borderColor",
							onaction: e()
						}, {
							label: "Background color",
							type: "colorbox",
							name: "backgroundColor",
							onaction: e()
						}]
					}]
				}
			},
			buildListItems: function(e, r, t) {
				var o = function(e, n) {
					return n = n || [], Ea.each(e, function(e) {
						var t = {
								text: e.text || e.title
						};
						e.menu ? t.menu = o(e.menu) : (t.value = e.value, r && r(t)), n.push(t)
					}), n
				};
				return o(e, t || [])
			},
			updateStyleField: Ma,
			extractAdvancedStyles: function(e, t) {
				var n = e.parseStyle(e.getAttrib(t, "style")),
				r = {};
				return n["border-style"] && (r.borderStyle = n["border-style"]), n["border-color"] && (r.borderColor = n["border-color"]), n["background-color"] && (r.backgroundColor = n["background-color"]), r.style = e.serializeStyle(n), r
			},
			updateAdvancedFields: _a,
			syncAdvancedStyleFields: function(e, t) {
				t.control.rootControl.find("#style")[0].getEl().isEqualNode(document.activeElement) ? _a(e, t) : Ma(e, t)
			}
	},
	ja = function(r, o, e) {
		var i, u = r.dom;

		function a(e, t, n) {
			(1 === o.length || n) && u.setAttrib(e, t, n)
		}

		function c(e, t, n) {
			(1 === o.length || n) && u.setStyle(e, t, n)
		}
		pa(r) && La.syncAdvancedStyleFields(r, e), i = e.control.rootControl.toJSON(), r.undoManager.transact(function() {
			Ea.each(o, function(e) {
				var t, n;
				a(e, "scope", i.scope), 1 === o.length ? a(e, "style", i.style) : (t = e, n = i.style, delete t.dataset.mceStyle, t.style.cssText += ";" + n), a(e, "class", i["class"]), c(e, "width", ca(i.width)), c(e, "height", ca(i.height)), i.type && e.nodeName.toLowerCase() !== i.type && (e = u.rename(e, i.type)), 1 === o.length && (Ia(r, e), Ba(r, e)), i.align && Aa(r, e, i.align), i.valign && Pa(r, e, i.valign)
			}), r.focus()
		})
	},
	Fa = function(t) {
		var e, n, r, o = [];
		if (o = t.dom.select("td[data-mce-selected],th[data-mce-selected]"), e = t.dom.getParent(t.selection.getStart(), "td,th"), !o.length && e && o.push(e), e = e || o[0]) {
			var i, u, a, c;
			1 < o.length ? n = {
					width: "",
					height: "",
					scope: "",
					"class": "",
					align: "",
					valign: "",
					style: "",
					type: e.nodeName.toLowerCase()
			} : (u = e, a = (i = t).dom, c = {
				width: a.getStyle(u, "width") || a.getAttrib(u, "width"),
				height: a.getStyle(u, "height") || a.getAttrib(u, "height"),
				scope: a.getAttrib(u, "scope"),
				"class": a.getAttrib(u, "class"),
				type: u.nodeName.toLowerCase(),
				style: "",
				align: "",
				valign: ""
			}, Ea.each("left center right".split(" "), function(e) {
				i.formatter.matchNode(u, "align" + e) && (c.align = e)
			}), Ea.each("top middle bottom".split(" "), function(e) {
				i.formatter.matchNode(u, "valign" + e) && (c.valign = e)
			}), pa(i) && Ea.extend(c, La.extractAdvancedStyles(a, u)), n = c), 0 < ya(t).length && (r = {
					name: "class",
					type: "listbox",
					label: "Class",
					values: La.buildListItems(ya(t), function(e) {
						e.value && (e.textStyle = function() {
							return t.formatter.getCssText({
								block: "td",
								classes: [e.value]
							})
						})
					})
			});
			var l = {
					type: "form",
					layout: "flex",
					direction: "column",
					labelGapCalc: "children",
					padding: 0,
					items: [{
						type: "form",
						layout: "grid",
						columns: 2,
						labelGapCalc: !1,
						padding: 0,
						defaults: {
							type: "textbox",
							maxWidth: 50
						},
						items: [{
							label: "Width",
							name: "width",
							onchange: b(La.updateStyleField, t)
						}, {
							label: "Height",
							name: "height",
							onchange: b(La.updateStyleField, t)
						}, {
							label: "Cell type",
							name: "type",
							type: "listbox",
							text: "None",
							minWidth: 90,
							maxWidth: null,
							values: [{
								text: "Cell",
								value: "td"
							}, {
								text: "Header cell",
								value: "th"
							}]
						}, {
							label: "Scope",
							name: "scope",
							type: "listbox",
							text: "None",
							minWidth: 90,
							maxWidth: null,
							values: [{
								text: "None",
								value: ""
							}, {
								text: "Row",
								value: "row"
							}, {
								text: "Column",
								value: "col"
							}, {
								text: "Row group",
								value: "rowgroup"
							}, {
								text: "Column group",
								value: "colgroup"
							}]
						}, {
							label: "H Align",
							name: "align",
							type: "listbox",
							text: "None",
							minWidth: 90,
							maxWidth: null,
							values: [{
								text: "None",
								value: ""
							}, {
								text: "Left",
								value: "left"
							}, {
								text: "Center",
								value: "center"
							}, {
								text: "Right",
								value: "right"
							}]
						}, {
							label: "V Align",
							name: "valign",
							type: "listbox",
							text: "None",
							minWidth: 90,
							maxWidth: null,
							values: [{
								text: "None",
								value: ""
							}, {
								text: "Top",
								value: "top"
							}, {
								text: "Middle",
								value: "middle"
							}, {
								text: "Bottom",
								value: "bottom"
							}]
						}]
					}, r]
			};
			pa(t) ? t.windowManager.open({
				title: "Cell properties",
				bodyType: "tabpanel",
				data: n,
				body: [{
					title: "General",
					type: "form",
					items: l
				}, La.createStyleForm(t)],
				onsubmit: b(ja, t, o)
			}) : t.windowManager.open({
				title: "Cell properties",
				data: n,
				body: l,
				onsubmit: b(ja, t, o)
			})
		}
	};

	function za(f, s, d, e) {
		var m = f.dom;

		function g(e, t, n) {
			(1 === s.length || n) && m.setAttrib(e, t, n)
		}
		va(f) && La.syncAdvancedStyleFields(f, e);
		var h = e.control.rootControl.toJSON();
		f.undoManager.transact(function() {
			Ea.each(s, function(e) {
				var t, n, r, o, i, u, a, c, l;
				g(e, "scope", h.scope), g(e, "style", h.style), g(e, "class", h["class"]), t = e, n = "height", r = ca(h.height), (1 === s.length || r) && m.setStyle(t, n, r), h.type !== e.parentNode.nodeName.toLowerCase() && (o = f.dom, i = e, u = h.type, a = o.getParent(i, "table"), c = i.parentNode, (l = o.select(u, a)[0]) || (l = o.create(u), a.firstChild ? "CAPTION" === a.firstChild.nodeName ? o.insertAfter(l, a.firstChild) : a.insertBefore(l, a.firstChild) : a.appendChild(l)), l.appendChild(i), c.hasChildNodes() || o.remove(c)), h.align !== d.align && (Ia(f, e), Aa(f, e, h.align))
			}), f.focus()
		})
	}
	var Ha = function(t) {
		var e, n, r, o, i, u, a, c, l, f, s = t.dom,
		d = [];
		e = s.getParent(t.selection.getStart(), "table"), n = s.getParent(t.selection.getStart(), "td,th"), Ea.each(e.rows, function(t) {
			Ea.each(t.cells, function(e) {
				if (s.getAttrib(e, "data-mce-selected") || e === n) return d.push(t), !1
			})
		}), (r = d[0]) && (1 < d.length ? i = {
				height: "",
				scope: "",
				style: "",
				"class": "",
				align: "",
				type: r.parentNode.nodeName.toLowerCase()
		} : (c = r, l = (a = t).dom, f = {
			height: l.getStyle(c, "height") || l.getAttrib(c, "height"),
			scope: l.getAttrib(c, "scope"),
			"class": l.getAttrib(c, "class"),
			align: "",
			style: "",
			type: c.parentNode.nodeName.toLowerCase()
		}, Ea.each("left center right".split(" "), function(e) {
			a.formatter.matchNode(c, "align" + e) && (f.align = e)
		}), va(a) && Ea.extend(f, La.extractAdvancedStyles(l, c)), i = f), 0 < xa(t).length && (o = {
				name: "class",
				type: "listbox",
				label: "Class",
				values: La.buildListItems(xa(t), function(e) {
					e.value && (e.textStyle = function() {
						return t.formatter.getCssText({
							block: "tr",
							classes: [e.value]
						})
					})
				})
		}), u = {
				type: "form",
				columns: 2,
				padding: 0,
				defaults: {
					type: "textbox"
				},
				items: [{
					type: "listbox",
					name: "type",
					label: "Row type",
					text: "Header",
					maxWidth: null,
					values: [{
						text: "Header",
						value: "thead"
					}, {
						text: "Body",
						value: "tbody"
					}, {
						text: "Footer",
						value: "tfoot"
					}]
				}, {
					type: "listbox",
					name: "align",
					label: "Alignment",
					text: "None",
					maxWidth: null,
					values: [{
						text: "None",
						value: ""
					}, {
						text: "Left",
						value: "left"
					}, {
						text: "Center",
						value: "center"
					}, {
						text: "Right",
						value: "right"
					}]
				}, {
					label: "Height",
					name: "height"
				}, o]
		}, va(t) ? t.windowManager.open({
			title: "Row properties",
			data: i,
			bodyType: "tabpanel",
			body: [{
				title: "General",
				type: "form",
				items: u
			}, La.createStyleForm(t)],
			onsubmit: b(za, t, d, i)
		}) : t.windowManager.open({
			title: "Row properties",
			data: i,
			body: u,
			onsubmit: b(za, t, d, i)
		}))
	},
	Ua = tinymce.util.Tools.resolve("tinymce.Env"),
	qa = {
		styles: {
			"border-collapse": "collapse",
			width: "100%"
		},
		attributes: {
			border: "1"
		},
		percentages: !0
	},
	Va = function(e, t, n, r, o) {
		void 0 === o && (o = qa);
		var i = le.fromTag("table");
		tn(i, o.styles), bt(i, o.attributes);
		var u = le.fromTag("tbody");
		fn(i, u);
		for (var a = [], c = 0; c < e; c++) {
			for (var l = le.fromTag("tr"), f = 0; f < t; f++) {
				var s = c < n || f < r ? le.fromTag("th") : le.fromTag("td");
				f < r && vt(s, "scope", "row"), c < n && vt(s, "scope", "col"), fn(s, le.fromTag("br")), o.percentages && en(s, "width", 100 / t + "%"), fn(l, s)
			}
			a.push(l)
		}
		return mn(u, a), i
	},
	Ga = function(e, t) {
		e.selection.select(t.dom(), !0), e.selection.collapse(!0)
	},
	Ya = function(r, e, t) {
		var n, o, i = r.getParam("table_default_styles", ga, "object"),
		u = {
			styles: i,
			attributes: (o = r, o.getParam("table_default_attributes", ha, "object")),
			percentages: (n = i.width, v(n) && -1 !== n.indexOf("%") && !Ra(r))
		},
		a = Va(t, e, 0, 0, u);
		vt(a, "data-mce-id", "__mce");
		var c, l, f, s = (c = a, l = le.fromTag("div"), f = le.fromDom(c.dom().cloneNode(!0)), fn(l, f), l.dom().innerHTML);
		return r.insertContent(s), jt(oa(r), 'table[data-mce-id="__mce"]').map(function(e) {
			var t, n;
			return Ra(r) && en(e, "width", nn(e, "width")), xt(e, "data-mce-id"), t = r, A(Nt(e, "tr"), function(e) {
				Sa(t, e.dom()), A(Nt(e, "th,td"), function(e) {
					Ta(t, e.dom())
				})
			}), n = r, jt(e, "td,th").each(b(Ga, n)), e.dom()
		}).getOr(null)
	};

	function Xa(e, t, n, r) {
		if ("TD" === t.tagName || "TH" === t.tagName) e.setStyle(t, n, r);
		else if (t.children)
			for (var o = 0; o < t.children.length; o++) Xa(e, t.children[o], n, r)
	}
	var Ka = function(e, t, n) {
		var r, o, i = e.dom;
		ba(e) && La.syncAdvancedStyleFields(e, n), !1 === (o = n.control.rootControl.toJSON())["class"] && delete o["class"], e.undoManager.transact(function() {
			t || (t = Ya(e, o.cols || 1, o.rows || 1)),
			function(e, t, n) {
				var r, o = e.dom,
				i = {},
				u = {};
				if (i["class"] = n["class"], u.height = ca(n.height), o.getAttrib(t, "width") && !wa(e) ? i.width = (r = n.width) ? r.replace(/px$/, "") : "" : u.width = ca(n.width), wa(e) ? (u["border-width"] = ca(n.border), u["border-spacing"] = ca(n.cellspacing), Ea.extend(i, {
					"data-mce-border-color": n.borderColor,
					"data-mce-cell-padding": n.cellpadding,
					"data-mce-border": n.border
				})) : Ea.extend(i, {
					border: n.border,
					cellpadding: n.cellpadding,
					cellspacing: n.cellspacing
				}), wa(e) && t.children)
					for (var a = 0; a < t.children.length; a++) Xa(o, t.children[a], {
						"border-width": ca(n.border),
						"border-color": n.borderColor,
						padding: ca(n.cellpadding)
					});
				n.style ? Ea.extend(u, o.parseStyle(n.style)) : u = Ea.extend({}, o.parseStyle(o.getAttrib(t, "style")), u), i.style = o.serializeStyle(u), o.setAttribs(t, i)
			}(e, t, o), (r = i.select("caption", t)[0]) && !o.caption && i.remove(r), !r && o.caption && ((r = i.create("caption")).innerHTML = Ua.ie ? "\xa0" : '<br data-mce-bogus="1"/>', t.insertBefore(r, t.firstChild)), Ia(e, t), o.align && Aa(e, t, o.align), e.focus(), e.addVisual()
		})
	},
	Ja = function(t, e) {
		var n, r, o, i, u, a, c, l, f, s, d = t.dom,
		m = {};
		!0 === e ? (n = d.getParent(t.selection.getStart(), "table")) && (c = n, l = (a = t).dom, f = {
			width: l.getStyle(c, "width") || l.getAttrib(c, "width"),
			height: l.getStyle(c, "height") || l.getAttrib(c, "height"),
			cellspacing: l.getStyle(c, "border-spacing") || l.getAttrib(c, "cellspacing"),
			cellpadding: l.getAttrib(c, "data-mce-cell-padding") || l.getAttrib(c, "cellpadding") || Wa(a.dom, c, "padding"),
			border: l.getAttrib(c, "data-mce-border") || l.getAttrib(c, "border") || Wa(a.dom, c, "border"),
			borderColor: l.getAttrib(c, "data-mce-border-color"),
			caption: !!l.select("caption", c)[0],
			"class": l.getAttrib(c, "class")
		}, Ea.each("left center right".split(" "), function(e) {
			a.formatter.matchNode(c, "align" + e) && (f.align = e)
		}), ba(a) && Ea.extend(f, La.extractAdvancedStyles(l, c)), m = f) : (r = {
				label: "Cols",
				name: "cols"
		}, o = {
				label: "Rows",
				name: "rows"
		}), 0 < Ca(t).length && (m["class"] && (m["class"] = m["class"].replace(/\s*mce\-item\-table\s*/g, "")), i = {
			name: "class",
			type: "listbox",
			label: "Class",
			values: La.buildListItems(Ca(t), function(e) {
				e.value && (e.textStyle = function() {
					return t.formatter.getCssText({
						block: "table",
						classes: [e.value]
					})
				})
			})
		}), u = {
				type: "form",
				layout: "flex",
				direction: "column",
				labelGapCalc: "children",
				padding: 0,
				items: [{
					type: "form",
					labelGapCalc: !1,
					padding: 0,
					layout: "grid",
					columns: 2,
					defaults: {
						type: "textbox",
						maxWidth: 50
					},
					items: (s = t, s.getParam("table_appearance_options", !0, "boolean") ? [r, o, {
						label: "Width",
						name: "width",
						onchange: b(La.updateStyleField, t)
					}, {
						label: "Height",
						name: "height",
						onchange: b(La.updateStyleField, t)
					}, {
						label: "Cell spacing",
						name: "cellspacing"
					}, {
						label: "Cell padding",
						name: "cellpadding"
					}, {
						label: "Border",
						name: "border"
					}, {
						label: "Caption",
						name: "caption",
						type: "checkbox"
					}] : [r, o, {
						label: "Width",
						name: "width",
						onchange: b(La.updateStyleField, t)
					}, {
						label: "Height",
						name: "height",
						onchange: b(La.updateStyleField, t)
					}])
				}, {
					label: "Alignment",
					name: "align",
					type: "listbox",
					text: "None",
					values: [{
						text: "None",
						value: ""
					}, {
						text: "Left",
						value: "left"
					}, {
						text: "Center",
						value: "center"
					}, {
						text: "Right",
						value: "right"
					}]
				}, i]
		}, ba(t) ? t.windowManager.open({
			title: "Table properties",
			data: m,
			bodyType: "tabpanel",
			body: [{
				title: "General",
				type: "form",
				items: u
			}, La.createStyleForm(t)],
			onsubmit: b(Ka, t, n)
		}) : t.windowManager.open({
			title: "Table properties",
			data: m,
			body: u,
			onsubmit: b(Ka, t, n)
		})
	},
	$a = Ea.each,
	Qa = function(a, t, c, l, n) {
		var r = aa(a),
		f = function() {
			return le.fromDom(a.dom.getParent(a.selection.getStart(), "th,td"))
		},
		s = function(e) {
			return Gt.table(e, r)
		},
		d = function(e) {
			return {
				width: ia(e.dom()),
				height: ia(e.dom())
			}
		},
		o = function(t) {
			var n = f();
			s(n).each(function(i) {
				var e = Ur.forMenu(l, i, n),
				u = d(i);
				t(i, e).each(function(e) {
					var t, n, r, o;
					t = a, n = u, o = d(r = i), n.width === o.width && n.height === o.height || (Da(t, r.dom(), n.width, n.height), Oa(t, r.dom(), o.width, o.height)), a.selection.setRng(e), a.focus(), c.clear(i), la(i)
				})
			})
		},
		i = function(e) {
			var o = f();
			return s(o).bind(function(e) {
				var t = le.fromDom(a.getDoc()),
				n = Ur.forMenu(l, e, o),
				r = _n(y, t, R.none());
				return Na(e, n, r)
			})
		},
		u = function(u) {
			n.get().each(function(e) {
				var o = E(e, function(e) {
					return xn(e)
				}),
				i = f();
				s(i).bind(function(t) {
					var e = le.fromDom(a.getDoc()),
					n = Ln(e),
					r = Ur.pasteRows(l, t, i, o, n);
					u(t, r).each(function(e) {
						a.selection.setRng(e), a.focus(), c.clear(t)
					})
				})
			})
		};
		$a({
			mceTableSplitCells: function() {
				o(t.unmergeCells)
			},
			mceTableMergeCells: function() {
				o(t.mergeCells)
			},
			mceTableInsertRowBefore: function() {
				o(t.insertRowsBefore)
			},
			mceTableInsertRowAfter: function() {
				o(t.insertRowsAfter)
			},
			mceTableInsertColBefore: function() {
				o(t.insertColumnsBefore)
			},
			mceTableInsertColAfter: function() {
				o(t.insertColumnsAfter)
			},
			mceTableDeleteCol: function() {
				o(t.deleteColumn)
			},
			mceTableDeleteRow: function() {
				o(t.deleteRow)
			},
			mceTableCutRow: function(e) {
				n.set(i()), o(t.deleteRow)
			},
			mceTableCopyRow: function(e) {
				n.set(i())
			},
			mceTablePasteRowBefore: function(e) {
				u(t.pasteRowsBefore)
			},
			mceTablePasteRowAfter: function(e) {
				u(t.pasteRowsAfter)
			},
			mceTableDelete: function() {
				var e = le.fromDom(a.dom.getParent(a.selection.getStart(), "th,td"));
				Gt.table(e, r).filter(m(r)).each(function(e) {
					var t = le.fromText("");
					cn(e, t), hn(e);
					var n = a.dom.createRng();
					n.setStart(t.dom(), 0), n.setEnd(t.dom(), 0), a.selection.setRng(n)
				})
			}
		}, function(e, t) {
			a.addCommand(t, e)
		}), $a({
			mceInsertTable: b(Ja, a),
			mceTableProps: b(Ja, a, !0),
			mceTableRowProps: b(Ha, a),
			mceTableCellProps: b(Fa, a)
		}, function(n, e) {
			a.addCommand(e, function(e, t) {
				n(t)
			})
		})
	},
	Za = function(e) {
		var t = R.from(e.dom().documentElement).map(le.fromDom).getOr(e);
		return {
			parent: C(t),
			view: C(e),
			origin: C(go(0, 0))
		}
	},
	ec = function(e, t) {
		return {
			parent: C(t),
			view: C(e),
			origin: C(go(0, 0))
		}
	};

	function tc(e) {
		var n = X.apply(null, e),
		r = [];
		return {
			bind: function(e) {
				if (e === undefined) throw "Event bind error: undefined handler";
				r.push(e)
			},
			unbind: function(t) {
				r = P(r, function(e) {
					return e !== t
				})
			},
			trigger: function() {
				var t = n.apply(null, arguments);
				A(r, function(e) {
					e(t)
				})
			}
		}
	}
	var nc = {
			create: function(e) {
				return {
					registry: G(e, function(e) {
						return {
							bind: e.bind,
							unbind: e.unbind
						}
					}),
					trigger: G(e, function(e) {
						return e.trigger
					})
				}
			}
	},
	rc = {
			mode: Mo(["compare", "extract", "mutate", "sink"]),
			sink: Mo(["element", "start", "stop", "destroy"]),
			api: Mo(["forceDrop", "drop", "move", "delayDrop"])
	},
	oc = {
			resolve: xi("ephox-dragster").resolve
	},
	ic = function(m, g) {
		return function(e) {
			if (m(e)) {
				var t, n, r, o, i, u, a, c = le.fromDom(e.target),
				l = function() {
					e.stopPropagation()
				},
				f = function() {
					e.preventDefault()
				},
				s = x(f, l),
				d = (t = c, n = e.clientX, r = e.clientY, o = l, i = f, u = s, a = e, {
					target: C(t),
					x: C(n),
					y: C(r),
					stop: o,
					prevent: i,
					kill: u,
					raw: C(a)
				});
				g(d)
			}
		}
	},
	uc = function(e, t, n, r) {
		return o = e, i = t, u = !1, a = ic(n, r), o.dom().addEventListener(i, a, u), {
			unbind: b(ac, o, i, a, u)
		};
		var o, i, u, a
	},
	ac = function(e, t, n, r) {
		e.dom().removeEventListener(t, n, r)
	},
	cc = C(!0),
	lc = function(e, t, n) {
		return uc(e, t, cc, n)
	},
	fc = rc.mode({
		compare: function(e, t) {
			return go(t.left() - e.left(), t.top() - e.top())
		},
		extract: function(e) {
			return R.some(go(e.x(), e.y()))
		},
		sink: function(e, t) {
			var n, r, o, i = (n = t, r = ei({
				layerClass: oc.resolve("blocker")
			}, n), o = le.fromTag("div"), vt(o, "role", "presentation"), tn(o, {
				position: "fixed",
				left: "0px",
				top: "0px",
				width: "100%",
				height: "100%"
			}), Oi(o, oc.resolve("blocker")), Oi(o, r.layerClass), {
				element: function() {
					return o
				},
				destroy: function() {
					hn(o)
				}
			}),
			u = lc(i.element(), "mousedown", e.forceDrop),
			a = lc(i.element(), "mouseup", e.drop),
			c = lc(i.element(), "mousemove", e.move),
			l = lc(i.element(), "mouseout", e.delayDrop);
			return rc.sink({
				element: i.element,
				start: function(e) {
					fn(e, i.element())
				},
				stop: function() {
					hn(i.element())
				},
				destroy: function() {
					i.destroy(), a.unbind(), c.unbind(), l.unbind(), u.unbind()
				}
			})
		},
		mutate: function(e, t) {
			e.mutate(t.left(), t.top())
		}
	});

	function sc() {
		var i = R.none(),
		u = nc.create({
			move: tc(["info"])
		});
		return {
			onEvent: function(e, o) {
				o.extract(e).each(function(e) {
					var t, n, r;
					(t = o, n = e, r = i.map(function(e) {
						return t.compare(e, n)
					}), i = R.some(n), r).each(function(e) {
						u.trigger.move(e)
					})
				})
			},
			reset: function() {
				i = R.none()
			},
			events: u.registry
		}
	}

	function dc() {
		var e = {
				onEvent: function(e, t) {},
				reset: y
		},
		t = sc(),
		n = e;
		return {
			on: function() {
				n.reset(), n = t
			},
			off: function() {
				n.reset(), n = e
			},
			isOn: function() {
				return n === t
			},
			onEvent: function(e, t) {
				n.onEvent(e, t)
			},
			events: t.events
		}
	}
	var mc = function(t, n, e) {
		var r, o, i, u = !1,
		a = nc.create({
			start: tc([]),
			stop: tc([])
		}),
		c = dc(),
		l = function() {
			d.stop(), c.isOn() && (c.off(), a.trigger.stop())
		},
		f = (r = l, o = 200, i = null, {
			cancel: function() {
				null !== i && (clearTimeout(i), i = null)
			},
			throttle: function() {
				for (var e = [], t = 0; t < arguments.length; t++) e[t] = arguments[t];
				null !== i && clearTimeout(i), i = setTimeout(function() {
					r.apply(null, e), i = null
				}, o)
			}
		});
		c.events.move.bind(function(e) {
			n.mutate(t, e.info())
		});
		var s = function(t) {
			return function() {
				var e = Array.prototype.slice.call(arguments, 0);
				if (u) return t.apply(null, e)
			}
		},
		d = n.sink(rc.api({
			forceDrop: l,
			drop: s(l),
			move: s(function(e, t) {
				f.cancel(), c.onEvent(e, n)
			}),
			delayDrop: s(f.throttle)
		}), e);
		return {
			element: d.element,
			go: function(e) {
				d.start(e), c.on(), a.trigger.start()
			},
			on: function() {
				u = !0
			},
			off: function() {
				u = !1
			},
			destroy: function() {
				d.destroy()
			},
			events: a.registry
		}
	},
	gc = {
			transform: function(e, t) {
				var n = t !== undefined ? t : {},
						r = n.mode !== undefined ? n.mode : fc;
				return mc(e, r, t)
			}
	};

	function hc() {
		var n, r = nc.create({
			drag: tc(["xDelta", "yDelta", "target"])
		}),
		o = R.none(),
		e = {
			mutate: function(e, t) {
				n.trigger.drag(e, t)
			},
			events: (n = nc.create({
				drag: tc(["xDelta", "yDelta"])
			})).registry
		};
		return e.events.drag.bind(function(t) {
			o.each(function(e) {
				r.trigger.drag(t.xDelta(), t.yDelta(), e)
			})
		}), {
			assign: function(e) {
				o = R.some(e)
			},
			get: function() {
				return o
			},
			mutate: e.mutate,
			events: r.registry
		}
	}
	var pc = function(e, t, n) {
		return Ft(e, t, n).isSome()
	},
	vc = Ci.resolve("resizer-bar-dragging");

	function bc(e, n) {
		var r = ko.height,
		t = function(o, t, i) {
			var n = hc(),
			r = gc.transform(n, {}),
			u = R.none(),
			e = function(e, t) {
				return R.from(wt(e, t))
			};
			n.events.drag.bind(function(n) {
				e(n.target(), "data-row").each(function(e) {
					var t = ku.getInt(n.target(), "top");
					en(n.target(), "top", t + n.yDelta() + "px")
				}), e(n.target(), "data-column").each(function(e) {
					var t = ku.getInt(n.target(), "left");
					en(n.target(), "left", t + n.xDelta() + "px")
				})
			});
			var a = function(e, t) {
				return ku.getInt(e, t) - parseInt(wt(e, "data-initial-" + t), 10)
			};
			r.events.stop.bind(function() {
				n.get().each(function(r) {
					u.each(function(n) {
						e(r, "data-row").each(function(e) {
							var t = a(r, "top");
							xt(r, "data-initial-top"), d.trigger.adjustHeight(n, t, parseInt(e, 10))
						}), e(r, "data-column").each(function(e) {
							var t = a(r, "left");
							xt(r, "data-initial-left"), d.trigger.adjustWidth(n, t, parseInt(e, 10))
						}), _i.refresh(o, n, i, t)
					})
				})
			});
			var c = function(e, t) {
				d.trigger.startAdjust(), n.assign(e), vt(e, "data-initial-" + t, parseInt(nn(e, t), 10)), Oi(e, vc), en(e, "opacity", "0.2"), r.go(o.parent())
			},
			l = lc(o.parent(), "mousedown", function(e) {
				_i.isRowBar(e.target()) && c(e.target(), "top"), _i.isColBar(e.target()) && c(e.target(), "left")
			}),
			f = function(e) {
				return $e(e, o.view())
			},
			s = lc(o.view(), "mouseover", function(e) {
				"table" === lt(e.target()) || pc(e.target(), "table", f) ? (u = "table" === lt(e.target()) ? R.some(e.target()) : _t(e.target(), "table", f)).each(function(e) {
					_i.refresh(o, e, i, t)
				}) : Rt(e.target()) && _i.destroy(o)
			}),
			d = nc.create({
				adjustHeight: tc(["table", "delta", "row"]),
				adjustWidth: tc(["table", "delta", "column"]),
				startAdjust: tc([])
			});
			return {
				destroy: function() {
					l.unbind(), s.unbind(), r.destroy(), _i.destroy(o)
				},
				refresh: function(e) {
					_i.refresh(o, e, i, t)
				},
				on: r.on,
				off: r.off,
				hideBars: b(_i.hide, o),
				showBars: b(_i.show, o),
				events: d.registry
			}
		}(e, n, r),
		o = nc.create({
			beforeResize: tc(["table"]),
			afterResize: tc(["table"]),
			startDrag: tc([])
		});
		return t.events.adjustHeight.bind(function(e) {
			o.trigger.beforeResize(e.table());
			var t = r.delta(e.delta(), e.table());
			Gu(e.table(), t, e.row(), r), o.trigger.afterResize(e.table())
		}), t.events.startAdjust.bind(function(e) {
			o.trigger.startDrag()
		}), t.events.adjustWidth.bind(function(e) {
			o.trigger.beforeResize(e.table());
			var t = n.delta(e.delta(), e.table());
			Vu(e.table(), t, e.column(), n), o.trigger.afterResize(e.table())
		}), {
			on: t.on,
			off: t.off,
			hideBars: t.hideBars,
			showBars: t.showBars,
			destroy: t.destroy,
			events: o.registry
		}
	}
	var wc = function(e, t) {
		return e.inline ? ec(oa(e), (n = le.fromTag("div"), tn(n, {
			position: "static",
			height: "0",
			width: "0",
			padding: "0",
			margin: "0",
			border: "0"
		}), fn(St(), n), n)) : Za(le.fromDom(e.getDoc()));
		var n
	},
	yc = function(e, t) {
		e.inline && hn(t.parent())
	},
	xc = function(u) {
		var a, c, o = R.none(),
		i = R.none(),
		l = R.none(),
		f = /(\d+(\.\d+)?)%/,
		s = function(e) {
			return "TABLE" === e.nodeName
		};
		return u.on("init", function() {
			var e, t = Eo(da.directionAt),
			n = wc(u);
			if (l = R.some(n), ("table" === (e = u.getParam("object_resizing", !0)) || e) && u.getParam("table_resize_bars", !0, "boolean")) {
				var r = bc(n, t);
				r.on(), r.events.startDrag.bind(function(e) {
					o = R.some(u.selection.getRng())
				}), r.events.beforeResize.bind(function(e) {
					var t = e.table().dom();
					Da(u, t, ia(t), ua(t))
				}), r.events.afterResize.bind(function(e) {
					var t = e.table(),
					n = t.dom();
					la(t), o.each(function(e) {
						u.selection.setRng(e), u.focus()
					}), Oa(u, n, ia(n), ua(n)), u.undoManager.add()
				}), i = R.some(r)
			}
		}), u.on("ObjectResizeStart", function(e) {
			var t, n = e.target;
			s(n) && (a = e.width, t = n, c = u.dom.getStyle(t, "width") || u.dom.getAttrib(t, "width"))
		}), u.on("ObjectResized", function(e) {
			var t = e.target;
			if (s(t)) {
				var n = t;
				if (f.test(c)) {
					var r = parseFloat(f.exec(c)[1]),
					o = e.width * r / a;
					u.dom.setStyle(n, "width", o + "%")
				} else {
					var i = [];
					Ea.each(n.rows, function(e) {
						Ea.each(e.cells, function(e) {
							var t = u.dom.getStyle(e, "width", !0);
							i.push({
								cell: e,
								width: t
							})
						})
					}), Ea.each(i, function(e) {
						u.dom.setStyle(e.cell, "width", e.width), u.dom.setAttrib(e.cell, "width", null)
					})
				}
			}
		}), {
			lazyResize: function() {
				return i
			},
			lazyWire: function() {
				return l.getOr(Za(le.fromDom(u.getBody())))
			},
			destroy: function() {
				i.each(function(e) {
					e.destroy()
				}), l.each(function(e) {
					yc(u, e)
				})
			}
		}
	},
	Cc = function(e) {
		return {
			fold: e
		}
	},
	Rc = function(o) {
		return Cc(function(e, t, n, r) {
			return e(o)
		})
	},
	Sc = function(o) {
		return Cc(function(e, t, n, r) {
			return t(o)
		})
	},
	Tc = function(o, i) {
		return Cc(function(e, t, n, r) {
			return n(o, i)
		})
	},
	Dc = function(o) {
		return Cc(function(e, t, n, r) {
			return r(o)
		})
	},
	Oc = function(n, e) {
		return Gt.table(n, e).bind(function(e) {
			var t = Gt.cells(e);
			return M(t, function(e) {
				return $e(n, e)
			}).map(function(e) {
				return {
					index: C(e),
					all: C(t)
				}
			})
		})
	},
	kc = function(t, e) {
		return Oc(t, e).fold(function() {
			return Rc(t)
		}, function(e) {
			return e.index() + 1 < e.all().length ? Tc(t, e.all()[e.index() + 1]) : Dc(t)
		})
	},
	Nc = function(t, e) {
		return Oc(t, e).fold(function() {
			return Rc()
		}, function(e) {
			return 0 <= e.index() - 1 ? Tc(t, e.all()[e.index() - 1]) : Sc(t)
		})
	},
	Ec = Br([{
		before: ["element"]
	}, {
		on: ["element", "offset"]
	}, {
		after: ["element"]
	}]),
	Ac = {
		before: Ec.before,
		on: Ec.on,
		after: Ec.after,
		cata: function(e, t, n, r) {
			return e.fold(t, n, r)
		},
		getStart: function(e) {
			return e.fold(o, o, o)
		}
	},
	Pc = Br([{
		domRange: ["rng"]
	}, {
		relative: ["startSitu", "finishSitu"]
	}, {
		exact: ["start", "soffset", "finish", "foffset"]
	}]),
	Ic = X("start", "soffset", "finish", "foffset"),
	Bc = function(e) {
		var t, n = e.match({
			domRange: function(e) {
				return le.fromDom(e.startContainer)
			},
			relative: function(e, t) {
				return Ac.getStart(e)
			},
			exact: function(e, t, n, r) {
				return e
			}
		});
		return t = n.dom().ownerDocument.defaultView, le.fromDom(t)
	},
	Wc = (Pc.domRange, Pc.relative),
	Mc = Pc.exact,
	_c = function(e, t, n, r) {
		var o, i, u, a, c, l = (i = t, u = n, a = r, (c = et(o = e).dom().createRange()).setStart(o.dom(), i), c.setEnd(u.dom(), a), c),
		f = $e(e, n) && t === r;
		return l.collapsed && !f
	},
	Lc = function(e, t) {
		e.selectNodeContents(t.dom())
	},
	jc = function(e, t, n) {
		var r, o, i = e.document.createRange();
		return r = i, t.fold(function(e) {
			r.setStartBefore(e.dom())
		}, function(e, t) {
			r.setStart(e.dom(), t)
		}, function(e) {
			r.setStartAfter(e.dom())
		}), o = i, n.fold(function(e) {
			o.setEndBefore(e.dom())
		}, function(e, t) {
			o.setEnd(e.dom(), t)
		}, function(e) {
			o.setEndAfter(e.dom())
		}), i
	},
	Fc = function(e, t, n, r, o) {
		var i = e.document.createRange();
		return i.setStart(t.dom(), n), i.setEnd(r.dom(), o), i
	},
	zc = function(e) {
		return {
			left: C(e.left),
			top: C(e.top),
			right: C(e.right),
			bottom: C(e.bottom),
			width: C(e.width),
			height: C(e.height)
		}
	},
	Hc = Br([{
		ltr: ["start", "soffset", "finish", "foffset"]
	}, {
		rtl: ["start", "soffset", "finish", "foffset"]
	}]),
	Uc = function(e, t, n) {
		return t(le.fromDom(n.startContainer), n.startOffset, le.fromDom(n.endContainer), n.endOffset)
	},
	qc = function(e, t) {
		var o, n, r, i = (o = e, t.match({
			domRange: function(e) {
				return {
					ltr: C(e),
					rtl: R.none
				}
			},
			relative: function(e, t) {
				return {
					ltr: Se(function() {
						return jc(o, e, t)
					}),
					rtl: Se(function() {
						return R.some(jc(o, t, e))
					})
				}
			},
			exact: function(e, t, n, r) {
				return {
					ltr: Se(function() {
						return Fc(o, e, t, n, r)
					}),
					rtl: Se(function() {
						return R.some(Fc(o, n, r, e, t))
					})
				}
			}
		}));
		return (r = (n = i).ltr()).collapsed ? n.rtl().filter(function(e) {
			return !1 === e.collapsed
		}).map(function(e) {
			return Hc.rtl(le.fromDom(e.endContainer), e.endOffset, le.fromDom(e.startContainer), e.startOffset)
		}).getOrThunk(function() {
			return Uc(0, Hc.ltr, r)
		}) : Uc(0, Hc.ltr, r)
	},
	Vc = function(i, e) {
		return qc(i, e).match({
			ltr: function(e, t, n, r) {
				var o = i.document.createRange();
				return o.setStart(e.dom(), t), o.setEnd(n.dom(), r), o
			},
			rtl: function(e, t, n, r) {
				var o = i.document.createRange();
				return o.setStart(n.dom(), r), o.setEnd(e.dom(), t), o
			}
		})
	},
	Gc = (Hc.ltr, Hc.rtl, function(e, t, n) {
		return t >= e.left && t <= e.right && n >= e.top && n <= e.bottom
	}),
	Yc = function(n, r, e, t, o) {
		var i = function(e) {
			var t = n.dom().createRange();
			return t.setStart(r.dom(), e), t.collapse(!0), t
		},
		u = Sn(r).length,
		a = function(e, t, n, r, o) {
			if (0 === o) return 0;
			if (t === r) return o - 1;
			for (var i = r, u = 1; u < o; u++) {
				var a = e(u),
				c = Math.abs(t - a.left);
				if (n <= a.bottom) {
					if (n < a.top || i < c) return u - 1;
					i = c
				}
			}
			return 0
		}(function(e) {
			return i(e).getBoundingClientRect()
		}, e, t, o.right, u);
		return i(a)
	},
	Xc = function(t, n, r, o) {
		var e = t.dom().createRange();
		e.selectNode(n.dom());
		var i = e.getClientRects();
		return ni(i, function(e) {
			return Gc(e, r, o) ? R.some(e) : R.none()
		}).map(function(e) {
			return Yc(t, n, r, o, e)
		})
	},
	Kc = function(t, e, n, r) {
		var o = t.dom().createRange(),
		i = it(e);
		return ni(i, function(e) {
			return o.selectNode(e.dom()), Gc(o.getBoundingClientRect(), n, r) ? Jc(t, e, n, r) : R.none()
		})
	},
	Jc = function(e, t, n, r) {
		return (gt(t) ? Xc : Kc)(e, t, n, r)
	},
	$c = function(e, t) {
		return t - e.left < e.right - t
	},
	Qc = function(e, t, n) {
		var r = e.dom().createRange();
		return r.selectNode(t.dom()), r.collapse(n), r
	},
	Zc = function(t, e, n) {
		var r = t.dom().createRange();
		r.selectNode(e.dom());
		var o = r.getBoundingClientRect(),
		i = $c(o, n);
		return (!0 === i ? En : An)(e).map(function(e) {
			return Qc(t, e, i)
		})
	},
	el = function(e, t, n) {
		var r = t.dom().getBoundingClientRect(),
		o = $c(r, n);
		return R.some(Qc(e, t, o))
	},
	tl = function(e, t, n, r) {
		var o = e.dom().createRange();
		o.selectNode(t.dom());
		var i = o.getBoundingClientRect();
		return function(e, t, n, r) {
			var o = e.dom().createRange();
			o.selectNode(t.dom());
			var i = o.getBoundingClientRect(),
			u = Math.max(i.left, Math.min(i.right, n)),
			a = Math.max(i.top, Math.min(i.bottom, r));
			return Jc(e, t, u, a)
		}(e, t, Math.max(i.left, Math.min(i.right, n)), Math.max(i.top, Math.min(i.bottom, r)))
	},
	nl = document.caretPositionFromPoint ? function(n, e, t) {
		return R.from(n.dom().caretPositionFromPoint(e, t)).bind(function(e) {
			if (null === e.offsetNode) return R.none();
			var t = n.dom().createRange();
			return t.setStart(e.offsetNode, e.offset), t.collapse(), R.some(t)
		})
	} : document.caretRangeFromPoint ? function(e, t, n) {
		return R.from(e.dom().caretRangeFromPoint(t, n))
	} : function(o, i, t) {
		return le.fromPoint(o, i, t).bind(function(r) {
			var e = function() {
				return e = o, n = i, (0 === it(t = r).length ? el : Zc)(e, t, n);
				var e, t, n
			};
			return 0 === it(r).length ? e() : tl(o, r, i, t).orThunk(e)
		})
	},
	rl = function(e, t) {
		var n = lt(e);
		return "input" === n ? Ac.after(e) : k(["br", "img"], n) ? 0 === t ? Ac.before(e) : Ac.after(e) : Ac.on(e, t)
	},
	ol = function(e, t) {
		var n = e.fold(Ac.before, rl, Ac.after),
		r = t.fold(Ac.before, rl, Ac.after);
		return Wc(n, r)
	},
	il = function(e, t, n, r) {
		var o = rl(e, t),
		i = rl(n, r);
		return Wc(o, i)
	},
	ul = function(e, t) {
		R.from(e.getSelection()).each(function(e) {
			e.removeAllRanges(), e.addRange(t)
		})
	},
	al = function(e, t, n, r, o) {
		var i = Fc(e, t, n, r, o);
		ul(e, i)
	},
	cl = function(s, e) {
		return qc(s, e).match({
			ltr: function(e, t, n, r) {
				al(s, e, t, n, r)
			},
			rtl: function(e, t, n, r) {
				var o, i, u, a, c, l = s.getSelection();
				if (l.setBaseAndExtent) l.setBaseAndExtent(e.dom(), t, n.dom(), r);
				else if (l.extend) try {
					i = e, u = t, a = n, c = r, (o = l).collapse(i.dom(), u), o.extend(a.dom(), c)
				} catch (f) {
					al(s, n, r, e, t)
				} else al(s, n, r, e, t)
			}
		})
	},
	ll = function(e) {
		var o = Bc(e).dom(),
		t = function(e, t, n, r) {
			return Fc(o, e, t, n, r)
		},
		n = e.match({
			domRange: function(e) {
				var t = le.fromDom(e.startContainer),
				n = le.fromDom(e.endContainer);
				return il(t, e.startOffset, n, e.endOffset)
			},
			relative: ol,
			exact: il
		});
		return qc(o, n).match({
			ltr: t,
			rtl: t
		})
	},
	fl = function(e) {
		var t = le.fromDom(e.anchorNode),
		n = le.fromDom(e.focusNode);
		return _c(t, e.anchorOffset, n, e.focusOffset) ? R.some(Ic(le.fromDom(e.anchorNode), e.anchorOffset, le.fromDom(e.focusNode), e.focusOffset)) : function(e) {
			if (0 < e.rangeCount) {
				var t = e.getRangeAt(0),
				n = e.getRangeAt(e.rangeCount - 1);
				return R.some(Ic(le.fromDom(t.startContainer), t.startOffset, le.fromDom(n.endContainer), n.endOffset))
			}
			return R.none()
		}(e)
	},
	sl = function(e, t) {
		var n, r, o = (n = t, r = e.document.createRange(), Lc(r, n), r);
		ul(e, o)
	},
	dl = function(e) {
		return (t = e, R.from(t.getSelection()).filter(function(e) {
			return 0 < e.rangeCount
		}).bind(fl)).map(function(e) {
			return Mc(e.start(), e.soffset(), e.finish(), e.foffset())
		});
		var t
	},
	ml = function(e, t) {
		var n, r, o, i = Vc(e, t);
		return r = (n = i).getClientRects(), 0 < (o = 0 < r.length ? r[0] : n.getBoundingClientRect()).width || 0 < o.height ? R.some(o).map(zc) : R.none()
	},
	gl = function(e, t, n) {
		return r = e, o = t, i = n, u = le.fromDom(r.document), nl(u, o, i).map(function(e) {
			return Ic(le.fromDom(e.startContainer), e.startOffset, le.fromDom(e.endContainer), e.endOffset)
		});
		var r, o, i, u
	},
	hl = tinymce.util.Tools.resolve("tinymce.util.VK"),
	pl = function(e, t, n, r) {
		return yl(e, t, kc(n), r)
	},
	vl = function(e, t, n, r) {
		return yl(e, t, Nc(n), r)
	},
	bl = function(e, t) {
		var n = Mc(t, 0, t, 0);
		return ll(n)
	},
	wl = function(e, t) {
		var n, r = Nt(t, "tr");
		return (n = r, 0 === n.length ? R.none() : R.some(n[n.length - 1])).bind(function(e) {
			return jt(e, "td,th").map(function(e) {
				return bl(0, e)
			})
		})
	},
	yl = function(r, e, t, o, n) {
		return t.fold(R.none, R.none, function(e, t) {
			return En(t).map(function(e) {
				return bl(0, e)
			})
		}, function(n) {
			return Gt.table(n, e).bind(function(e) {
				var t = Ur.noMenu(n);
				return r.undoManager.transact(function() {
					o.insertRowsAfter(e, t)
				}), wl(0, e)
			})
		})
	},
	xl = ["table", "li", "dl"],
	Cl = function(t, n, r, o) {
		if (t.keyCode === hl.TAB) {
			var i = oa(n),
			u = function(e) {
				var t = lt(e);
				return $e(e, i) || k(xl, t)
			},
			e = n.selection.getRng();
			if (e.collapsed) {
				var a = le.fromDom(e.startContainer);
				Gt.cell(a, u).each(function(e) {
					t.preventDefault(), (t.shiftKey ? vl : pl)(n, u, e, r, o).each(function(e) {
						n.selection.setRng(e)
					})
				})
			}
		}
	},
	Rl = {
			response: X("selection", "kill")
	},
	Sl = function(t) {
		return function(e) {
			return e === t
		}
	},
	Tl = Sl(38),
	Dl = Sl(40),
	Ol = {
		ltr: {
			isBackward: Sl(37),
			isForward: Sl(39)
		},
		rtl: {
			isBackward: Sl(39),
			isForward: Sl(37)
		},
		isUp: Tl,
		isDown: Dl,
		isNavigation: function(e) {
			return 37 <= e && e <= 40
		}
	},
	kl = function(e, t) {
		var n = Vc(e, t);
		return {
			start: C(le.fromDom(n.startContainer)),
			soffset: C(n.startOffset),
			finish: C(le.fromDom(n.endContainer)),
			foffset: C(n.endOffset)
		}
	},
	Nl = function(e, t, n, r) {
		return {
			start: C(Ac.on(e, t)),
			finish: C(Ac.on(n, r))
		}
	};

	function El(a) {
		return {
			elementFromPoint: function(e, t) {
				return le.fromPoint(le.fromDom(a.document), e, t)
			},
			getRect: function(e) {
				return e.dom().getBoundingClientRect()
			},
			getRangedRect: function(e, t, n, r) {
				var o = Mc(e, t, n, r);
				return ml(a, o).map(function(e) {
					return G(e, c)
				})
			},
			getSelection: function() {
				return dl(a).map(function(e) {
					return kl(a, e)
				})
			},
			fromSitus: function(e) {
				var t = Wc(e.start(), e.finish());
				return kl(a, t)
			},
			situsFromPoint: function(e, t) {
				return gl(a, e, t).map(function(e) {
					return {
						start: C(Ac.on(e.start(), e.soffset())),
						finish: C(Ac.on(e.finish(), e.foffset()))
					}
				})
			},
			clearSelection: function() {
				a.getSelection().removeAllRanges()
			},
			setSelection: function(e) {
				var t, n, r, o, i, u;
				t = a, n = e.start(), r = e.soffset(), o = e.finish(), i = e.foffset(), u = il(n, r, o, i), cl(t, u)
			},
			setRelativeSelection: function(e, t) {
				var n, r;
				n = a, r = ol(e, t), cl(n, r)
			},
			selectContents: function(e) {
				sl(a, e)
			},
			getInnerHeight: function() {
				return a.innerHeight
			},
			getScrollY: function() {
				var e, t, n, r;
				return (e = le.fromDom(a.document), t = e !== undefined ? e.dom() : document, n = t.body.scrollLeft || t.documentElement.scrollLeft, r = t.body.scrollTop || t.documentElement.scrollTop, go(n, r)).top()
			},
			scrollBy: function(e, t) {
				var n, r, o;
				n = e, r = t, ((o = le.fromDom(a.document)) !== undefined ? o.dom() : document).defaultView.scrollBy(n, r)
			}
		}
	}
	Je.detect().browser.isSafari();
	var Al = function(n, e, r, t, o) {
		return $e(r, t) ? R.none() : Sr(r, t, e).bind(function(e) {
			var t = e.boxes().getOr([]);
			return 0 < t.length ? (o(n, t, e.start(), e.finish()), R.some(Rl.response(R.some(Nl(r, 0, r, On(r))), !0))) : R.none()
		})
	},
	Pl = {
			sync: function(n, r, e, t, o, i, u) {
				return $e(e, o) && t === i ? R.none() : Ft(e, "td,th", r).bind(function(t) {
					return Ft(o, "td,th", r).bind(function(e) {
						return Al(n, r, t, e, u)
					})
				})
			},
			detect: Al,
			update: function(e, t, n, r, o) {
				return Dr(r, e, t, o.firstSelectedSelector(), o.lastSelectedSelector()).map(function(e) {
					return o.clear(n), o.selectRange(n, e.boxes(), e.start(), e.finish()), e.boxes()
				})
			}
	},
	Il = ee(["left", "top", "right", "bottom"], []),
	Bl = {
		nu: Il,
		moveUp: function(e, t) {
			return Il({
				left: e.left(),
				top: e.top() - t,
				right: e.right(),
				bottom: e.bottom() - t
			})
		},
		moveDown: function(e, t) {
			return Il({
				left: e.left(),
				top: e.top() + t,
				right: e.right(),
				bottom: e.bottom() + t
			})
		},
		moveBottomTo: function(e, t) {
			var n = e.bottom() - e.top();
			return Il({
				left: e.left(),
				top: t - n,
				right: e.right(),
				bottom: t
			})
		},
		moveTopTo: function(e, t) {
			var n = e.bottom() - e.top();
			return Il({
				left: e.left(),
				top: t,
				right: e.right(),
				bottom: t + n
			})
		},
		getTop: function(e) {
			return e.top()
		},
		getBottom: function(e) {
			return e.bottom()
		},
		translate: function(e, t, n) {
			return Il({
				left: e.left() + t,
				top: e.top() + n,
				right: e.right() + t,
				bottom: e.bottom() + n
			})
		},
		toString: function(e) {
			return "(" + e.left() + ", " + e.top() + ") -> (" + e.right() + ", " + e.bottom() + ")"
		}
	},
	Wl = function(e) {
		return Bl.nu({
			left: e.left,
			top: e.top,
			right: e.right,
			bottom: e.bottom
		})
	},
	Ml = function(e, t) {
		return R.some(e.getRect(t))
	},
	_l = function(e, t, n) {
		return mt(t) ? Ml(e, t).map(Wl) : gt(t) ? (r = e, o = t, i = n, 0 <= i && i < On(o) ? r.getRangedRect(o, i, o, i + 1) : 0 < i ? r.getRangedRect(o, i - 1, o, i) : R.none()).map(Wl) : R.none();
		var r, o, i
	},
	Ll = function(e, t) {
		return mt(t) ? Ml(e, t).map(Wl) : gt(t) ? e.getRangedRect(t, 0, t, On(t)).map(Wl) : R.none()
	},
	jl = X("item", "mode"),
	Fl = function(e, t, n, r) {
		var o = r !== undefined ? r : zl;
		return e.property().parent(t).map(function(e) {
			return jl(e, o)
		})
	},
	zl = function(e, t, n, r) {
		var o = r !== undefined ? r : Hl;
		return n.sibling(e, t).map(function(e) {
			return jl(e, o)
		})
	},
	Hl = function(e, t, n, r) {
		var o = r !== undefined ? r : Hl,
				i = e.property().children(t);
		return n.first(i).map(function(e) {
			return jl(e, o)
		})
	},
	Ul = [{
		current: Fl,
		next: zl,
		fallback: R.none()
	}, {
		current: zl,
		next: Hl,
		fallback: R.some(Fl)
	}, {
		current: Hl,
		next: Hl,
		fallback: R.some(zl)
	}],
	ql = function(t, n, r, o, e) {
		return e = e !== undefined ? e : Ul, W(e, function(e) {
			return e.current === r
		}).bind(function(e) {
			return e.current(t, n, o, e.next).orThunk(function() {
				return e.fallback.bind(function(e) {
					return ql(t, n, e, o)
				})
			})
		})
	},
	Vl = {
			backtrack: Fl,
			sidestep: zl,
			advance: Hl,
			go: ql
	},
	Gl = {
			left: function() {
				return {
					sibling: function(e, t) {
						return e.query().prevSibling(t)
					},
					first: function(e) {
						return 0 < e.length ? R.some(e[e.length - 1]) : R.none()
					}
				}
			},
			right: function() {
				return {
					sibling: function(e, t) {
						return e.query().nextSibling(t)
					},
					first: function(e) {
						return 0 < e.length ? R.some(e[0]) : R.none()
					}
				}
			}
	},
	Yl = function(t, e, n, r, o, i) {
		return Vl.go(t, e, r, o).bind(function(e) {
			return i(e.item()) ? R.none() : n(e.item()) ? R.some(e.item()) : Yl(t, e.item(), n, e.mode(), o, i)
		})
	},
	Xl = function(e, t, n, r) {
		return Yl(e, t, n, Vl.sidestep, Gl.left(), r)
	},
	Kl = function(e, t, n, r) {
		return Yl(e, t, n, Vl.sidestep, Gl.right(), r)
	},
	Jl = function(e, t) {
		return 0 === e.property().children(t).length
	},
	$l = function(e, t, n, r) {
		return Xl(e, t, n, r)
	},
	Ql = function(e, t, n, r) {
		return Kl(e, t, n, r)
	},
	Zl = {
			before: function(e, t, n) {
				return $l(e, t, b(Jl, e), n)
			},
			after: function(e, t, n) {
				return Ql(e, t, b(Jl, e), n)
			},
			seekLeft: $l,
			seekRight: Ql,
			walkers: function() {
				return {
					left: Gl.left,
					right: Gl.right
				}
			},
			walk: function(e, t, n, r, o) {
				return Vl.go(e, t, n, r, o)
			},
			backtrack: Vl.backtrack,
			sidestep: Vl.sidestep,
			advance: Vl.advance
	},
	ef = Fn(),
	tf = {
		gather: function(e, t, n) {
			return Zl.gather(ef, e, t, n)
		},
		before: function(e, t) {
			return Zl.before(ef, e, t)
		},
		after: function(e, t) {
			return Zl.after(ef, e, t)
		},
		seekLeft: function(e, t, n) {
			return Zl.seekLeft(ef, e, t, n)
		},
		seekRight: function(e, t, n) {
			return Zl.seekRight(ef, e, t, n)
		},
		walkers: function() {
			return Zl.walkers()
		},
		walk: function(e, t, n, r) {
			return Zl.walk(ef, e, t, n, r)
		}
	},
	nf = Br([{
		none: []
	}, {
		retry: ["caret"]
	}]),
	rf = function(t, e, r) {
		return (n = e, o = Ko, Et(function(e) {
			return o(e)
		}, Mt, n, o, i)).fold(C(!1), function(e) {
			return Ll(t, e).exists(function(e) {
				return n = e, (t = r).left() < n.left() || Math.abs(n.right() - t.left()) < 1 || t.left() > n.right();
				var t, n
			})
		});
		var n, o, i
	},
	of = {
			point: Bl.getTop,
			adjuster: function(e, t, n, r, o) {
				var i = Bl.moveUp(o, 5);
				return Math.abs(n.top() - r.top()) < 1 ? nf.retry(i) : n.bottom() < o.top() ? nf.retry(i) : n.bottom() === o.top() ? nf.retry(Bl.moveUp(o, 1)) : rf(e, t, o) ? nf.retry(Bl.translate(i, 5, 0)) : nf.none()
			},
			move: Bl.moveUp,
			gather: tf.before
	},
	uf = {
			point: Bl.getBottom,
			adjuster: function(e, t, n, r, o) {
				var i = Bl.moveDown(o, 5);
				return Math.abs(n.bottom() - r.bottom()) < 1 ? nf.retry(i) : n.top() > o.bottom() ? nf.retry(i) : n.top() === o.bottom() ? nf.retry(Bl.moveDown(o, 1)) : rf(e, t, o) ? nf.retry(Bl.translate(i, 5, 0)) : nf.none()
			},
			move: Bl.moveDown,
			gather: tf.after
	},
	af = function(n, r, o, i, u) {
		return 0 === u ? R.some(i) : (c = n, l = i.left(), f = r.point(i), c.elementFromPoint(l, f).filter(function(e) {
			return "table" === lt(e)
		}).isSome() ? (t = i, a = u - 1, af(n, e = r, o, e.move(t, 5), a)) : n.situsFromPoint(i.left(), r.point(i)).bind(function(e) {
			return e.start().fold(R.none, function(t, e) {
				return Ll(n, t, e).bind(function(e) {
					return r.adjuster(n, t, e, o, i).fold(R.none, function(e) {
						return af(n, r, o, e, u - 1)
					})
				}).orThunk(function() {
					return R.some(i)
				})
			}, R.none)
		}));
		var e, t, a, c, l, f
	},
	cf = function(t, n, e) {
		var r, o, i, u = t.move(e, 5),
		a = af(n, t, e, u, 100).getOr(u);
		return (r = t, o = a, i = n, r.point(o) > i.getInnerHeight() ? R.some(r.point(o) - i.getInnerHeight()) : r.point(o) < 0 ? R.some(-r.point(o)) : R.none()).fold(function() {
			return n.situsFromPoint(a.left(), t.point(a))
		}, function(e) {
			return n.scrollBy(0, e), n.situsFromPoint(a.left(), t.point(a) - e)
		})
	},
	lf = {
			tryUp: b(cf, of),
			tryDown: b(cf, uf),
			ieTryUp: function(e, t) {
				return e.situsFromPoint(t.left(), t.top() - 5)
			},
			ieTryDown: function(e, t) {
				return e.situsFromPoint(t.left(), t.bottom() + 5)
			},
			getJumpSize: C(5)
	},
	ff = Br([{
		none: ["message"]
	}, {
		success: []
	}, {
		failedUp: ["cell"]
	}, {
		failedDown: ["cell"]
	}]),
	sf = function(e) {
		return Ft(e, "tr")
	},
	df = {
			verify: function(a, e, t, n, r, c, o) {
				return Ft(n, "td,th", o).bind(function(u) {
					return Ft(e, "td,th", o).map(function(i) {
						return $e(u, i) ? $e(n, u) && On(u) === r ? c(i) : ff.none("in same cell") : ur.sharedOne(sf, [u, i]).fold(function() {
							return t = i, n = u, r = (e = a).getRect(t), (o = e.getRect(n)).right > r.left && o.left < r.right ? ff.success() : c(i);
							var e, t, n, r, o
						}, function(e) {
							return c(i)
						})
					})
				}).getOr(ff.none("default"))
			},
			cata: function(e, t, n, r, o) {
				return e.fold(t, n, r, o)
			},
			adt: ff
	},
	mf = {
			point: X("element", "offset"),
			delta: X("element", "deltaOffset"),
			range: X("element", "start", "finish"),
			points: X("begin", "end"),
			text: X("element", "text")
	},
	gf = (X("ancestor", "descendants", "element", "index"), X("parent", "children", "element", "index")),
	hf = function(e, t) {
		return M(e, b($e, t))
	},
	pf = function(e) {
		return "br" === lt(e)
	},
	vf = function(e, t, n) {
		return t(e, n).bind(function(e) {
			return gt(e) && 0 === Sn(e).trim().length ? vf(e, t, n) : R.some(e)
		})
	},
	bf = function(t, e, n, r) {
		return (o = e, i = n, ut(o, i).filter(pf).orThunk(function() {
			return ut(o, i - 1).filter(pf)
		})).bind(function(e) {
			return r.traverse(e).fold(function() {
				return vf(e, r.gather, t).map(r.relative)
			}, function(e) {
				return (r = e, tt(r).bind(function(t) {
					var n = it(t);
					return hf(n, r).map(function(e) {
						return gf(t, n, r, e)
					})
				})).map(function(e) {
					return Ac.on(e.parent(), e.index())
				});
				var r
			})
		});
		var o, i
	},
	wf = function(e, t, n, r) {
		var o, i, u;
		return (pf(t) ? (o = e, i = t, (u = r).traverse(i).orThunk(function() {
			return vf(i, u.gather, o)
		}).map(u.relative)) : bf(e, t, n, r)).map(function(e) {
			return {
				start: C(e),
				finish: C(e)
			}
		})
	},
	yf = function(e) {
		return df.cata(e, function(e) {
			return R.none()
		}, function() {
			return R.none()
		}, function(e) {
			return R.some(mf.point(e, 0))
		}, function(e) {
			return R.some(mf.point(e, On(e)))
		})
	},
	xf = Je.detect(),
	Cf = function(r, o, i, u, a, c) {
		return 0 === c ? R.none() : Tf(r, o, i, u, a).bind(function(e) {
			var t = r.fromSitus(e),
			n = df.verify(r, i, u, t.finish(), t.foffset(), a.failure, o);
			return df.cata(n, function() {
				return R.none()
			}, function() {
				return R.some(e)
			}, function(e) {
				return $e(i, e) && 0 === u ? Rf(r, i, u, Bl.moveUp, a) : Cf(r, o, e, 0, a, c - 1)
			}, function(e) {
				return $e(i, e) && u === On(e) ? Rf(r, i, u, Bl.moveDown, a) : Cf(r, o, e, On(e), a, c - 1)
			})
		})
	},
	Rf = function(t, e, n, r, o) {
		return _l(t, e, n).bind(function(e) {
			return Sf(t, o, r(e, lf.getJumpSize()))
		})
	},
	Sf = function(e, t, n) {
		return xf.browser.isChrome() || xf.browser.isSafari() || xf.browser.isFirefox() || xf.browser.isEdge() ? t.otherRetry(e, n) : xf.browser.isIE() ? t.ieRetry(e, n) : R.none()
	},
	Tf = function(t, e, n, r, o) {
		return _l(t, n, r).bind(function(e) {
			return Sf(t, o, e)
		})
	},
	Df = function(t, n, r) {
		return (o = t, i = n, u = r, o.getSelection().bind(function(r) {
			return wf(i, r.finish(), r.foffset(), u).fold(function() {
				return R.some(mf.point(r.finish(), r.foffset()))
			}, function(e) {
				var t = o.fromSitus(e),
				n = df.verify(o, r.finish(), r.foffset(), t.finish(), t.foffset(), u.failure, i);
				return yf(n)
			})
		})).bind(function(e) {
			return Cf(t, n, e.element(), e.offset(), r, 20).map(t.fromSitus)
		});
		var o, i, u
	},
	Of = Je.detect(),
	kf = function(e, t) {
		return Mt(e, function(e) {
			return tt(e).exists(function(e) {
				return $e(e, t)
			})
		}, n).isSome();
		var n
	},
	Nf = function(t, r, o, e, i) {
		return Ft(e, "td,th", r).bind(function(n) {
			return Ft(n, "table", r).bind(function(e) {
				return kf(i, e) ? Df(t, r, o).bind(function(t) {
					return Ft(t.finish(), "td,th", r).map(function(e) {
						return {
							start: C(n),
							finish: C(e),
							range: C(t)
						}
					})
				}) : R.none()
			})
		})
	},
	Ef = function(e, t, n, r, o, i) {
		return Of.browser.isIE() ? R.none() : i(r, t).orThunk(function() {
			return Nf(e, t, n, r, o).map(function(e) {
				var t = e.range();
				return Rl.response(R.some(Nl(t.start(), t.soffset(), t.finish(), t.foffset())), !0)
			})
		})
	},
	Af = function(e, t, n, r, o, i, u) {
		return Nf(e, n, r, o, i).bind(function(e) {
			return Pl.detect(t, n, e.start(), e.finish(), u)
		})
	},
	Pf = function(e, r) {
		return Ft(e, "tr", r).bind(function(n) {
			return Ft(n, "table", r).bind(function(e) {
				var t = Nt(e, "tr");
				return $e(n, t[0]) ? tf.seekLeft(e, function(e) {
					return An(e).isSome()
				}, r).map(function(e) {
					var t = On(e);
					return Rl.response(R.some(Nl(e, t, e, t)), !0)
				}) : R.none()
			})
		})
	},
	If = function(e, r) {
		return Ft(e, "tr", r).bind(function(n) {
			return Ft(n, "table", r).bind(function(e) {
				var t = Nt(e, "tr");
				return $e(n, t[t.length - 1]) ? tf.seekRight(e, function(e) {
					return En(e).isSome()
				}, r).map(function(e) {
					return Rl.response(R.some(Nl(e, 0, e, 0)), !0)
				}) : R.none()
			})
		})
	},
	Bf = function(e, t) {
		return Ft(e, "td,th", t)
	},
	Wf = {
			down: {
				traverse: ot,
				gather: tf.after,
				relative: Ac.before,
				otherRetry: lf.tryDown,
				ieRetry: lf.ieTryDown,
				failure: df.adt.failedDown
			},
			up: {
				traverse: rt,
				gather: tf.before,
				relative: Ac.before,
				otherRetry: lf.tryUp,
				ieRetry: lf.ieTryUp,
				failure: df.adt.failedUp
			}
	},
	Mf = X("rows", "cols"),
	_f = {
		mouse: function(e, t, n, r) {
			var o, i, u, a, c, l, f = El(e),
			s = (o = f, i = t, u = n, a = r, c = R.none(), l = function() {
				c = R.none()
			}, {
				mousedown: function(e) {
					a.clear(i), c = Bf(e.target(), u)
				},
				mouseover: function(e) {
					c.each(function(r) {
						a.clear(i), Bf(e.target(), u).each(function(n) {
							Sr(r, n, u).each(function(e) {
								var t = e.boxes().getOr([]);
								(1 < t.length || 1 === t.length && !$e(r, n)) && (a.selectRange(i, t, e.start(), e.finish()), o.selectContents(n))
							})
						})
					})
				},
				mouseup: function() {
					c.each(l)
				}
			});
			return {
				mousedown: s.mousedown,
				mouseover: s.mouseover,
				mouseup: s.mouseup
			}
		},
		keyboard: function(e, c, l, f) {
			var s = El(e),
			d = function() {
				return f.clear(c), R.none()
			};
			return {
				keydown: function(e, t, n, r, o, i) {
					var u = e.raw().which,
					a = !0 === e.raw().shiftKey;
					return Tr(c, f.selectedSelector()).fold(function() {
						return Ol.isDown(u) && a ? b(Af, s, c, l, Wf.down, r, t, f.selectRange) : Ol.isUp(u) && a ? b(Af, s, c, l, Wf.up, r, t, f.selectRange) : Ol.isDown(u) ? b(Ef, s, l, Wf.down, r, t, If) : Ol.isUp(u) ? b(Ef, s, l, Wf.up, r, t, Pf) : R.none
					}, function(t) {
						var e = function(e) {
							return function() {
								return ni(e, function(e) {
									return Pl.update(e.rows(), e.cols(), c, t, f)
								}).fold(function() {
									return Or(c, f.firstSelectedSelector(), f.lastSelectedSelector()).map(function(e) {
										var t = Ol.isDown(u) || i.isForward(u) ? Ac.after : Ac.before;
										return s.setRelativeSelection(Ac.on(e.first(), 0), t(e.table())), f.clear(c), Rl.response(R.none(), !0)
									})
								}, function(e) {
									return R.some(Rl.response(R.none(), !0))
								})
							}
						};
						return Ol.isDown(u) && a ? e([Mf(1, 0)]) : Ol.isUp(u) && a ? e([Mf(-1, 0)]) : i.isBackward(u) && a ? e([Mf(0, -1), Mf(-1, 0)]) : i.isForward(u) && a ? e([Mf(0, 1), Mf(1, 0)]) : Ol.isNavigation(u) && !1 === a ? d : R.none
					})()
				},
				keyup: function(t, n, r, o, i) {
					return Tr(c, f.selectedSelector()).fold(function() {
						var e = t.raw().which;
						return 0 == (!0 === t.raw().shiftKey) ? R.none() : Ol.isNavigation(e) ? Pl.sync(c, l, n, r, o, i, f.selectRange) : R.none()
					}, R.none)
				}
			}
		}
	},
	Lf = function(r, e) {
		A(e, function(e) {
			var t, n;
			n = e, Si(t = r) ? t.dom().classList.remove(n) : Di(t, n), ki(t)
		})
	},
	jf = {
			byClass: function(o) {
				var t, n, i = (t = o.selected(), function(e) {
					Oi(e, t)
				}),
				r = (n = [o.selected(), o.lastSelected(), o.firstSelected()], function(e) {
					Lf(e, n)
				}),
				u = function(e) {
					var t = Nt(e, o.selectedSelector());
					A(t, r)
				};
				return {
					clear: u,
					selectRange: function(e, t, n, r) {
						u(e), A(t, i), Oi(n, o.firstSelected()), Oi(r, o.lastSelected())
					},
					selectedSelector: o.selectedSelector,
					firstSelectedSelector: o.firstSelectedSelector,
					lastSelectedSelector: o.lastSelectedSelector
				}
			},
			byAttr: function(o) {
				var n = function(e) {
					xt(e, o.selected()), xt(e, o.firstSelected()), xt(e, o.lastSelected())
				},
				i = function(e) {
					vt(e, o.selected(), "1")
				},
				u = function(e) {
					var t = Nt(e, o.selectedSelector());
					A(t, n)
				};
				return {
					clear: u,
					selectRange: function(e, t, n, r) {
						u(e), A(t, i), vt(n, o.firstSelected(), "1"), vt(r, o.lastSelected(), "1")
					},
					selectedSelector: o.selectedSelector,
					firstSelectedSelector: o.firstSelectedSelector,
					lastSelectedSelector: o.lastSelectedSelector
				}
			}
	},
	Ff = function(e) {
		return !1 === Ni(le.fromDom(e.target), "ephox-snooker-resizer-bar")
	};

	function zf(h, p) {
		var v = ee(["mousedown", "mouseover", "mouseup", "keyup", "keydown"], []),
		b = R.none(),
		w = jf.byAttr(Ir);
		return h.on("init", function(e) {
			var r = h.getWin(),
			o = oa(h),
			t = aa(h),
			n = _f.mouse(r, o, t, w),
			a = _f.keyboard(r, o, t, w),
			c = function(e, t) {
				!0 === e.raw().shiftKey && (t.kill() && e.kill(), t.selection().each(function(e) {
					var t = Wc(e.start(), e.finish()),
					n = Vc(r, t);
					h.selection.setRng(n)
				}))
			},
			i = function(e) {
				var t = f(e);
				if (t.raw().shiftKey && Ol.isNavigation(t.raw().which)) {
					var n = h.selection.getRng(),
					r = le.fromDom(n.startContainer),
					o = le.fromDom(n.endContainer);
					a.keyup(t, r, n.startOffset, o, n.endOffset).each(function(e) {
						c(t, e)
					})
				}
			},
			u = function(e) {
				var t = f(e);
				p().each(function(e) {
					e.hideBars()
				});
				var n = h.selection.getRng(),
				r = le.fromDom(h.selection.getStart()),
				o = le.fromDom(n.startContainer),
				i = le.fromDom(n.endContainer),
				u = da.directionAt(r).isRtl() ? Ol.rtl : Ol.ltr;
				a.keydown(t, o, n.startOffset, i, n.endOffset, u).each(function(e) {
					c(t, e)
				}), p().each(function(e) {
					e.showBars()
				})
			},
			l = function(e) {
				return e.hasOwnProperty("x") && e.hasOwnProperty("y")
			},
			f = function(e) {
				var t = le.fromDom(e.target),
				n = function() {
					e.stopPropagation()
				},
				r = function() {
					e.preventDefault()
				},
				o = x(r, n);
				return {
					target: C(t),
					x: C(l(e) ? e.x : null),
					y: C(l(e) ? e.y : null),
					stop: n,
					prevent: r,
					kill: o,
					raw: C(e)
				}
			},
			s = function(e) {
				return 0 === e.button
			},
			d = function(e) {
				s(e) && Ff(e) && n.mousedown(f(e))
			},
			m = function(e) {
				var t;
				(t = e).buttons !== undefined && 0 == (1 & t.buttons) || !Ff(e) || n.mouseover(f(e))
			},
			g = function(e) {
				s(e) && Ff(e) && n.mouseup(f(e))
			};
			h.on("mousedown", d), h.on("mouseover", m), h.on("mouseup", g), h.on("keyup", i), h.on("keydown", u), h.on("nodechange", function() {
				var e = h.selection,
				t = le.fromDom(e.getStart()),
				n = le.fromDom(e.getEnd());
				ur.sharedOne(Gt.table, [t, n]).fold(function() {
					w.clear(o)
				}, y)
			}), b = R.some(v({
				mousedown: d,
				mouseover: m,
				mouseup: g,
				keyup: i,
				keydown: u
			}))
		}), {
			clear: w.clear,
			destroy: function() {
				b.each(function(e) {})
			}
		}
	}
	var Hf = Ea.each,
	Uf = function(t) {
		var n = [];

		function e(e) {
			return function() {
				t.execCommand(e)
			}
		}
		Hf("inserttable tableprops deletetable | cell row column".split(" "), function(e) {
			"|" === e ? n.push({
				text: "-"
			}) : n.push(t.menuItems[e])
		}), t.addButton("table", {
			type: "menubutton",
			title: "Table",
			menu: n
		}), t.addButton("tableprops", {
			title: "Table properties",
			onclick: e("mceTableProps"),
			icon: "table"
		}), t.addButton("tabledelete", {
			title: "Delete table",
			onclick: e("mceTableDelete")
		}), t.addButton("tablecellprops", {
			title: "Cell properties",
			onclick: e("mceTableCellProps")
		}), t.addButton("tablemergecells", {
			title: "Merge cells",
			onclick: e("mceTableMergeCells")
		}), t.addButton("tablesplitcells", {
			title: "Split cell",
			onclick: e("mceTableSplitCells")
		}), t.addButton("tableinsertrowbefore", {
			title: "Insert row before",
			onclick: e("mceTableInsertRowBefore")
		}), t.addButton("tableinsertrowafter", {
			title: "Insert row after",
			onclick: e("mceTableInsertRowAfter")
		}), t.addButton("tabledeleterow", {
			title: "Delete row",
			onclick: e("mceTableDeleteRow")
		}), t.addButton("tablerowprops", {
			title: "Row properties",
			onclick: e("mceTableRowProps")
		}), t.addButton("tablecutrow", {
			title: "Cut row",
			onclick: e("mceTableCutRow")
		}), t.addButton("tablecopyrow", {
			title: "Copy row",
			onclick: e("mceTableCopyRow")
		}), t.addButton("tablepasterowbefore", {
			title: "Paste row before",
			onclick: e("mceTablePasteRowBefore")
		}), t.addButton("tablepasterowafter", {
			title: "Paste row after",
			onclick: e("mceTablePasteRowAfter")
		}), t.addButton("tableinsertcolbefore", {
			title: "Insert column before",
			onclick: e("mceTableInsertColBefore")
		}), t.addButton("tableinsertcolafter", {
			title: "Insert column after",
			onclick: e("mceTableInsertColAfter")
		}), t.addButton("tabledeletecol", {
			title: "Delete column",
			onclick: e("mceTableDeleteCol")
		})
	},
	qf = function(t) {
		var e, n = "" === (e = t.getParam("table_toolbar", ma)) || !1 === e ? [] : v(e) ? e.split(/[ ,]/) : w(e) ? e : [];
		0 < n.length && t.addContextToolbar(function(e) {
			return t.dom.is(e, "table") && t.getBody().contains(e)
		}, n.join(" "))
	},
	Vf = function(o, n) {
		var r = R.none(),
		i = [],
		u = [],
		a = [],
		c = [],
		l = function(e) {
			e.disabled(!0)
		},
		f = function(e) {
			e.disabled(!1)
		},
		e = function() {
			var t = this;
			i.push(t), r.fold(function() {
				l(t)
			}, function(e) {
				f(t)
			})
		},
		t = function() {
			var t = this;
			u.push(t), r.fold(function() {
				l(t)
			}, function(e) {
				f(t)
			})
		};
		o.on("init", function() {
			o.on("nodechange", function(e) {
				var t = R.from(o.dom.getParent(o.selection.getStart(), "th,td"));
				(r = t.bind(function(e) {
					var t = le.fromDom(e);
					return Gt.table(t).map(function(e) {
						return Ur.forMenu(n, e, t)
					})
				})).fold(function() {
					A(i, l), A(u, l), A(a, l), A(c, l)
				}, function(t) {
					A(i, f), A(u, f), A(a, function(e) {
						e.disabled(t.mergable().isNone())
					}), A(c, function(e) {
						e.disabled(t.unmergable().isNone())
					})
				})
			})
		});
		var s = function(e, t, n, r) {
			var o, i, u, a, c, l = r.getEl().getElementsByTagName("table")[0],
			f = r.isRtl() || "tl-tr" === r.parent().rel;
			for (l.nextSibling.innerHTML = t + 1 + " x " + (n + 1), f && (t = 9 - t), i = 0; i < 10; i++)
				for (o = 0; o < 10; o++) a = l.rows[i].childNodes[o].firstChild, c = (f ? t <= o : o <= t) && i <= n, e.dom.toggleClass(a, "mce-active", c), c && (u = a);
			return u.parentNode
		},
		d = !1 === o.getParam("table_grid", !0, "boolean") ? {
			text: "Table",
			icon: "table",
			context: "table",
			onclick: m("mceInsertTable")
		} : {
			text: "Table",
			icon: "table",
			context: "table",
			ariaHideMenu: !0,
			onclick: function(e) {
				e.aria && (this.parent().hideAll(), e.stopImmediatePropagation(), o.execCommand("mceInsertTable"))
			},
			onshow: function() {
				s(o, 0, 0, this.menu.items()[0])
			},
			onhide: function() {
				var e = this.menu.items()[0].getEl().getElementsByTagName("a");
				o.dom.removeClass(e, "mce-active"), o.dom.addClass(e[0], "mce-active")
			},
			menu: [{
				type: "container",
				html: function() {
					var e = "";
					e = '<table role="grid" class="mce-grid mce-grid-border" aria-readonly="true">';
					for (var t = 0; t < 10; t++) {
						e += "<tr>";
						for (var n = 0; n < 10; n++) e += '<td role="gridcell" tabindex="-1"><a id="mcegrid' + (10 * t + n) + '" href="#" data-mce-x="' + n + '" data-mce-y="' + t + '"></a></td>';
						e += "</tr>"
					}
					return e += "</table>", e += '<div class="mce-text-center" role="presentation">1 x 1</div>'
				}(),
				onPostRender: function() {
					this.lastX = this.lastY = 0
				},
				onmousemove: function(e) {
					var t, n, r = e.target;
					"A" === r.tagName.toUpperCase() && (t = parseInt(r.getAttribute("data-mce-x"), 10), n = parseInt(r.getAttribute("data-mce-y"), 10), (this.isRtl() || "tl-tr" === this.parent().rel) && (t = 9 - t), t === this.lastX && n === this.lastY || (s(o, t, n, e.control), this.lastX = t, this.lastY = n))
				},
				onclick: function(e) {
					var t = this;
					"A" === e.target.tagName.toUpperCase() && (e.preventDefault(), e.stopPropagation(), t.parent().cancel(), o.undoManager.transact(function() {
						Ya(o, t.lastX + 1, t.lastY + 1)
					}), o.addVisual())
				}
			}]
		};

		function m(e) {
			return function() {
				o.execCommand(e)
			}
		}
		var g = {
				text: "Table properties",
				context: "table",
				onPostRender: e,
				onclick: m("mceTableProps")
		},
		h = {
				text: "Delete table",
				context: "table",
				onPostRender: e,
				cmd: "mceTableDelete"
		},
		p = {
				text: "Row",
				context: "table",
				menu: [{
					text: "Insert row before",
					onclick: m("mceTableInsertRowBefore"),
					onPostRender: t
				}, {
					text: "Insert row after",
					onclick: m("mceTableInsertRowAfter"),
					onPostRender: t
				}, {
					text: "Delete row",
					onclick: m("mceTableDeleteRow"),
					onPostRender: t
				}, {
					text: "Row properties",
					onclick: m("mceTableRowProps"),
					onPostRender: t
				}, {
					text: "-"
				}, {
					text: "Cut row",
					onclick: m("mceTableCutRow"),
					onPostRender: t
				}, {
					text: "Copy row",
					onclick: m("mceTableCopyRow"),
					onPostRender: t
				}, {
					text: "Paste row before",
					onclick: m("mceTablePasteRowBefore"),
					onPostRender: t
				}, {
					text: "Paste row after",
					onclick: m("mceTablePasteRowAfter"),
					onPostRender: t
				}]
		},
		v = {
				text: "Column",
				context: "table",
				menu: [{
					text: "Insert column before",
					onclick: m("mceTableInsertColBefore"),
					onPostRender: t
				}, {
					text: "Insert column after",
					onclick: m("mceTableInsertColAfter"),
					onPostRender: t
				}, {
					text: "Delete column",
					onclick: m("mceTableDeleteCol"),
					onPostRender: t
				}]
		},
		b = {
				separator: "before",
				text: "Cell",
				context: "table",
				menu: [{
					text: "Cell properties",
					onclick: m("mceTableCellProps"),
					onPostRender: t
				}, {
					text: "Merge cells",
					onclick: m("mceTableMergeCells"),
					onPostRender: function() {
						var t = this;
						a.push(t), r.fold(function() {
							l(t)
						}, function(e) {
							t.disabled(e.mergable().isNone())
						})
					}
				}, {
					text: "Split cell",
					onclick: m("mceTableSplitCells"),
					onPostRender: function() {
						var t = this;
						c.push(t), r.fold(function() {
							l(t)
						}, function(e) {
							t.disabled(e.unmergable().isNone())
						})
					}
				}]
		};
		o.addMenuItem("inserttable", d), o.addMenuItem("tableprops", g), o.addMenuItem("deletetable", h), o.addMenuItem("row", p), o.addMenuItem("column", v), o.addMenuItem("cell", b)
	},
	Gf = function(n, r) {
		return {
			insertTable: function(e, t) {
				return Ya(n, e, t)
			},
			setClipboardRows: function(e) {
				return t = r, n = E(e, le.fromDom), void t.set(R.from(n));
				var t, n
			},
			getClipboardRows: function() {
				return r.get().fold(function() {}, function(e) {
					return E(e, function(e) {
						return e.dom()
					})
				})
			}
		}
	};
	e.add("table", function(t) {
		var n, r = xc(t),
		e = zf(t, r.lazyResize),
		o = ka(t, r.lazyWire),
		i = (n = t, {
			get: function() {
				var e = oa(n);
				return kr(e, Ir.selectedSelector()).fold(function() {
					return n.selection.getStart() === undefined ? Mr.none() : Mr.single(n.selection)
				}, function(e) {
					return Mr.multiple(e)
				})
			}
		}),
		u = Io(R.none());
		return Qa(t, o, e, i, u), qr(t, i, o, e), Vf(t, i), Uf(t), qf(t), t.on("PreInit", function() {
			t.serializer.addTempAttr(Ir.firstSelected()), t.serializer.addTempAttr(Ir.lastSelected())
		}), t.getParam("table_tab_navigation", !0, "boolean") && t.on("keydown", function(e) {
			Cl(e, t, o, r.lazyWire)
		}), t.on("remove", function() {
			r.destroy(), e.destroy()
		}), Gf(t, u)
	})
}();