<?php
namespace EncodeonSalesRepManager\Views\Admin;
class Config
{
    public function __construct()
    {
        $this->add_submenu();
    }

    public function add_submenu()
    {
        add_submenu_page( 
            'sales-rep-manager', 
            'Config', 
            'Config', 
            'manage_options', 
            'sales-rep-manager-config', 
            array($this, 'submenu_page') 
        );
    }

    public function submenu_page()
    {
        ?>
        <main class="container-fluid mt-2">
            <h1>Sales Rep Config</h1>
            <div class="row-fluid">
                <div class="col-md-12">Test</div>
            </div>
        </main>
        <?php
    }
}
