<?php
/**
 * Plugin Name: Fooz FAQ
 * Description: Adds a FAQ Accordion in Gutenberg block.
 * Version:     1.0.0
 * Author:      Dawid Hrynkiewicz
 * Text Domain: fooz-faq-block
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

add_action( 'init', 'fooz_faq_register_blocks' );

function fooz_faq_register_blocks(): void {
  $dir = plugin_dir_path( __FILE__ );
  $url = plugin_dir_url( __FILE__ );

  // Editor script
  wp_register_script(
    'fooz-faq-blocks',
    $url . 'assets/js/faq.js',
    [ 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ],
    filemtime( $dir . 'assets/js/faq.js' ),
    true
  );

  // Front + editor styles
  wp_register_style(
    'fooz-faq-style',
    $url . 'assets/css/faq.css',
    [],
    filemtime( $dir . 'assets/css/faq.css' )
  );

  // Register parent block
  register_block_type( 'fooz/faq-accordion', [
    'editor_script' => 'fooz-faq-blocks',
    'style'         => 'fooz-faq-style',
    'editor_style'  => 'fooz-faq-style',
  ] );

  // Register child block
  register_block_type( 'fooz/faq-item', [
    'editor_script' => 'fooz-faq-blocks',
    'style'         => 'fooz-faq-style',
    'editor_style'  => 'fooz-faq-style',
  ] );
}
