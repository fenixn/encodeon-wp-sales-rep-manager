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
                            <h4>Control Panel</h4>
                            <div class="btn-toolbar" role="toolbar">
                                <div class="btn-group mr-2" role="group" aria-label="First group">
                                    <button type="button" class="btn btn-primary">Controls</button>
                                    <button type="button" class="btn btn-primary">Buttons</button>
                                </div>
                                <div class="btn-group mr-2" role="group" aria-label="Second group">
                                    <button type="button" class="btn btn-primary">5</button>
                                    <button type="button" class="btn btn-primary">6</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-md-0 mt-4">
                            <h4>Spreadsheet Upload</h4>
                            <form>
                                <label class="custom-file">
                                    <input type="file" class="custom-file-input" id="exampleInputFile" aria-describedby="fileHelp">
                                    <span class="custom-file-control form-control-file "></span>
                                </label>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row-fluid">
                <div class="card col-md-12 import-log">
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

            <script type="text/javascript">
            jQuery(document).ready(function($) {

                generate_sales_rep_table();

                function generate_sales_rep_table(
                    attribute="id", 
                    sort="ASC",
                    page=1,
                    search_input="",
                    table=2,
                    limit=50,
                ) {
                    var form_data = new FormData();
                    form_data.append("action", "generate_sales_rep_table");
                    form_data.append(
                        "generate_sales_rep_table_nonce", 
                        "<?php echo wp_create_nonce('generate_sales_rep_table'); ?>"
                    );
                    form_data.append("attribute", attribute);
                    form_data.append("sort", sort);
                    form_data.append("page", page);
                    form_data.append("search_input", search_input);
                    form_data.append("limit", limit);
                    form_data.append("table", table);

                    $.ajax({
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        type: "post",
                        data: form_data,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            $("#table-container").html(data);
                        },
                        error: function(xhr, desc, err) {
                            $(".status-message").html("<div class='alert alert-danger'>Error: " + err + "</div>");
                        }
                    });
                }

                // AJAX call for sorting the sales rep table
                $('#table-container').on("click", "th", function(event) {
                    var attribute = this.dataset.attributeName;
                    var sort = this.dataset.attributeSort;
                    var page = $("#table-data").attr("data-page");
                    var search = $("#table-data").attr("data-search");

                    // Reverse the sort order of the current sort.
                    if (sort === "ASC") { sort = "DESC" } else { sort = "ASC" }
                    generate_sales_rep_table(attribute, sort, page, search);
                });

                // AJAX call for changing page number
                $("#table-container").on("click", ".page-item", function(event) {
                    event.preventDefault();
                    var attribute = $("#table-data").attr("data-attribute-name");
                    var sort = $("#table-data").attr("data-attribute-sort");
                    var page = this.dataset.page;
                    var search = $("#table-data").attr("data-search");

                    var active = this.dataset.active;

                    if (active == 1) {
                        generate_sales_rep_table(attribute, sort, page, search);
                    }
                });

                // AJAX call for search
                $("#table-container").on("click", "#sales-rep-search button", function(event) {
                    event.preventDefault();
                    var attribute = $("#table-data").attr("data-attribute-name");
                    var sort = $("#table-data").attr("data-attribute-sort");
                    var page = $("#table-data").attr("data-page");
                    var active = $("#table-data").attr("data-active");
                    var search_input = document.getElementById("sales-rep-search-input").value;

                    generate_sales_rep_table(attribute, sort, page, search_input);
                });
            });
        </script>
        </main>
        <?php
    }
}
