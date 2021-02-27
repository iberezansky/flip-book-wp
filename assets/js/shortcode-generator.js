if(window.fb3dCreateInsertApp) {
  var $ = jQuery, instance = fb3dCreateInsertApp($('#'+FB3D_ADMIN_LOCALE.shortcodeGeneratorMountNode)[0], ''),
      text = $('#3dfb-shortcode-textarea');
  instance.onUpdate = function() {
    text[0].value = instance.getShortCode();
  };
  text.on('input', function() {
    instance.setShortCode(text[0].value);
  });
}
