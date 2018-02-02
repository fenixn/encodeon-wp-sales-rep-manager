<?php
namespace EncodeonSalesRepManager\Controllers;
use EncodeonSalesRepManager\Models\Config;
class ConfigAjaxController
{
    public function __construct()
    {
        add_action("wp_ajax_encodeon_sales_rep_manager_save_config", array($this, "encodeon_sales_rep_manager_save_config"));
    }

    public function encodeon_sales_rep_manager_save_config()
    {
        $config_model = new Config;
        $config_model->save_config();
        die();
    }
}
