<?php
// Shortcode
function calc_display($atts){
    $atts = shortcode_atts(
        array(
            'calc_id'   => null,
            'type'      => 'profit_calculator'
        ), 
        $atts
    );

    if(!empty($atts['type'])){
        wp_enqueue_style("fwa_calculator_styles");
        wp_enqueue_script("angular");
        if($atts['type'] == 'profit_calculator'){
            wp_enqueue_script("google_charts");
            wp_enqueue_script("angular_profit_calculator");
            $template = file_get_contents(FWA_CALC_DIR . "templates/profitability-calculator.php");
            return $template;
        
        }elseif($atts['type'] == 'valuation_calculator'){
            wp_enqueue_script("angular_valuation_calculator");
            $template = file_get_contents(FWA_CALC_DIR . "templates/valuation-calculator.php");
            return $template;
        }
    }
}
add_shortcode( 'fwa_calculator', 'calc_display' );