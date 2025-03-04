import MobileClassroom from './MobileClassroom';
import Vant from 'vant';

Vue.config.productionTip = false;
Vue.use(Vant);

Vue.filter('trans', function (value, params) {
  if (!value) return '';
  return Translator.trans(value, params);
});

if($('[name="isWeixin"]').val() || $('[name="isMobile"]').val()) {
  new Vue({
    render: createElement => createElement(MobileClassroom)
  }).$mount('#app');
}

$('#free').on('click', function(event) {
  window.location.href = $(this).val();
});