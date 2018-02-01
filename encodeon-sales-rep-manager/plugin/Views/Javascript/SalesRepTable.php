<?php
namespace EncodeonSalesRepManager\Views\Javascript;
class SalesRepTable
{
    /**
     * Javascript for AJAX calls to generate the sales rep table.
     * $table accepts one of two values. 
     * 1 will generate the main sales rep table.
     * 2 will generate the import preview table.
     */
    public function render($table=1)
    { 
        ?>

        generate_sales_rep_table();

        function generate_sales_rep_table(
            attribute="id", 
            sort="ASC",
            page=1,
            search_input="",
            table=<?php echo $table; ?>,
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
            var table = $("#table-data").attr("data-table");

            // Reverse the sort order of the current sort.
            if (sort === "ASC") { sort = "DESC" } else { sort = "ASC" }
            generate_sales_rep_table(attribute, sort, page, search, table);
        });

        // AJAX call for changing page number
        $("#table-container").on("click", ".page-item", function(event) {
            event.preventDefault();
            var attribute = $("#table-data").attr("data-attribute-name");
            var sort = $("#table-data").attr("data-attribute-sort");
            var page = this.dataset.page;
            var search = $("#table-data").attr("data-search");
            var table = $("#table-data").attr("data-table");

            var active = this.dataset.active;

            if (active == 1) {
                generate_sales_rep_table(attribute, sort, page, search, table);
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
            var table = $("#table-data").attr("data-table");

            generate_sales_rep_table(attribute, sort, page, search_input, table);
        });
            
        <?php
    }
}
