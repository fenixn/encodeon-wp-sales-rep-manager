<?php
namespace EncodeonSalesRepManager\Views\Admin\SalesRep;
use EncodeonSalesRepManager\Models\SalesRep\Show;
class ShowView
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
            'sales-rep-manager-list', 
            array($this, 'submenu_page') 
        );
    }

    public function submenu_page()
    {
        ?>

        <main class="container-fluid mt-2">
            <h1>Sales Representatives</h1>

            <?php (new \EncodeonSalesRepManager\Views\Partials\StatusMessage)->render(); ?>

            <?php $sales_rep_show = new Show; $sales_rep_show->render(); ?>
        </main>
        
        <?php 
    }
}
