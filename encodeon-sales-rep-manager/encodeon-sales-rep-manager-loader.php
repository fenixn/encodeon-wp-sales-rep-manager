<?php

class EncodeonSalesRepManagerLoader {
    /**
     * @var float $db_version
     *
     * Version number to put in DB
     */
    private $db_version = 1.0;

    /**
     * Install the table for the plugin into the database
     */
    public function db_install() 
    {
    require_once ABSPATH.'wp-admin/includes/upgrade.php';

    update_option('encodeon_sales_rep_db_version', $this->db_version);
    update_option('encodeon_sales_rep_table_name', $wpdb->prefix.'encodeon_sales_reps');

    global $wpdb;
    $sql = '
    CREATE TABLE '.$wpdb->prefix.'encodeon_sales_reps (
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
    )';
    dbDelta($sql);
    }
}
