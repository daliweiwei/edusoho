!function(e){function t(t){for(var r,i,l=t[0],u=t[1],c=t[2],s=0,f=[];s<l.length;s++)i=l[s],Object.prototype.hasOwnProperty.call(a,i)&&a[i]&&f.push(a[i][0]),a[i]=0;for(r in u)Object.prototype.hasOwnProperty.call(u,r)&&(e[r]=u[r]);for(p&&p(t);f.length;)f.shift()();return o.push.apply(o,c||[]),n()}function n(){for(var e,t=0;t<o.length;t++){for(var n=o[t],r=!0,l=1;l<n.length;l++){var u=n[l];0!==a[u]&&(r=!1)}r&&(o.splice(t--,1),e=i(i.s=n[0]))}return e}var r={},a={237:0},o=[];function i(t){if(r[t])return r[t].exports;var n=r[t]={i:t,l:!1,exports:{}};return e[t].call(n.exports,n,n.exports,i),n.l=!0,n.exports}i.m=e,i.c=r,i.d=function(e,t,n){i.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},i.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},i.t=function(e,t){if(1&t&&(e=i(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(i.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)i.d(n,r,function(t){return e[t]}.bind(null,r));return n},i.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return i.d(t,"a",t),t},i.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},i.p="/static-dist/";var l=window.webpackJsonp=window.webpackJsonp||[],u=l.push.bind(l);l.push=t,l=l.slice();for(var c=0;c<l.length;c++)t(l[c]);var p=u;o.push([1029,0]),n()}({1029:function(e,t,n){"use strict";n.r(t);var r=n(134),a=new(n(105).a)({drag:{limitType:"groupThread",bar:"#drag-btn",target:".js-jigsaw"}}),o=$("#user-thread-form"),i="#groupthread-save-btn",l="thread_content";new r.a(o);var u=CKEDITOR.replace(l,{toolbar:"Full",fileSingleSizeLimit:app.fileSingleSizeLimit,filebrowserImageUploadUrl:$("#thread_content").data("imageUploadUrl"),allowedContent:!0,height:300});u.on("change",(function(){$("#thread_content").val(u.getData())})),u.on("blur",(function(){$("#thread_content").val(u.getData())}));var c=null;1==$("input[name=enable_anti_brush_captcha]").val()&&(c={captchaClass:a,isShowCaptcha:$(a.params.maskClass).length?1:0});var p=o.validate({currentDom:i,rules:{"thread[title]":{required:!0,minlength:2,maxlength:100},"thread[content]":{required:!0,minlength:2}},captcha:c});a.on("success",(function(e){p.settings.captcha.isShowCaptcha=0,$("input[name=_dragCaptchaToken]").val(e.token),o.submit()})),$(i).click((function(){p.form()&&o.submit()}))},22:function(e,t){e.exports=jQuery}});