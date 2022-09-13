<?php

/*
Plugin Name: Woo API Order Creator
Plugin URI:
Description: This plugin allows to save orders on the cloud
Version: 0.1
Author: Jorge Blanco Suárez
Author URI: jorgewebcuba.000webhostapp.com/curriculum-vitae/
License: GPL2+
*/


register_activation_hook(__FILE__, 'oc_plugin_activation');

function oc_plugin_activation()
{
    add_option("oc_enable", true);
    add_option("oc_description", "");
    add_option("oc_webshop_id", "");
}

register_deactivation_hook(__FILE__, 'oc_plugin_deactivation');

function oc_plugin_deactivation()
{
    delete_option("oc_enable");
    delete_option("oc_description");
    delete_option("oc_webshop_id");
}

require __DIR__ . '/vendor/autoload.php';

require 'plugin_system/Init.php';

require 'controllers/AdminController.php';
require 'controllers/CheckoutController.php';

new oc_Init();
