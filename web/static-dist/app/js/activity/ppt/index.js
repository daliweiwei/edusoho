!function(l){function t(t){for(var e,n,a=t[0],i=t[1],r=t[2],o=0,s=[];o<a.length;o++)n=a[o],Object.prototype.hasOwnProperty.call(u,n)&&u[n]&&s.push(u[n][0]),u[n]=0;for(e in i)Object.prototype.hasOwnProperty.call(i,e)&&(l[e]=i[e]);for(f&&f(t);s.length;)s.shift()();return p.push.apply(p,r||[]),c()}function c(){for(var t,e=0;e<p.length;e++){for(var n=p[e],a=!0,i=1;i<n.length;i++){var r=n[i];0!==u[r]&&(a=!1)}a&&(p.splice(e--,1),t=o(o.s=n[0]))}return t}var n={},u={43:0},p=[];function o(t){if(n[t])return n[t].exports;var e=n[t]={i:t,l:!1,exports:{}};return l[t].call(e.exports,e,e.exports,o),e.l=!0,e.exports}o.m=l,o.c=n,o.d=function(t,e,n){o.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:n})},o.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},o.t=function(e,t){if(1&t&&(e=o(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(o.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)o.d(n,a,function(t){return e[t]}.bind(null,a));return n},o.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return o.d(e,"a",e),e},o.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},o.p="/static-dist/";var e=window.webpackJsonp=window.webpackJsonp||[],a=e.push.bind(e);e.push=t,e=e.slice();for(var i=0;i<e.length;i++)t(e[i]);var f=a;p.push([513,0]),c()}({357:function(t,e,n){"use strict";n.d(e,"a",function(){return _});var a=n(2),r=n.n(a),i=n(0),s=n.n(i),o=n(1),l=n.n(o),c=n(8),u=n.n(c),p=n(9),f=n.n(p),d=n(5),h=n.n(d),g=n(103),v=n(54),y=n.n(v);function A(a){var i=function(){if("undefined"==typeof Reflect||!r.a)return!1;if(r.a.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(r()(Date,[],function(){})),!0}catch(t){return!1}}();return function(){var t,e,n=h()(a);return e=i?(t=h()(this).constructor,r()(n,arguments,t)):n.apply(this,arguments),f()(this,e)}}var _=function(t){u()(o,t);var r=A(o);function o(t){var e,n=t.element,a=t.slides,i=t.watermark;return s()(this,o),(e=r.call(this)).element=$(n),e.slides=a||[],e.watermark=i||"",e._KEY_ACTION_MAP={37:e._onPrev,39:e._onNext,38:e._onLast,40:e._onFirst},e.total=e.slides.length,e._page=0,e.placeholder="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC",e._init(),e}return l()(o,[{key:"_render",value:function(){this.element.html('\n      <div class="slide-player">\n        <div class="slide-player-body loading-background"></div>\n        <div class="slide-notice">\n          <div class="header">{{ \'site.data_last_picture\'|trans }}\n            <button type="button" class="close">×</button>\n          </div>\n        </div>\n      \n        <div class="slide-player-control clearfix">\n          <a href="javascript:" class="goto-first">\n            <span class="glyphicon glyphicon-step-backward"></span>\n          </a>\n          <a href="javascript:" class="goto-prev">\n            <span class="glyphicon glyphicon-chevron-left"></span>\n          </a>\n          <a href="javascript:" class="goto-next">\n            <span class="glyphicon glyphicon-chevron-right"></span>\n          </a>\n          <a href="javascript:" class="goto-last">\n            <span class="glyphicon glyphicon-step-forward"></span>\n          </a>\n          <a href="javascript:" class="fullscreen">\n            <span class="glyphicon glyphicon-fullscreen"></span>\n          </a>\n          <div class="goto-page-input">\n            <input type="text" class="goto-page form-control input-sm" value="1">&nbsp;/&nbsp;\n              <span class="total"></span>\n          </div>\n        </div>\n      </div>'),this.element.find(".total").text(this.total);var t=this.slides.reduce(function(t,e,n){return t+='<img data-src="'.concat(e,'" class="slide" data-page="').concat(n+1,'">')},"");this.element.find(".slide-player-body").html(t),this.watermark&&this.element.append('<div class="slide-player-watermark">'.concat(this.watermark,"</div>"))}},{key:"_init",value:function(){this._render(),this._bindEvents(),this._onFirst()}},{key:"_lazyLoad",value:function(t){for(var e=t;e<t+4&&!(e>this.total);e++){var n=this._getSlide(e);n.attr("src")||n.attr("src",n.data("src"))}}},{key:"_getSlide",value:function(t){return this.element.find(".slide-player-body .slide").eq(t-1)}},{key:"_bindEvents",value:function(){var n=this;$(document).on("keydown",function(t){n._KEY_ACTION_MAP[t.keyCode]&&n._KEY_ACTION_MAP[t.keyCode].call(n)}),this.element.on("click",".goto-next",function(t){return n._onNext(t)}),this.element.on("click",".goto-prev",function(t){return n._onPrev(t)}),this.element.on("click",".goto-first",function(t){return n._onFirst(t)}),this.element.on("click",".goto-last",function(t){return n._onLast(t)}),this.element.on("click",".fullscreen",function(t){return n._onFullScreen(t)}),this.element.on("change",".goto-page",function(t){return n._onChangePage(t)});var a=this;this.on("change",function(t){var e=t.current;t.before;e==a.total&&a.emit("end",{page:n.total})})}},{key:"_onNext",value:function(){this.page!==this.total?this.page++:this.emit("end",{page:this.total})}},{key:"_onPrev",value:function(){1!=this.page&&this.page--}},{key:"_onFirst",value:function(){this.page=1}},{key:"_onLast",value:function(){this.page=this.total}},{key:"_onFullScreen",value:function(){var t=!!navigator.userAgent.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);y.a.enabled?y.a.isFullscreen?y.a.toggle():y.a.request():t&&$("#task-content-iframe",parent.document).toggleClass("ios-full-screen")}},{key:"_onChangePage",value:function(t){this.page=$(t.target).val()}},{key:"page",get:function(){return this._page},set:function(t){var e=this,n=this.page,a=t;a>this.total&&(this.element.find(".goto-page").val(a),this._page=a),a<1&&(this.element.find(".goto-page").val(n),this._page=n),n&&this.element.find(".slide-player-body .slide").eq(n-1).removeClass("active");var i=this._getSlide(a);i.attr("src")?i.addClass("active"):(i.load(function(){e._page==i.data("page")&&i.addClass("active")}),i.attr("src",i.data("src"))),this._lazyLoad(a),this.element.find(".goto-page").val(a),this._page=a,this.emit("change",{current:a,before:n})}}]),o}(g.a)},513:function(t,e,n){"use strict";n.r(e);function a(t){var e=new i.a({element:"#activity-ppt-content",slides:o.data("slides").split(","),watermark:t});return"end"===o.data("finishType")&&(1===e.total?setTimeout(function(){r.emit("finish",{page:1})},1e3):e.once("end",function(t){r.emit("finish",t)})),e}var i=n(357),r=new(n(144).a),o=$("#activity-ppt-content"),s=o.data("watermarkUrl");void 0===s?a():$.get(s).then(function(t){a(t)}).fail(function(t){console.error(t)})}});