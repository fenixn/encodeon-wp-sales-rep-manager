<?php
namespace EncodeonSalesRepManager\Models\SalesRep;
class Show extends SalesRep
{
    public function render()
    {
        ?>

        <div id="table-container" class="container-fluid pt-4"></div>

        <form id='test' method='post'>
            <?php wp_nonce_field('test', 'test_nonce'); ?>
            <input name="action" value="test_action" type="hidden">
            <input name="option" value="1" type="hidden">
            <button type='submit'>Test</button>
        </form>

        <script type="text/javascript">
            jQuery(document).ready(function($) {

                generate_sales_rep_table();

                function generate_sales_rep_table(
                    attribute="id", 
                    sort="ASC",
                    page=1,
                    limit=3,
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
                    form_data.append("limit", limit);

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
                    var page = $(this).closest("#table-container").find("#table-data").attr("data-page");

                    // Reverse the sort order of the current sort.
                    if (sort === "ASC") { sort = "DESC" } else { sort = "ASC" }
                    generate_sales_rep_table(attribute, sort, page);
                });

                $('form').on('submit', function(event) {
                    event.preventDefault();
                    $.ajax({
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        type: "post",
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            $(".status-message").html("<div class='alert alert-success'>" + data + "</div>");
                        },
                        error: function(xhr, desc, err) {
                            $(".status-message").html("<div class='alert alert-danger'>Error: " + err + "</div>");
                        }
                    });
                });
            });
        </script>

        <?php
    }
}
