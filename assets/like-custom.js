jQuery(document).ready(function ($) {

  $(".btn-like-plugin").stop().click(function () {
    $(this).prop( "disabled", true );
    if ($(this).hasClass('is-liked'))
      $(this).text('Beğen').removeClass('is-liked');
    else
      $(this).text('Beğendin').addClass('is-liked');

    var btnInfo = this.id.split('__');
    var _post_id = btnInfo[1];
    var _user_id = btnInfo[2];
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
        $(btnObj).prop( "disabled", false );
      },
      error: function(err){
        console.log('Error:', err);
        $(btnObj).prop( "disabled", false );
      }
    });
  }

});