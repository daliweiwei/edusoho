!function(s){function e(e){for(var t,n,r=e[0],i=e[1],a=e[2],o=0,l=[];o<r.length;o++)n=r[o],Object.prototype.hasOwnProperty.call(c,n)&&c[n]&&l.push(c[n][0]),c[n]=0;for(t in i)Object.prototype.hasOwnProperty.call(i,t)&&(s[t]=i[t]);for(d&&d(e);l.length;)l.shift()();return f.push.apply(f,a||[]),u()}function u(){for(var e,t=0;t<f.length;t++){for(var n=f[t],r=!0,i=1;i<n.length;i++){var a=n[i];0!==c[a]&&(r=!1)}r&&(f.splice(t--,1),e=o(o.s=n[0]))}return e}var n={},c={40:0},f=[];function o(e){if(n[e])return n[e].exports;var t=n[e]={i:e,l:!1,exports:{}};return s[e].call(t.exports,t,t.exports,o),t.l=!0,t.exports}o.m=s,o.c=n,o.d=function(e,t,n){o.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},o.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.t=function(t,e){if(1&e&&(t=o(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(o.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)o.d(n,r,function(e){return t[e]}.bind(null,r));return n},o.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(t,"a",t),t},o.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},o.p="/static-dist/";var t=window.webpackJsonp=window.webpackJsonp||[],r=t.push.bind(t);t.push=e,t=t.slice();for(var i=0;i<t.length;i++)e(t[i]);var d=r;f.push([504,0]),u()}({348:function(e,t,n){"use strict";var r=n(0),l=n.n(r),i=n(1),a=n.n(i),o=n(72),s=n.n(o),u=(n(288),function(){function o(e){var t=e.element,n=e.swfUrl,r=e.pdfUrl,i=e.watermarkOptions,a=e.canCopy;l()(this,o),this.element=$(t),this.swfUrl=n||"",this.pdfUrl=r||"",this.swfPlayerWidth="100%",this.swfPlayerHeight="100%",this.swfPlayerUrl="",this.watermarkOptions=i||"",this.canCopy=a||!1,this.init(),console.log(i)}return a()(o,[{key:"init",value:function(){this.isSupportHtml5()&&!this.isIE9()?this.initPDFJSViewer():this.initSwfViewer(),this.onFullScreen()}},{key:"onFullScreen",value:function(){window.onmessage=function(e){var t,n;console.log(e.data),null==e||null==e||"boolean"==typeof(t=e.data)&&(n=$("#task-content-iframe",window.parent.document),t?(n.removeClass("screen-full"),n.width("100%")):(n.addClass("screen-full"),n.width(window.document.body.offsetWidth+"px")))}}},{key:"isIE9",value:function(){return-1!=navigator.appVersion.indexOf("MSIE 9.")}},{key:"isSupportHtml5",value:function(){return $.support.leadingWhitespace}},{key:"initPDFJSViewer",value:function(){$("html").attr("dir","ltr");var e=app.cloudOldDocumentSdkUrl+"#"+this.pdfUrl;this.canCopy||(e+="#false");var t='<iframe id="doc-pdf-player" class="task-content-iframe" \n     src="'.concat(e,'" style="width:100%;height:100%;border:0px" \n     allowfullscreen="" webkitallowfullscreen="">\n      </iframe>');this.element.append(t),this.addWatermark()}},{key:"initSwfViewer",value:function(){$.html('<div id="website"><p align="center" class="style1">'.concat(Translator.trans("site.flash_not_install_hint"),"</p></div>"));var e={doc_url:decodeURI(this.swfUrl.value)};s.a.embedSWF(this.swfPlayerUrl,"website",this.swfPlayerWidth,this.swfPlayerHeight,"9.0.45",null,e,{bgcolor:"#efefef",allowFullScreen:!0,wmode:"window",allowNetworking:"all",allowscriptaccess:"always",autoPlay:!1},{id:"website"}),this.addWatermark()}},{key:"addWatermark",value:function(){this.watermarkOptions&&this.element.WaterMark(this.watermarkOptions)}}]),o}());t.a=u},504:function(e,t,n){"use strict";n.r(t);var r=n(348),i=$("#document-content"),a=i.data("watermark-url");function o(e){new r.a({element:i,swfUrl:i.data("swf"),pdfUrl:i.data("pdf"),watermarkOptions:{contents:e,xPosition:"center",yPosition:"center",rotate:45},canCopy:i.data("disableCopy")})}a?$.get(a,function(e){console.log(e),o(e)}):o("")}});