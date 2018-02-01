<?php
namespace EncodeonSalesRepManager\Models\SalesRep;
class Export extends SalesRep
{
    function process_request()
    {
        // Anti CSRF
        if (wp_verify_nonce($_REQUEST['prepare_sales_rep_data_download_nonce'], "prepare_sales_rep_data_download") === false) {
            echo "Invalid nonce for this request. <br>";
            die();
        }

        // Authorize user
        if(!current_user_can('manage_options')) {
            $this->show_error("Invalid authorization.");
            die();
        };

        global $wpdb;
        $sql_statement = "SELECT * FROM " . get_option("encodeon_sales_rep_table_name");
        $results = $wpdb->get_results($sql_statement, ARRAY_A);

        if ($results === false) {
            $this->show_error("There was an error connecting to the database. This may be a temporary issue. Please try again and contact the administrator only if the issue persists.");
            die();
        } else {
            $file_location = wp_upload_dir()['basedir'] . '/export/';
            $fp = fopen($file_location . "abra-lighting-sales-rep.csv", "w");

            // Write headers first
            $headers = array();
            foreach($results[0] as $key => $header) {
                array_push($headers, $key);
            }
            if (($key = array_search("id", $headers)) !== false) {
                unset($headers[$key]);
            }
            fputcsv($fp, $headers);

            // Write each database row to csv
            foreach($results as $result) {
                unset($result["id"]);
                fputcsv($fp, $result);
            }
            
            fclose($fp);

            $file_url = wp_upload_dir()['baseurl'] . "/export/abra-lighting-sales-rep.csv";

            $this->show_success("The file has successfully been generated. <a href='" . $file_url . "'>Click here to download the sales rep csv</a>.");
            die();
        }
    }
}
