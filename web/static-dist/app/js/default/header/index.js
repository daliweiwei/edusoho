!function(n){function t(t){for(var o,a,c=t[0],s=t[1],l=t[2],u=0,d=[];u<c.length;u++)a=c[u],Object.prototype.hasOwnProperty.call(e,a)&&e[a]&&d.push(e[a][0]),e[a]=0;for(o in s)Object.prototype.hasOwnProperty.call(s,o)&&(n[o]=s[o]);for(f&&f(t);d.length;)d.shift()();return r.push.apply(r,l||[]),i()}function i(){for(var n,t=0;t<r.length;t++){for(var i=r[t],o=!0,c=1;c<i.length;c++){var s=i[c];0!==e[s]&&(o=!1)}o&&(r.splice(t--,1),n=a(a.s=i[0]))}return n}var o={},e={225:0},r=[];function a(t){if(o[t])return o[t].exports;var i=o[t]={i:t,l:!1,exports:{}};return n[t].call(i.exports,i,i.exports,a),i.l=!0,i.exports}a.m=n,a.c=o,a.d=function(n,t,i){a.o(n,t)||Object.defineProperty(n,t,{enumerable:!0,get:i})},a.r=function(n){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(n,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(n,"__esModule",{value:!0})},a.t=function(n,t){if(1&t&&(n=a(n)),8&t)return n;if(4&t&&"object"==typeof n&&n&&n.__esModule)return n;var i=Object.create(null);if(a.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:n}),2&t&&"string"!=typeof n)for(var o in n)a.d(i,o,function(t){return n[t]}.bind(null,o));return i},a.n=function(n){var t=n&&n.__esModule?function(){return n.default}:function(){return n};return a.d(t,"a",t),t},a.o=function(n,t){return Object.prototype.hasOwnProperty.call(n,t)},a.p="/static-dist/";var c=window.webpackJsonp=window.webpackJsonp||[],s=c.push.bind(c);c.push=t,c=c.slice();for(var l=0;l<c.length;l++)t(c[l]);var f=s;r.push([1596,0]),i()}({1596:function(n,t,i){"use strict";i.r(t);var o=i(4),e=i.n(o),r=i(5),a=i.n(r),c=i(99),s=i.n(c),l=i(48);new(function(){function n(){e()(this,n),this.isClicked=!1,this.init()}return a()(n,[{key:"init",value:function(){this.initEvent(),this.initNotification(),this.bindEvent()}},{key:"initEvent",value:function(){$("body").on("click",".js-user-nav-dropdown",(function(n){n.stopPropagation()}));var n=$(".js-switch-pc"),t=$(".js-switch-mobile");n.length&&n.on("click",(function(){s.a.set("PCVersion",1),window.location.reload()})),t.length&&t.on("click",(function(){s.a.remove("PCVersion"),window.location.reload()}))}},{key:"initNotification",value:function(){var n=$(".js-user-inform"),t="block"===n.css("display"),i=$(".js-inform-newNotification");n.length&&t&&this.api("newNotification",i,!0)}},{key:"bindEvent",value:function(){var n=this;$(".js-inform-tab").on("click",(function(t){return n.changeTab(t)})),$(".js-user-nav-dropdown").on("click",".js-inform-notification",(function(t){return n.toNotification(t)})),$(".js-back").on("click",(function(){return n.mobileHistory()}))}},{key:"changeTab",value:function(n){var t=$(n.target);this.isClicked=!0,n.preventDefault(),t.tab("show");var i=t.data("type"),o=t.hasClass("active"),e=$(".js-inform-conversation"),r=$(".js-inform-newNotification"),a="conversation"===i?e:r,c="conversation"!==i;$(".tab-pane.active").find(".js-inform-empty").length||o||this.api(i,a,c),t.addClass("active").siblings().removeClass("active")}},{key:"api",value:function(n,t,i){var o=this,e=this;l.a[n].search({before:function(){e.loadingShow()}}).then((function(n){o.informShow(t,n,i)})).catch((function(n){console.log("catch",n.responseJSON.message)}))}},{key:"informShow",value:function(n,t,i){this.isClicked&&n.empty(),$(".tab-pane.active").find(".js-inform-loading").addClass("hidden"),$(".js-inform-dropdown-body").css("overflow-y","auto"),n.append(t),i&&(n.find(".notification-footer").addClass("hidden"),n.find(".pull-left").addClass("hidden"))}},{key:"loadingShow",value:function(){var n=$(".tab-pane.active").find(".js-inform-loading"),t=cd.loading();n.removeClass("hidden"),$(".js-inform-dropdown-body").css("overflow-y","hidden"),n.html(t)}},{key:"toNotification",value:function(n){var t=$(n.currentTarget);window.location.href=t.data("url")}},{key:"mobileHistory",value:function(){1!==history.length?history.go(-1):location.href="/"}}]),n}())}});