<?php
namespace EncodeonSalesRepManager\Models;
class Config extends \EncodeonSalesRepManager\Plugin
{
    public function save_config()
    {
        // Anti CSRF
        if (wp_verify_nonce($_REQUEST["encodeon_sales_rep_manager_save_config_nonce"], "encodeon_sales_rep_manager_save_config") === false) {
            $this->show_error("Invalid nonce for this request.");
            die();
        }

        // Authorize user
        if(!current_user_can("manage_options")) {
            $this->show_error("Invalid authorization.");
            die();
        };

        $page_limit = $_REQUEST["page_limit"];
        $pager_increment = $_REQUEST["pager_increment"];

        // Test if limit input is a positive integer
        if(!preg_match("/^[+]?[1-9]\d*$/", $page_limit)) {
            $this->show_error("Invalid input for sales rep per page.", false);
            die();
        }

        // Test if pager increment is a positive integer
        if(!preg_match("/^[+]?[1-9]\d*$/", $pager_increment)) {
            $this->show_error("Invalid input for pager increment.", false);
            die();
        }

        update_option("encodeon_sales_reps_table_page_limit", $page_limit);
        update_option("encodeon_sales_reps_table_pager_increment", $pager_increment);

        $this->show_success("Your new configurations have been saved.");
    }
}
