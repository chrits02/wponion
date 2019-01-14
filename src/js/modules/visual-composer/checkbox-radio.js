import WPOnion_VC_Field from './field';

class field extends WPOnion_VC_Field {
	/**
	 * Inits Field.
	 */
	init() {
		if( this.is_vc_param_elem() ) {
			if( this.element.hasClass( 'wponion-element-radio' ) && 0 === this.element.find( '.wponion-checkbox-radio-group' ).length ) {
				this.handle( 'array' );
				this.element.find( 'input' ).on( 'change', () => this.handle( 'array' ) );
			} else if( ( this.element.find( 'input' ).length > 1 ) ) {
				this.handle( 'array' );
				this.element.find( 'input' ).on( 'change', () => this.handle( 'array' ) );
			} else {
				let $this = this;
				let $val  = this.element.find( 'input' ).attr( 'value' );
				this.element.find( 'input' ).attr( 'data-orgval', $val );
				this.element.find( 'input' ).on( 'change', function() {
					$this.handle_single_change( jQuery( this ) );
				} );
				this.element.find( 'input' ).each( function() {
					$this.handle_single_change( jQuery( this ) );
				} );
			}
		}
	}

	/**
	 * Handles Single Checkbox Change Events.
	 * @param $elem
	 */
	handle_single_change( $elem ) {
		if( $elem.is( ':checked' ) ) {
			$elem.val( $elem.attr( 'data-orgval' ) );
		} else {
			$elem.val( 'false' );
		}
	}

	/**
	 * Handles Multiple Checkboxes
	 * @param $type
	 */
	handle( $type ) {
		let $checked = this.element.find( 'input:checked' );
		let $save    = [];
		if( $checked.length > 0 ) {
			jQuery.each( $checked, function() {
				$save.push( jQuery( this ).val() );
			} );
		}
		this.save( $save, $type );
	}
}

export default ( ( w ) => {
	w.wponion_register_field( 'checkbox_radio', ( $elem ) => new field( $elem ), 'vc' );
	w.wponion_register_field( 'image_select', ( $elem ) => new field( $elem ), 'vc' );
	w.wponion_register_field( 'color_palette', ( $elem ) => new field( $elem ), 'vc' );
	w.wponion_register_field( 'switcher', ( $elem ) => new field( $elem ), 'vc' );
} )( window );
