(window.webpackJsonp=window.webpackJsonp||[]).push([["homeworkIntro"],{"7c97":function(t,e,i){"use strict";var o={props:{type:{type:String,default:""},isShow:{type:Boolean,default:!1},reportType:{type:String,default:""}},methods:{onKeepLearning:function(t){this.$emit("outFocusMask",t)}}},r=i("0c7c"),n=Object(r.a)(o,(function(){var t=this,e=t.$createElement,i=t._self._c||e;return t.isShow?i("div",{staticClass:"out-focus-mask"},[i("div",{staticClass:"report-go-back",on:{click:function(e){return t.$router.go(-1)}}},[i("van-icon",{attrs:{name:"arrow-left",size:"25",color:"#43bc60"}})],1),"kick_previous"===t.type?[i("div",{staticClass:"content"},[t._m(0),i("van-button",{attrs:{color:"#43bc60",size:"small"},on:{click:function(e){return t.onKeepLearning("kick_previous")}}},[t._v("\n        继续学习\n      ")])],1)]:t._e(),"reject_current"===t.type?[t._m(1)]:t._e()],2):t._e()}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"tips"},[e("p",{staticClass:"kick-each-other"},[this._v("请勿同时多开任务学习")]),e("p",{staticClass:"kick-each-other"},[this._v("不要一心多用哦！")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"content"},[e("div",{staticClass:"tips"},[e("p",{staticClass:"kick-each-other"},[this._v("请勿同时多开任务学习")]),e("p",{staticClass:"kick-each-other"},[this._v("不要一心多用哦！")])])])}],!1,null,null,null);e.a=n.exports},a23b:function(t,e,i){"use strict";i.r(e),i("8e6e"),i("ac6a"),i("456d"),i("e7e5");var o=i("d399"),r=i("bd86"),n=i("3ce7"),s=i("2f62"),a=i("ea9a"),c=i("c8a5"),u=i("7c97");function h(t,e){var i=Object.keys(t);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(t);e&&(o=o.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),i.push.apply(i,o)}return i}function l(t){for(var e=1;e<arguments.length;e++){var i=null!=arguments[e]?arguments[e]:{};e%2?h(Object(i),!0).forEach((function(e){Object(r.a)(t,e,i[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(i)):h(Object(i)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(i,e))}))}return t}var p={name:"HomeworkIntro",mixins:[a.a,c.a],components:{OutFocusMask:u.a},data:function(){return{courseId:null,taskId:null,homework:null}},computed:l({hasResult:function(){return!!this.homework.latestHomeworkResult}},Object(s.e)({isLoading:function(t){return t.isLoading},user:function(t){return t.user}})),mounted:function(){this.initReport(),this.getInfo()},beforeRouteEnter:function(t,e,i){document.getElementById("app").style.background="#f6f6f6",i()},beforeRouteLeave:function(t,e,i){document.getElementById("app").style.background="",i()},methods:l(l({},Object(s.c)("course",["handHomeworkdo"])),{},{getInfo:function(){var t=this;this.courseId=this.$route.query.courseId,this.taskId=this.$route.query.taskId,n.a.getHomeworkIntro({query:{courseId:this.courseId,taskId:this.taskId}}).then((function(e){t.homework=e.homework,t.interruption()}))},initReport:function(){this.initReportData(this.$route.query.courseId,this.$route.query.taskId,"homework")},interruption:function(){var t=this;this.canDoing(this.homework.latestHomeworkResult,this.user.id).then((function(){t.startHomework()})).catch((function(e){var i=e.answer;t.submitHomework(i)}))},showResult:function(){this.$router.push({name:"homeworkResult",query:{homeworkId:this.homework.id,homeworkResultId:this.homework.latestHomeworkResult.id,courseId:this.$route.query.courseId,taskId:this.taskId}})},startHomework:function(){this.$router.push({name:"homeworkDo",query:{targetId:this.taskId,homeworkId:this.homework.id,courseId:this.$route.query.courseId},params:{KeepDoing:!0}})},submitHomework:function(t){var e=this,i={answer:t,homeworkId:this.homework.id,userId:this.user.id,homeworkResultId:this.homework.latestHomeworkResult.id};this.handHomeworkdo(i).then((function(t){e.reprtData({eventName:"finish"}),e.showResult()})).catch((function(t){o.a.fail(t.message)}))}})},d=i("0c7c"),m=Object(d.a)(p,(function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",[i("out-focus-mask",{attrs:{type:t.outFocusMaskType,isShow:t.isShowOutFocusMask,reportType:t.reportType},on:{outFocusMask:t.outFocusMask}}),t.isLoading?i("e-loading"):t._e(),t.homework?i("div",{staticClass:"intro-body"},[i("van-panel",{staticClass:"panel intro-panel",attrs:{title:t.$t("courseLearning.jobName")}},[i("div",{staticClass:"intro-panel__content intro-panel__content--title"},[t._v("\n        "+t._s(t.homework.name)+"\n      ")])]),i("van-panel",{staticClass:"panel intro-panel",attrs:{title:t.$t("courseLearning.jobDescription")}},[i("div",{staticClass:"intro-panel__content",domProps:{innerHTML:t._s(t.homework.description)}})])],1):t._e(),t.homework?i("div",{staticClass:"intro-footer"},[t.hasResult?i("van-button",{staticClass:"intro-footer__btn",attrs:{type:"primary"},on:{click:t.showResult}},[t._v(t._s(t.$t("courseLearning.viewResult2")))]):i("van-button",{staticClass:"intro-footer__btn",attrs:{type:"primary"},on:{click:function(e){return t.startHomework()}}},[t._v(t._s(t.$t("courseLearning.startAnsweringQuestions")))])],1):t._e()],1)}),[],!1,null,null,null);e.default=m.exports},c8a5:function(t,e,i){"use strict";i("6762"),i("2fdb");var o=i("3ce7"),r=i("faa5");e.a={data:function(){return{reportIntervalTime:null,reportLearnTime:null,reportFinishCondition:null,reportData:{courseId:null,taskId:null},reportResult:null,isFinish:!1,reportType:null,learnTime:0,isShowOutFocusMask:!1,outFocusMaskType:"",sign:"",record:{},absorbed:0,learnedTime:0}},beforeRouteLeave:function(t,e,i){this.sign.length>0&&localStorage.setItem("flowSign",this.sign),i()},beforeDestroy:function(){this.clearReportIntervalTime(),window.removeEventListener("beforeunload",this.onbeforeunloadFlowSign),this.toggleReportMaskHidden("remove")},methods:{initReportData:function(t,e,i){var o=!(arguments.length>3&&void 0!==arguments[3])||arguments[3];this.clearReportIntervalTime(),this.reportData={courseId:t,taskId:e},this.reportType=i,this.isFinish=!1,this.reportIntervalTime=null,this.reportLearnTime=null,this.reportResult=null,this.learnTime=0,this.reportFinishCondition=null,o&&this.initReportEvent(),this.onbeforeunload()},initReportEvent:function(){this.reprtData(),this.intervalReportData(),this.intervalReportLearnTime()},getCourseData:function(t,e){var i=this,r={courseId:t,taskId:e};return new Promise((function(t,e){o.a.getCourseData({query:r}).then((function(e){i.reportFinishCondition=e.activity.finishCondition,t(e)})).catch((function(t){e(t)}))}))},reprtData:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{eventName:"doing",ContinuousReport:!1,watchTime:null};if(null!==this.reportData.courseId&&null!==this.reportData.taskId&&(!this.isFinish||t.ContinuousReport))if(""===this.sign){var e={client:"h5"},i=localStorage.getItem("flowSign");i&&(e.lastSign=i,localStorage.removeItem("flowSign")),this.start(t,e)}else this.reportTaskEvent(t)},start:function(t,e){var i=this;o.a.reportTaskEvent({query:{courseId:this.reportData.courseId,taskId:this.reportData.taskId,eventName:"start"},data:e}).then((function(e){if(i.handleReportResult(e),e.learnControl.allowLearn)i.sign=e.record.flowSign,i.record=e.record,i.reportTaskEvent(t);else{var o=e.learnControl.denyReason;i.reportJudge(o)}}))},reportTaskEvent:function(t){var e=this;if(0!==this.sign.length){var i={client:"h5",sign:this.sign,duration:this.learnTime,status:this.absorbed};if(t.reActive&&(i.reActive=t.reActive),this.sourceType&&"video"===this.sourceType){var r=parseInt(this.nowWatchTime-this.lastWatchTime);this.lastWatchTime=this.nowWatchTime;var n={watchData:{duration:r}};i=Object.assign(i,n)}this.learnTime=0,o.a.reportTaskEvent({query:{courseId:this.reportData.courseId,taskId:this.reportData.taskId,eventName:t.eventName},data:i}).then((function(t){if(e.handleReportResult(t),e.record=t.record,e.absorbed=0,e.sign=t.record.flowSign,!t.learnControl.allowLearn){var i=t.learnControl.denyReason;e.reportJudge(i)}})).catch((function(t){e.clearReportIntervalTime()}))}},handleReportResult:function(t){this.reportResult=t,this.learnedTime=t.learnedTime,t.taskResult&&"finish"===t.taskResult.status?(this.isFinish=!0,this.$store.commit(r.B,"finish"),this.$store.commit("course/".concat(r.F),t.completionRate)):this.$store.commit(r.B,"start")},intervalReportLearnTime:function(){var t=this;this.reportLearnTime=setInterval((function(){t.checkoutTime(),t.learnTime++}),1e3)},intervalReportData:function(){var t=this,e=60*(arguments.length>0&&void 0!==arguments[0]?arguments[0]:1)*1e3;this.reportIntervalTime=setInterval((function(){t.reprtData({eventName:"doing",ContinuousReport:!0})}),e)},checkoutTime:function(){this.reportFinishCondition&&["time","watchTime"].includes(this.reportFinishCondition.type)&&parseInt(this.learnTime/60,10)>=parseInt(this.reportFinishCondition.data,10)&&this.reprtData({eventName:"finish",ContinuousReport:!0})},clearReportIntervalTime:function(){clearInterval(this.reportIntervalTime),clearInterval(this.reportLearnTime),this.reportIntervalTime=null,this.reportLearnTime=null},reportJudge:function(t){"kick_previous"===t?this.kickEachOther("kick_previous"):"reject_current"===t&&(this.clearReportIntervalTime(),this.kickEachOther("reject_current"))},outFocusMask:function(t){this.absorbed=1,this.isShowOutFocusMask=!1,!this.player||"video"!==this.reportType&&"audio"!==this.reportType||this.player.play(),this.toggleReportMaskHidden("remove"),this.reprtData({eventName:"doing",ContinuousReport:!0,reActive:1})},kickEachOther:function(t){if(this.absorbed=1,"testpaper"!==this.reportType&&"live"!==this.reportType&&"homework"!==this.reportType||"kick_previous"!==t){if(this.isShowOutFocusMask=!0,this.outFocusMaskType=t,"video"===this.reportType||"audio"===this.reportType){if(this.player&&this.player.destory&&"reject_current"===t)return void this.player.destory();this.player&&this.player.pause&&this.player.pause()}this.toggleReportMaskHidden("add")}},ineffectiveLearning:function(t){this.isShowOutFocusMask||(this.absorbed=0,this.isShowOutFocusMask=!0,this.outFocusMaskType=t,this.player&&"video"===this.reportType&&this.player.pause(),this.reprtData({eventName:"doing",ContinuousReport:!0}))},toggleReportMaskHidden:function(t){"video"!==this.reportType&&"audio"!==this.reportType&&("add"===t?document.getElementsByTagName("body")[0].classList.add("report-mask-hidden"):"remove"===t&&document.getElementsByTagName("body")[0].classList.remove("report-mask-hidden"))},initVisibilitychange:function(){document.addEventListener("visibilitychange",this.visibilityState)},visibilityState:function(){"video"===this.reportType&&("hidden"===document.visibilityState?this.ineffectiveLearning("ineffective_learning"):"visible"===document.visibilityState&&this.player.pause())},onbeforeunload:function(){window.addEventListener("beforeunload",this.onbeforeunloadFlowSign)},onbeforeunloadFlowSign:function(){this.sign.length>0&&localStorage.setItem("flowSign",this.sign)}}}},ea9a:function(t,e,i){"use strict";i("e17f");var o=i("2241");i("456d"),i("ac6a"),e.a={methods:{canDoing:function(t,e){return new Promise((function(i,r){if(t&&"doing"===t.status){var n="homework-".concat(e,"-").concat(t.id),s=JSON.parse(localStorage.getItem(n));s=s?Object.keys(s).forEach((function(t){s[t]=s[t].filter((function(t){return""!==t}))})):{},o.a.confirm({title:"提示",cancelButtonText:"放弃做题",confirmButtonText:"继续做题",message:"您有未完成的作业，是否继续？"}).then((function(){i()})).catch((function(){r({answer:s})}))}}))}}}}}]);