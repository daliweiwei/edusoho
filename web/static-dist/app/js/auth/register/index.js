!function(e){function a(a){for(var r,s,o=a[0],l=a[1],d=a[2],c=0,m=[];c<o.length;c++)s=o[c],Object.prototype.hasOwnProperty.call(i,s)&&i[s]&&m.push(i[s][0]),i[s]=0;for(r in l)Object.prototype.hasOwnProperty.call(l,r)&&(e[r]=l[r]);for(u&&u(a);m.length;)m.shift()();return n.push.apply(n,d||[]),t()}function t(){for(var e,a=0;a<n.length;a++){for(var t=n[a],r=!0,o=1;o<t.length;o++){var l=t[o];0!==i[l]&&(r=!1)}r&&(n.splice(a--,1),e=s(s.s=t[0]))}return e}var r={},i={137:0},n=[];function s(a){if(r[a])return r[a].exports;var t=r[a]={i:a,l:!1,exports:{}};return e[a].call(t.exports,t,t.exports,s),t.l=!0,t.exports}s.m=e,s.c=r,s.d=function(e,a,t){s.o(e,a)||Object.defineProperty(e,a,{enumerable:!0,get:t})},s.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},s.t=function(e,a){if(1&a&&(e=s(e)),8&a)return e;if(4&a&&"object"==typeof e&&e&&e.__esModule)return e;var t=Object.create(null);if(s.r(t),Object.defineProperty(t,"default",{enumerable:!0,value:e}),2&a&&"string"!=typeof e)for(var r in e)s.d(t,r,function(a){return e[a]}.bind(null,r));return t},s.n=function(e){var a=e&&e.__esModule?function(){return e.default}:function(){return e};return s.d(a,"a",a),a},s.o=function(e,a){return Object.prototype.hasOwnProperty.call(e,a)},s.p="/static-dist/";var o=window.webpackJsonp=window.webpackJsonp||[],l=o.push.bind(o);o.push=a,o=o.slice();for(var d=0;d<o.length;d++)a(o[d]);var u=l;n.push([1417,0]),t()}({1417:function(e,a,t){"use strict";t.r(a);var r=t(2),i=t.n(r),n=t(3),s=t.n(n),o=t(106),l=t(83),d=t(138);new(function(){function e(){i()(this,e),this.drag=$("#drag-btn").length?new l.a($("#drag-btn"),$(".js-jigsaw"),{limitType:"web_register"}):null,this.setValidateRule(),this.dragEvent(),this.initValidator(),this.inEventMobile(),this.initMobileMsgVeriCodeSendBtn(),this.initFieldVisitId(),this.submitFrom()}return s()(e,[{key:"dragEvent",value:function(){var e=this;this.drag&&this.drag.on("success",(function(a){e._smsBtnable()}))}},{key:"setValidateRule",value:function(){$.validator.addMethod("spaceNoSupport",(function(e,a){return e.indexOf(" ")<0}),$.validator.format(Translator.trans("validate.have_spaces")))}},{key:"initValidator",value:function(){var e=this;$("#register-form").validate(this._validataRules()),$.validator.addMethod("email_or_mobile_check",(function(a,t,r){var i=!1,n=/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(a),s=/^1\d{10}$/.test(a);return s?($(".email_mobile_msg").removeClass("hidden"),e.captchEnable||$(".js-drag-jigsaw").addClass("hidden")):($(".email_mobile_msg").addClass("hidden"),$(".js-drag-jigsaw").removeClass("hidden")),(n||s)&&(i=!0),$.validator.messages.email_or_mobile_check=Translator.trans("validate.mobile_or_email_message"),this.optional(t)||i}),Translator.trans("validate.email_or_mobile_check.message"))}},{key:"inEventMobile",value:function(){var e=this;$("#register_emailOrMobile").blur((function(){var a=$("#register_emailOrMobile").val();e.emSmsCodeValidate(a)})),$("#register_mobile").blur((function(){var a=$("#register_mobile").val();e.emSmsCodeValidate(a)}))}},{key:"initDragCaptchaCodeRule",value:function(){$(".js-drag-img").length&&$('[name="dragCaptchaToken"]').rules("add",{required:!0,messages:{required:Translator.trans("auth.register.drag_captcha_tips")}})}},{key:"_smsBtnDisable",value:function(){$(".js-sms-send-btn").addClass("disabled").attr("disabled",!0)}},{key:"_smsBtnable",value:function(){$(".js-sms-send-btn").removeClass("disabled").attr("disabled",!1)}},{key:"initSmsCodeRule",value:function(){$('[name="sms_code"]').rules("add",{required:!0,unsigned_integer:!0,rangelength:[6,6],es_remote:{type:"get"},messages:{rangelength:Translator.trans("validate.sms_code.message")}})}},{key:"initMobileMsgVeriCodeSendBtn",value:function(){var e=$(".js-sms-send-btn"),a=this;e.click((function(t){var r=(new d.a).getCoordinate(t,$("meta[name=csrf-token]").attr("content"));a._smsBtnDisable();var i=$("[name='verifiedMobile']").length?"verifiedMobile":"emailOrMobile";new o.a({element:e,url:$(this).data("smsUrl"),smsType:"sms_registration",dataTo:i,captcha:!0,captchaValidated:!0,captchaNum:"dragCaptchaToken",encryptedPoint:r,preSmsSend:function(){return!0},error:function(e){a.drag.initDragCaptcha()},additionalAction:function(t){return"captchaRequired"==t&&(e.attr("disabled",!0),$(".js-drag-jigsaw").removeClass("hidden"),a.captchEnable=!0,a.drag&&a.drag.initDragCaptcha(),!0)}})}))}},{key:"_validataRules",value:function(){var e=this;return{rules:{nickname:{required:!0,byte_minlength:4,byte_maxlength:18,nickname:!0,chinese_alphanumeric:!0,es_remote:{type:"get"}},password:this._passwordValidateRules(),email:{required:!0,email:!0,es_remote:{type:"get"}},invitedCode:{required:!1,reg_inviteCode:!0,es_remote:{type:"get"}},emailOrMobile:{required:!0,email_or_mobile_check:!0,es_remote:{type:"get",callback:function(a){a?e._smsBtnable():e._smsBtnDisable()}}},verifiedMobile:{required:!0,phone:!0,es_remote:{type:"get",callback:function(a){a?e._smsBtnable():e._smsBtnDisable()}}},dragCaptchaToken:{required:!0}},messages:{nickname:{required:Translator.trans("auth.register.nickname_required_error_hit")},verifiedMobile:{required:Translator.trans("validate.phone.message")},emailOrMobile:{required:Translator.trans("validate.phone_and_email_input.message")},email:{required:Translator.trans("validate.valid_email_input.message")},dragCaptchaToken:{required:Translator.trans("auth.register.drag_captcha_tips")},password:{required:Translator.trans("auth.register.password_required_error_hint")}}}}},{key:"_passwordValidateRules",value:function(){var e=$("#password_level").val(),a={spaceNoSupport:!0};return a["check_password_".concat(e)]=!0,a}},{key:"emSmsCodeValidate",value:function(e){/^1\d{10}$/.test(e)?(this.initSmsCodeRule(),$('[name="dragCaptchaToken"]').rules("remove")):(this.initDragCaptchaCodeRule(),$('[name="sms_code"]').rules("remove"))}},{key:"initFieldVisitId",value:function(){$(document).ready((function(){"undefined"!==window._VISITOR_ID&&$('[name="registerVisitId"]').val(window._VISITOR_ID)}))}},{key:"submitFrom",value:function(){var e=$("#register-form"),a=$("#modal");$("#register-btn").on("click",(function(){var t=e.validate(),r=$('input[name="agree_policy"]').prop("checked");t.form()&&(r||null==r?e.submit():(a.load("/register/agreement"),a.modal("show"),a.on("click",".js-agree-register",(function(){$('input[name="agree_policy"]').prop("checked",!0),a.modal("hide"),e.submit()})),a.on("click",".js-close-modal",(function(){a.modal("hide")}))))}))}}]),e}())},22:function(e,a){e.exports=jQuery}});