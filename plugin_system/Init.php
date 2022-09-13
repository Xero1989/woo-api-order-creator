<?php

class OC_Init
{
    private $checkoutController;

    function __construct()
    {

        $this->checkoutController = new XUP_CheckoutController();

        $this->load_helpers();

        $this->load_filters();

        $this->load_actions();

        $this->load_shortcodes();
    }


    function load_helpers()
    {
        foreach (glob(__DIR__ . "/Helpers/*.php") as $filename) {
            require $filename;
        }
    }

    function load_filters()
    {
        add_filter('woocommerce_checkout_fields', [$this->checkoutController, 'add_checkout_fields'], 10, 2);
    }

    function load_actions()
    {
        //Admin menu version
        // add_action('admin_menu', 'OC_AdminController::create_custom_menu');
        // add_action('wp_ajax_oc_save_settings', 'OC_AdminController::oc_save_settings');
       
        

        add_action('woocommerce_checkout_process', [$this->checkoutController, 'woocommerce_checkout_process']);

    }

    function load_shortcodes()
    {
    }
}
