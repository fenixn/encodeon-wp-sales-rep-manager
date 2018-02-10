<?php
namespace EncodeonSalesRepManager\Views\Admin\SalesRep;
class Create
{
    public function __construct()
    {
        $this->add_submenu();
    }

    public function add_submenu()
    {
        add_submenu_page( 
            "sales-rep-manager", 
            "Add New Sales Rep", 
            "Add New Sales Rep", 
            "manage_options", 
            "sales-rep-manager-add", 
            array($this, "submenu_page") 
        );
    }

    public function submenu_page()
    {
        ?>
        <main class="container-fluid mt-2">
            <h1>Add New Sales Rep</h1>

            <?php (new \EncodeonSalesRepManager\Views\Partials\StatusMessage)->render(); ?>

            <div class="card col-md-12">
                <form id="create-new-sales-rep" method="post">

                    <input type="hidden" name="action" value="create_sales_rep">
                    <input type="hidden" name="create_sales_rep_nonce" value="<?php echo wp_create_nonce('create_sales_rep'); ?>">

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="name">Sales Representative Name <span class="required">*</span></label>
                            <input type="text" class="form-control" name="name" placeholder="Enter name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter email">
                        </div>    
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="phone">Phone</label>
                            <input type="tel" name="phone" class="form-control" placeholder="Enter phone number">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="cell">Cell</label>
                            <input type="tel" name="cell" class="form-control" placeholder="Enter cell number">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="fax">Fax</label>
                            <input type="tel" name="fax" class="form-control" placeholder="Enter fax number">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="company">Company</label>
                            <input type="text" class="form-control" name="company" placeholder="Enter company name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="url">Website</label>
                            <input type="url" class="form-control" name="url" placeholder="Enter website url">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address1">Address</label>
                        <input type="text" class="form-control" name="address1" placeholder="Enter first line of address">
                    </div>
                    <div class="form-group">
                        <label for="address2">Address 2</label>
                        <input type="text" class="form-control" name="address2" placeholder="Enter second line of address (if applicable)">
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="city">City</label>
                            <input type="text" class="form-control" name="city" placeholder="Enter city name">
                        </div>

                        <?php $states = unserialize(get_option("encodeon_sales_reps_states")); ?>

                        <div class="form-group col-md-4">
                            <label for="state">State <span class="required">*</span></label>
                            <select name="state" class="form-control">
                                <option value="" selected="selected" disabled>Select State</option>
                                <?php foreach ($states as $key => $state): ?>
                                    <option value="<?php echo $key; ?>">
                                        <?php echo $state; ?>
                                    </option>
                                <?php endforeach; ?>    
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="zip">Zip</label>
                            <input type="text" class="form-control" name="zip" placeholder="Enter zip code">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 text-center">
                            <button type="submit" class="btn btn-primary ">
                                <i class="fas fa-plus-circle"></i>
                                Add sales rep
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </main>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                // AJAX call for creating new sales rep
                $("#create-new-sales-rep").on("click", "button[type='submit']", function(event) {
                    event.preventDefault();
                    $(".status-message").html("<div class='alert alert-info'>Processing your request. Please wait...</div>");

                    var form_data = $("#create-new-sales-rep").serialize();
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
