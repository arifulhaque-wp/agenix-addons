<?php
/**
 * Plugin Name:         Agenix Addons
 * Plugin URI:          https://examplee.com/plugins/the-basics/
 * Author:              Agenix Team
 * Author URI:          https://author.examplee.com/
 * Description:         Elementor widgets with full styling controls. Works standalone or bundled with Agenix theme.
 * Version:             1.0.0
 * Requires at least:   5.2
 * Requires PHP:        7.2
 * Text Domain:         agenix-addons
 * Requires Plugins:    elementor
 * Elementor tested up to: 3.25.0
 * Elementor Pro tested up to: 3.25.0
 * Domain Path:         /languages
 * License:             GPL v2 or later
 * License URI:         https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'AGENIX_ADDONS_DIR', plugin_dir_path( __FILE__ ) );
define( 'AGENIX_ADDONS_URL', plugin_dir_url( __FILE__ ) );

/* ------------------------------------------------------------------
 * Admin menu + Dashboard
 * ------------------------------------------------------------------ */
add_action( 'admin_menu', function() {
    add_menu_page(
        'Agenix Addons',
        'Agenix Addons',
        'manage_options',
        'agenix-addons',
        'agenix_addons_dashboard',
        'dashicons-screenoptions',
        4
    );
});

/* -------------------------
 * Dashboard with tabs (General | Widgets)
 * ------------------------- */
function agenix_addons_dashboard() {
    $tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'general';
    $enabled_widgets = get_option( 'agenix_enabled_widgets', [] );
    $global_opts = get_option( 'agenix_global_settings', [] );

    ?>
    <div id="agenix-di-wrap" class="agenix-di-wrap">
        <h1><?php esc_html_e( 'Agenix Addons', 'agenix-addons' ); ?></h1>
        <h2 class="nav-tab-wrapper">
            <a href="<?php echo esc_url( add_query_arg( 'tab', 'general' ) ); ?>" class="nav-tab <?php echo $tab === 'general' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'General', 'agenix-addons' ); ?></a>
            <a href="<?php echo esc_url( add_query_arg( 'tab', 'widgets' ) ); ?>" class="nav-tab <?php echo $tab === 'widgets' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Widgets', 'agenix-addons' ); ?></a>
            <a href="<?php echo esc_url( add_query_arg( 'tab', 'contacts' ) ); ?>" class="nav-tab <?php echo $tab === 'contacts' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Contacts', 'agenix-addons' ); ?></a>
        </h2>
        <div class="agenix-di-content">
            <?php if ( $tab === 'general' ) : ?>
                <form method="post" action="options.php">
                    <?php
                    // settings_fields( 'agenix_global_group' );
                    do_settings_sections( 'agenix-general' );
                    // submit_button( __( 'Save General Settings', 'agenix-addons' ) );
                    ?>
                </form>
            <?php endif; ?>
            <?php if ( $tab === 'widgets' ) : ?>
                <form method="post" action="options.php">
                    <?php
                    settings_fields( 'agenix_addons_group' );   // outputs nonce + option_group
                    do_settings_sections( 'agenix-addons' );    // outputs all fields registered
                    submit_button( __( 'Save Widgets', 'agenix-addons' ) );
                    ?>
                </form>
            <?php endif; ?>
            <?php if ( $tab === 'contacts' ) : ?>                 
                    <h3><?php esc_html_e('Demo Contacts','agenix-extends'); ?></h3>
                    <p>Support: support@example.com</p>
                    <p>Sales: sales@example.com</p>
            <?php endif; ?>

        </div>
    </div>
    <?php
}

/* ------------------------------------------------------------------
 * Add Settings link on Plugins page
 * ------------------------------------------------------------------ */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function($links) {
    $settings_link = '<a href="' . esc_url(admin_url('admin.php?page=agenix-addons')) . '">' . __('Settings', 'agenix-addons') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
});


/* ------------------------------------------------------------------
 * Register widget toggles
 * ------------------------------------------------------------------ */
add_action( 'admin_init', function() {
    register_setting(
        'agenix_addons_group',
        'agenix_enabled_widgets',
        [
            'type' => 'array',
            'sanitize_callback' => function( $value ) {
                $out = [];
                if ( is_array( $value ) ) {
                    foreach ( $value as $k => $v ) {
                        $out[ sanitize_key( $k ) ] = ! empty( $v ) ? 1 : 0;
                    }
                }
                return $out;
            },
        ]
    );

    add_settings_section(
        'agenix_global_section',
        __( 'General', 'agenix-addons' ),
        function() {
            echo '<p>' . esc_html__( 'What\'s New In Agenix Addons?', 'agenix-addons' ) . '</p>';
        },
        'agenix-general'
    );

    add_settings_section(
        'agenix_addons_section',
        __( 'Available Widgets', 'agenix-addons' ),
        function() {
            echo '<p>' . esc_html__( 'Toggle widgets on or off. Disabled widgets will not be registered with Elementor.', 'agenix-addons' ) . '</p>';
        },
        'agenix-addons'
    );

    add_settings_field(
        'agenix_button_widget',
        __( 'Agenix Button', 'agenix-addons' ),
        function() {
            $options = get_option( 'agenix_enabled_widgets', [] );
            $enabled = ! empty( $options['button'] );
            ?>
            <label class="agenix-toggle">
                <input type="checkbox" name="agenix_enabled_widgets[button]" value="1" <?php checked( $enabled ); ?> />
                <span class="slider" aria-hidden="true"></span>
            </label>
            <span style="margin-left:10px;vertical-align:middle;"><?php esc_html_e( 'Enable Agenix Button', 'agenix-addons' ); ?></span>
            <?php
        },
        'agenix-addons',
        'agenix_addons_section'
    );

    add_settings_field(
        'agenix_cf7_widget',
        __( 'Agenix Form', 'agenix-addons' ),
        function() {
            $options = get_option( 'agenix_enabled_widgets', [] );
            $enabled = ! empty( $options['cf7'] );
            ?>
            <label class="agenix-toggle">
                <input type="checkbox" name="agenix_enabled_widgets[cf7]" value="1" <?php checked( $enabled ); ?> />
                <span class="slider" aria-hidden="true"></span>
            </label>
            <span style="margin-left:10px;vertical-align:middle;"><?php esc_html_e( 'Enable Agenix Form', 'agenix-addons' ); ?></span>
            <?php
        },
        'agenix-addons',
        'agenix_addons_section'
    );

});

/* ------------------------------------------------------------------
 * Admin CSS for toggles
 * ------------------------------------------------------------------ */
add_action( 'admin_enqueue_scripts', function( $hook ) {
    if ( 'toplevel_page_agenix-addons' !== $hook ) {
        return;
    }
    wp_enqueue_style( 'agenix-addons-admin', AGENIX_ADDONS_URL . 'assets/css/admin.css', [], '1.1.0' );
} );

/* ------------------------------------------------------------------
 * Frontend + Editor CSS
 * ------------------------------------------------------------------ */
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style( 'agenix-addons-frontend', AGENIX_ADDONS_URL . 'assets/css/frontend.css', [], '1.1.0' );
});
add_action( 'elementor/editor/after_enqueue_styles', function() {
    wp_enqueue_style( 'agenix-addons-frontend' );
});

add_action( 'elementor/editor/after_enqueue_styles', function() {
    wp_enqueue_style( 'agenix-editor-styles', plugin_dir_url( __FILE__ ) . 'assets/css/agenix-editor.css', [], '1.0.0' );
} );

add_action( 'elementor/editor/after_enqueue_scripts', function() {
    wp_enqueue_script( 'agenix-editor-fixes', plugin_dir_url( __FILE__ ) . 'assets/js/agenix-editor-fixes.js', [ 'jquery' ], '1.0.0', true );
} );


/* ------------------------------------------------------------------
 * Admin notice if Elementor missing
 * ------------------------------------------------------------------ */
add_action( 'admin_notices', function() {
    if ( current_user_can( 'activate_plugins' ) && ! did_action( 'elementor/loaded' ) ) {
        echo '<div class="notice notice-warning"><p>';
        echo esc_html__( 'Agenix Addons: Elementor is not active. Please install and activate Elementor to use Agenix widgets.', 'agenix-addons' );
        echo '</p></div>';
    }
} );

/* ------------------------------------------------------------------
 * Register Custom Elementor category
 * ------------------------------------------------------------------ */
add_action( 'elementor/elements/categories_registered', function( $elements_manager ) {
    $elements_manager->add_category(
        'agenix-addons',
        [
            'title' => __( 'Agenix Addons', 'agenix-addons' ),
            'icon'  => 'fa fa-plug',
        ]
    );
});

/* ------------------------------------------------------------------
 * Register widgets safely
 * ------------------------------------------------------------------ */
add_action( 'elementor/widgets/register', function( $widgets_manager ) {
    $options = get_option( 'agenix_enabled_widgets', [] );

    if ( ! empty( $options['button'] ) ) {
        $file = AGENIX_ADDONS_DIR . 'widgets/class-agenix-button-widget.php';
        if ( file_exists( $file ) ) {
            require_once $file;
            if ( class_exists( 'Agenix_Button_Widget' ) ) {
                $widgets_manager->register( new \Agenix_Button_Widget() );
            }
        }
    }

    if ( ! empty( $options['cf7'] ) ) {
        $file = AGENIX_ADDONS_DIR . 'widgets/class-agenix-form-widget.php';
        if ( file_exists( $file ) ) {
            require_once $file;
            if ( class_exists( 'Agenix_Form_Widget' ) ) {
                $widgets_manager->register( new \Agenix_Form_Widget() );
            }
        }
    }
});

/* ------------------------------------------------------------------
 * Defensive editor guard + critical CSS to avoid crashes and FOUC
 * - Waits for elementor to be available before attaching handlers
 * - Normalizes originalEvent.key to avoid toLowerCase crash
 * - Injects minimal critical CSS so editor renders without flash
 * ------------------------------------------------------------------ */
add_action( 'elementor/editor/after_enqueue_scripts', function() {
    // Inline JS guard (waits for elementor then attaches)
    $guard_js = <<<JS
    (function($){
        function attachGuard() {
            try {
                if ( typeof elementor === 'undefined' || ! elementor ) {
                    return false;
                }
                // Normalize originalEvent.key for input-like events
                $(document).on('input keydown keyup keypress', function(e){
                    try {
                        if ( e && e.originalEvent && typeof e.originalEvent.key === 'undefined' ) {
                            e.originalEvent.key = '';
                        }
                    } catch(err) {}
                });
                return true;
            } catch(err) {
                return false;
            }
        }

        if ( ! attachGuard() ) {
            var attempts = 0;
            var timer = setInterval(function(){
                if ( attachGuard() || ++attempts > 50 ) {
                    clearInterval(timer);
                }
            }, 100);
        }
    }
)(jQuery);
JS;
    wp_add_inline_script( 'elementor-editor', $guard_js );

    // Minimal critical CSS to avoid FOUC in editor
    $critical_css = "
    .agenix-button { display:inline-block; padding:12px 24px; background:#0073aa; color:#fff; text-decoration:none; border-radius:4px; line-height:1; text-align:center; box-sizing:border-box; font-weight:600; }
    .agenix-button[role='button'] { cursor:default; }
    .agenix-widget-hidden { visibility:hidden; opacity:0; transition:opacity .15s ease; }
    .agenix-widget-ready { visibility:visible; opacity:1; }
    ";
    wp_add_inline_style( 'elementor-editor', $critical_css );
}, 20 );




/* ------------------------------------------------------------------
 * Load textdomain
 * ------------------------------------------------------------------ */
add_action( 'init', function() {
    load_plugin_textdomain( 'agenix-addons', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
});

