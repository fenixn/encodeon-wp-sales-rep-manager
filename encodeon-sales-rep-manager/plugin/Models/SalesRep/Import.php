<?php
namespace EncodeonSalesRepManager\Models\SalesRep;
class Import extends SalesRep
{
    public function process_request()
    {
        var_dump($_REQUEST);
        var_dump($_FILES);
    }
}
