!function(t){function e(e){for(var r,s,i=e[0],l=e[1],u=e[2],d=0,p=[];d<i.length;d++)s=i[d],Object.prototype.hasOwnProperty.call(o,s)&&o[s]&&p.push(o[s][0]),o[s]=0;for(r in l)Object.prototype.hasOwnProperty.call(l,r)&&(t[r]=l[r]);for(c&&c(e);p.length;)p.shift()();return a.push.apply(a,u||[]),n()}function n(){for(var t,e=0;e<a.length;e++){for(var n=a[e],r=!0,i=1;i<n.length;i++){var l=n[i];0!==o[l]&&(r=!1)}r&&(a.splice(e--,1),t=s(s.s=n[0]))}return t}var r={},o={341:0},a=[];function s(e){if(r[e])return r[e].exports;var n=r[e]={i:e,l:!1,exports:{}};return t[e].call(n.exports,n,n.exports,s),n.l=!0,n.exports}s.m=t,s.c=r,s.d=function(t,e,n){s.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:n})},s.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},s.t=function(t,e){if(1&e&&(t=s(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(s.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)s.d(n,r,function(e){return t[e]}.bind(null,r));return n},s.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return s.d(e,"a",e),e},s.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},s.p="/static-dist/";var i=window.webpackJsonp=window.webpackJsonp||[],l=i.push.bind(i);i.push=e,i=i.slice();for(var u=0;u<i.length;u++)e(i[u]);var c=l;a.push([1062,0]),n()}({1062:function(t,e,n){"use strict";n.r(e);var r=n(10),o=n.n(r),a=n(8),s=n(198),i=$("#review-form"),l=i.validate({rules:{rating:{required:!0,raty_star:!0},content:{required:!0}},messages:{rating:{required:Translator.trans("course.marking_hint")}}});i.length>0&&(i.find(".rating-btn").raty({path:i.find(".rating-btn").data("imgPath"),hints:[Translator.trans("course.marking_one_star"),Translator.trans("course.marking_two_star"),Translator.trans("course.marking_three_star"),Translator.trans("course.marking_four_star"),Translator.trans("course.marking_five_star")],score:function(){return $(this).attr("data-rating")},click:function(t,e){i.find("[name=rating]").val(t)}}),i.find(".js-btn-save").on("click",(function(){var t=$(this);l.form()&&(t.button("loading"),$.ajax({type:"POST",beforeSend:function(t){t.setRequestHeader("Accept","application/vnd.edusoho.v2+json"),t.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))},url:"/api/review",data:i.serialize()+"&targetType="+$(".js-btn-save").data("targetType")+"&targetId="+$(".js-btn-save").data("targetId"),success:function(){i.find(".js-review-remind").fadeIn("fast",(function(){window.location.reload()}))},error:function(){t.button("reset")}}))})),$(".js-hide-review-form").on("click",(function(){$(this).hide(),$(".js-show-review-form").show(),i.hide()})),$(".js-show-review-form").on("click",(function(){$(this).hide(),$(".js-hide-review-form").show(),i.show()})));var u=$(".js-reviews");if($(".js-reviews").hover((function(){$(this).find(".full-content").text().length>100&&0==$(this).find(".short-content").is(":hidden")?$(this).find(".show-full-btn").show():$(this).find(".show-full-btn").hide()})),u.on("click",".show-full-btn",(function(){var t=$(this).parents(".media");t.find(".short-content").slideUp("fast",(function(){t.find(".full-content").slideDown("fast")})),$(this).hide(),t.find(".show-short-btn").show()})),u.on("click",".show-short-btn",(function(){var t=$(this).parents(".media");t.find(".full-content").slideUp("fast",(function(){t.find(".short-content").slideDown("fast")})),$(this).hide(),t.find(".show-full-btn").show()})),$(".js-reviews").length>0){var c=new s.a({element:".js-reviews"});c.undelegateEvents(".js-toggle-subpost-form","click"),c.undelegateEvents(".js-reply","click"),c.undelegateEvents(".js-post-delete","click"),$(".js-toggle-subpost-form").click((function(t){if(t.stopPropagation(),$(this).closest(".thread-subpost-container").find(".thread-subpost-content .thread-subpost-list .thread-subpost").length>=5)Object(a.a)("danger",Translator.trans("course.manage.post_limit_hint"));else{var e=$(this).parents(".thread-subpost-container").find(".thread-subpost-form");e.toggleClass("hide"),c.initSubpostForm(e),d(e)}})),$(".js-reply").on("click",(function(t){t.stopPropagation();var e=$(t.currentTarget),n=e.parents(".thread-subpost-list").length>0,r=e.parents(".thread-post").find(".thread-subpost-container"),o=r.find(".thread-subpost-form");if(n){o.removeClass("hide");var a=Translator.trans("thread.post.reply")+" @ "+e.parents(".thread-post").data("authorName")+"： ";o.find("textarea").val(a).trigger("focus")}else r.toggleClass("hide");e.html()==Translator.trans("thread.post.reply")?e.html(Translator.trans("thread.post.put_away")):e.html(Translator.trans("thread.post.reply")),c.initSubpostForm(o),d(o)})),$(".js-reviews").on("click",".js-delete-post",(function(t){var e=this.ele,n=$(t.currentTarget);if(confirm(Translator.trans("thread.post.delete_hint"))){var r=n.parents(".thread-subpost-list").length>0;$.ajax({type:"DELETE",beforeSend:function(t){t.setRequestHeader("Accept","application/vnd.edusoho.v2+json"),t.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))},url:"/api/review/"+n.data("reviewId"),success:function(t){if(r){var s=n.parents(".thread-post").find(".subposts-num");s.text(o()(s.text())-1)}else e.find(".thread-post-num").text(o()(e.find(".thread-post-num").text())-1);$(n.data("for")).remove(),Object(a.a)("success",Translator.trans("site.delete_success_hint"))},error:function(){}})}}))}function d(t){$(".js-btn-save-post").off("click").on("click",(function(e){if(e.stopPropagation(),t.validate().form()){var n=$(this);n.button("loading"),$.ajax({type:"POST",beforeSend:function(t){t.setRequestHeader("Accept","application/vnd.edusoho.v2+json"),t.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))},url:"/api/review/"+n.data("targetId")+"/post",data:t.serialize(),success:function(e){n.button("reset"),t.parents(".thread-subpost-container").find(".thread-subpost-list").append(e.template),t.find("textarea").val("");var r=t.parents(".thread-post").find(".subposts-num");r.text(o()(r.text())+1),r.parent().removeClass("hide")},error:function(){n.button("reset")}})}}))}},198:function(t,e,n){"use strict";var r=n(10),o=n.n(r),a=n(2),s=n.n(a),i=n(3),l=n.n(i),u=n(8),c=function(){function t(e){s()(this,t),this.ele=$(e.element),this.init()}return l()(t,[{key:"init",value:function(){this.initEvent(),this.initPostForm()}},{key:"initEvent",value:function(){var t=this,e=this.ele;console.log(e),e.on("click",".js-post-more",(function(e){return t.onClickPostMore(e)})),e.on("click",".js-reply",(function(e){return t.onClickReply(e)})),e.on("click",".js-post-delete",(function(e){return t.onPostDelete(e)})),e.on("click",".js-post-up",(function(e){return t.onPostUp(e)})),e.on("click","[data-role=confirm-btn]",(function(e){return t.onConfirmBtn(e)})),e.on("click",".js-toggle-subpost-form",(function(e){return t.onClickToggleSubpostForm(e)})),e.on("click",".js-event-cancel",(function(e){return t.onClickEventCancelBtn(e)})),e.on("click",".thread-subpost-container .pagination a",(function(e){return t.onClickSubpost(e)}))}},{key:"onClickPostMore",value:function(t){t.stopPropagation();var e=$(t.currentTarget);e.parents(".thread-subpost-moretext").addClass("hide"),e.parents(".thread-post").find(".thread-subpost").removeClass("hide"),e.parents(".thread-post").find(".pagination").removeClass("hide")}},{key:"onClickReply",value:function(t){console.log("ok"),t.stopPropagation();var e=$(t.currentTarget),n=e.parents(".thread-subpost-list").length>0,r=e.parents(".thread-post").find(".thread-subpost-container"),o=r.find(".thread-subpost-form");if(n){o.removeClass("hide");var a=Translator.trans("thread.post.reply")+" @ "+e.parents(".thread-post").data("authorName")+"： ";o.find("textarea").val(a).trigger("focus")}else r.toggleClass("hide");e.html()==Translator.trans("thread.post.reply")?e.html(Translator.trans("thread.post.put_away")):e.html(Translator.trans("thread.post.reply")),this.initSubpostForm(o)}},{key:"onPostDelete",value:function(t){t.stopPropagation();var e=this.ele,n=$(t.currentTarget);if(confirm(Translator.trans("thread.post.delete_hint"))){var r=n.parents(".thread-subpost-list").length>0;$.post(n.data("url"),(function(){if(r){var t=n.parents(".thread-post").find(".subposts-num");t.text(o()(t.text())-1)}else e.find(".thread-post-num").text(o()(e.find(".thread-post-num").text())-1);$(n.data("for")).remove()}))}}},{key:"onPostUp",value:function(t){t.stopPropagation();var e=$(t.currentTarget);$.post(e.data("url"),(function(t){"ok"==t.status?e.find(".post-up-num").text(o()(e.find(".post-up-num").text())+1):"votedError"==t.status?Object(u.a)("danger",Translator.trans("thread.post.like_hint")):Object(u.a)("danger",Translator.trans("thread.post.like_error_hint"))}),"json")}},{key:"onConfirmBtn",value:function(t){t.stopPropagation();var e=$(t.currentTarget);confirm(e.data("confirmMessage"))&&$.post(e.data("url"),(function(){e.data("afterUrl")?window.location.href=e.data("afterUrl"):window.location.reload()}))}},{key:"onClickToggleSubpostForm",value:function(t){t.stopPropagation();var e=$(t.currentTarget).parents(".thread-subpost-container").find(".thread-subpost-form");e.toggleClass("hide"),this.initSubpostForm(e)}},{key:"onClickEventCancelBtn",value:function(t){$.post($(t.currentTarget).data("url"),(function(){window.location.reload()}))}},{key:"onClickSubpost",value:function(t){t.preventDefault();var e=$(t.currentTarget);$.post(e.attr("href"),(function(t){var n=e.parents(".thread-post").attr("id");$("body,html").animate({scrollTop:$("#"+n).offset().top},300),e.closest(".thread-subpost-container .thread-subpost-content").html(t)}))}},{key:"initPostForm",value:function(){var t=$(".thread-pripost-list"),e=$("#thread-post-form");if(0!=e.length){var n=null,r=e.find("textarea[name=content]");r.data("imageUploadUrl")&&(n=CKEDITOR.replace(r.attr("id"),{toolbar:"Thread",fileSingleSizeLimit:app.fileSingleSizeLimit,filebrowserImageUploadUrl:r.data("imageUploadUrl")})).on("change",(function(){r.val(n.getData())}));var a=e.find("[type=submit]");e.validate({ajax:!0,currentDom:a,rules:{content:"required"},submitSuccess:function(s){a.button("reset"),r.data("imageUploadUrl")?(t.append(s),n.setData("")):(t.prepend(s),r.val(""));var i=t.find("li:last-child").offset();$("body").scrollTop(i.top),e.find(".thread-post-num").text(o()(e.find(".thread-post-num").text())+1),t.find("li.empty").remove(),t.closest(".top-reply").removeClass("hidden"),$(".js-attachment-list").empty(),$(".js-attachment-ids").val(""),$(".js-upload-file").show()},submitError:function(t){a.button("reset")}})}}},{key:"initSubpostForm",value:function(t){var e=t.find("[type=submit]");t.validate({ajax:!0,currentDom:e,rules:{content:"required"},submitSuccess:function(n){if(n.error)Object(u.a)("danger",n.error);else{e.button("reset"),t.parents(".thread-subpost-container").find(".thread-subpost-list").append(n),t.find("textarea").val("");var r=t.parents(".thread-post").find(".subposts-num");r.text(o()(r.text())+1),r.parent().removeClass("hide")}},submitError:function(t){e.button("reset"),(t=$.parseJSON(t.responseText)).error||Object(u.a)("danger",Translator.trans("thread.post.reply_error_hint"))}})}},{key:"undelegateEvents",value:function(t,e){this.ele.off(t,e)}}]),t}();e.a=c},22:function(t,e){t.exports=jQuery}});