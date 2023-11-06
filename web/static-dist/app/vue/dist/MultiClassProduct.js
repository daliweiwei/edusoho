(window.webpackJsonp=window.webpackJsonp||[]).push([[427],{116:function(t,e,a){t.exports=a(600)},123:function(t,e,a){t.exports=a(726)},1638:function(t,e,a){"use strict";a.r(e);var r=a(116),n=a.n(r),i=a(123),s=a.n(i),o=a(82),c=a.n(o),l=a(80),u=a.n(l),d=a(83),p=a.n(d),f=a(41),v=a.n(f),m=a(38),g=a.n(m),h=a(220),_=a.n(h),x=a(374),b=a.n(x),C=a(1675),y=(a(252),a(1670)),k={name:"ProductCard",props:{product:{type:Object,required:!0}},data:function(){return{}},methods:{editMultiClassProduct:function(){this.$emit("edit",this.product)},deleteMultiClassProduct:function(){var t=this;if("default"!==this.product.type)if(this.product.multiClassNum)this.$message.warning("该产品含有班课，不能删除");else{var e=this.product.title;this.$confirm({content:"确认要删除-".concat(e),okType:"danger",okText:"确认",cancelText:"取消",icon:"close-circle",maskClosable:!0,onOk:function(){t.$emit("delete",t.product)}})}},lookoverMultiClass:function(){this.$emit("lookover",this.product)}}},P=(a(1968),a(34)),w=Object(P.a)(k,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"product-card"},[a("div",{staticStyle:{position:"relative","padding-right":"60px"}},[a("div",{staticClass:"product-card__title text-overflow"},[t._v(t._s(t.product.title))]),t._v(" "),a("div",{staticClass:"product-card__remark text-overflow"},[t._v(t._s(t.product.remark))]),t._v(" "),a("div",{staticClass:"product-card__operation"},[a("i",{staticClass:"es-icon es-icon-bianjimian mr24 color-primary",on:{click:t.editMultiClassProduct}}),t._v(" "),"default"!==t.product.type?a("i",{staticClass:"es-icon es-icon-shanchu1 color-danger",on:{click:t.deleteMultiClassProduct}}):t._e()])]),t._v(" "),a("a-row",{staticClass:"mt24"},[a("a-col",{attrs:{span:8}},[a("div",{staticClass:"gray-darker text-24 text-overflow",attrs:{title:t.product.income}},[t._v(t._s(t.product.income))]),t._v(" "),a("div",{staticClass:"color-gray text-14 mt4"},[t._v("预估收入")])]),t._v(" "),a("a-col",{attrs:{span:6}},[a("div",{staticClass:"gray-darker text-24"},[t._v(t._s(t.product.studentNum))]),t._v(" "),a("div",{staticClass:"color-gray text-14 mt4"},[t._v("学习人数")])]),t._v(" "),a("a-col",{attrs:{span:5}},[a("div",{staticClass:"gray-darker text-24"},[t._v(t._s(t.product.finishedCourseRate))]),t._v(" "),a("div",{staticClass:"color-gray text-14 mt4"},[t._v("完课率")])]),t._v(" "),a("a-col",{attrs:{span:5}},[a("div",{staticClass:"gray-darker text-24"},[t._v(t._s(t.product.multiClassNum))]),t._v(" "),a("div",{staticClass:"color-gray text-14 mt4"},[t._v("班课")])])],1),t._v(" "),a("div",{staticClass:"product-card__lookover-multiclass",on:{click:t.lookoverMultiClass}},[t._v("\n    查看班课列表\n  ")])],1)}),[],!1,null,null,null).exports,L=[{title:"班课名称",dataIndex:"title",scopedSlots:{customRender:"class_title"}},{title:"课程名称",dataIndex:"course",scopedSlots:{customRender:"course"}},{title:"价格",dataIndex:"price"},{title:"已完成/课时",dataIndex:"taskNum",scopedSlots:{customRender:"taskNum"}},{title:"授课老师",dataIndex:"teacher"},{title:"助教老师",dataIndex:"assistant",scopedSlots:{customRender:"assistant"}},{title:"已报班人数",dataIndex:"studentNum",scopedSlots:{customRender:"studentNum"}},{title:"创建时间",dataIndex:"createdTime",scopedSlots:{customRender:"createdTime"}},{title:"操作",dataIndex:"action",scopedSlots:{customRender:"action"}}],M={props:{product:{type:Object,required:!0},visible:{type:Boolean,required:!0,default:!1}},data:function(){return{multiClassList:[],paging:[],ajaxLoading:!1,columns:L}},watch:{product:{immediate:!0,handler:"searchMultiClassList"}},created:function(){},methods:{handleCancel:function(){this.$emit("close",!1)},searchMultiClassList:function(){var t=arguments,e=this;return b()(_.a.mark((function a(){var r,n,i,s,o;return _.a.wrap((function(a){for(;;)switch(a.prev=a.next){case 0:if(r=t.length>0&&void 0!==t[0]?t[0]:{},e.product){a.next=3;break}return a.abrupt("return");case 3:return(n={}).limit=r.pageSize||10,n.offset=r.offset||0,n.productId=e.product.id,e.ajaxLoading=!0,a.prev=8,a.next=11,y.t.search(n);case 11:i=a.sent,s=i.data,(o=i.paging).page=o.offset/o.limit+1,o.pageSize=Number(o.limit),e.multiClassList=s,e.paging=o;case 18:return a.prev=18,e.ajaxLoading=!1,a.finish(18);case 21:case"end":return a.stop()}}),a,null,[[8,,18,21]])})))()},goToMultiClassManage:function(t){window.location.href="/admin/v2/multi_class/index#/manage/".concat(t)},goToEditMultiClass:function(t){window.location.href="/admin/v2/multi_class/index#/create?id=".concat(t)},goToMultiClassDataPreview:function(t){window.location.href="/admin/v2/multi_class/index#/manage/".concat(t,"/data_preview")},handleTableChange:function(t){var e={};t&&(e.offset=t.pageSize*(t.current-1),e.pageSize=t.pageSize,e.current=t.current),this.searchMultiClassList(e)}}},T=Object(P.a)(M,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("a-modal",{attrs:{title:t.product.title+"-班课列表",width:1240,footer:null,visible:t.visible},on:{cancel:t.handleCancel}},[a("a-spin",{attrs:{spinning:t.ajaxLoading}},[a("a-table",{attrs:{columns:t.columns,"data-source":t.multiClassList,pagination:t.paging},on:{change:t.handleTableChange},scopedSlots:t._u([{key:"class_title",fn:function(e,r){return a("a",{attrs:{href:"javascript:;"},on:{click:function(e){return t.goToMultiClassManage(r.id)}}},[t._v("\n        "+t._s(e)+"\n      ")])}},{key:"course",fn:function(e,r){return a("a",{attrs:{href:"/course/"+r.courseId,target:"_blank"}},[t._v("\n        "+t._s(r.course?r.course.courseSetTitle:"")+"\n      ")])}},{key:"taskNum",fn:function(e,r){return a("a",{attrs:{href:"javascript:;"},on:{click:function(e){return t.goToMultiClassManage(r.id)}}},[t._v("\n        "+t._s(r.endTaskNum)+"/"+t._s(r.taskNum)+"\n      ")])}},{key:"assistant",fn:function(e){return[t._v("\n        "+t._s(e?e.join("、"):"")+"\n      ")]}},{key:"createdTime",fn:function(e){return[t._v("\n        "+t._s(t.$dateFormat(e,"YYYY-MM-DD HH:mm"))+"\n      ")]}},{key:"studentNum",fn:function(e,r){return a("a",{attrs:{href:"/admin/v2/multi_class/index#/manage/"+r.id+"/student_manage"}},[t._v("\n        "+t._s(e)+"\n      ")])}},{key:"action",fn:function(e,r){return[a("a-button",{attrs:{type:"link"},on:{click:function(e){return t.goToMultiClassManage(r.id)}}},[t._v("管理")]),t._v(" "),a("a-button",{attrs:{type:"link"},on:{click:function(e){return t.goToEditMultiClass(r.id)}}},[t._v("编辑")]),t._v(" "),a("a-button",{attrs:{type:"link"},on:{click:function(e){return t.goToMultiClassDataPreview(r.id)}}},[t._v("数据概览")])]}}])})],1)],1)}),[],!1,null,null,null).exports,j=a(1691);function S(t,e){var a=v()(t);if(p.a){var r=p()(t);e&&(r=r.filter((function(e){return u()(t,e).enumerable}))),a.push.apply(a,r)}return a}function O(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?S(Object(a),!0).forEach((function(e){g()(t,e,a[e])})):c.a?s()(t,c()(a)):S(Object(a)).forEach((function(e){n()(t,e,u()(a,e))}))}return t}var N={name:"MultiClassProduct",components:{ProductCard:w,MultiClassModal:T,AsideLayout:C.a,Empty:j.a},props:{},data:function(){return{modalVisible:!1,multiClassModalVisible:!1,form:this.$form.createForm(this),productList:[],paging:{offset:0,limit:9,total:0},title:"",getListLoading:!1,ajaxProductLoading:!1,editingProduct:null,currentProduct:{},modalTitle:"",headerTitle:"什么是产品库？",headerTip:"<p>产品是相同课程内容不同班课的集合，方便教务人员,实时了解产品的经营状况。机构可以根据自身课程情况进行差异设置。 产品可开设多个班课，班课可关联直播大班课课程</p> <p>范例1：<br>K12英语大班产品（产品）- K12英语大班三月班（班课）- k12英语大班直播课程（课程）</p> <p>范例2：<br>K12英语大班三月班（产品）-K12英语3月班1班/ k12英语三月班2班-k12英语大班直播课程（课程）</p>"}},created:function(){this.getProductList()},methods:{getProductList:function(){var t=arguments,e=this;return b()(_.a.mark((function a(){var r,n,i,s;return _.a.wrap((function(a){for(;;)switch(a.prev=a.next){case 0:return r=t.length>0&&void 0!==t[0]?t[0]:{},e.getListLoading=!0,a.prev=2,a.next=5,y.w.search({keywords:r.title||e.title,offset:r.offset||e.paging.offset||0,limit:r.limit||e.paging.limit||9});case 5:n=a.sent,i=n.data,(s=n.paging).page=s.offset/s.limit+1,e.productList=i,e.paging=s;case 11:return a.prev=11,e.getListLoading=!1,a.finish(11);case 14:case"end":return a.stop()}}),a,null,[[2,,11,14]])})))()},searchProductList:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";this.paging.offset=0,this.getProductList({title:t})},createMultiClassProduct:function(){this.modalVisible=!0,this.modalTitle="新建产品"},validatorTitle:function(t,e,a){var r=this;return b()(_.a.mark((function t(){var n;return _.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,y.J.search({type:"multiClassProduct",title:e,exceptId:r.editingProduct?r.editingProduct.id:0});case 2:n=t.sent,n.result||a("产品名称不能与已创建的相同"),a();case 6:case"end":return t.stop()}}),t)})))()},validatorLen:function(t,e,a){this.calculateByteLength(e)>40&&a("产品名称不能超过40个字符，一个中文字算2个字符"),a()},calculateByteLength:function(){for(var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",e=t.length,a=0;a<t.length;a++)t.charCodeAt(a)>127&&e++;return e},ajaxMultiClassProduct:function(t){t.preventDefault(),this.editingProduct?this.editMultiClassProduct():this.addMultiClassProduct()},addMultiClassProduct:function(){var t=this;return b()(_.a.mark((function e(){return _.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:t.form.validateFields(function(){var e=b()(_.a.mark((function e(a,r){var n,i;return _.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(!a){e.next=2;break}return e.abrupt("return");case 2:return e.prev=2,t.ajaxProductLoading=!0,e.next=6,y.w.add(r);case 6:n=e.sent,i=n.error,t.modalVisible=!1,t.form.resetFields(),i||t.getProductList({title:t.title});case 11:return e.prev=11,t.ajaxProductLoading=!1,e.finish(11);case 14:case"end":return e.stop()}}),e,null,[[2,,11,14]])})));return function(t,a){return e.apply(this,arguments)}}());case 1:case"end":return e.stop()}}),e)})))()},startEditMultiClassProduct:function(t){var e=this;this.editingProduct=t,this.modalVisible=!0,this.modalTitle="编辑产品",this.$nextTick((function(){e.form.setFieldsValue({title:t.title||"",remark:t.remark||""})}))},editMultiClassProduct:function(){var t=this;this.form.validateFields(function(){var e=b()(_.a.mark((function e(a,r){var n,i;return _.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(!a){e.next=2;break}return e.abrupt("return");case 2:return t.ajaxProductLoading=!0,e.prev=3,e.next=6,y.w.update(O(O({},r),{},{id:t.editingProduct.id}));case 6:n=e.sent,i=n.error,t.editingProduct=null,t.modalVisible=!1,t.form.resetFields(),i||t.getProductList({title:t.title});case 12:return e.prev=12,t.ajaxProductLoading=!1,e.finish(12);case 15:case"end":return e.stop()}}),e,null,[[3,,12,15]])})));return function(t,a){return e.apply(this,arguments)}}())},deleteMultiClassProduct:function(t){var e=this;return b()(_.a.mark((function a(){var r,n;return _.a.wrap((function(a){for(;;)switch(a.prev=a.next){case 0:return r=t.id,t.title,a.next=3,y.w.delete({id:r});case 3:n=a.sent,n.success&&e.getProductList();case 6:case"end":return a.stop()}}),a)})))()},lookoverMultiClass:function(t){var e=this;return b()(_.a.mark((function a(){return _.a.wrap((function(a){for(;;)switch(a.prev=a.next){case 0:e.currentProduct=t,e.multiClassModalVisible=!0;case 2:case"end":return a.stop()}}),a)})))()},closeModal:function(){this.form.resetFields(),this.modalVisible=!1,this.editingProduct=null},onChangePagination:function(t){this.paging.offset=(t-1)*this.paging.limit,this.getProductList()}}},$=(a(1969),Object(P.a)(N,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("aside-layout",{attrs:{breadcrumbs:[{name:"产品库"}],headerTip:t.headerTip,headerTitle:t.headerTitle}},[a("a-spin",{staticClass:"multi-class-product",attrs:{spinning:t.getListLoading||t.ajaxProductLoading}},[a("div",{staticClass:"clearfix"},[a("a-input-search",{staticStyle:{width:"262px"},attrs:{placeholder:"请输入产品名称",allowClear:!0},on:{search:t.searchProductList},model:{value:t.title,callback:function(e){t.title=e},expression:"title"}}),t._v(" "),a("a-button",{staticClass:"pull-right",attrs:{type:"primary"},on:{click:t.createMultiClassProduct}},[t._v("\n        新建产品\n      ")])],1),t._v(" "),a("a-row",{attrs:{gutter:24}},t._l(t.productList,(function(e){return a("a-col",{key:e.id,attrs:{sm:24,lg:12,xl:8}},[a("product-card",{attrs:{product:e},on:{edit:t.startEditMultiClassProduct,delete:t.deleteMultiClassProduct,lookover:t.lookoverMultiClass}})],1)})),1),t._v(" "),a("div",{staticClass:"text-center"},[t.paging&&t.productList.length>0?a("a-pagination",{staticClass:"mt24",attrs:{total:t.paging.total,"show-less-items":""},on:{change:t.onChangePagination},model:{value:t.paging.page,callback:function(e){t.$set(t.paging,"page",e)},expression:"paging.page"}}):t._e()],1),t._v(" "),a("a-modal",{attrs:{title:t.modalTitle,okText:"确认",cancelText:"取消",width:920,visible:t.modalVisible},on:{cancel:t.closeModal}},[a("a-form",{attrs:{form:t.form,"label-col":{span:3},"wrapper-col":{span:21}}},[a("a-form-item",{attrs:{label:"产品名称"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["title",{trigger:"blur",rules:[{required:!0,message:"产品名称不能为空"},{validator:t.validatorTitle},{validator:t.validatorLen}]}],expression:"['title', {\n              trigger: 'blur',\n              rules: [\n                { required: true, message: '产品名称不能为空' },\n                { validator: validatorTitle },\n                { validator: validatorLen },\n              ]\n            }]"}],attrs:{placeholder:"请输入产品名称"}})],1),t._v(" "),a("a-form-item",{attrs:{label:"备注"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["remark",{rules:[{max:30,message:"备注不能超过30个字"}]}],expression:"['remark', { rules: [{ max: 30, message: '备注不能超过30个字' }] }]"}],attrs:{placeholder:"备注30个字以内"}})],1)],1),t._v(" "),a("template",{slot:"footer"},[a("a-button",{key:"back",on:{click:t.closeModal}},[t._v("\n          取消\n        ")]),t._v(" "),a("a-button",{key:"submit",attrs:{type:"primary",loading:t.ajaxProductLoading},on:{click:t.ajaxMultiClassProduct}},[t._v("\n          确认\n        ")])],1)],2),t._v(" "),a("MultiClassModal",{attrs:{product:t.currentProduct,visible:t.multiClassModalVisible},on:{close:function(e){return t.multiClassModalVisible=e}}}),t._v(" "),t.getListLoading||t.ajaxProductLoading||t.productList.length?t._e():a("empty")],1)],1)}),[],!1,null,null,null));e.default=$.exports},1672:function(t,e,a){},1673:function(t,e,a){a(1674),t.exports=a(79).Reflect.deleteProperty},1674:function(t,e,a){var r=a(117),n=a(442).f,i=a(204);r(r.S,"Reflect",{deleteProperty:function(t,e){var a=n(i(t),e);return!(a&&!a.configurable)&&delete t[e]}})},1675:function(t,e,a){"use strict";var r={name:"AsideLayout",props:{breadcrumbs:{type:Array,required:!0},headerTitle:{type:String,default:""},headerTip:{type:String,default:""},titleTip:{type:String,default:""}}},n=(a(1676),a(34)),i=Object(n.a)(r,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"aside-layout"},[a("div",{staticClass:"aside-layout-header"},[a("a-breadcrumb",{staticClass:"pull-left aside-layout-header__breadcrumb",attrs:{separator:"/"}},t._l(t.breadcrumbs,(function(e,r){return a("a-breadcrumb-item",{key:r},[e.href?[a("a",{attrs:{href:e.href,target:"_blank"}},[t._v(t._s(e.name))])]:e.pathName?[a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return t.$router.push({name:e.pathName})}}},[t._v(t._s(e.name))])]:[t._v("\n          "+t._s(e.name)+"\n        ")]],2)})),1),t._v(" "),t.headerTip?a("a-popover",{attrs:{placement:"bottomLeft"}},[a("template",{slot:"content"},[a("div",{staticClass:"aside-header-tip",domProps:{innerHTML:t._s(t.headerTip)}})]),t._v(" "),a("span",{staticClass:"aside-header-title-icon"},[a("a-icon",{attrs:{theme:"filled",type:"question-circle"}}),a("span",{staticClass:"icon-circle"},[t._v(t._s(t.headerTitle))])],1)],2):t._e(),t._v(" "),t.titleTip?a("span",{staticClass:"aside-header-title-icon"},[t._v(t._s(t.titleTip))]):t._e()],1),t._v(" "),a("div",{staticClass:"aside-layout-main"},[t._t("default")],2)])}),[],!1,null,null,null);e.a=i.exports},1676:function(t,e,a){"use strict";var r=a(1672);a.n(r).a},1677:function(t,e,a){t.exports=a(1673)},1680:function(t,e,a){},1691:function(t,e,a){"use strict";var r={name:"Empty",props:{description:{type:String,default:"暂无数据"},image:{type:String,default:"/static-dist/app/img/vue/empty.png"},imageStyle:{type:Object,default:function(){return{height:"200px"}}}}},n=(a(1692),a(34)),i=Object(n.a)(r,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("a-empty",{staticClass:"custom-empty",attrs:{image:t.image,"image-style":t.imageStyle}},[a("span",{staticClass:"custom-empty__description",attrs:{slot:"description"},slot:"description"},[t._v(t._s(t.description))])])}),[],!1,null,null,null);e.a=i.exports},1692:function(t,e,a){"use strict";var r=a(1680);a.n(r).a},1797:function(t,e,a){},1798:function(t,e,a){},1968:function(t,e,a){"use strict";var r=a(1797);a.n(r).a},1969:function(t,e,a){"use strict";var r=a(1798);a.n(r).a},38:function(t,e,a){var r=a(116);t.exports=function(t,e,a){return e in t?r(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}},4:function(t,e){t.exports=function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}},41:function(t,e,a){t.exports=a(724)},5:function(t,e,a){var r=a(116);function n(t,e){for(var a=0;a<e.length;a++){var n=e[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),r(t,n.key,n)}}t.exports=function(t,e,a){return e&&n(t.prototype,e),a&&n(t,a),t}},529:function(t,e,a){var r=a(117),n=a(79),i=a(352);t.exports=function(t,e){var a=(n.Object||{})[t]||Object[t],s={};s[t]=e(a),r(r.S+r.F*i((function(){a(1)})),"Object",s)}},724:function(t,e,a){a(725),t.exports=a(79).Object.keys},725:function(t,e,a){var r=a(414),n=a(413);a(529)("keys",(function(){return function(t){return n(r(t))}}))},726:function(t,e,a){a(727);var r=a(79).Object;t.exports=function(t,e){return r.defineProperties(t,e)}},727:function(t,e,a){var r=a(117);r(r.S+r.F*!a(221),"Object",{defineProperties:a(601)})},728:function(t,e,a){a(729),t.exports=a(79).Object.getOwnPropertyDescriptors},729:function(t,e,a){var r=a(117),n=a(730),i=a(253),s=a(442),o=a(603);r(r.S,"Object",{getOwnPropertyDescriptors:function(t){for(var e,a,r=i(t),c=s.f,l=n(r),u={},d=0;l.length>d;)void 0!==(a=c(r,e=l[d++]))&&o(u,e,a);return u}})},730:function(t,e,a){var r=a(530),n=a(486),i=a(204),s=a(146).Reflect;t.exports=s&&s.ownKeys||function(t){var e=r.f(i(t)),a=n.f;return a?e.concat(a(t)):e}},731:function(t,e,a){a(732);var r=a(79).Object;t.exports=function(t,e){return r.getOwnPropertyDescriptor(t,e)}},732:function(t,e,a){var r=a(253),n=a(442).f;a(529)("getOwnPropertyDescriptor",(function(){return function(t,e){return n(r(t),e)}}))},733:function(t,e,a){a(602),t.exports=a(79).Object.getOwnPropertySymbols},80:function(t,e,a){t.exports=a(731)},82:function(t,e,a){t.exports=a(728)},83:function(t,e,a){t.exports=a(733)}}]);