(window.webpackJsonp=window.webpackJsonp||[]).push([["marketingWxpay"],{8567:function(e,t,a){"use strict";a.r(t),a("e7e5");var n=a("d399"),r=(a("96cf"),a("3b8d")),i=a("3ce7"),s={name:"MarketingWXPay",data:function(){return{isLoading:!1,payInfo:{}}},computed:{isWeixinBrowser:function(){return/micromessenger/.test(navigator.userAgent.toLowerCase())}},created:function(){var e=this;return Object(r.a)(regeneratorRuntime.mark((function t(){var a;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:if(a=e.$route.query.payToken||""){t.next=4;break}return n.a.fail("缺少关键信息"),t.abrupt("return");case 4:return t.next=6,i.a.getMarketingPayConfig({data:{token:a}});case 6:e.payInfo=t.sent;case 7:case"end":return t.stop()}}),t)})))()},methods:{handlePay:function(){var e=this;return Object(r.a)(regeneratorRuntime.mark((function t(){return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:WeixinJSBridge.invoke("getBrandWCPayRequest",e.payInfo.config,(function(t){"get_brand_wcpay_request:ok"==t.err_msg?window.location.href=e.payInfo.redirectUrl+"&isNeedCheckOrderStatus=1":"get_brand_wcpay_request:fail"==t.err_msg||t.err_msg}));case 1:case"end":return t.stop()}}),t)})))()}}},o=(a("d821"),a("0c7c")),c=Object(o.a)(s,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return e.isWeixinBrowser?a("div",{staticClass:"marketing-wxpay"},[a("div",{staticClass:"marketing-wxpay__title"},[e._v(e._s(e.$t("marketingPay.amountLabel")))]),a("div",{staticClass:"marketing-wxpay__amount"},[a("span",{staticClass:"amount-unit"},[e._v("￥")]),a("span",{staticClass:"amount-number"},[e._v(e._s(e.payInfo.amount))])]),a("div",{staticClass:"marketing-wxpay__info"},[a("div",{staticClass:"info-label"},[e._v(e._s(e.$t("marketingPay.orderSnLabel")))]),a("div",{staticClass:"info-desc"},[e._v(e._s(e.payInfo.orderSn))])]),a("div",{staticClass:"marketing-wxpay__info",staticStyle:{border:"none"}},[a("div",{staticClass:"info-label"},[e._v(e._s(e.$t("marketingPay.acceptLabel")))]),a("div",{staticClass:"info-desc"},[e._v(e._s(e.payInfo.siteName))])]),a("div",{staticClass:"pay-btn",on:{click:e.handlePay}},[e._v("\n    "+e._s(e.$t("marketingPay.payNow"))+"\n  ")])]):e._e()}),[],!1,null,"64f536a8",null);t.default=c.exports},d821:function(e,t,a){"use strict";var n=a("ee9b");a.n(n).a},ee9b:function(e,t,a){}}]);