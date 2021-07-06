<?php
		remove_action( 'wp_head', 'feed_links', 2 ); //RSSフィード
		remove_action( 'wp_head', 'feed_links_extra', 3 ); //RSSフィード
		remove_action( 'wp_head', 'rsd_link' ); //Really Simple Discovery
		remove_action( 'wp_head', 'wlwmanifest_link' ); //Windows Live Writer
		remove_action( 'wp_head', 'index_rel_link' ); //indexへのリンク
		remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); //分割ページへのリンク
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); //分割ページへのリンク
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 ); //前後のページへのリンク
		remove_action( 'wp_head', 'wp_generator' ); //WordPressのバージョン
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); //絵文字対応
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' ); //絵文字対応
		remove_action( 'wp_print_styles', 'print_emoji_styles' ); //絵文字対応
		remove_action( 'admin_print_styles', 'print_emoji_styles' ); //絵文字対応
		remove_action('wp_head','rest_output_link_wp_head'); //Embed対応
		
		function my_after_setup_theme() {
				add_theme_support( 'post-thumbnails' );
				set_post_thumbnail_size( 640, 384, false );
		}
		
		add_action( 'after_setup_theme', 'my_after_setup_theme' );
		
		function my_enqueue_style_child() {
				wp_enqueue_style( 'child-style', get_stylesheet_uri() );
		}
		add_action( 'wp_enqueue_scripts', 'my_enqueue_style_child' );
		
		function add_my_scripts() {
				wp_enqueue_script( 'main',  get_template_directory_uri() . '/assets/scripts/bundle.js' , array(), '1.0.0', false );
		}
		add_action( 'wp_enqueue_scripts', 'add_my_scripts' );
		
		add_filter( 'show_admin_bar', '__return_false' );
		
		function wp_document_title_separator( $separator ) {
				$separator = '|';
				return $separator;
		}
		add_filter( 'document_title_separator', 'wp_document_title_separator' );
		
		function wp_document_title_parts( $title ) {
				if ( is_home() || is_front_page() ) {
						unset( $title['tagline'] );
				} else if ( is_category() ) {
						$title['title'] = '「' . $title['title'] . '」カテゴリーの記事一覧';
				} else if ( is_tag() ) {
						$title['title'] = '「' . $title['title'] . '」タグの記事一覧';
				} else if ( is_archive() ) {
						$title['title'] = $title['title'] . 'の記事一覧';
				}
				return $title;
		}
		
		add_filter( 'document_title_parts', 'wp_document_title_parts', 10, 1 );
?>
