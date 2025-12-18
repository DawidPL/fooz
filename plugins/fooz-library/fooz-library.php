<?php
/**
 * Plugin Name: Fooz Library
 * Description: Registers custom post type and taxonomy.
 * Version:     1.0.0
 * Author:      Dawid Hrynkiewicz
 * Text Domain: fooz-library
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

define( 'FOOZ_LIBRARY_TD', 'fooz-library' );

/**
 * Load translations if you add .mo later.
 */
 
add_action( 'plugins_loaded', function () {
  load_plugin_textdomain(
    FOOZ_LIBRARY_TD,
    false,
    dirname( plugin_basename( __FILE__ ) ) . '/languages'
  );
} );

add_action( 'init', 'fooz_library_register_content' );

/**
 * Register posts + taxonomy.
 */
 
function fooz_library_register_content(): void {
  fooz_library_register_books();
  fooz_library_register_taxonomy();
}

function fooz_library_register_books(): void {
  $labels = [
    'name'          => __( 'Books', FOOZ_LIBRARY_TD ),
    'singular_name' => __( 'Book', FOOZ_LIBRARY_TD ),
    'menu_name'     => __( 'Books', FOOZ_LIBRARY_TD ),
    'add_new_item'  => __( 'Add New Book', FOOZ_LIBRARY_TD ),
    'edit_item'     => __( 'Edit Book', FOOZ_LIBRARY_TD ),
    'view_item'     => __( 'View Book', FOOZ_LIBRARY_TD ),
  ];

  $args = [
    'labels'       => $labels,
    'public'       => true,
    'show_in_rest' => true,
    'menu_icon'    => 'dashicons-book',
    'supports'     => [ 'title', 'editor', 'excerpt', 'thumbnail' ],
    'rewrite'      => [ 'slug' => 'library', 'with_front' => false ],
    'has_archive'  => 'library',
  ];

  register_post_type( 'book', $args );
}

function fooz_library_register_taxonomy(): void {
  $labels = [
    'name'          => _x( 'Genres', 'taxonomy general name', FOOZ_LIBRARY_TD ),
    'singular_name' => _x( 'Genre', 'taxonomy singular name', FOOZ_LIBRARY_TD ),
    'menu_name'     => __( 'Genres', FOOZ_LIBRARY_TD ),
    'edit_item'     => __( 'Edit Genre', FOOZ_LIBRARY_TD ),
    'add_new_item'  => __( 'Add New Genre', FOOZ_LIBRARY_TD ),
  ];

  $args = [
    'labels'       => $labels,
    'public'       => true,
    'hierarchical' => true,
    'show_in_rest' => true,
    'rewrite'      => [ 'slug' => 'book-genre', 'with_front' => false ],
  ];

  register_taxonomy( 'genre', [ 'book' ], $args );
}

register_activation_hook( __FILE__, function () {
  fooz_library_register_content();
  flush_rewrite_rules();
} );

register_deactivation_hook( __FILE__, function () {
  flush_rewrite_rules();
} );


/*

AJAX endpoint

*/

add_action( 'wp_ajax_fooz_library_latest_books', 'fooz_library_ajax_latest_books' );
add_action( 'wp_ajax_nopriv_fooz_library_latest_books', 'fooz_library_ajax_latest_books' );

function fooz_library_ajax_latest_books(): void {
  // Security: nonce
  $nonce = isset( $_GET['nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['nonce'] ) ) : '';
  if ( ! wp_verify_nonce( $nonce, 'fooz_library_latest_books' ) ) {
    wp_send_json_error( [ 'message' => 'Invalid nonce.' ], 403 );
  }

  // Current post ID to exclude
  $current_id = isset( $_GET['current_id'] ) ? absint( $_GET['current_id'] ) : 0;

  $q = new WP_Query( [
    'post_type'           => 'book',
    'post_status'         => 'publish',
    'posts_per_page'      => 20,
    'orderby'             => 'date',
    'order'               => 'DESC',
    'post__not_in'        => $current_id ? [ $current_id ] : [],
    'no_found_rows'       => true,
    'ignore_sticky_posts' => true,
  ] );

  $items = [];

  foreach ( $q->posts as $post ) {
    $book_id = (int) $post->ID;

    $terms = get_the_terms( $book_id, 'genre' );
    $genres = [];
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
      foreach ( $terms as $t ) {
        $genres[] = [
          'id'   => (int) $t->term_id,
          'name' => $t->name,
          'url'  => get_term_link( $t ),
        ];
      }
    }

	$raw_excerpt = get_the_excerpt( $book_id );
	if ( '' === trim( $raw_excerpt ) ) {
	  $raw_excerpt = wp_strip_all_tags( get_post_field( 'post_content', $book_id ) );
	}

    $items[] = [
      'id'      => $book_id,
      'title'   => get_the_title( $book_id ),
      'url'     => get_permalink( $book_id ),
      'date'    => get_the_date( 'Y-m-d', $book_id ),
      'date_h'  => get_the_date( '', $book_id ),
      'excerpt' => wp_trim_words( $raw_excerpt, 25, '…' ),
      'genres'  => $genres,
    ];
  }

  wp_send_json_success( [
    'items' => $items,
  ] );
}
