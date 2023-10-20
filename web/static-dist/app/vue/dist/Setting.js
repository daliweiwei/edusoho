(window.webpackJsonp=window.webpackJsonp||[]).push([[433],{106:function(t,e,a){t.exports=a(564)},1505:function(t,e,a){"use strict";a.r(e);var r=a(202),i=a.n(r),s=a(358),n=a.n(s),o=a(1513),l={name:"index",components:{AsideLayout:a(1518).a},data:function(){return{rules:{group_number_limit:[{required:!0,message:"请输入分组学员人数上限",trigger:"blur"},{validator:this.validatorGroupNumber,trigger:"blur"}],assistant_group_limit:[{required:!0,message:"请输入助教服务组数上限",trigger:"blur"},{validator:this.validatorAssistantGroup,trigger:"blur"}],assistant_service_limit:[{required:!0,message:"请输入助教服务学员人数上限",trigger:"blur"},{validator:this.validatorAssistantService,trigger:"blur"}],review_time_limit:[{required:!0,message:"请输入超时未批阅时间设定",trigger:"blur"},{validator:this.validatorReviewTime,trigger:"blur"}]},form:{group_number_limit:"",assistant_service_limit:"",review_time_limit:"",assistant_group_limit:""},ajaxLoading:!1}},computed:{},mounted:function(){this.getParams()},methods:{getParams:function(){var t=this;return n()(i.a.mark((function e(){return i.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,o.x.search();case 2:t.form=e.sent;case 3:case"end":return e.stop()}}),e)})))()},validatorGroupNumber:function(t,e,a){e>1e4&&a("超出最大人数"),!1===/^\+?[0-9][0-9]*$/.test(e)&&a("请输入整数"),a()},validatorAssistantService:function(t,e,a){e>1e4&&a("超出最大人数"),!1===/^\+?[0-9][0-9]*$/.test(e)&&a("请输入整数"),a()},validatorAssistantGroup:function(t,e,a){e>1e4&&a("超出最大组数"),!1===/^\+?[0-9][0-9]*$/.test(e)&&a("请输入整数"),a()},validatorReviewTime:function(t,e,a){e>200&&a("时间范围在0-200小时"),!1===/^\+?[0-9][0-9]*$/.test(e)&&a("请输入整数"),a()},handleSubmit:function(){var t=this;this.$refs.form.validate().then(n()(i.a.mark((function e(){return i.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.ajaxLoading=!0,e.prev=1,e.next=4,o.x.add(t.form);case 4:t.$message.success("保存成功");case 5:return e.prev=5,t.ajaxLoading=!1,e.finish(5);case 8:case"end":return e.stop()}}),e,null,[[1,,5,8]])}))))}}},u=(a(1833),a(33)),m=Object(u.a)(l,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("aside-layout",{attrs:{breadcrumbs:[{name:"参数设置"}]}},[a("a-form-model",{ref:"form",staticStyle:{"max-width":"500px"},attrs:{model:t.form,rules:t.rules,"label-col":{span:10},"wrapper-col":{span:14}}},[a("a-form-model-item",{ref:"group_number_limit",attrs:{label:"分组容纳学员上限",prop:"group_number_limit",extra:"新建分组大班课时将默认填入该参数"}},[a("a-input",{model:{value:t.form.group_number_limit,callback:function(e){t.$set(t.form,"group_number_limit",e)},expression:"form.group_number_limit"}},[a("span",{attrs:{slot:"suffix"},slot:"suffix"},[t._v("人")])])],1),t._v(" "),a("a-form-model-item",{ref:"assistant_group_limit",attrs:{label:"助教服务组数上限",prop:"assistant_group_limit",extra:"新建分组大班课时将默认填入该参数"}},[a("a-input",{model:{value:t.form.assistant_group_limit,callback:function(e){t.$set(t.form,"assistant_group_limit",e)},expression:"form.assistant_group_limit"}},[a("span",{attrs:{slot:"suffix"},slot:"suffix"},[t._v("组")])])],1),t._v(" "),a("a-form-model-item",{ref:"assistant_service_limit",attrs:{label:"助教服务学员人数上限",prop:"assistant_service_limit",extra:"新建大班课时将默认填入该参数"}},[a("a-input",{model:{value:t.form.assistant_service_limit,callback:function(e){t.$set(t.form,"assistant_service_limit",e)},expression:"form.assistant_service_limit"}},[a("span",{attrs:{slot:"suffix"},slot:"suffix"},[t._v("人")])])],1),t._v(" "),a("a-form-model-item",{ref:"review_time_limit",attrs:{label:"超时未批阅时间设定",prop:"review_time_limit",extra:"针对所有班课的参数设置"}},[a("a-input",{model:{value:t.form.review_time_limit,callback:function(e){t.$set(t.form,"review_time_limit",e)},expression:"form.review_time_limit"}},[a("span",{attrs:{slot:"suffix"},slot:"suffix"},[t._v("小时")])]),t._v(" "),a("span",{staticClass:"default-time"},[t._v("默认0为不限时间")])],1)],1),t._v(" "),a("div",{staticClass:"setup-btn"},[a("a-space",{attrs:{size:"large"}},[a("a-button",{attrs:{type:"primary",loading:t.ajaxLoading},on:{click:t.handleSubmit}},[t._v("\n        保存\n      ")])],1)],1)],1)}),[],!1,null,"0d07e086",null);e.default=m.exports},1515:function(t,e,a){},1516:function(t,e,a){a(1517),t.exports=a(69).Reflect.deleteProperty},1517:function(t,e,a){var r=a(108),i=a(404).f,s=a(190);r(r.S,"Reflect",{deleteProperty:function(t,e){var a=i(s(t),e);return!(a&&!a.configurable)&&delete t[e]}})},1518:function(t,e,a){"use strict";var r={name:"AsideLayout",props:{breadcrumbs:{type:Array,required:!0},headerTitle:{type:String,default:""},headerTip:{type:String,default:""},titleTip:{type:String,default:""}}},i=(a(1519),a(33)),s=Object(i.a)(r,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"aside-layout"},[a("div",{staticClass:"aside-layout-header"},[a("a-breadcrumb",{staticClass:"pull-left aside-layout-header__breadcrumb",attrs:{separator:"/"}},t._l(t.breadcrumbs,(function(e,r){return a("a-breadcrumb-item",{key:r},[e.href?[a("a",{attrs:{href:e.href,target:"_blank"}},[t._v(t._s(e.name))])]:e.pathName?[a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return t.$router.push({name:e.pathName})}}},[t._v(t._s(e.name))])]:[t._v("\n          "+t._s(e.name)+"\n        ")]],2)})),1),t._v(" "),t.headerTip?a("a-popover",{attrs:{placement:"bottomLeft"}},[a("template",{slot:"content"},[a("div",{staticClass:"aside-header-tip",domProps:{innerHTML:t._s(t.headerTip)}})]),t._v(" "),a("span",{staticClass:"aside-header-title-icon"},[a("a-icon",{attrs:{theme:"filled",type:"question-circle"}}),a("span",{staticClass:"icon-circle"},[t._v(t._s(t.headerTitle))])],1)],2):t._e(),t._v(" "),t.titleTip?a("span",{staticClass:"aside-header-title-icon"},[t._v(t._s(t.titleTip))]):t._e()],1),t._v(" "),a("div",{staticClass:"aside-layout-main"},[t._t("default")],2)])}),[],!1,null,null,null);e.a=s.exports},1519:function(t,e,a){"use strict";var r=a(1515);a.n(r).a},1520:function(t,e,a){t.exports=a(1516)},1672:function(t,e,a){},1833:function(t,e,a){"use strict";var r=a(1672);a.n(r).a},3:function(t,e){t.exports=function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}},4:function(t,e,a){var r=a(106);function i(t,e){for(var a=0;a<e.length;a++){var i=e[a];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),r(t,i.key,i)}}t.exports=function(t,e,a){return e&&i(t.prototype,e),a&&i(t,a),t}}}]);