<?php
namespace EncodeonSalesRepManager\Models\SalesRep;
class Show extends SalesRep
{
    public function render()
    {
        ?>
        <div id="table-container" class="container-fluid pt-4"></div>

        <script type="text/javascript">
        jQuery(document).ready(function($) {
            <?php (new \EncodeonSalesRepManager\JavascriptGenerators\SalesRepTable)->render("live"); ?>
        });
        </script>
        <?php
    }
}
