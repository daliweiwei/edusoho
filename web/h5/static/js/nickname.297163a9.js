(window.webpackJsonp=window.webpackJsonp||[]).push([["nickname"],{"4a4c":function(e,n,t){"use strict";t.r(n),t("8e6e"),t("ac6a"),t("456d"),t("e7e5");var i=t("d399"),c=t("bd86"),a=(t("3ce7"),t("2f62"));function r(e,n){var t=Object.keys(e);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(e);n&&(i=i.filter((function(n){return Object.getOwnPropertyDescriptor(e,n).enumerable}))),t.push.apply(t,i)}return t}function s(e){for(var n=1;n<arguments.length;n++){var t=null!=arguments[n]?arguments[n]:{};n%2?r(Object(t),!0).forEach((function(n){Object(c.a)(e,n,t[n])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(t)):r(Object(t)).forEach((function(n){Object.defineProperty(e,n,Object.getOwnPropertyDescriptor(t,n))}))}return e}var o={data:function(){return{nickname:"",confirmFlag:!1}},computed:s({btnDisable:function(){return 0===this.nickname.length||!this.confirmFlag}},Object(a.e)({isLoading:function(e){return e.isLoading}})),watch:{nickname:function(){/^([\u4E00-\uFA29]|[a-zA-Z0-9_.·])*$/i.test(this.nickname)?this.confirmFlag=!0:(Object(i.a)("仅支持中文字、英文字母、数字及_ . ·"),this.confirmFlag=!1)}},created:function(){var e=this.$route.query.nickname;e&&(this.nickname=e)},methods:s(s({},Object(a.c)(["setNickname"])),{},{modifyNickname:function(){var e=this;this.confirmFlag&&this.setNickname({nickname:this.nickname}).then((function(){i.a.success("修改成功"),e.$router.go(-1)})).catch((function(e){i.a.fail(e.message)}))}})},u=t("0c7c"),l=Object(u.a)(o,(function(){var e=this,n=e.$createElement,t=e._self._c||n;return t("div",{staticClass:"my_setting-nickname"},[e.isLoading?t("e-loading"):e._e(),t("van-field",{staticClass:"my_setting-nickname--input",attrs:{placeholder:"请修改您的用户名"},model:{value:e.nickname,callback:function(n){e.nickname=n},expression:"nickname"}}),t("van-button",{staticClass:"primary-btn my_setting-nickname—-btn",attrs:{disabled:e.btnDisable,type:"default"},on:{click:e.modifyNickname}},[e._v("确定")])],1)}),[],!1,null,null,null);n.default=l.exports}}]);