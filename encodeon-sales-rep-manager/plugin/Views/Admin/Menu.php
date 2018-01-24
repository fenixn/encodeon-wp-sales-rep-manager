<?php
namespace EncodeonSalesRepManager\Views\Admin;
class Menu
{
    public function __construct()
    {
        $this->add_top_level_menu();
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    }

    public function add_top_level_menu()
    {
        add_menu_page( 
            'Sales Rep Manager', 
            'Sales Rep Manager', 
            'manage_options', 
            'sales-rep-manager', 
            array($this, 'sales_rep_main_menu_page'), 
            'dashicons-businessman', 
            3 
        );
    }
    
    public function admin_enqueue_scripts()
    {
        // Enqueue Bootstrap. Does a cursory check first to see if a bootstrap handle already exists
        if( ( ! wp_style_is( 'bootstrap', 'queue' ) ) && 
            ( ! wp_style_is( 'bootstrap', 'done' ) ) ) {
            wp_enqueue_style(
                'bootstrap',
                plugins_url('encodeon-sales-rep-manager/vendor/twbs/bootstrap/dist/css/bootstrap.min.css'),
                array(),
                '4.00'
            );
        }
    }

    public function sales_rep_main_menu_page()
    {
        ?>

        <h2>Show List of Menu items here</h2>

        <?php 
        $wp_tests_dir = getenv( 'WP_TESTS_DIR' );
        echo $wp_tests_dir;
    }
}
