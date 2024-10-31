<?php

namespace Whodunit\MywpLoginForm\Admin;


use Whodunit\MywpLoginForm\Init\Core;

/**
 * Register admin stuffs for Gutenberg block.
 */
class Gutenberg {
	protected $core;

	public function __construct( Core $Core ) {
		$this->core = $Core;

		add_filter( 'block_categories_all', array( $this, 'init_block_category' ), 10, 2 );
		add_action( 'init', array( $this, 'init_block' ) );
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_block_assets' ) );
	}

	/**
	 * Dequeue block assets if the block is not on the page
	 *
	 * @return void
	 */
	public function enqueue_block_assets() {
		if ( is_admin() ) {
			return;
		}
		global $post;

		$js_blocks = glob( $this->core->base_dir . '/js/blocks/*.js' );

		foreach ( $js_blocks as $path ) {
			$slug = basename( $path, '.min.js' );
			if ( null !== $post && ! in_array( $post->ID, Shortcode::authorized_ids() ) && ! has_block( 'whoblock/' . $slug, $post->ID ) && ! $this->has_reusable_block( 'whoblock/' . $slug, $post->ID ) ) {
				wp_dequeue_style( 'block-' . $slug );
				wp_deregister_style( 'block-' . $slug );
			}
		}
	}

	/**
	 * Check if a reusable block is on the page
	 *
	 * @param string $block_name
	 * @param int    $post_id
	 *
	 * @return boolean
	 */
	private function has_reusable_block( $block_name, $post_id ) {
		if ( $post_id && has_block( 'block', $post_id ) ) {
			$content = get_post_field( 'post_content', $post_id );
			$blocks  = parse_blocks( $content );

			if ( ! is_array( $blocks ) || empty( $blocks ) ) {
				return false;
			}

			foreach ( $blocks as $block ) {
				if ( 'core/block' === $block['blockName'] && ! empty( $block['attrs']['ref'] ) ) {
					$content   = get_post_field( 'post_content', $block['attrs']['ref'] );
					$has_block = false !== strpos( $content, '<!-- wp:' . $block_name . ' ' );
					if ( $has_block ) {
						return true;
					}
				}
			}
		}

		return false;
	}

	/**
	 * Init the gutenberg block
	 *
	 * @return void
	 */
	public function init_block() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		} // gutenberg disabled

		/// Register the block
		/// ============================
		$blocks = array(
			$this->core->base_dir.'/js/blocks/mwp-login-form.min.js'
		);
		foreach ( $blocks as $path ) {
			$slug = basename( $path, '.min.js' );

			wp_register_script(
				'block-editor-' . $slug,
				$this->core->plugin_url . 'js/blocks/' . $slug . '.min.js',
				array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' )
			);

			$style_css = 'block-' . $slug;

			if ( file_exists( $this->core->base_dir . '/css/blocks/' . $slug . '.min.css' ) ) {
				wp_register_style(
					'block-' . $slug,
					$this->core->plugin_url . 'css/blocks/' . $slug . '.min.css'
				);
			}

			$attributes = $this->set_block_attributes( $slug, 'mwp-login-form', array(
				'slug' => array(
					'type'    => 'string',
					'default' => $slug,
				),
			) );

			// give the theme settings to the block
			wp_localize_script(
				'block-editor-' . $slug,
				'theme_options',
				[ 
					'editor_color_palette' => get_theme_support( 'editor-color-palette' ),
					'editor_font_sizes'    => get_theme_support( 'editor-font-sizes' ),
				]
			);

			register_block_type( 'whoblock/' . $slug,
				array(
					'editor_script'   => 'block-editor-' . $slug,
					'editor_style'    => $style_css,
					'style'           => $style_css,
					'render_callback' => function ( $attributes, $content ) {
						ob_start();
						include( $this->core->base_dir . '/core/views/template-blocks/' . $attributes['slug'] . '.php' );

						return ob_get_clean();
					},
					'attributes'      => $attributes,
				)
			);
		}
	}


	/**
	 * Sets the attributes for the block
	 *
	 * @param string $current_slug The slug of the current block
	 * @param string $slug The slug of the block
	 * @param array  $current_attributes The current attributes
	 *
	 * @return array The attributes for the current block
	 */
	private function set_block_attributes( $current_slug, $slug, $current_attributes ): array {
		if ( $current_slug === $slug && file_exists( $this->core->base_dir . '/core/admin/attributes/' . $slug . '.json' ) ) {
			return array_merge( $current_attributes, json_decode( file_get_contents( $this->core->base_dir . '/core/admin/attributes/' . $slug . '.json' ), true ) );
		}

		return $current_attributes;
	}

	/**
	 * Add new category for block
	 *
	 * @return array $categories
	 */
	public function init_block_category( $categories, $post ) {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		} // gutenberg disabled

		foreach ( $categories as $category ) {
			if ( 'whoblock' === $category['slug'] ) {
				return $categories;
			}
		}

		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'whoblock',
					'title' => 'Custom Blocks',
				),
			)
		);
	}
}
