<?php
namespace EncodeonSalesRepManager\Views\Admin\SalesRep;
class Export
{
    public function __construct()
    {
        $this->add_submenu();
    }

    public function add_submenu()
    {
        add_submenu_page( 
            'sales-rep-manager', 
            'Export', 
            'Export', 
            'manage_options', 
            'sales-rep-manager-export', 
            array($this, 'submenu_page') 
        );
    }

    public function submenu_page()
    {
        ?>
        <main class="container-fluid mt-2">
            <h1>Sales Rep Exporter</h1>

            <?php (new \EncodeonSalesRepManager\Views\Partials\StatusMessage)->render(); ?>

            <div class="row-fluid">
                <div class="card col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Control Panel</h4>
                            <div class="btn-toolbar" role="toolbar">
                                <div class="btn-group mr-2" role="group">
                                    <button id="prepare-sales-rep-data-download" type="button" class="btn btn-primary">Prepare Data for Download</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#prepare-sales-rep-data-download').on("click", function() {
                    var form_data = new FormData();
                    form_data.append("action", "prepare_sales_rep_data_download");
                    form_data.append(
                        "prepare_sales_rep_data_download_nonce", 
                        "<?php echo wp_create_nonce('prepare_sales_rep_data_download'); ?>"
                    );
                    $.ajax({
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        type: "post",
                        data: form_data,
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
