<?php
namespace EncodeonSalesRepManager\Models;
class SalesRep
{
    protected $primary_key = 'id';

    public function show()
    {
        global $wpdb;
        $sales_reps = $wpdb->get_results("SELECT * FROM " . get_option('encodeon_sales_rep_table_name'));
        ?>
        <div class="container-fluid pt-4">
            <div class="row">
                <div class="table-responsive">
                    <?php if (count($sales_reps) > 0): ?>
                        <table class="table table-striped table-sm">
                            <thead>
                            <?php foreach($sales_reps[0] as $key => $sales_rep): ?>
                                <th class="text-capitalize">
                                <?php echo $key; ?>
                                </th>
                            <?php endforeach; ?>
                            </thead>
                            <?php foreach($sales_reps as $sales_rep): ?>
                            <tr>
                                <?php foreach($sales_rep as $sales_rep_attribute): ?>
                                    <td>
                                        <?php echo $sales_rep_attribute; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php
    }
}
