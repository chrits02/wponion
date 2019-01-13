import WPOnion_Field from '../core/field';
import WPOnion_Dependency from '../core/dependency';
import $wponion from '../core/core';

class field extends WPOnion_Field {
	/**
	 * Inits Field.
	 */
	init() {
		let $this       = this,
			$add        = this.element.find( '> .wponion-fieldset > button[data-wponion-group-add]' ),
			$group_wrap = this.element.find( '> .wponion-fieldset > .wponion-group-wrap' ),
			$limit      = $this.option( 'limit' ),
			$error_msg  = $this.option( 'error_msg' );

		this.init_field( this.element.find( '.wponion-group-wrap' ), 'accordion' );

		$group_wrap.find( '> .wponion-accordion-wrap' ).each( function() {
			new WPOnion_Dependency( jQuery( this ), { nestable: true } );
		} );
		this.bind_events_for_title();
		this.element.find( '.wponion-group-remove' ).tippy( {
			appendTo: () => this.get_field_parent_by_id( this.element )[ 0 ],
		} );
		this.element.on( 'click', '.wponion-group-remove', function() {
			jQuery( this ).parent().parent().find( '> .wponion-accordion-content > .wponion-group-action > button' )
						  .click();
		} );

		$group_wrap.WPOnionCloner( {
			add_btn: $add,
			limit: parseInt( $limit ),
			clone_elem: '> .wponion-fieldset > .wponion-accordion-wrap',
			remove_btn: '.wponion-group-action > button',
			template: this.option( 'group_template' ),
			onRemove: ( $elem ) => {
				$elem.parent().parent().parent().slideUp( function() {
					jQuery( this ).remove();
				} );
				if( jQuery( 'body' ).find( 'link#editor-buttons-css' ).length === 0 ) {
					jQuery( 'body' )
						.append( '<link rel="stylesheet" id="editor-buttons-css" href="' + $wponion.option( 'wpeditor_buttons_css', false ) + '" type="text/css" media="all">' );
				}
				this.update_groups_title();
				this.element.trigger( 'change' );
			},
			templateAfterRender: () => {
				let $data = $group_wrap.find( '> .wponion-accordion-wrap:last-child' );
				$data.hide();
				this.update_groups_title();
				this.bind_events_for_title();
				this.init_field( $group_wrap, 'accordion' );
				//this.js_validate_elem( this.option( 'js_validate', false ), $data );
				$data.find( '.wponion-group-remove' ).tippy( {
					appendTo: () => this.get_field_parent_by_id( this.element )[ 0 ],
				} );
				window.wponion_field( $data ).reload();
				new WPOnion_Dependency( $group_wrap.find( '> .wponion-accordion-wrap:last-child' ), { nestable: true } );
				this.init_field( $data.find( '.wponion-element-wp_editor' ), 'reload_wp_editor' );
				$data.slideDown();
				this.element.trigger( 'change' );
			},
			sortable: {
				items: '.wponion-accordion-wrap',
				handle: '.wponion-accordion-title',
				placeholder: 'wponion-accordion-placeholder',
				start: function( event, ui ) {
					ui.item.css( 'background-color', '#eeee' );
				},
				stop: ( event, ui ) => {
					ui.item.removeAttr( 'style' );
					this.update_groups_title();
					this.element.trigger( 'change' );
				}

			},
			onLimitReached: function() {
				if( $add.parent().find( 'div.alert' ).length === 0 ) {
					$add.before( jQuery( $error_msg ).hide() );
					$add.parent().find( 'div.alert' ).slideDown();
					window.wponion_notice( $add.parent().find( 'div.alert, div.notice' ) );
				}
			}
		} );
	}

	/**
	 * Binds Dynamic Group Title Events.
	 * @param $elem
	 */
	bind_events_for_title( $elem = false ) {
		$elem = ( false === $elem ) ? this.element.find( '> .wponion-fieldset > .wponion-group-wrap > .wponion-accordion-wrap' ) : $elem;
		$elem.each( ( i, e ) => {
			let $data = jQuery( e );

			let $mached = this.option( 'matched_heading_fields' );
			for( let $key in $mached ) {
				if( $mached.hasOwnProperty( $key ) ) {
					let $elem = $data.find( ':input[data-depend-id="' + $mached[ $key ] + '"]' );
					if( $elem.length > 0 ) {
						$elem.on( 'change, blur', () => this.update_groups_title() );
					}
				}
			}
		} );
	}

	/**
	 * Updates Group Title
	 * @param $elem
	 */
	update_groups_title( $elem = false ) {
		let $limit = 1;
		$elem      = ( false === $elem ) ? this.element.find( '> .wponion-fieldset > .wponion-group-wrap > .wponion-accordion-wrap' ) : $elem;

		$elem.each( ( i, e ) => {
			let $data    = jQuery( e );
			let $heading = this.option( 'heading' );
			if( false !== this.option( 'heading_counter' ) ) {
				$heading = window.wponion._.replace( $heading, '[count]', $limit );
			}

			let $mached = this.option( 'matched_heading_fields' );
			for( let $key in $mached ) {
				if( $mached.hasOwnProperty( $key ) ) {
					let $elem = $data.find( ':input[data-depend-id="' + $mached[ $key ] + '"]' );
					if( $elem.length > 0 ) {
						$heading = window.wponion._.replace( $heading, $mached[ $key ], $elem.val() );
					}
				}
			}

			if( $heading === '' ) {
				$heading = window.wponion._.replace( this.option( 'default_heading' ), '[count]', $limit );
			}

			$data.find( '> .wponion-accordion-title span.heading' ).html( $heading );
			$limit++;
		} );

	}

	/**
	 * Handles Javascript Error Placement.
	 * @param err
	 */
	js_error( err ) {
		let $elem = $wponion.IDtoElement( err.element, this.element );
		/* if( $elem ) { //err.error.appendTo( $elem.find( '> .wponion-fieldset' ) ); } */
	}
}

export default ( ( w ) => w.wponion_register_field( 'group', ( $elem ) => new field( $elem ) ) )( window );
