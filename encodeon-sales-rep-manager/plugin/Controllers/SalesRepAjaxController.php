<?php
namespace EncodeonSalesRepManager\Controllers;
use EncodeonSalesRepManager\Models\SalesRep\SalesRep;
class SalesRepAjaxController
{
    public function __construct()
    {
        add_action('wp_ajax_generate_sales_rep_table', array($this, 'generate_sales_rep_table'));
    }

    public function generate_sales_rep_table()
    {
        $sales_rep_model = new SalesRep;
        $sales_rep_model->generate_sales_rep_table();
        die();
    }
}
