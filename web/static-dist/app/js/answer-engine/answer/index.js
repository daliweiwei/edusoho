!function(c){function e(e){for(var t,a,n=e[0],o=e[1],r=e[2],i=0,s=[];i<n.length;i++)a=n[i],Object.prototype.hasOwnProperty.call(d,a)&&d[a]&&s.push(d[a][0]),d[a]=0;for(t in o)Object.prototype.hasOwnProperty.call(o,t)&&(c[t]=o[t]);for(p&&p(e);s.length;)s.shift()();return u.push.apply(u,r||[]),l()}function l(){for(var e,t=0;t<u.length;t++){for(var a=u[t],n=!0,o=1;o<a.length;o++){var r=a[o];0!==d[r]&&(n=!1)}n&&(u.splice(t--,1),e=i(i.s=a[0]))}return e}var a={},d={77:0},u=[];function i(e){if(a[e])return a[e].exports;var t=a[e]={i:e,l:!1,exports:{}};return c[e].call(t.exports,t,t.exports,i),t.l=!0,t.exports}i.m=c,i.c=a,i.d=function(e,t,a){i.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:a})},i.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},i.t=function(t,e){if(1&e&&(t=i(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var a=Object.create(null);if(i.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var n in t)i.d(a,n,function(e){return t[e]}.bind(null,n));return a},i.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return i.d(t,"a",t),t},i.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},i.p="/static-dist/";var t=window.webpackJsonp=window.webpackJsonp||[],n=t.push.bind(t);t.push=e,t=t.slice();for(var o=0;o<t.length;o++)e(t[o]);var p=n;u.push([756,0]),l()}({294:function(e,t){e.exports=function(e){"use strict";if(!window||window.window!==window)throw new Error("This module is only available in browser");var t=window.Blob||window.MozBlob||window.WebKitBlob;if(!t)throw new Error("Blob was not supported");var a=e.match(/^data:((.*?)(;charset=.*?)?)(;base64)?,/);if(!a)throw new Error("invalid dataURI");for(var n=a[2]?a[1]:"text/plain"+(a[3]||";charset=utf-8"),o=!!a[4],r=e.slice(a[0].length),i=(o?atob:decodeURIComponent)(r),s=[],c=0;c<i.length;c++)s.push(i.charCodeAt(c));return new t([new Uint8Array(s)],{type:n})}},756:function(e,t,a){"use strict";a.r(t);var n,o=a(20),r=a.n(o),i=a(18),s=a.n(i),c=a(16),l=a(134),d=a(294),u=a.n(d),p={data:function(){var e=0<$("[name=token]").length&&""!==$("[name=token]").val();return{showCKEditorData:{publicPath:$("[name=ckeditor_path]").val(),filebrowserImageUploadUrl:$("[name=ckeditor_image_upload_url]").val(),filebrowserImageDownloadUrl:$("[name=ckeditor_image_download_url]").val(),language:"zh_CN"===document.documentElement.lang?"zh-cn":document.documentElement.lang,jqueryPath:$("[name=jquery_path]").val()},showAttachment:$("[name=show_attachment]").val(),cdnHost:$("[name=cdn_host]").val(),uploadSDKInitData:{sdkBaseUri:app.cloudSdkBaseUri,disableDataUpload:app.cloudDisableLogReport,disableSentry:app.cloudDisableLogReport,initUrl:$("[name=upload_init_url]").val(),finishUrl:$("[name=upload_finish_url]").val(),accept:JSON.parse($("[name=upload_accept]").val()),fileSingleSizeLimit:$("[name=upload_size_limit]").val(),locale:document.documentElement.lang},fileId:0,inspectionOpen:e,isNotMobile:!Object(c.f)()}},created:function(){this.emitter=new l.a,this.emitter.emit("doing",{data:""});var t=this;$.ajax({url:"/api/continue_answer",type:"POST",async:!1,headers:{Accept:"application/vnd.edusoho.v2+json"},data:{answer_record_id:$("[name='answer_record_id']").val()},beforeSend:function(e){e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done(function(e){t.assessment=e.assessment,t.answerRecord=e.answer_record,t.answerScene=e.answer_scene,t.assessmentResponse=e.assessment_response})},methods:{getAnswerData:function(e){var t=this;$.ajax({url:"/api/submit_answer",contentType:"application/json;charset=utf-8",type:"POST",headers:{Accept:"application/vnd.edusoho.v2+json"},data:s()(e),beforeSend:function(e){e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done(function(e){t.emitter.emit("finish",{data:""}),location.replace($("[name=submit_goto_url]").val())})},reachTimeSubmitAnswerData:function(e){var t=this;$.ajax({url:"/api/submit_answer",contentType:"application/json;charset=utf-8",type:"POST",headers:{Accept:"application/vnd.edusoho.v2+json"},data:s()(e),beforeSend:function(e){e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done(function(e){t.emitter.emit("finish",{data:""}),cd.confirm({title:"答题结束",content:"答题已结束，您的试卷已提交，请点击下面的按钮查看结果！",okText:"查看结果",cancelText:"返回",className:""}).on("ok",function(){location.replace($("[name=submit_goto_url]").val())}).on("cancel",function(){location.replace($("[name=submit_goto_url]").val())})})},timeSaveAnswerData:function(e){$.ajax({url:"/api/save_answer",contentType:"application/json;charset=utf-8",headers:{Accept:"application/vnd.edusoho.v2+json"},type:"POST",data:s()(e),beforeSend:function(e){e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done(function(e){})},saveAnswerData:function(e){$.ajax({url:"/api/save_answer",contentType:"application/json;charset=utf-8",type:"POST",headers:{Accept:"application/vnd.edusoho.v2+json"},data:s()(e),beforeSend:function(e){e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done(function(e){parent.location.href=$("[name=save_goto_url]").val()})},deleteAttachment:function(e,t){t&&(this.fileId=e)},previewAttachment:function(e){this.fileId=e},downloadAttachment:function(e){this.fileId=e},previewAttachmentCallback:function(){var e=this,a=this;return new r.a(function(t){$.ajax({url:$("[name=preview-attachment-url]").val(),type:"post",data:{id:e.fileId},beforeSend:function(e){e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done(function(e){e.data.sdkBaseUri=app.cloudSdkBaseUri,e.data.disableDataUpload=app.cloudDisableLogReport,e.data.disableSentry=app.cloudDisableLogReport,t(e),a.fileId=0})})},downloadAttachmentCallback:function(){var e=this,a=this;return new r.a(function(t){$.ajax({url:$("[name=download-attachment-url]").val(),type:"post",data:{id:e.fileId},beforeSend:function(e){e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done(function(e){t(e),a.fileId=0})})},deleteAttachmentCallback:function(){var e=this,a=this;return new r.a(function(t){$.ajax({url:$("[name=delete-attachment-url]").val(),type:"post",data:{id:e.fileId},beforeSend:function(e){e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done(function(e){t(e),a.fileId=0})})},readyHandler:function(){var e=$("[name=img-url]");0<e.length&&""!==e.val()?this.$refs.inspection.captureModal({token:$("[name=token]").val(),faceUrl:e.val()}):this.$refs.inspection.captureModal({token:$("[name=token]").val()})},saveCheatRecord:function(e){var t=new FormData;t.append("status",e.status),t.append("level",e.level),t.append("duration",e.duration),t.append("behavior",e.behavior),t.append("picture",u()(e.picture)),$.ajax({url:$("[name=inspection-save-url]").val(),type:"POST",contentType:!1,processData:!1,data:t,beforeSend:function(e){e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))},success:function(e){console.log(e)}})},captureHandler:function(e){new Image(480).src=e.capture;var t=new FormData;t.append("picture",u()(e.capture)),$.ajax({url:$("[name=upload-url]").val(),type:"POST",contentType:!1,processData:!1,data:t,beforeSend:function(e){e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))},success:function(){}})}}},f=a(93),m=Object(f.a)(p,function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"ibs-vue",attrs:{id:"app"}},[a("div",{attrs:{id:"cd-modal"}}),e._v(" "),a("item-engine",{attrs:{assessment:e.assessment,answerRecord:e.answerRecord,answerScene:e.answerScene,showCKEditorData:e.showCKEditorData,assessmentResponse:e.assessmentResponse,showAttachment:e.showAttachment,cdnHost:e.cdnHost,uploadSDKInitData:e.uploadSDKInitData,deleteAttachmentCallback:e.deleteAttachmentCallback,previewAttachmentCallback:e.previewAttachmentCallback,downloadAttachmentCallback:e.downloadAttachmentCallback},on:{getAnswerData:e.getAnswerData,saveAnswerData:e.saveAnswerData,timeSaveAnswerData:e.timeSaveAnswerData,reachTimeSubmitAnswerData:e.reachTimeSubmitAnswerData,deleteAttachment:e.deleteAttachment,previewAttachment:e.previewAttachment,downloadAttachment:e.downloadAttachment}},[e.inspectionOpen&&e.isNotMobile?a("template",{slot:"inspection"},[a("inspection-control",{ref:"inspection",attrs:{mode:"watching"},on:{ready:e.readyHandler,cheatHappened:e.saveCheatRecord,faceCaptured:e.captureHandler}})],1):e._e()],2)],1)},[],!1,null,null,null).exports;jQuery.support.cors=!0,Object(c.f)()&&$("body, html").css({height:"100%",overflow:"auto"}),Vue.config.productionTip=!1,"en"==app.lang&&(n=local.default,itemBank.default.install(Vue,{locale:n})),new Vue({render:function(e){return e(m)}}).$mount("#app")}});