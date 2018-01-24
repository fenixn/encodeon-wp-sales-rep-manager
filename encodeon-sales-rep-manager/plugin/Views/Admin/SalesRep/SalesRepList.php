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

        <main class="pt-4 px-4">
            <h1>Sales Reprsentatives</h1>
            <?php $sales_rep = new SalesRep; $sales_rep->show(); ?>
        </main>
        
        <?php 
    }
}
