!function(e){var r={};function t(n){if(r[n])return r[n].exports;var a=r[n]={i:n,l:!1,exports:{}};return e[n].call(a.exports,a,a.exports,t),a.l=!0,a.exports}t.m=e,t.c=r,t.d=function(e,r,n){t.o(e,r)||Object.defineProperty(e,r,{enumerable:!0,get:n})},t.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},t.t=function(e,r){if(1&r&&(e=t(e)),8&r)return e;if(4&r&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(t.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&r&&"string"!=typeof e)for(var a in e)t.d(n,a,function(r){return e[r]}.bind(null,a));return n},t.n=function(e){var r=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(r,"a",r),r},t.o=function(e,r){return Object.prototype.hasOwnProperty.call(e,r)},t.p="/static-dist/",t(t.s=1122)}({1122:function(e,r){$("#approval-form").validate({rules:{idcard:{required:!0,maxlength:50,only_alphanumeric:!0},truename:{required:!0,chinese:!0,trim:!0,maxlength:25,minlength:2},faceImg:"required isImage limitSize",backImg:"required isImage limitSize"},messages:{faceImg:{required:Translator.trans("user.fields.idcard_front_placeholder")},backImg:{required:Translator.trans("user.fields.idcard_back_placeholder")}},submitHandler:function(e){var r=$(e).find('[type="submit"]');$(".js-input-val").each((function(){var e=$(this),r=e.val(),t=$.trim(r);e.val(t)})),r.button("loading"),e.submit()}}),cd.upload({el:".js-upload-input"}).on("success",(function(e,r,t){var n=$(e.currentTarget),a=$(n.data("target"));if(a.addClass("done").css({"background-image":"url(".concat(t,")")}),!a.find(".mask").length){a.append('<div class="mask"></div>')}})).on("error",(function(e){"FILE_SIZE_LIMIT"===e?cd.message({type:"danger",message:Translator.trans("uploader.size_2m_limit_hint")}):"FLIE_TYPE_LIMIT"===e&&cd.message({type:"danger",message:Translator.trans("uploader.type_denied_limit_hint")})}))}});