!function(t){function e(e){for(var n,l,r=e[0],c=e[1],d=e[2],s=0,m=[];s<r.length;s++)l=r[s],Object.prototype.hasOwnProperty.call(o,l)&&o[l]&&m.push(o[l][0]),o[l]=0;for(n in c)Object.prototype.hasOwnProperty.call(c,n)&&(t[n]=c[n]);for(u&&u(e);m.length;)m.shift()();return i.push.apply(i,d||[]),a()}function a(){for(var t,e=0;e<i.length;e++){for(var a=i[e],n=!0,r=1;r<a.length;r++){var c=a[r];0!==o[c]&&(n=!1)}n&&(i.splice(e--,1),t=l(l.s=a[0]))}return t}var n={},o={343:0},i=[];function l(e){if(n[e])return n[e].exports;var a=n[e]={i:e,l:!1,exports:{}};return t[e].call(a.exports,a,a.exports,l),a.l=!0,a.exports}l.m=t,l.c=n,l.d=function(t,e,a){l.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:a})},l.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},l.t=function(t,e){if(1&e&&(t=l(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var a=Object.create(null);if(l.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var n in t)l.d(a,n,function(e){return t[e]}.bind(null,n));return a},l.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return l.d(e,"a",e),e},l.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},l.p="/static-dist/";var r=window.webpackJsonp=window.webpackJsonp||[],c=r.push.bind(r);r.push=e,r=r.slice();for(var d=0;d<r.length;d++)e(r[d]);var u=c;i.push([1507,0]),a()}({1507:function(t,e,a){"use strict";a.r(e);var n=a(29),o=a.n(n),i=a(35),l=a.n(i),r=a(605),c=a.n(r),d={data:function(){var t=$("[name=mode]").val(),e={};return"edit"===t&&((e=JSON.parse($("[name=item]").val())).questions=c()(e.questions)),{bank_id:$("[name=bank_id]").val(),mode:t,category:JSON.parse($("[name=category]").val()),subject:e,type:$("[name=type]").val(),showCKEditorData:{publicPath:$("[name=ckeditor_path]").val(),filebrowserImageUploadUrl:$("[name=ckeditor_image_upload_url]").val(),filebrowserImageDownloadUrl:$("[name=ckeditor_image_download_url]").val(),language:"zh_CN"===document.documentElement.lang?"zh-cn":document.documentElement.lang,jqueryPath:$("[name=jquery_path]").val()},showAttachment:$("[name=show_attachment]").val(),cdnHost:$("[name=cdn_host]").val(),uploadSDKInitData:{ui:"batch",multiple:!0,multitaskNum:3,fileNumLimit:3,sdkBaseUri:app.cloudSdkBaseUri,disableDataUpload:app.cloudDisableLogReport,disableSentry:app.cloudDisableLogReport,initUrl:$("[name=upload_init_url]").val(),finishUrl:$("[name=upload_finish_url]").val(),accept:JSON.parse($("[name=upload_accept]").val()),fileSingleSizeLimit:$("[name=upload_size_limit]").val(),locale:document.documentElement.lang},fileId:0,isDownload:!1,isDisable:null}},provide:function(){return{modeOrigin:"create",self:this}},methods:{getRepeatStem:function(t){var e=this,a="material"===t.data.type?t.data.material:t.data.questions[0].stem;return new l.a((function(t){$.ajax({url:"/question_bank/".concat(e.bank_id,"/checkQuestionDuplicative"),contentType:"application/json;charset=utf-8",type:"post",data:o()({material:a}),beforeSend:function(t){t.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done((function(e){t(e)}))}))},getData:function(t){var e=this,a=this;a.isDisable=!0,this.getRepeatStem(t).then((function(n){n?e.$confirm({title:Translator.trans("created.question.confirm.title"),okText:Translator.trans("created.question.confirm.ok.btn"),cancelText:Translator.trans("created.question.confirm.close.btn"),icon:"exclamation-circle",onOk:function(){a.isDisable=!1},onCancel:function(){a.createdItemQuestion(t)}}):a.createdItemQuestion(t)})).catch((function(t){console.log(t)}))},createdItemQuestion:function(t){var e=t.isAgain?"continue":"";(t=t.data).submission=e,t.type=$("[name=type]").val();var a=$("[name=mode]").val();$.ajax({url:"create"===a?$("[name=create_url]").val():$("[name=update_url]").val(),contentType:"application/json;charset=utf-8",type:"post",data:o()(t),beforeSend:function(t){t.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done((function(t){t.goto&&(window.location.href=t.goto)}))},goBack:function(){window.location.href=$("[name=back_url]").val()},deleteAttachment:function(t){this.fileId=t},previewAttachment:function(t){this.fileId=t},downloadAttachment:function(t){this.fileId=t},previewAttachmentCallback:function(){var t=this,e=this;return new l.a((function(a){$.ajax({url:$("[name=preview-attachment-url]").val(),type:"post",data:{id:t.fileId},beforeSend:function(t){t.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done((function(t){t.data.sdkBaseUri=app.cloudSdkBaseUri,t.data.disableDataUpload=app.cloudDisableLogReport,t.data.disableSentry=app.cloudDisableLogReport,a(t),e.fileId=0}))}))},downloadAttachmentCallback:function(){var t=this,e=this;return new l.a((function(a){$.ajax({url:$("[name=download-attachment-url]").val(),type:"post",data:{id:t.fileId},beforeSend:function(t){t.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done((function(t){a(t),e.fileId=0}))}))},deleteAttachmentCallback:function(){var t=this,e=this;return new l.a((function(a){$.ajax({url:$("[name=delete-attachment-url]").val(),type:"post",data:{id:t.fileId},beforeSend:function(t){t.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done((function(t){a(t),e.fileId=0}))}))}}},u=a(34),s=Object(u.a)(d,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"ibs-vue",attrs:{id:"app"}},["create"===t.mode?a("item-manage",{attrs:{bank_id:t.bank_id,mode:t.mode,category:t.category,type:t.type,showCKEditorData:t.showCKEditorData,showAttachment:t.showAttachment,cdnHost:t.cdnHost,isDownload:t.isDownload,isDisable:t.isDisable,uploadSDKInitData:t.uploadSDKInitData,deleteAttachmentCallback:t.deleteAttachmentCallback,previewAttachmentCallback:t.previewAttachmentCallback,downloadAttachmentCallback:t.downloadAttachmentCallback},on:{getData:t.getData,goBack:t.goBack,deleteAttachment:t.deleteAttachment,previewAttachment:t.previewAttachment,downloadAttachment:t.downloadAttachment}}):t._e(),t._v(" "),"edit"===t.mode?a("item-manage",{attrs:{bank_id:t.bank_id,mode:t.mode,category:t.category,subject:t.subject,type:t.type,showCKEditorData:t.showCKEditorData,showAttachment:t.showAttachment,cdnHost:t.cdnHost,isDownload:t.isDownload,isDisable:t.isDisable,uploadSDKInitData:t.uploadSDKInitData,deleteAttachmentCallback:t.deleteAttachmentCallback,previewAttachmentCallback:t.previewAttachmentCallback,downloadAttachmentCallback:t.downloadAttachmentCallback},on:{getData:t.getData,goBack:t.goBack,deleteAttachment:t.deleteAttachment,previewAttachment:t.previewAttachment,downloadAttachment:t.downloadAttachment}}):t._e()],1)}),[],!1,null,null,null).exports;if(Vue.config.productionTip=!1,"en"==app.lang){var m=local.default;itemBank.default.install(Vue,{locale:m})}new Vue({render:function(t){return t(s)}}).$mount("#app")},605:function(t,e,a){t.exports=a(735)},721:function(t,e,a){var n=a(220),o=a(410),i=a(249),l=a(440).f;t.exports=function(t){return function(e){for(var a,r=i(e),c=o(r),d=c.length,u=0,s=[];d>u;)a=c[u++],n&&!l.call(r,a)||s.push(t?[a,r[a]]:r[a]);return s}}},735:function(t,e,a){a(736),t.exports=a(77).Object.values},736:function(t,e,a){var n=a(117),o=a(721)(!1);n(n.S,"Object",{values:function(t){return o(t)}})}});