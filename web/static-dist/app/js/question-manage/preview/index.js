!function(t){function e(e){for(var a,i,l=e[0],c=e[1],u=e[2],s=0,f=[];s<l.length;s++)i=l[s],Object.prototype.hasOwnProperty.call(o,i)&&o[i]&&f.push(o[i][0]),o[i]=0;for(a in c)Object.prototype.hasOwnProperty.call(c,a)&&(t[a]=c[a]);for(d&&d(e);f.length;)f.shift()();return r.push.apply(r,u||[]),n()}function n(){for(var t,e=0;e<r.length;e++){for(var n=r[e],a=!0,l=1;l<n.length;l++){var c=n[l];0!==o[c]&&(a=!1)}a&&(r.splice(e--,1),t=i(i.s=n[0]))}return t}var a={},o={336:0},r=[];function i(e){if(a[e])return a[e].exports;var n=a[e]={i:e,l:!1,exports:{}};return t[e].call(n.exports,n,n.exports,i),n.l=!0,n.exports}i.m=t,i.c=a,i.d=function(t,e,n){i.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:n})},i.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},i.t=function(t,e){if(1&e&&(t=i(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(i.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var a in t)i.d(n,a,function(e){return t[e]}.bind(null,a));return n},i.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return i.d(e,"a",e),e},i.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},i.p="/static-dist/";var l=window.webpackJsonp=window.webpackJsonp||[],c=l.push.bind(l);l.push=e,l=l.slice();for(var u=0;u<l.length;u++)e(l[u]);var d=c;r.push([1331,0]),n()}({1331:function(t,e,n){"use strict";n.r(e);var a=n(33),o=n.n(a),r={data:function(){return{item:JSON.parse($("[name=item]").val()),showAttachment:$("[name=show_attachment]").val(),cdnHost:$("[name=cdn_host]").val(),fileId:0}},methods:{previewAttachment:function(t){this.fileId=t},downloadAttachment:function(t){console.log(t),this.fileId=t},previewAttachmentCallback:function(){var t=this,e=this;return new o.a((function(n){$.ajax({url:$("[name=preview-attachment-url]").val(),type:"post",data:{id:t.fileId},beforeSend:function(t){t.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done((function(t){t.data.sdkBaseUri=app.cloudSdkBaseUri,t.data.disableDataUpload=app.cloudDisableLogReport,t.data.disableSentry=app.cloudDisableLogReport,n(t),e.fileId=0}))}))},downloadAttachmentCallback:function(){var t=this,e=this;return new o.a((function(n){$.ajax({url:$("[name=download-attachment-url]").val(),type:"post",data:{id:t.fileId},beforeSend:function(t){t.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done((function(t){n(t),e.fileId=0}))}))}}},i=n(30),l=Object(i.a)(r,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"ibs-vue",attrs:{id:"app"}},[n("item-preview",{attrs:{item:t.item,showAttachment:t.showAttachment,cdnHost:t.cdnHost,previewAttachmentCallback:t.previewAttachmentCallback,downloadAttachmentCallback:t.downloadAttachmentCallback},on:{previewAttachment:t.previewAttachment,downloadAttachment:t.downloadAttachment}})],1)}),[],!1,null,null,null).exports,c=n(20);if(Vue.config.productionTip=!1,"en"==app.lang){var u=local.default;itemBank.default.install(Vue,{locale:u})}new Vue({render:function(t){return t(l)}}).$mount("#app"),(c.a.ie||c.a.ie10||c.a.ie11||c.a.edge)&&$(".modal").on("hide.bs.modal",(function(){window.location.reload()}))}});