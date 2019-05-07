<?php
/**
 *
 * @author Varun Sridharan <varunsridharan23@gmail.com>
 * @version 1.0
 * @since 1.0
 * @link
 * @copyright 2018 Varun Sridharan
 * @license GPLV3 Or Greater (https://www.gnu.org/licenses/gpl-3.0.txt)
 */

namespace WPOnion\Integrations\Page_Builders;

use WPOnion\Field\Cloner;
use WPOnion\Integrations\Page_Builders\Elementor\Metabox;
use WPOnion\Integrations\Page_Builders\Elementor\Metabox_Data;
use WPOnion\Integrations\Page_Builders\Elementor\Taxonomy;
use WPOnion\Integrations\Page_Builders\Elementor\Taxonomy_Data;

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( '\WPOnion\Integrations\Page_Builders\Elementor' ) ) {
	/**
	 * Class Elementor
	 *
	 * @package WPOnion\Integrations\Page_Builders
	 * @author Varun Sridharan <varunsridharan23@gmail.com>
	 * @since 1.0
	 */
	final class Elementor {

		/**
		 * Inits Base Class.
		 *
		 * @static
		 */
		public static function init() {
			add_action( 'elementor/dynamic_tags/register_tags', array( __CLASS__, 'register_tags' ) );
		}

		/**
		 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags
		 *
		 * @static
		 */
		public static function register_tags( $dynamic_tags ) {
			self::set_metaboxes( $dynamic_tags );
			self::set_taxonomy( $dynamic_tags );
		}

		/**
		 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags
		 *
		 * @static
		 */
		private static function set_taxonomy( $dynamic_tags ) {
			/**
			 * @var \WPOnion\Modules\Taxonomy                              $mb
			 * @var \WPOnion\Integrations\Page_Builders\Elementor\Taxonomy $new_class
			 */
			$instances = 'all';
			$taxonomy  = wponion_taxonomy_registry( $instances );
			foreach ( $taxonomy as $mb ) {
				if ( false !== $mb->option( 'elementor' ) ) {
					$title     = $mb->option( 'elementor' );
					$slug      = str_replace( '-', '', sanitize_title( $title ) );
					$new_class = 'wpo_' . $slug . '_elementor_taxonomy';
					$class     = new Class extends Taxonomy {
					};

					class_alias( get_class( $class ), $new_class );
					$new_class::$wpo_title    = $title;
					$new_class::$wpo_slug     = $slug;
					$new_class::$wpo_instance = $mb;
					$dynamic_tags->register_tag( $new_class );
					$class_data = new Class extends Taxonomy_Data {
					};

					$new_class = $new_class . '_data';
					class_alias( get_class( $class_data ), $new_class );
					$new_class::$wpo_title    = $title;
					$new_class::$wpo_slug     = $slug . '-data';
					$new_class::$wpo_instance = $mb;
					$dynamic_tags->register_tag( $new_class );
				}
			}
		}

		/**
		 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags
		 *
		 * @static
		 */
		private static function set_metaboxes( $dynamic_tags ) {
			/**
			 * @var \WPOnion\Modules\Metabox\Metabox                              $mb
			 * @var \WPOnion\Integrations\Page_Builders\Elementor\Metabox $new_class
			 */
			$instances = 'all';
			$metaboxes = wponion_metabox_registry( $instances );
			foreach ( $metaboxes as $mb ) {
				if ( false !== $mb->option( 'elementor' ) ) {
					$title     = ( true === $mb->option( 'elementor' ) ) ? $mb->option( 'metabox_title' ) : $mb->option( 'elementor' );
					$slug      = str_replace( '-', '', sanitize_title( $title ) );
					$new_class = 'wpo_' . $slug . '_elementor_metabox';
					$class     = new Class extends Metabox {
					};

					class_alias( get_class( $class ), $new_class );
					$new_class::$wpo_title    = $title;
					$new_class::$wpo_slug     = $slug;
					$new_class::$wpo_instance = $mb;
					$dynamic_tags->register_tag( $new_class );
					$class_data = new Class extends Metabox_Data {
					};

					$new_class = $new_class . '_data';
					class_alias( get_class( $class_data ), $new_class );
					$new_class::$wpo_title    = $title;
					$new_class::$wpo_slug     = $slug . '-data';
					$new_class::$wpo_instance = $mb;
					$dynamic_tags->register_tag( $new_class );
				}
			}
		}
	}
}