<?php
    wp_register_style("fwa_calculator_styles", FWA_CALC_URL . 'assets/css/styles.css' );
    
    wp_register_script( "angular", "https://ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular.min.js");
    wp_register_script( "angular_profit_calculator", FWA_CALC_URL . 'assets/js/profitCalculatorApp.js' );
    wp_register_script( "angular_valuation_calculator", FWA_CALC_URL . 'assets/js/valuationCalculatorApp.js' );
    
    wp_register_script("google_charts", "https://www.gstatic.com/charts/loader.js");