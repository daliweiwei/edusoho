webpackJsonp(["app/js/init-password/index"],{"6fd19fada85409f1aa97":function(a,s,i){"use strict";var e=$("#init-password-form"),r=e.validate({rules:{newPassword:{required:!0,minlength:5,maxlength:20},confirmPassword:{required:!0,equalTo:"#newPassword"}}});$('[type="submit"]').click(function(){r.form()&&e.submit()})}},["6fd19fada85409f1aa97"]);