(window.webpackJsonp=window.webpackJsonp||[]).push([[404],{101:function(e,t,a){e.exports=a(218)},109:function(e,t,a){e.exports=a(535)},1425:function(e,t,a){"use strict";a.r(t);var n=a(109),r=a.n(n),o=a(101),i=a.n(o),s=a(68),c=a.n(s),l=a(78),u=a.n(l),d=a(69),f=a.n(d),m=a(42),p=a.n(m),v=a(37),h=a.n(v),_=a(194),y=a.n(_),g=a(349),b=a.n(g),k=a(397),w=a.n(k),x=a(1458),C={name:"EditModal",props:{tags:{type:Array,required:!0}},data:function(){return{visible:!1,confirmLoading:!1,form:{id:void 0,tagIds:void 0,replayPublic:"0"}}},methods:{showModal:function(e){var t=e.id,a=e.tag,n=e.replayPublic;this.form.id=t,this.form.tagIds=a,this.form.replayPublic=n,this.visible=!0},handleCancel:function(){this.visible=!1},handleOk:function(){var e=this;return b()(y.a.mark((function t(){var a,n;return y.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e.confirmLoading=!0,a={query:{id:e.form.id},params:e.form},t.next=4,x.q.update(a);case 4:n=t.sent,n.success&&(e.$message.success(Translator.trans("message.edit_succeeded")),e.confirmLoading=!1,e.visible=!1,e.$emit("success",e.form));case 7:case"end":return t.stop()}}),t)})))()}}},T=a(30),O=Object(T.a)(C,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("a-modal",{attrs:{title:e._f("trans")("modal.title.edit"),visible:e.visible,"confirm-loading":e.confirmLoading},on:{ok:e.handleOk,cancel:e.handleCancel}},[a("a-form-model",{attrs:{model:e.form,labelCol:{span:4},wrapperCol:{span:20}}},[a("a-form-model-item",{attrs:{label:e._f("trans")("form.label.tag")}},[a("a-select",{attrs:{mode:"multiple",placeholder:e._f("trans")("placeholder.playback_label")},model:{value:e.form.tagIds,callback:function(t){e.$set(e.form,"tagIds",t)},expression:"form.tagIds"}},e._l(e.tags,(function(t){return a("a-select-option",{key:t.id,attrs:{value:t.id}},[e._v("\n          "+e._s(t.name)+"\n        ")])})),1)],1),e._v(" "),a("a-form-model-item",{attrs:{label:e._f("trans")("form.label.share")}},[a("a-radio-group",{model:{value:e.form.replayPublic,callback:function(t){e.$set(e.form,"replayPublic",t)},expression:"form.replayPublic"}},[a("a-radio",{staticClass:"mt8",attrs:{value:"1"}},[e._v("\n          "+e._s(e._f("trans")("radio.share_to_live_playback"))+"\n        ")]),e._v(" "),a("a-radio",{staticClass:"mt8",attrs:{value:"0"}},[e._v("\n          "+e._s(e._f("trans")("radio.visible_only_to_yourself"))+"\n        ")])],1)],1)],1)],1)}),[],!1,null,null,null).exports,S={name:"RemoveModal",data:function(){return{visible:!1,confirmLoading:!1,currentId:void 0}},methods:{showModal:function(e){this.currentId=e,this.visible=!0},handleCancel:function(){this.visible=!1},handleOk:function(){var e=this;return b()(y.a.mark((function t(){var a,n;return y.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e.confirmLoading=!0,a={data:{ids:[e.currentId]}},t.next=4,x.q.delete(a);case 4:n=t.sent,n.success&&(e.$message.success(Translator.trans("message.removal_succeeded")),e.confirmLoading=!1,e.visible=!1,e.$emit("success",e.currentId));case 7:case"end":return t.stop()}}),t)})))()}}},I=Object(T.a)(S,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("a-modal",{attrs:{title:e._f("trans")("site.btn.remove_playback"),visible:e.visible},on:{cancel:e.handleCancel}},[e._v("\n  "+e._s(e._f("trans")("modal.content.remove_playback_resources"))+"\n  "),a("template",{slot:"footer"},[a("div",{staticClass:"clearfix"},[a("span",{staticClass:"pull-left",staticStyle:{color:"#fe4040","margin-top":"7px"}},[e._v("\n        "+e._s(e._f("trans")("live.playback.tip.cannot_be_used_normally"))+"\n      ")]),e._v(" "),a("a-button",{attrs:{type:"danger",loading:e.confirmLoading},on:{click:e.handleOk}},[e._v("\n        "+e._s(e._f("trans")("site.btn.confirm"))+"\n      ")])],1)])],2)}),[],!1,null,null,null).exports;function F(e,t){var a=p()(e);if(f.a){var n=f()(e);t&&(n=n.filter((function(t){return u()(e,t).enumerable}))),a.push.apply(a,n)}return a}function P(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?F(Object(a),!0).forEach((function(t){h()(e,t,a[t])})):c.a?i()(e,c()(a)):F(Object(a)).forEach((function(t){r()(e,t,u()(a,t))}))}return e}var $=[{title:Translator.trans("live_name"),dataIndex:"title"},{title:Translator.trans("live_statistics.presenter"),dataIndex:"anchor"},{title:Translator.trans("live_playback_duration"),dataIndex:"liveTime"},{title:Translator.trans("live_statistics.live_time"),dataIndex:"liveStartTime"},{title:Translator.trans("live_statistics.operation"),scopedSlots:{customRender:"actions"}}],j={name:"LivePlayback",components:{EditModal:O,RemoveModal:I},data:function(){return{searchForm:{time:void 0,replayTagId:void 0,courseCategoryId:void 0,keyword:"",keywordType:"activityTitle"},data:[],columns:$,pagination:{hideOnSinglePage:!0,current:1,pageSize:10,total:0},loading:!1,categoryData:[],tagData:[]}},computed:{searchQuery:function(){var e=w.a.pickBy(this.searchForm,w.a.identity);if(!w.a.size(e))return{};var t=e.time;return t&&(e.startTime=moment(t[0]).unix(),e.endTime=moment(t[1]).unix(),delete e.time),e}},mounted:function(){this.fetchLiveReplay(),this.fetchCourseCategory(),this.fetchCourseTag()},methods:{fetchCourseCategory:function(){var e=this;return b()(y.a.mark((function t(){var a,n;return y.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,x.h.get();case 2:a=t.sent,n=a.data,e.categoryData=n;case 5:case"end":return t.stop()}}),t)})))()},fetchCourseTag:function(){var e=this;return b()(y.a.mark((function t(){var a,n;return y.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,x.k.get();case 2:a=t.sent,n=a.data,e.tagData=n;case 5:case"end":return t.stop()}}),t)})))()},handleClickSearch:function(){this.pagination.current=1,this.fetchLiveReplay()},handleTableChange:function(e){this.pagination.current=e.current,this.fetchLiveReplay()},fetchLiveReplay:function(){var e=this;return b()(y.a.mark((function t(){var a,n,r,o,i,s,c;return y.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e.loading=!0,a=e.pagination,n=a.current,r=a.pageSize,o={params:P({offset:(n-1)*r,limit:r},e.searchQuery)},t.next=5,x.q.get(o);case 5:i=t.sent,s=i.data,c=i.paging,e.loading=!1,e.pagination.total=c.total,e.data=s;case 11:case"end":return t.stop()}}),t)})))()},handleClickView:function(e){window.open(e)},handleClickEdit:function(e){this.$refs.editModal.showModal(e)},handleClickRemove:function(e){this.$refs.removeModal.showModal(e)},removeSuccess:function(e){var t=this;w.a.forEach(this.data,(function(a,n){if(a.id===e)return t.data.splice(n,1),!1}))},editSuccess:function(e){var t=e.id,a=e.replayPublic,n=e.tagIds;w.a.forEach(this.data,(function(e,r){if(e.id===t)return e.tag=n,e.replayPublic=a,!1}))}}},L=Object(T.a)(j,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("a-form-model",{staticClass:"mt16",attrs:{layout:"inline",model:e.searchForm}},[a("a-form-model-item",[a("a-range-picker",{model:{value:e.searchForm.time,callback:function(t){e.$set(e.searchForm,"time",t)},expression:"searchForm.time"}})],1),e._v(" "),a("a-form-model-item",[a("a-select",{staticStyle:{width:"160px"},attrs:{allowClear:!0,placeholder:e._f("trans")("placeholder.course_category")},model:{value:e.searchForm.courseCategoryId,callback:function(t){e.$set(e.searchForm,"courseCategoryId",t)},expression:"searchForm.courseCategoryId"}},e._l(e.categoryData,(function(t){return a("a-select-option",{key:t.id,attrs:{value:t.id}},[e._v("\n          "+e._s(t.name)+"\n        ")])})),1)],1),e._v(" "),a("a-form-model-item",[a("a-select",{staticStyle:{width:"160px"},attrs:{allowClear:!0,placeholder:e._f("trans")("placeholder.playback_label")},model:{value:e.searchForm.replayTagId,callback:function(t){e.$set(e.searchForm,"replayTagId",t)},expression:"searchForm.replayTagId"}},e._l(e.tagData,(function(t){return a("a-select-option",{key:t.id,attrs:{value:t.id}},[e._v("\n          "+e._s(t.name)+"\n        ")])})),1)],1),e._v(" "),a("a-form-model-item",[a("a-input-group",{attrs:{compact:""}},[a("a-select",{staticStyle:{width:"100px"},attrs:{"default-value":"activityTitle"},model:{value:e.searchForm.keywordType,callback:function(t){e.$set(e.searchForm,"keywordType",t)},expression:"searchForm.keywordType"}},[a("a-select-option",{attrs:{value:"activityTitle"}},[e._v("\n            "+e._s(e._f("trans")("live_name"))+"\n          ")]),e._v(" "),a("a-select-option",{attrs:{value:"anchor"}},[e._v("\n            "+e._s(e._f("trans")("live_statistics.presenter"))+"\n          ")]),e._v(" "),a("a-select-option",{attrs:{value:"courseTitle"}},[e._v("\n            "+e._s(e._f("trans")("course.name"))+"\n          ")])],1),e._v(" "),a("a-input",{staticStyle:{width:"200px"},attrs:{placeholder:e._f("trans")("placeholder.enter_keyword")},model:{value:e.searchForm.keyword,callback:function(t){e.$set(e.searchForm,"keyword",t)},expression:"searchForm.keyword"}})],1)],1),e._v(" "),a("a-form-model-item",[a("a-button",{attrs:{type:"primary"},on:{click:e.handleClickSearch}},[e._v("\n        "+e._s(e._f("trans")("site.search_hint"))+"\n      ")])],1)],1),e._v(" "),a("a-table",{staticClass:"mt16",attrs:{columns:e.columns,"row-key":function(e){return e.id},"data-source":e.data,pagination:e.pagination,loading:e.loading},on:{change:e.handleTableChange},scopedSlots:e._u([{key:"actions",fn:function(t){return[a("a-button-group",[a("a-button",{staticStyle:{padding:"0 8px"},attrs:{type:"primary"},on:{click:function(a){return e.handleClickEdit(t)}}},[e._v("\n          "+e._s(e._f("trans")("modal.title.edit"))+"\n        ")]),e._v(" "),a("a-dropdown",{attrs:{placement:"bottomRight"}},[a("a-menu",{attrs:{slot:"overlay"},slot:"overlay"},[a("a-menu-item",{on:{click:function(a){return e.handleClickView(t.url)}}},[e._v("\n              "+e._s(e._f("trans")("site.btn.view_playback"))+"\n            ")]),e._v(" "),a("a-menu-item",{on:{click:function(a){return e.handleClickRemove(t.id)}}},[e._v("\n              "+e._s(e._f("trans")("site.btn.remove_playback"))+"\n            ")])],1),e._v(" "),a("a-button",{staticStyle:{padding:"0 8px"},attrs:{type:"primary"}},[a("a-icon",{attrs:{type:"down"}})],1)],1)],1)]}}])}),e._v(" "),a("remove-modal",{ref:"removeModal",on:{success:e.removeSuccess}}),e._v(" "),a("edit-modal",{ref:"editModal",attrs:{tags:e.tagData},on:{success:e.editSuccess}})],1)}),[],!1,null,null,null);t.default=L.exports},1461:function(e,t,a){a(1462),e.exports=a(53).Reflect.deleteProperty},1462:function(e,t,a){var n=a(72),r=a(217).f,o=a(135);n(n.S,"Reflect",{deleteProperty:function(e,t){var a=r(o(e),t);return!(a&&!a.configurable)&&delete e[t]}})},1465:function(e,t,a){e.exports=a(1461)},2:function(e,t){e.exports=function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}},218:function(e,t,a){a(219);var n=a(53).Object;e.exports=function(e,t){return n.defineProperties(e,t)}},219:function(e,t,a){var n=a(72);n(n.S+n.F*!a(140),"Object",{defineProperties:a(370)})},220:function(e,t,a){a(221),e.exports=a(53).Object.getOwnPropertyDescriptors},221:function(e,t,a){var n=a(72),r=a(222),o=a(181),i=a(217),s=a(372);n(n.S,"Object",{getOwnPropertyDescriptors:function(e){for(var t,a,n=o(e),c=i.f,l=r(n),u={},d=0;l.length>d;)void 0!==(a=c(n,t=l[d++]))&&s(u,t,a);return u}})},222:function(e,t,a){var n=a(350),r=a(320),o=a(135),i=a(102).Reflect;e.exports=i&&i.ownKeys||function(e){var t=n.f(o(e)),a=r.f;return a?t.concat(a(e)):t}},223:function(e,t,a){a(371),e.exports=a(53).Object.getOwnPropertySymbols},3:function(e,t,a){var n=a(109);function r(e,t){for(var a=0;a<t.length;a++){var r=t[a];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),n(e,r.key,r)}}e.exports=function(e,t,a){return t&&r(e.prototype,t),a&&r(e,a),e}},37:function(e,t,a){var n=a(109);e.exports=function(e,t,a){return t in e?n(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}},42:function(e,t,a){e.exports=a(627)},485:function(e,t,a){var n=a(72),r=a(53),o=a(319);e.exports=function(e,t){var a=(r.Object||{})[e]||Object[e],i={};i[e]=t(a),n(n.S+n.F*o((function(){a(1)})),"Object",i)}},627:function(e,t,a){a(628),e.exports=a(53).Object.keys},628:function(e,t,a){var n=a(369),r=a(368);a(485)("keys",(function(){return function(e){return r(n(e))}}))},629:function(e,t,a){a(630);var n=a(53).Object;e.exports=function(e,t){return n.getOwnPropertyDescriptor(e,t)}},630:function(e,t,a){var n=a(181),r=a(217).f;a(485)("getOwnPropertyDescriptor",(function(){return function(e,t){return r(n(e),t)}}))},68:function(e,t,a){e.exports=a(220)},69:function(e,t,a){e.exports=a(223)},78:function(e,t,a){e.exports=a(629)}}]);