( function( $ ) {
    'use strict';
    // Simple enhancement: add aria-pressed on click for accessibility (toggle not implemented)
    $( document ).on( 'click', '.agenix-btn', function() {
        var $btn = $( this );
        $btn.attr( 'aria-pressed', 'true' );
        setTimeout( function() { $btn.removeAttr( 'aria-pressed' ); }, 300 );
    } );
} )( jQuery );
