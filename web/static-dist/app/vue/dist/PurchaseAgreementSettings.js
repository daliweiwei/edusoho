(window.webpackJsonp=window.webpackJsonp||[]).push([[429],{100:function(e,t,a){e.exports=a(538)},1463:function(e,t,a){"use strict";a.r(t);var n=a(193),r=a.n(n),s=a(341),o=a.n(s),i=a(388),c=a.n(i),l=(a(1788),a(30)),u=Object(l.a)({},(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"aside-container"},[a("div",{staticClass:"aside-header"},[a("div",{staticClass:"aside-header__title"},[e._t("title")],2)]),e._v(" "),a("div",{staticClass:"aside-content"},[e._t("default")],2)])}),[],!1,null,"11e9749a",null).exports,d=a(1789),m=a.n(d),p=a(1494),f=app.basePath+"/static-dist/libs/es-ckeditor/";window.CKEDITOR_BASEPATH=f;var v={data:function(){return{editor:null,imageUploadUrl:"/editor/upload?token=",flashUploadUrl:"/editor/upload?token="}},methods:{getEditorUploadToken:function(){var e=this;return o()(r.a.mark((function t(){var a,n;return r.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,p.G.get("course");case 2:a=t.sent,n=a.token,e.imageUploadUrl+=n,e.flashUploadUrl+=n;case 6:case"end":return t.stop()}}),t)})))()},initCkeditor:function(){var e=arguments,t=this;return o()(r.a.mark((function a(){var n;return r.a.wrap((function(a){for(;;)switch(a.prev=a.next){case 0:return n=e.length>0&&void 0!==e[0]?e[0]:"",a.next=3,t.getEditorUploadToken();case 3:m()("".concat(f,"/ckeditor.js"),(function(e){if(e)throw e;t.editor=CKEDITOR.replace("summary",{allowedContent:!0,toolbar:"Detail",height:400,fileSingleSizeLimit:app.fileSingleSizeLimit,filebrowserImageUploadUrl:t.imageUploadUrl,filebrowserFlashUploadUrl:t.flashUploadUrl}),t.editor.on("instanceReady",(function(){t.setData(n)}))}));case 4:case"end":return a.stop()}}),a)})))()},getData:function(){return this.editor.getData(this.content)},setData:function(e){e&&this.editor.setData(e)}}},h=(a(1790),{name:"PurchaseAgreementSettings",components:{AdminContainer:u,Ckeditor:Object(l.a)(v,(function(){var e=this.$createElement;return(this._self._c||e)("div",{attrs:{id:"summary"}})}),[],!1,null,"614bd7cc",null).exports},data:function(){return{loading:!0,form:{enabled:1,title:"",content:"",type:"tick"},radioStyle:{display:"block",marginTop:"4px",height:"30px",lineHeight:"30px"},rules:{content:[{required:!0,message:Translator.trans("admin.system.purchase_agreement.content_empty")}]}}},mounted:function(){this.fetchPurchaseAgreement()},methods:{fetchPurchaseAgreement:function(){var e=this;return o()(r.a.mark((function t(){var a,n,s,o,i;return r.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,p.C.get();case 2:a=t.sent,n=a.enabled,s=a.title,o=a.content,i=a.type,c.a.assign(e.form,{enabled:n,title:s||Translator.trans("admin.system.purchase_agreement"),content:o,type:i}),e.$refs.ckeditor.initCkeditor(o),e.loading=!1;case 10:case"end":return t.stop()}}),t)})))()},onSubmit:function(){var e=this;this.form.content=this.$refs.ckeditor.getData(),this.$refs.ruleForm.validate(function(){var t=o()(r.a.mark((function t(a){return r.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:if(a){t.next=2;break}return t.abrupt("return",!1);case 2:return t.prev=2,t.next=5,p.C.update({data:e.form});case 5:e.$message.success(Translator.trans("admin.system.purchase_agreement.saved_successfully")),t.next=11;break;case 8:t.prev=8,t.t0=t.catch(2),e.$message.error(Translator.trans("admin.system.purchase_agreement.save_failed"));case 11:case"end":return t.stop()}}),t,null,[[2,8]])})));return function(e){return t.apply(this,arguments)}}())}}}),_=Object(l.a)(h,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("admin-container",{scopedSlots:e._u([{key:"title",fn:function(){return[e._v(e._s(e._f("trans")("admin.system.purchase_agreement_setting")))]},proxy:!0}])},[e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:!e.loading,expression:"!loading"}],staticClass:"single-content-sec"},[a("a-form-model",{ref:"ruleForm",attrs:{model:e.form,rules:e.rules,"label-col":{span:4},"wrapper-col":{span:14}}},[a("a-form-model-item",{attrs:{label:e._f("trans")("admin.system.purchase_agreement")}},[a("a-radio-group",{model:{value:e.form.enabled,callback:function(t){e.$set(e.form,"enabled",t)},expression:"form.enabled"}},[a("a-radio",{attrs:{value:1}},[e._v(e._s(e._f("trans")("decorate.turn_on")))]),e._v(" "),a("a-radio",{attrs:{value:0}},[e._v(e._s(e._f("trans")("decorate.closure")))])],1)],1),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.form.enabled,expression:"form.enabled"}]},[a("a-form-model-item",{attrs:{label:e._f("trans")("admin.system.purchase_agreement.title")}},[a("a-input",{model:{value:e.form.title,callback:function(t){e.$set(e.form,"title",t)},expression:"form.title"}})],1),e._v(" "),a("a-form-model-item",{attrs:{label:e._f("trans")("admin.system.purchase_agreement.content"),prop:"content"}},[a("ckeditor",{ref:"ckeditor"})],1),e._v(" "),a("a-form-model-item",{attrs:{label:e._f("trans")("admin.system.purchase_agreement.style_settings")}},[a("a-radio-group",{model:{value:e.form.type,callback:function(t){e.$set(e.form,"type",t)},expression:"form.type"}},[a("div",{staticStyle:{"margin-top":"2px"}},[a("a-radio",{attrs:{value:"tick"}},[e._v(e._s(e._f("trans")("admin.system.purchase_agreement.check_to_confirm")))]),e._v(" "),a("a-popover",{attrs:{placement:"topLeft"}},[a("template",{slot:"content"},[a("img",{attrs:{src:"/static-dist/app/img/vue/agreement-1.png",alt:""}})]),e._v(" "),a("a-button",{staticStyle:{padding:"0"},attrs:{type:"link"}},[e._v(e._s(e._f("trans")("admin.system.purchase_agreement.see_details")))])],2)],1),e._v(" "),a("div",{staticStyle:{"margin-top":"2px"}},[a("a-radio",{attrs:{value:"eject"}},[e._v(e._s(e._f("trans")("admin.system.purchase_agreement.popup_confirmation")))]),e._v(" "),a("a-popover",{attrs:{placement:"topLeft"}},[a("template",{slot:"content"},[a("img",{attrs:{src:"/static-dist/app/img/vue/agreement-2.png",alt:""}})]),e._v(" "),a("a-button",{staticStyle:{padding:"0"},attrs:{type:"link"}},[e._v(e._s(e._f("trans")("admin.system.purchase_agreement.see_details")))])],2)],1)])],1)],1),e._v(" "),a("a-form-model-item",{attrs:{"wrapper-col":{span:14,offset:4}}},[a("a-button",{attrs:{type:"primary"},on:{click:e.onSubmit}},[e._v("\n          "+e._s(e._f("trans")("admin.system.purchase_agreement.submit"))+"\n        ")])],1)],1)],1)])}),[],!1,null,null,null);t.default=_.exports},1497:function(e,t,a){a(1498),e.exports=a(67).Reflect.deleteProperty},1498:function(e,t,a){var n=a(103),r=a(389).f,s=a(181);n(n.S,"Reflect",{deleteProperty:function(e,t){var a=r(s(e),t);return!(a&&!a.configurable)&&delete e[t]}})},1501:function(e,t,a){e.exports=a(1497)},1618:function(e,t,a){},1619:function(e,t,a){},1788:function(e,t,a){"use strict";var n=a(1618);a.n(n).a},1789:function(e,t){function a(e,t){e.onload=function(){this.onerror=this.onload=null,t(null,e)},e.onerror=function(){this.onerror=this.onload=null,t(new Error("Failed to load "+this.src),e)}}function n(e,t){e.onreadystatechange=function(){"complete"!=this.readyState&&"loaded"!=this.readyState||(this.onreadystatechange=null,t(null,e))}}e.exports=function(e,t,r){var s=document.head||document.getElementsByTagName("head")[0],o=document.createElement("script");"function"==typeof t&&(r=t,t={}),t=t||{},r=r||function(){},o.type=t.type||"text/javascript",o.charset=t.charset||"utf8",o.async=!("async"in t)||!!t.async,o.src=e,t.attrs&&function(e,t){for(var a in t)e.setAttribute(a,t[a])}(o,t.attrs),t.text&&(o.text=""+t.text),("onload"in o?a:n)(o,r),o.onload||a(o,r),s.appendChild(o)}},1790:function(e,t,a){"use strict";var n=a(1619);a.n(n).a},2:function(e,t){e.exports=function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}},3:function(e,t,a){var n=a(100);function r(e,t){for(var a=0;a<t.length;a++){var r=t[a];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),n(e,r.key,r)}}e.exports=function(e,t,a){return t&&r(e.prototype,t),a&&r(e,a),e}}}]);