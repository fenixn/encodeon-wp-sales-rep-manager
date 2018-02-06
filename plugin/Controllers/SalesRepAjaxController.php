<?php
namespace EncodeonSalesRepManager\Controllers;
use EncodeonSalesRepManager\Models\SalesRep\SalesRep;
use EncodeonSalesRepManager\Models\SalesRep\Create;
use EncodeonSalesRepManager\Models\SalesRep\Edit;
use EncodeonSalesRepManager\Models\SalesRep\Delete;
use EncodeonSalesRepManager\Models\SalesRep\Import;
use EncodeonSalesRepManager\Models\SalesRep\Export;
class SalesRepAjaxController
{
    public function __construct()
    {
        add_action("wp_ajax_generate_sales_rep_table", array($this, "generate_sales_rep_table"));
        add_action("wp_ajax_create_sales_rep", array($this, "create_sales_rep"));
        add_action("wp_ajax_edit_sales_rep", array($this, "edit_sales_rep"));
        add_action("wp_ajax_delete_sales_rep", array($this, "delete_sales_rep"));
        add_action("wp_ajax_get_state_sales_reps", array($this, "get_state_sales_reps"));
        add_action("wp_ajax_nopriv_get_state_sales_reps", array($this, "get_state_sales_reps"));
        add_action("wp_ajax_upload_sales_rep", array($this, "upload_sales_rep"));
        add_action("wp_ajax_copy_sales_rep_import_to_live", array($this, "copy_sales_rep_import_to_live"));
        add_action("wp_ajax_prepare_sales_rep_data_download", array($this, "prepare_sales_rep_data_download"));
    }

    public function generate_sales_rep_table()
    {
        $sales_rep_model = new SalesRep;
        $sales_rep_model->generate_sales_rep_table();
        die();
    }

    public function create_sales_rep()
    {
        $sales_rep_create = new Create;
        $sales_rep_create->process_request();
        die();
    }

    public function edit_sales_rep()
    {
        $sales_rep_edit = new Edit;
        $sales_rep_edit->process_request();
        die();
    }

    public function delete_sales_rep()
    {
        $sales_rep_delete = new Delete;
        $sales_rep_delete->process_request();
        die();
    }

    public function get_state_sales_reps()
    {
        $sales_rep_model = new SalesRep;
        $sales_rep_model->get_state_sales_reps();
        die();
    }

    public function upload_sales_rep()
    {
        $sales_rep_import_model = new Import;
        $sales_rep_import_model->process_upload();
        die();
    }

    public function copy_sales_rep_import_to_live()
    {
        $sales_rep_import_model = new Import;
        $sales_rep_import_model->copy_sales_rep_import_to_live();
        die();
    }

    public function prepare_sales_rep_data_download()
    {
        $sales_rep_export_model = new Export;
        $sales_rep_export_model->process_request();
        die();
    }
}
