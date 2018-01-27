<?php
namespace EncodeonSalesRepManager\Controllers;
use EncodeonSalesRepManager\Models\SalesRep\SalesRep;
use EncodeonSalesRepManager\Models\SalesRep\Create;
class SalesRepAjaxController
{
    public function __construct()
    {
        add_action('wp_ajax_generate_sales_rep_table', array($this, 'generate_sales_rep_table'));
        add_action('wp_ajax_create_sales_rep', array($this, 'create_sales_rep'));
    }

    public function generate_sales_rep_table()
    {
        $sales_rep_model = new SalesRep;
        $sales_rep_model->generate_sales_rep_table();
        die();
    }

    public function create_sales_rep()
    {
        $sales_rep_create = new Create;
        $sales_rep_create->process_request();
        die();
    }
}
