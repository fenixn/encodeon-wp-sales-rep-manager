<?php
namespace EncodeonSalesRepManager\Views\Admin\SalesRep;
class Import
{
    protected $table = 2;

    public function __construct()
    {
        $this->add_submenu();
    }

    public function add_submenu()
    {
        add_submenu_page( 
            'sales-rep-manager', 
            'Import', 
            'Import', 
            'manage_options', 
            'sales-rep-manager-import', 
            array($this, 'submenu_page') 
        );
    }

    public function submenu_page()
    {
        ?>
        <style>
        .custom-file-control.selected:lang(en)::after {
            content: "" !important;
        }
        </style>

        <script>
        (function($) {
            $(document).ready(function() {
                $('.custom-file-input').on('change',function(){
                    var fileName = $(this).val().split('\\').pop();
                    $(this).next('.form-control-file').addClass("selected").html(fileName);
                })
            });
        }(jQuery));
        </script>

        <main class="container-fluid mt-2">
            <h1>Sales Rep Importer</h1>
            <div class="row-fluid">
                <div class="card col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Spreadsheet Upload</h4>
                            <form id="upload-sales-rep" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="upload_sales_rep">
                                <input type="hidden" name="upload_sales_rep_nonce" value="<?php echo wp_create_nonce('upload_sales_rep'); ?>">
                                <label class="custom-file">
                                    <input type="file" class="custom-file-input" name="file" aria-describedby="fileHelp">
                                    <span class="custom-file-control form-control-file "></span>
                                </label>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </form>
                        </div>

                        <div class="col-md-6 mt-md-0 mt-4">
                            <h4>Control Panel</h4>
                            <div class="btn-toolbar" role="toolbar">
                                <div class="btn-group mr-2" role="group" aria-label="First group">
                                    <button data-toggle="modal" data-target="#confirm-modal" type="button" class="btn btn-primary">Make Import Data Live</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row-fluid">
                <div class="card col-md-12 import-log" style="height:300px; max-height:300px; overflow-y: scroll;">
                    <div class="import-log-header">
                        <h2>Import Logs</h2>
                    </div>
                    <div class="import-log-content"></div>
                </div>
            </div>

            <div class="row-fluid">
                <div class="card col-md-12 import-table">
                    <div class="import-table-header">
                        <h2>Import Preview</h2>
                    </div>
                    <div class="import-table-content">
                        <div id="table-container" class="container-fluid pt-4"></div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog">
                <div class="modal-dialog .modal-dialog-centered h-100 d-flex flex-column justify-content-center my-0" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Press confirm to make the import data live, or cancel to go back to check the data.</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="confirm-data-live" method="post">
                            <input type="hidden" name="action" value="copy_sales_rep_import_to_live">
                            <input type="hidden" name="copy_sales_rep_import_to_live_nonce" value="<?php echo wp_create_nonce('copy_sales_rep_import_to_live'); ?>">
                            <div class="modal-body text-center">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Confirm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
            jQuery(document).ready(function($) {

                $('#upload-sales-rep').on('submit', function (e) {
                    e.preventDefault();
                    $('.import-log-content').prepend( 'Uploading your file, please wait... <br />' );
                    
                    $.ajax({
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        type: 'post',
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function (data, status) {
                        if(data) {
                            $('.import-log-content').prepend(data + "<br>");
                            generate_sales_rep_table();

                            $('.import-log-content').prepend("Upload completed. Please check the updated Import Preview below. If the new data looks correct, click the Make Import Data Live button. <br>");
                        }
                        },
                        error: function (xhr, desc, err) {
                            $('.import-log-content').prepend(err + "<br>");
                        }
                    }); 
                });

                // AJAX call for copying sales rep import table to live table
                $('#confirm-data-live').on("submit", function(event) {
                    event.preventDefault();
                    $('.import-log-content').prepend("Copying sales rep import table to live table...");

                    $.ajax({
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        type: 'post',
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function (data, status) {
                        if(data) {
                            $('.import-log-content').prepend(data + "<br>");
                            $("#confirm-modal").modal("toggle");
                        }
                        },
                        error: function (xhr, desc, err) {
                            $('.import-log-content').prepend(err + "<br>");
                        }
                    }); 
                });

                <?php (new \EncodeonSalesRepManager\JavascriptGenerators\SalesRepTable)->render("import"); ?>
            });
        </script>

        </main>
        <?php
    }
}
