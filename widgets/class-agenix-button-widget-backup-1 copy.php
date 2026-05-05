<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
    return;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;

class Agenix_Button_Widget extends Widget_Base {

    public function get_name() { return 'agenix_button'; }
    public function get_title() { return __( 'Agenix Button', 'agenix-addons' ); }
    public function get_icon() { return 'eicon-button'; }
    public function get_categories() { return [ 'agenix-addons' ]; }

    protected function register_controls() {

        /* ---------------- Content ---------------- */
        $this->start_controls_section('section_content', [
            'label' => __( 'Button', 'agenix-addons' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('text', [
            'label'       => __( 'Text', 'agenix-addons' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => __( 'Click Me', 'agenix-addons' ),
            'placeholder' => __( 'Click Me', 'agenix-addons' ),
        ]);

        $this->add_control('link', [
            'label'       => __( 'Link', 'agenix-addons' ),
            'type'        => Controls_Manager::URL,
            'placeholder' => __( 'https://your-link.com', 'agenix-addons' ),
            'default'     => [ 'url' => '#' ],
        ]);

        $this->add_control('icon_source', [
            'label'   => __( 'Icon', 'agenix-addons' ),
            'type'    => Controls_Manager::CHOOSE,
            'options' => [
                'none'      => [ 'title' => __( 'None', 'agenix-addons' ), 'icon' => 'eicon-ban' ],
                'svg'       => [ 'title' => __( 'Upload SVG', 'agenix-addons' ), 'icon' => 'eicon-upload' ],
                'elementor' => [ 'title' => __( 'Elementor Icon', 'agenix-addons' ), 'icon' => 'eicon-circle' ],
            ],
            'default' => 'none',
        ]);

        // ICON control with a safe default so editor preview has an icon
        $this->add_control('icon', [
            'label'     => __( 'Icon', 'agenix-addons' ),
            'type'      => \Elementor\Controls_Manager::ICONS,
            'condition' => [ 'icon_source' => 'elementor' ],
            'default'   => [
                'value'   => 'fas fa-star',
                'library' => 'fa-solid',
            ],
        ]);

        $this->add_control('icon_svg', [
            'label'      => __( 'SVG Icon', 'agenix-addons' ),
            'type'       => \Elementor\Controls_Manager::MEDIA,
            'media_type' => 'svg',
            'condition'  => [ 'icon_source' => 'svg' ],
        ]);

        $this->add_control(
            'icon_position',
            [
                'label' => __( 'Icon Position', 'agenix-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left'  => __( 'Left', 'agenix-addons' ),
                    'right' => __( 'Right', 'agenix-addons' ),
                    'none'  => __( 'None', 'agenix-addons' ),
                ],
                'render_type' => 'template',
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'icon_spacing',
            [
                'label' => __( 'Icon Spacing', 'agenix-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                    'em' => [ 'min' => 0, 'max' => 5 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .agenix-button' => '--agenix-icon-gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [ 'icon_source' => [ 'elementor', 'svg' ] ],
            ]
        );

        $this->end_controls_section();

        /* ---------------- Style ---------------- */
        $this->start_controls_section('section_style', [
            'label' => __( 'Button', 'agenix-addons' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control(
            'alignment',
            [
                'label' => __( 'Alignment', 'agenix-addons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'agenix-addons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'agenix-addons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'agenix-addons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .agenix-button-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'typography',
            'selector' => '{{WRAPPER}} .agenix-button',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Text_Shadow::get_type(), [
            'name'     => 'text_shadow',
            'selector' => '{{WRAPPER}} .agenix-button-text',
        ]);

        $this->start_controls_tabs('tabs_button_style');

        // Normal
        $this->start_controls_tab('tab_button_normal', [ 'label' => __( 'Normal', 'agenix-addons' ) ]);

        $this->add_control('button_text_color', [
            'label'     => __( 'Text Color', 'agenix-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .agenix-button' => '--agenix-text-color: {{VALUE}};' ],
        ]);

        $this->add_control('background_color_normal', [
            'label'     => __( 'Background Color', 'agenix-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .agenix-button' => '--agenix-bg: {{VALUE}};', ],
        ]);

        $this->end_controls_tab();

        // Hover
        $this->start_controls_tab('tab_button_hover', [ 'label' => __( 'Hover', 'agenix-addons' ) ]);

        $this->add_control('hover_text_color', [
            'label'     => __( 'Text Color', 'agenix-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .agenix-button:hover' => '--agenix-hover-text-color: {{VALUE}};', ],
        ]);

        $this->add_control('background_color_hover', [
            'label'     => __( 'Background Color', 'agenix-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .agenix-button:hover' => '--agenix-hover-bg: {{VALUE}};',
            ],
        ]);

        $this->add_control('button_hover_border_color', [
            'label'     => __( 'Border Color', 'agenix-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .agenix-button:hover' => 'border-color: {{VALUE}};' ],
        ]);

        $this->add_control('hover_reveal_effect', [
            'label'   => __( 'Hover Reveal Effect', 'agenix-addons' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => [
                ''              => __( 'None', 'agenix-addons' ),
                'left-to-right' => __( 'Left to Right', 'agenix-addons' ),
                'right-to-left' => __( 'Right to Left', 'agenix-addons' ),
                'top-to-bottom' => __( 'Top to Bottom', 'agenix-addons' ),
                'bottom-to-top' => __( 'Bottom to Top', 'agenix-addons' ),
            ],
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .agenix-button' => '--agenix-hover-reveal-effect: {{VALUE}};',
            ],
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'border',
                'selector' => '{{WRAPPER}} .agenix-button',
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                    'width' => [
                        'default' => [
                            'top' => 0,
                            'right' => 0,
                            'bottom' => 0,
                            'left' => 0,
                            'unit' => 'px',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control('border_radius', [
            'label'     => __( 'Border Radius', 'agenix-addons' ),
            'type'      => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units'=> [ 'px', '%' ],
            'selectors' => [ '{{WRAPPER}} .agenix-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'box_shadow_hover',
            'selector' => '{{WRAPPER}} .agenix-button:hover',
        ]);

        $this->add_responsive_control('padding', [
            'label'      => __( 'Padding', 'agenix-addons' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em', 'rem', 'vw' ],
            'selectors'  => [
                '{{WRAPPER}} .agenix-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'separator'  => 'before',
        ]);

        $this->end_controls_section();

        /* ---------------- Icon Style ---------------- */
        $this->start_controls_section('section_icon_style', [
            'label' => __( 'Icon', 'agenix-addons' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('icon_size', [
            'label'      => __( 'Icon Size', 'agenix-addons' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', '%' ],
            'range'      => [ 'px' => [ 'min' => 1, 'max' => 200 ] ],
            'selectors'  => [
                '{{WRAPPER}} .agenix-button .agenix-button-icon, {{WRAPPER}} .agenix-button .agenix-button-icon svg' =>
                    'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
            'default'    => [ 'size' => 16, 'unit' => 'px' ],
        ]);

        $this->add_control('icon_color', [
            'label'     => __( 'Icon Color', 'agenix-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .agenix-button .agenix-button-icon, {{WRAPPER}} .agenix-button .agenix-button-icon svg' =>
                    'color: {{VALUE}}; fill: {{VALUE}}; stroke: {{VALUE}};',
            ],
        ]);

        $this->add_control('icon_hover_color', [
            'label'     => __( 'Icon Hover Color', 'agenix-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .agenix-button:hover .agenix-button-icon, {{WRAPPER}} .agenix-button:hover .agenix-button-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}}; stroke: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $icon_position = ! empty( $settings['icon_position'] ) ? $settings['icon_position'] : 'left';
        $button_text   = ! empty( $settings['text'] ) ? $settings['text'] : __( 'Click Me', 'agenix-addons' );
        $raw_link      = ! empty( $settings['link']['url'] ) ? trim( $settings['link']['url'] ) : '';
        $button_link   = ( $raw_link !== '#' ) ? $raw_link : '';

        // Build classes
        $classes = 'agenix-button';
        if ( ! empty( $settings['icon_source'] ) && $settings['icon_source'] !== 'none' ) {
            $classes .= ' has-icon icon-' . esc_attr( $icon_position );
        }
        if ( ! empty( $settings['hover_reveal_effect'] ) ) {
            $classes .= ' reveal-' . esc_attr( $settings['hover_reveal_effect'] );
        }

        // Render icon HTML (robust)
        $icon_html = '';
        if ( isset( $settings['icon_source'] ) && $settings['icon_source'] === 'elementor' ) {
            if ( is_array( $settings['icon'] ) && ! empty( $settings['icon']['value'] ) ) {
                ob_start();
                \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
                $icon_html = (string) ob_get_clean();
            }
        } elseif ( isset( $settings['icon_source'] ) && $settings['icon_source'] === 'svg' ) {
            if ( ! empty( $settings['icon_svg']['url'] ) ) {
                $icon_html = '<img class="agenix-button-icon-svg" src="' . esc_url( $settings['icon_svg']['url'] ) . '" alt="" />';
            }
        }

        // Wrapper
        echo '<div class="agenix-button-wrapper" style="text-align:' . esc_attr( $settings['alignment'] ?? 'left' ) . ';">';

        // Prepare attributes
        $this->add_render_attribute( 'agenix-button', 'class', $classes );

        if ( $button_link ) {
            $this->add_render_attribute( 'agenix-button', 'href', esc_url( $button_link ) );

            // target and rel handling
            $rel = [];
            if ( ! empty( $settings['link']['is_external'] ) ) {
                $this->add_render_attribute( 'agenix-button', 'target', '_blank' );
                $rel[] = 'noopener';
                $rel[] = 'noreferrer';
            }
            if ( ! empty( $settings['link']['nofollow'] ) ) {
                $rel[] = 'nofollow';
            }
            if ( ! empty( $rel ) ) {
                $this->add_render_attribute( 'agenix-button', 'rel', implode( ' ', array_unique( $rel ) ) );
            }

            echo '<a ' . $this->get_render_attribute_string( 'agenix-button' ) . '>';
        } else {
            $this->add_render_attribute( 'agenix-button', 'role', 'button' );
            echo '<span ' . $this->get_render_attribute_string( 'agenix-button' ) . '>';
        }

        // Icon left
        if ( $icon_position === 'left' && $icon_html ) {
            echo '<span class="agenix-button-icon" aria-hidden="true">' . $icon_html . '</span>';
        }

        // Text
        echo '<span class="agenix-button-text">' . esc_html( $button_text ) . '</span>';

        // Icon right
        if ( $icon_position === 'right' && $icon_html ) {
            echo '<span class="agenix-button-icon" aria-hidden="true">' . $icon_html . '</span>';
        }

        // Hover overlay for reveal effects
        if ( ! empty( $settings['hover_reveal_effect'] ) ) {
            echo '<span class="agenix-hover-overlay" aria-hidden="true"></span>';
        }

        echo $button_link ? '</a>' : '</span>';
        echo '</div>';
    }

    /**
     * Render the widget output in the editor
     */
    protected function _content_template() {
        ?>
        <#
        var buttonText = settings.text || 'Click Me';
        var rawLink = ( settings.link && settings.link.url ) ? settings.link.url : '';
        var buttonLink = ( rawLink && rawLink !== '#' ) ? rawLink : '';

        // Normalize icon position to a string ('left'|'right'|'none')
        var iconPosition = 'left';
        if ( typeof settings.icon_position === 'boolean' ) {
            iconPosition = settings.icon_position ? 'right' : 'left';
        } else if ( typeof settings.icon_position === 'string' && settings.icon_position.length ) {
            iconPosition = settings.icon_position;
        }

        // Render icon HTML safely and force it to a string
        var iconHTML = '';
        try {
            if ( settings.icon_source === 'elementor' && settings.icon && settings.icon.value ) {
                var iconObj = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i', 'object' );
                if ( iconObj ) {
                    if ( typeof iconObj === 'object' && iconObj.rendered ) {
                        iconHTML = String( iconObj.rendered );
                    } else if ( typeof iconObj === 'string' ) {
                        iconHTML = String( iconObj );
                    }
                }
            } else if ( settings.icon_source === 'svg' && settings.icon_svg && settings.icon_svg.url ) {
                iconHTML = '<img class="agenix-button-icon-svg" src="' + settings.icon_svg.url + '" alt="" />';
            }
        } catch ( e ) {
            iconHTML = '';
        }
        if ( ! iconHTML || typeof iconHTML !== 'string' || iconHTML.length === 0 ) {
            iconHTML = '';
        }

        var classes = 'agenix-button';
        if ( iconHTML ) { classes += ' has-icon icon-' + iconPosition; }
        if ( settings.hover_reveal_effect ) { classes += ' reveal-' + settings.hover_reveal_effect; }

        // Link target/rel for editor preview
        var linkTarget = ( settings.link && settings.link.is_external ) ? '_blank' : '';
        var linkRel = '';
        if ( settings.link && settings.link.is_external ) { linkRel = 'noopener noreferrer'; }
        if ( settings.link && settings.link.nofollow ) {
            linkRel = linkRel ? (linkRel + ' nofollow') : 'nofollow';
        }
        #>

        <div class="agenix-button-wrapper" style="text-align: {{ settings.alignment || 'left' }};">
            <# if ( buttonLink ) { #>
                <a href="{{ buttonLink }}" class="{{ classes }}" <# if ( linkTarget ) { #> target="{{ linkTarget }}" <# } #> <# if ( linkRel ) { #> rel="{{ linkRel }}" <# } #> >
                    <# if ( iconPosition === 'left' && iconHTML ) { #>
                        <span class="agenix-button-icon" aria-hidden="true">{{{ iconHTML }}}</span>
                    <# } #>
                    <span class="agenix-button-text">{{{ buttonText }}}</span>
                    <# if ( iconPosition === 'right' && iconHTML ) { #>
                        <span class="agenix-button-icon" aria-hidden="true">{{{ iconHTML }}}</span>
                    <# } #>
                    <# if ( settings.hover_reveal_effect ) { #>
                        <span class="agenix-hover-overlay" aria-hidden="true"></span>
                    <# } #>
                </a>
            <# } else { #>
                <span class="{{ classes }}" role="button">
                    <# if ( iconPosition === 'left' && iconHTML ) { #>
                        <span class="agenix-button-icon" aria-hidden="true">{{{ iconHTML }}}</span>
                    <# } #>
                    <span class="agenix-button-text">{{{ buttonText }}}</span>
                    <# if ( iconPosition === 'right' && iconHTML ) { #>
                        <span class="agenix-button-icon" aria-hidden="true">{{{ iconHTML }}}</span>
                    <# } #>
                    <# if ( settings.hover_reveal_effect ) { #>
                        <span class="agenix-hover-overlay" aria-hidden="true"></span>
                    <# } #>
                </span>
            <# } #>
        </div>
        <?php
    }
}

// Register widget
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Agenix_Button_Widget() );

/**
 * Enqueue editor fixes script (coerce boolean switchers to strings)
 * Place this in the same plugin file or in a bootstrap file that runs in plugin context.
 */
add_action( 'elementor/editor/after_enqueue_scripts', function() {
    $handle = 'agenix-editor-fixes';
    $src = plugin_dir_url( __FILE__ ) . 'assets/js/agenix-editor-fixes.js';
    wp_enqueue_script( $handle, $src, [ 'jquery' ], '1.0.0', true );
} );
