(function ($) {
    "use strict";

    // Reapply function: finds each agenix button and reapplies inline CSS variables
    function reapplyAgenixButtonVars(scope) {
        // scope is optional: if provided, limit to that widget's DOM
        var root = scope ? $(scope) : $(document);
        root.find(".agenix-btn").each(function () {
            var $btn = $(this);
            // If inline vars already present, keep them; otherwise try to read data attributes (Elementor preview provides settings)
            // We rely on the widget render to set inline vars; this is a safety reapply for editor preview.
            var vars = $btn.attr("style") || "";
            // Force reflow to ensure CSS updates apply
            $btn.css("transform", "translateZ(0)");
            setTimeout(function () {
                $btn.css("transform", "");
            }, 20);
        });
    }

    // Hook into Elementor editor events
    function initEditorHooks() {
        if (!window.elementor) return;

        // When a widget panel opens, reapply after a short delay
        try {
            window.elementor.hooks.addAction(
                "panel/open_editor/widget",
                function (panel, model) {
                    if (
                        model &&
                        model.get &&
                        model.get("widgetType") === "agenix_button"
                    ) {
                        setTimeout(function () {
                            // Reapply for the whole preview iframe
                            reapplyAgenixButtonVars(window.elementor.$previewContents);
                            // Trigger frontend element ready so any JS initializers run
                            if (window.elementorFrontend && window.elementorFrontend.hooks) {
                                window.elementorFrontend.hooks.doAction(
                                    "frontend/element_ready/global",
                                );
                            }
                        }, 80);
                    }
                },
            );
        } catch (e) {
            // ignore
        }

        // When preview is loaded, reapply
        try {
            window.elementor.on("preview:loaded", function () {
                setTimeout(function () {
                    reapplyAgenixButtonVars(window.elementor.$previewContents);
                }, 60);
            });
        } catch (e) {
            // ignore
        }

        // Listen for model changes (widget settings updates) and reapply for that widget
        try {
            if (window.elementor.channels && window.elementor.channels.data) {
                window.elementor.channels.data.on("change", function (model) {
                    try {
                        if (
                            model &&
                            model.get &&
                            model.get("widgetType") === "agenix_button"
                        ) {
                            // Find the widget preview element by model.cid
                            var cid = model.cid;
                            var $preview = window.elementor.$previewContents;
                            if ($preview && cid) {
                                // elementor adds data-elementor-id or similar; fallback to reapply globally
                                setTimeout(function () {
                                    reapplyAgenixButtonVars($preview);
                                    if (
                                        window.elementorFrontend &&
                                        window.elementorFrontend.hooks
                                    ) {
                                        window.elementorFrontend.hooks.doAction(
                                            "frontend/element_ready/global",
                                        );
                                    }
                                }, 60);
                            }
                        }
                    } catch (err) {
                        /* ignore per-widget errors */
                    }
                });
            }
        } catch (e) {
            // ignore
        }
    }

    // DOM ready in editor context
    $(function () {
        initEditorHooks();
    });
})(jQuery);
