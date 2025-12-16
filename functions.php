<?php
add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style(
    'tt5-child',
    get_stylesheet_uri(),
    ['twentytwentyfive-style'],
    wp_get_theme()->get('Version')
  );
});