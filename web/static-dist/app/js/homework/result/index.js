!function(e){function t(t){for(var o,r,c=t[0],a=t[1],l=t[2],d=0,m=[];d<c.length;d++)r=c[d],Object.prototype.hasOwnProperty.call(i,r)&&i[r]&&m.push(i[r][0]),i[r]=0;for(o in a)Object.prototype.hasOwnProperty.call(a,o)&&(e[o]=a[o]);for(u&&u(t);m.length;)m.shift()();return s.push.apply(s,l||[]),n()}function n(){for(var e,t=0;t<s.length;t++){for(var n=s[t],o=!0,c=1;c<n.length;c++){var a=n[c];0!==i[a]&&(o=!1)}o&&(s.splice(t--,1),e=r(r.s=n[0]))}return e}var o={},i={241:0},s=[];function r(t){if(o[t])return o[t].exports;var n=o[t]={i:t,l:!1,exports:{}};return e[t].call(n.exports,n,n.exports,r),n.l=!0,n.exports}r.m=e,r.c=o,r.d=function(e,t,n){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)r.d(n,o,function(t){return e[t]}.bind(null,o));return n},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="/static-dist/";var c=window.webpackJsonp=window.webpackJsonp||[],a=c.push.bind(c);c.push=t,c=c.slice();for(var l=0;l<c.length;l++)t(c[l]);var u=a;s.push([1113,0]),n()}({1113:function(e,t,n){"use strict";n.r(t);var o=n(4),i=n.n(o),s=n(5),r=n.n(s);new(function(){function e(){i()(this,e),this.$commentBox=$(".js-comment-box"),this.$modifyCommentBox=$(".js-modify-comment-box"),this.answerRecordId=$(".js-answer-record-id").val(),this.initEvent()}return r()(e,[{key:"initEvent",value:function(){var e=this;$(".js-modify-btn").click((function(){e.handleClickModify()})),$(".js-close-btn").click((function(){e.handleClickClose()})),$(".js-save-btn").click((function(){e.handleClickSave()})),$(".js-comment-select").change((function(){e.handleChangeSelect()}))}},{key:"handleClickClose",value:function(){this.showElement(this.$commentBox),this.hiddenElement(this.$modifyCommentBox),$(".js-comment-textarea").val($(".js-comment-content").text()),$(".js-comment-select option:first").prop("selected","selected")}},{key:"handleClickSave",value:function(){this.showElement(this.$commentBox),this.hiddenElement(this.$modifyCommentBox);var e=$(".js-comment-textarea").val();$(".js-comment-content").text(e),$.ajax({type:"POST",beforeSend:function(e){e.setRequestHeader("Accept","application/vnd.edusoho.v2+json"),e.setRequestHeader("X-CSRF-Token",$("meta[name=csrf-token]").attr("content"))},data:{comment:e},url:"/api/answerRecord/"+this.answerRecordId+"/comment",success:function(e){console.log(e)}})}},{key:"handleClickModify",value:function(){this.hiddenElement(this.$commentBox),this.showElement(this.$modifyCommentBox)}},{key:"handleChangeSelect",value:function(){var e=$(".js-comment-select").find("option:selected").text();$(".js-comment-textarea").val(e)}},{key:"hiddenElement",value:function(e){e.hasClass("show")&&e.removeClass("show"),e.addClass("hidden")}},{key:"showElement",value:function(e){e.hasClass("hidden")&&e.removeClass("hidden"),e.addClass("show")}}]),e}())}});