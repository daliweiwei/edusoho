!function(e){var t={};function n(o){if(t[o])return t[o].exports;var r=t[o]={i:o,l:!1,exports:{}};return e[o].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=e,n.c=t,n.d=function(e,t,o){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)n.d(o,r,function(t){return e[t]}.bind(null,r));return o},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="/static-dist/",n(n.s=866)}({866:function(e,t){if($('a[data-role="announcement-modal"]').click((function(){$("#modal").html("").load($(this).data("url"))})),$(".announcement-list").on("click","[data-role=delete]",(function(){return confirm(Translator.trans("announcement.delete_hint"))&&$.post($(this).data("url"),(function(){window.location.reload()})),!1})),$(".alert-edit").height()){var n=$(".alert-edit .alert-header"),o=n.find(".icon-click");o.hasClass("es-icon-chevronright")?o.data("toggle",!0):o.data("toggle",!1),n.click((function(){$(this).siblings(".details").animate({visibility:"toggle",opacity:"toggle",easing:"linear"});var e=$(this).find(".icon-click");e.data("toggle")&&e.parents(".alert-header").siblings(".details").height()?(e.addClass("es-icon-keyboardarrowdown").removeClass("es-icon-chevronright"),e.data("toggle",!1)):(e.addClass("es-icon-chevronright").removeClass("es-icon-keyboardarrowdown"),e.data("toggle",!0))}))}$(".annoucement-add-btn, .es-icon-edit").click((function(){$("#modal").modal("hide")}))}});