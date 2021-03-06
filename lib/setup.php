<?php

namespace Roots\Sage\Setup;

use Roots\Sage\Assets;

/**
 * Theme setup
 */
function setup() {
  // Enable features from Soil when plugin is activated
  // https://roots.io/plugins/soil/
  add_theme_support('soil-clean-up');
  add_theme_support('soil-nav-walker');
  add_theme_support('soil-nice-search');
  //add_theme_support('soil-jquery-cdn');
  add_theme_support('soil-relative-urls');

  // Make theme available for translation
  // Community translations can be found at https://github.com/roots/sage-translations
  load_theme_textdomain('sage', get_template_directory() . '/lang');

  // Enable plugins to manage the document title
  // http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
  add_theme_support('title-tag');

  // Register wp_nav_menu() menus
  // http://codex.wordpress.org/Function_Reference/register_nav_menus
  register_nav_menus([
    'primary_navigation' => __('Primary Navigation', 'sage'),
    'work_navigation' => __('SKAR Work Navigation', 'sage')
  ]);

  // Enable post thumbnails
  // http://codex.wordpress.org/Post_Thumbnails
  // http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
  // http://codex.wordpress.org/Function_Reference/add_image_size
  add_theme_support('post-thumbnails');

  // Enable post formats
  // http://codex.wordpress.org/Post_Formats
  add_theme_support('post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio']);

  // Enable HTML5 markup support
  // http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
  add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

  // Use main stylesheet for visual editor
  // To add custom styles edit /assets/styles/layouts/_tinymce.scss
  add_editor_style(Assets\asset_path('styles/main.css'));
}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup');

/**
 * Register sidebars
 */
function widgets_init() {
  register_sidebar([
    'name'          => __('Primary', 'sage'),
    'id'            => 'sidebar-primary',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);

  register_sidebar([
    'name'          => __('Footer', 'sage'),
    'id'            => 'sidebar-footer',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);
}
add_action('widgets_init', __NAMESPACE__ . '\\widgets_init');

/**
 * Determine which pages should NOT display the sidebar
 */
function display_sidebar() {
  static $display;

  isset($display) || $display = !in_array(true, [
    // The sidebar will NOT be displayed if ANY of the following return true.
    // @link https://codex.wordpress.org/Conditional_Tags
    is_404(),
    is_front_page(),
    is_page_template('template-custom.php'),
    is_singular(),
    is_page_template('template-what-we-do.php'),
    is_page_template('template-who-we-are.php'),
    is_singular( 'industries' ),
    is_singular( 'campaigns' ),
    is_singular( 'work' ),
    is_singular( 'people' ),
    is_singular( 'case-studies' ),
    is_page_template('template-get-in-touch.php'),
    is_page_template('template-careers.php'),
    is_page_template('template-view-more-industry-work.php'),
    is_tax( 'work-categories' ),
    is_category(),
    is_search(),
    is_author(),
  ]);

  return apply_filters('sage/display_sidebar', $display);
}

/**
 * Theme assets
 */
function assets() {
  wp_enqueue_style('sage/css', Assets\asset_path('styles/main.css'), false, null);

  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  wp_enqueue_script('sage/js', Assets\asset_path('scripts/main.js'), ['jquery'], null, true);
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\assets', 100);

if ( ! is_admin() ) {
// Loads the js specific to the home page
  function load_home_js () {
    if(  is_front_page() ) {
      wp_enqueue_script('home_scripts', Assets\asset_path('scripts/home_scripts.js'), ['jquery'], null, true);
    }
  }
  add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\load_home_js', 100);

  function load_what_we_do_js() {
    if(  is_page_template('template-what-we-do.php') ) {
      wp_enqueue_script('what_we_do_scripts', Assets\asset_path('scripts/what_we_do_scripts.js'), ['jquery'], null, true);
    }
  }
  add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\load_what_we_do_js', 100);

  function load_who_we_are_js() {
    if(  is_page_template('template-who-we-are.php') ) {
      wp_enqueue_script('who_we_are_scripts', Assets\asset_path('scripts/who_we_are_scripts.js'), ['jquery'], null, true);
    }
  }
  add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\load_who_we_are_js', 100);

  function load_industry_js() {
    if(  is_singular( 'industries' ) ) {
      wp_enqueue_script('industries_scripts', Assets\asset_path('scripts/industries-scripts.js'), ['jquery'], null, true);
    }
  }
  add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\load_industry_js', 100);

  /*if( is_page( 12022 ) ) {
    function reload_jquery() {
      wp_enqueue_script( 'jquery' );
    }
    add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\reload_jquery' );
  }*/
}
