!function(e){function t(t){for(var a,s,i=t[0],c=t[1],d=t[2],u=0,p=[];u<i.length;u++)s=i[u],Object.prototype.hasOwnProperty.call(o,s)&&o[s]&&p.push(o[s][0]),o[s]=0;for(a in c)Object.prototype.hasOwnProperty.call(c,a)&&(e[a]=c[a]);for(l&&l(t);p.length;)p.shift()();return r.push.apply(r,d||[]),n()}function n(){for(var e,t=0;t<r.length;t++){for(var n=r[t],a=!0,i=1;i<n.length;i++){var c=n[i];0!==o[c]&&(a=!1)}a&&(r.splice(t--,1),e=s(s.s=n[0]))}return e}var a={},o={113:0},r=[];function s(t){if(a[t])return a[t].exports;var n=a[t]={i:t,l:!1,exports:{}};return e[t].call(n.exports,n,n.exports,s),n.l=!0,n.exports}s.m=e,s.c=a,s.d=function(e,t,n){s.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},s.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},s.t=function(e,t){if(1&t&&(e=s(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(s.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)s.d(n,a,function(t){return e[t]}.bind(null,a));return n},s.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return s.d(t,"a",t),t},s.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},s.p="/static-dist/";var i=window.webpackJsonp=window.webpackJsonp||[],c=i.push.bind(i);i.push=t,i=i.slice();for(var d=0;d<i.length;d++)t(i[d]);var l=c;r.push([1323,0]),n()}({1323:function(e,t,n){"use strict";n.r(t);var a=n(33),o=n.n(a),r=n(26),s=n.n(r),i=n(10),c=n.n(i),d=n(20),l=n(177),u=n(382),p=n.n(u),m=n(429),w={data:function(){var e=$("[name=token]").length>0&&""!==$("[name=token]").val(),t=Object(m.a)();return{showCKEditorData:{publicPath:$("[name=ckeditor_path]").val(),filebrowserImageUploadUrl:$("[name=ckeditor_image_upload_url]").val(),filebrowserImageDownloadUrl:$("[name=ckeditor_image_download_url]").val(),language:"zh_CN"===document.documentElement.lang?"zh-cn":document.documentElement.lang,jqueryPath:$("[name=jquery_path]").val()},showAttachment:$("[name=show_attachment]").val(),showSaveProgressBtn:void 0===$("[name=show_save_progress_btn]").val()?0:c()($("[name=show_save_progress_btn]").val()),cdnHost:$("[name=cdn_host]").val(),uploadSDKInitData:{sdkBaseUri:app.cloudSdkBaseUri,disableDataUpload:app.cloudDisableLogReport,disableSentry:app.cloudDisableLogReport,initUrl:$("[name=upload_init_url]").val(),finishUrl:$("[name=upload_finish_url]").val(),accept:JSON.parse($("[name=upload_accept]").val()),fileSingleSizeLimit:$("[name=upload_size_limit]").val(),locale:document.documentElement.lang},fileId:0,inspectionOpen:e,isNotMobile:!Object(d.f)(),errorMessage:t.ok?"":t.message,getCurrentTime:function(){var e=Date.parse(new Date);return $.ajax({type:"GET",beforeSend:function(e){e.setRequestHeader("Accept","application/vnd.edusoho.v2+json")},url:"/api/system/timestamp",async:!1,success:function(t){isNaN(t)||(e=1e3*t)},error:function(e){console.log(e)}}),e}}},created:function(){this.emitter=new l.a,this.emitter.emit("doing",{data:""});var e=this;$.ajax({url:"/api/continue_answer",type:"POST",async:!1,headers:{Accept:"application/vnd.edusoho.v2+json"},data:{answer_record_id:$("[name='answer_record_id']").val()},beforeSend:function(e){e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done((function(t){e.assessment=t.assessment,e.answerRecord=t.answer_record,e.answerScene=t.answer_scene,e.assessmentResponse=t.assessment_response}))},methods:{getAnswerData:function(e){var t=this;$.ajax({url:"/api/submit_answer",contentType:"application/json;charset=utf-8",type:"POST",headers:{Accept:"application/vnd.edusoho.v2+json"},data:s()(e),beforeSend:function(e){e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done((function(e){t.emitter.emit("finish",{data:""}),location.replace($("[name=submit_goto_url]").val())}))},reachTimeSubmitAnswerData:function(e){var t=this;$.ajax({url:"/api/submit_answer",contentType:"application/json;charset=utf-8",type:"POST",headers:{Accept:"application/vnd.edusoho.v2+json"},data:s()(e),beforeSend:function(e){e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done((function(e){t.emitter.emit("finish",{data:""}),cd.confirm({title:"答题结束",content:"答题已结束，您的试卷已提交，请点击下面的按钮查看结果！",okText:"查看结果",cancelText:"返回",className:""}).on("ok",(function(){location.replace($("[name=submit_goto_url]").val())})).on("cancel",(function(){location.replace($("[name=submit_goto_url]").val())}))}))},timeSaveAnswerData:function(e){$.ajax({url:"/api/save_answer",contentType:"application/json;charset=utf-8",headers:{Accept:"application/vnd.edusoho.v2+json"},type:"POST",data:s()(e),beforeSend:function(e){e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done((function(e){}))},saveAnswerData:function(e){$.ajax({url:"/api/save_answer",contentType:"application/json;charset=utf-8",type:"POST",headers:{Accept:"application/vnd.edusoho.v2+json"},data:s()(e),beforeSend:function(e){e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done((function(e){parent.location.href=$("[name=save_goto_url]").val()}))},deleteAttachment:function(e,t){t&&(this.fileId=e)},previewAttachment:function(e){this.fileId=e},downloadAttachment:function(e){this.fileId=e},previewAttachmentCallback:function(){var e=this,t=this;return new o.a((function(n){$.ajax({url:$("[name=preview-attachment-url]").val(),type:"post",data:{id:e.fileId},beforeSend:function(e){e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done((function(e){e.data.sdkBaseUri=app.cloudSdkBaseUri,e.data.disableDataUpload=app.cloudDisableLogReport,e.data.disableSentry=app.cloudDisableLogReport,n(e),t.fileId=0}))}))},downloadAttachmentCallback:function(){var e=this,t=this;return new o.a((function(n){$.ajax({url:$("[name=download-attachment-url]").val(),type:"post",data:{id:e.fileId},beforeSend:function(e){e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done((function(e){n(e),t.fileId=0}))}))},deleteAttachmentCallback:function(){var e=this,t=this;return new o.a((function(n){$.ajax({url:$("[name=delete-attachment-url]").val(),type:"post",data:{id:e.fileId},beforeSend:function(e){e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))}}).done((function(e){n(e),t.fileId=0}))}))},readyHandler:function(){var e=$("[name=img-url]");e.length>0&&""!==e.val()?this.$refs.inspection.captureModal({token:$("[name=token]").val(),faceUrl:e.val(),errorMessage:this.errorMessage}):this.$refs.inspection.captureModal({token:$("[name=token]").val(),errorMessage:this.errorMessage})},saveCheatRecord:function(e){var t=new FormData;t.append("status","cheating"),t.append("level","1"),t.append("duration","15000"),t.append("behavior",e.behavior),t.append("picture",p()(e.image)),$.ajax({url:$("[name=inspection-save-url]").val(),type:"POST",contentType:!1,processData:!1,data:t,beforeSend:function(e){e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))},success:function(e){console.log(e)}})},captureHandler:function(e){var t=new FormData;t.append("picture",p()(e.capture)),$.ajax({url:$("[name=upload-url]").val(),type:"POST",contentType:!1,processData:!1,data:t,beforeSend:function(e){e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))},success:function(e){}})}}},f=n(30),h=Object(f.a)(w,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"ibs-vue",attrs:{id:"app"}},[n("div",{attrs:{id:"cd-modal"}}),e._v(" "),n("item-engine",{attrs:{assessment:e.assessment,answerRecord:e.answerRecord,answerScene:e.answerScene,showCKEditorData:e.showCKEditorData,showSaveProgressBtn:e.showSaveProgressBtn,assessmentResponse:e.assessmentResponse,showAttachment:e.showAttachment,cdnHost:e.cdnHost,uploadSDKInitData:e.uploadSDKInitData,deleteAttachmentCallback:e.deleteAttachmentCallback,previewAttachmentCallback:e.previewAttachmentCallback,downloadAttachmentCallback:e.downloadAttachmentCallback,getCurrentTime:e.getCurrentTime},on:{getAnswerData:e.getAnswerData,saveAnswerData:e.saveAnswerData,timeSaveAnswerData:e.timeSaveAnswerData,reachTimeSubmitAnswerData:e.reachTimeSubmitAnswerData,deleteAttachment:e.deleteAttachment,previewAttachment:e.previewAttachment,downloadAttachment:e.downloadAttachment}},[e.inspectionOpen&&e.isNotMobile?n("template",{slot:"inspection"},[n("inspection-control",{ref:"inspection",attrs:{mode:"watching"},on:{ready:e.readyHandler,cheatHappened:e.saveCheatRecord,faceCaptured:e.captureHandler}})],1):e._e()],2)],1)}),[],!1,null,null,null).exports;if(jQuery.support.cors=!0,Object(d.f)()&&$("body, html").css({height:"100%",overflow:"auto"}),Vue.config.productionTip=!1,"en"==app.lang){var g=local.default;itemBank.default.install(Vue,{locale:g})}new Vue({render:function(e){return e(h)}}).$mount("#app")},382:function(e,t){e.exports=function(e){"use strict";if(!window||window.window!==window)throw new Error("This module is only available in browser");var t=window.Blob||window.MozBlob||window.WebKitBlob;if(!t)throw new Error("Blob was not supported");var n=e.match(/^data:((.*?)(;charset=.*?)?)(;base64)?,/);if(!n)throw new Error("invalid dataURI");for(var a=n[2]?n[1]:"text/plain"+(n[3]||";charset=utf-8"),o=!!n[4],r=e.slice(n[0].length),s=o?atob(r):decodeURIComponent(r),i=[],c=0;c<s.length;c++)i.push(s.charCodeAt(c));return new t([new Uint8Array(i)],{type:a})}},429:function(e,t,n){"use strict";n.d(t,"a",(function(){return r}));var a=n(10),o=n.n(a);var r=function(){var e={ok:!0,message:""},t=function(){var e=navigator.userAgent.toLowerCase(),t=function(t){return t.test(e)},n=function(t){return e.match(t).toString().replace(/[^0-9|_.]/g,"").replace(/_/g,".")},a="unknow";t(/windows|win32|win64|wow32|wow64/g)?a="windows":t(/macintosh|macintel/g)?a="macos":t(/x11/g)?a="linux":t(/android|adr/g)?a="android":t(/ios|iphone|ipad|ipod|iwatch/g)&&(a="ios");var o="unknow";"windows"===a?t(/windows nt 5.0|windows 2000/g)?o="2000":t(/windows nt 5.1|windows xp/g)?o="xp":t(/windows nt 5.2|windows 2003/g)?o="2003":t(/windows nt 6.0|windows vista/g)?o="vista":t(/windows nt 6.1|windows 7/g)?o="7":t(/windows nt 6.2|windows 8/g)?o="8":t(/windows nt 6.3|windows 8.1/g)?o="8.1":t(/windows nt 10.0|windows 10/g)&&(o="10"):"macos"===a?o=n(/os x [\d._]+/g):"android"===a?o=n(/android [\d._]+/g):"ios"===a&&(o=n(/os [\d._]+/g));var r="unknow";"windows"===a||"macos"===a||"linux"===a?r="desktop":("android"===a||"ios"===a||t(/mobile/g))&&(r="mobile");var s="unknow",i="unknow";t(/applewebkit/g)?(s="webkit",t(/edge/g)?i="edge":t(/opr/g)?i="opera":t(/chrome/g)?i="chrome":t(/safari/g)&&(i="safari")):t(/gecko/g)&&t(/firefox/g)?(s="gecko",i="firefox"):t(/presto/g)?(s="presto",i="opera"):t(/trident|compatible|msie/g)&&(s="trident",i="iexplore");var c="unknow";"webkit"===s?c=n(/applewebkit\/[\d._]+/g):"gecko"===s?c=n(/gecko\/[\d._]+/g):"presto"===s?c=n(/presto\/[\d._]+/g):"trident"===s&&(c=n(/trident\/[\d._]+/g));var d="unknow";"chrome"===i?d=n(/chrome\/[\d._]+/g):"safari"===i?d=n(/version\/[\d._]+/g):"firefox"===i?d=n(/firefox\/[\d._]+/g):"opera"===i?d=n(/opr\/[\d._]+/g):"iexplore"===i?d=n(/(msie [\d._]+)|(rv:[\d._]+)/g):"edge"===i&&(d=n(/edge\/[\d._]+/g));var l="none",u="unknow";t(/micromessenger/g)?(l="wechat",u=n(/micromessenger\/[\d._]+/g)):t(/qqbrowser/g)?(l="qq",u=n(/qqbrowser\/[\d._]+/g)):t(/ucbrowser/g)?(l="uc",u=n(/ucbrowser\/[\d._]+/g)):t(/qihu 360se/g)?l="360":t(/2345explorer/g)?(l="2345",u=n(/2345explorer\/[\d._]+/g)):t(/metasr/g)?l="sougou":t(/lbbrowser/g)?l="liebao":t(/maxthon/g)&&(l="maxthon",u=n(/maxthon\/[\d._]+/g));var p={engine:s,engineVs:c,platform:r,supporter:i,supporterVs:d,system:a,systemVs:o};return"none"!==l&&(p.shell=l,p.shellVs=u),p}(),n=e.message='请下载安装使用最新版 <a href="https://edtech.edusoho.net/software/chrome-win64.exe" target="_blank">谷歌</a>、<a href="https://browser.360.cn/se/" target="_blank">360</a> 或 <a href="https://ie.sogou.com/" target="_blank">搜狗</a> 浏览器。';return"desktop"!==t.platform?(e.ok=!1,e.message="请在电脑端浏览器中打开！"):"iexplore"===t.supporter||t.shell&&"qq"===t.shell?(e.ok=!1,e.message="不支持当前浏览器，"+n):"chrome"===t.supporter&&o()(t.supporterVs)<69&&(e.ok=!1,e.message="当前浏览器版本过低，"+n),e}}});