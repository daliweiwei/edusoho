(window.webpackJsonp=window.webpackJsonp||[]).push([["vip"],{a9a0:function(t,e,i){"use strict";i.r(e),i("8e6e"),i("ac6a"),i("456d"),i("c5f6"),i("7514"),i("a481");var s=i("bd86"),r=i("3ce7"),a=i("2f62"),n=i("faa5"),c=i("7212"),l=(i("a7a3"),{props:{title:String}}),v=i("a6c2"),p=Object(v.a)(l,(function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"vip-sec__title"},[this._m(0),e("span",{staticClass:"vip-sec__text"},[this._v(this._s(this.title))]),this._m(1)])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"vip-sec__style"},[e("span",{staticClass:"style style--first"}),e("span",{staticClass:"style style--second"}),e("span",{staticClass:"style style--third"})])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"vip-sec__style"},[e("span",{staticClass:"style style--first"}),e("span",{staticClass:"style style--second"}),e("span",{staticClass:"style style--third"})])}],!1,null,null,null).exports,o={name:"PriceItem",props:{item:{type:Object,default:function(){return{}}},activePriceId:[Number,String]},computed:{price:function(){var t=this.item.price,e=t.currency,i=t.amount,s=t.coinAmount,r=t.coinName;return"RMB"==e?"".concat(i,"元"):"".concat(s).concat(r)},isActive:function(){return this.item.id==this.activePriceId}},methods:{clickPrice:function(){this.$emit("clickPriceItem",this.item)}}},u=Object(v.a)(o,(function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"vip-price-item",class:{active:t.isActive},on:{click:function(e){return t.clickPrice()}}},["first"===t.item.type?i("span",{staticClass:"vip-price-item__new"},[t._v("\n    "+t._s(t.item.tag)+"\n  ")]):t._e(),i("div",{staticClass:"vip-price-item__title"},[t._v(t._s(t.item.title))]),i("div",{staticClass:"vip-price-item__price"},[t._v(t._s(t.price))])])}),[],!1,null,null,null).exports,d=i("062f");function _(t,e){var i=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);e&&(s=s.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),i.push.apply(i,s)}return i}function m(t){for(var e=1;e<arguments.length;e++){var i=null!=arguments[e]?arguments[e]:{};e%2?_(Object(i),!0).forEach((function(e){Object(s.a)(t,e,i[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(i)):_(Object(i)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(i,e))}))}return t}var h={components:{Swiper:c.Swiper,SwiperSlide:c.SwiperSlide,ModuleTitle:p,PriceItem:u,ECourseList:d.a},data:function(){var t=this;return{swiperOption:{loop:!1,centeredSlides:!0,slidesPerView:1.28,observer:!0,observeParents:!0,on:{slideChange:function(){t.activeIndex=t.swiper.activeIndex,t.getActivePrice()}}},vipOpenSwiperOption:{slidesPerView:3.1},user:{},vipInfo:null,levels:[{courses:{data:[]},classrooms:{data:[]}}],activeIndex:0,activePrice:null,isLoading:!1}},computed:m(m(m({},Object(a.e)(["vipSwitch"])),Object(a.e)({userInfo:function(t){return t.user}})),{},{vipDated:function(){if(!this.vipInfo)return!0;var t=new Date(this.vipInfo.deadline).getTime();return(new Date).getTime()>t},swiper:function(){return this.$refs.mySwiper.$swiper},currentLevel:function(){return this.levels[this.activeIndex]},userLevelStatus:function(){var t=this.vipInfo?this.vipInfo.seq:0,e=this.currentLevel.seq;return 0===t||this.vipDated?"opening":t===e?"renew":t<e?"upgrade":"low"},vipBuyStatu:function(){var t=this.activePrice?this.activePrice.title:"";return{opening:{text:"立即开通".concat(t,"特权"),status:!0,type:"开通"},renew:{text:"续费".concat(t,"特权"),status:!0,type:"续费"},upgrade:{text:"升级为当前会员特权",status:!0,type:"升级"},low:{text:"等级低于已购会员",status:!1,type:"低于"}}[this.userLevelStatus]},courseData:function(){var t=this.currentLevel.courses,e=t.data,i=t.paging;if(0==e.length)return!1;var s={items:[],title:"会员课程(".concat(i.total,")"),source:{},limit:4,vipCenter:!0};return s.items=e.slice(0,3),s},classroomData:function(){var t=this.currentLevel.classrooms,e=t.data,i=t.paging;if(0==e.length)return!1;var s={items:[],title:"会员班级(".concat(i.total,")"),source:{},limit:4,vipCenter:!0};return s.items=e.slice(0,3),s}}),created:function(){this.$store.state.token?this.vipSwitch?(this.isLoading=!0,this.getVipDetail()):this.$router.push({path:"/",query:{redirect:this.$route.fullPath}}):this.$router.replace({name:"login",query:{redirect:this.$route.fullPath}})},methods:{getVipDetail:function(){var t=this,e=this.$route.query.id;r.a.getVipDetail().then((function(i){t.isLoading=!1;var s=i.levels,r=i.vipUser;t.levels=s,t.user=r?r.user:null,t.vipInfo=r.vip;var a=r?r.vip:null,c=t.userInfo;c.vip=a,t.$store.commit(n.D,c);var l=a?a.levelId:s[0].id;l=isNaN(e)?l:e,t.getVipIndex(l,s)}))},getVipIndex:function(t,e){var i=0;e.find((function(e,s){if(e.id===t)return i=s,e})),this.activeIndex=i||0,this.initSwiperActiveIndex(),this.getActivePrice()},vipStatus:function(t){if(!this.vipInfo)return"您还不是会员，开通享特权";var e=Number(this.vipInfo.seq),i=this.vipInfo.deadline,s=Number(t.seq);return this.vipDated?e===s?"会员身份已到期":"您还不是会员，开通享特权":e===s?"会员有效期至：".concat(this.$moment(i).format("YYYY/MM/DD")):e>s?"等级低于已购会员":"您还不是该等级会员请升级"},initSwiperActiveIndex:function(){var t=this;this.$nextTick((function(){t.swiper.slideTo(t.activeIndex,0)}))},getActivePrice:function(){var t=this.levels[this.activeIndex].sellModes;this.activePrice=t.length>0?t[0]:null},clickPriceItem:function(t){this.activePrice=t},clickVipBuy:function(){this.user?this.vipBuyStatu.status&&this.activePrice&&this.$router.replace({name:"order",params:{id:this.activePrice.id,unit:this.activePrice.specUnit,num:this.activePrice.duration,type:this.vipBuyStatu.type},query:{targetType:"vip"}}):this.$router.push({path:"/login",query:{redirect:"/vip"}})}}},f=Object(v.a)(h,(function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"vip-detail"},[t.isLoading?i("e-loading"):t._e(),i("div",{staticClass:"vip-swiper"},[i("swiper",{ref:"mySwiper",staticClass:"swiper",attrs:{options:t.swiperOption}},t._l(t.levels,(function(e,s){return i("swiper-slide",{key:s},[i("img",{staticClass:"vip-swiper__img",attrs:{src:e.background}}),t.user?i("div",{staticClass:"vip-user"},[t.user.avatar?i("div",{staticClass:"vip-user__img"},[i("img",{attrs:{src:t.user.avatar.large}})]):t._e(),i("span",{staticClass:"vip-user__name"},[t._v(t._s(t.user.nickname))])]):t._e(),i("div",{staticClass:"vip-info"},[i("div",{staticClass:"vip-info__detail"},[i("img",{staticClass:"vip-info__icon",attrs:{src:e.icon}}),i("span",{staticClass:"vip-info__name"},[t._v(t._s(e.name))])]),i("div",{staticClass:"vip-info__status"},[t._v(t._s(t.vipStatus(e)))])])])})),1)],1),i("div",{staticClass:"vip-sec"},[i("module-title",{attrs:{title:"upgrade"==t.userLevelStatus?"会员升级":"选择开通时长"}}),i("div",{staticClass:"vip-open"},["upgrade"!=t.userLevelStatus?i("swiper",{attrs:{options:t.vipOpenSwiperOption}},[t._l(t.currentLevel.sellModes,(function(e){return[i("swiper-slide",{key:e.id},[i("price-item",{attrs:{item:e,activePriceId:t.activePrice.id},on:{clickPriceItem:t.clickPriceItem}})],1)]}))],2):i("div",{staticClass:"vip-upgrade"},[i("span",{staticClass:"vip-upgrade__deadline"},[t._v("\n          会员升级期限至："+t._s(t.$moment(t.vipInfo.deadline).format("YYYY/MM/DD"))+"\n        ")])]),i("div",{staticClass:"vip-open__buy",class:{disabled:!t.vipBuyStatu.status},on:{click:t.clickVipBuy}},[t._v("\n        "+t._s(t.vipBuyStatu.text)+"\n      ")])],1)],1),i("div",{staticClass:"vip-sec"},[i("module-title",{attrs:{title:"专属权益"}}),i("div",{staticClass:"vip-interest"},[t.currentLevel.courses.data.length?i("div",{staticClass:"vip-interest__item"},[t._m(0),i("div",{staticClass:"vip-interest__item__title"},[t._v("会员课程")]),i("div",{staticClass:"vip-interest__item__total"},[t._v("\n          "+t._s(t.currentLevel.courses.paging.total)+"\n          "),i("span",{staticClass:"company"},[t._v("个")])])]):t._e(),t.currentLevel.classrooms.data.length?i("div",{staticClass:"vip-interest__item"},[t._m(1),i("div",{staticClass:"vip-interest__item__title"},[t._v("会员班级")]),i("div",{staticClass:"vip-interest__item__total"},[t._v("\n          "+t._s(t.currentLevel.classrooms.paging.total)+"\n          "),i("span",{staticClass:"company"},[t._v("个")])])]):t._e()])],1),i("div",{staticClass:"vip-sec"},[i("module-title",{attrs:{title:"专属介绍"}}),i("div",{staticClass:"vip-introduce",domProps:{innerHTML:t._s(t.currentLevel.description||"暂无介绍")}})],1),i("div",{staticClass:"vip-sec"},[i("module-title",{attrs:{title:"专属特权"}}),i("div",{staticClass:"vip-privilege"},[t.courseData?i("e-course-list",{staticClass:"vip-course-list",attrs:{"course-list":t.courseData,"vip-name":t.currentLevel.name,"more-type":"vip","level-id":Number(t.currentLevel.id),"type-list":"course_list"}}):t._e(),t.classroomData?i("e-course-list",{staticClass:"vip-course-list",attrs:{"more-type":"vip","level-id":Number(t.currentLevel.id),"course-list":t.classroomData,"vip-name":t.currentLevel.name,"type-list":"classroom_list"}}):t._e()],1)],1)],1)}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"vip-interest__item__img"},[e("img",{attrs:{src:"static/images/vip/vip_course.png"}})])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"vip-interest__item__img"},[e("img",{attrs:{src:"static/images/vip/vip_classroom.png"}})])}],!1,null,null,null);e.default=f.exports}}]);