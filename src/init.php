<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * Assets enqueued:
 * 1. blocks.style.build.css - Frontend + Backend.
 * 2. blocks.build.js - Backend.
 * 3. blocks.editor.build.css - Backend.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function code_highlighter_block_cgb_block_assets() { 

	// Register block styles for both frontend + backend.
	wp_register_style(
		'code_highlighter_block-cgb-style-css',
		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ),
		is_admin() ? array( 'wp-editor' ) : null, 
		null
	);

	// Register block editor script for backend.
	wp_register_script(
		'code_highlighter_block-cgb-block-js', 
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), 
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
		null, 
		true
	);

	// Register block editor styles for backend.
	wp_register_style(
		'code_highlighter_block-cgb-block-editor-css',
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ),
		array( 'wp-edit-blocks' ),
		null 
	);

	// WP Localized globals. Use dynamic PHP stuff in JavaScript via `cgbGlobal` object.
	wp_localize_script(
		'code_highlighter_block-cgb-block-js',
		'cgbGlobal', // Array containing dynamic data for a JS Global.
		[
			'pluginDirPath' => plugin_dir_path( __DIR__ ),
			'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
			// Add more data here that you want to access from `cgbGlobal` object.
		]
	);

	/**
	 * Register Gutenberg block on server-side.
	 *
	 * Register the block on server-side to ensure that the block
	 * scripts and styles for both frontend and backend are
	 * enqueued when the editor loads.
	 * @since 1.16.0
	 */
	function cbg_register_block( $name, $options= array() ){
		return register_block_type(	
			$name,
			array_merge(
				array(
					// Enqueue blocks.style.build.css on both frontend & backend.
					'style'         => 'code_highlighter_block-cgb-style-css',
					// Enqueue blocks.build.js in the editor only.
					'editor_script' => 'code_highlighter_block-cgb-block-js',
					// Enqueue blocks.editor.build.css in the editor only.
					'editor_style'  => 'code_highlighter_block-cgb-block-editor-css',
				),
				$options 
			)
		);

	}
	cbg_register_block( 'cgb/block-code-highlighter-block' );
	cbg_register_block( 'cgb/block-post-carousel', array(
		'render_callback' => 'cgb_render_callback', 
		'attributes'=> array(
			'numberOfPosts'=> array(
				'type'=> 'number',
				'default'=> 4
			),
			'postCats' => array(
				'type' => 'string',
				'default' => ''
			)
		)
	) );
}

// Hook: Block assets.
add_action( 'init', 'code_highlighter_block_cgb_block_assets' );

// dynamic block - render function 
function cgb_render_callback( $attributes ) {
	$posts = null; 
	$args = array(
		'post_type'=> 'post', 
		'posts_per_page' => $attributes['numberOfPosts']
	);
	$posts = new WP_Query( $args );
	if( $posts->have_posts( )){
		$markup = '<div class="dynamic_wrapper">';
		while($posts->have_posts( )){
			$posts->the_post( );
			$markup .= '<h2>'.get_the_title( ).'</h2>';
		}

		$markup .= '</div>';
		wp_reset_postdata( );
	}

	return $markup;
}


/**	
 * Libs Assets loading 
 */
function code_highlighter_block_libs_assets() {

	if( ! is_admin( )){
		// JS Libs
		wp_enqueue_script(
			'code_highlighter_block_js_lib', 
			plugins_url( '/dist/lib/js/prism.js', dirname( __FILE__ ) ), 
			array(),
			null, 
			true
		);

		// CSS Libs
		wp_enqueue_style(
			'code_highlighter_block_css_lib', 
			plugins_url( '/dist/lib/css/prism.css', dirname( __FILE__ ) ), 
			array(),
			null, 
			'all'
		);
	}

}
add_action( 'enqueue_block_assets', 'code_highlighter_block_libs_assets' );

// custom category 
function cgb_custom_block_category( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'guten-post-blocks',
				'title' => __( 'Guten Post Blocks', 'code-highlighter-block' ),
			),
		)
	);
}
add_filter( 'block_categories', 'cgb_custom_block_category', 10, 2); // Where $priority is 10, $accepted_args is 2.


