!function(e){function t(t){for(var a,l,o=t[0],c=t[1],s=t[2],h=0,u=[];h<o.length;h++)l=o[h],Object.prototype.hasOwnProperty.call(i,l)&&i[l]&&u.push(i[l][0]),i[l]=0;for(a in c)Object.prototype.hasOwnProperty.call(c,a)&&(e[a]=c[a]);for(d&&d(t);u.length;)u.shift()();return r.push.apply(r,s||[]),n()}function n(){for(var e,t=0;t<r.length;t++){for(var n=r[t],a=!0,o=1;o<n.length;o++){var c=n[o];0!==i[c]&&(a=!1)}a&&(r.splice(t--,1),e=l(l.s=n[0]))}return e}var a={},i={124:0},r=[];function l(t){if(a[t])return a[t].exports;var n=a[t]={i:t,l:!1,exports:{}};return e[t].call(n.exports,n,n.exports,l),n.l=!0,n.exports}l.m=e,l.c=a,l.d=function(e,t,n){l.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},l.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},l.t=function(e,t){if(1&t&&(e=l(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(l.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)l.d(n,a,function(t){return e[t]}.bind(null,a));return n},l.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return l.d(t,"a",t),t},l.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},l.p="/static-dist/";var o=window.webpackJsonp=window.webpackJsonp||[],c=o.push.bind(o);o.push=t,o=o.slice();for(var s=0;s<o.length;s++)t(o[s]);var d=c;r.push([1588,0]),n()}({1588:function(e,t,n){"use strict";n.r(t);var a=n(372),i=n(373),r=n(5),l=n.n(r),o=n(6),c=n.n(o),s=function(){function e(t){l()(this,e),this.element=$(t.element),this.batchDelete()}return c()(e,[{key:"batchDelete",value:function(){var e=$(this.element);this.element.on("click","[data-role=batch-delete]",(function(){var t=$(this),n=t.data("name"),a=[];e.find("[data-role=batch-item]:checked").each((function(){a.push(this.value)})),0!==a.length?confirm(Translator.trans("admin.util.batch_delete.delete_hint",{ids:a.length,name:n}))&&($(this.element).find(".btn").addClass("disabled"),cd.message({type:"info",message:Translator.trans("admin.util.batch_delete.deleting_hint",{name:n})}),$.post(t.data("url"),{ids:a},(function(){window.location.reload()}))):cd.message({type:"danger",message:Translator.trans("admin.util.batch_delete.checked_empty_hint",{name:n})})}))}}]),e}(),d=function(){function e(t){l()(this,e),this.element=$(t.element),this.itemDelete()}return c()(e,[{key:"itemDelete",value:function(){this.element.on("click","[data-role=item-delete]",(function(e){var t=$(this),n=t.data("name"),a=t.data("message");a||(a=Translator.trans("admin.util.item_delete.delete_hint",{name:n})),confirm(a)&&$.post(t.data("url"),(function(){$.isFunction(e)?e.call($element,$item):(t.parents("[data-role=item]").remove(),cd.message({type:"success",message:Translator.trans("admin.util.item_delete.delete_success_hint",{name:n})}))}))}))}}]),e}(),h=$("#review-table-container"),u=$("#review-table");new a.a({element:u}),new i.a({element:h}),new s({element:h}),new d({element:h})},372:function(e,t,n){"use strict";n.d(t,"a",(function(){return o}));var a=n(5),i=n.n(a),r=n(6),l=n.n(r),o=function(){function e(t){i()(this,e),this.element=$(t.element),this.shortText(),this.longText()}return l()(e,[{key:"shortText",value:function(){this.element.on("click",".short-text",(function(){$(this).slideUp("fast").parents(".short-long-text").find(".long-text").slideDown("fast")}))}},{key:"longText",value:function(){this.element.on("click",".long-text",(function(){$(this).slideUp("fast").parents(".short-long-text").find(".short-text").slideDown("fast")}))}}]),e}()},373:function(e,t,n){"use strict";n.d(t,"a",(function(){return o}));var a=n(5),i=n.n(a),r=n(6),l=n.n(r),o=function(){function e(t){i()(this,e),this.element=$(t.element),this.batchSelect(),this.batchItem()}return l()(e,[{key:"batchSelect",value:function(){var e=$(this.element);this.element.on("click","[data-role=batch-select]",(function(){!0===$(this).is(":checked")?($(".js-batch-tag-btn, .js-batch-delete-btn, .js-batch-download").attr("disabled",!1),e.find("[data-role=batch-select], [data-role=batch-item]").prop("checked",!0)):($(".js-batch-tag-btn, .js-batch-delete-btn, .js-batch-download").attr("disabled",!0),e.find("[data-role=batch-select], [data-role=batch-item]").prop("checked",!1))}))}},{key:"batchItem",value:function(){var e=$(this.element);this.element.on("click","[data-role=batch-item]",(function(){var t=e.find("[data-role=batch-item]").length,n=0;e.find("[data-role=batch-item]").each((function(){$(this).is(":checked")&&n++})),n===t?e.find("[data-role=batch-select]").prop("checked",!0):e.find("[data-role=batch-select]").prop("checked",!1),0!==n?$(".js-batch-tag-btn, .js-batch-delete-btn, .js-batch-download").attr("disabled",!1):$(".js-batch-tag-btn, .js-batch-delete-btn, .js-batch-download").attr("disabled",!0)}))}}]),e}()}});