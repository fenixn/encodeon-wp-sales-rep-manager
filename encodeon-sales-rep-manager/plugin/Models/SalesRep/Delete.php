<?php
namespace EncodeonSalesRepManager\Models\SalesRep;
class Delete extends SalesRep
{
    public function process_request()
    {
        // Anti CSRF
        if (wp_verify_nonce($_REQUEST['delete_sales_rep_nonce'], "delete_sales_rep") === false) {
            $this->show_error("Invalid nonce for the delete sales rep request.");
            die();
        }

        // Authorize user
        if(!current_user_can('manage_options')) {
            $this->show_error("Invalid authorization.");
            die();
        };

        $sales_rep_id = $_REQUEST['id'];

        // Test if id is a positive integer
        if(!preg_match("/^[+]?[1-9]\d*$/", $sales_rep_id)) {
            $this->show_error("An invalid sales rep id was submitted.", true);
            die();
        }

        $prepared_statement = "DELETE FROM " . get_option("encodeon_sales_reps_table_name") . " WHERE id = %s";

        global $wpdb;
        $result = $wpdb->query($wpdb->prepare($prepared_statement, $sales_rep_id));

        if ($result === false) {
            $this->show_error("There was an error attempting to run the delete request on the database. This may be a temporary connection error. Try again. If the issue persists, contact the administrator.");
            die();
        } else if ($result === 0) {
            $this->show_error("Connection to the database succeeded, but no data was deleted. Please contact the administrator to resolve this issue.");
            die();
        } else {
            $success_message = "This sales representative has been deleted.";

            $after_script = "$('.alert').append(' <a href=\'admin.php?page=sales-rep-manager\'>Click here to return to sales rep table.</a>'); $('.card').fadeOut(1000);";

            $this->show_success($success_message, $after_script);
            die();
        }
    }
}
