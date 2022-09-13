<?php

class OC_AdminController
{


  static function create_custom_menu()
  {
    add_menu_page('OC_ Admin', 'OC_ Admin', 'manage_options', 'oc_admin-settings', 'OC_AdminController::index',plugin_dir_url(__FILE__) .'../assets/images/antonella-icon.png');
  }

  static function index()
  {
    wp_enqueue_style('css_ajax_response_message_box', plugin_dir_url(__FILE__) . '../assets/libraries/ajax_response_message_box/ajax_response_message_box.css');
    wp_enqueue_script('js_ajax_response_message_box', plugin_dir_url(__FILE__) . '../assets/libraries/ajax_response_message_box/ajax_response_message_box.js');

    wp_enqueue_style('css_admin', plugin_dir_url(__FILE__) . '../assets/css/oc_admin.css');
    wp_enqueue_script('js_admin', plugin_dir_url(__FILE__) . '../assets/js/oc_admin.js');
    wp_add_inline_script('js_admin', 'var url_admin_ajax = "' . admin_url('admin-ajax.php').'";');
    //wp_localize_script('js_admin', 'url_admin_ajax', admin_url('admin-ajax.php'));

    $oc_enable = get_option("oc_enable");
    $oc_description = get_option("oc_description");
    $oc_webshop_id = get_option("oc_webshop_id");    

    OC_Useful::log("$oc_enable - $oc_description - $oc_webshop_id");

    OC_Blade::view('oc_admin', compact('oc_enable','oc_description','oc_webshop_id'));
  }

  static function oc_save_settings()
  {
    $oc_enable = $_POST["oc_enable"];
    $oc_description = $_POST["oc_description"];
    $oc_webshop_id = $_POST["oc_webshop_id"];   

    OC_Useful::log("$oc_enable - $oc_description - $oc_webshop_id");
    
    update_option("oc_enable", $oc_enable);
    update_option("oc_description", $oc_description);
    update_option("oc_webshop_id", $oc_webshop_id);  

    OC_Useful::ajax_server_response(array("message" => "Settings has been saved correctly"));
  }
}
