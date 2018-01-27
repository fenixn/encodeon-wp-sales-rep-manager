<?php
namespace EncodeonSalesRepManager\Views\Admin;
class MainMenu
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

        // Enqueue FontAwesome
        if( ( ! wp_style_is( 'fontawesome', 'queue' ) ) && 
            ( ! wp_style_is( 'fontawesome', 'done' ) ) ) {
            wp_enqueue_style(
                'fontawesome',
                plugins_url('encodeon-sales-rep-manager/vendor/fontawesome/web-fonts-with-css/css/fontawesome-all.min.css'),
                array(),
                '5.0.4'
            );
        }
    }

    public function sales_rep_main_menu_page()
    {
        ?>

        <main class="container-fluid mt-2">
            <h1>Sales Representatives Control Panel</h1>

            <div class="jumbotron">
                <a href="admin.php?page=sales-rep-manager-list">
                    <button type="button" class="btn btn-primary">Sale Representatives Table</button>
                </a>
            </div>

        </main>

        <?php 
    }
}
