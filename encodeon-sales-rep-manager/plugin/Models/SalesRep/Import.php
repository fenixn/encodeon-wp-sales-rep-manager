<?php
namespace EncodeonSalesRepManager\Models\SalesRep;
class Import extends SalesRep
{
    public function process_upload()
    {
        // Anti CSRF
        if (wp_verify_nonce($_REQUEST['upload_sales_rep_nonce'], "upload_sales_rep") === false) {
            echo "Invalid nonce for this request. <br>";
            die();
        }

        // Authorize user
        if(!current_user_can('manage_options')) {
            $this->show_error("Invalid authorization.");
            die();
        };

        $php_file_upload_errors = array(
            0 => 'There is no error, the file uploaded with success',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        );

        if ($_FILES['file']['error'] != 0) {
            echo $php_file_upload_errors[$_FILES['file']['error']] . "<br>";
            die();
        }

        $upload_destination = wp_upload_dir()['basedir'] . '/import/';

        $csv_mimetypes = array(
            'text/csv',
            'application/csv',
            'text/comma-separated-values',
            'application/vnd.ms-excel'
        );
        
        if (in_array($_FILES['file']['type'], $csv_mimetypes)) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_destination . 'salesreps.csv')) {
                echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded. <br>";
            } else {
                echo "Sorry, there was an error uploading your file. <br>";
                die();
            }
        } else {
            echo 'Filetype not allowed, please upload a CSV file. <br>';
            die();
        }

        $this->parse_uploaded_csv_and_import_into_preview_table();
    }

    public function parse_uploaded_csv_and_import_into_preview_table()
    {
        global $wpdb;
        $import_file = wp_upload_dir()["baseurl"] . '/import/salesreps.csv';
        $import_csv = array_map('str_getcsv', file($import_file));
        $import_table = get_option("encodeon_sales_reps_table_name") . "_import";

        /**
         * Truncate the preview table before importing new data for preview
         */
        $truncate_query = 'TRUNCATE TABLE ' . $import_table;
        $results = $wpdb->get_results($truncate_query, ARRAY_A);

        $import_column_headers = $import_csv[0];
        unset($import_csv[0]);

        foreach ($import_csv as $key => $import_csv_row) { 
            $insert_statement = "INSERT INTO " . $import_table . " (";
            foreach($import_column_headers as $import_column_header) {
                $insert_statement .= $import_column_header . ", ";
            }
            $insert_statement = rtrim(trim($insert_statement), ",") . ") VALUES (";
            foreach($import_csv_row as $data_cell) {
                $insert_statement .= "'" . $data_cell . "', ";
            }
            $insert_statement = rtrim(trim($insert_statement), ",") . ")";
            
            $result = $wpdb->query($insert_statement);

            if ($result === false) {
                echo "There was an error attempting to run the request on the database. This may be a temporary connection error. Try again. If the issue persists, contact the administrator.";
                die();
            }
        }
    }

    public function copy_sales_rep_import_to_live()
    {
        // Anti CSRF
        if (wp_verify_nonce($_REQUEST['copy_sales_rep_import_to_live_nonce'], "copy_sales_rep_import_to_live") === false) {
            echo "Invalid nonce for this request. <br>";
            die();
        }

        // Authorize user
        if(!current_user_can('manage_options')) {
            $this->show_error("Invalid authorization.");
            die();
        };

        global $wpdb;

        $truncate_statement = 'TRUNCATE TABLE ' . get_option("encodeon_sales_reps_table_name");
        $results = $wpdb->query($truncate_statement);

        if ($result === false) {
            echo "There was an error. This may be a temporary connection error. Try again. If the issue persists, contact the administrator.";
            die();
        } else {
            $copy_statement = "INSERT INTO " . get_option("encodeon_sales_reps_table_name") . " SELECT * FROM " . get_option("encodeon_sales_reps_table_name") . "_import";

            $results = $wpdb->query($copy_statement);
            if ($results === false) {
                echo "There was a fatal error! Please contact system administrator as soon as possible to resolve the issue.";
                die();
            } else {
                echo "The data from the sales rep import preview has been successfully transferred into the live sales rep data.";
                die();
            }
        }
    }
}
