<?php
/**
 * @package LikeCustom
 */
/*
Plugin Name: Like Custom Post
Plugin URI: http://github.con/hhgsun/likecustom/
Description: gönderileri kullanıcılar tarafından beğenilmesi.
Author: HHGsun
Version: 0.0.1
Author URI: http://hhgsun.com/
Licence: GNU
*/
/*
Bu Eklenti Yazılar ile ilgili kullanıcılar tarafından beğenilip beğenilmediğini tutar
*/

defined('ABSPATH') or die('Bu dosyaya erişemezsiniz');

class LikeCustom {

  function __construct(){
    add_filter('the_content', array($this, 'likeBtnRender') );
    add_action('wp_enqueue_scripts',array($this, 'enqueue'));
    add_action('wp_ajax_like_callback', array($this, 'like_callback'));
    add_action('wp_ajax_nopriv_like_callback', array($this, 'like_callback'));  
  }

  var $like_meta_name = 'like_ids'; // post meta alan adı
  var $like_meta_count_name = 'like_count'; // post meta alan adı

  function enqueue(){
    wp_enqueue_script('enqueue-ajax-call');
    wp_enqueue_script('jquery');
    ?>
    <script>
      var __WP_ADMIN_AJAX_URL = "<?php echo admin_url('admin-ajax.php');?>";
    </script>
    <style>.like-btn-disable{padding:5px; margin:5px; background:#ececec; border: 2px solid;}</style>
    <?php
    wp_enqueue_script('like-btn-script', plugins_url('/assets/like-custom.js', __FILE__));
  }

  function likeBtnRender($content){
    $btn = '<a class="btn like-btn-disable" href="'. wp_login_url( get_permalink() ) .'" title="beğen">Beğenmek için Giriş Yapınız</a>';
    if(is_single()){
      if(is_user_logged_in()){
        $like_list = get_post_meta(get_the_ID(), $this->like_meta_name, true); // tüm beğeni listesi
        $is_liked = $this->preLiked($like_list, get_the_ID(), get_current_user_id()) == -1 ? false : true;
        $btn = '<button id="btn-like__'. get_the_ID() .'__'. get_current_user_id() .'" class="btn-like-plugin'. ($is_liked ? ' is-liked' : '') .'">'. ($is_liked ? 'Beğendin' : 'Beğen') . '</button>';
        return $content.$btn;
      }
      return $content.$btn;
    }
    return $content;
  }

  function preLiked($_like_list, $_like_post_id, $_like_user_id){
    if($_like_list != ''){
      foreach ($_like_list as $key => $uid) {
        if($uid == $_like_user_id)
          return $key; // listedeki indis döner
      }
    }
    return -1;
  }

  function like_callback() {
    $like_post_id = $_REQUEST['like_post_id']; // post id
    $like_user_id = $_REQUEST['like_user_id']; // user id
    $like_list = get_post_meta($like_post_id, $this->like_meta_name, true); // tüm beğeni listesi

    if($like_list == '') {
      $like_list = array($like_user_id); // beğeni listesi boş ise sıfırdan oluşur
    } else {
      $count = 0;
      foreach ($like_list as $key => $uid) {
        if($uid == $like_user_id){
          unset($like_list[$key]); // beğeni önceden varsa kaldırılır
          $count++;
          break;
        }
      }
      if($count == 0){
        array_push($like_list, $like_user_id); // beğeni önceden yoksa oluşur
      }
    }
    update_post_meta($like_post_id, $this->like_meta_name, $like_list);
    update_post_meta($like_post_id, $this->like_meta_count_name, count($like_list));
    echo json_encode($like_list);
    die();
  }

}

if( class_exists('LikeCustom') ){
  $like = new LikeCustom();
}

// SHORT CODE FILE
include('tags-shortcode.php');

// WIDGET FILE
include('likes-widget.php');