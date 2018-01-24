<?php
namespace EncodeonSalesRepManager\Views\Admin\SalesRep;
use EncodeonSalesRepManager\Models\SalesRep;
class SalesRepList
{
    public function __construct()
    {
        $this->add_submenu();
    }

    public function add_submenu()
    {
        add_submenu_page( 
            'sales-rep-manager', 
            'Sales Rep List', 
            'List', 
            'manage_options', 
            'sales-rep-mananger-list', 
            array($this, 'submenu_page') 
        );
    }

    public function submenu_page()
    {
        ?>

        <h2>Sales Reprsentatives</h2>

        <?php $sales_rep = new SalesRep; $sales_rep->show(); ?>

        <?php 
    }
}
