!function(t){function e(e){for(var n,i,l=e[0],s=e[1],c=e[2],u=0,g=[];u<l.length;u++)i=l[u],Object.prototype.hasOwnProperty.call(r,i)&&r[i]&&g.push(r[i][0]),r[i]=0;for(n in s)Object.prototype.hasOwnProperty.call(s,n)&&(t[n]=s[n]);for(d&&d(e);g.length;)g.shift()();return o.push.apply(o,c||[]),a()}function a(){for(var t,e=0;e<o.length;e++){for(var a=o[e],n=!0,l=1;l<a.length;l++){var s=a[l];0!==r[s]&&(n=!1)}n&&(o.splice(e--,1),t=i(i.s=a[0]))}return t}var n={},r={54:0},o=[];function i(e){if(n[e])return n[e].exports;var a=n[e]={i:e,l:!1,exports:{}};return t[e].call(a.exports,a,a.exports,i),a.l=!0,a.exports}i.m=t,i.c=n,i.d=function(t,e,a){i.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:a})},i.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},i.t=function(t,e){if(1&e&&(t=i(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var a=Object.create(null);if(i.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var n in t)i.d(a,n,function(e){return t[e]}.bind(null,n));return a},i.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return i.d(e,"a",e),e},i.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},i.p="/static-dist/";var l=window.webpackJsonp=window.webpackJsonp||[],s=l.push.bind(l);l.push=e,l=l.slice();for(var c=0;c<l.length;c++)e(l[c]);var d=s;o.push([779,0]),a()}({779:function(t,e,a){"use strict";a.r(e);var n=a(153),r=a.n(n),o=a(96),i=a.n(o),l=window.localStorage.getItem("upgradeNotice")?window.localStorage.getItem("upgradeNotice"):"";1==$(".js-notice").val()&&1!=l&&(window.localStorage.setItem("upgradeNotice","1"),$(".js-upgrade-notice").removeClass("hidden")),$(".js-show-toggle").on("click",(function(t){var e=$(t.currentTarget);$(".js-steps").slideToggle();var a=$(".js-toggle-text").text()===Translator.trans("site.data.collapse")?Translator.trans("site.data.expand"):Translator.trans("site.data.collapse");$(".js-toggle-text").text(a),e.find("i").toggleClass("es-icon-keyboardarrowup es-icon-keyboardarrowdown")}));var s=function(t,e,a){var n=$(e),r=t.find(".modal-dialog"),o=r.width()?r.width():$(window).width()-20,i=$(window).height(),l=e.width,s=e.height,c=0;l/s>=4/3?c=(i-(s=l>o?s/(l/o):s*(o/l)))/2:(s=i>600?600:.9*i,n.height(s),n.width("auto"),c=(i-s)/2),t.find("a").attr("href",a.urlOfImage).append(n).css({"margin-top":c}),t.modal("show")};!function(){var t=$(".js-steps");t.length&&$.get(t.data("url"),(function(e){t.html(e)}));var e=$(".js-data-overview");e.length&&$.get(e.data("url"),(function(t){e.html(t)}));var a=$(".js-quick-entrance");a.length&&$.get(a.data("url"),(function(t){a.html(t)}));var n=$(".js-admin-info");n.length&&n.data("url")&&$.get(n.data("url"),(function(t){n.html(t);var e=$(".js-mini-program").data("src");$(".js-mini-program").popover({trigger:"hover",placement:"bottom",title:Translator.trans("admin_v2.homepage.mini_program.title"),template:'<div class="popover mini-program-popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',html:!0,content:'<img src="'.concat(e,'" width="200px">')})}));var r=$(".js-announcement");r.length&&r.data("url")&&$.get(r.data("url"),(function(t){r.html(t)}))}(),setTimeout((function(){!function(){var t=$(".application-intro");t.length&&t.data("url")&&$.get(t.data("url"),(function(e){t.html(e)}));var e=$(".js-admin-advice");e.length&&e.data("url")&&$.get(e.data("url"),(function(t){e.html(t)}));var a=$(".js-admin-changelog");a.length&&a.data("url")&&$.get(a.data("url"),(function(t){a.html(t)}));var n=$(".js-admin-qrcode");n.length&&n.data("url")&&$.get(n.data("url"),(function(t){n.html(t)}))}()}),1e3),window.onload=function(){var t;t=$("#cloud-ad"),$.get(t.data("url"),(function(e){if(!e.error){var a=new Image;i.a.get("cloud-ad")!=e.image&&(a.src=e.image,a.complete?s(t,a,e):a.onload=function(){s(t,a,e),a.onload=null})}})),t.on("hide.bs.modal",(function(){i.a.set("cloud-ad",t.find("img").attr("src"),{expires:3600})})),$(".js-steps").delegate($(".task-left"),"click",(function(t){r()(t.target.classList).includes("task-left")&&($(".task-ul").animate({scrollLeft:0},400),$(".task-left").css("opacity",0),$(".task-left").hide(),$(".task-right").css("opacity",1),$(".task-right").show())})),$(".js-steps").delegate($(".task-right"),"click",(function(t){var e;r()(t.target.classList).includes("task-right")&&(e=$(".task-ul")[0].scrollWidth,$(".task-ul").animate({scrollLeft:e},400),$(".task-left").css("opacity",1),$(".task-left").show(),$(".task-right").css("opacity",0),$(".task-right").hide())}))},$(".js-no-network").click((function(){cd.message({type:"danger",message:Translator.trans("admin.can_not_link_data")})}))}});