!function(t){var e={};function n(r){if(e[r])return e[r].exports;var o=e[r]={i:r,l:!1,exports:{}};return t[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=t,n.c=e,n.d=function(t,e,r){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:r})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var o in t)n.d(r,o,function(e){return t[e]}.bind(null,o));return r},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="/static-dist/",n(n.s=1517)}({1517:function(t,e,n){var r=n(464),o=document.querySelector(".qrcode-canvas"),i=window.location.origin,s=(window.location.href,$("input[name=courseId]").val());$.get({url:"/api/pages/h5/courses/"+s,headers:{Accept:"application/vnd.edusoho.v2+json"}}).then((function(t){var e=t.goodsId;r.toCanvas(o,"".concat(i,"/mobile/downloadMiddlePage?courseId=").concat(s,"&goodsId=").concat(e),{width:190,height:190})}))},172:function(t,e){let n;const r=[0,26,44,70,100,134,172,196,242,292,346,404,466,532,581,655,733,815,901,991,1085,1156,1258,1364,1474,1588,1706,1828,1921,2051,2185,2323,2465,2611,2761,2876,3034,3196,3362,3532,3706];e.getSymbolSize=function(t){if(!t)throw new Error('"version" cannot be null or undefined');if(t<1||t>40)throw new Error('"version" should be in range from 1 to 40');return 4*t+17},e.getSymbolTotalCodewords=function(t){return r[t]},e.getBCHDigit=function(t){let e=0;for(;0!==t;)e++,t>>>=1;return e},e.setToSJISFunction=function(t){if("function"!=typeof t)throw new Error('"toSJISFunc" is not a valid function.');n=t},e.isKanjiModeEnabled=function(){return void 0!==n},e.toSJIS=function(t){return n(t)}},173:function(t,e,n){const r=n(451),o=n(452);e.NUMERIC={id:"Numeric",bit:1,ccBits:[10,12,14]},e.ALPHANUMERIC={id:"Alphanumeric",bit:2,ccBits:[9,11,13]},e.BYTE={id:"Byte",bit:4,ccBits:[8,16,16]},e.KANJI={id:"Kanji",bit:8,ccBits:[8,10,12]},e.MIXED={bit:-1},e.getCharCountIndicator=function(t,e){if(!t.ccBits)throw new Error("Invalid mode: "+t);if(!r.isValid(e))throw new Error("Invalid version: "+e);return e>=1&&e<10?t.ccBits[0]:e<27?t.ccBits[1]:t.ccBits[2]},e.getBestModeForData=function(t){return o.testNumeric(t)?e.NUMERIC:o.testAlphanumeric(t)?e.ALPHANUMERIC:o.testKanji(t)?e.KANJI:e.BYTE},e.toString=function(t){if(t&&t.id)return t.id;throw new Error("Invalid mode")},e.isValid=function(t){return t&&t.bit&&t.ccBits},e.from=function(t,n){if(e.isValid(t))return t;try{return function(t){if("string"!=typeof t)throw new Error("Param is not a string");switch(t.toLowerCase()){case"numeric":return e.NUMERIC;case"alphanumeric":return e.ALPHANUMERIC;case"kanji":return e.KANJI;case"byte":return e.BYTE;default:throw new Error("Unknown mode: "+t)}}(t)}catch(t){return n}}},387:function(t,e){e.L={bit:1},e.M={bit:0},e.Q={bit:3},e.H={bit:2},e.isValid=function(t){return t&&void 0!==t.bit&&t.bit>=0&&t.bit<4},e.from=function(t,n){if(e.isValid(t))return t;try{return function(t){if("string"!=typeof t)throw new Error("Param is not a string");switch(t.toLowerCase()){case"l":case"low":return e.L;case"m":case"medium":return e.M;case"q":case"quartile":return e.Q;case"h":case"high":return e.H;default:throw new Error("Unknown EC Level: "+t)}}(t)}catch(t){return n}}},450:function(t,e,n){const r=n(387),o=[1,1,1,1,1,1,1,1,1,1,2,2,1,2,2,4,1,2,4,4,2,4,4,4,2,4,6,5,2,4,6,6,2,5,8,8,4,5,8,8,4,5,8,11,4,8,10,11,4,9,12,16,4,9,16,16,6,10,12,18,6,10,17,16,6,11,16,19,6,13,18,21,7,14,21,25,8,16,20,25,8,17,23,25,9,17,23,34,9,18,25,30,10,20,27,32,12,21,29,35,12,23,34,37,12,25,34,40,13,26,35,42,14,28,38,45,15,29,40,48,16,31,43,51,17,33,45,54,18,35,48,57,19,37,51,60,19,38,53,63,20,40,56,66,21,43,59,70,22,45,62,74,24,47,65,77,25,49,68,81],i=[7,10,13,17,10,16,22,28,15,26,36,44,20,36,52,64,26,48,72,88,36,64,96,112,40,72,108,130,48,88,132,156,60,110,160,192,72,130,192,224,80,150,224,264,96,176,260,308,104,198,288,352,120,216,320,384,132,240,360,432,144,280,408,480,168,308,448,532,180,338,504,588,196,364,546,650,224,416,600,700,224,442,644,750,252,476,690,816,270,504,750,900,300,560,810,960,312,588,870,1050,336,644,952,1110,360,700,1020,1200,390,728,1050,1260,420,784,1140,1350,450,812,1200,1440,480,868,1290,1530,510,924,1350,1620,540,980,1440,1710,570,1036,1530,1800,570,1064,1590,1890,600,1120,1680,1980,630,1204,1770,2100,660,1260,1860,2220,720,1316,1950,2310,750,1372,2040,2430];e.getBlocksCount=function(t,e){switch(e){case r.L:return o[4*(t-1)+0];case r.M:return o[4*(t-1)+1];case r.Q:return o[4*(t-1)+2];case r.H:return o[4*(t-1)+3];default:return}},e.getTotalCodewordsCount=function(t,e){switch(e){case r.L:return i[4*(t-1)+0];case r.M:return i[4*(t-1)+1];case r.Q:return i[4*(t-1)+2];case r.H:return i[4*(t-1)+3];default:return}}},451:function(t,e){e.isValid=function(t){return!isNaN(t)&&t>=1&&t<=40}},452:function(t,e){const n="[0-9]+";let r="(?:[u3000-u303F]|[u3040-u309F]|[u30A0-u30FF]|[uFF00-uFFEF]|[u4E00-u9FAF]|[u2605-u2606]|[u2190-u2195]|u203B|[u2010u2015u2018u2019u2025u2026u201Cu201Du2225u2260]|[u0391-u0451]|[u00A7u00A8u00B1u00B4u00D7u00F7])+";r=r.replace(/u/g,"\\u");const o="(?:(?![A-Z0-9 $%*+\\-./:]|"+r+")(?:.|[\r\n]))+";e.KANJI=new RegExp(r,"g"),e.BYTE_KANJI=new RegExp("[^A-Z0-9 $%*+\\-./:]+","g"),e.BYTE=new RegExp(o,"g"),e.NUMERIC=new RegExp(n,"g"),e.ALPHANUMERIC=new RegExp("[A-Z $%*+\\-./:]+","g");const i=new RegExp("^"+r+"$"),s=new RegExp("^[0-9]+$"),u=new RegExp("^[A-Z0-9 $%*+\\-./:]+$");e.testKanji=function(t){return i.test(t)},e.testNumeric=function(t){return s.test(t)},e.testAlphanumeric=function(t){return u.test(t)}},453:function(t,e){function n(t){if("number"==typeof t&&(t=t.toString()),"string"!=typeof t)throw new Error("Color should be defined as hex string");let e=t.slice().replace("#","").split("");if(e.length<3||5===e.length||e.length>8)throw new Error("Invalid hex color: "+t);3!==e.length&&4!==e.length||(e=Array.prototype.concat.apply([],e.map((function(t){return[t,t]})))),6===e.length&&e.push("F","F");const n=parseInt(e.join(""),16);return{r:n>>24&255,g:n>>16&255,b:n>>8&255,a:255&n,hex:"#"+e.slice(0,6).join("")}}e.getOptions=function(t){t||(t={}),t.color||(t.color={});const e=void 0===t.margin||null===t.margin||t.margin<0?4:t.margin,r=t.width&&t.width>=21?t.width:void 0,o=t.scale||4;return{width:r,scale:r?4:o,margin:e,color:{dark:n(t.color.dark||"#000000ff"),light:n(t.color.light||"#ffffffff")},type:t.type,rendererOpts:t.rendererOpts||{}}},e.getScale=function(t,e){return e.width&&e.width>=t+2*e.margin?e.width/(t+2*e.margin):e.scale},e.getImageWidth=function(t,n){const r=e.getScale(t,n);return Math.floor((t+2*n.margin)*r)},e.qrToImageData=function(t,n,r){const o=n.modules.size,i=n.modules.data,s=e.getScale(o,r),u=Math.floor((o+2*r.margin)*s),a=r.margin*s,c=[r.color.light,r.color.dark];for(let e=0;e<u;e++)for(let n=0;n<u;n++){let l=4*(e*u+n),f=r.color.light;if(e>=a&&n>=a&&e<u-a&&n<u-a){f=c[i[Math.floor((e-a)/s)*o+Math.floor((n-a)/s)]?1:0]}t[l++]=f.r,t[l++]=f.g,t[l++]=f.b,t[l]=f.a}}},464:function(t,e,n){const r=n(561),o=n(562),i=n(580),s=n(581);function u(t,e,n,i,s){const u=[].slice.call(arguments,1),a=u.length,c="function"==typeof u[a-1];if(!c&&!r())throw new Error("Callback required as last argument");if(!c){if(a<1)throw new Error("Too few arguments provided");return 1===a?(n=e,e=i=void 0):2!==a||e.getContext||(i=n,n=e,e=void 0),new Promise((function(r,s){try{const s=o.create(n,i);r(t(s,e,i))}catch(t){s(t)}}))}if(a<2)throw new Error("Too few arguments provided");2===a?(s=n,n=e,e=i=void 0):3===a&&(e.getContext&&void 0===s?(s=i,i=void 0):(s=i,i=n,n=e,e=void 0));try{const r=o.create(n,i);s(null,t(r,e,i))}catch(t){s(t)}}e.create=o.create,e.toCanvas=u.bind(null,i.render),e.toDataURL=u.bind(null,i.renderToDataURL),e.toString=u.bind(null,(function(t,e,n){return s.render(t,n)}))},561:function(t,e){t.exports=function(){return"function"==typeof Promise&&Promise.prototype&&Promise.prototype.then}},562:function(t,e,n){const r=n(172),o=n(387),i=n(563),s=n(564),u=n(565),a=n(566),c=n(567),l=n(450),f=n(568),h=n(571),d=n(572),g=n(173),p=n(573);function w(t,e,n){const r=t.size,o=d.getEncodedBits(e,n);let i,s;for(i=0;i<15;i++)s=1==(o>>i&1),i<6?t.set(i,8,s,!0):i<8?t.set(i+1,8,s,!0):t.set(r-15+i,8,s,!0),i<8?t.set(8,r-i-1,s,!0):i<9?t.set(8,15-i-1+1,s,!0):t.set(8,15-i-1,s,!0);t.set(r-8,8,1,!0)}function m(t,e,n){const o=new i;n.forEach((function(e){o.put(e.mode.bit,4),o.put(e.getLength(),g.getCharCountIndicator(e.mode,t)),e.write(o)}));const s=8*(r.getSymbolTotalCodewords(t)-l.getTotalCodewordsCount(t,e));for(o.getLengthInBits()+4<=s&&o.put(0,4);o.getLengthInBits()%8!=0;)o.putBit(0);const u=(s-o.getLengthInBits())/8;for(let t=0;t<u;t++)o.put(t%2?17:236,8);return function(t,e,n){const o=r.getSymbolTotalCodewords(e),i=l.getTotalCodewordsCount(e,n),s=o-i,u=l.getBlocksCount(e,n),a=u-o%u,c=Math.floor(o/u),h=Math.floor(s/u),d=h+1,g=c-h,p=new f(g);let w=0;const m=new Array(u),y=new Array(u);let E=0;const A=new Uint8Array(t.buffer);for(let t=0;t<u;t++){const e=t<a?h:d;m[t]=A.slice(w,w+e),y[t]=p.encode(m[t]),w+=e,E=Math.max(E,e)}const v=new Uint8Array(o);let C,B,I=0;for(C=0;C<E;C++)for(B=0;B<u;B++)C<m[B].length&&(v[I++]=m[B][C]);for(C=0;C<g;C++)for(B=0;B<u;B++)v[I++]=y[B][C];return v}(o,t,e)}function y(t,e,n,o){let i;if(Array.isArray(t))i=p.fromArray(t);else{if("string"!=typeof t)throw new Error("Invalid data");{let r=e;if(!r){const e=p.rawSplit(t);r=h.getBestVersionForData(e,n)}i=p.fromString(t,r||40)}}const l=h.getBestVersionForData(i,n);if(!l)throw new Error("The amount of data is too big to be stored in a QR Code");if(e){if(e<l)throw new Error("\nThe chosen QR Code version cannot contain this amount of data.\nMinimum version required to store current data is: "+l+".\n")}else e=l;const f=m(e,n,i),d=r.getSymbolSize(e),g=new s(d);return function(t,e){const n=t.size,r=a.getPositions(e);for(let e=0;e<r.length;e++){const o=r[e][0],i=r[e][1];for(let e=-1;e<=7;e++)if(!(o+e<=-1||n<=o+e))for(let r=-1;r<=7;r++)i+r<=-1||n<=i+r||(e>=0&&e<=6&&(0===r||6===r)||r>=0&&r<=6&&(0===e||6===e)||e>=2&&e<=4&&r>=2&&r<=4?t.set(o+e,i+r,!0,!0):t.set(o+e,i+r,!1,!0))}}(g,e),function(t){const e=t.size;for(let n=8;n<e-8;n++){const e=n%2==0;t.set(n,6,e,!0),t.set(6,n,e,!0)}}(g),function(t,e){const n=u.getPositions(e);for(let e=0;e<n.length;e++){const r=n[e][0],o=n[e][1];for(let e=-2;e<=2;e++)for(let n=-2;n<=2;n++)-2===e||2===e||-2===n||2===n||0===e&&0===n?t.set(r+e,o+n,!0,!0):t.set(r+e,o+n,!1,!0)}}(g,e),w(g,n,0),e>=7&&function(t,e){const n=t.size,r=h.getEncodedBits(e);let o,i,s;for(let e=0;e<18;e++)o=Math.floor(e/3),i=e%3+n-8-3,s=1==(r>>e&1),t.set(o,i,s,!0),t.set(i,o,s,!0)}(g,e),function(t,e){const n=t.size;let r=-1,o=n-1,i=7,s=0;for(let u=n-1;u>0;u-=2)for(6===u&&u--;;){for(let n=0;n<2;n++)if(!t.isReserved(o,u-n)){let r=!1;s<e.length&&(r=1==(e[s]>>>i&1)),t.set(o,u-n,r),i--,-1===i&&(s++,i=7)}if(o+=r,o<0||n<=o){o-=r,r=-r;break}}}(g,f),isNaN(o)&&(o=c.getBestMask(g,w.bind(null,g,n))),c.applyMask(o,g),w(g,n,o),{modules:g,version:e,errorCorrectionLevel:n,maskPattern:o,segments:i}}e.create=function(t,e){if(void 0===t||""===t)throw new Error("No input text");let n,i,s=o.M;return void 0!==e&&(s=o.from(e.errorCorrectionLevel,o.M),n=h.from(e.version),i=c.from(e.maskPattern),e.toSJISFunc&&r.setToSJISFunction(e.toSJISFunc)),y(t,n,s,i)}},563:function(t,e){function n(){this.buffer=[],this.length=0}n.prototype={get:function(t){const e=Math.floor(t/8);return 1==(this.buffer[e]>>>7-t%8&1)},put:function(t,e){for(let n=0;n<e;n++)this.putBit(1==(t>>>e-n-1&1))},getLengthInBits:function(){return this.length},putBit:function(t){const e=Math.floor(this.length/8);this.buffer.length<=e&&this.buffer.push(0),t&&(this.buffer[e]|=128>>>this.length%8),this.length++}},t.exports=n},564:function(t,e){function n(t){if(!t||t<1)throw new Error("BitMatrix size must be defined and greater than 0");this.size=t,this.data=new Uint8Array(t*t),this.reservedBit=new Uint8Array(t*t)}n.prototype.set=function(t,e,n,r){const o=t*this.size+e;this.data[o]=n,r&&(this.reservedBit[o]=!0)},n.prototype.get=function(t,e){return this.data[t*this.size+e]},n.prototype.xor=function(t,e,n){this.data[t*this.size+e]^=n},n.prototype.isReserved=function(t,e){return this.reservedBit[t*this.size+e]},t.exports=n},565:function(t,e,n){const r=n(172).getSymbolSize;e.getRowColCoords=function(t){if(1===t)return[];const e=Math.floor(t/7)+2,n=r(t),o=145===n?26:2*Math.ceil((n-13)/(2*e-2)),i=[n-7];for(let t=1;t<e-1;t++)i[t]=i[t-1]-o;return i.push(6),i.reverse()},e.getPositions=function(t){const n=[],r=e.getRowColCoords(t),o=r.length;for(let t=0;t<o;t++)for(let e=0;e<o;e++)0===t&&0===e||0===t&&e===o-1||t===o-1&&0===e||n.push([r[t],r[e]]);return n}},566:function(t,e,n){const r=n(172).getSymbolSize;e.getPositions=function(t){const e=r(t);return[[0,0],[e-7,0],[0,e-7]]}},567:function(t,e){e.Patterns={PATTERN000:0,PATTERN001:1,PATTERN010:2,PATTERN011:3,PATTERN100:4,PATTERN101:5,PATTERN110:6,PATTERN111:7};const n=3,r=3,o=40,i=10;function s(t,n,r){switch(t){case e.Patterns.PATTERN000:return(n+r)%2==0;case e.Patterns.PATTERN001:return n%2==0;case e.Patterns.PATTERN010:return r%3==0;case e.Patterns.PATTERN011:return(n+r)%3==0;case e.Patterns.PATTERN100:return(Math.floor(n/2)+Math.floor(r/3))%2==0;case e.Patterns.PATTERN101:return n*r%2+n*r%3==0;case e.Patterns.PATTERN110:return(n*r%2+n*r%3)%2==0;case e.Patterns.PATTERN111:return(n*r%3+(n+r)%2)%2==0;default:throw new Error("bad maskPattern:"+t)}}e.isValid=function(t){return null!=t&&""!==t&&!isNaN(t)&&t>=0&&t<=7},e.from=function(t){return e.isValid(t)?parseInt(t,10):void 0},e.getPenaltyN1=function(t){const e=t.size;let r=0,o=0,i=0,s=null,u=null;for(let a=0;a<e;a++){o=i=0,s=u=null;for(let c=0;c<e;c++){let e=t.get(a,c);e===s?o++:(o>=5&&(r+=n+(o-5)),s=e,o=1),e=t.get(c,a),e===u?i++:(i>=5&&(r+=n+(i-5)),u=e,i=1)}o>=5&&(r+=n+(o-5)),i>=5&&(r+=n+(i-5))}return r},e.getPenaltyN2=function(t){const e=t.size;let n=0;for(let r=0;r<e-1;r++)for(let o=0;o<e-1;o++){const e=t.get(r,o)+t.get(r,o+1)+t.get(r+1,o)+t.get(r+1,o+1);4!==e&&0!==e||n++}return n*r},e.getPenaltyN3=function(t){const e=t.size;let n=0,r=0,i=0;for(let o=0;o<e;o++){r=i=0;for(let s=0;s<e;s++)r=r<<1&2047|t.get(o,s),s>=10&&(1488===r||93===r)&&n++,i=i<<1&2047|t.get(s,o),s>=10&&(1488===i||93===i)&&n++}return n*o},e.getPenaltyN4=function(t){let e=0;const n=t.data.length;for(let r=0;r<n;r++)e+=t.data[r];return Math.abs(Math.ceil(100*e/n/5)-10)*i},e.applyMask=function(t,e){const n=e.size;for(let r=0;r<n;r++)for(let o=0;o<n;o++)e.isReserved(o,r)||e.xor(o,r,s(t,o,r))},e.getBestMask=function(t,n){const r=Object.keys(e.Patterns).length;let o=0,i=1/0;for(let s=0;s<r;s++){n(s),e.applyMask(s,t);const r=e.getPenaltyN1(t)+e.getPenaltyN2(t)+e.getPenaltyN3(t)+e.getPenaltyN4(t);e.applyMask(s,t),r<i&&(i=r,o=s)}return o}},568:function(t,e,n){const r=n(569);function o(t){this.genPoly=void 0,this.degree=t,this.degree&&this.initialize(this.degree)}o.prototype.initialize=function(t){this.degree=t,this.genPoly=r.generateECPolynomial(this.degree)},o.prototype.encode=function(t){if(!this.genPoly)throw new Error("Encoder not initialized");const e=new Uint8Array(t.length+this.degree);e.set(t);const n=r.mod(e,this.genPoly),o=this.degree-n.length;if(o>0){const t=new Uint8Array(this.degree);return t.set(n,o),t}return n},t.exports=o},569:function(t,e,n){const r=n(570);e.mul=function(t,e){const n=new Uint8Array(t.length+e.length-1);for(let o=0;o<t.length;o++)for(let i=0;i<e.length;i++)n[o+i]^=r.mul(t[o],e[i]);return n},e.mod=function(t,e){let n=new Uint8Array(t);for(;n.length-e.length>=0;){const t=n[0];for(let o=0;o<e.length;o++)n[o]^=r.mul(e[o],t);let o=0;for(;o<n.length&&0===n[o];)o++;n=n.slice(o)}return n},e.generateECPolynomial=function(t){let n=new Uint8Array([1]);for(let o=0;o<t;o++)n=e.mul(n,new Uint8Array([1,r.exp(o)]));return n}},570:function(t,e){const n=new Uint8Array(512),r=new Uint8Array(256);!function(){let t=1;for(let e=0;e<255;e++)n[e]=t,r[t]=e,t<<=1,256&t&&(t^=285);for(let t=255;t<512;t++)n[t]=n[t-255]}(),e.log=function(t){if(t<1)throw new Error("log("+t+")");return r[t]},e.exp=function(t){return n[t]},e.mul=function(t,e){return 0===t||0===e?0:n[r[t]+r[e]]}},571:function(t,e,n){const r=n(172),o=n(450),i=n(387),s=n(173),u=n(451),a=r.getBCHDigit(7973);function c(t,e){return s.getCharCountIndicator(t,e)+4}function l(t,e){let n=0;return t.forEach((function(t){const r=c(t.mode,e);n+=r+t.getBitsLength()})),n}e.from=function(t,e){return u.isValid(t)?parseInt(t,10):e},e.getCapacity=function(t,e,n){if(!u.isValid(t))throw new Error("Invalid QR Code version");void 0===n&&(n=s.BYTE);const i=8*(r.getSymbolTotalCodewords(t)-o.getTotalCodewordsCount(t,e));if(n===s.MIXED)return i;const a=i-c(n,t);switch(n){case s.NUMERIC:return Math.floor(a/10*3);case s.ALPHANUMERIC:return Math.floor(a/11*2);case s.KANJI:return Math.floor(a/13);case s.BYTE:default:return Math.floor(a/8)}},e.getBestVersionForData=function(t,n){let r;const o=i.from(n,i.M);if(Array.isArray(t)){if(t.length>1)return function(t,n){for(let r=1;r<=40;r++)if(l(t,r)<=e.getCapacity(r,n,s.MIXED))return r}(t,o);if(0===t.length)return 1;r=t[0]}else r=t;return function(t,n,r){for(let o=1;o<=40;o++)if(n<=e.getCapacity(o,r,t))return o}(r.mode,r.getLength(),o)},e.getEncodedBits=function(t){if(!u.isValid(t)||t<7)throw new Error("Invalid QR Code version");let e=t<<12;for(;r.getBCHDigit(e)-a>=0;)e^=7973<<r.getBCHDigit(e)-a;return t<<12|e}},572:function(t,e,n){const r=n(172),o=r.getBCHDigit(1335);e.getEncodedBits=function(t,e){const n=t.bit<<3|e;let i=n<<10;for(;r.getBCHDigit(i)-o>=0;)i^=1335<<r.getBCHDigit(i)-o;return 21522^(n<<10|i)}},573:function(t,e,n){const r=n(173),o=n(574),i=n(575),s=n(576),u=n(578),a=n(452),c=n(172),l=n(579);function f(t){return unescape(encodeURIComponent(t)).length}function h(t,e,n){const r=[];let o;for(;null!==(o=t.exec(n));)r.push({data:o[0],index:o.index,mode:e,length:o[0].length});return r}function d(t){const e=h(a.NUMERIC,r.NUMERIC,t),n=h(a.ALPHANUMERIC,r.ALPHANUMERIC,t);let o,i;c.isKanjiModeEnabled()?(o=h(a.BYTE,r.BYTE,t),i=h(a.KANJI,r.KANJI,t)):(o=h(a.BYTE_KANJI,r.BYTE,t),i=[]);return e.concat(n,o,i).sort((function(t,e){return t.index-e.index})).map((function(t){return{data:t.data,mode:t.mode,length:t.length}}))}function g(t,e){switch(e){case r.NUMERIC:return o.getBitsLength(t);case r.ALPHANUMERIC:return i.getBitsLength(t);case r.KANJI:return u.getBitsLength(t);case r.BYTE:return s.getBitsLength(t)}}function p(t,e){let n;const a=r.getBestModeForData(t);if(n=r.from(e,a),n!==r.BYTE&&n.bit<a.bit)throw new Error('"'+t+'" cannot be encoded with mode '+r.toString(n)+".\n Suggested mode is: "+r.toString(a));switch(n!==r.KANJI||c.isKanjiModeEnabled()||(n=r.BYTE),n){case r.NUMERIC:return new o(t);case r.ALPHANUMERIC:return new i(t);case r.KANJI:return new u(t);case r.BYTE:return new s(t)}}e.fromArray=function(t){return t.reduce((function(t,e){return"string"==typeof e?t.push(p(e,null)):e.data&&t.push(p(e.data,e.mode)),t}),[])},e.fromString=function(t,n){const o=function(t,e){const n={},o={start:{}};let i=["start"];for(let s=0;s<t.length;s++){const u=t[s],a=[];for(let t=0;t<u.length;t++){const c=u[t],l=""+s+t;a.push(l),n[l]={node:c,lastCount:0},o[l]={};for(let t=0;t<i.length;t++){const s=i[t];n[s]&&n[s].node.mode===c.mode?(o[s][l]=g(n[s].lastCount+c.length,c.mode)-g(n[s].lastCount,c.mode),n[s].lastCount+=c.length):(n[s]&&(n[s].lastCount=c.length),o[s][l]=g(c.length,c.mode)+4+r.getCharCountIndicator(c.mode,e))}}i=a}for(let t=0;t<i.length;t++)o[i[t]].end=0;return{map:o,table:n}}(function(t){const e=[];for(let n=0;n<t.length;n++){const o=t[n];switch(o.mode){case r.NUMERIC:e.push([o,{data:o.data,mode:r.ALPHANUMERIC,length:o.length},{data:o.data,mode:r.BYTE,length:o.length}]);break;case r.ALPHANUMERIC:e.push([o,{data:o.data,mode:r.BYTE,length:o.length}]);break;case r.KANJI:e.push([o,{data:o.data,mode:r.BYTE,length:f(o.data)}]);break;case r.BYTE:e.push([{data:o.data,mode:r.BYTE,length:f(o.data)}])}}return e}(d(t,c.isKanjiModeEnabled())),n),i=l.find_path(o.map,"start","end"),s=[];for(let t=1;t<i.length-1;t++)s.push(o.table[i[t]].node);return e.fromArray(function(t){return t.reduce((function(t,e){const n=t.length-1>=0?t[t.length-1]:null;return n&&n.mode===e.mode?(t[t.length-1].data+=e.data,t):(t.push(e),t)}),[])}(s))},e.rawSplit=function(t){return e.fromArray(d(t,c.isKanjiModeEnabled()))}},574:function(t,e,n){const r=n(173);function o(t){this.mode=r.NUMERIC,this.data=t.toString()}o.getBitsLength=function(t){return 10*Math.floor(t/3)+(t%3?t%3*3+1:0)},o.prototype.getLength=function(){return this.data.length},o.prototype.getBitsLength=function(){return o.getBitsLength(this.data.length)},o.prototype.write=function(t){let e,n,r;for(e=0;e+3<=this.data.length;e+=3)n=this.data.substr(e,3),r=parseInt(n,10),t.put(r,10);const o=this.data.length-e;o>0&&(n=this.data.substr(e),r=parseInt(n,10),t.put(r,3*o+1))},t.exports=o},575:function(t,e,n){const r=n(173),o=["0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"," ","$","%","*","+","-",".","/",":"];function i(t){this.mode=r.ALPHANUMERIC,this.data=t}i.getBitsLength=function(t){return 11*Math.floor(t/2)+t%2*6},i.prototype.getLength=function(){return this.data.length},i.prototype.getBitsLength=function(){return i.getBitsLength(this.data.length)},i.prototype.write=function(t){let e;for(e=0;e+2<=this.data.length;e+=2){let n=45*o.indexOf(this.data[e]);n+=o.indexOf(this.data[e+1]),t.put(n,11)}this.data.length%2&&t.put(o.indexOf(this.data[e]),6)},t.exports=i},576:function(t,e,n){const r=n(577),o=n(173);function i(t){this.mode=o.BYTE,"string"==typeof t&&(t=r(t)),this.data=new Uint8Array(t)}i.getBitsLength=function(t){return 8*t},i.prototype.getLength=function(){return this.data.length},i.prototype.getBitsLength=function(){return i.getBitsLength(this.data.length)},i.prototype.write=function(t){for(let e=0,n=this.data.length;e<n;e++)t.put(this.data[e],8)},t.exports=i},577:function(t,e,n){"use strict";t.exports=function(t){for(var e=[],n=t.length,r=0;r<n;r++){var o=t.charCodeAt(r);if(o>=55296&&o<=56319&&n>r+1){var i=t.charCodeAt(r+1);i>=56320&&i<=57343&&(o=1024*(o-55296)+i-56320+65536,r+=1)}o<128?e.push(o):o<2048?(e.push(o>>6|192),e.push(63&o|128)):o<55296||o>=57344&&o<65536?(e.push(o>>12|224),e.push(o>>6&63|128),e.push(63&o|128)):o>=65536&&o<=1114111?(e.push(o>>18|240),e.push(o>>12&63|128),e.push(o>>6&63|128),e.push(63&o|128)):e.push(239,191,189)}return new Uint8Array(e).buffer}},578:function(t,e,n){const r=n(173),o=n(172);function i(t){this.mode=r.KANJI,this.data=t}i.getBitsLength=function(t){return 13*t},i.prototype.getLength=function(){return this.data.length},i.prototype.getBitsLength=function(){return i.getBitsLength(this.data.length)},i.prototype.write=function(t){let e;for(e=0;e<this.data.length;e++){let n=o.toSJIS(this.data[e]);if(n>=33088&&n<=40956)n-=33088;else{if(!(n>=57408&&n<=60351))throw new Error("Invalid SJIS character: "+this.data[e]+"\nMake sure your charset is UTF-8");n-=49472}n=192*(n>>>8&255)+(255&n),t.put(n,13)}},t.exports=i},579:function(t,e,n){"use strict";var r={single_source_shortest_paths:function(t,e,n){var o={},i={};i[e]=0;var s,u,a,c,l,f,h,d=r.PriorityQueue.make();for(d.push(e,0);!d.empty();)for(a in u=(s=d.pop()).value,c=s.cost,l=t[u]||{})l.hasOwnProperty(a)&&(f=c+l[a],h=i[a],(void 0===i[a]||h>f)&&(i[a]=f,d.push(a,f),o[a]=u));if(void 0!==n&&void 0===i[n]){var g=["Could not find a path from ",e," to ",n,"."].join("");throw new Error(g)}return o},extract_shortest_path_from_predecessor_list:function(t,e){for(var n=[],r=e;r;)n.push(r),t[r],r=t[r];return n.reverse(),n},find_path:function(t,e,n){var o=r.single_source_shortest_paths(t,e,n);return r.extract_shortest_path_from_predecessor_list(o,n)},PriorityQueue:{make:function(t){var e,n=r.PriorityQueue,o={};for(e in t=t||{},n)n.hasOwnProperty(e)&&(o[e]=n[e]);return o.queue=[],o.sorter=t.sorter||n.default_sorter,o},default_sorter:function(t,e){return t.cost-e.cost},push:function(t,e){var n={value:t,cost:e};this.queue.push(n),this.queue.sort(this.sorter)},pop:function(){return this.queue.shift()},empty:function(){return 0===this.queue.length}}};t.exports=r},580:function(t,e,n){const r=n(453);e.render=function(t,e,n){let o=n,i=e;void 0!==o||e&&e.getContext||(o=e,e=void 0),e||(i=function(){try{return document.createElement("canvas")}catch(t){throw new Error("You need to specify a canvas element")}}()),o=r.getOptions(o);const s=r.getImageWidth(t.modules.size,o),u=i.getContext("2d"),a=u.createImageData(s,s);return r.qrToImageData(a.data,t,o),function(t,e,n){t.clearRect(0,0,e.width,e.height),e.style||(e.style={}),e.height=n,e.width=n,e.style.height=n+"px",e.style.width=n+"px"}(u,i,s),u.putImageData(a,0,0),i},e.renderToDataURL=function(t,n,r){let o=r;void 0!==o||n&&n.getContext||(o=n,n=void 0),o||(o={});const i=e.render(t,n,o),s=o.type||"image/png",u=o.rendererOpts||{};return i.toDataURL(s,u.quality)}},581:function(t,e,n){const r=n(453);function o(t,e){const n=t.a/255,r=e+'="'+t.hex+'"';return n<1?r+" "+e+'-opacity="'+n.toFixed(2).slice(1)+'"':r}function i(t,e,n){let r=t+e;return void 0!==n&&(r+=" "+n),r}e.render=function(t,e,n){const s=r.getOptions(e),u=t.modules.size,a=t.modules.data,c=u+2*s.margin,l=s.color.light.a?"<path "+o(s.color.light,"fill")+' d="M0 0h'+c+"v"+c+'H0z"/>':"",f="<path "+o(s.color.dark,"stroke")+' d="'+function(t,e,n){let r="",o=0,s=!1,u=0;for(let a=0;a<t.length;a++){const c=Math.floor(a%e),l=Math.floor(a/e);c||s||(s=!0),t[a]?(u++,a>0&&c>0&&t[a-1]||(r+=s?i("M",c+n,.5+l+n):i("m",o,0),o=0,s=!1),c+1<e&&t[a+1]||(r+=i("h",u),u=0)):o++}return r}(a,u,s.margin)+'"/>',h='viewBox="0 0 '+c+" "+c+'"',d='<svg xmlns="http://www.w3.org/2000/svg" '+(s.width?'width="'+s.width+'" height="'+s.width+'" ':"")+h+' shape-rendering="crispEdges">'+l+f+"</svg>\n";return"function"==typeof n&&n(null,d),d}}});