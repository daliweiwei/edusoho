!function(t){function e(e){for(var n,i,s=e[0],l=e[1],c=e[2],d=0,u=[];d<s.length;d++)i=s[d],Object.prototype.hasOwnProperty.call(o,i)&&o[i]&&u.push(o[i][0]),o[i]=0;for(n in l)Object.prototype.hasOwnProperty.call(l,n)&&(t[n]=l[n]);for(p&&p(e);u.length;)u.shift()();return r.push.apply(r,c||[]),a()}function a(){for(var t,e=0;e<r.length;e++){for(var a=r[e],n=!0,s=1;s<a.length;s++){var l=a[s];0!==o[l]&&(n=!1)}n&&(r.splice(e--,1),t=i(i.s=a[0]))}return t}var n={},o={236:0},r=[];function i(e){if(n[e])return n[e].exports;var a=n[e]={i:e,l:!1,exports:{}};return t[e].call(a.exports,a,a.exports,i),a.l=!0,a.exports}i.m=t,i.c=n,i.d=function(t,e,a){i.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:a})},i.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},i.t=function(t,e){if(1&e&&(t=i(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var a=Object.create(null);if(i.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var n in t)i.d(a,n,function(e){return t[e]}.bind(null,n));return a},i.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return i.d(e,"a",e),e},i.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},i.p="/static-dist/";var s=window.webpackJsonp=window.webpackJsonp||[],l=s.push.bind(s);s.push=e,s=s.slice();for(var c=0;c<s.length;c++)e(s[c]);var p=l;r.push([1591,0]),a()}({1591:function(t,e,a){"use strict";a.r(e);var n,o,r=a(10),i=a(48),s=a.n(i),l=a(162),c=new(a(128).a)({drag:{limitType:"groupThread",bar:"#drag-btn",target:".js-jigsaw"}}),p=a(58);!function(){var t,e,a="#post-thread-btn",n=$("#post-thread-form");new l.a(n),$("#post_content").length&&(t={toolbar:"Thread",replace:"post_content"},(e=CKEDITOR.replace(t.replace,{toolbar:t.toolbar,fileSingleSizeLimit:app.fileSingleSizeLimit,filebrowserImageUploadUrl:$("#"+t.replace).data("imageUploadUrl"),allowedContent:!0,height:300})).on("change",(function(){$("#"+t.replace).val(e.getData())})),e.on("blur",(function(){$("#"+t.replace).val(e.getData())})));var o=null;1==$("input[name=enable_anti_brush_captcha]").val()&&(o={captchaClass:c,isShowCaptcha:$(c.params.maskClass).length?1:0});var r=n.validate({currentDom:a,ajax:!0,captcha:o,rules:{content:{required:!0,minlength:2,maxlength:3e3,trim:!0}},messages:{content:{maxlength:Translator.trans("group.thread.reply.max_length.notice")}},submitSuccess:function(t){console.log(t),"/login"!=t?window.location.reload():window.location.href=t},submitError:function(t){r.settings.captcha.isShowCaptcha=1,c.hideDrag()}});n.on("submitHandler",(function(){c.setType("groupThread")})),c.on("success",(function(t){"groupThread"==t.type&&(r.settings.captcha.isShowCaptcha=0,n.find("input[name=_dragCaptchaToken]").val(t.token),n.submit())})),$(a).click((function(){r.form()}))}(),n=$(".thread-post-reply-form"),o=0,1==$("input[name=enable_anti_brush_captcha]").val()&&(o=1),n.each((function(){var t=$(this),e=t.find("textarea").attr("name"),a=t.validate({ignore:"",rules:s()({},"".concat(e),{required:!0,minlength:2,maxlength:3e3,trim:!0}),messages:s()({},"".concat(e),{maxlength:Translator.trans("group.thread.reply.max_length.notice")}),submitHandler:function(t){if($(t).triggerHandler("submitHandler"),1==o)return c.showDrag(),!1;var e=$(t).find("input[name=_dragCaptchaToken]").val(),a=$(t).find(".reply-btn"),n=a.attr("postId"),i="";i=$("#fromUserId").length>0?$("#fromUserId").val():$("#fromUserIdNosub").length>0?$("#fromUserIdNosub").val():"",a.button("submiting").addClass("disabled"),console.log($(t).attr("action")),console.log("content="+$(t).find("textarea").val()+"&postId="+n+"&fromUserId="+i+"_dragCaptchaToken="+e),$.ajax({url:$(t).attr("action"),data:"content="+$(t).find("textarea").val()+"&postId="+n+"&fromUserId="+i+"&_dragCaptchaToken="+e,cache:!1,async:!1,type:"POST",dataType:"text",success:function(t){"/login"!=t?window.location.reload():window.location.href=t},error:function(t){o=1,c.hideDrag(),(t=$.parseJSON(t.responseText)).error?Object(r.a)("danger",t.error.message):Object(r.a)("danger",Translator.trans("group.post.reply_fail_hint")),a.button("reset").removeClass("disabled")}})}});t.on("submitHandler",(function(){c.setType("groupThreadReply")})),c.on("success",(function(e){"groupThreadReply"==e.type&&(o=0,t.find("input[name=_dragCaptchaToken]").val(e.token),t.submit())})),t.find("button").click((function(t){a.form()}))}));$("#thread-list").on("click",".uncollect-btn, .collect-btn",(function(t){var e=$(this);e.hasClass("uncollect-btn")?p.a.favorite.unfavorite({data:{targetType:e.data("targetType"),targetId:e.data("targetId")}}).then((function(t){e.hide(),e.hasClass("collect-btn")?e.parent().find(".uncollect-btn").show():e.parent().find(".collect-btn").show()})):e.hasClass("collect-btn")&&p.a.favorite.favorite({data:{targetType:e.data("targetType"),targetId:e.data("targetId")}}).then((function(t){e.hide(),e.hasClass("collect-btn")?e.parent().find(".uncollect-btn").show():e.parent().find(".collect-btn").show()}))})),$(".attach").tooltip(),$(".group-post-list").length>0&&($(".group-post-list").on("click",".li-reply",(function(){var t=$(this).attr("postId"),e=$(this).data("fromUserId");$("#fromUserIdDiv").html('<input type="hidden" id="fromUserId" value="'+e+'">'),$("#li-"+t).show(),$("#reply-content-"+t).focus(),$("#reply-content-"+t).val(Translator.trans("group.post.reply_hint")+" "+$(this).attr("postName")+":")})),$(".group-post-list").on("click",".reply",(function(){var t=$(this).attr("postId");if(""!=$(this).data("fromUserIdNosub")){var e=$(this).data("fromUserIdNosub");$("#fromUserIdNoSubDiv").html('<input type="hidden" id="fromUserIdNosub" value="'+e+'">'),$("#fromUserIdDiv").html("")}$(this).hide(),$("#unreply-"+t).show(),$(".reply-"+t).css("display","")})),$(".group-post-list").on("click",".unreply",(function(){var t=$(this).attr("postId");$(this).hide(),$("#reply-"+t).show(),$(".reply-"+t).css("display","none")})),$(".group-post-list").on("click",".replyToo",(function(){var t=$(this).attr("postId");"hidden"==$(this).attr("data-status")?($(this).attr("data-status",""),$("#li-"+t).show(),$("#reply-content-"+t).focus(),$("#reply-content-"+t).val("")):($("#li-"+t).hide(),$(this).attr("data-status","hidden"))})),$(".group-post-list").on("click",".lookOver",(function(){var t=$(this).attr("postId");$(".li-reply-"+t).css("display",""),$(".lookOver-"+t).hide(),$(".paginator-"+t).css("display","")})),$(".group-post-list").on("click",".postReply-page",(function(){var t=$(this).attr("postId");$.post($(this).data("url"),"",(function(e){$("body,html").animate({scrollTop:$("#post-"+t).offset().top},300),$(".reply-post-list-"+t).replaceWith(e)}))}))),$("#hasAttach").length>0&&$(".ke-icon-accessory").addClass("ke-icon-accessory-red"),$("#post-action").length>0&&($("#post-action").on("click","#closeThread",(function(){var t=$(this);if(!confirm(t.attr("title")+"?"))return!1;$.post(t.data("url"),(function(t){window.location.href=t}))})),$("#post-action").on("click","#elite,#stick,#cancelReward",(function(){var t=$(this);$.post(t.data("url"),(function(t){window.location.href=t}))}))),$(".actions").length>0&&$(".group-post-list").on("click",".post-delete-btn,.post-adopt-btn",(function(){var t=$(this);if(!confirm(t.attr("title")+"?"))return!1;$.post(t.data("url"),(function(){window.location.reload()}))}))},26:function(t,e){t.exports=jQuery}});