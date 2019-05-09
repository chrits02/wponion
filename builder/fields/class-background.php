<?php
/**
 *
 * @author Varun Sridharan <varunsridharan23@gmail.com>
 * @version 1.0
 * @since 1.0
 * @link
 * @copyright 2019 Varun Sridharan
 * @license GPLV3 Or Greater (https://www.gnu.org/licenses/gpl-3.0.txt)
 */

namespace WPO\Fields;

if ( ! class_exists( 'WPO\Fields\Background' ) ) {
	/**
	 * Class Background
	 *
	 * @package WPO\Fields
	 * @author Varun Sridharan <varunsridharan23@gmail.com>
	 * @since 1.0
	 *
	 * @method get_preview()
	 * @method get_background_repeat()
	 * @method get_background_attachment()
	 * @method get_background_position()
	 * @method get_background_clip()
	 * @method get_background_origin()
	 * @method get_background_size()
	 * @method get_background_color()
	 * @method get_background_image()
	 */
	class Background extends \WPO\Field {
		/**
		 * Background constructor.
		 *
		 * @param bool  $id
		 * @param bool  $title
		 * @param array $args
		 */
		public function __construct( $id = false, $title = false, $args = array() ) {
			parent::__construct( 'background', $id, $title, $args );
		}

		/**
		 * @param bool $show_preview
		 *
		 * @return $this
		 */
		public function preview( $show_preview = true ) {
			return $this->_set( 'preview', $show_preview );
		}

		/**
		 * @return \WPO\Fields\Background
		 */
		public function show_preview() {
			return $this->preview( true );
		}

		/**
		 * @return \WPO\Fields\Background
		 */
		public function hide_preview() {
			return $this->preview( false );
		}

		/**
		 * @param string $height
		 *
		 * @return $this
		 */
		public function preview_height( $height = '200px' ) {
			return $this->_set( 'height', $height );
		}

		/**
		 * @param bool|\WPO\Field $background_repeat
		 *
		 * @return $this
		 */
		public function background_repeat( $background_repeat = true ) {
			return $this->_set( 'background-repeat', $background_repeat );
		}

		/**
		 * @param bool|\WPO\Field $background_attachment
		 *
		 * @return $this
		 */
		public function background_attachment( $background_attachment = true ) {
			return $this->_set( 'background-attachment', $background_attachment );
		}

		/**
		 * @param bool|\WPO\Field $background_position
		 *
		 * @return $this
		 */
		public function background_position( $background_position = true ) {
			return $this->_set( 'background-position', $background_position );
		}

		/**
		 * @param bool|\WPO\Field $background_clip
		 *
		 * @return $this
		 */
		public function background_clip( $background_clip = true ) {
			return $this->_set( 'background-clip', $background_clip );
		}

		/**
		 * @param bool|\WPO\Field $background_origin
		 *
		 * @return $this
		 */
		public function background_origin( $background_origin = true ) {
			return $this->_set( 'background-origin', $background_origin );
		}

		/**
		 * @param bool|\WPO\Field $background_size
		 *
		 * @return $this
		 */
		public function background_size( $background_size = true ) {
			return $this->_set( 'background-size', $background_size );
		}

		/**
		 * @param bool|\WPO\Field $background_color
		 *
		 * @return $this
		 */
		public function background_color( $background_color = true ) {
			return $this->_set( 'background-color', $background_color );
		}

		/**
		 * @param bool|\WPO\Field $background_image
		 *
		 * @return $this
		 */
		public function background_image( $background_image = true ) {
			return $this->_set( 'background-image', $background_image );
		}
	}
}
