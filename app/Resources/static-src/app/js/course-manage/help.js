import {publish} from 'app/common/widget/publish';

export const closeCourse = () => {
  $('body').on('click', '.js-close-course', (evt) => {
    let $target = $(evt.currentTarget);
    cd.confirm({
      title: Translator.trans('site.close'),
      content: Translator.trans('course.manage.close_hint'),
      okText: Translator.trans('site.confirm'),
      cancelText: Translator.trans('site.cancel')
    }).on('ok', () => {
      $.post($target.data('checkUrl'), (data) => {
        if (data.warn) {
          cd.confirm({
            title: Translator.trans('site.close'),
            content: Translator.trans(data.message),
            okText: Translator.trans('site.confirm'),
            cancelText: Translator.trans('site.cancel')
          }).on('ok', () => {
            closeCourseAction($target);
          });
          return;
        }
        closeCourseAction($target);
      });
    });
  });
};

export const hideCourse = () => {
  $('body').on('click', '.js-hide-course', (evt) => {
    let $target = $(evt.currentTarget);
    cd.confirm({
      title: '<span class="es-icon es-icon-infooutline" style="color: #FAAD14; margin-right: 16px; font-size: 22px; line-height: 22px;"></span>' + Translator.trans('site.tips.delist'),
      content: Translator.trans('course.manage.hide_hint'),
      okText: Translator.trans('site.confirm'),
      cancelText: Translator.trans('site.cancel')
    }).on('ok', () => {
      hideCourseAction($target);
    });
  });
};

const hideCourseAction = ($target) => {
    $.post($target.data('url'), (data) => {
      if (data.success) {
        cd.message({type: 'success', message: Translator.trans('admin.courseSet.delist.success_hint')});
        window.location.reload();
      } else {
        cd.message({type: 'danger', message: Translator.trans('admin.courseSet.delist.fail_hint') + ':' + data.message});
      }
    });
}

export const showCourse = () => {
  $('body').on('click', '.js-show-course', (evt) => {
    let $target = $(evt.currentTarget);
    $.post($target.data('url'), (data) => {
      if (data.success) {
        cd.message({type: 'success', message: Translator.trans('course.manage.show_success_hint')});
        window.location.reload();
      } else {
        cd.message({type: 'danger', message: Translator.trans('course.manage.show_fail_hint') + ':' + data.message});
      }
    });
  });
};

const closeCourseAction = ($target) => {
  $.post($target.data('url'), (data) => {
    if (data.success) {
      cd.message({type: 'success', message: Translator.trans('course.manage.close_success_hint')});
      window.location.reload();
    } else {
      cd.message({type: 'danger', message: Translator.trans('course.manage.close_fail_hint') + ':' + data.message});
    }
  });
};

export const deleteCourse = () => {
  $('body').on('click', '.js-delete-course', (evt) => {
    let msg = 'course.manage.delete_hint';
    let status = null;
    $.ajax({
      type: 'post',
      url: $(evt.currentTarget).data('check-url'),
      async: false,
      success: function (data) {
        status = data.status;
        if (status === 'should_delete_mall_goods') {
          msg = 'course.manage.mall_goods_exist.delete_hint';
          if (data.redirect) {
            window.location.href = data.redirect;
          }
        }
        if (status === 'cannot_delete') {
          cd.message({type: 'danger', message: Translator.trans('mall.goods.exist.delete_fail_hint')});
        }
      }
    });
    if (status === 'cannot_delete') {
      return;
    }
    cd.confirm({
      title: Translator.trans('site.delete'),
      content: Translator.trans(msg),
      okText: Translator.trans('site.confirm'),
      cancelText: Translator.trans('site.cancel')
    }).on('ok', () => {
      $.post($(evt.currentTarget).data('url'), (data) => {
        if (data.success) {
          cd.message({type: 'success', message: Translator.trans('site.delete_success_hint')});
          if (data.redirect) {
            window.location.href = data.redirect;
          } else {
            window.location.reload();
          }
        } else {
          cd.message({type: 'danger', message: Translator.trans('site.delete_fail_hint') + ':' + data.message});
        }
      });
    });
  });
};

export const publishCourse = () => {
  const info = {
    title: 'course.manage.publish_title',
    hint: 'course.manage.publish_hint',
    success: 'course.manage.publish_success_hint',
    fail: 'course.manage.publish_fail_hint'
  };

  publish('.js-publish-course', info);
};

export const showSettings = () => {
  $('#sortable-list').on('click', '.js-item-content', (event) => {
    console.log('click');
    let $this = $(event.currentTarget);
    let $li = $this.closest('.js-task-manage-item');
    if ($li.hasClass('active')) {
      $li.removeClass('active').find('.js-settings-list').stop().slideUp(500);
    } else {
      $li.addClass('active').find('.js-settings-list').stop().slideDown(500);
      $li.siblings('.js-task-manage-item.active').removeClass('active').find('.js-settings-list').hide();
    }
  });
};

export const TabChange = () => {
  $('[data-role="tab"]').click(function (event) {
    let $this = $(this);
    $($this.data('tab-content')).removeClass('hidden').siblings('[data-role="tab-content"]').addClass('hidden');
  });
};


export const TaskListHeaderFixed = () => {
  let $header = $('.js-task-list-header');
  const $headerSlot = $('.js-task-list-header__slot');
  if (!$header.length) {
    return;
  }
  let headerTop = $header.offset().top;
  $(window).scroll(function (event) {
    if ($(window).scrollTop() >= headerTop) {
      $header.addClass('fixed');
      $headerSlot.removeClass('hidden');
    } else {
      $header.removeClass('fixed');
      $headerSlot.addClass('hidden');
    }
  });
};
