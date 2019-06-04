<?php

//[likes title="Anahtar Kelimeler"]
function like_list_render( $atts ){
  ob_start();
  $params = shortcode_atts( array(
		'title' => 'Etiketler',
	), $atts );
  echo $params['title'];
  
  $tags = get_tags(array(
    'offset' => 0,
    'number'  => 100,
    //'orderby' => 'like_count',
  ));
  echo '<ul>';
  foreach ($tags as $key => $tag) { ?>
    <li>
      <a href="<?php echo get_tag_link($tag->term_id); ?>" title="<?php echo $tag->name; ?>">
        <?php echo $tag->name . ' (' . $tag->count . ')'; ?>
      </a>
    </li>
    <?php 
  }
  echo '</ul>';
	return ob_get_clean();
}
add_shortcode( 'likes', 'like_list_render' );
