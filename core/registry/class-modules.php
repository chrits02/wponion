<?php
/**
 *
 * Initial version created 14-05-2018 / 10:32 AM
 *
 * @author Varun Sridharan <varunsridharan23@gmail.com>
 * @version 1.0
 * @since 1.0
 * @package
 * @link
 * @copyright 2018 Varun Sridharan
 * @license GPLV3 Or Greater (https://www.gnu.org/licenses/gpl-3.0.txt)
 */

namespace WPOnion\Registry;
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( '\WPOnion\Registry\Modules' ) ) {
	/**
	 * Class Modules
	 *
	 * @package WPOnion\Registry
	 * @author Varun Sridharan <varunsridharan23@gmail.com>
	 * @since 1.0
	 */
	class Modules implements \WPOnion\Registry\Common {
		/**
		 * Stores All Instances
		 *
		 * @var array
		 */
		protected $registry = array();

		/**
		 * Adds An Instance To Array.
		 *
		 * @param string            $type
		 * @param \WPOnion\Bridge $instance
		 *
		 * @return mixed|void
		 */
		public function add( $type = 'settings', \WPOnion\Bridge &$instance ) {
			if ( ! isset( $this->registry[ $type ] ) ) {
				$this->registry[ $type ] = array();
			}

			$key = $instance->plugin_id();

			if ( ! isset( $registry[ $type ][ $key ] ) ) {
				$this->registry[ $type ][ $key ] = $instance;
			}
		}

		/**
		 * Returns An Instance.
		 *
		 * @param string $type
		 * @param        $key
		 *
		 * @return bool
		 * @static
		 */
		public function get( $type = 'settings', $key ) {
			if ( isset( $this->registry[ $type ][ $key ] ) ) {
				return $this->registry[ $type ][ $key ];
			}
			return false;
		}
	}
}
