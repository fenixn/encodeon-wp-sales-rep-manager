<?php
namespace EncodeonSalesRepManager;
class Plugin
{
    public function __construct()
    {
        $this->run_controllers();

        /** Frontend Pages */
        new Views\Frontend\SalesRep\Map;

        add_action("admin_menu", array($this, "on_wp_admin_menu"));
        add_action("wp_enqueue_scripts", array($this, "enqueue_for_frontend"));
    }

    /**
     * Set up the plugin admin menu items when the WordPress admin is loaded.
     */
    public function on_wp_admin_menu()
    {
        new Views\Admin\MainMenu;
        new Views\Admin\SalesRep\Create;
        new Views\Admin\SalesRep\Edit;
        new Views\Admin\SalesRep\Import;
        new Views\Admin\SalesRep\Export;
        new Views\Admin\Config;
    }

    /**
     * Set the options for which scripts to enqueue on the frontend.
     */
    public function enqueue_for_frontend()
    {
        $tether = true;
        $bootstrap = true;
        $font_awesome = true;
        $jqvmp = true;
        $this->enqueue_scripts($tether, $bootstrap, $font_awesome, $jqvmp);
    }

    /**
     * Set the enqueues for the scripts that the plugin uses. 
     * @var bool $tether        Enqueues Tether js if true
     * @var bool $bootstrap     Enqueues Bootstrap js and css if true
     * @var bool $font_awesome  Enqueues FontQwesome css if true
     * @var bool $jqvmp         Enqueues jQuery Vector Map js and css if true
     */
    public function enqueue_scripts($tether=true, $bootstrap=true, $font_awesome=true, $jqvmp=false)
    {
        /** Enqueue Tether */
        if ($tether === true) {
            if((!wp_style_is("tether", "queue")) && 
               (!wp_style_is("tether", "done"))) {
                wp_enqueue_style(
                    "tether",
                    plugins_url("encodeon-wp-sales-rep-manager/node_modules/tether/dist/css/tether.min.css"),
                    array(),
                    "1.4.7"
                );
                wp_enqueue_script(
                    "tether",
                    plugins_url("encodeon-wp-sales-rep-manager/node_modules/tether/dist/js/tether.min.js"),
                    array(),
                    "1.4.7"
                );
            }
        }
        
        /** Enqueue Bootstrap */
        if ($bootstrap === true) {
            if((!wp_style_is("bootstrap", "queue")) && 
               (!wp_style_is("bootstrap", "done"))) {
                wp_enqueue_style(
                    "bootstrap",
                    plugins_url("encodeon-wp-sales-rep-manager/node_modules/bootstrap/dist/css/bootstrap.min.css"),
                    array(),
                    "4.00"
                );
                wp_enqueue_script(
                    "bootstrap",
                    plugins_url("encodeon-wp-sales-rep-manager/node_modules/bootstrap/dist/js/bootstrap.min.js"),
                    array("jquery", "tether"),
                    "4.00"
                );
            }
        }

        /** Enqueue FontAwesome */
        if ($font_awesome === true) {
            if((!wp_style_is("fontawesome", "queue")) && 
               (!wp_style_is("fontawesome", "done"))) {
                wp_enqueue_style(
                    "fontawesome",
                    plugins_url("encodeon-wp-sales-rep-manager/vendor/fontawesome/web-fonts-with-css/css/fontawesome-all.min.css"),
                    array(),
                    "5.0.4"
                );
            }
        }

        /** Enqeue jQuery Vector Map */
        if ($jqvmp === true) {
            if((!wp_style_is("jqvmap", "queue")) && 
               (!wp_style_is("jqvmap", "done"))) {
                wp_enqueue_style(
                    "jqvmap",
                    plugins_url("encodeon-wp-sales-rep-manager/vendor/jqvmap-master/dist/jqvmap.min.css"),
                    array(),
                    "1.5.0"
                );

                wp_enqueue_script(
                    "jqvmap",
                    plugins_url("encodeon-wp-sales-rep-manager/vendor/jqvmap-master/dist/jquery.vmap.min.js"),
                    array("jquery"),
                    "1.5.0"
                );

                wp_enqueue_script(
                    "jqvmap-usa",
                    plugins_url("encodeon-wp-sales-rep-manager/vendor/jqvmap-master/dist/maps/jquery.vmap.usa.js"),
                    array("jquery"),
                    "1.5.0"
                );
            }
        }
    }

    /**
     * Run the controllers for the plugin
     */
    public function run_controllers()
    {
        new Controllers\SalesRepAjaxController;
        new Controllers\ConfigAjaxController;
    }

    /**
     * Show an error message with jQuery on the .status-message element
     * @var string $error   The error message to display.
     * @var bool $refresh   After the error message, will show a link to refresh the page if true.
     */
    public function show_error($error, $refresh = false)
    {
        $error_html = "<div class='alert alert-danger'>Error: " . $error; 
        
        if ($refresh) {
            $error_html .= " <a href='admin.php?page=sales-rep-manager'>Click here to refresh the page.</a>";
        }

        $error_html .= "</div>";
        ?>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $(".status-message").html("<?php echo $error_html; ?>");
            });
        </script>
        <?php
    }

    /**
     * Show a success message with jQuery on the .status-message element
     * @var string $success     The success message to display.
     * @var string $after       Takes jQuery to run after the message.
     */
    public function show_success($success, $after="")
    {
        $success_html = "<div class='alert alert-success'>Success: " . $success . "</div>";
        ?>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $(".status-message").html("<?php echo $success_html; ?>");
                <?php echo $after; ?>
            });
        </script>
        <?php
    }
}
