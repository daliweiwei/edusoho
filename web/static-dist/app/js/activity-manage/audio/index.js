!function(l){function e(e){for(var t,r,n=e[0],i=e[1],a=e[2],o=0,u=[];o<n.length;o++)r=n[o],Object.prototype.hasOwnProperty.call(s,r)&&s[r]&&u.push(s[r][0]),s[r]=0;for(t in i)Object.prototype.hasOwnProperty.call(i,t)&&(l[t]=i[t]);for(f&&f(e);u.length;)u.shift()();return c.push.apply(c,a||[]),d()}function d(){for(var e,t=0;t<c.length;t++){for(var r=c[t],n=!0,i=1;i<r.length;i++){var a=r[i];0!==s[a]&&(n=!1)}n&&(c.splice(t--,1),e=o(o.s=r[0]))}return e}var r={},s={27:0},c=[];function o(e){if(r[e])return r[e].exports;var t=r[e]={i:e,l:!1,exports:{}};return l[e].call(t.exports,t,t.exports,o),t.l=!0,t.exports}o.m=l,o.c=r,o.d=function(e,t,r){o.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},o.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.t=function(t,e){if(1&e&&(t=o(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var r=Object.create(null);if(o.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var n in t)o.d(r,n,function(e){return t[e]}.bind(null,n));return r},o.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(t,"a",t),t},o.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},o.p="/static-dist/";var t=window.webpackJsonp=window.webpackJsonp||[],n=t.push.bind(t);t.push=e,t=t.slice();for(var i=0;i<t.length;i++)e(t[i]);var f=n;c.push([864,0]),d()}({15:function(e,t){e.exports=jQuery},864:function(e,t,r){"use strict";r.r(t);var n=r(18),l=r.n(n),i=r(6),d=r.n(i),a=r(0),o=r.n(a),u=r(1),s=r.n(u),c=r(68),f=r(105);new(function(){function e(){o()(this,e),Object(f.c)($('[name="ext[mediaId]"]')),this.initStep2Form(),this.autoValidatorLength(),this.initFileChooser()}return s()(e,[{key:"initStep2Form",value:function(){var e=$("#step2-form");e.data("validator");e.validate({groups:{nameGroup:"minute second"},rules:{title:{required:!0,maxlength:50,trim:!0,course_title:!0},minute:"required unsigned_integer unsigned_integer",second:"required second_range unsigned_integer","ext[mediaId]":"required"},messages:{minute:{required:Translator.trans("activity.audio_manage.length_required_error_hint"),unsigned_integer:Translator.trans("activity.audio_manage.length_required_error_hint")},second:{required:Translator.trans("activity.audio_manage.length_required_error_hint"),second_range:Translator.trans("activity.audio_manage.length_required_error_hint"),unsigned_integer:Translator.trans("activity.audio_manage.length_required_error_hint")},"ext[mediaId]":Translator.trans("activity.audio_manage.media_error_hint")}})}},{key:"autoValidatorLength",value:function(){$(".js-length").blur(function(){var e,t,r=$("#step2-form").data("validator");r&&r.form()&&(e=0|d()($("#minute").val()),t=0|d()($("#second").val()),$("#length").val(60*e+t))})}},{key:"initFileChooser",value:function(){var e=new c.a;console.log(e);e.on("select",function(e){Object(f.a)();var t,r,n,i,a,o,u;0!==(t=e).length&&void 0!==t.length&&(r=$("#minute"),n=$("#second"),i=$("#length"),a=d()(t.length),o=d()(a/60),u=a%60,r.val(o),n.val(u),i.val(a),t.minute=o,t.second=u),$('[name="media"]').val(l()(t)),$('[name="ext[mediaId]"]').val(e.source),$("#step2-form").valid(),"self"==e.source?($("#ext_mediaId").val(e.id),$("#ext_mediaUri").val("")):($("#ext_mediaId").val(""),$("#ext_mediaUri").val(e.uri))})}}]),e}())}});