<?php

// load widget
function likes_widget_register() {
  register_widget( 'like_widget' );
}
add_action( 'widgets_init', 'likes_widget_register' );

// widget settings 
class like_widget extends WP_Widget {

  function __construct() {
    parent::__construct('likes_widget', 'En Çok Beğenilenler', 'En çok beğeni alan yazılar');
  }

  // widget render
  public function widget( $args, $instance ) {
    $title = apply_filters( 'widget_title', $instance['title'] );

    // before and after widget
    echo $args['before_widget'];
    if ( ! empty( $title ) )
      echo $args['before_title'] . $title . $args['after_title'];

    // Likes query
    $args = array(
      'posts_per_page' => -1,
      'meta_key' => 'like_count',
      'orderby' => 'meta_value',
      'order' => 'DESC',
    );
    $the_query = new WP_Query($args);
    if ( $the_query->have_posts() ) {
      echo '<ul>';
      while ( $the_query->have_posts() ) {
        $the_query->the_post();
        $total_like = get_post_meta(get_the_ID(), 'like_count', true);
        echo '<li><a href="'. get_the_permalink() .'" title="'.get_the_title().'">' . get_the_title() . '</a> ('. $total_like .')</li>';
      }
      echo '</ul>';
    } else {
      echo 'Henüz beğeni alan yazı yok';
    }

    echo $args['after_widget'];
  }
}