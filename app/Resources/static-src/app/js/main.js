import Swiper from 'swiper';
import Cookies from 'js-cookie';

import 'codeages-design';
import 'common/tabs-lavalamp';
import 'common/card';
import 'common/ajax-event';
import 'common/bootstrap-modal-hack';
import RewardPointNotify from 'app/common/reward-point-notify';
import {isMobileDevice} from 'common/utils';
import notify from 'common/notify';
import './alert';
import 'echo-js';
import 'app/common/katex-render';

echo.init();

let rpn = new RewardPointNotify();
rpn.display();

if ($('#rewardPointNotify').length > 0) {
  let message = $('#rewardPointNotify').text();
  if (message) {
    notify('success', decodeURIComponent(message));
  }
}

$(document).on('click', '.js-handleLearnOnMessage', function (event) {
  event.preventDefault();
  notify('danger', decodeURIComponent(Translator.trans('validate.course.closed')));
});

$(document).on('click', '.js-handleClassroomOnMessage', function (event) {
  event.preventDefault();
  notify('danger', decodeURIComponent(Translator.trans('validate.classroom.closed')));
});

$(document).on('click', '.js-handleExerciseOnMessage', function (event) {
  event.preventDefault();
  notify('danger', decodeURIComponent(Translator.trans('validate.exercise.closed')));
});

$(document).on('click', '.js-handleLearnContentOnMessage', function (event) {
  event.preventDefault();
  notify('danger', decodeURIComponent(Translator.trans('validate.learn_content.closed')));
});

$('[data-toggle="popover"]').popover({
  html: true
});

$('[data-toggle="tooltip"]').tooltip({
  html: true,
});

if (app.scheduleCrontab) {
  $.post(app.scheduleCrontab);
}

$('i.hover-spin').mouseenter(function () {
  $(this).addClass('md-spin');
}).mouseleave(function () {
  $(this).removeClass('md-spin');
});

if ($('#announcements-alert').length && $('#announcements-alert .swiper-container .swiper-wrapper').children().length > 1) {
  let noticeSwiper = new Swiper('#announcements-alert .swiper-container', {
    speed: 300,
    loop: true,
    mode: 'vertical',
    autoplay: 5000,
    calculateHeight: true
  });
}

if (!isMobileDevice()) {
  $('body').on('mouseenter', 'li.nav-hover', function (event) {
    $(this).addClass('open');
  }).on('mouseleave', 'li.nav-hover', function (event) {
    $(this).removeClass('open');
  });
} else {
  $('li.nav-hover >a').attr('data-toggle', 'dropdown');
}

$('.js-search').focus(function () {
  $(this).prop('placeholder', '').addClass('active');
}).blur(function () {
  $(this).prop('placeholder', Translator.trans('site.search_hint')).removeClass('active');
});

$('select[name=\'language\']').change(function () {
  Cookies.set('locale', $('select[name=language]').val(), {'path': '/'});
  $('select[name=\'language\']').parents('form').trigger('submit');
});

let eventPost = function ($obj) {
  let postData = $obj.data();
  $.post($obj.data('url'), postData);
};

$('.event-report').each(function () {
  (function ($obj) {
    eventPost($obj);
  })($(this));
});

$('body').on('event-report', function (e, name) {
  let $obj = $(name);
  eventPost($obj);
});

$('.modal').on('hidden.bs.modal', function () {
  let $modal = $(this);
  if ($modal.find('.modal-dialog').data('clear')) {
    $modal.empty();
  }
});

if ($('.js-hidden-exception').length > 0) {
  let replacedExceptionHtml = $('.js-hidden-exception').html().replace(/\r?\n/g, '');
  let exception = $.parseJSON(replacedExceptionHtml);
  notify('danger', exception.message);
  if ($('.js-hidden-exception-trace').length > 0) {
    exception.trace = $('.js-hidden-exception-trace').html();
  }
  console.log('exception', exception);
}

$.ajax('/online/sample');

let pageQueryUrl = $('.js-advanced-paginator').data('url');
let currentPerPageCount = $('#currentPerPageCount').children('option:selected').val();
// 每页显示数量
$('#currentPerPageCount').on('change', function () {
  currentPerPageCount = $(this).val();
  window.location.href = pageQueryUrl + 'page=1&perpage=' + currentPerPageCount;
})

// 分页
$('.js-advanced-paginator a').on('click', function () {
  let page = $(this).data('page');
  window.location.href = pageQueryUrl + 'page=' + page + '&perpage=' + currentPerPageCount;
})

// 跳页
$('#jumpPage').on('blur', function () {
  let currentPage = $(this).data('currentPage');
  let lastPage = $(this).data('lastPage');
  let jumpPage = $(this).val();
  if (currentPage == jumpPage || jumpPage > lastPage) {
    $(this).val(currentPage);
  } else {
    window.location.href = pageQueryUrl + 'page=' + jumpPage + '&perpage=' + currentPerPageCount;
  }
});
