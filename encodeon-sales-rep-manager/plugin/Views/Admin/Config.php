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
            "sales-rep-manager", 
            "Config", 
            "Config", 
            "manage_options", 
            "sales-rep-manager-config", 
            array($this, "submenu_page") 
        );
    }

    public function submenu_page()
    {
        ?>
        <main class="container-fluid mt-2">
            <h1>Sales Rep Config</h1>
            <div class="row-fluid">
                <div class="card col-md-12 p-1">
                    <div class="card-block">
                        <ul class="m-0">
                            <li>Version: <?php echo get_option("encodeon_sales_reps_version"); ?></li>
                            <li>Database Version: <?php echo get_option("encodeon_sales_reps_db_version"); ?></li>
                        </ul>
                    </div>
                </div>
            </div>

            <?php (new \EncodeonSalesRepManager\Views\Partials\StatusMessage)->render(); ?>

            <div class="row-fluid">
                <div class="card col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Settings</h4>
                            <form id="sales-rep-manager-config">
                                <input type="hidden" name="action" value="encodeon_sales_rep_manager_save_config">
                                <input type="hidden" name="encodeon_sales_rep_manager_save_config_nonce" value="<?php echo wp_create_nonce('encodeon_sales_rep_manager_save_config'); ?>">
                                <div class="form-group row">
                                    <label for="page-limit" class="col-2 col-form-label">Maximum number of sales rep per page</label>
                                    <div class="col-10">
                                        <input type="text" class="form-control" name="page_limit" value="<?php echo get_option('encodeon_sales_reps_table_page_limit'); ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="pager-increment" class="col-2 col-form-label">Pager increments from center</label>
                                    <div class="col-10">
                                        <input type="text" class="form-control" name="pager_increment" value="<?php echo get_option('encodeon_sales_reps_table_pager_increment'); ?>">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Settings</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#sales-rep-manager-config').on("submit", function(event) {
                    event.preventDefault();
                    $.ajax({
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        type: "post",
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            $(".status-message").html(data);
                        },
                        error: function(xhr, desc, err) {
                            $(".status-message").html("<div class='alert alert-danger'>Error: " + err + "</div>");
                        }
                    });
                });
            });
            </script>
        </main>
        <?php
    }
}
