(window.webpackJsonp=window.webpackJsonp||[]).push([[420],{116:function(t,e,n){t.exports=n(600)},123:function(t,e,n){t.exports=n(726)},1641:function(t,e,n){"use strict";n.r(e);var s=n(116),a=n.n(s),o=n(123),i=n.n(o),r=n(82),c=n.n(r),u=n(80),l=n.n(u),d=n(83),f=n.n(d),p=n(41),v=n.n(p),h=n(38),m=n.n(h),y=n(252),g=n.n(y),k=n(1670),x=n(1838),_={video:"视频",audio:"音频",live:"直播",discuss:"讨论",flash:"Flash",doc:"文档",ppt:"PPT",testpaper:"考试",homework:"作业",exercise:"练习",download:"下载资料",text:"图文"},b={name:"TeachMode",filters:{teachType:function(t){return _[t]}},props:{record:{type:Object,required:!0,default:function(){return{}}}},computed:{replayStatus:function(){var t=this.record.tasks,e=t.type,n=t.activity.ext;return!("live"!=e||!["generated","videoGenerated"].includes(n.replayStatus))},progressStatus:function(){var t=this.record.tasks,e=t.type,n=t.activity.ext;return"live"==e?n.progressStatus:""}}},S=n(34),w=Object(S.a)(b,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[t._v("\n  "+t._s(t._f("teachType")(t.record.tasks.type))+"\n\n  "),t.progressStatus?[n("a-divider",{staticStyle:{margin:"0 4px"},attrs:{type:"vertical"}}),t._v(" "),"created"==t.progressStatus?n("span",{staticStyle:{"font-size":"14px",color:"#fb8d4d"}},[t._v("未开始")]):"start"==t.progressStatus?n("span",{staticStyle:{"font-size":"14px",color:"#43bc60"}},[t._v("直播中")]):"closed"==t.progressStatus?n("span",{staticStyle:{"font-size":"14px",color:"#999"}},[t._v("已结束")]):t._e()]:t._e(),t._v(" "),t.replayStatus?[n("br"),t._v(" "),n("a-tag",{staticStyle:{"margin-top":"4px"},attrs:{color:"green"}},[t._v("有回放")])]:t._e()],2)}),[],!1,null,null,null).exports,I=n(1715);function T(t,e){var n=v()(t);if(f.a){var s=f()(t);e&&(s=s.filter((function(e){return l()(t,e).enumerable}))),n.push.apply(n,s)}return n}function C(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?T(Object(n),!0).forEach((function(e){m()(t,e,n[e])})):c.a?i()(t,c()(n)):T(Object(n)).forEach((function(e){a()(t,e,l()(n,e))}))}return t}var O=[{title:"课时名称",dataIndex:"name",width:"15%",scopedSlots:{customRender:"name"}},{title:"任务类型",dataIndex:"mode",filters:[{text:"视频",value:"video"},{text:"音频",value:"audio"},{text:"直播",value:"live"},{text:"讨论",value:"discuss"},{text:"文档",value:"doc"},{text:"PPT",value:"ppt"},{text:"考试",value:"testpaper"},{text:"作业",value:"homework"},{text:"练习",value:"exercise"},{text:"下载资料",value:"download"},{text:"图文",value:"text"}],width:"12%",scopedSlots:{customRender:"mode"}},{title:"开课时间",dataIndex:"startTime",width:"13%",scopedSlots:{customRender:"startTime"}},{title:"时长",width:"10%",dataIndex:"time",scopedSlots:{customRender:"time"}},{title:"授课老师",width:"10%",ellipsis:!0,dataIndex:"teacher",scopedSlots:{customRender:"teacher"}},{title:"助教老师",width:"10%",ellipsis:!0,dataIndex:"assistant",scopedSlots:{customRender:"assistant"}},{title:"问题",width:"10%",dataIndex:"questionNum",scopedSlots:{customRender:"questionNum"}},{title:"学习人数",width:"10%",dataIndex:"studyStudentNum",scopedSlots:{customRender:"studyStudentNum"}},{title:"操作",width:"128px",dataIndex:"actions",scopedSlots:{customRender:"actions"}}],j={components:{ClassName:x.a,TeachMode:w,Assistant:I.a},data:function(){return{data:[],pagination:{},loading:!1,columns:O,locale:{filterConfirm:"确定",filterReset:"重置"},multiClassId:this.$route.params.id,keywords:"",courseId:0,courseSetId:0}},mounted:function(){var t=this;this.fetchLessons(),this.fetchMultiClass(),$("#modal").on("hide.bs.modal",(function(){var e={limit:10,offset:10*(t.pagination.current-1)};t.fetchLessons(e)}))},destroyed:function(){$("#modal").off("hide.bs.modal")},filters:{timeTransfer:function(t){var e=g.a.floor(t/60),n=t%60,s="".concat(e,"min ");return n&&(s+="".concat(n,"s")),s}},methods:{handleTableChange:function(t,e,n){var s=n.order,a=C({},this.pagination);a.current=t.current,this.pagination=a;var o={limit:t.pageSize,offset:(t.current-1)*t.pageSize};g.a.size(e)&&(o.types=e.mode),s&&(o.sort="ascend"==s?"ASC":"DESC"),this.fetchLessons(o)},fetchMultiClass:function(){var t=this;k.t.get(this.multiClassId).then((function(e){var n=e.course,s=n.id,a=n.courseSetId;t.courseId=s,t.courseSetId=a}))},fetchLessons:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};this.loading=!0,k.t.getLessons(this.multiClassId,C({limit:10,titleLike:this.keywords},e)).then((function(e){var n=C({},t.pagination);n.total=e.paging.total,t.loading=!1,t.data=e.data,t.pagination=n}))},onSearch:function(t){this.keywords=t,this.pagination.current=1,this.fetchLessons()},handleMenuClick:function(t,e){"copy"!==t?["publish","unpublish"].includes(t)?this.updateTaskStatus(t,e):"delete"===t&&this.deleteTask(e):this.copy(e)},copy:function(t){this.$clipboard(this.copyTaskUrl(t)),this.$message.success("复制成功")},updateTaskStatus:function(t,e){var n=this,s=e.tasks,a=s.courseId,o=s.id,i="publish"==t?"发布成功":"取消发布成功";k.g.updateTaskStatus(a,o,{type:t}).then((function(){n.$message.success(i),g.a.forEach(n.data,(function(e){e.tasks.id==o&&(e.tasks.status="publish"===t?"published":"create")}))}))},deleteTask:function(t){var e=this,n=t.tasks,s=n.courseId,a=n.id;this.$confirm({title:"删除",content:"是否确定删除该课时吗?",okType:"danger",onOk:function(){k.g.deleteTask(s,a).then((function(t){t.success&&(e.$message.success("删除成功"),g.a.forEach(e.data,(function(t,n){t.tasks.id==a&&e.data.splice(n,1)})))}))}})},copyTaskUrl:function(t){return"".concat(window.location.origin,"/course/").concat(t.courseId)},goToEditorLesson:function(){this.$router.push({name:"MultiClassEditorLesson",params:{id:this.courseId},query:{courseSetId:this.courseSetId,multiClassId:this.multiClassId}})}}},R=(n(1976),Object(S.a)(j,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"class-info"},[n("div",{staticClass:"clearfix",staticStyle:{"margin-bottom":"16px"}},[n("a-input-search",{staticClass:"pull-left",staticStyle:{width:"260px"},attrs:{placeholder:"请输入课时关键字搜索"},on:{search:t.onSearch}}),t._v(" "),n("a-button",{staticClass:"pull-right",attrs:{type:"primary"},on:{click:t.goToEditorLesson}},[t._v("\n      重排课时/新增课时\n    ")])],1),t._v(" "),n("a-table",{attrs:{columns:t.columns,"row-key":function(t){return t.id},"data-source":t.data,loading:t.loading,pagination:t.pagination,locale:t.locale},on:{change:t.handleTableChange},scopedSlots:t._u([{key:"name",fn:function(t,e){return n("class-name",{attrs:{record:e}})}},{key:"mode",fn:function(t,e){return n("teach-mode",{attrs:{record:e}})}},{key:"startTime",fn:function(e,n){return["live"===n.tasks.type?[t._v("\n        "+t._s(t.$dateFormat(n.tasks.startTime,"YYYY-MM-DD HH:mm"))+"\n      ")]:[t._v(" -- ")]]}},{key:"time",fn:function(e,n){return["video"===n.tasks.type?[t._v("\n        "+t._s((n.tasks.length/60).toFixed(2))+"min\n      ")]:"live"===n.tasks.type?[t._v(t._s(n.tasks.length)+"min")]:[t._v("--")]]}},{key:"teacher",fn:function(e){return[t._v(t._s(e.nickname))]}},{key:"assistant",fn:function(t){return n("assistant",{attrs:{assistant:t}})}},{key:"questionNum",fn:function(e,s){return n("a",{attrs:{href:"/my/course/"+s.tasks.courseId+"/question?type=question",target:"_blank"}},[t._v(t._s(e))])}},{key:"studyStudentNum",fn:function(e,n){return[t._v("\n      "+t._s(e)+"/"+t._s(n.totalStudentNum)+"\n    ")]}},{key:"actions",fn:function(e,s){return[n("a-dropdown",{staticStyle:{"margin-right":"12px"},attrs:{trigger:["hover"],placement:"bottomRight"}},[n("a-button",{attrs:{type:"link"},on:{click:function(t){return t.preventDefault()}}},[n("a-icon",{attrs:{type:"copy"}})],1),t._v(" "),n("a-menu",{attrs:{slot:"overlay"},on:{click:function(e){var n=e.key;return t.handleMenuClick(n,s)}},slot:"overlay"},[n("a-menu-item",{key:"copy"},[t._v("\n            复制课程链接\n          ")])],1)],1),t._v(" "),n("a-button",{attrs:{type:"link","data-toggle":"modal","data-target":"#modal","data-url":"/course/"+s.courseId+"/task/"+s.tasks.id+"/update"}},[t._v("编辑")]),t._v(" "),n("a-dropdown",{attrs:{trigger:["hover"],placement:"bottomRight"}},[n("a",{staticClass:"ant-dropdown-link",staticStyle:{"margin-left":"-6px"},on:{click:function(t){return t.preventDefault()}}},[n("a-icon",{attrs:{type:"caret-down"}})],1),t._v(" "),n("a-menu",{attrs:{slot:"overlay"},on:{click:function(e){var n=e.key;return t.handleMenuClick(n,s)}},slot:"overlay"},["published"==s.tasks.status?n("a-menu-item",{key:"unpublish"},[t._v("\n            取消发布\n          ")]):[n("a-menu-item",{key:"publish"},[t._v("\n              立即发布\n            ")]),t._v(" "),n("a-menu-item",{key:"delete"},[n("span",{staticStyle:{color:"#fe4040",cursor:"pointer"}},[t._v("删除")])])]],2)],1)]}}])})],1)}),[],!1,null,null,null));e.default=R.exports},1673:function(t,e,n){n(1674),t.exports=n(79).Reflect.deleteProperty},1674:function(t,e,n){var s=n(117),a=n(442).f,o=n(204);s(s.S,"Reflect",{deleteProperty:function(t,e){var n=a(o(t),e);return!(n&&!n.configurable)&&delete t[e]}})},1677:function(t,e,n){t.exports=n(1673)},1687:function(t,e,n){},1706:function(t,e,n){"use strict";var s=n(1687);n.n(s).a},1715:function(t,e,n){"use strict";var s=n(252),a=n.n(s),o={name:"Assistant",props:{assistant:{required:!0,default:function(){return[]}}},data:function(){return{ellipsis:!1}},computed:{assistants:function(){if(!a.a.size(this.assistant))return"- -";var t=[];return this.assistant.nickname?t.push(this.assistant.nickname):a.a.forEach(this.assistant,(function(e,n){e.nickname?t.push(e.nickname):t.push(e)})),a.a.join(t,"、")}},mounted:function(){var t=this.$refs.assistant;this.ellipsis=t.scrollWidth>t.clientWidth}},i=(n(1706),n(34)),r=Object(i.a)(o,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{ref:"assistant",class:[t.ellipsis?"assistant":"","text-overflow"]},[t._v("\n  "+t._s(t.assistants)+"\n\n  "),t.ellipsis?n("a-tooltip",{staticClass:"assistant-all",attrs:{placement:"bottom"}},[n("template",{slot:"title"},[n("span",[t._v(t._s(t.assistants))])]),t._v(" "),n("svg-icon",{staticStyle:{color:"#979797"},attrs:{icon:"icon-more"}})],2):t._e()],1)}),[],!1,null,"18dadfc4",null);e.a=r.exports},1805:function(t,e,n){},1838:function(t,e,n){"use strict";var s={name:"ClassName",props:{record:{type:Object,required:!0,default:function(){return{}}}},computed:{className:function(){var t=this.record,e=t.chapterTitle,n=t.unitTitle,s=t.title,a=t.tasks,o=t.tasks.number,i="";if("lesson"===a.mode)i="".concat(o,". ").concat(s);else{var r=o.split("-");i="".concat(o,".").concat(r[1]-1," [任务]").concat(a.title)}return n&&(i="".concat(n," ").concat(i)),e&&(i=n?"".concat(e," - ").concat(i):"".concat(e," ").concat(i)),i}}},a=n(34),o=Object(a.a)(s,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"text-overflow"},[n("span",{attrs:{title:t.className}},[t._v(t._s(t.className))]),t._v(" "),n("br"),t._v(" "),"published"!=t.record.tasks.status?n("a-tag",{staticStyle:{"margin-top":"4px"}},[t._v("未发布")]):t._e()],1)}),[],!1,null,null,null);e.a=o.exports},1976:function(t,e,n){"use strict";var s=n(1805);n.n(s).a},38:function(t,e,n){var s=n(116);t.exports=function(t,e,n){return e in t?s(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}},4:function(t,e){t.exports=function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}},41:function(t,e,n){t.exports=n(724)},5:function(t,e,n){var s=n(116);function a(t,e){for(var n=0;n<e.length;n++){var a=e[n];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),s(t,a.key,a)}}t.exports=function(t,e,n){return e&&a(t.prototype,e),n&&a(t,n),t}},529:function(t,e,n){var s=n(117),a=n(79),o=n(352);t.exports=function(t,e){var n=(a.Object||{})[t]||Object[t],i={};i[t]=e(n),s(s.S+s.F*o((function(){n(1)})),"Object",i)}},724:function(t,e,n){n(725),t.exports=n(79).Object.keys},725:function(t,e,n){var s=n(414),a=n(413);n(529)("keys",(function(){return function(t){return a(s(t))}}))},726:function(t,e,n){n(727);var s=n(79).Object;t.exports=function(t,e){return s.defineProperties(t,e)}},727:function(t,e,n){var s=n(117);s(s.S+s.F*!n(221),"Object",{defineProperties:n(601)})},728:function(t,e,n){n(729),t.exports=n(79).Object.getOwnPropertyDescriptors},729:function(t,e,n){var s=n(117),a=n(730),o=n(253),i=n(442),r=n(603);s(s.S,"Object",{getOwnPropertyDescriptors:function(t){for(var e,n,s=o(t),c=i.f,u=a(s),l={},d=0;u.length>d;)void 0!==(n=c(s,e=u[d++]))&&r(l,e,n);return l}})},730:function(t,e,n){var s=n(530),a=n(486),o=n(204),i=n(146).Reflect;t.exports=i&&i.ownKeys||function(t){var e=s.f(o(t)),n=a.f;return n?e.concat(n(t)):e}},731:function(t,e,n){n(732);var s=n(79).Object;t.exports=function(t,e){return s.getOwnPropertyDescriptor(t,e)}},732:function(t,e,n){var s=n(253),a=n(442).f;n(529)("getOwnPropertyDescriptor",(function(){return function(t,e){return a(s(t),e)}}))},733:function(t,e,n){n(602),t.exports=n(79).Object.getOwnPropertySymbols},80:function(t,e,n){t.exports=n(731)},82:function(t,e,n){t.exports=n(728)},83:function(t,e,n){t.exports=n(733)}}]);