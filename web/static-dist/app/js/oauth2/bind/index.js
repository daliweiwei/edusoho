!function(r){var e={};function t(n){if(e[n])return e[n].exports;var o=e[n]={i:n,l:!1,exports:{}};return r[n].call(o.exports,o,o.exports,t),o.l=!0,o.exports}t.m=r,t.c=e,t.d=function(r,e,n){t.o(r,e)||Object.defineProperty(r,e,{enumerable:!0,get:n})},t.r=function(r){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(r,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(r,"__esModule",{value:!0})},t.t=function(r,e){if(1&e&&(r=t(r)),8&e)return r;if(4&e&&"object"==typeof r&&r&&r.__esModule)return r;var n=Object.create(null);if(t.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:r}),2&e&&"string"!=typeof r)for(var o in r)t.d(n,o,function(e){return r[e]}.bind(null,o));return n},t.n=function(r){var e=r&&r.__esModule?function(){return r.default}:function(){return r};return t.d(e,"a",e),e},t.o=function(r,e){return Object.prototype.hasOwnProperty.call(r,e)},t.p="/static-dist/",t(t.s=917)}({198:function(r,e,t){"use strict";t.d(e,"a",(function(){return n}));var n=function(r,e){r.keypress((function(r){13==r.which&&(e.trigger("click"),r.preventDefault())}))}},917:function(r,e,t){"use strict";t.r(e);var n=t(198),o=$("#third-party-bind-form"),u=$(".js-submit-btn");o.validate({currentDom:u,ajax:!0,rules:{password:{required:!0}},submitSuccess:function(r){0===r.success?$(".js-password-error").length||u.prev().addClass("has-error").append('<p id="password-error" class="form-error-message js-password-error">'.concat(r.message,"</p>")):window.location.href=r.url}});$("#password").focus((function(){$(".js-password-error").remove()})),Object(n.a)(o,u)}});