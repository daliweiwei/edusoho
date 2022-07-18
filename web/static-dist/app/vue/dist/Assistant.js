(window.webpackJsonp=window.webpackJsonp||[]).push([[401],{110:function(t,e,r){t.exports=r(342)},130:function(t,e,r){t.exports=r(532)},1428:function(t,e,r){"use strict";r.r(e);var n=r(130),a=r.n(n),i=r(110),s=r.n(i),o=r(80),c=r.n(o),u=r(70),l=r.n(u),d=r(81),f=r.n(d),v=r(44),p=r.n(v),_=r(192),m=r.n(_),h=r(340),b=r.n(h),g=r(37),y=r.n(g),k=r(1449),x=r(1444),S=r(1478);function w(t,e){var r=p()(t);if(f.a){var n=f()(t);e&&(n=n.filter((function(e){return l()(t,e).enumerable}))),r.push.apply(r,n)}return r}function C(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?w(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):c.a?s()(t,c()(r)):w(Object(r)).forEach((function(e){a()(t,e,l()(r,e))}))}return t}var O=[{title:"用户名",ellipsis:!0,dataIndex:"nickname",scopedSlots:{customRender:"nickname"}},{title:"是否绑定销客助手",dataIndex:"isScrmBind",ellipsis:!0,scopedSlots:{customRender:"isScrmBind"}},{title:"现带班课总数",dataIndex:"liveMultiClassNum",ellipsis:!0},{title:"现学员总数",dataIndex:"liveMultiClassStudentNum",ellipsis:!0},{title:"已结班班课总数",dataIndex:"endMultiClassNum",ellipsis:!0},{title:"已结班班课学员总数",dataIndex:"endMultiClassStudentNum",ellipsis:!0},{title:"最近登录",ellipsis:!0,scopedSlots:{customRender:"loginInfo"}},{title:"操作",scopedSlots:{customRender:"action"}}],j={name:"assistants",components:{userInfoTable:S.a,AsideLayout:k.a},data:function(){return{visible:!1,user:{},columns:O,pageData:[],loading:!1,pagination:{},keyWord:""}},created:function(){this.fetchAssistant()},methods:{handleTableChange:function(t){var e=C({},this.pagination);e.current=t.current,this.pagination=e;var r={limit:t.pageSize,offset:(t.current-1)*t.pageSize};this.fetchAssistant(r)},fetchAssistant:function(t){var e=this;return b()(m.a.mark((function r(){var n,a,i,s;return m.a.wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return e.loading=!0,r.next=3,x.a.search(C({limit:10,nickname:e.keyWord},t));case 3:n=r.sent,a=n.data,i=n.paging,(s=C({},e.pagination)).total=i.total,e.loading=!1,e.pageData=a,e.pagination=s;case 11:case"end":return r.stop()}}),r)})))()},onSearch:function(t){var e=this;return b()(m.a.mark((function r(){return m.a.wrap((function(r){for(;;)switch(r.prev=r.next){case 0:e.keyWord=t,e.pagination.current=1,e.fetchAssistant();case 3:case"end":return r.stop()}}),r)})))()},check:function(t){var e=this;return b()(m.a.mark((function r(){return m.a.wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return r.next=2,x.G.get(t);case 2:e.user=r.sent,e.visible=!0;case 4:case"end":return r.stop()}}),r)})))()},close:function(){this.visible=!1}}},I=r(30),T=Object(I.a)(j,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("aside-layout",{attrs:{breadcrumbs:[{name:"助教管理"}]}},[r("div",{staticClass:"clearfix cd-mb24"},[r("a-input-search",{staticStyle:{width:"224px"},attrs:{placeholder:"请输入用户名搜索",allowClear:!0},on:{search:t.onSearch}})],1),t._v(" "),r("a-table",{attrs:{columns:t.columns,"data-source":t.pageData,rowKey:"id",pagination:t.pagination,loading:t.loading},on:{change:t.handleTableChange},scopedSlots:t._u([{key:"nickname",fn:function(e,n){return[r("div",{staticClass:"avatar-name"},[r("a-avatar",{attrs:{size:48,src:n.avatar.middle,icon:"user"}}),t._v(" "),r("a",{staticClass:"ml8",on:{click:function(e){return t.check(n.id)}}},[t._v(t._s(e))])],1)]}},{key:"isScrmBind",fn:function(e){return[r("span",[t._v(t._s(0===e?"":"已绑定"))])]}},{key:"loginInfo",fn:function(e){return r("div",{},[r("div",[t._v(t._s(t.$dateFormat(e.loginTime,"YYYY-MM-DD HH:mm")))]),t._v(" "),r("div",{staticClass:"color-gray text-sm"},[t._v(t._s(e.loginIp))])])}},{key:"action",fn:function(e){return[r("a-button",{attrs:{type:"link"},on:{click:function(r){return t.check(e.id)}}},[t._v("\n        查看\n      ")]),t._v(" "),r("a-dropdown",[r("a",{staticClass:"ant-dropdown-link",staticStyle:{"margin-left":"-6px"},on:{click:function(t){return t.preventDefault()}}},[r("a-icon",{attrs:{type:"caret-down"}})],1),t._v(" "),r("a-menu",{attrs:{slot:"overlay"},slot:"overlay"},[r("a-menu-item",[r("a",{attrs:{"data-toggle":"modal","data-target":"#modal","data-backdrop":"static","data-keyboard":"false","data-url":"/admin/v2/user/"+e.id+"/edit"}},[t._v("\n              编辑用户信息\n            ")])]),t._v(" "),r("a-menu-item",[r("a",{attrs:{"data-toggle":"modal","data-target":"#modal","data-backdrop":"static","data-keyboard":"false","data-url":"/admin/v2/user/"+e.id+"/avatar"}},[t._v("\n              修改用户头像\n            ")])])],1)],1)]}}])}),t._v(" "),r("a-modal",{attrs:{title:"助教详细信息",visible:t.visible},on:{cancel:t.close}},[r("userInfoTable",{attrs:{user:t.user}}),t._v(" "),r("template",{slot:"footer"},[r("a-button",{key:"back",on:{click:t.close}},[t._v(" 关闭 ")])],1)],2)],1)}),[],!1,null,null,null);e.default=T.exports},1446:function(t,e,r){},1447:function(t,e,r){r(1448),t.exports=r(52).Reflect.deleteProperty},1448:function(t,e,r){var n=r(79),a=r(183).f,i=r(134);n(n.S,"Reflect",{deleteProperty:function(t,e){var r=a(i(t),e);return!(r&&!r.configurable)&&delete t[e]}})},1449:function(t,e,r){"use strict";var n={name:"AsideLayout",props:{breadcrumbs:{type:Array,required:!0},headerTitle:{type:String,default:""},headerTip:{type:String,default:""}}},a=(r(1450),r(30)),i=Object(a.a)(n,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"aside-layout"},[r("div",{staticClass:"aside-layout-header"},[r("a-breadcrumb",{staticClass:"pull-left aside-layout-header__breadcrumb",attrs:{separator:"/"}},t._l(t.breadcrumbs,(function(e,n){return r("a-breadcrumb-item",{key:n},[e.href?[r("a",{attrs:{href:e.href,target:"_blank"}},[t._v(t._s(e.name))])]:e.pathName?[r("a",{attrs:{href:"javascript:;"},on:{click:function(r){return t.$router.push({name:e.pathName})}}},[t._v(t._s(e.name))])]:[t._v("\n          "+t._s(e.name)+"\n        ")]],2)})),1),t._v(" "),t.headerTip?r("a-popover",{attrs:{placement:"bottomLeft"}},[r("template",{slot:"content"},[r("div",{staticClass:"aside-header-tip",domProps:{innerHTML:t._s(t.headerTip)}})]),t._v(" "),r("span",{staticClass:"aside-header-title-icon"},[r("a-icon",{attrs:{theme:"filled",type:"question-circle"}}),r("span",{staticClass:"icon-circle"},[t._v(t._s(t.headerTitle))])],1)],2):t._e()],1),t._v(" "),r("div",{staticClass:"aside-layout-main"},[t._t("default")],2)])}),[],!1,null,null,null);e.a=i.exports},1450:function(t,e,r){"use strict";var n=r(1446);r.n(n).a},1451:function(t,e,r){t.exports=r(1447)},1478:function(t,e,r){"use strict";var n={props:{user:{type:Object,default:{}}},computed:{getUserRoles:function(){return _.join(this.user.user.roles," ")}},methods:{toPersonalHomepage:function(t){window.open("/user/"+t+"/about","_blank")},formatTimeIp:function(t,e){var r="";return r+=0!=t?this.$dateFormat(t,"YYYY-MM-DD HH:mm"):" -- ",""!=e&&(r+=" / "+e+" 本机IP"),r},formatStr:function(t){return void 0===t||""==t||null==t?"暂无":t},formatGender:function(){return{male:"男性",female:"女性",secret:"秘密"}[this.user.profile.gender]}}},a=r(30),i=Object(a.a)(n,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("table",{staticClass:"table table-striped table-condenseda table-bordered"},[r("tbody",[r("tr",[r("th",{attrs:{width:"30%"}},[t._v("用户名")]),t._v(" "),r("td",{attrs:{width:"70%"}},[r("a",{staticClass:"pull-right",attrs:{href:"javascript:;"},on:{click:function(e){return t.toPersonalHomepage(t.user.user.id)}}},[t._v("个人主页")]),t._v("\n        "+t._s(t.user.user.nickname)+"\n      ")])]),t._v(" "),r("tr",[r("th",[t._v("Email")]),t._v(" "),r("td",[t._v(t._s(t.user.profile.email||"- -"))])]),t._v(" "),r("tr",[r("th",[t._v("用户组")]),t._v(" "),r("td",[t._v(t._s(t.getUserRoles))])]),t._v(" "),r("tr",[r("th",[t._v("注册时间/IP")]),t._v(" "),r("td",[t._v(t._s(t.formatTimeIp(t.user.user.createdTime,t.user.user.createdIp)))])]),t._v(" "),r("tr",[r("th",[t._v("最近登录时间/IP")]),t._v(" "),r("td",[t._v(t._s(t.formatTimeIp(t.user.user.loginTime,t.user.user.loginIp)))])]),t._v(" "),r("tr",[r("th",[t._v("姓名")]),t._v(" "),r("td",[t._v(t._s(t.formatStr(t.user.profile.truename)))])]),t._v(" "),r("tr",[r("th",[t._v("性别")]),t._v(" "),r("td",[t._v(t._s(t.formatGender()))])]),t._v(" "),r("tr",[r("th",[t._v("身份证号")]),t._v(" "),r("td",[t._v(t._s(t.formatStr(t.user.profile.idcard)))])]),t._v(" "),r("tr",[r("th",[t._v("手机号码")]),t._v(" "),r("td",[t._v(t._s(t.formatStr(t.user.profile.mobile)))])]),t._v(" "),r("tr",[r("th",[t._v("公司")]),t._v(" "),r("td",[t._v(t._s(t.formatStr(t.user.profile.company)))])]),t._v(" "),r("tr",[r("th",[t._v("职业")]),t._v(" "),r("td",[t._v(t._s(t.formatStr(t.user.profile.job)))])]),t._v(" "),r("tr",[r("th",[t._v("头衔")]),t._v(" "),r("td",[t._v(t._s(t.formatStr(t.user.user.title)))])]),t._v(" "),r("tr",[r("th",[t._v("个人签名")]),t._v(" "),r("td",[t._v(t._s(t.formatStr(t.user.profile.signature)))])]),t._v(" "),r("tr",[r("th",[t._v("自我介绍")]),t._v(" "),r("td",{domProps:{innerHTML:t._s(t.user.profile.about||"暂无")}})]),t._v(" "),r("tr",[r("th",[t._v("个人网站")]),t._v(" "),r("td",[t._v(t._s(t.formatStr(t.user.profile.site)))])]),t._v(" "),r("tr",[r("th",[t._v("微博")]),t._v(" "),r("td",[t._v(t._s(t.formatStr(t.user.profile.weibo)))])]),t._v(" "),r("tr",[r("th",[t._v("微信")]),t._v(" "),r("td",[t._v(t._s(t.formatStr(t.user.profile.weixin)))])]),t._v(" "),r("tr",[r("th",[t._v("QQ")]),t._v(" "),r("td",[t._v(t._s(t.formatStr(t.user.profile.qq)))])])])])}),[],!1,null,null,null);e.a=i.exports},2:function(t,e){t.exports=function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}},214:function(t,e,r){r(215);var n=r(52).Object;t.exports=function(t,e){return n.getOwnPropertyDescriptor(t,e)}},215:function(t,e,r){var n=r(135),a=r(183).f;r(341)("getOwnPropertyDescriptor",(function(){return function(t,e){return a(n(t),e)}}))},3:function(t,e,r){var n=r(130);function a(t,e){for(var r=0;r<e.length;r++){var a=e[r];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),n(t,a.key,a)}}t.exports=function(t,e,r){return e&&a(t.prototype,e),r&&a(t,r),t}},341:function(t,e,r){var n=r(79),a=r(52),i=r(311);t.exports=function(t,e){var r=(a.Object||{})[t]||Object[t],s={};s[t]=e(r),n(n.S+n.F*i((function(){r(1)})),"Object",s)}},342:function(t,e,r){r(343);var n=r(52).Object;t.exports=function(t,e){return n.defineProperties(t,e)}},343:function(t,e,r){var n=r(79);n(n.S+n.F*!r(141),"Object",{defineProperties:r(393)})},344:function(t,e,r){r(345),t.exports=r(52).Object.getOwnPropertyDescriptors},345:function(t,e,r){var n=r(79),a=r(346),i=r(135),s=r(183),o=r(395);n(n.S,"Object",{getOwnPropertyDescriptors:function(t){for(var e,r,n=i(t),c=s.f,u=a(n),l={},d=0;u.length>d;)void 0!==(r=c(n,e=u[d++]))&&o(l,e,r);return l}})},346:function(t,e,r){var n=r(368),a=r(348),i=r(134),s=r(102).Reflect;t.exports=s&&s.ownKeys||function(t){var e=n.f(i(t)),r=a.f;return r?e.concat(r(t)):e}},347:function(t,e,r){r(394),t.exports=r(52).Object.getOwnPropertySymbols},37:function(t,e,r){var n=r(130);t.exports=function(t,e,r){return e in t?n(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}},44:function(t,e,r){t.exports=r(624)},624:function(t,e,r){r(625),t.exports=r(52).Object.keys},625:function(t,e,r){var n=r(367),a=r(366);r(341)("keys",(function(){return function(t){return a(n(t))}}))},70:function(t,e,r){t.exports=r(214)},80:function(t,e,r){t.exports=r(344)},81:function(t,e,r){t.exports=r(347)}}]);