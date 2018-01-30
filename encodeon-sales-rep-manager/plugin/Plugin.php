<?php
namespace EncodeonSalesRepManager;
class Plugin
{
    public function __construct()
    {
        $this->run_controllers();

        add_action('admin_menu', array($this, 'on_wp_admin_menu'));
        add_action('wp_enqueue_scripts', array($this, 'frontend_enqueue_scripts'));
    }

    public function on_wp_admin_menu()
    {
        new Views\Admin\MainMenu;
        new Views\Admin\SalesRep\CreateView;
        new Views\Admin\SalesRep\EditView;
    }

    public function frontend_enqueue_scripts()
    {
        new Views\Frontend\SalesRep\Map;

        // Enqueue Bootstrap. Does a cursory check first to see if a bootstrap handle already exists
        if( ( ! wp_style_is( 'bootstrap', 'queue' ) ) && 
            ( ! wp_style_is( 'bootstrap', 'done' ) ) ) {
            wp_enqueue_style(
                'bootstrap',
                plugins_url('encodeon-sales-rep-manager/vendor/twbs/bootstrap/dist/css/bootstrap.min.css'),
                array(),
                '4.00'
            );

            wp_enqueue_script(
                'bootstrap',
                plugins_url('encodeon-sales-rep-manager/vendor/twbs/bootstrap/dist/js/bootstrap.min.js'),
                array('jquery'),
                '4.00'
            );
        }

        // Enqueue Jquery Vector Map
        if((!wp_style_is('jqvmap', 'queue')) && 
           (!wp_style_is('jqvmap', 'done'))) {
            wp_enqueue_style(
                'jqvmap',
                plugins_url('encodeon-sales-rep-manager/vendor/jqvmap-master/dist/jqvmap.min.css'),
                array(),
                '1.5.0'
            );

            wp_enqueue_script(
                'jqvmap',
                plugins_url('encodeon-sales-rep-manager/vendor/jqvmap-master/dist/jquery.vmap.min.js'),
                array('jquery'),
                '1.5.0'
            );

            wp_enqueue_script(
                'jqvmap-usa',
                plugins_url('encodeon-sales-rep-manager/vendor/jqvmap-master/dist/maps/jquery.vmap.usa.js'),
                array('jquery'),
                '1.5.0'
            );
        }
    }

    public function run_controllers()
    {
        new Controllers\SalesRepAjaxController;
    }
}
