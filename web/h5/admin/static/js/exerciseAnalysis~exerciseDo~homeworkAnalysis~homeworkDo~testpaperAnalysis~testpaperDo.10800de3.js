(window.webpackJsonp=window.webpackJsonp||[]).push([["exerciseAnalysis~exerciseDo~homeworkAnalysis~homeworkDo~testpaperAnalysis~testpaperDo"],{"1c4c":function(t,e,s){"use strict";var i=s("9b43"),n=s("5ca1"),a=s("4bf8"),r=s("1fa8"),o=s("33a4"),c=s("9def"),l=s("f1ae"),u=s("27ee");n(n.S+n.F*!s("5cc5")((function(t){Array.from(t)})),"Array",{from:function(t){var e,s,n,d,h=a(t),p="function"==typeof this?this:Array,f=arguments.length,m=f>1?arguments[1]:void 0,v=void 0!==m,_=0,y=u(h);if(v&&(m=i(m,f>2?arguments[2]:void 0,2)),void 0==y||p==Array&&o(y))for(s=new p(e=c(h.length));e>_;_++)l(s,_,v?m(h[_],_):h[_]);else for(d=y.call(h),s=new p;!(n=d.next()).done;_++)l(s,_,v?r(d,m,[n.value,_],!0):n.value);return s.length=_,s}})},"36bd":function(t,e,s){"use strict";var i=s("4bf8"),n=s("77f1"),a=s("9def");t.exports=function(t){for(var e=i(this),s=a(e.length),r=arguments.length,o=n(r>1?arguments[1]:void 0,s),c=r>2?arguments[2]:void 0,l=void 0===c?s:n(c,s);l>o;)e[o++]=t;return e}},"4f7f":function(t,e,s){"use strict";var i=s("c26b"),n=s("b39a");t.exports=s("e0b8")("Set",(function(t){return function(){return t(this,arguments.length>0?arguments[0]:void 0)}}),{add:function(t){return i.def(n(this,"Set"),t=0===t?0:t,t)}},i)},"67ab":function(t,e,s){var i=s("ca5a")("meta"),n=s("d3f4"),a=s("69a8"),r=s("86cc").f,o=0,c=Object.isExtensible||function(){return!0},l=!s("79e5")((function(){return c(Object.preventExtensions({}))})),u=function(t){r(t,i,{value:{i:"O"+ ++o,w:{}}})},d=t.exports={KEY:i,NEED:!1,fastKey:function(t,e){if(!n(t))return"symbol"==typeof t?t:("string"==typeof t?"S":"P")+t;if(!a(t,i)){if(!c(t))return"F";if(!e)return"E";u(t)}return t[i].i},getWeak:function(t,e){if(!a(t,i)){if(!c(t))return!0;if(!e)return!1;u(t)}return t[i].w},onFreeze:function(t){return l&&d.NEED&&c(t)&&!a(t,i)&&u(t),t}}},"6c7b":function(t,e,s){var i=s("5ca1");i(i.P,"Array",{fill:s("36bd")}),s("9c6c")("fill")},"7c97":function(t,e,s){"use strict";var i={props:{type:{type:String,default:""},isShow:{type:Boolean,default:!1},reportType:{type:String,default:""}},methods:{onKeepLearning:function(t){this.$emit("outFocusMask",t)}}},n=s("0c7c"),a=Object(n.a)(i,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return t.isShow?s("div",{staticClass:"out-focus-mask"},[s("div",{staticClass:"report-go-back",on:{click:function(e){return t.$router.go(-1)}}},[s("van-icon",{attrs:{name:"arrow-left",size:"25",color:"#43bc60"}})],1),"kick_previous"===t.type?[s("div",{staticClass:"content"},[t._m(0),s("van-button",{attrs:{color:"#43bc60",size:"small"},on:{click:function(e){return t.onKeepLearning("kick_previous")}}},[t._v("\n        继续学习\n      ")])],1)]:t._e(),"reject_current"===t.type?[t._m(1)]:t._e()],2):t._e()}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"tips"},[e("p",{staticClass:"kick-each-other"},[this._v("请勿同时多开任务学习")]),e("p",{staticClass:"kick-each-other"},[this._v("不要一心多用哦！")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"content"},[e("div",{staticClass:"tips"},[e("p",{staticClass:"kick-each-other"},[this._v("请勿同时多开任务学习")]),e("p",{staticClass:"kick-each-other"},[this._v("不要一心多用哦！")])])])}],!1,null,null,null);e.a=a.exports},"8ae7":function(t,e,s){"use strict";s("a481");var i=s("75fc");s("4f7f"),s("5df3"),s("1c4c"),s("ac6a"),s("6c7b"),s("c5f6"),e.a={methods:{sixType:function(t,e,s){if("essay"!==t&&"fill"!==t){var i=s?s[e.id]:[];return{item:e,answer:i=e.testResult&&e.testResult.answer?e.testResult.answer.map((function(t){return Number(t)})):i}}if("essay"===t){var n=s?s[e.id]:[""];return{item:e,answer:n=e.testResult&&e.testResult.answer?e.testResult.answer:n}}if("fill"===t){var a=this.fillReplce(e.stem,0),r=a.stem,o=a.index;e.stem=r,e.fillnum=o;var c=s?s[e.id]:new Array(o).fill("");return{item:e,answer:c=e.testResult?e.testResult.answer:c}}return""},analysisSixType:function(t,e){var s=[];if("fill"!==t&&"essay"!==t&&(e.answer.forEach((function(t,s){e.answer[s]=Number(t)})),s=e.answer,e.testResult&&(e.testResult.answer.forEach((function(t,s){e.testResult.answer[s]=Number(t)})),s=Array.from(new Set([].concat(Object(i.a)(e.answer),Object(i.a)(e.testResult.answer)))))),"essay"===t&&(s=e.testResult?e.testResult.answer:[]),"fill"===t){var n=this.fillReplce(e.stem,0),a=n.stem,r=n.index;e.stem=a,e.fillnum=r,s=e.testResult?e.testResult.answer:new Array(r).fill("")}return{item:e,answer:s}},fillReplce:function(t){for(var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:0,s=/\[\[.+?\]\]/;null!==s.exec(t);)t=t.replace(s,(function(){return'<span class="fill-bank">('.concat(e+=1,"）</span>")}));return{stem:t,index:e}}}}},b39a:function(t,e,s){var i=s("d3f4");t.exports=function(t,e){if(!i(t)||t._t!==e)throw TypeError("Incompatible receiver, "+e+" required!");return t}},c26b:function(t,e,s){"use strict";var i=s("86cc").f,n=s("2aeb"),a=s("dcbc"),r=s("9b43"),o=s("f605"),c=s("4a59"),l=s("01f9"),u=s("d53b"),d=s("7a56"),h=s("9e1e"),p=s("67ab").fastKey,f=s("b39a"),m=h?"_s":"size",v=function(t,e){var s,i=p(e);if("F"!==i)return t._i[i];for(s=t._f;s;s=s.n)if(s.k==e)return s};t.exports={getConstructor:function(t,e,s,l){var u=t((function(t,i){o(t,u,e,"_i"),t._t=e,t._i=n(null),t._f=void 0,t._l=void 0,t[m]=0,void 0!=i&&c(i,s,t[l],t)}));return a(u.prototype,{clear:function(){for(var t=f(this,e),s=t._i,i=t._f;i;i=i.n)i.r=!0,i.p&&(i.p=i.p.n=void 0),delete s[i.i];t._f=t._l=void 0,t[m]=0},delete:function(t){var s=f(this,e),i=v(s,t);if(i){var n=i.n,a=i.p;delete s._i[i.i],i.r=!0,a&&(a.n=n),n&&(n.p=a),s._f==i&&(s._f=n),s._l==i&&(s._l=a),s[m]--}return!!i},forEach:function(t){f(this,e);for(var s,i=r(t,arguments.length>1?arguments[1]:void 0,3);s=s?s.n:this._f;)for(i(s.v,s.k,this);s&&s.r;)s=s.p},has:function(t){return!!v(f(this,e),t)}}),h&&i(u.prototype,"size",{get:function(){return f(this,e)[m]}}),u},def:function(t,e,s){var i,n,a=v(t,e);return a?a.v=s:(t._l=a={i:n=p(e,!0),k:e,v:s,p:i=t._l,n:void 0,r:!1},t._f||(t._f=a),i&&(i.n=a),t[m]++,"F"!==n&&(t._i[n]=a)),t},getEntry:v,setStrong:function(t,e,s){l(t,e,(function(t,s){this._t=f(t,e),this._k=s,this._l=void 0}),(function(){for(var t=this._k,e=this._l;e&&e.r;)e=e.p;return this._t&&(this._l=e=e?e.n:this._t._f)?u(0,"keys"==t?e.k:"values"==t?e.v:[e.k,e.v]):(this._t=void 0,u(1))}),s?"entries":"values",!s,!0),d(e)}}},c8a5:function(t,e,s){"use strict";s("6762"),s("2fdb");var i=s("3ce7"),n=s("faa5");e.a={data:function(){return{reportIntervalTime:null,reportLearnTime:null,reportFinishCondition:null,reportData:{courseId:null,taskId:null},reportResult:null,isFinish:!1,reportType:null,learnTime:0,isShowOutFocusMask:!1,outFocusMaskType:"",sign:"",record:{},absorbed:0,learnedTime:0}},beforeRouteLeave:function(t,e,s){this.sign.length>0&&localStorage.setItem("flowSign",this.sign),s()},beforeDestroy:function(){this.clearReportIntervalTime(),window.removeEventListener("beforeunload",this.onbeforeunloadFlowSign),this.toggleReportMaskHidden("remove")},methods:{initReportData:function(t,e,s){var i=!(arguments.length>3&&void 0!==arguments[3])||arguments[3];this.clearReportIntervalTime(),this.reportData={courseId:t,taskId:e},this.reportType=s,this.isFinish=!1,this.reportIntervalTime=null,this.reportLearnTime=null,this.reportResult=null,this.learnTime=0,this.reportFinishCondition=null,i&&this.initReportEvent(),this.onbeforeunload()},initReportEvent:function(){this.reprtData(),this.intervalReportData(),this.intervalReportLearnTime()},getCourseData:function(t,e){var s=this,n={courseId:t,taskId:e};return new Promise((function(t,e){i.a.getCourseData({query:n}).then((function(e){s.reportFinishCondition=e.activity.finishCondition,t(e)})).catch((function(t){e(t)}))}))},reprtData:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{eventName:"doing",ContinuousReport:!1,watchTime:null};if(null!==this.reportData.courseId&&null!==this.reportData.taskId&&(!this.isFinish||t.ContinuousReport))if(""===this.sign){var e={client:"h5"},s=localStorage.getItem("flowSign");s&&(e.lastSign=s,localStorage.removeItem("flowSign")),this.start(t,e)}else this.reportTaskEvent(t)},start:function(t,e){var s=this;i.a.reportTaskEvent({query:{courseId:this.reportData.courseId,taskId:this.reportData.taskId,eventName:"start"},data:e}).then((function(e){if(s.handleReportResult(e),e.learnControl.allowLearn)s.sign=e.record.flowSign,s.record=e.record,s.reportTaskEvent(t);else{var i=e.learnControl.denyReason;s.reportJudge(i)}}))},reportTaskEvent:function(t){var e=this;if(0!==this.sign.length){var s={client:"h5",sign:this.sign,duration:this.learnTime,status:this.absorbed};if(t.reActive&&(s.reActive=t.reActive),this.sourceType&&"video"===this.sourceType){var n=parseInt(this.nowWatchTime-this.lastWatchTime);this.lastWatchTime=this.nowWatchTime;var a={watchData:{duration:n}};s=Object.assign(s,a)}this.learnTime=0,i.a.reportTaskEvent({query:{courseId:this.reportData.courseId,taskId:this.reportData.taskId,eventName:t.eventName},data:s}).then((function(t){if(e.handleReportResult(t),e.record=t.record,e.absorbed=0,e.sign=t.record.flowSign,!t.learnControl.allowLearn){var s=t.learnControl.denyReason;e.reportJudge(s)}})).catch((function(t){e.clearReportIntervalTime()}))}},handleReportResult:function(t){this.reportResult=t,this.learnedTime=t.learnedTime,t.taskResult&&"finish"===t.taskResult.status?(this.isFinish=!0,this.$store.commit(n.z,"finish"),this.$store.commit("course/".concat(n.D),t.completionRate)):this.$store.commit(n.z,"start")},intervalReportLearnTime:function(){var t=this;this.reportLearnTime=setInterval((function(){t.checkoutTime(),t.learnTime++}),1e3)},intervalReportData:function(){var t=this,e=60*(arguments.length>0&&void 0!==arguments[0]?arguments[0]:1)*1e3;this.reportIntervalTime=setInterval((function(){t.reprtData({eventName:"doing",ContinuousReport:!0})}),e)},checkoutTime:function(){this.reportFinishCondition&&["time","watchTime"].includes(this.reportFinishCondition.type)&&parseInt(this.learnTime/60,10)>=parseInt(this.reportFinishCondition.data,10)&&this.reprtData({eventName:"finish",ContinuousReport:!0})},clearReportIntervalTime:function(){clearInterval(this.reportIntervalTime),clearInterval(this.reportLearnTime),this.reportIntervalTime=null,this.reportLearnTime=null},reportJudge:function(t){"kick_previous"===t?this.kickEachOther("kick_previous"):"reject_current"===t&&(this.clearReportIntervalTime(),this.kickEachOther("reject_current"))},outFocusMask:function(t){this.absorbed=1,this.isShowOutFocusMask=!1,!this.player||"video"!==this.reportType&&"audio"!==this.reportType||this.player.play(),this.toggleReportMaskHidden("remove"),this.reprtData({eventName:"doing",ContinuousReport:!0,reActive:1})},kickEachOther:function(t){if(this.absorbed=1,"testpaper"!==this.reportType&&"live"!==this.reportType&&"homework"!==this.reportType||"kick_previous"!==t){if(this.isShowOutFocusMask=!0,this.outFocusMaskType=t,"video"===this.reportType||"audio"===this.reportType){if(this.player&&this.player.destory&&"reject_current"===t)return void this.player.destory();this.player&&this.player.pause&&this.player.pause()}this.toggleReportMaskHidden("add")}},ineffectiveLearning:function(t){this.isShowOutFocusMask||(this.absorbed=0,this.isShowOutFocusMask=!0,this.outFocusMaskType=t,this.player&&"video"===this.reportType&&this.player.pause(),this.reprtData({eventName:"doing",ContinuousReport:!0}))},toggleReportMaskHidden:function(t){"video"!==this.reportType&&"audio"!==this.reportType&&("add"===t?document.getElementsByTagName("body")[0].classList.add("report-mask-hidden"):"remove"===t&&document.getElementsByTagName("body")[0].classList.remove("report-mask-hidden"))},initVisibilitychange:function(){document.addEventListener("visibilitychange",this.visibilityState)},visibilityState:function(){"video"===this.reportType&&("hidden"===document.visibilityState?this.ineffectiveLearning("ineffective_learning"):"visible"===document.visibilityState&&this.player.pause())},onbeforeunload:function(){window.addEventListener("beforeunload",this.onbeforeunloadFlowSign)},onbeforeunloadFlowSign:function(){this.sign.length>0&&localStorage.setItem("flowSign",this.sign)}}}},e0b8:function(t,e,s){"use strict";var i=s("7726"),n=s("5ca1"),a=s("2aba"),r=s("dcbc"),o=s("67ab"),c=s("4a59"),l=s("f605"),u=s("d3f4"),d=s("79e5"),h=s("5cc5"),p=s("7f20"),f=s("5dbc");t.exports=function(t,e,s,m,v,_){var y=i[t],w=y,b=v?"set":"add",g=w&&w.prototype,C={},T=function(t){var e=g[t];a(g,t,"delete"==t||"has"==t?function(t){return!(_&&!u(t))&&e.call(this,0===t?0:t)}:"get"==t?function(t){return _&&!u(t)?void 0:e.call(this,0===t?0:t)}:"add"==t?function(t){return e.call(this,0===t?0:t),this}:function(t,s){return e.call(this,0===t?0:t,s),this})};if("function"==typeof w&&(_||g.forEach&&!d((function(){(new w).entries().next()})))){var k=new w,x=k[b](_?{}:-0,1)!=k,R=d((function(){k.has(1)})),j=h((function(t){new w(t)})),L=!_&&d((function(){for(var t=new w,e=5;e--;)t[b](e,e);return!t.has(-0)}));j||((w=e((function(e,s){l(e,w,t);var i=f(new y,e,w);return void 0!=s&&c(s,v,i[b],i),i}))).prototype=g,g.constructor=w),(R||L)&&(T("delete"),T("has"),v&&T("get")),(L||x)&&T(b),_&&g.clear&&delete g.clear}else w=m.getConstructor(e,t,v,b),r(w.prototype,s),o.NEED=!0;return p(w,t),C[t]=w,n(n.G+n.W+n.F*(w!=y),C),_||m.setStrong(w,t,v),w}},f5cc:function(t,e,s){"use strict";s("c5f6");var i,n=s("bd86"),a=(i={name:"FillType",props:{filldata:{type:Object,default:function(){}}}},Object(n.a)(i,"props",{itemdata:{type:Object,default:function(){}},answer:{type:Array,default:function(){return[]}},number:{type:Number,default:1},canDo:{type:Boolean,default:!0}}),Object(n.a)(i,"data",(function(){return{index:0}})),Object(n.a)(i,"computed",{stem:{get:function(){return this.itemdata.parentTitle?this.itemdata.parentTitle.stem:this.itemdata.stem}},placeholder:{get:function(){return this.canDo?"请填写答案":"未作答"}}}),Object(n.a)(i,"methods",{}),i),r=s("0c7c"),o=Object(r.a)(a,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"fill"},[s("div",{staticClass:"subject-stem"},[s("div",{staticClass:"serial-number"},[t._v(t._s(t.itemdata.seq)+"、")]),s("div",{staticClass:"rich-text",domProps:{innerHTML:t._s(t.stem)}})]),t.itemdata.parentTitle?s("div",{staticClass:"material-title"},[s("span",{staticClass:"serial-number"},[t._v("问题"+t._s(t.itemdata.materialIndex)+"：")]),s("div",{staticClass:"rich-text",domProps:{innerHTML:t._s(t.itemdata.stem)}})]):t._e(),s("div",{staticClass:"answer-paper"},t._l(t.itemdata.fillnum,(function(e,i){return s("div",{key:i},[s("div",{staticClass:"fill-subject"},[t._v("填空题（"+t._s(i+1)+"）")]),s("van-field",{staticClass:"fill-input",attrs:{placeholder:t.placeholder,disabled:!t.canDo,"label-width":"0px",type:"textarea",rows:"1",autosize:""},model:{value:t.answer[i],callback:function(e){t.$set(t.answer,i,e)},expression:"answer[index]"}})],1)})),0)])}),[],!1,null,null,null).exports,c={name:"EssayType",props:{itemdata:{type:Object,default:function(){}},answer:{type:Array,default:function(){return[]}},number:{type:Number,default:1},canDo:{type:Boolean,default:!0}},computed:{stem:{get:function(){return this.itemdata.parentTitle?this.itemdata.parentTitle.stem:this.itemdata.stem}},placeholder:{get:function(){return this.canDo?"请填写你的答案......":"未作答"}}},methods:{change:function(){}}},l=Object(r.a)(c,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"essay"},[s("div",{staticClass:"subject-stem"},[s("div",{staticClass:"serial-number"},[t._v(t._s(t.itemdata.seq)+"、")]),s("div",{staticClass:"rich-text",domProps:{innerHTML:t._s(t.stem)}})]),t.itemdata.parentTitle?s("div",{staticClass:"material-title"},[s("span",{staticClass:"serial-number"},[t._v("问题"+t._s(t.itemdata.materialIndex)+"：")]),s("div",{staticClass:"rich-text",domProps:{innerHTML:t._s(t.itemdata.stem)}})]):t._e(),s("div",{staticClass:"answer-paper"},[s("van-field",{staticClass:"essay-input",attrs:{placeholder:t.placeholder,autosize:{maxHeight:200,minHeight:200},disabled:!t.canDo,"label-width":"0px",type:"textarea"},on:{input:function(e){return t.change()}},model:{value:t.answer[0],callback:function(e){t.$set(t.answer,0,e)},expression:"answer[0]"}})],1)])}),[],!1,null,null,null).exports,u={name:"HeadTop",props:{all:{type:Number,default:0},current:{type:Number,default:0},subject:{type:String,default:""},score:{type:String,default:"0"},showScore:{type:Boolean,default:!0}}},d=Object(r.a)(u,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"head-top"},[s("div",{staticClass:"head-left"},[t._v("\n    "+t._s(t.subject)+"\n    "),s("span",{directives:[{name:"show",rawName:"v-show",value:t.showScore,expression:"showScore"}],staticClass:"left-color"},[t._v("["+t._s(t.score)+"分]")])]),s("div",{staticClass:"head-right"},[s("span",{staticClass:"right-color"},[t._v(t._s(t.current))]),t._v("/"+t._s(t.all)+"\n  ")])])}),[],!1,null,null,null).exports,h=(s("6762"),s("2fdb"),{methods:{checkAnswer:function(t,e){var s=e.answer;if(e.testResult&&e.testResult.answer){var i=e.testResult.answer||[];if(s.includes(t))return"subject-option__order_right";if(s.includes(t)&&!i.includes(t)||!s.includes(t)&&i.includes(t))return"subject-option__order_wrong"}return""}}}),p={name:"ChoiceType",filters:{filterOrder:function(t){return["A","B","C","D","E","F","G","H","I","J"][t]}},mixins:[h],props:{itemdata:{type:Object,default:function(){}},number:{type:Number,default:1},answer:{type:Array,default:function(){return[]}},canDo:{type:Boolean,default:!0}},data:function(){return{result:this.answer}},computed:{stem:{get:function(){return this.itemdata.parentTitle?this.itemdata.parentTitle.stem:this.itemdata.stem}}},methods:{choose:function(t){this.$emit("choiceChoose",this.result,this.itemdata.id)}}},f=Object(r.a)(p,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"subject"},[s("div",{staticClass:"subject-stem"},[s("span",{staticClass:"serial-number"},[t._v(t._s(t.itemdata.seq)+"、")]),s("div",{staticClass:"subject-stem__content rich-text",domProps:{innerHTML:t._s(t.stem)}})]),t.itemdata.parentTitle?s("div",{staticClass:"material-title"},[s("span",{staticClass:"serial-number"},[t._v("问题"+t._s(t.itemdata.materialIndex)+"：")]),s("div",{staticClass:"rich-text",domProps:{innerHTML:t._s(t.itemdata.stem)}})]):t._e(),s("van-checkbox-group",{staticClass:"answer-paper",on:{change:function(e){return t.choose()}},model:{value:t.result,callback:function(e){t.result=e},expression:"result"}},t._l(t.itemdata.metas.choices,(function(e,i){return s("van-checkbox",{key:i,staticClass:"subject-option",attrs:{name:i,disabled:!t.canDo},scopedSlots:t._u([{key:"icon",fn:function(e){return s("span",{class:["subject-option__order","subject-option__order--square",t.canDo?"":t.checkAnswer(i,t.itemdata)]},[t._v(t._s(t._f("filterOrder")(i)))])}}],null,!0)},[s("div",{staticClass:"subject-option__content",domProps:{innerHTML:t._s(e)}})])})),1)],1)}),[],!1,null,null,null).exports,m={name:"SingleChoice",filters:{filterOrder:function(t){return["A","B","C","D","E","F","G","H","I","J"][t]}},mixins:[h],props:{itemdata:{type:Object,default:function(){}},answer:{type:Array,default:function(){return[]}},number:{type:Number,default:1},canDo:{type:Boolean,default:!0}},data:function(){return{radio:this.answer[0]}},computed:{stem:{get:function(){return this.itemdata.parentTitle?this.itemdata.parentTitle.stem:this.itemdata.stem}}},methods:{choose:function(){this.$emit("singleChoose",this.radio,this.itemdata.id)}}},v=Object(r.a)(m,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"subject"},[s("div",{staticClass:"subject-stem"},[s("span",{staticClass:"serial-number"},[t._v(t._s(t.itemdata.seq)+"、")]),s("div",{staticClass:"rich-text",domProps:{innerHTML:t._s(t.stem)}})]),t.itemdata.parentTitle?s("div",{staticClass:"material-title"},[s("span",{staticClass:"serial-number"},[t._v("问题"+t._s(t.itemdata.materialIndex)+"：")]),s("div",{staticClass:"rich-text",domProps:{innerHTML:t._s(t.itemdata.stem)}})]):t._e(),s("van-radio-group",{staticClass:"answer-paper",on:{change:function(e){return t.choose()}},model:{value:t.radio,callback:function(e){t.radio=e},expression:"radio"}},t._l(t.itemdata.metas.choices,(function(e,i){return s("van-radio",{key:i,staticClass:"subject-option",attrs:{name:i,disabled:!t.canDo},scopedSlots:t._u([{key:"icon",fn:function(e){return s("span",{class:["subject-option__order",t.canDo?"":t.checkAnswer(i,t.itemdata)]},[t._v(t._s(t._f("filterOrder")(i)))])}}],null,!0)},[s("div",{staticClass:"subject-option__content",domProps:{innerHTML:t._s(e)}})])})),1)],1)}),[],!1,null,null,null).exports,_={name:"DetermineType",mixins:[h],props:{itemdata:{type:Object,default:function(){}},number:{type:Number,default:1},answer:{type:Array,default:function(){return[]}},canDo:{type:Boolean,default:!0}},data:function(){return{radio:this.answer[0]}},computed:{stem:{get:function(){return this.itemdata.parentTitle?this.itemdata.parentTitle.stem:this.itemdata.stem}}},methods:{choose:function(){this.$emit("determineChoose",this.radio,this.itemdata.id)}}},y=Object(r.a)(_,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"subject"},[s("div",{staticClass:"subject-stem"},[s("span",{staticClass:"serial-number"},[t._v(t._s(t.itemdata.seq)+"、")]),s("div",{staticClass:"subject-stem__content rich-text",domProps:{innerHTML:t._s(t.stem)}})]),t.itemdata.parentTitle?s("div",{staticClass:"material-title"},[s("span",{staticClass:"serial-number"},[t._v("问题"+t._s(t.itemdata.materialIndex)+"：")]),s("div",{staticClass:"rich-text",domProps:{innerHTML:t._s(t.itemdata.stem)}})]):t._e(),s("van-radio-group",{staticClass:"answer-paper",on:{change:function(e){return t.choose()}},model:{value:t.radio,callback:function(e){t.radio=e},expression:"radio"}},[s("van-radio",{staticClass:"subject-option subject-option--determine",attrs:{name:1,disabled:!t.canDo},scopedSlots:t._u([{key:"icon",fn:function(e){return s("i",{class:["iconfont","icon-yes","subject-option__order",t.canDo?"":t.checkAnswer(1,t.itemdata)]})}}])},[s("div",{staticClass:"subject-option__content"},[t._v("对")])]),s("van-radio",{staticClass:"subject-option subject-option--determine",attrs:{name:0,disabled:!t.canDo},scopedSlots:t._u([{key:"icon",fn:function(e){return s("i",{class:["iconfont","icon-no","subject-option__order",t.canDo?"":t.checkAnswer(0,t.itemdata)]})}}])},[s("div",{staticClass:"subject-option__content"},[t._v("错")])])],1)],1)}),[],!1,null,null,null).exports,w=(s("6b54"),{name:"Analysis",props:{testResult:{type:Object,default:function(){}},answer:{type:Array,default:function(){return[]}},analysis:{type:String,default:""},subject:{type:String,default:""},isExercise:{type:Boolean,default:!1},resultShow:{type:Boolean,default:!0}},computed:{statusColor:function(){if(!this.testResult)return"analysis-item_noAnswer";switch(this.testResult.status){case"right":return"analysis-item_right";case"none":return this.isExercise?"analysis-item_subject":"analysis-item_none";case"wrong":case"partRight":return"analysis-item_worng";case"noAnswer":return"analysis-item_noAnswer"}return""}},methods:{status:function(t){if(!t)return this.$t("courseLearning.unanswered");var e=t.status;switch(e){case"right":return this.$t("courseLearning.correctAnswer2");case"none":return this.isExercise?"主观题":this.$t("courseLearning.toBeReviewed");case"wrong":case"partRight":return this.$t("courseLearning.wrongAnswer");case"noAnswer":return this.$t("courseLearning.unanswered")}},filterOrder:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:[],e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"do";if("fill"==this.subject)return"standard"==e?t.length>0?t.toString():"无":t.length>0?t.toString():this.$t("courseLearning.unanswered");var s=["A","B","C","D","E","F","G","H","I","J"];"determine"==this.subject&&(s=["错","对"]);var i=null;return i=t.map((function(t){return s[t]})),"standard"==e?i.length>0?i.join(" "):"无":i.length>0?i.join(" "):this.$t("courseLearning.unanswered")}}}),b=Object(r.a)(w,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"analysis"},[s("div",{staticClass:"mt10 analysis-result"},[s("div",{staticClass:"analysis-title"},[t._v(t._s(t.$t("courseLearning.answerResult")))]),s("div",{staticClass:"analysis-content"},[s("div",{staticClass:"analysis-content__item  mt10"},[s("div",{staticClass:"analysis-item__title"},[t._v(t._s(t.$t("courseLearning.answerResult")))]),s("div",{class:[t.statusColor]},[t._v(t._s(t.status(t.testResult)))])]),"fill"===t.subject?s("div",[t.resultShow?s("div",{staticClass:"analysis-content__item  mt10"},[s("div",{staticClass:"analysis-item__title"},[t._v(t._s(t.$t("courseLearning.correctAnswer")))]),s("div",{staticClass:"analysis-item_right analysis-content__item--column"},t._l(t.answer,(function(e,i){return s("div",{key:"right"+i,staticClass:"fill-answer"},[t._v("\n              （"+t._s(i+1)+"）"+t._s(t.filterOrder(e,"standard"))+"\n            ")])})),0)]):t._e(),s("div",{staticClass:"analysis-content__item "},[s("div",{staticClass:"analysis-item__title"},[t._v(t._s(t.$t("courseLearning.yourAnswer")))]),s("div",{staticClass:"analysis-content__item--column"},t._l(t.answer,(function(e,i){return s("div",{key:i},[t.testResult?s("div",{class:[t.statusColor,"fill-answer"]},[t._v("\n                （"+t._s(i+1)+"）"+t._s(t.filterOrder(t.testResult.answer[i]))+"\n              ")]):s("div",{staticClass:"analysis-item_noAnswer"},[t._v("\n                "+t._s(t.$t("courseLearning.unanswered"))+"\n              ")])])})),0)])]):t._e(),"essay"===t.subject?s("div",[t.resultShow?s("div",{staticClass:"analysis-content__item  mt10"},[s("div",{staticClass:"analysis-item__title"},[t._v(t._s(t.$t("courseLearning.correctAnswer")))]),s("div",{staticClass:"analysis-item_right",domProps:{innerHTML:t._s(t.answer[0])}})]):t._e(),s("div",{staticClass:"analysis-content__item  mt10"},[s("div",{staticClass:"analysis-item__title"},[t._v(t._s(t.$t("courseLearning.yourAnswer")))]),t.testResult&&t.testResult.answer.length>0?s("div",{class:[t.statusColor],domProps:{innerHTML:t._s(t.testResult.answer[0])}}):s("div",{staticClass:"analysis-item_noAnswer"},[t._v(t._s(t.$t("courseLearning.unanswered")))])])]):t._e(),"fill"!==t.subject&&"essay"!==t.subject?s("div",[t.resultShow?s("div",{staticClass:"analysis-content__item  mt10"},[s("div",{staticClass:"analysis-item__title"},[t._v(t._s(t.$t("courseLearning.correctAnswer")))]),s("div",{staticClass:"analysis-item_right",domProps:{innerHTML:t._s(t.filterOrder(t.answer,"standard"))}})]):t._e(),s("div",{staticClass:"analysis-content__item  mt10"},[s("div",{staticClass:"analysis-item__title"},[t._v(t._s(t.$t("courseLearning.yourAnswer")))]),t.testResult?s("div",{class:[t.statusColor]},[t._v("\n            "+t._s(t.filterOrder(t.testResult.answer))+"\n          ")]):s("div",{staticClass:"analysis-item_noAnswer"},[t._v(t._s(t.$t("courseLearning.unanswered")))])])]):t._e()])]),t.resultShow?s("div",{staticClass:"mt10 analysis-result"},[s("div",{staticClass:"analysis-title"},[t._v(t._s(t.$t("courseLearning.parsing")))]),t.analysis?s("div",{staticClass:"analysis-content mt10",domProps:{innerHTML:t._s(t.analysis)}}):s("div",{staticClass:"analysis-content mt10"},[t._v(t._s(t.$t("courseLearning.noParsing")))])]):t._e()])}),[],!1,null,null,null).exports,g=document.documentElement.clientHeight-44,C={name:"ItemBank",components:{fillType:o,essayType:l,headTop:d,choiceType:f,singleChoice:v,determineType:y,analysis:b},props:{info:{type:Array,default:function(){return[]}},answer:{type:Object,default:function(){}},current:{type:Number,default:0},showScore:{type:Boolean,default:!0},canDo:{type:Boolean,default:!0},all:{type:Number,default:0},isWrongMode:{type:Boolean,default:!1},isExercise:{type:Boolean,default:!1},resultShow:{type:Boolean,default:!0}},data:function(){return{testData:this.info,testAnswer:this.answer,currentIndex:this.current,height:g}},watch:{answer:function(t){this.$emit("update:answer",t)},isWrongMode:function(t){this.currentIndex=this.current-1,this.$refs.swipe.swipeTo(this.current-1,{immediate:!0})},current:function(t,e){var s=Number(t);s-1!==this.currentIndex&&this.$refs.swipe.swipeTo(s-1)}},methods:{changeswiper:function(t){this.currentIndex=t,this.$emit("update:current",t+1),this.$emit("update:slideIndex",t)},last:function(){0!=this.currentIndex&&this.$refs.swipe.swipeTo(this.currentIndex-1)},next:function(){this.currentIndex!=this.info.length-1&&this.$refs.swipe.swipeTo(this.currentIndex+1)},subject:function(t){var e,s="",i=t.type;switch(t.parentType&&(s="材料题-"),i){case"single_choice":e=this.$t("courseLearning.singleChoice");break;case"choice":e=this.$t("courseLearning.choice");break;case"essay":e=this.$t("courseLearning.essay");break;case"uncertain_choice":e=this.$t("courseLearning.uncertainChoice");break;case"determine":e=this.$t("courseLearning.determine");break;case"fill":e=this.$t("courseLearning.fill");break;case"material":e=this.$t("courseLearning.material");break;default:e=""}return s+e},singleChoose:function(t,e){this.$set(this.testAnswer[e],0,t)},choiceChoose:function(t,e){this.$set(this.testAnswer,e,t)},determineChoose:function(t,e){this.$set(this.testAnswer[e],0,Number(t))}}},T=Object(r.a)(C,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"paper-swiper"},[t.testData.length>0?s("van-swipe",{ref:"swipe",attrs:{height:t.height,"show-indicators":!1,loop:!1,duration:100},on:{change:t.changeswiper}},t._l(t.info,(function(e,i){return s("van-swipe-item",{key:e.id,style:{height:t.height+"px"}},[s("div",{ref:"paper"+i,refInFor:!0,staticClass:"paper-item"},[s("head-top",{attrs:{all:t.all,current:Number(e.seq),subject:t.subject(e),score:""+parseFloat(e.score),"show-score":t.showScore}}),"single_choice"==e.type?s("single-choice",{attrs:{itemdata:e,answer:t.testAnswer[e.id],number:i+1,"can-do":t.canDo},on:{singleChoose:t.singleChoose}}):t._e(),"choice"==e.type||"uncertain_choice"==e.type?s("choice-type",{attrs:{itemdata:e,answer:t.testAnswer[e.id],number:i+1,"can-do":t.canDo},on:{choiceChoose:t.choiceChoose}}):t._e(),"determine"==e.type?s("determine-type",{attrs:{itemdata:e,answer:t.testAnswer[e.id],number:i+1,"can-do":t.canDo},on:{determineChoose:t.determineChoose}}):t._e(),"essay"==e.type?s("essay-type",{attrs:{itemdata:e,answer:t.testAnswer[e.id],"can-do":t.canDo,number:i+1}}):t._e(),"fill"==e.type?s("fill-type",{attrs:{itemdata:e,answer:t.testAnswer[e.id],"can-do":t.canDo,number:i+1}}):t._e(),t.canDo?t._e():s("analysis",{attrs:{"test-result":e.testResult,analysis:e.analysis,answer:e.answer,subject:e.type,"is-exercise":t.isExercise,"result-show":t.resultShow}})],1)])})),1):t._e(),s("div",[s("div",{class:["left-slide__btn",0==t.currentIndex?"slide-disabled":""],on:{click:function(e){return t.last()}}},[s("i",{staticClass:"iconfont icon-arrow-left"})]),s("div",{class:["right-slide__btn",t.currentIndex==t.info.length-1?"slide-disabled":""],on:{click:function(e){return t.next()}}},[s("i",{staticClass:"iconfont icon-arrow-right"})])])],1)}),[],!1,null,null,null);e.a=T.exports}}]);