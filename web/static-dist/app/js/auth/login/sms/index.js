!function(e){function t(t){for(var r,s,i=t[0],l=t[1],d=t[2],u=0,f=[];u<i.length;u++)s=i[u],Object.prototype.hasOwnProperty.call(a,s)&&a[s]&&f.push(a[s][0]),a[s]=0;for(r in l)Object.prototype.hasOwnProperty.call(l,r)&&(e[r]=l[r]);for(c&&c(t);f.length;)f.shift()();return o.push.apply(o,d||[]),n()}function n(){for(var e,t=0;t<o.length;t++){for(var n=o[t],r=!0,i=1;i<n.length;i++){var l=n[i];0!==a[l]&&(r=!1)}r&&(o.splice(t--,1),e=s(s.s=n[0]))}return e}var r={},a={133:0},o=[];function s(t){if(r[t])return r[t].exports;var n=r[t]={i:t,l:!1,exports:{}};return e[t].call(n.exports,n,n.exports,s),n.l=!0,n.exports}s.m=e,s.c=r,s.d=function(e,t,n){s.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},s.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},s.t=function(e,t){if(1&t&&(e=s(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(s.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)s.d(n,r,function(t){return e[t]}.bind(null,r));return n},s.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return s.d(t,"a",t),t},s.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},s.p="/static-dist/";var i=window.webpackJsonp=window.webpackJsonp||[],l=i.push.bind(i);i.push=t,i=i.slice();for(var d=0;d<i.length;d++)t(i[d]);var c=l;o.push([1020,0]),n()}({1020:function(e,t,n){"use strict";n.r(t);var r=n(97),a=n(48),o=n(9),s=n(358),i=n(153),l=$("#login-form"),d=$("#drag-btn").length?new r.a($("#drag-btn"),$(".js-jigsaw"),{limitType:"user_login"}):null,c=l.validate({rules:{mobile:{required:!0,phone:!0},sms_code:{required:!0,unsigned_integer:!0,rangelength:[6,6]},dragCaptchaToken:{required:!0}},messages:{dragCaptchaToken:{required:Translator.trans("auth.register.drag_captcha_tips")},sms_code:{required:Translator.trans("auth.password_reset.sms_code_required_hint"),rangelength:Translator.trans("auth.password_reset.sms_code_validate_hint")}}});$(".js-btn-login").click((function(e){var t=$("#modal"),n=$('input[name="agree_policy"]').prop("checked");if(c.form()){if(n||null==n)return $(e.currentTarget).button("loadding"),void l.submit();t.load("/register/agreement"),t.modal("show"),t.on("click",".js-agree-register",(function(){$('input[name="agree_policy"]').prop("checked",!0),t.modal("hide"),l.submit()})),t.on("click",".js-close-modal",(function(){t.modal("hide")}))}}));var u;(u=$(".js-sms-send")).click((function(){var e=(new i.a).getCoordinate(event,$("meta[name=csrf-token]").attr("content"));if(c.element($('[name="dragCaptchaToken"]'))&&c.element($('[name="mobile"]'))){if(u.hasClass("disabled"))return;u.addClass("disabled"),a.a.fastLoginSms.send({data:{type:"sms_login",mobile:$("#mobile").val(),allowNotExistMobile:0,encryptedPoint:e,dragCaptchaToken:$('[name="dragCaptchaToken"]').val()}}).then((function(e){Object(o.a)("success",Translator.trans("notify.sms_send_success.message")),u.removeClass("disabled"),Object(s.a)($(".js-sms-send"),$("#js-fetch-btn-text"),120),$('[name="sms_token"]').val(e.smsToken)})).catch((function(){u.removeClass("disabled"),d.initDragCaptcha()}))}}))},24:function(e,t){e.exports=jQuery},358:function(e,t,n){"use strict";n.d(t,"a",(function(){return r}));var r=function e(t,n,r){var a=arguments.length>3&&void 0!==arguments[3]?arguments[3]:function(){};if(t.addClass("disabled").attr("disabled",!0),n.data("count-down-text")||n.data("count-down-text",n.text()),n.text(r+" 秒后重新获取"),--r<0)return t.removeClass("disabled").attr("disabled",!1),n.text(n.data("count-down-text")),void a();setTimeout((function(){e(t,n,r,a)}),1e3)}}});