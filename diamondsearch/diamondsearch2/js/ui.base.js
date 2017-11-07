/*
2 * jQuery UI @VERSION
3 *
4 * Copyright (c) 2008 Paul Bakaus (ui.jquery.com)
5 * Dual licensed under the MIT (MIT-LICENSE.txt)
6 * and GPL (GPL-LICENSE.txt) licenses.
7 *
8 * http://docs.jquery.com/UI
9 *
10 * $Date: 2008-04-01 08:23:47 -0500 (Tue, 01 Apr 2008) $
11 * $Rev: 5174 $
12 */
13;(function($) {
14
15 //If the UI scope is not available, add it
16 $.ui = $.ui || {};
17
18 //Add methods that are vital for all mouse interaction stuff (plugin registering)
19 $.extend($.ui, {
20 plugin: {
21 add: function(module, option, set) {
22 var proto = $.ui[module].prototype;
23 for(var i in set) {
24 proto.plugins[i] = proto.plugins[i] || [];
25 proto.plugins[i].push([option, set[i]]);
26 }
27 },
28 call: function(instance, name, arguments) {
29 var set = instance.plugins[name]; if(!set) return;
30 for (var i = 0; i < set.length; i++) {
31 if (instance.options[set[i][0]]) set[i][1].apply(instance.element, arguments);
32 }
33 }
34 },
35 cssCache: {},
36 css: function(name) {
37 if ($.ui.cssCache[name]) return $.ui.cssCache[name];
38 var tmp = $('<div class="ui-resizable-gen">').addClass(name).css({position:'absolute', top:'-5000px', left:'-5000px', display:'block'}).appendTo('body');
39
40 //if (!$.browser.safari)
41 //tmp.appendTo('body');
42
43 //Opera and Safari set width and height to 0px instead of auto
44 //Safari returns rgba(0,0,0,0) when bgcolor is not set
45 $.ui.cssCache[name] = !!(
46 (!/auto|default/.test(tmp.css('cursor')) || (/^[1-9]/).test(tmp.css('height')) || (/^[1-9]/).test(tmp.css('width')) ||
47 !(/none/).test(tmp.css('backgroundImage')) || !(/transparent|rgba\(0, 0, 0, 0\)/).test(tmp.css('backgroundColor')))
48 );
49 try { $('body').get(0).removeChild(tmp.get(0)); } catch(e){}
50 return $.ui.cssCache[name];
51 },
52 disableSelection: function(e) {
53 e.unselectable = "on";
54 e.onselectstart = function() { return false; };
55 if (e.style) e.style.MozUserSelect = "none";
56 },
57 enableSelection: function(e) {
58 e.unselectable = "off";
59 e.onselectstart = function() { return true; };
60 if (e.style) e.style.MozUserSelect = "";
61 },
62 hasScroll: function(e, a) {
63 var scroll = /top/.test(a||"top") ? 'scrollTop' : 'scrollLeft', has = false;
64 if (e[scroll] > 0) return true; e[scroll] = 1;
65 has = e[scroll] > 0 ? true : false; e[scroll] = 0;
66 return has;
67 }
68 });
69
70 /******* fn scope modifications ********/
71
72 $.each( ['Left', 'Top'], function(i, name) {
73 if(!$.fn['scroll'+name]) $.fn['scroll'+name] = function(v) {
74 return v != undefined ?
75 this.each(function() { this == window || this == document ? window.scrollTo(name == 'Left' ? v : $(window)['scrollLeft'](), name == 'Top' ? v : $(window)['scrollTop']()) : this['scroll'+name] = v; }) :
76 this[0] == window || this[0] == document ? self[(name == 'Left' ? 'pageXOffset' : 'pageYOffset')] || $.boxModel && document.documentElement['scroll'+name] || document.body['scroll'+name] : this[0][ 'scroll' + name ];
77 };
78 });
79
80 var _remove = $.fn.remove;
81 $.fn.extend({
82 position: function() {
83 var offset = this.offset();
84 var offsetParent = this.offsetParent();
85 var parentOffset = offsetParent.offset();
86
87 return {
88 top: offset.top - num(this[0], 'marginTop') - parentOffset.top - num(offsetParent, 'borderTopWidth'),
89 left: offset.left - num(this[0], 'marginLeft') - parentOffset.left - num(offsetParent, 'borderLeftWidth')
90 };
91 },
92 offsetParent: function() {
93 var offsetParent = this[0].offsetParent;
94 while ( offsetParent && (!/^body|html$/i.test(offsetParent.tagName) && $.css(offsetParent, 'position') == 'static') )
95 offsetParent = offsetParent.offsetParent;
96 return $(offsetParent);
97 },
98 mouseInteraction: function(o) {
99 return this.each(function() {
100 new $.ui.mouseInteraction(this, o);
101 });
102 },
103 removeMouseInteraction: function(o) {
104 return this.each(function() {
105 if($.data(this, "ui-mouse"))
106 $.data(this, "ui-mouse").destroy();
107 });
108 },
109 remove: function() {
110 jQuery("*", this).add(this).trigger("remove");
111 return _remove.apply(this, arguments );
112 }
113 });
114
115 function num(el, prop) {
116 return parseInt($.curCSS(el.jquery?el[0]:el,prop,true))||0;
117 };
118
119
120 /********** Mouse Interaction Plugin *********/
121
122 $.ui.mouseInteraction = function(element, options) {
123
124 var self = this;
125 this.element = element;
126
127 $.data(this.element, "ui-mouse", this);
128 this.options = $.extend({}, options);
129
130 $(element).bind('mousedown.draggable', function() { return self.click.apply(self, arguments); });
131 if($.browser.msie) $(element).attr('unselectable', 'on'); //Prevent text selection in IE
132
133 // prevent draggable-options-delay bug #2553
134 $(element).mouseup(function() {
135 if(self.timer) clearInterval(self.timer);
136 });
137 };
138
139 $.extend($.ui.mouseInteraction.prototype, {
140
141 destroy: function() { $(this.element).unbind('mousedown.draggable'); },
142 trigger: function() { return this.click.apply(this, arguments); },
143 click: function(e) {
144
145 if(
146 e.which != 1 //only left click starts dragging
147 || $.inArray(e.target.nodeName.toLowerCase(), this.options.dragPrevention || []) != -1 // Prevent execution on defined elements
148 || (this.options.condition && !this.options.condition.apply(this.options.executor || this, [e, this.element])) //Prevent execution on condition
149 ) return true;
150
151 var self = this;
152 var initialize = function() {
153 self._MP = { left: e.pageX, top: e.pageY }; // Store the click mouse position
154 $(document).bind('mouseup.draggable', function() { return self.stop.apply(self, arguments); });
155 $(document).bind('mousemove.draggable', function() { return self.drag.apply(self, arguments); });
156
157 if(!self.initalized && Math.abs(self._MP.left-e.pageX) >= self.options.distance || Math.abs(self._MP.top-e.pageY) >= self.options.distance) {
158 if(self.options.start) self.options.start.call(self.options.executor || self, e, self.element);
159 if(self.options.drag) self.options.drag.call(self.options.executor || self, e, this.element); //This is actually not correct, but expected
160 self.initialized = true;
161 }
162 };
163
164 if(this.options.delay) {
165 if(this.timer) clearInterval(this.timer);
166 this.timer = setTimeout(initialize, this.options.delay);
167 } else {
168 initialize();
169 }
170
171 return false;
172
173 },
174 stop: function(e) {
175
176 var o = this.options;
177 if(!this.initialized) return $(document).unbind('mouseup.draggable').unbind('mousemove.draggable');
178
179 if(this.options.stop) this.options.stop.call(this.options.executor || this, e, this.element);
180 $(document).unbind('mouseup.draggable').unbind('mousemove.draggable');
181 this.initialized = false;
182 return false;
183
184 },
185 drag: function(e) {
186
187 var o = this.options;
188 if ($.browser.msie && !e.button) return this.stop.apply(this, [e]); // IE mouseup check
189
190 if(!this.initialized && (Math.abs(this._MP.left-e.pageX) >= o.distance || Math.abs(this._MP.top-e.pageY) >= o.distance)) {
191 if(this.options.start) this.options.start.call(this.options.executor || this, e, this.element);
192 this.initialized = true;
193 } else {
194 if(!this.initialized) return false;
195 }
196
197 if(o.drag) o.drag.call(this.options.executor || this, e, this.element);
198 return false;
199
200 }
201 });
202
203})(jQuery);
204 