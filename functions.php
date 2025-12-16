<?php

/* 
	assets enqueue

*/

// load tailwind cdn
add_action('wp_enqueue_scripts', function () {
  wp_enqueue_script(
    'tailwindcdn',
    'https://cdn.tailwindcss.com',
    [],
    null,
    false
  );
}, 5);

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
}, 20);