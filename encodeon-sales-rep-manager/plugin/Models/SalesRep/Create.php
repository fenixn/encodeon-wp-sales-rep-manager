<?php
namespace EncodeonSalesRepManager\Models\SalesRep;
class Create extends SalesRep
{
    public function process_request()
    {
        // Anti CSRF
        if (wp_verify_nonce($_REQUEST['create_sales_rep_nonce'], "create_sales_rep") === false) {
            $this->show_error("Invalid nonce for the create sales rep request.");
            die();
        }

        foreach($_REQUEST as $key => $value) {
            $$key = htmlspecialchars($value);
        }

        /**
         * Variables name, city, company, address1, address2 not validated because there
         * are many outliers. Use prepared SQL statements to insert.
         */

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
            $this->show_error("Invalid state");
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

        /*
        foreach($_REQUEST as $key => $value) {
            echo $key . "=" . $value . "<br>";
        }
        */
    }
}
