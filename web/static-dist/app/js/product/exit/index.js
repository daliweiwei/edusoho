!function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(r,o,function(t){return e[t]}.bind(null,o));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="/static-dist/",n(n.s=1125)}({1125:function(e,t){var n=$("#refund-form"),r=n.parents(".modal"),o=n.find("#reasonNote"),a=n.find("#reasonNote-container"),i=n.find(".warnning"),d=n.find(".js-textarea-number"),l=$('button[type="submit"]');function u(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";o.val(e),d.text(e.length)}n.find('[name="reason[type]"]').on("change",(function(){var e=$(this).find("option:selected");"other"==e.val()?(u(""),a.removeClass("hide"),l.attr("disabled",!0)):(a.addClass("hide"),u(e.text()),""!==e.val()?l.removeAttr("disabled"):l.attr("disabled",!0)),i.text("")})),o.on("input",(function(){d.text(o.val().length),0==o.val().length?l.attr("disabled",!0):l.removeAttr("disabled")})),n.on("submit",(function(){return"reason"==n.find("#reasonType").val()?(i.text(Translator.trans("order.refund.reason_choose_hint")),!1):o.val().length>120?(i.text(Translator.trans("order.refund.reason_limit_hint")),!1):0==o.val().length?(i.text(Translator.trans("order.refund.reason_required_hint")),!1):void r.find("[type=submit]").button("loading").attr("disabled",!0)}))}});