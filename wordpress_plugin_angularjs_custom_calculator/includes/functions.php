<?php
    function load_kpm_angular_app(){
        wp_enqueue_script('angular');
        wp_enqueue_script('angular_loader');
        wp_enqueue_script('angular_route');
        wp_enqueue_script('angular_app');
        wp_enqueue_script('angular_ngdialog');
        wp_enqueue_style('angular_ngdialog_style');
        wp_enqueue_style('angular_ngdialog_style_default');
        wp_enqueue_style('angular_ngdialog_style_plain');
    }