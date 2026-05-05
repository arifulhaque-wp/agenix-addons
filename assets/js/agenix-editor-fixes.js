(function ($, elementor) {
    'use strict';

    var ensureDefaults = function () {
        var panel = elementor.getPanelView();
        if (!panel) return;
        var pageView = panel.getCurrentPageView();
        if (!pageView) return;
        var model = pageView.getActiveModel ? pageView.getActiveModel() : pageView.model;
        if (!model) return;

        var settings = _.clone(model.get('settings') || {});
        var changed = false;

        // Ensure icon_position is a string
        if (typeof settings.icon_position === 'undefined' || settings.icon_position === null) {
            settings.icon_position = 'left';
            changed = true;
        } else if (typeof settings.icon_position === 'boolean') {
            settings.icon_position = settings.icon_position ? 'right' : 'left';
            changed = true;
        }

        // Ensure icon has a safe default
        if (settings.icon_source === 'elementor' &&
            (!settings.icon || !settings.icon.value)) {
            settings.icon = { value: 'fas fa-star', library: 'fa-solid' };
            changed = true;
        }

        if (changed) {
            model.set('settings', settings);
            setTimeout(function () { pageView.render(); }, 30);
        }
    };

    var clearInlineTransforms = function () {
        document.querySelectorAll('.agenix-button .agenix-hover-overlay').forEach(function (el) {
            if (el && el.style && el.style.transform) {
                el.style.transform = '';
            }
        });
    };

    var attach = function () {
        elementor.on('panel:render', ensureDefaults);
        elementor.on('panel:open', ensureDefaults);
        ensureDefaults();

        var panel = elementor.getPanelView();
        if (panel) {
            panel.$el.on('change',
                '[data-setting="icon_position"], [data-setting="icon"], [data-setting="icon_source"], [data-setting="hover_reveal_effect"]',
                function () {
                    setTimeout(ensureDefaults, 20);
                    setTimeout(clearInlineTransforms, 40);
                });
        }

        elementor.on('panel:render', function () {
            setTimeout(clearInlineTransforms, 50);
        });
    };

    if (window.elementor) {
        elementor.on('panel:ready', attach);
    } else {
        $(window).on('elementor:init', attach);
    }
})(jQuery, window.elementor);
