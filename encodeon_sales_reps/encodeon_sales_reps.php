<?php
/*
Plugin Name: Encodeon Sales Rep Management
Description: This plugin adds a custom table to manage sales reps.
Version:     1.0
Author:      Phuong Nguyen
*/

// Security Check: Kill script if user is attempting to access this file directly
defined('ABSPATH') or die('Access Restricted');

// Autoload classes
function encodeon_sales_rep_autoloader($class_name) 
{
    $plugin_namespace = 'EncodeonSalesReps';

    if (false !== strpos($class_name, $plugin_namespace)) {
        $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR;
        $class_file = str_replace($plugin_namespace . '\\', '', $class_name) . '.php';
        require_once $classes_dir . 'plugin\\' . $class_file;
    }
}
spl_autoload_register('encodeon_sales_rep_autoloader');

$product_manager_plugin = new \EncodeonSalesReps\Plugin();
