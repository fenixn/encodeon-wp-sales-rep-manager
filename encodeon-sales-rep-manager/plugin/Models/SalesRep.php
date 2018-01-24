<?php
namespace EncodeonSalesRepManager\Models;
class SalesRep
{
    protected $primary_key = 'id';

    public function show()
    {
        global $wpdb;
        $table = $wpdb->get_results("SELECT * FROM " . get_option('encodeon_sales_rep_table_name'));
        var_dump($table);
    }
}
