!function(l){function e(e){for(var t,n,i=e[0],a=e[1],s=e[2],o=0,c=[];o<i.length;o++)n=i[o],Object.prototype.hasOwnProperty.call(u,n)&&u[n]&&c.push(u[n][0]),u[n]=0;for(t in a)Object.prototype.hasOwnProperty.call(a,t)&&(l[t]=a[t]);for(h&&h(e);c.length;)c.shift()();return d.push.apply(d,s||[]),r()}function r(){for(var e,t=0;t<d.length;t++){for(var n=d[t],i=!0,a=1;a<n.length;a++){var s=n[a];0!==u[s]&&(i=!1)}i&&(d.splice(t--,1),e=o(o.s=n[0]))}return e}var n={},u={313:0},d=[];function o(e){if(n[e])return n[e].exports;var t=n[e]={i:e,l:!1,exports:{}};return l[e].call(t.exports,t,t.exports,o),t.l=!0,t.exports}o.m=l,o.c=n,o.d=function(e,t,n){o.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},o.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.t=function(t,e){if(1&e&&(t=o(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(o.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var i in t)o.d(n,i,function(e){return t[e]}.bind(null,i));return n},o.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(t,"a",t),t},o.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},o.p="/static-dist/";var t=window.webpackJsonp=window.webpackJsonp||[],i=t.push.bind(t);t.push=e,t=t.slice();for(var a=0;a<t.length;a++)e(t[a]);var h=i;d.push([773,0]),r()}({159:function(e,t,n){"use strict";n.d(t,"a",function(){return r});var i=n(29),a=n.n(i),s=n(0),o=n.n(s),c=n(1),l=n.n(c),r=function(){function t(e){o()(this,t),this.$elem=$(e),this.init(),this.selectMap={}}return l()(t,[{key:"init",value:function(){this.initEvent()}},{key:"initEvent",value:function(){var t=this;this.$elem.on("click",".js-checkbox",function(e){t.toggleItem(e),t.changeSelectedNum()}),this.$elem.on("click",".js-select-all",function(e){$(e.target).prop("checked")?(t.$elem.find(".js-select-all").prop("checked",!0),t.addItems()):(t.$elem.find(".js-select-all").prop("checked",!1),t.removeItems()),t.changeSelectedNum()})}},{key:"setOpts",value:function(e){var t=e.addCb,n=void 0===t?function(){}:t,i=e.removeCb,a=void 0===i?function(){}:i;this.addCb=n,this.removeCb=a}},{key:"getItem",value:function(e){return{id:e.data("id")}}},{key:"removeItem",value:function(e){var t=this.$elem.find('input[data-id="'.concat(e.id,'"]'));t.length&&t.prop("checked",!1),this.selectMap[e.id]&&delete this.selectMap[e.id]}},{key:"addItem",value:function(e){var t=$(e);t.prop("checked",!0),this.selectMap[t.data("id")]||(this.selectMap[t.data("id")]=!0)}},{key:"addItems",value:function(){var n=this;this.$elem.find(".js-checkbox").each(function(e,t){$(t).prop("checked")||(n.addItem(t),n.addCb&&n.addCb(t))})}},{key:"removeItems",value:function(){var i=this;this.$elem.find(".js-checkbox").each(function(e,t){var n;$(t).prop("checked")&&(n=i.getItem($(t)),i.removeItem(n),i.removeCb&&i.removeCb(t))})}},{key:"toggleItem",value:function(e){var t=$(e.currentTarget);t.prop("checked")?this.selectMap[t.data("id")]||(this.selectMap[t.data("id")]=!0,this.addCb&&this.addCb(t)):this.selectMap[t.data("id")]&&(delete this.selectMap[t.data("id")],this.removeCb&&this.removeCb(t))}},{key:"resetItems",value:function(){this.selectMap={}}},{key:"getObjectLength",value:function(){return a()(this.selectMap).length}},{key:"toJson",value:function(){return a()(this.selectMap)}},{key:"updateTable",value:function(){var n=this;this.$elem.find(".js-checkbox").each(function(e,t){n.selectMap[$(t).data("id")]&&$(t).prop("checked",!0)}),this.changeSelectedNum()}},{key:"changeSelectedNum",value:function(){0<this.$elem.find(".js-select-number").length&&this.$elem.find(".js-select-number").text(this.getObjectLength())}}]),t}()},773:function(e,t,n){"use strict";n.r(t);var i=n(18),a=n.n(i),s=n(29),o=n.n(s),c=n(6),l=n.n(c),r=n(0),u=n.n(r),d=n(1),h=n.n(d),v=n(159),f=n(98);new(function(){function e(){u()(this,e),this.element=$(".js-select-container"),this.table=$(".js-select-table"),this.$questionBankSelector=$(".js-question-bank"),this.renderUrl=this.table.data("url"),this.categoryContainer=$(".js-category-list"),this.selectTypeQuestion={},this.selector=new v.a(this.table),this.init()}return h()(e,[{key:"init",value:function(){var t=this;this.initEvent(),this.initQuestionBankSelector(),this.initQuestionType(),this.selector.setOpts({addCb:function(e){t.selectQuestion(e)},removeCb:function(e){t.removeQuestion(e)}}),this.initToolTip()}},{key:"initEvent",value:function(){var t=this;this.element.on("change",".js-question-bank",function(e){t.onChangeQuestionBank(e)}),this.element.on("click",".js-search-btn",function(e){t.onClickSearchBtn(e)}),this.element.on("click",".pagination li",function(e){t.onClickPagination(e)}),this.element.on("click",".js-category-search",function(e){t.onClickCategorySearch(e)}),this.element.on("click",".js-all-category-search",function(e){t.onClickAllCategorySearch(e)}),this.element.on("click",".js-clear-select",function(e){t.onClickClearSelect(e)}),$(".js-pick-btn").on("click",function(e){t.onClickPick(e)})}},{key:"initToolTip",value:function(){$("a[data-toggle=tooltip]").tooltip({container:"body"})}},{key:"initQuestionBankSelector",value:function(){0!==this.$questionBankSelector.length&&this.$questionBankSelector.select2({treeview:!0,dropdownAutoWidth:!0,treeviewInitState:"collapsed",placeholderOption:"first",formatResult:function(e){var t=Object(f.b)(e.text);return e.id?'<div class="select2-result-text"><span class="select2-match"></span><span><i class="es-icon es-icon-tiku"></i>'.concat(t,"</span></div>"):t},dropdownCss:{width:""}})}},{key:"initQuestionType",value:function(){var i=this;this.element.find(".js-list-item").each(function(e,t){var n=$(t).data("type");i.selectTypeQuestion[n]={}})}},{key:"selectQuestion",value:function(e){this.element.find(".js-select-number").text(this.selector.getObjectLength());var t=$(e).data("type"),n=$(e).data("id");this.selectTypeQuestion[t]&&(this.selectTypeQuestion[t][n]=!0,this.element.find(".js-select-"+t).text(this.getTypeQuestionLength(t)))}},{key:"removeQuestion",value:function(e){this.element.find(".js-select-number").text(this.selector.getObjectLength());var t=$(e).data("type"),n=$(e).data("id");this.selectTypeQuestion[t][n]&&(delete this.selectTypeQuestion[t][n],this.element.find(".js-select-"+t).text(this.getTypeQuestionLength(t)))}},{key:"onChangeQuestionBank",value:function(){var e,t=this.$questionBankSelector.select2("data").id;l()(t)&&(e=(e=this.$questionBankSelector.data("url")).replace(/[0-9]/,t),$.post(e,{isSelectBank:1},function(e){$("#attachment-modal").html(e)}).error(function(e){cd.message({type:"danger",message:e.responseJson.error.message})}))}},{key:"onClickClearSelect",value:function(){var i=this;this.element.find(".js-list-item").each(function(e,t){var n=$(t).data("type");i.selectTypeQuestion[n]={},i.element.find(".js-select-"+n).text(0)}),this.selector.removeItems(),this.selector.resetItems(),this.element.find(".js-select-number").text(0)}},{key:"onClickPick",value:function(e){var t,n=$(e.currentTarget).data("name");0!==this.selector.toJson().length?(this.cacheQuestionAndBank(),(t=this.element.parents(".modal")).trigger("selectQuestion",this.selectTypeQuestion),t.modal("hide"),$(".js-close-modal").trigger("click")):cd.message({type:"danger",message:Translator.trans("site.data.uncheck_name_hint",{name:n})})}},{key:"getTypeQuestionLength",value:function(e){return this.selectTypeQuestion[e]?o()(this.selectTypeQuestion[e]).length:0}},{key:"onClickSearchBtn",value:function(e){this.renderTable(),e.preventDefault()}},{key:"onClickPagination",value:function(e){var t=$(e.currentTarget);this.element.find(".js-page").val(t.data("page")),this.renderTable(!0),e.preventDefault()}},{key:"onClickCategorySearch",value:function(e){var t=$(e.currentTarget);this.categoryContainer.find(".js-active-set.active").removeClass("active"),t.addClass("active"),$(".js-category-choose").val(t.data("id")),this.renderTable()}},{key:"onClickAllCategorySearch",value:function(e){var t=$(e.currentTarget);this.categoryContainer.find(".js-active-set.active").removeClass("active"),t.addClass("active"),$(".js-category-choose").val(""),this.renderTable()}},{key:"cacheQuestionAndBank",value:function(){var e=$("#task-create-content-iframe").contents();e.find(".js-cached-question").text(a()(this.selectTypeQuestion));var t=this.$questionBankSelector.select2("data").id,n=e.find(".js-origin-bank"),i=e.find(".js-current-bank");""===$.trim(i.val())?n.val(t):n.val(i.val()),i.val(t)}},{key:"renderTable",value:function(e){e||this._resetPage();var t=this.element.find('[data-role="search-conditions"]').serialize()+"&page="+this.element.find(".js-page").val();t+="&exclude_ids="+$(".js-excludeIds").val(),this._loading();var n=this;$.ajax({type:"GET",url:this.renderUrl,data:t}).done(function(e){n.table.html(e),n.selector.updateTable(),n.initToolTip()}).fail(function(){n._loaded_error()})}},{key:"_loading",value:function(){var e='<div class="empty" colspan="10" style="color:#999;padding:80px;">'+Translator.trans("site.loading")+"</div>";this.table.html(e)}},{key:"_loaded_error",value:function(){var e='<div class="empty" colspan="10" style="color:#999;padding:80px;">'+Translator.trans("site.loading_error")+"</div>";this.table.html(e)}},{key:"_resetPage",value:function(){this.element.find(".js-page").val(1)}}]),e}())}});