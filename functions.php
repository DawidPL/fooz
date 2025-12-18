<?php

/* 
	assets enqueue

*/



// load tailwind cdn for dev
/*
add_action('wp_enqueue_scripts', function () {
  wp_enqueue_script(
    'tailwindcdn',
    'https://cdn.tailwindcss.com',
    [],
    null,
    false
  );
}, 5);
*/


// enqueue tailwind css 
add_action('wp_enqueue_scripts', function () {
  $rel_path  = '/dist/app.css';
  $path = get_stylesheet_directory() . $rel_path;

  if (file_exists($path)) {
    wp_enqueue_style(
      'fooz-tailwind',
      get_stylesheet_directory_uri() . $rel_path,
      [],
      filemtime($path)
    );
  }
}, 20);

// enqueue scripts 
add_action('wp_enqueue_scripts', function () {
  $rel_path  = '/assets/js/scripts.js';
  $path = get_stylesheet_directory() . $rel_path;

  if (! file_exists($path)) {
    return;
  }
  
  wp_enqueue_script(
    'fooz-scripts',                          
    get_stylesheet_directory_uri() . $rel_path,       
    [],                                           
    filemtime($path),                             
    [
      'in_footer' => true,   
      'strategy'  => 'defer',
    ]
  );
  
  // AJAX data for single book
  if ( is_singular('book') ) {
    wp_add_inline_script(
      'fooz-scripts',
      'window.FOOZ_LIBRARY = ' . wp_json_encode([
        'ajaxUrl'    => admin_url('admin-ajax.php'),
        'action'     => 'fooz_library_latest_books',
        'nonce'      => wp_create_nonce('fooz_library_latest_books'),
        'currentId'  => get_queried_object_id(),
      ]) . ';',
      'before'
    );
  }
  
}, 20);

// set pagination for taxonomy
add_action('pre_get_posts', function (WP_Query $q) {
  if (is_admin() || ! $q->is_main_query()) {
    return;
  }

  // taxonomy archive for genre
  if ($q->is_tax('genre')) {
    $q->set('post_type', ['book']);
    $q->set('posts_per_page', 5);
  }
});
