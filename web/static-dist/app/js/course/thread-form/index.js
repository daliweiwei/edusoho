!function(t){function e(e){for(var n,i,u=e[0],c=e[1],l=e[2],p=0,f=[];p<u.length;p++)i=u[p],Object.prototype.hasOwnProperty.call(a,i)&&a[i]&&f.push(a[i][0]),a[i]=0;for(n in c)Object.prototype.hasOwnProperty.call(c,n)&&(t[n]=c[n]);for(s&&s(e);f.length;)f.shift()();return o.push.apply(o,l||[]),r()}function r(){for(var t,e=0;e<o.length;e++){for(var r=o[e],n=!0,u=1;u<r.length;u++){var c=r[u];0!==a[c]&&(n=!1)}n&&(o.splice(e--,1),t=i(i.s=r[0]))}return t}var n={},a={207:0},o=[];function i(e){if(n[e])return n[e].exports;var r=n[e]={i:e,l:!1,exports:{}};return t[e].call(r.exports,r,r.exports,i),r.l=!0,r.exports}i.m=t,i.c=n,i.d=function(t,e,r){i.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:r})},i.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},i.t=function(t,e){if(1&e&&(t=i(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var r=Object.create(null);if(i.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var n in t)i.d(r,n,function(e){return t[e]}.bind(null,n));return r},i.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return i.d(e,"a",e),e},i.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},i.p="/static-dist/";var u=window.webpackJsonp=window.webpackJsonp||[],c=u.push.bind(u);u.push=e,u=u.slice();for(var l=0;l<u.length;l++)e(u[l]);var s=c;o.push([978,0]),r()}({978:function(t,e,r){"use strict";r.r(e);var n=new(r(105).a)({drag:{limitType:"thread",bar:"#drag-btn",target:".js-jigsaw"}}),a=$("#thread-form"),o=null;1==$("input[name=enable_anti_brush_captcha]").val()&&(o={captchaClass:n,isShowCaptcha:0});var i=a.find(".js-btn-thread-save"),u=a.validate({captcha:o,rules:{"thread[title]":{required:!0,trim:!0,maxlength:30},"thread[content]":{required:!0,maxlength:1e4}},submitSuccess:function(t){n.hideDrag()},submitError:function(t){n.hideDrag(),i.button("reset")}});a.on("submitHandler",(function(){n.setType("thread")})),n.on("success",(function(t){"thread"==t.type&&(u.settings.captcha.isShowCaptcha=0,a.find("input[name=_dragCaptchaToken]").val(t.token),a.submit())})),$(".js-btn-thread-save").click((function(t){u.form()&&($(t.currentTarget).button("loading"),a.submit())}));var c=CKEDITOR.replace("thread_content",{toolbar:"Thread",fileSingleSizeLimit:app.fileSingleSizeLimit,filebrowserImageUploadUrl:$("#thread_content").data("imageUploadUrl")});c.on("change",(function(){$("#thread_content").val(c.getData())})),c.on("blur",(function(){$("#thread_content").val(c.getData()),u.form()}))}});