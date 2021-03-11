(window.webpackJsonp=window.webpackJsonp||[]).push([["fastlogin"],{"1495a":function(e,t,r){"use strict";r.r(t),r("8e6e"),r("ac6a"),r("456d");var n=r("bd86"),a=(r("96cf"),r("3b8d")),i=r("f13d"),o=(r("7185"),r("8e6c"),r("3ce7")),s=r("a262"),c=r("d863"),u=r("1527"),d=r("2f62");function l(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function f(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?l(Object(r),!0).forEach((function(t){Object(n.a)(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):l(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var h={name:"FastLogin",components:{EDrag:i.a},mixins:[s.a,c.a,u.a],data:function(){return{userinfo:{mobile:"",dragCaptchaToken:void 0,smsCode:"",smsToken:"",type:"sms_login"},userTerms:!1,privacyPolicy:!1,registerSettings:null,agreement:!0,dragEnable:!1,dragKey:0,errorMessage:{mobile:""},validated:{mobile:!1}}},computed:{btnDisable:function(){return!(this.userinfo.mobile&&this.userinfo.smsCode&&this.agreement)}},created:function(){var e=this;return Object(a.a)(regeneratorRuntime.mark((function t(){return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:if(!e.$store.state.token){t.next=4;break}return Toast.loading({message:"请稍后"}),e.afterLogin(),t.abrupt("return");case 4:e.getPrivacySetting();case 5:case"end":return t.stop()}}),t)})))()},methods:f(f({},Object(d.c)(["addUser","setMobile","sendSmsSend","fastLogin"])),{},{getPrivacySetting:function(){var e=this;return Object(a.a)(regeneratorRuntime.mark((function t(){return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,o.a.getSettings({query:{type:"user"}}).then((function(t){t.auth.user_terms_enabled&&(e.userTerms=!0),t.auth.privacy_policy_enabled&&(e.privacyPolicy=!0)})).catch((function(e){Toast.fail(e.message)}));case 2:e.registerSettings=t.sent;case 3:case"end":return t.stop()}}),t)})))()},lookPrivacyPolicy:function(){window.location.href=window.location.origin+"/mapi_v2/School/getPrivacyPolicy"},handleSmsSuccess:function(e){this.userinfo.dragCaptchaToken=e,this.handleSendSms()},handleSubmitSuccess:function(){this.afterLogin()},checkAgree:function(){this.agreement=!this.agreement},changeLogin:function(){this.$router.push({name:"login"})}})},g=r("0c7c"),m=Object(g.a)(h,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"login"},[r("span",{staticClass:"login-title"},[e._v("手机快捷登录")]),r("span",{staticClass:"login-des"},[e._v("新用户将为您自动注册")]),r("van-field",{staticClass:"login-input e-input",attrs:{border:!1,"error-message":e.errorMessage.mobile,placeholder:"请输入手机号","max-length":"11",type:"number",clearable:""},on:{blur:function(t){return e.validateMobileOrPsw("mobile")},keyup:function(t){return e.validatedChecker()}},model:{value:e.userinfo.mobile,callback:function(t){e.$set(e.userinfo,"mobile",t)},expression:"userinfo.mobile"}}),e.dragEnable?r("e-drag",{key:e.dragKey,ref:"dragComponent",attrs:{"limit-type":"sms_login"},on:{success:e.handleSmsSuccess}}):e._e(),r("van-field",{ref:"smsCode",staticClass:"login-input e-input",attrs:{border:!1,type:"number",center:"",clearable:"","max-length":"6",placeholder:"请输入验证码"},model:{value:e.userinfo.smsCode,callback:function(t){e.$set(e.userinfo,"smsCode",t)},expression:"userinfo.smsCode"}},[r("van-button",{attrs:{slot:"button",disabled:e.count.codeBtnDisable||!e.validated.mobile,size:"small",type:"primary"},on:{click:e.clickSmsBtn},slot:"button"},[e._v("\n      发送验证码\n      "),r("span",{directives:[{name:"show",rawName:"v-show",value:e.count.showCount,expression:"count.showCount"}]},[e._v("("+e._s(e.count.num)+")")])])],1),r("van-button",{staticClass:"primary-btn mb20",attrs:{disabled:e.btnDisable,type:"default"},on:{click:function(t){return e.handleSubmit(e.handleSubmitSuccess)}}},[e._v("登录")]),r("div",{staticClass:"login-bottom text-center"},[e.userTerms||e.privacyPolicy?r("div",{staticClass:"login-agree"},[r("van-checkbox",{attrs:{"icon-size":16,"checked-color":"#408ffb"},on:{click:e.checkAgree},model:{value:e.agreement,callback:function(t){e.agreement=t},expression:"agreement"}}),e._v("\n      我已阅读并同意"),e.userTerms?r("i",{on:{click:e.lookPrivacyPolicy}},[e._v("《用户服务》")]):e._e(),e.userTerms&&e.privacyPolicy?r("span",[e._v("和")]):e._e(),e.privacyPolicy?r("span",[e._v("《"),r("i",{on:{click:e.lookPrivacyPolicy}},[e._v("隐私协议")]),e._v("》")]):e._e()],1):e._e(),r("div",{staticClass:"login-change",on:{click:e.changeLogin}},[r("img",{staticClass:"login_change-icon",attrs:{src:"static/images/login_change.png"}}),e._v("切换账号密码登录\n    ")])])],1)}),[],!1,null,null,null);t.default=m.exports},1527:function(e,t,r){"use strict";r("e7e5");var n=r("d399"),a=r("7185");t.a={data:function(){return{count:{showCount:!1,num:60,codeBtnDisable:!1}}},methods:{validateMobileOrPsw:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"mobile",t=this.userinfo[e],r=a.a[e];0===t.length&&(this.errorMessage[e]=""),this.errorMessage[e]=r.validator(t)?"":r.message},validatedChecker:function(){this.userinfo.mobile.length>11&&(this.userinfo.mobile=this.userinfo.mobile.substring(0,11));var e=this.userinfo.mobile,t=a.a.mobile;this.validated.mobile=t.validator(e)},countDown:function(){var e=this;this.$nextTick((function(){e.$refs.smsCode.$refs.input.focus()})),this.count.showCount=!0,this.count.codeBtnDisable=!0,this.count.num=60;var t=setInterval((function(){if(e.count.num<=0)return e.count.codeBtnDisable=!1,e.count.showCount=!1,void clearInterval(t);e.count.num-=1}),1e3)},handleSubmit:function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:void 0;this.btnDisable||this.fastLogin({mobile:this.userinfo.mobile,smsToken:this.userinfo.smsToken,smsCode:this.userinfo.smsCode,loginType:"sms",client:"h5"}).then((function(t){return e(t)})).catch((function(e){t&&t(e.message),n.a.fail(e.message)}))},handleSendSms:function(){var e=this;this.sendSmsSend(this.userinfo).then((function(t){e.userinfo.smsToken=t.smsToken,e.countDown(),e.dragEnable=!1,e.userinfo.dragCaptchaToken=""})).catch((function(t){switch(t.code){case 4030301:case 4030302:e.dragKey+=1,e.userinfo.dragCaptchaToken="",e.userinfo.smsToken="",n.a.fail(t.message);break;case 4030303:e.dragEnable?n.a.fail(t.message):e.dragEnable=!0;break;default:n.a.fail(t.message)}}))},clickSmsBtn:function(){!this.count.codeBtnDisable&&this.validated.mobile&&(this.dragEnable?this.$refs.dragComponent.dragToEnd?this.$refs.dragComponent.initDragCaptcha():Object(n.a)("请先完成拼图验证"):this.handleSendSms())}}}},7067:function(e,t,r){"use strict";r("e17f");var n=r("2241"),a=(r("e7e5"),r("d399")),i=r("a026"),o=r("3ce7"),s=/micromessenger/.test(navigator.userAgent.toLowerCase());t.a=function(e,t){var r=arguments.length>2&&void 0!==arguments[2]&&arguments[2];if(!e||r&&!t)a.a.fail("缺少分享参数");else{var c={domainUri:location.origin,itemUri:"",source:"h5"};o.a.marketingActivities({query:{activityId:e},data:c}).then((function(e){var o=-1!==e.url.indexOf("?")?"&":"?",c=r?"".concat(t).concat(o,"ticket=").concat(e.ticket):e.url;s?window.location.href=c:n.a.confirm({message:"去微信完成活动",confirmButtonText:"复制链接",title:""}).then((function(){try{i.default.prototype.$copyText(c).then((function(){a.a.success("复制成功")}),(function(){a.a.fail("请更换浏览器复制")}))}catch(e){a.a.fail("请更换浏览器复制")}})).catch((function(){}))})).catch((function(e){a.a.fail(e.message)}))}}},"8e6c":function(e,t,r){"use strict";r("6b54"),r("28a5"),function(e){var t,r;void 0===e.btoa&&(e.btoa=(t="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/".split(""),function(e){var r,n,a,i,o,s,c;for(n=a=0,i=e.length,s=(i-=o=i%3)/3<<2,o>0&&(s+=4),r=new Array(s);n<i;)c=e.charCodeAt(n++)<<16|e.charCodeAt(n++)<<8|e.charCodeAt(n++),r[a++]=t[c>>18]+t[c>>12&63]+t[c>>6&63]+t[63&c];return 1==o?(c=e.charCodeAt(n++),r[a++]="".concat(t[c>>2]+t[(3&c)<<4],"==")):2==o&&(c=e.charCodeAt(n++)<<8|e.charCodeAt(n++),r[a++]="".concat(t[c>>10]+t[c>>4&63]+t[(15&c)<<2],"=")),r.join("")})),void 0===e.atob&&(e.atob=(r=[-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,62,-1,-1,-1,63,52,53,54,55,56,57,58,59,60,61,-1,-1,-1,-1,-1,-1,-1,0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,-1,-1,-1,-1,-1,-1,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,-1,-1,-1,-1,-1],function(e){var t,n,a,i,o,s,c,u,d,l;if((c=e.length)%4!=0)return"";if(/[^ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789\+\/\=]/.test(e))return"";for(d=c,(u="="==e.charAt(c-2)?1:"="==e.charAt(c-1)?2:0)>0&&(d-=4),d=3*(d>>2)+u,l=new Array(d),o=s=0;o<c&&-1!=(t=r[e.charCodeAt(o++)])&&-1!=(n=r[e.charCodeAt(o++)])&&(l[s++]=String.fromCharCode(t<<2|(48&n)>>4),-1!=(a=r[e.charCodeAt(o++)]))&&(l[s++]=String.fromCharCode((15&n)<<4|(60&a)>>2),-1!=(i=r[e.charCodeAt(o++)]));)l[s++]=String.fromCharCode((3&a)<<6|i);return l.join("")}));var n=2654435769;function a(e,t){var r=e.length,n=r<<2;if(t){var a=e[r-1];if(a<(n-=4)-3||a>n)return null;n=a}for(var i=0;i<r;i++)e[i]=String.fromCharCode(255&e[i],e[i]>>>8&255,e[i]>>>16&255,e[i]>>>24&255);var o=e.join("");return t?o.substring(0,n):o}function i(e,t){var r,n=e.length,a=n>>2;0!=(3&n)&&++a,t?(r=new Array(a+1))[a]=n:r=new Array(a);for(var i=0;i<n;++i)r[i>>2]|=e.charCodeAt(i)<<((3&i)<<3);return r}function o(e){return 4294967295&e}function s(e,t,r,n,a,i){return(r>>>5^t<<2)+(t>>>3^r<<4)^(e^t)+(i[3&n^a]^r)}function c(e){return e.length<4&&(e.length=4),e}function u(e){if(/^[\x00-\x7f]*$/.test(e))return e;for(var t=[],r=e.length,n=0,a=0;n<r;++n,++a){var i=e.charCodeAt(n);if(i<128)t[a]=e.charAt(n);else if(i<2048)t[a]=String.fromCharCode(192|i>>6,128|63&i);else{if(!(i<55296||i>57343)){if(n+1<r){var o=e.charCodeAt(n+1);if(i<56320&&o>=56320&&o<=57343){var s=65536+((1023&i)<<10|1023&o);t[a]=String.fromCharCode(240|s>>18&63,128|s>>12&63,128|s>>6&63,128|63&s),++n;continue}}throw new Error("Malformed string")}t[a]=String.fromCharCode(224|i>>12,128|i>>6&63,128|63&i)}}return t.join("")}function d(e,t){return(void 0===t||null===t||t<0)&&(t=e.length),0===t?"":/^[\x00-\x7f]*$/.test(e)||!/^[\x00-\xff]*$/.test(e)?t===e.length?e:e.substr(0,t):t<65535?function(e,t){for(var r=new Array(t),n=0,a=0,i=e.length;n<t&&a<i;n++){var o=e.charCodeAt(a++);switch(o>>4){case 0:case 1:case 2:case 3:case 4:case 5:case 6:case 7:r[n]=o;break;case 12:case 13:if(!(a<i))throw new Error("Unfinished UTF-8 octet sequence");r[n]=(31&o)<<6|63&e.charCodeAt(a++);break;case 14:if(!(a+1<i))throw new Error("Unfinished UTF-8 octet sequence");r[n]=(15&o)<<12|(63&e.charCodeAt(a++))<<6|63&e.charCodeAt(a++);break;case 15:if(!(a+2<i))throw new Error("Unfinished UTF-8 octet sequence");var s=((7&o)<<18|(63&e.charCodeAt(a++))<<12|(63&e.charCodeAt(a++))<<6|63&e.charCodeAt(a++))-65536;if(!(s>=0&&s<=1048575))throw new Error("Character outside valid Unicode range: 0x".concat(s.toString(16)));r[n++]=s>>10&1023|55296,r[n]=1023&s|56320;break;default:throw new Error("Bad UTF-8 encoding 0x".concat(o.toString(16)))}}return n<t&&(r.length=n),String.fromCharCode.apply(String,r)}(e,t):function(e,t){for(var r=[],n=new Array(32768),a=0,i=0,o=e.length;a<t&&i<o;a++){var s=e.charCodeAt(i++);switch(s>>4){case 0:case 1:case 2:case 3:case 4:case 5:case 6:case 7:n[a]=s;break;case 12:case 13:if(!(i<o))throw new Error("Unfinished UTF-8 octet sequence");n[a]=(31&s)<<6|63&e.charCodeAt(i++);break;case 14:if(!(i+1<o))throw new Error("Unfinished UTF-8 octet sequence");n[a]=(15&s)<<12|(63&e.charCodeAt(i++))<<6|63&e.charCodeAt(i++);break;case 15:if(!(i+2<o))throw new Error("Unfinished UTF-8 octet sequence");var c=((7&s)<<18|(63&e.charCodeAt(i++))<<12|(63&e.charCodeAt(i++))<<6|63&e.charCodeAt(i++))-65536;if(!(c>=0&&c<=1048575))throw new Error("Character outside valid Unicode range: 0x".concat(c.toString(16)));n[a++]=c>>10&1023|55296,n[a]=1023&c|56320;break;default:throw new Error("Bad UTF-8 encoding 0x".concat(s.toString(16)))}if(a>=32766){var u=a+1;n.length=u,r[r.length]=String.fromCharCode.apply(String,n),t-=u,a=-1}}return a>0&&(n.length=a,r[r.length]=String.fromCharCode.apply(String,n)),r.join("")}(e,t)}function l(e,t){return void 0===e||null===e||0===e.length?e:(e=u(e),t=u(t),a(function(e,t){var r,a,i,c,u,d,l=e.length,f=l-1;for(a=e[f],i=0,d=0|Math.floor(6+52/l);d>0;--d){for(c=(i=o(i+n))>>>2&3,u=0;u<f;++u)r=e[u+1],a=e[u]=o(e[u]+s(i,r,a,u,c,t));r=e[0],a=e[f]=o(e[f]+s(i,r,a,f,c,t))}return e}(i(e,!0),c(i(t,!1))),!1))}function f(e,t){return void 0===e||null===e||0===e.length?e:(t=u(t),d(a(function(e,t){var r,a,i,c,u,d=e.length,l=d-1;for(r=e[0],i=o(Math.floor(6+52/d)*n);0!==i;i=o(i-n)){for(c=i>>>2&3,u=l;u>0;--u)a=e[u-1],r=e[u]=o(e[u]-s(i,r,a,u,c,t));a=e[l],r=e[0]=o(e[0]-s(i,r,a,0,c,t))}return e}(i(e,!1),c(i(t,!1))),!0)))}e.XXTEA={utf8Encode:u,utf8Decode:d,encrypt:l,encryptToBase64:function(t,r){return e.btoa(l(t,r))},decrypt:f,decryptFromBase64:function(t,r){return void 0===t||null===t||0===t.length?t:f(e.atob(t),r)}}}(window)},a262:function(e,t,r){"use strict";var n=r("7067");t.a={methods:{activityHandle:function(e,t){var r=arguments.length>2&&void 0!==arguments[2]&&arguments[2];Object(n.a)(e,t,r)}}}},d863:function(e,t,r){"use strict";r("a481");var n=r("7067");t.a={data:function(){return{redirect:""}},created:function(){this.redirect=decodeURIComponent(this.$route.fullPath)},methods:{afterLogin:function(){var e=this,t=this.$route.query.redirect?decodeURIComponent(this.$route.query.redirect):"/",r=this.$route.query.skipUrl?decodeURIComponent(this.$route.query.skipUrl):"",a=this.$route.query.callbackType,i=this.$route.query.activityId,o=decodeURIComponent(this.$route.query.callback);setTimeout((function(){if(a)switch(a){case"marketing":Object(n.a)(i,o)}else r?e.$router.replace({path:t,query:{backUrl:r}}):e.$router.replace({path:t})}),2e3)}}}},f13d:function(e,t,r){"use strict";r("8e6e"),r("ac6a"),r("456d");var n=r("75fc"),a=(r("e7e5"),r("d399")),i=r("bd86"),o=(r("c5f6"),r("3ce7"));function s(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}var c={props:{tips:{type:String,default:"拖动左边滑块完成上方拼图"},limitType:{type:String,default:""}},data:function(){return{imgInfo:{url:"",jigsaw:"",token:""},dragState:{left:0,width:0,currentX:0,currentLeft:0,btnWidth:0,maskWidth:0},dragToEnd:!1}},created:function(){this.initDragCaptcha()},mounted:function(){var e=this.$refs.bar,t=this.$refs.dragBtn,r=(this.$refs.drag,e.getBoundingClientRect());Object.assign(this.dragState,{left:Number(r.left.toFixed(2)),width:e.clientWidth,btnWidth:t.offsetWidth/2})},methods:{initDragCaptcha:function(){var e=this,t={};this.limitType&&(t={limitType:this.limitType}),o.a.dragCaptcha({data:t}).then((function(t){e.imgInfo=function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?s(Object(r),!0).forEach((function(t){Object(i.a)(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):s(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({},t),Object.assign(e.dragState,{currentLeft:0,maskWidth:0}),e.dragToEnd=!1})).catch((function(e){a.a.fail(e.message)}))},handletTouchEnd:function(){var e=this;if(!this.dragToEnd&&this.dragState.currentLeft){var t=this.getToken();this.dragToEnd=!0,o.a.dragValidate({query:{token:t}}).then((function(r){a.a.success("验证成功"),e.$emit("success",t)})).catch((function(t){a.a.fail(t.message),e.initDragCaptcha()}))}},handleTouchMove:function(e){if(!this.dragToEnd){e.preventDefault();var t=this.$refs.dragBtn,r=(this.$refs.dragImgBg,this.dragState),n=e.clientX?e.clientX.toFixed(2):e.targetTouches[0].pageX.toFixed(2)-this.$refs.drag.offsetLeft,a=(n-r.left-r.btnWidth).toFixed(2);a<0&&(a=0),n>r.width+this.$refs.drag.offsetLeft&&(a=r.width-this.$refs.dragImg.width),Object.assign(this.dragState,{currentLeft:a,maskWidth:(Number(a)+t.offsetWidth/2).toFixed(2)})}},calPositionX:function(){var e=this.$refs.dragImgBg,t=(e.naturalWidth/e.width).toFixed(2);return(Number(this.dragState.currentLeft).toFixed(2)*t).toFixed(2)},getToken:function(){var e={token:this.imgInfo.token,captcha:this.calPositionX()};return Object(n.a)(btoa(JSON.stringify(e))).reverse().join("")}}},u=r("0c7c"),d=Object(u.a)(c,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{ref:"drag",staticClass:"e-drag"},[r("div",{staticClass:"e-drag-section"},[r("div",{staticClass:"e-drag-img"},[r("img",{ref:"dragImgBg",attrs:{src:e.imgInfo.url,alt:""}}),r("img",{ref:"dragImg",staticClass:"e-drag-img__dragable",style:{left:e.dragState.currentLeft+"px"},attrs:{src:e.imgInfo.jigsaw,alt:""}})]),r("div",{ref:"bar",staticClass:"e-drag-bar"},[r("span",[e._v(e._s(e.tips))]),r("div",{staticClass:"e-drag-bar__mask",style:{width:e.dragState.maskWidth+"px"}}),r("div",{ref:"dragBtn",staticClass:"e-drag-btn",style:{left:e.dragState.currentLeft+"px"},on:{touchend:e.handletTouchEnd,touchmove:e.handleTouchMove}},[r("img",{attrs:{src:"static/images/drag.png",alt:""}})])])])])}),[],!1,null,null,null);t.a=d.exports}}]);