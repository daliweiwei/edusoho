!function(t){function e(e){for(var i,o,s=e[0],l=e[1],c=e[2],d=0,f=[];d<s.length;d++)o=s[d],Object.prototype.hasOwnProperty.call(r,o)&&r[o]&&f.push(r[o][0]),r[o]=0;for(i in l)Object.prototype.hasOwnProperty.call(l,i)&&(t[i]=l[i]);for(u&&u(e);f.length;)f.shift()();return a.push.apply(a,c||[]),n()}function n(){for(var t,e=0;e<a.length;e++){for(var n=a[e],i=!0,s=1;s<n.length;s++){var l=n[s];0!==r[l]&&(i=!1)}i&&(a.splice(e--,1),t=o(o.s=n[0]))}return t}var i={},r={336:0},a=[];function o(e){if(i[e])return i[e].exports;var n=i[e]={i:e,l:!1,exports:{}};return t[e].call(n.exports,n,n.exports,o),n.l=!0,n.exports}o.m=t,o.c=i,o.d=function(t,e,n){o.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:n})},o.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},o.t=function(t,e){if(1&e&&(t=o(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(o.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var i in t)o.d(n,i,function(e){return t[e]}.bind(null,i));return n},o.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return o.d(e,"a",e),e},o.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},o.p="/static-dist/";var s=window.webpackJsonp=window.webpackJsonp||[],l=s.push.bind(s);s.push=e,s=s.slice();for(var c=0;c<s.length;c++)e(s[c]);var u=l;a.push([1240,0]),n()}({1240:function(t,e,n){"use strict";n.r(e);var i=n(12),r=n.n(i),a=n(4),o=n.n(a),s=n(5),l=n.n(s),c=n(89),u=n(23);new(function(){function t(e){o()(this,t),this.$form=e,this.$description=this.$form.find('[name="description"]'),this.validator=null,this.difficultySlider=null,this._initEvent(),this._initValidate(),this._initSortList(),this._initSelect(),this.scoreSlider=null}return l()(t,[{key:"_initEvent",value:function(){var t=this;this.$form.on("click",'[data-role="submit"]',(function(e){return t._submit(e)})),this.$form.on("click",'[name="mode"]',(function(e){return t.changeMode(e)})),this.$form.on("click",'[name="range"]',(function(e){return t.changeRange(e)})),this.$form.on("blur",'[data-role="count"]',(function(e){return t.changeCount(e)}))}},{key:"_initSelect",value:function(){$('[name="ranges[categoryId]"]').select2({treeview:!0,dropdownAutoWidth:!0,treeviewInitState:"collapsed",placeholderOption:"first"})}},{key:"initScoreSlider",value:function(t,e){var n=document.getElementById("score-slider"),i={start:t,connect:[!0,!1],tooltips:[!0],step:1,range:{min:0,max:e}};this.scoreSlider?this.scoreSlider.updateOptions(i):(this.scoreSlider=noUiSlider.create(n,i),n.noUiSlider.on("update",(function(t,n){$(".noUi-tooltip").text("".concat((t[n]/e*100).toFixed(0),"%")),$(".js-passScore").text(r()(t[n]))}))),$(".noUi-handle").attr("data-placement","top").attr("data-original-title",Translator.trans("activity.testpaper_manage.pass_score_hint",{passScore:t})).attr("data-container","body"),$(".noUi-handle").tooltip({html:!0}),$(".noUi-tooltip").text("".concat((t/e*100).toFixed(0),"%"))}},{key:"changeMode",value:function(t){"difficulty"==$(t.currentTarget).val()?(this.$form.find("#difficulty-form-group").removeClass("hidden"),this.initDifficultySlider()):this.$form.find("#difficulty-form-group").addClass("hidden")}},{key:"changeRange",value:function(t){"course"==$(t.currentTarget).val()?this.$form.find("#testpaper-range-selects").addClass("hidden"):this.$form.find("#testpaper-range-selects").removeClass("hidden")}},{key:"initDifficultySlider",value:function(){if(!this.difficultySlider){var t=document.getElementById("difficulty-percentage-slider");this.difficultySlider=noUiSlider.create(t,{start:[30,70],margin:30,range:{min:0,max:100},step:1,connect:[!0,!0,!0],serialization:{resolution:1}}),t.noUiSlider.on("update",(function(t){var e=r()(t[0]),n=t[1]-t[0],i=100-t[1];$(".js-simple-percentage-text").html(Translator.trans("activity.testpaper_manage.simple_percentage",{simplePercentage:e})),$(".js-normal-percentage-text").html(Translator.trans("activity.testpaper_manage.normal_percentage",{normalPercentage:n})),$(".js-difficulty-percentage-text").html(Translator.trans("activity.testpaper_manage.difficulty_percentage",{difficultyPercentage:i})),$('input[name="percentages[simple]"]').val(e),$('input[name="percentages[normal]"]').val(n),$('input[name="percentages[difficulty]"]').val(i)}))}}},{key:"_initEditor",value:function(t){var e=this,n=CKEDITOR.replace(this.$description.attr("id"),{toolbar:"Simple",fileSingleSizeLimit:app.fileSingleSizeLimit,filebrowserImageUploadUrl:this.$description.data("imageUploadUrl"),height:100});n.on("change",(function(){e.$description.val(Object(u.c)(n.getData()))})),n.on("blur",(function(){e.$description.val(Object(u.c)(n.getData())),t.form()}))}},{key:"changeCount",value:function(){var t=0;this.$form.find('[data-role="count"]').each((function(e,n){t+=r()($(n).val())})),this.$form.find('[name="questioncount"]').val(t>0?t:null)}},{key:"_initValidate",value:function(){this.validator=this.$form.validate({rules:{name:{required:!0,maxlength:50,trim:!0},description:{maxlength:500,trim:!0},limitedTime:{min:0,max:1e4,digits:!0},mode:{required:!0},range:{required:!0},questioncount:{required:!0}},messages:{questioncount:Translator.trans("activity.testpaper_manage.question_required_error_hint"),name:{required:Translator.trans("activity.testpaper_manage.input_title_hint"),maxlength:Translator.trans("site.maxlength_hint",{length:50})},description:{required:Translator.trans("activity.testpaper_manage.input_description_hint"),maxlength:Translator.trans("site.maxlength_hint",{length:500})},mode:Translator.trans("activity.testpaper_manage.generate_mode_hint"),range:Translator.trans("activity.testpaper_manage.question_scope")}}),this.$form.find(".testpaper-question-option-item").each((function(){var t=$(this);t.find('[data-role="count"]').rules("add",{min:0,max:function(){return r()(t.find('[role="questionNum"]').text())},digits:!0}),t.find('[data-role="score"]').rules("add",{min:0,max:1e3,es_score:!0}),t.find('[data-role="missScore"]').length>0&&t.find('[data-role="missScore"]').rules("add",{min:0,max:function(){return r()(t.find('[data-role="score"]').val())},es_score:!0})})),this._initEditor(this.validator)}},{key:"_initSortList",value:function(){Object(c.a)({element:"#testpaper-question-options",itemSelector:".testpaper-question-option-item",handle:".question-type-sort-handler",ajax:!1})}},{key:"_submit",value:function(t){var e=this,n=$(t.currentTarget),i=this.validator.form(),r=0;this.$form.find('[data-role="count"]').each((function(){var t=$(this);r+=Number(t.val())})),i&&(r>2e3?cd.message({type:"danger",message:Translator.trans("activity.testpaper_manage.questions_length_hint")}):$.post(n.data("checkUrl"),this.$form.serialize(),(function(t){t?($(".js-build-check").html(""),n.button("loading").addClass("disabled"),e.$form.submit()):$(".js-build-check").html(Translator.trans("activity.testpaper_manage.question_num_error"))})))}}]),t}())($("#testpaper-form")),$('[name="ranges[categoryId]"]').change((function(){!function(t){var e=$('[name="ranges[categoryId]"]').data("bankId"),n=$('[name="ranges[categoryId]"]').val();$.post(t,{bankId:e,categoryId:n},(function(t){$('[role="questionNum"]').text(0),$.each(t,(function(t,e){$("[type='"+t+"']").text(e.itemNum)}))}))}($(this).data("checkNumUrl"))}))}});