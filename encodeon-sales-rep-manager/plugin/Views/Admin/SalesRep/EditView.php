<?php
namespace EncodeonSalesRepManager\Views\Admin\SalesRep;
use EncodeonSalesRepManager\Models\SalesRep\SalesRep;
class EditView
{
    public function __construct()
    {
        $this->add_submenu();
    }

    public function add_submenu()
    {
        add_submenu_page( 
            'hide-from-menu', 
            'Edit Sales Rep', 
            'Edit Sales Rep', 
            'manage_options', 
            'sales-rep-manager-edit', 
            array($this, 'submenu_page') 
        );
    }

    public function submenu_page()
    {
        $sales_rep_model = new SalesRep;
        $sales_rep_id = $_GET['id'];
        $sales_rep = $sales_rep_model->get_sales_rep($sales_rep_id);
        ?>

        <main class="container-fluid mt-2">
            <h1>Edit Sales Rep</h1>

            <?php (new \EncodeonSalesRepManager\Views\Partials\StatusMessage)->render(); ?>

            <div class="card col-md-12">
                <form id="edit-new-sales-rep" method="post">

                    <input type="hidden" name="action" value="edit_sales_rep">
                    <input type="hidden" name="edit_sales_rep_nonce" value="<?php echo wp_create_nonce('edit_sales_rep'); ?>">
                    <input type="hidden" name="id" value="<?php echo $sales_rep['id']; ?>">

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="name">Sales Representative Name <span class="required">*</span></label>
                            <input type="text" class="form-control" name="name" placeholder="Enter name" <?php if ($sales_rep['name'] !== "") echo "value='" . $sales_rep['name'] . "'"; ?>>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter email" <?php if ($sales_rep['email'] !== "") echo "value='" . $sales_rep['email'] . "'"; ?>>
                        </div>    
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="phone">Phone</label>
                            <input type="tel" name="phone" class="form-control" placeholder="Enter phone number" <?php if ($sales_rep['phone'] !== "") echo "value='" . $sales_rep['phone'] . "'"; ?>>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="cell">Cell</label>
                            <input type="tel" name="cell" class="form-control" placeholder="Enter cell number" <?php if ($sales_rep['cell'] !== "") echo "value='" . $sales_rep['cell'] . "'"; ?>>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="fax">Fax</label>
                            <input type="tel" name="fax" class="form-control" placeholder="Enter fax number" <?php if ($sales_rep['fax'] !== "") echo "value='" . $sales_rep['fax'] . "'"; ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="company">Company</label>
                            <input type="text" class="form-control" name="company" placeholder="Enter company name" <?php if ($sales_rep['name'] !== "") echo "value='" . $sales_rep['name'] . "'"; ?>>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="url">Website</label>
                            <input type="url" class="form-control" name="url" placeholder="Enter website url" <?php if ($sales_rep['url'] !== "") echo "value='" . $sales_rep['url'] . "'"; ?>>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address1">Address</label>
                        <input type="text" class="form-control" name="address1" placeholder="Enter first line of address" <?php if ($sales_rep['address1'] !== "") echo "value='" . $sales_rep['address1'] . "'"; ?>>
                    </div>
                    <div class="form-group">
                        <label for="address2">Address 2</label>
                        <input type="text" class="form-control" name="address2" placeholder="Enter second line of address (if applicable)" <?php if ($sales_rep['address2'] !== "") echo "value='" . $sales_rep['address2'] . "'"; ?>>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="city">City</label>
                            <input type="text" class="form-control" name="city" placeholder="Enter city name" <?php if ($sales_rep['city'] !== "") echo "value='" . $sales_rep['city'] . "'"; ?>>
                        </div>

                        <?php $states = array(
                            "AL" => "Alabama", "AK" => "Alaska", "AZ" => "Arizona", "AR" => "Arkansas", "CA" => "California", "CO" => "Colorado", "CT" => "Connecticut", "DE" => "Delaware", "DC" => "District of Columbia", "FL" => "Florida", "GA" => "Georgia", "HI" => "Hawaii", "ID" => "Idaho", "IL" => "Illinois", "IN" => "Indiana", "IA" => "Iowa", "KS" => "Kansas", "KY" => "Kentucky", "LA" => "Louisiana", "ME" => "Maine", "MD" => "Maryland", "MA" => "Massachusetts", "MI" => "Michigan", "MN" => "Minnesota", "MS" => "Mississippi", "MO" => "Missouri", "MT" => "Montana", "NE" => "Nebraska", "NV" => "Nevada", "NH" => "New Hampshire", "NJ" => "New Jersey", "NM" => "New Mexico", "NY" => "New York", "NC" => "North Carolina", "ND" => "North Dakota", "OH" => "Ohio", "OK" => "Oklahoma", "OR" => "Oregon", "PA" => "Pennsylvania", "RI" => "Rhode Island", "SC" => "South Carolina", "SD" => "South Dakota", "TN" => "Tennessee", "TX" => "Texas", "UT" => "Utah", "VT" => "Vermont", "VA" => "Virginia", "WA" => "Washington", "WV" => "West Virginia", "WI" => "Wisconsin", "WY" => "Wyoming"
                        ); ?>
                        
                        <div class="form-group col-md-4">
                            <label for="state">State <span class="required">*</span></label>
                            <select name="state" class="form-control">
                                <option value="" selected="selected" disabled>Select State</option>
                                <?php foreach ($states as $key => $state): ?>
                                    <option value="<?php echo $key; ?>"
                                        <?php if ($sales_rep['state'] == $key) echo "selected='selected'"; ?>>
                                        <?php echo $state; ?>
                                    </option>
                                <?php endforeach; ?>    
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="zip">Zip</label>
                            <input type="text" class="form-control" name="zip" placeholder="Enter zip code" <?php if ($sales_rep['zip'] !== "") echo "value='" . $sales_rep['zip'] . "'"; ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 text-center">
                            <button type="submit" class="btn btn-primary ">
                                <i class="fas fa-plus-circle"></i>
                                Edit sales rep
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </main>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                // AJAX call for editing new sales rep
                $('#edit-new-sales-rep').on("click", "button[type='submit']", function(event) {
                    event.preventDefault();
                    var form_data = $("#edit-new-sales-rep").serialize();
                    $.ajax({
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        type: "post",
                        data: form_data,
                        success: function(data) {
                            $(".status-message").html(data);
                        },
                        error: function(xhr, desc, err) {
                            $(".status-message").html("<div class='alert alert-danger'>Error: " + err + "</div>");
                        }
                    });
                });
            });
        </script>
        
        <?php 
    }
}
