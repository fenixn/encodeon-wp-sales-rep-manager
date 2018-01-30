<?php
namespace EncodeonSalesRepManager\Views\Frontend\SalesRep;
class Map
{
    public function __construct()
    {
        add_shortcode( 'sales-rep-map', array( $this, 'render' ) );
    }

    public function render()
    {
        echo "test";
    }
}
