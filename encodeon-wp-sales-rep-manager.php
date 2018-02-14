<?php
/*
Plugin Name: Encodeon Sales Rep Management
Description: This plugin adds a custom table to manage sales reps.
Version:     1.1.4
Author:      Phuong Nguyen
*/

// Security Check: Kill script if user is attempting to access this file directly
defined('ABSPATH') or die('Access Restricted');

// register activation hook for when the plugin is installed.
register_activation_hook(__FILE__, 'encodeon_sales_rep_manager_activate');
function encodeon_sales_rep_manager_activate($network_wide) {
    require_once dirname(__FILE__).'/encodeon-wp-sales-rep-manager-loader.php';
    $loader = new EncodeonSalesRepManagerLoader();
    $loader->db_install();
}

// autoloader
function encodeon_sales_rep_manager_autoloader($class_name) 
{
    $plugin_namespace = 'EncodeonSalesRepManager';

    if (false !== strpos($class_name, $plugin_namespace)) {
        $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR;
        $class_file = str_replace($plugin_namespace, '', $class_name) . '.php';
        $class_file = str_replace("\\", DIRECTORY_SEPARATOR, $class_file);
        require_once $classes_dir . 'plugin' . DIRECTORY_SEPARATOR . $class_file;
    }
}
spl_autoload_register('encodeon_sales_rep_manager_autoloader');

// run the plugin
$plugin = new \EncodeonSalesRepManager\Plugin;
