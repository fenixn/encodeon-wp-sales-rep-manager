<?php
namespace EncodeonSalesRepManager\Views\Admin\SalesRep;
class CreateView
{
    public function __construct()
    {
        $this->add_submenu();
    }

    public function add_submenu()
    {
        add_submenu_page( 
            'sales-rep-manager', 
            'Add New Sales Rep', 
            'Add New Sales Rep', 
            'manage_options', 
            'sales-rep-manager-add', 
            array($this, 'submenu_page') 
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
                            <label for="name">Sales Representative Name</label>
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
                            <label for="url">Email</label>
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
                        <div class="form-group col-md-4">
                            <label for="state">State</label>
                            <select name="state" class="form-control">
                                <option value="" selected="selected" disabled>Select State</option>
                                <option value="AL">Alabama</option>
                                <option value="AK">Alaska</option>
                                <option value="AZ">Arizona</option>
                                <option value="AR">Arkansas</option>
                                <option value="CA">California</option>
                                <option value="CO">Colorado</option>
                                <option value="CT">Connecticut</option>
                                <option value="DE">Delaware</option>
                                <option value="DC">District Of Columbia</option>
                                <option value="FL">Florida</option>
                                <option value="GA">Georgia</option>
                                <option value="HI">Hawaii</option>
                                <option value="ID">Idaho</option>
                                <option value="IL">Illinois</option>
                                <option value="IN">Indiana</option>
                                <option value="IA">Iowa</option>
                                <option value="KS">Kansas</option>
                                <option value="KY">Kentucky</option>
                                <option value="LA">Louisiana</option>
                                <option value="ME">Maine</option>
                                <option value="MD">Maryland</option>
                                <option value="MA">Massachusetts</option>
                                <option value="MI">Michigan</option>
                                <option value="MN">Minnesota</option>
                                <option value="MS">Mississippi</option>
                                <option value="MO">Missouri</option>
                                <option value="MT">Montana</option>
                                <option value="NE">Nebraska</option>
                                <option value="NV">Nevada</option>
                                <option value="NH">New Hampshire</option>
                                <option value="NJ">New Jersey</option>
                                <option value="NM">New Mexico</option>
                                <option value="NY">New York</option>
                                <option value="NC">North Carolina</option>
                                <option value="ND">North Dakota</option>
                                <option value="OH">Ohio</option>
                                <option value="OK">Oklahoma</option>
                                <option value="OR">Oregon</option>
                                <option value="PA">Pennsylvania</option>
                                <option value="RI">Rhode Island</option>
                                <option value="SC">South Carolina</option>
                                <option value="SD">South Dakota</option>
                                <option value="TN">Tennessee</option>
                                <option value="TX">Texas</option>
                                <option value="UT">Utah</option>
                                <option value="VT">Vermont</option>
                                <option value="VA">Virginia</option>
                                <option value="WA">Washington</option>
                                <option value="WV">West Virginia</option>
                                <option value="WI">Wisconsin</option>
                                <option value="WY">Wyoming</option>
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
                $('#create-new-sales-rep').on("click", "button[type='submit']", function(event) {
                    event.preventDefault();
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
