!function(e){var t={};function r(n){if(t[n])return t[n].exports;var a=t[n]={i:n,l:!1,exports:{}};return e[n].call(a.exports,a,a.exports,r),a.l=!0,a.exports}r.m=e,r.c=t,r.d=function(e,t,n){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)r.d(n,a,function(t){return e[t]}.bind(null,a));return n},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="/static-dist/",r(r.s=1061)}({1061:function(e,t){$(".js-report-submit").on("click",(function(){var e=$(this),t=e.data("targetType"),r=e.data("targetId"),n=e.data("url");void 0!==$("[name=reportTag]:checked").val()?$.ajax({url:n,type:"POST",async:!1,dataType:"json",data:{targetType:t,targetId:r,reportTag:$("[name=reportTag]:checked").val()},success:function(t){var r=e.data("contentTarget"),n=e.data("modalTarget");$("#".concat(r)).append('<span style="color: red;">(已举报)</span>'),$("#".concat(n)).remove(),cd.message({type:"success",message:"举报成功"}),$(".js-review-report").html('<div>\n            <div class="text-center text-normal">你的举报我们已经收到了，会尽快处理……</div>\n            <div class="text-right">\n              <a data-dismiss="modal" aria-hidden="true" class="btn btn-info">关闭</a>\n            </div>\n          </div>\n        ')},error:function(){}}):cd.message({type:"warning",message:"至少选择一个举报项"})}))}});