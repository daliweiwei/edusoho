(window.webpackJsonp=window.webpackJsonp||[]).push([["marketingWxpay"],{8567:function(e,n,t){"use strict";t.r(n),t("e7e5");var r=t("d399"),a=(t("96cf"),t("3b8d")),i=t("3ce7"),s={name:"MarketingWXPay",computed:{isWeixinBrowser:function(){return/micromessenger/.test(navigator.userAgent.toLowerCase())}},methods:{handlePay:function(){var e=this;return Object(a.a)(regeneratorRuntime.mark((function n(){var t,a;return regeneratorRuntime.wrap((function(n){for(;;)switch(n.prev=n.next){case 0:if(t=e.$route.query.payToken||""){n.next=4;break}return r.a.fail("缺少关键信息"),n.abrupt("return");case 4:return n.next=6,i.a.getMarketingPayConfig({data:{token:t}});case 6:a=n.sent,WeixinJSBridge.invoke("getBrandWCPayRequest",a.config,(function(e){"get_brand_wcpay_request:ok"==e.err_msg?window.location.href=a.returnUrl:"get_brand_wcpay_request:fail"==e.err_msg||e.err_msg}));case 8:case"end":return n.stop()}}),n)})))()}}},c=(t("caea"),t("0c7c")),o=Object(c.a)(s,(function(){var e=this.$createElement,n=this._self._c||e;return this.isWeixinBrowser?n("div",{staticClass:"pay-btn",on:{click:this.handlePay}},[this._v("\n  立即支付\n")]):this._e()}),[],!1,null,null,null);n.default=o.exports},b2c2:function(e,n,t){},caea:function(e,n,t){"use strict";var r=t("b2c2");t.n(r).a}}]);