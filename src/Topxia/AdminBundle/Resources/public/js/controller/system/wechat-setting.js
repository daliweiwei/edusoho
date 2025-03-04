define(function (require, exports, module) {
  var Validator = require('bootstrap.validator');
  require('common/validator-rules').inject(Validator);
  var Notify = require('common/bootstrap-notify');
  var WebUploader = require('edusoho.webuploader');

  exports.run = function () {

    var uploader = new WebUploader({
      element: '#qrcode-upload'
    });

    uploader.on('uploadSuccess', function (file, response) {
      $('.js-code-img').attr('src', response.url);
      $('#account_code').val(response.url);
      if ($('.es-qrcode').hasClass('hidden')) {
        $('.es-qrcode').removeClass('hidden');
        $('.code-help-block').addClass('hidden');
      }
    });

    registerWebUploader();

    var validator = new Validator({
      element: '#wechat-setting-form',
    });

    $('[data-toggle="switch"]').on('click', function () {
      var $this = $(this);
      var $parent = $this.parent();
      var isEnable = $this.val();
      var reverseEnable = isEnable == 1 ? 0 : 1;

      if ($this.context.id == 'wechat_notification_enabled' && isEnable == '0') {
        var weixinmobChecked = $('#weixinmob_enabled').val();
        var weixinwebChecked = $('#weixinweb_enabled').val();
        if (weixinmobChecked == '0' || weixinwebChecked == '0') {
          Notify.danger(Translator.trans('admin.system.wechat.notification_open'), 3);
          return;
        }
        var uploader = new WebUploader({
          element: '#qrcode-upload'
        });

        uploader.on('uploadSuccess', function (file, response) {
          $('.js-code-img').attr('src', response.url);
          $('#account_code').val(response.url);
          if ($('.es-qrcode').hasClass('hidden')) {
            $('.es-qrcode').removeClass('hidden');
            $('.code-help-block').addClass('hidden');
          }
        });
      }

      if ($this.context.id == 'weixinweb_enabled' || $this.context.id == 'weixinmob_enabled') {
        var notificationItem = $('#wechat_notification_enabled');
        if (isEnable == '1' && notificationItem.val() == '1') {
          switchCheck('#wechat_notification_enabled', 0);
          $('input[name="wechatSetting[wechat_notification_enabled]"]').change();
        }
      }

      registerWebUploader();

      // 关闭微信内登录时，关闭微信支付
      // if ($this.context.id == 'weixinmob_enabled' && isEnable == '1') {
      //   if ($('#wxpay_enabled').val() == '1') {
      //     switchCheck('#wxpay_enabled', 0);
      //     $('input[name="payment[wxpay_enabled]"]').change();
      //   }
      // }

      // 打开微信支付时，打开微信内登录
      // if ($this.context.id == 'wxpay_enabled' && isEnable == '0') {
      //   if ($('#weixinmob_enabled').val() == '0') {
      //     switchCheck('#weixinmob_enabled', 1);
      //     $('input[name="loginConnect[weixinmob_enabled]"]').change();
      //   }
      // }

      switchCheck(this, reverseEnable);
    });

    var switchCheck = function (target, reverseEnable) {
      var $this = $(target);
      var $parent = $this.parent();

      if ($parent.hasClass('checked')) {
        $parent.removeClass('checked');
      } else {
        $parent.addClass('checked');
      }
      $this.val(reverseEnable);
      $this.next().val(reverseEnable);
    };

    $('[name="loginConnect[weixinweb_enabled]"]').change(function (e) {
      var checked = e.target.value;
      var subItem = $(this).parents('form').find('[data-sub="weixinweb"]');

      if (checked == '1') {
        subItem.removeClass('hidden');
        validator.addItem({
          element: '[name="loginConnect[weixinweb_key]"]',
          required: true,
        });
        validator.addItem({
          element: '[name="loginConnect[weixinweb_secret]"]',
          required: true,
        });
      } else {
        subItem.addClass('hidden');
        validator.removeItem('[name="loginConnect[weixinweb_key]"]');
        validator.removeItem('[name="loginConnect[weixinweb_secret]"]');
      }
    });

    $('[name="loginConnect[weixinmob_enabled]"]').change(function (e) {
      var checked = e.target.value;
      var wxpayChecked = $('#wxpay_enabled').val();
      var subItem = $(this).parents('form').find('[data-sub="weixinmob"]');

      if (checked == '1' || wxpayChecked == '1') {
        subItem.removeClass('hidden');
      } else {
        subItem.addClass('hidden');
      }

      if (checked == '1') {
        validator.addItem({
          element: '[name="loginConnect[weixinmob_key]"]',
          required: true,
        });
        validator.addItem({
          element: '[name="loginConnect[weixinmob_secret]"]',
          required: true,
        });
        validator.addItem({
          element: '[name="payment[wxpay_mp_secret]"]',
          required: true,
        });
      } else {
        validator.removeItem('[name="loginConnect[weixinmob_key]"]');
        validator.removeItem('[name="loginConnect[weixinmob_secret]"]');
        validator.removeItem('[name="payment[wxpay_mp_secret]"]');
      }
    });

    $('[name="wechatSetting[wechat_notification_enabled]"]').change(function (e) {
      var checked = e.target.value;
      var subItem = $(this).parents('form').find('[data-sub="account"]');

      if (checked == '1') {
        subItem.removeClass('hidden');
        validator.addItem({
          element: '[name="wechatSetting[account_code]"]',
          required: true,
        });
      } else {
        subItem.addClass('hidden');
        validator.removeItem('[name="wechatSetting[account_code]"]');
      }
    });

    $('[name="payment[wxpay_enabled]"]').change(function (e) {
      var checked = e.target.value;
      var weixinmobChecked = $('#weixinmob_enabled').val();
      var subItem = $(this).parents('form').find('[data-sub="wxpay"]');
      var mobItem = $(this).parents('form').find('[data-sub="weixinmob"]');

      if (checked == '1') {
        subItem.removeClass('hidden');
        validator.addItem({
          element: '[name="payment[wxpay_account]"]',
          required: true,
        });
        validator.addItem({
          element: '[name="payment[wxpay_key]"]',
          required: true,
        });
      } else {
        subItem.addClass('hidden');
        validator.removeItem('[name="payment[wxpay_account]"]');
        validator.removeItem('[name="payment[wxpay_key]"]');
      }

      if (checked == '1' || weixinmobChecked == '1') {
        mobItem.removeClass('hidden');
      } else {
        mobItem.addClass('hidden');
      }

      if (checked == '1') {
        validator.addItem({
          element: '[name="loginConnect[weixinmob_key]"]',
          required: true,
        });
        validator.addItem({
          element: '[name="loginConnect[weixinmob_secret]"]',
          required: true,
        });
        validator.addItem({
          element: '[name="payment[wxpay_mp_secret]"]',
          required: true,
        });
      } else {
        validator.removeItem('[name="loginConnect[weixinmob_key]"]');
        validator.removeItem('[name="loginConnect[weixinmob_secret]"]');
        validator.removeItem('[name="payment[wxpay_mp_secret]"]');
      }
    });

    $('input[name="loginConnect[weixinweb_enabled]"][type="checkbox"][value="1"]').change();
    $('input[name="loginConnect[weixinmob_enabled]"][type="checkbox"][value="1"]').change();
    $('input[name="wechatSetting[wechat_notification_enabled]"][type="checkbox"][value="1"]').change();
    $('input[name="payment[wxpay_enabled]"][type="checkbox"][value="1"]').change();

    $('#wechat-setting-form').on('click', '.js-code-view', (event) => {
      var $target = $('.js-code-img');
      var $codeItem = $('.es-icon-qrcode');
      if ($target.hasClass('hidden')) {
        $target.removeClass('hidden');
      } else {
        $target.addClass('hidden');
      }
      event.stopPropagation();
    });

    $('body').on('click', () => {
      var $target = $('.js-code-img');
      if (!$target.hasClass('hidden')) {
        $target.addClass('hidden');
      }
    });

    $('.js-wechat-pre-auth-url').on('click', function (event) {
      $.get($('.js-wechat-pre-auth-url').data('url'), function (resp) {
        $('.js-re-auth-btn').attr('href', resp.url);
        $('#confirm-modal').modal('show');
        window.open(resp.url, '_blank');
      });
    });

    $('#confirm-modal').on('hidden.bs.modal', function () {
      window.location.reload();
    });
  };

  var registerWebUploader = function () {
    var keyUploader = new WebUploader({
      element: '#wxpay-cert-key-upload',
      accept: {
        title: 'Cert',
        extensions: 'pem,crt',
        mimeTypes: 'application/x-x509-ca-cert',
      },
      server: $('#wxpay-cert-key-upload').data('uploadUrl'),
    });

    keyUploader.on('uploadSuccess', function (file, response) {
      $('[name="payment[wxpay_key_uploaded]"]').val(1);
      $('#wxpay-cert-key-upload').text('重新上传');
      $('.js-key-uploaded').removeClass('hidden');
      $('.js-key-name').text(response.name);
      $('.js-key-ext').text(response.ext);
      $('.js-key-name').parent().removeClass('hidden');
      Notify.success(Translator.trans('site.upload_success_hint'), 1);
      registerWebUploader();
    });

    var certUploader = new WebUploader({
      element: '#wxpay-cert-upload',
      accept: {
        title: 'Cert',
        extensions: 'pem,crt',
        mimeTypes: 'application/x-x509-ca-cert',
      },
      server: $('#wxpay-cert-upload').data('uploadUrl'),
    });

    certUploader.on('uploadSuccess', function (file, response) {
      $('[name="payment[wxpay_cert_uploaded]"]').val(1);
      $('#wxpay-cert-upload').text('重新上传');
      $('.js-cert-uploaded').removeClass('hidden');
      $('.js-cert-name').text(response.name);
      $('.js-cert-ext').text(response.ext);
      $('.js-cert-name').parent().removeClass('hidden');
      Notify.success(Translator.trans('site.upload_success_hint'), 1);
      registerWebUploader();
    });
  };
});
