<?php
namespace EncodeonSalesRepManager\Models\SalesRep;
use EncodeonSalesRepManager\Views\Javascript\SalesRepTable;
class Show extends SalesRep
{
    public function render()
    {
        ?>

        <div id="table-container" class="container-fluid pt-4"></div>

        <script type="text/javascript">
        jQuery(document).ready(function($) {
            <?php (new SalesRepTable)->render(1); ?>
        });
        </script>

        <?php
    }
}
