import Base from './base';

/**
 * WPOnion Module JS Class
 */
export default class extends Base {

	ui_menu_handler() {
		this.element.find( '.wponion-menu > ul a' ).on( 'click', ( e ) => {
			e.preventDefault();
			let $elem = jQuery( e.currentTarget );

			if( $elem.hasClass( 'dropdown' ) ) {
				if( $elem.parent().find( '> ul' ).is( ':visible' ) ) {
					$elem.parent().find( '> ul' ).slideToggle();
				} else {
					this.element.find( '.wponion-menu > ul ul' ).slideUp();
					$elem.parent().find( '> ul' ).slideToggle();
				}

			} else {
				let $href = window.wponion.helper.url_params( $elem.attr( 'href' ) );
				if( false === window.wponion._.isUndefined( $href[ 'container-id' ] ) ) {
					let $lookup = 'div#wponion-tab-' + $href[ 'container-id' ];
					if( false === window.wponion._.isUndefined( $href[ 'sub-container-id' ] ) ) {
						$lookup = $lookup + '-' + $href[ 'sub-container-id' ];
					}

					$lookup = jQuery( $lookup );
					if( $lookup.length === 1 ) {
						this.element.find( 'div.wponion-container-wraps' ).addClass( 'hidden' );
						$lookup.removeClass( 'hidden' );
						this.element.find( '.wponion-menu .active' ).removeClass( 'active' );
						$elem.addClass( 'active' );
						$elem.parent().parent().parent().find( '> a' ).addClass( 'active' );
					} else {
						//window.location.href = $elem.attr( 'href' );
					}
				} else {
					//window.location.href = $elem.attr( 'href' );
				}
			}
		} );
	}
}