<?php
namespace EncodeonSalesRepManager;
class Plugin
{
    public function __construct()
    {
        $this->run_controllers();

        add_action('admin_menu', array($this, 'on_wp_admin_menu'));

    }

    public function on_wp_admin_menu()
    {
        new Views\Admin\MainMenu;
        new Views\Admin\SalesRep\ShowView;
    }

    public function run_controllers()
    {
        new Controllers\SalesRepAjaxController;
    }
}
