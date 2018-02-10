<?php

class EncodeonSalesRepManagerLoader {
    private $version = "1.1.0";
    private $db_version = "1.0.0";

    /**
     * Install the tables for the plugin into the database
     */
    public function db_install() 
    {
        require_once ABSPATH.'wp-admin/includes/upgrade.php';

        // Set version numbers
        update_option('encodeon_sales_reps_version', $this->version);
        update_option('encodeon_sales_reps_db_version', $this->db_version);

        // Set some default values
        add_option('encodeon_sales_reps_table_page_limit', 50);
        add_option('encodeon_sales_reps_table_pager_increment', 3);

        $states = array(
            "AL" => "Alabama", "AK" => "Alaska", "AZ" => "Arizona", "AR" => "Arkansas", "CA" => "California", "CO" => "Colorado", "CT" => "Connecticut", "DE" => "Delaware", "DC" => "District of Columbia", "FL" => "Florida", "GA" => "Georgia", "HI" => "Hawaii", "ID" => "Idaho", "IL" => "Illinois", "IN" => "Indiana", "IA" => "Iowa", "KS" => "Kansas", "KY" => "Kentucky", "LA" => "Louisiana", "ME" => "Maine", "MD" => "Maryland", "MA" => "Massachusetts", "MI" => "Michigan", "MN" => "Minnesota", "MS" => "Mississippi", "MO" => "Missouri", "MT" => "Montana", "NE" => "Nebraska", "NV" => "Nevada", "NH" => "New Hampshire", "NJ" => "New Jersey", "NM" => "New Mexico", "NY" => "New York", "NC" => "North Carolina", "ND" => "North Dakota", "OH" => "Ohio", "OK" => "Oklahoma", "OR" => "Oregon", "PA" => "Pennsylvania", "RI" => "Rhode Island", "SC" => "South Carolina", "SD" => "South Dakota", "TN" => "Tennessee", "TX" => "Texas", "UT" => "Utah", "VT" => "Vermont", "VA" => "Virginia", "WA" => "Washington", "WV" => "West Virginia", "WI" => "Wisconsin", "WY" => "Wyoming"
        );
        add_option("encodeon_sales_reps_states", serialize($states));

        // Create import and export directory in the uploads folder if it doesn't exist
        if (!file_exists(wp_upload_dir()['basedir'] . "/import")) {
            mkdir(wp_upload_dir()['basedir'] . "/export", 0774, true);
        }
        if (!file_exists(wp_upload_dir()['basedir'] . "/export")) {
            mkdir(wp_upload_dir()['basedir'] . "/export", 0774, true);
        }

        global $wpdb;
        $sales_reps_table = $wpdb->prefix . "encodeon_sales_reps";
        update_option('encodeon_sales_reps_table_name', $sales_reps_table);
        update_option('encodeon_sales_reps_import_table_name', $sales_reps_table . "_import");

        // Install the main sales rep table
        $sql = "
        CREATE TABLE " . $sales_reps_table . " (
            id int(11) NOT NULL auto_increment,
            name varchar(255) NOT NULL,
            email varchar(255) default NULL,
            phone varchar(10) default NULL,
            cell varchar(10) default NULL,
            fax varchar(10) default NULL,
            company varchar(255) default NULL,
            url varchar(255) default NULL,
            address1 varchar(255) default NULL,
            address2 varchar(255) default NULL,
            city varchar(100) default NULL,
            state varchar(5) default NULL,
            zip varchar(20) default NULL,
            PRIMARY KEY  (id)
        )";
        dbDelta($sql);

        // Install the import preview table
        $sql = "
        CREATE TABLE " . $sales_reps_table . " (
            id int(11) NOT NULL auto_increment,
            name varchar(255) NOT NULL,
            email varchar(255) default NULL,
            phone varchar(10) default NULL,
            cell varchar(10) default NULL,
            fax varchar(10) default NULL,
            company varchar(255) default NULL,
            url varchar(255) default NULL,
            address1 varchar(255) default NULL,
            address2 varchar(255) default NULL,
            city varchar(100) default NULL,
            state varchar(5) default NULL,
            zip varchar(20) default NULL,
            PRIMARY KEY  (id)
        )";
        dbDelta($sql);
    }
}
