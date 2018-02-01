<?php
namespace EncodeonSalesRepManager\Models\SalesRep;
class Edit extends SalesRep
{
    public function process_request()
    {
        // Anti CSRF
        if (wp_verify_nonce($_REQUEST['edit_sales_rep_nonce'], "edit_sales_rep") === false) {
            $this->show_error("Invalid nonce for the edit sales rep request.");
            die();
        }

        // Authorize user
        if(!current_user_can('manage_options')) {
            $this->show_error("Invalid authorization.");
            die();
        };

        foreach($_REQUEST as $key => $value) {
            $$key = htmlspecialchars($value);
        }

        /**
         * Variables name, city, company, address1, address2 not validated because there
         * are many outliers. Use prepared SQL statements to insert.
         */

        // Validate required inputs
        $required_inputs = ['name', 'state'];

        foreach($required_inputs as $required_input) {
            if($$required_input == "") {
                $error_message = "The " . $required_input . " field must not be empty. It is a required input.";
                $this->show_error($error_message);
            }
        }

        // Validate email
        if($email != "") {
            if(!preg_match("/^\S+@\S+\.\S+$/", $email)) {
                $this->show_error("The email input appears to be invalid. Contact the website administrator if you believe the email you inputted should be valid.");
            }
        }

        // Validate phone, cell, fax
        if($phone != "") {
            if(!preg_match("/^\d{10}$/", $phone)) {
                $this->show_error("Invalid phone number. This field must either be blank or consist of 10 numbers and should not contain any additional symbols such as parentheses or dashes.");
            }
        }

        if($cell != "") {
            if(!preg_match("/^\d{10}$/", $cell)) {
                $this->show_error("Invalid cell number. This field must either be blank or consist of 10 numbers and should not contain any additional symbols such as parentheses or dashes.");
            }
        }

        if($fax != "") {
            if(!preg_match("/^\d{10}$/", $fax)) {
                $this->show_error("Invalid fax number. This field must either be blank or consist of 10 numbers and should not contain any additional symbols such as parentheses or dashes.");
            }
        }

        // Validate state; whitelist strategy
        $accepted_states = ['AK', 'AL', 'AR', 'AZ', 'CA', 'CO', 'CT', 'DC', 'DE', 'FL', 'GA', 'HI', 'IA', 'ID', 'IL', 'IN', 'KS', 'KY', 'LA', 'MA', 'MD', 'ME', 'MI', 'MN', 'MO', 'MS', 'MT', 'NC', 'ND', 'NE', 'NH', 'NJ', 'NM', 'NV', 'NY', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VA', 'VT', 'WA', 'WI', 'WV', 'WY'];

        if(!in_array($state, $accepted_states) && strlen($state) !== 2) {
            $this->show_error("Invalid input for the State.");
            die();
        }

        // Validate zip
        if(strlen($zip) == 0 || strlen($zip) ==5 || strlen($zip) == 10) {
            switch (strlen($zip)) {
                case 5:
                    if(!preg_match("/^\d+$/", $zip)) {
                        $this->show_error("Invalid zip code. A five digit zip code must only consist of numbers.");
                    }
                    break;
                case 10:
                    if(!preg_match("/^\d{5}-\d{4}$/", $zip)) {
                        $this->show_error("Invalid zip code. A five digit zip code with extension must follow this format: 12345-6789.");
                    }
                    break;
                default:
                    break;
            }
        } else {
            $this->show_error("Invalid zip code. The zip code must either be blank, 5 digits, or 5 digits with extension following this format: 12345-6789");
            die();
        }

        $prepared_statement = "UPDATE " . get_option("encodeon_sales_reps_table_name") . " SET name = %s, email = %s, phone = %s, cell = %s, fax = %s, company = %s, url = %s, address1 = %s, address2 = %s, city = %s, state = %s, zip = %s WHERE id = %s";

        global $wpdb;
        $result = $wpdb->query($wpdb->prepare($prepared_statement, $name, $email, $phone, $cell, $fax, $company, $url, $address1, $address2, $city, $state, $zip, $id));

        if ($result === false) {
            $this->show_error("The data failed to be inserted into the database. This may be a temporary connection error. Try again. If the issue persists, contact the administrator.");
            die();
        } else if ($result === 0) {
            $this->show_error("Connection to the database succeeded, but no data was updated. Check your inputs and try again.");
            die();
        } else {
            $success_message = "The sales representative " . $name . " has been updated.";

            $after_script = "$('.alert').append(' <a href=\'admin.php?page=sales-rep-manager\'>Click here to return to sales rep table.</a>')";

            $this->show_success($success_message, $after_script);
            die();
        }
    }
}
