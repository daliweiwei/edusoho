!function(e){function t(t){for(var i,s,o=t[0],u=t[1],c=t[2],d=0,f=[];d<o.length;d++)s=o[d],Object.prototype.hasOwnProperty.call(a,s)&&a[s]&&f.push(a[s][0]),a[s]=0;for(i in u)Object.prototype.hasOwnProperty.call(u,i)&&(e[i]=u[i]);for(l&&l(t);f.length;)f.shift()();return r.push.apply(r,c||[]),n()}function n(){for(var e,t=0;t<r.length;t++){for(var n=r[t],i=!0,o=1;o<n.length;o++){var u=n[o];0!==a[u]&&(i=!1)}i&&(r.splice(t--,1),e=s(s.s=n[0]))}return e}var i={},a={384:0},r=[];function s(t){if(i[t])return i[t].exports;var n=i[t]={i:t,l:!1,exports:{}};return e[t].call(n.exports,n,n.exports,s),n.l=!0,n.exports}s.m=e,s.c=i,s.d=function(e,t,n){s.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},s.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},s.t=function(e,t){if(1&t&&(e=s(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(s.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var i in e)s.d(n,i,function(t){return e[t]}.bind(null,i));return n},s.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return s.d(t,"a",t),t},s.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},s.p="/static-dist/";var o=window.webpackJsonp=window.webpackJsonp||[],u=o.push.bind(o);o.push=t,o=o.slice();for(var c=0;c<o.length;c++)t(o[c]);var l=u;r.push([1397,0]),n()}({1397:function(e,t,n){"use strict";n.r(t);var i=n(5),a=n.n(i),r=n(2),s=n.n(r),o=n(3),u=n.n(o),c=n(12),l=n.n(c),d=n(13),f=n.n(d),h=n(9),p=n.n(h),v=n(328),g=n(77);function m(e){var t=function(){if("undefined"==typeof Reflect||!a.a)return!1;if(a.a.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(a()(Date,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,i=p()(e);if(t){var r=p()(this).constructor;n=a()(i,arguments,r)}else n=i.apply(this,arguments);return f()(this,n)}}var y=function(e){l()(n,e);var t=m(n);function n(e){var i;return s()(this,n),(i=t.call(this,e)).$timePauseDialog=i.$container.find("#time-pause-dialog"),i.$timer=e.find(".js-testpaper-timer"),i._init(),i}return u()(n,[{key:"_init",value:function(){var e=this;Object(g.a)(),Object(g.b)(),Object(g.d)(),Object(g.e)(),Object(g.c)(),this._initTimer(),this.$container.on("click",".js-btn-pause",(function(t){return e._clickBtnPause(t)})),this.$container.on("click",".js-btn-resume",(function(t){return e._clickBtnReume(t)}))}},{key:"_initTimer",value:function(){var e=this;this.$timer&&this.$timer.timer({countdown:!0,duration:this.$timer.data("time"),format:"%H:%M:%S",callback:function(){e.$container.find("#time-finish-dialog").modal("show"),clearInterval(e.$usedTimer),0==$('input[name="preview"]').length&&e._submitTest(e.$container.find('[data-role="paper-submit"]').data("url"))},repeat:!0,start:function(){e.usedTime=0}})}},{key:"_clickBtnPause",value:function(e){$(e.currentTarget).toggleClass("active").hasClass("active")?(this.$timer.timer("pause"),clearInterval(this.$usedTimer),this.$timePauseDialog.modal("show")):(this.$timer.timer("resume"),this._initUsedTimer(),this.$timePauseDialog.modal("hide"))}},{key:"_clickBtnReume",value:function(){this.$timer.timer("resume"),this._initUsedTimer(),this.$container.find(".js-btn-pause").removeClass("active"),this.$timePauseDialog.modal("hide")}}]),n}(v.a),b=n(133);$("#facein-init-modal").length<1&&new y($(".js-task-testpaper-body")),new b.a($(".js-task-testpaper-body"))},204:function(e,t,n){"use strict";var i=n(2),a=n.n(i);t.a=function e(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:$("html");a()(this,e),t.attr("unselectable","on").css("user-select","none").on("selectstart",!1)}},22:function(e,t){e.exports=jQuery},328:function(e,t,n){"use strict";var i=n(26),a=n.n(i),r=n(10),s=n.n(r),o=n(2),u=n.n(o),c=n(3),l=n.n(c),d=function(){function e(){u()(this,e)}return l()(e,[{key:"getAnswer",value:function(e){var t=[];return $("input[name="+e+"]:checked").each((function(){t.push($(this).val())})),t}}]),e}(),f=function(){function e(){u()(this,e)}return l()(e,[{key:"getAnswer",value:function(e){var t=[];return $("input[name="+e+"]:checked").each((function(){t.push($(this).val())})),t}}]),e}(),h=function(){function e(){u()(this,e)}return l()(e,[{key:"getAnswer",value:function(e){var t=[],n=$("[name="+e+"]").val();return t.push(n),t}},{key:"getAttachment",value:function(e){var t=[],n=$("[name="+e+"]").parent().find('[data-role="fileId"]').val();return""!=n&&t.push(n),t}}]),e}(),p=function(){function e(){u()(this,e)}return l()(e,[{key:"getAnswer",value:function(e){var t=[];return $("input[name="+e+"]").each((function(){t.push($(this).val())})),t}}]),e}(),v=function(){function e(){u()(this,e)}return l()(e,[{key:"getAnswer",value:function(e){var t=[];return $("input[name="+e+"]:checked").each((function(){t.push($(this).val())})),t}}]),e}(),g=function(){function e(t){u()(this,e),this.type=t}return l()(e,null,[{key:"getTypeBuilder",value:function(e){var t=null;switch(e){case"choice":t=new d;break;case"determine":t=new f;break;case"essay":t=new h;break;case"fill":t=new p;break;case"single_choice":case"uncertain_choice":t=new v;break;default:t=null}return t}}]),e}(),m=n(204),y=n(177),b=n(8),k=function(){function e(t){u()(this,e),this.$container=t,this.answers={},this.usedTime=t.find(".js-used-time").length>0?s()(t.find(".js-used-time").val()):0,this.$form=t.find("form"),this._initEvent(),this._initUsedTimer(),this._isCopy(),this._alwaysSave()}return l()(e,[{key:"_initEvent",value:function(){var e=this;this.$container.on("focusin","textarea",(function(t){return e._showEssayInputEditor(t)})),this.$container.on("click",'[data-role="test-suspend"],[data-role="paper-submit"]',(function(t){return e._btnSubmit(t)})),this.$container.on("click",".js-testpaper-question-list li",(function(t){return e._choiceList(t)})),this.$container.on("click","*[data-anchor]",(function(t){return e._quick2Question(t)})),this.$container.find(".js-testpaper-question-label").on("click","input",(function(t){return e._choiceLable(t)})),this.$container.on("click",".js-marking",(function(t){return e._markingToggle(t)})),this.$container.on("click",".js-favorite",(function(t){return e._favoriteToggle(t)})),this.$container.on("click",".js-analysis",(function(t){return e._analysisToggle(t)})),this.$container.on("blur",'[data-type="fill"]',(function(t){return e.fillChange(t)}))}},{key:"_isCopy",value:function(){this.$container.find(".js-testpaper-body").data("copy")&&new m.a}},{key:"fillChange",value:function(e){var t=$(e.currentTarget);this._renderBtnIndex(t.attr("name"),!!t.val())}},{key:"_markingToggle",value:function(e){var t=$(e.currentTarget).addClass("hidden");t.siblings(".js-marking.hidden").removeClass("hidden");var n=t.closest(".js-testpaper-question").attr("id");$('[data-anchor="#'.concat(n,'"]')).find(".js-marking-card").toggleClass("hidden")}},{key:"_favoriteToggle",value:function(e){var t=$(e.currentTarget),n=t.data("targetType"),i=t.data("targetId");$.post(t.data("url"),{targetType:n,targetId:i},(function(e){t.addClass("hidden").siblings(".js-favorite.hidden").data("url",e.url),t.addClass("hidden").siblings(".js-favorite.hidden").removeClass("hidden")})).error((function(e){Object(b.a)("error",e.error.message)}))}},{key:"_analysisToggle",value:function(e){var t=$(e.currentTarget);t.addClass("hidden"),t.siblings(".js-analysis.hidden").removeClass("hidden"),t.closest(".js-testpaper-question").find(".js-testpaper-question-analysis").slideToggle()}},{key:"_initUsedTimer",value:function(){var e=this;this.$usedTimer=window.setInterval((function(){e.usedTime+=1}),1e3)}},{key:"_choiceLable",value:function(e){var t=$(e.currentTarget),n=t.closest(".js-testpaper-question-label");this.changeInput(n,t)}},{key:"_choiceList",value:function(e){var t=$(e.currentTarget),n=t.index(),i=t.closest(".js-testpaper-question").find(".js-testpaper-question-label"),a=i.find("label").eq(n).find("input");a.prop("checked",!a.prop("checked")).change(),this.changeInput(i,a)}},{key:"changeInput",value:function(e,t){var n=0;e.find("label").each((function(e,t){$(t).find("input").prop("checked")?($(t).addClass("active"),n++):$(t).removeClass("active")}));var i=t.attr("name");this._renderBtnIndex(i,n>0)}},{key:"_renderBtnIndex",value:function(e){var t=!(arguments.length>1&&void 0!==arguments[1])||arguments[1],n=arguments.length>2&&void 0!==arguments[2]&&arguments[2],i=$('[data-anchor="#question'.concat(e,'"]'));t?i.addClass("done"):i.removeClass("done"),n?i.addClass("doing").siblings(".doing").removeClass("doing"):i.removeClass("doing")}},{key:"_showEssayInputEditor",value:function(e){var t=this,n=$(e.currentTarget);if(n.hasClass("essay-input-short")){e.preventDefault(),e.stopPropagation(),$(this).blur();var i=n.siblings(".essay-input-long"),a=i.siblings(".essay-input-btn");n.hide(),i.show(),a.show();var r=CKEDITOR.replace(i.attr("id"),{toolbar:"Minimal",fileSingleSizeLimit:app.fileSingleSizeLimit,filebrowserImageUploadUrl:i.data("imageUploadUrl")});r.on("blur",(function(){r.updateElement(),setTimeout((function(){i.val(r.getData()),i.change(),i.val()?t._renderBtnIndex(i.attr("name"),!0):t._renderBtnIndex(i.attr("name"),!1)}),1)})),r.on("instanceReady",(function(){this.focus(),a.one("click",(function(){n.val($(r.getData()).text()),r.destroy(),i.hide(),a.hide(),n.show()}))})),r.on("key",(function(){r.updateElement(),setTimeout((function(){i.val(r.getData()),i.change()}),1)})),r.on("insertHtml",(function(){r.updateElement(),setTimeout((function(){i.val(r.getData()),i.change()}),1)}))}}},{key:"_quick2Question",value:function(e){var t=$(e.currentTarget);window.location.hash=t.data("anchor")}},{key:"_suspendSubmit",value:function(e){var t=this._getAnswers(),n=this._getAttachments();$.post(e,{data:t,usedTime:this.usedTime,attachments:n}).done((function(){})).error((function(e){Object(b.a)("error",e.error.message)}))}},{key:"_btnSubmit",value:function(e){var t=$(e.currentTarget);t.button("loading"),clearInterval(this.saveTimer),this._submitTest(t.data("url"),t.data("goto"))}},{key:"_submitTest",value:function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",n=this._getAnswers(),i=new y.a,a=this._getAttachments();$.post(e,{data:n,usedTime:this.usedTime,attachments:a}).done((function(e){e.result&&i.emit("finish",{data:""}),""!=t||""!=e.goto?window.location.href=t:""!=e.goto?window.location.href=e.goto:""!=e.message&&Object(b.a)("error",e.message)})).error((function(e){Object(b.a)("error",e.error.message)}))}},{key:"_getAnswers",value:function(){var e={};return $("*[data-type]").each((function(){var t=$(this).attr("name"),n=$(this).data("type"),i=g.getTypeBuilder(n).getAnswer(t);e[t]=i})),a()(e)}},{key:"_getAttachments",value:function(){var e={};return $('[data-type="essay"]').each((function(){var t=$(this).attr("name"),n=g.getTypeBuilder("essay").getAttachment(t);e[t]=n})),e}},{key:"_alwaysSave",value:function(){if($('input[name="testSuspend"]').length>0){var e=this,t=$('input[name="testSuspend"]').data("url");this.saveTimer=setInterval((function(){e._suspendSubmit(t);var n=(new Date).getHours()+":"+(new Date).getMinutes()+":"+(new Date).getSeconds();Object(b.a)("success",n+Translator.trans("testpaper.widget.save_success_hint"))}),18e4)}}}]),e}();t.a=k},77:function(e,t,n){"use strict";n.d(t,"a",(function(){return a})),n.d(t,"d",(function(){return r})),n.d(t,"e",(function(){return s})),n.d(t,"c",(function(){return o})),n.d(t,"b",(function(){return u}));n(351);var i=n(20),a=function(){var e=$(".js-panel-card");e.perfectScrollbar(),e.perfectScrollbar("update")},r=function(){if(console.log("ok"),!Object(i.f)()){var e=$(".js-testpaper-card");if(!(e.length<=0)){var t=e.offset().top;$(window).scroll((function(){$(window).scrollTop()>=t?e.addClass("affix"):e.removeClass("affix")}))}}},s=function(){$(".js-btn-index").click((function(e){var t=$(e.currentTarget);$(".js-testpaper-heading").length<=0&&t.addClass("doing").siblings(".doing").removeClass("doing")}))},o=function(){$("#showWrong").change((function(e){var t=$(e.currentTarget);$(".js-answer-notwrong").each((function(e,n){var i=$($(n).data("anchor")),a=i.closest(".js-testpaper-question-block");t.prop("checked")?(i.hide(),a.find(".js-testpaper-question:visible").length<=0&&a.hide()):(i.show(),a.show())})),a()}))},u=function(){var e=$(".js-testpaper-watermark");e.length>0&&$.get(e.data("watermark-url"),(function(t){e.each((function(){$(this).WaterMark({yPosition:"center",style:{"font-size":10},opacity:.6,contents:t})}))}))}}});