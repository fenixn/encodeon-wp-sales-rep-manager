<?php
namespace EncodeonSalesRepManager\Views\Admin;
use EncodeonSalesRepManager\Models\SalesRep\Show;
class MainMenu extends \EncodeonSalesRepManager\Plugin
{
    public function __construct()
    {
        $this->add_top_level_menu();
        add_action("admin_enqueue_scripts", array($this, "admin_enqueue_scripts"));
    }

    public function add_top_level_menu()
    {
        add_menu_page( 
            "Sales Rep Manager", 
            "Sales Rep Manager", 
            "manage_options", 
            "sales-rep-manager", 
            array($this, "sales_rep_main_menu_page"), 
            "dashicons-businessman", 
            3 
        );
    }
    
    public function admin_enqueue_scripts()
    {
        $tether = true;
        $bootstrap = true;
        $font_awesome = true;
        $jqvmp = false;
        $this->enqueue_scripts($tether, $bootstrap, $font_awesome, $jqvmp);
    }

    public function sales_rep_main_menu_page()
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
