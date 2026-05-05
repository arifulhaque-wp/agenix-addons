// Editor JS (enqueue this in your plugin/theme for the editor only)
( function( $, elementor ) {
    'use strict';
    var attach = function() {
        var panel = elementor.getPanelView();
        if ( ! panel ) return;
        panel.$el.on( 'change', '[data-setting="icon_position"]', function() {
            var $el = $( this );
            var val = $el.prop('type') === 'checkbox' ? ($el.is(':checked') ? 'right' : 'left') : ($el.val() || 'left');
            try {
                var pageView = panel.getCurrentPageView();
                var model = pageView.getActiveModel ? pageView.getActiveModel() : pageView.model;
                if ( ! model ) return;
                var settings = _.clone( model.get('settings') || {} );
                settings.icon_position = val;
                model.set('settings', settings);
                pageView.render();
            } catch ( e ) { console.warn(e); }
        } );
    };
    if ( window.elementor ) elementor.on( 'panel:ready', attach );
    else $( window ).on( 'elementor:init', attach );
} )( jQuery, window.elementor );
