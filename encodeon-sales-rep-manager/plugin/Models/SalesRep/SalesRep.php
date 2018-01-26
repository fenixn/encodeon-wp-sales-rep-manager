<?php
namespace EncodeonSalesRepManager\Models\SalesRep;
class SalesRep
{
    public function generate_sales_rep_table()
    {
        global $wpdb;

        $attribute = $_REQUEST['attribute'];
        $sort = $_REQUEST['sort'];

        // Anti CSRF
        if (wp_verify_nonce($_REQUEST['generate_sales_rep_table_nonce'], "generate_sales_rep_table") === false) {
            die("Invalid nonce for the generate sales rep table request.");
        }

        // Input validation using whitelisting strategy
        $allowed_attributes = [
            'id',
            'name',
            'email',
            'phone',
            'cell',
            'fax',
            'company',
            'url',
            'address1',
            'address2',
            'city',
            'state',
            'zip'
        ];

        $allowed_sort = ['ASC', 'DESC'];

        if(!in_array($attribute, $allowed_attributes) || !in_array($sort, $allowed_sort))
        {
            die('Invalid inputs detected.');
        }

        $query = "SELECT * FROM " . get_option('encodeon_sales_rep_table_name') . " ORDER BY {$attribute} {$sort}";

        $sales_reps = $wpdb->get_results($query);

        ?>

        <div class="row">
            <div class="table-responsive">
                <?php if (count($sales_reps) > 0): ?>
                    <table id="sales-rep-manager-table" class="table table-striped table-sm">
                        <thead class="thead-inverse">
                        <?php foreach($sales_reps[0] as $key => $sales_rep): ?>
                            <th class="text-capitalize bg-primary" 
                                style="cursor: pointer"
                                data-attribute-name="<?php echo $key; ?>"
                                data-attribute-sort="<?php echo $sort; ?>">
                            <?php echo $key; ?>
                            <?php $this->get_sort($attribute, $key, $sort); ?>
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

        <?php
    }

    public function get_sort($sort_attribute, $current_attribute, $sort_by) 
    {
        if ($sort_attribute === $current_attribute) {
            if ($sort_by === 'ASC') {
                echo "<i class=\"fas fa-sort-up\"></i>";
            } else {
                echo "<i class=\"fas fa-sort-down\"></i>";
            }
        }
    }
}
