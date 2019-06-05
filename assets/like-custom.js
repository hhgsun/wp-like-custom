jQuery(document).ready(function ($) {

  var btnClickLimit = 5;
  var btnClickCount = 0;

  $(".btn-like-plugin").stop().click(function () {
    $(this).prop("disabled", true);
    var numberText = Number($($('.btn-number-text')[0]).text());

    if ($(this).hasClass('is-liked')) {
      $(this).removeClass('is-liked');
      $($('.btn-like-text')[0]).text('Beğen');
      numberText -= 1;
    } else {
      $($('.btn-like-text')[0]).text('Beğendin');
      $(this).addClass('is-liked');
      numberText += 1;
    }
    $($('.btn-number-text')[0]).text(numberText);

    var btnInfo = this.id.split('__');
    var _post_id = btnInfo[1];
    var _user_id = btnInfo[2];

    btnClickCount++;
    if (btnClickCount <= btnClickLimit)
      ajax_like_plug(_post_id, _user_id, this);
  });

  function ajax_like_plug(_post_id, _user_id, btnObj) {
    $.ajax({
      action: "like_callback",
      type: "POST",
      dataType: "json",
      url: __WP_ADMIN_AJAX_URL,
      data: {
        like_post_id: _post_id,
        like_user_id: _user_id,
        action: 'like_callback'
      },
      success: function (data) {
        console.log('başarılı', data);
        $(btnObj).prop("disabled", false);
      },
      error: function (err) {
        console.log('Error:', err);
        $(btnObj).prop("disabled", false);
      }
    });
  }

});