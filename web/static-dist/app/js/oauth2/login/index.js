!function(r){var n={};function a(e){if(n[e])return n[e].exports;var t=n[e]={i:e,l:!1,exports:{}};return r[e].call(t.exports,t,t.exports,a),t.l=!0,t.exports}a.m=r,a.c=n,a.d=function(e,t,r){a.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},a.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.t=function(t,e){if(1&e&&(t=a(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var r=Object.create(null);if(a.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var n in t)a.d(r,n,function(e){return t[e]}.bind(null,n));return r},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,"a",t),t},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.p="/static-dist/",a(a.s=721)}({153:function(e,t,r){"use strict";r.d(t,"a",function(){return n});var n=function(e,t){e.keypress(function(e){13==e.which&&(t.trigger("click"),e.preventDefault())})}},721:function(e,t,r){"use strict";r.r(t);var n=r(153),a=$("#third-party-login-form"),i=$(".js-submit-btn"),o={mobile:{rules:{account:{required:!0,phone:!0}},messages:{required:Translator.trans("validate.phone.message")}},email:{rules:{account:{required:!0,maxlength:32,email:!0}},messages:{required:Translator.trans("validate.valid_email_input.message")}},email_or_mobile:{rules:{account:{required:!0,maxlength:32,email_or_mobile_check:!0}},messages:{required:Translator.trans("validate.phone_and_email_input.message")}}},u=$(".js-third-party-type").data("type"),l=a.validate(o[u]);Object(n.a)(a,i),i.click(function(e){var t;l.form()&&($(e.target).button("loading"),t=/^([a-zA-Z0-9_.\-+])+@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test($("input[name='account']").val())?"email":"mobile",$("#accountType").val(t),a.submit())})}});