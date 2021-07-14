/*
 Field Color (color)
 */

/*global jQuery, document, reduxsa_change, reduxsa*/

(function( $ ) {
    'use strict';

    reduxsa.field_objects = reduxsa.field_objects || {};
    reduxsa.field_objects.color = reduxsa.field_objects.color || {};

    $( document ).ready(
        function() {

        }
    );

    reduxsa.field_objects.color.init = function( selector ) {

        if ( !selector ) {
            selector = $( document ).find( ".reduxsa-group-tab:visible" ).find( '.reduxsa-container-color:visible' );
        }

        $( selector ).each(
            function() {

                var el = $( this );
                var parent = el;

                if ( !el.hasClass( 'reduxsa-field-container' ) ) {
                    parent = el.parents( '.reduxsa-field-container:first' );
                }
                if ( parent.is( ":hidden" ) ) { // Skip hidden fields
                    return;
                }
                if ( parent.hasClass( 'reduxsa-field-init' ) ) {
                    parent.removeClass( 'reduxsa-field-init' );
                } else {
                    return;
                }
//				var $control = el.find( '.reduxsa-color-init' ),
//                                        
//					value = $control.val().replace( /\s+/g, '' ),
//					alpha_val = 100,
//					$alpha, $alpha_output;
//                                //console.log($control);
//				if ( value.match( /rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/ ) ) {
//					alpha_val = parseFloat( value.match( /rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/ )[ 1 ] ) * 100;
//				}
                el.find( '.reduxsa-color-init' ).wpColorPicker(
                    {
                        change: function( e, ui ) {
                            $( this ).val( ui.color.toString() );
                            reduxsa_change( $( this ) );
                            el.find( '#' + e.target.getAttribute( 'data-id' ) + '-transparency' ).removeAttr( 'checked' );
                        },
                        clear: function( e, ui ) {
                            $( this ).val( '' );
                            reduxsa_change( $( this ).parent().find( '.reduxsa-color-init' ) );
                        }
                    }
                );
//				$( '<div class="reduxsa-alpha-container">'
//				+ '<label>Alpha: <output class="rangevalue">' + alpha_val + '%</output></label>'
//				+ '<input type="range" min="1" max="100" value="' + alpha_val + '" name="alpha" class="vc_alpha-field">'
//				+ '</div>' ).appendTo( $control.parents( '.wp-picker-container:first' ).addClass( 'vc_color-picker' ).find( '.iris-picker' ) );
//				$alpha = $control.parents( '.wp-picker-container:first' ).find( '.vc_alpha-field' );
//                                //console.log($alpha);
//				$alpha_output = $control.parents( '.wp-picker-container:first' ).find( '.reduxsa-alpha-container output' );
//				$alpha.bind( 'change keyup', function () {
//					var alpha_val = parseFloat( $alpha.val() ),
//						iris = $control.data( 'a8cIris' ),
//						color_picker = $control.data( 'wp-wpColorPicker' );
//                                                //console.log(alpha_val);
//					$alpha_output.val( $alpha.val() + '%' );
//                                        console.log(alpha_val / 100.0);
//					iris._color._alpha = parseFloat(alpha_val / 100.0);
//                                        console.log(iris._color);
//					//$control.val( iris._color.toString() );
//                                        el.find( '.reduxsa-color-init' ).wpColorPicker( 'color', iris._color.toString() );
//                                        //console.log($control.val());
//					//color_picker.toggler.css( { backgroundColor: $control.val() } );
//				} ).val( alpha_val ).trigger( 'change' );                                

                el.find( '.reduxsa-color' ).on(
                    'focus', function() {
                        $( this ).data( 'oldcolor', $( this ).val() );
                    }
                );

                el.find( '.reduxsa-color' ).on(
                    'keyup', function() {
                        var value = $( this ).val();
                        var color = colorValidate( this );
                        var id = '#' + $( this ).attr( 'id' );

                        if ( value === "transparent" ) {
                            $( this ).parent().parent().find( '.wp-color-result' ).css(
                                'background-color', 'transparent'
                            );

                            el.find( id + '-transparency' ).attr( 'checked', 'checked' );
                        } else {
                            el.find( id + '-transparency' ).removeAttr( 'checked' );

                            if ( color && color !== $( this ).val() ) {
                                $( this ).val( color );
                            }
                        }
                    }
                );

                // Replace and validate field on blur
                el.find( '.reduxsa-color' ).on(
                    'blur', function() {
                        var value = $( this ).val();
                        var id = '#' + $( this ).attr( 'id' );

                        if ( value === "transparent" ) {
                            $( this ).parent().parent().find( '.wp-color-result' ).css(
                                'background-color', 'transparent'
                            );

                            el.find( id + '-transparency' ).attr( 'checked', 'checked' );
                        } else {
                            if ( colorValidate( this ) === value ) {
                                if ( value.indexOf( "#" ) !== 0 ) {
                                    $( this ).val( $( this ).data( 'oldcolor' ) );
                                }
                            }

                            el.find( id + '-transparency' ).removeAttr( 'checked' );
                        }
                    }
                );

                // Store the old valid color on keydown
                el.find( '.reduxsa-color' ).on(
                    'keydown', function() {
                        $( this ).data( 'oldkeypress', $( this ).val() );
                    }
                );

                // When transparency checkbox is clicked
                el.find( '.color-transparency' ).on(
                    'click', function() {
                        if ( $( this ).is( ":checked" ) ) {
                            el.find( '.reduxsa-saved-color' ).val( $( '#' + $( this ).data( 'id' ) ).val() );
                            el.find( '#' + $( this ).data( 'id' ) ).val( 'transparent' );
                            el.find( '#' + $( this ).data( 'id' ) ).parent().parent().find( '.wp-color-result' ).css(
                                'background-color', 'transparent'
                            );
                        } else {
                            if ( el.find( '#' + $( this ).data( 'id' ) ).val() === 'transparent' ) {
                                var prevColor = $( '.reduxsa-saved-color' ).val();

                                if ( prevColor === '' ) {
                                    prevColor = $( '#' + $( this ).data( 'id' ) ).data( 'default-color' );
                                }

                                el.find( '#' + $( this ).data( 'id' ) ).parent().parent().find( '.wp-color-result' ).css(
                                    'background-color', prevColor
                                );

                                el.find( '#' + $( this ).data( 'id' ) ).val( prevColor );
                            }
                        }
                        reduxsa_change( $( this ) );
                    }
                );
            }
        );
    };
})( jQuery );