<?php
/**
 * Plugin Name: Neuralab WooCommerce Email Templates Preview
 * Plugin URI: http://neuralab.net
 * Description: With this plugin you can easely preview all WooCommerce Email templates.
 * Version: 0.1
 * Author: Neuralab
 * Author URI: http://neuralab.net
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit;
}

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    
    // include plugin files
    include('nrlb_functions.php');
    include('nrlb_email-trigger.php');
    
    
    // set admin email classes for test buttons
    $test_admin_email_class = array(
        'WC_Email_New_Order'       => 'New Order',
        'WC_Email_Cancelled_Order' => 'Canceled Order',
        'WC_Email_Failed_Order'    => 'Failed Order'
    );
    
    // set customer email classes for test buttons
    $test_customer_email_class = array(
        'WC_Email_Customer_New_Account'      => 'New Account',
        'WC_Email_Customer_Processing_Order' => 'Processing Order',
        'WC_Email_Customer_On_Hold_Order'    => 'Order On Hold',
        'WC_Email_Customer_Completed_Order'  => 'Completed Order',
        'WC_Email_Customer_Invoice'          => 'Invoice',
        'WC_Email_Customer_Refunded_Order'   => 'Refunded Order',
        'WC_Email_Customer_Note'             => 'Note',
        'WC_Email_Customer_Reset_Password'   => 'Reset Password'
    );
    
    
    if (is_admin()) {
        
        // register admin page and add menu
        add_action('admin_menu', 'nrlb_register_test_email_submenu_page');
        
        function nrlb_register_test_email_submenu_page() {
            add_submenu_page('woocommerce', 'Email Test', 'Email Test', 'manage_options', 'nrlb-preview-woocommerce-emails', 'nrlb_register_test_email_submenu_page_callback');
        }
        
        function nrlb_register_test_email_submenu_page_callback() {
            include('nrlb_page_layout.php');
        }
    }
}
