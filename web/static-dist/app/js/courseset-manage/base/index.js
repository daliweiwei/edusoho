!function(t){function e(e){for(var r,o,s=e[0],u=e[1],l=e[2],f=0,m=[];f<s.length;f++)o=s[f],Object.prototype.hasOwnProperty.call(i,o)&&i[o]&&m.push(i[o][0]),i[o]=0;for(r in u)Object.prototype.hasOwnProperty.call(u,r)&&(t[r]=u[r]);for(c&&c(e);m.length;)m.shift()();return a.push.apply(a,l||[]),n()}function n(){for(var t,e=0;e<a.length;e++){for(var n=a[e],r=!0,s=1;s<n.length;s++){var u=n[s];0!==i[u]&&(r=!1)}r&&(a.splice(e--,1),t=o(o.s=n[0]))}return t}var r={},i={212:0},a=[];function o(e){if(r[e])return r[e].exports;var n=r[e]={i:e,l:!1,exports:{}};return t[e].call(n.exports,n,n.exports,o),n.l=!0,n.exports}o.m=t,o.c=r,o.d=function(t,e,n){o.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:n})},o.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},o.t=function(t,e){if(1&e&&(t=o(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(o.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)o.d(n,r,function(e){return t[e]}.bind(null,r));return n},o.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return o.d(e,"a",e),e},o.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},o.p="/static-dist/";var s=window.webpackJsonp=window.webpackJsonp||[],u=s.push.bind(s);s.push=e,s=s.slice();for(var l=0;l<s.length;l++)e(s[l]);var c=u;a.push([1344,0]),n()}({1344:function(t,e,n){"use strict";n.r(e);var r=n(2),i=n.n(r),a=n(3),o=n.n(a),s=function(){function t(e){i()(this,t),this.$element=$(e),this.formId=$(e).data("form"),this.btnId=$(e).data("button"),this.$form=$("#".concat(this.formId)),this.$btn=$("#".concat(this.btnId)),this.uploadUrl=this.$element.data("imageUploadUrl"),this.init()}return o()(t,[{key:"init",value:function(){this.submitForm(),this.initCkeditor()}},{key:"initCkeditor",value:function(){var t=this,e=this;e.editor=CKEDITOR.replace("summary",{allowedContent:!0,toolbar:"Detail",height:400,resize_enabled:!0,fileSingleSizeLimit:app.fileSingleSizeLimit,filebrowserImageUploadUrl:this.uploadUrl}),e.editor.on("blur",(function(){t.$element.val(e.editor.getData()),e.validator.form()}))}},{key:"submitForm",value:function(){var t=this;this.validator=this.$form.validate({rules:{summary:{ckeditor_maxlength:1e4}}}),this.$btn.click((function(){t.$element.val(t.editor.getData()),t.validator.form()&&t.$form.submit()}))}}]),t}();new(function(){function t(e){i()(this,t),this.init(),this.detail=new s(e)}return o()(t,[{key:"init",value:function(){this.initValidator(),this.initTags()}},{key:"initValidator",value:function(){var t=this,e=$("#title").closest("form"),n=$("#courseset-summary-field").val();e.validate({currentDom:"#courseset-base-submit",ajax:!0,rules:{title:{byte_maxlength:200,required:{depends:function(){return $(this).val($.trim($(this).val())),!0}},course_title:!0},subtitle:{maxlength:50,required:{depends:function(){return $(this).val($.trim($(this).val())),!1}},course_title:!0}},submitHandler:function(e){var r=$(e),i=this.settings,a=$(i.currentDom),o=r.data("value"),s=$("#courseset-summary-field").val();a.length||(a=$(e).find('[type="submit"]')),1==o&&""!=s&&s!=n?cd.confirm({title:Translator.trans("course_set.manage.operation_hint"),content:Translator.trans("course_set.manage.courseset_summary_operation_hint"),okText:Translator.trans("site.confirm"),cancelText:Translator.trans("site.cancel")}).on("ok",(function(){t.savePost(e,i)})):t.savePost(e,i)},submitSuccess:function(t){cd.message({type:"success",message:Translator.trans("site.save_success_hint")}),window.location.reload()}})}},{key:"savePost",value:function(t,e){var n=$(t),r=$(e.currentDom);r.button("loading"),$.post(n.attr("action"),n.serializeArray(),(function(t){r.button("reset"),e.submitSuccess(t)})).error((function(t){r.button("reset"),e.submitError(t)}))}},{key:"initTags",value:function(){var t=$("#tags");t.select2({ajax:{url:t.data("url"),dataType:"json",quietMillis:500,data:function(t,e){return{q:t,page_limit:10}},results:function(t){return console.log(t),{results:t.map((function(t){return{id:t.name,name:t.name}}))}}},initSelection:function(t,e){var n=[];$(t.val().split(",")).each((function(){n.push({id:this,name:this})})),e(n)},formatSelection:function(t){return t.name},formatResult:function(t){return t.name},formatNoMatches:function(){return Translator.trans("validate.tag_required_not_found_hint")},formatSearching:function(){return Translator.trans("site.searching_hint")},multiple:!0,maximumSelectionSize:20,placeholder:Translator.trans("course_set.manage.tag_required_hint"),width:"off",createSearchChoice:function(){return null}})}}]),t}())("#courseset-summary-field")}});