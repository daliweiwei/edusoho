!function(e){function t(t){for(var r,o,l=t[0],c=t[1],u=t[2],p=0,y=[];p<l.length;p++)o=l[p],Object.prototype.hasOwnProperty.call(i,o)&&i[o]&&y.push(i[o][0]),i[o]=0;for(r in c)Object.prototype.hasOwnProperty.call(c,r)&&(e[r]=c[r]);for(s&&s(t);y.length;)y.shift()();return a.push.apply(a,u||[]),n()}function n(){for(var e,t=0;t<a.length;t++){for(var n=a[t],r=!0,l=1;l<n.length;l++){var c=n[l];0!==i[c]&&(r=!1)}r&&(a.splice(t--,1),e=o(o.s=n[0]))}return e}var r={},i={51:0},a=[];function o(t){if(r[t])return r[t].exports;var n=r[t]={i:t,l:!1,exports:{}};return e[t].call(n.exports,n,n.exports,o),n.l=!0,n.exports}o.m=e,o.c=r,o.d=function(e,t,n){o.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},o.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.t=function(e,t){if(1&t&&(e=o(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(o.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)o.d(n,r,function(t){return e[t]}.bind(null,r));return n},o.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(t,"a",t),t},o.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},o.p="/static-dist/";var l=window.webpackJsonp=window.webpackJsonp||[],c=l.push.bind(l);l.push=t,l=l.slice();for(var u=0;u<l.length;u++)t(l[u]);var s=c;a.push([1517,0]),n()}({1517:function(e,t,n){"use strict";n.r(t);var r=n(4),i=n.n(r),a=n(5),o=n.n(a),l=(n(118),function(){function e(t){i()(this,e),this.container=t,this.interval=$(this.container).data("watchTimeSec"),this.playerCounter=0,this.activityId=$(this.container).data("id")}return o()(e,[{key:"addVideoPlayerCounter",value:function(e,t){var n=store.get("activity_id_"+this.activityId+"_playing_counter");if(n||(this.playerCounter=0),!t||!t.playing)return!1;n>=this.interval?this.watching(e):t.playing&&this.playerCounter++,store.set("activity_id_"+this.activityId+"_playing_counter",this.playerCounter)}},{key:"watching",value:function(e){var t=store.get("activity_id_"+this.activityId+"_playing_counter");console.log(t),e.emit("watching",{watchTime:t}).then((function(){var e=$("#video-content").data("watchUrl");$.post(e,(function(e){e&&"error"==e.status&&window.location.reload()}))})).catch((function(e){console.error(e)})),this.playerCounter=0}}]),e}()),c=n(125),u=n.n(c),s=n(148),p=n(200);new(function(){function e(t){i()(this,e),this.player={},this.intervalId=null,this.recorder=t,this.emitter=new p.a}return o()(e,[{key:"play",value:function(){$("#swf-player").length?this._playerSwf():this._playVideo(),this.record()}},{key:"record",value:function(){var e=this;this.intervalId=setInterval((function(){e.recorder.addVideoPlayerCounter(e.emitter,e.player)}),1e3)}},{key:"getPlay",value:function(){return this.player}},{key:"_playerSwf",value:function(){var e="swf-player";u.a.embedSWF($("#"+e).data("url"),e,"100%","100%","9.0.0",null,null,{wmode:"opaque",allowFullScreen:"true"})}},{key:"_playVideo",value:function(){var e=this,t=new s.a({name:"partner",project:"PlayerProject",children:[],type:"parent"});t.on("ended",(function(t){e.player.playing=!1,t.playerMsg.playEnd=!0,e._onFinishLearnTask(t)})),t.on("playing",(function(t){e.player.playing=!0})),t.on("paused",(function(t){e.player.playing=!1,e.recorder.watching(e.emitter)})),t.on("timechange",(function(t){e.player.currentTime=t.currentTime}))}},{key:"_onFinishLearnTask",value:function(e){var t=this;console.log("video-play.js",e),this.emitter.emit("finish",{data:e}).then((function(){clearInterval(t.intervalId)})).catch((function(e){console.error(e)}))}}]),e}())(new l("#video-content")).play()}});