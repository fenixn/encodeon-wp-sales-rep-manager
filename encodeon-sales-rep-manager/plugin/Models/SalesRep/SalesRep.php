<?php
namespace EncodeonSalesRepManager\Models\SalesRep;
class SalesRep
{
    public function get_sales_rep($sales_rep_id)
    {
        global $wpdb;
        $prepared_statement = "SELECT * FROM " . get_option("encodeon_sales_rep_table_name") . " WHERE id = %s";

        return $wpdb->get_row(
            $wpdb->prepare(
                $prepared_statement, 
                $sales_rep_id
            ), ARRAY_A
        );
    }

    public function get_state_sales_reps()
    {
        // Anti CSRF
        if (wp_verify_nonce($_REQUEST['get_state_sales_reps_nonce'], "get_state_sales_reps") === false) {
            $this->show_error("Invalid nonce for this request.", true);
            die();
        }

        $state = $_REQUEST['state'];

        $accepted_states = ['AK', 'AL', 'AR', 'AZ', 'CA', 'CO', 'CT', 'DC', 'DE', 'FL', 'GA', 'HI', 'IA', 'ID', 'IL', 'IN', 'KS', 'KY', 'LA', 'MA', 'MD', 'ME', 'MI', 'MN', 'MO', 'MS', 'MT', 'NC', 'ND', 'NE', 'NH', 'NJ', 'NM', 'NV', 'NY', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VA', 'VT', 'WA', 'WI', 'WV', 'WY'];

        if(!in_array($state, $accepted_states) && strlen($state) !== 2) {
            echo "Invalid input for the State.";
            die();
        }

        $prepared_statement = "SELECT * FROM " . get_option("encodeon_sales_rep_table_name") . " WHERE state = %s";

        global $wpdb;

        $sales_reps = $wpdb->get_results(
            $wpdb->prepare(
                $prepared_statement, 
                $state
            ), ARRAY_A
        );

        if ($sales_reps === false) {
            $this->show_error("There was an error connecting to the database. This may be a temporary issue. Please try again and contact the administrator only if the issue persists.");
            die();
        } else {
            ?>
            <div class='container'>
            <?php if(count($sales_reps) == 0): ?>
                We currently do not have a sales representative for this state."
            <?php else: ?>
                <div class='row'>
                    <?php foreach($sales_reps as $sales_rep): ?>
                    <div class="col-md-4">
                        <div class="card-block text-left">
                            <?php if($sales_rep['company'] != ""): ?>
                            <div class="bold"><?php echo $sales_rep['company']; ?></div>
                            <?php endif; ?>

                            <?php if($sales_rep['name'] != ""): ?>
                            <div>Name: <?php echo $sales_rep['name']; ?></div>
                            <?php endif; ?>
                            <?php if($sales_rep['email'] != ""): ?>
                            <div>Email: <a href="mailto:<?php echo $sales_rep['email']; ?>"><?php echo $sales_rep['email']; ?></a></div>
                            <?php endif; ?>
                            
                            <?php if($sales_rep['phone'] != ""): ?>
                            <div>Phone: <a href="tel:1<?php echo $sales_rep['phone']; ?>"><?php echo $this->get_formatted_phone_number($sales_rep['phone']); ?></a></div>
                            <?php endif; ?>
                            <?php if($sales_rep['cell'] != ""): ?>
                            <div>Cell: <a href="tel:1<?php echo $sales_rep['cell']; ?>"><?php echo $this->get_formatted_phone_number($sales_rep['cell']); ?></a></div>
                            <?php endif; ?>
                            <?php if($sales_rep['fax'] != ""): ?>
                            <div>Fax: <a href="tel:1<?php echo $sales_rep['fax']; ?>"><?php echo $this->get_formatted_phone_number($sales_rep['fax']); ?></a></div>
                            <?php endif; ?>


                            <?php if($sales_rep['address1'] != ""): ?>
                            <div><?php echo $sales_rep['address1']; ?></div>
                            <?php if($sales_rep['address2'] != ""): ?>
                            <div><?php echo $sales_rep['address2']; ?></div>
                            <?php endif; ?>
                            <div><?php echo $sales_rep['city']; ?>, <?php echo $sales_rep['state']; ?> <?php echo $sales_rep['zip']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            </div>
            <?php
        }
    }

    public function generate_sales_rep_table()
    {
        if(!current_user_can('manage_options')) {
            $this->show_error("Invalid authorization.");
            die();
        };

        global $wpdb;

        $attribute = $_REQUEST['attribute'];
        $sort = $_REQUEST['sort'];
        $page = $_REQUEST['page'];
        $limit = $_REQUEST['limit'];
        $search_term = htmlspecialchars($_REQUEST['search_input']);

        // Anti CSRF
        if (wp_verify_nonce($_REQUEST['generate_sales_rep_table_nonce'], "generate_sales_rep_table") === false) {
            $this->show_error("Invalid nonce for the generate sales rep table request.", true);
            die();
        }

        // Search input validation
        if(preg_match("/[^a-zA-Z0-9 ]+/", $search_term)) {
            $this->show_error("Invalid characters detected in search.", true);
            die();
        }

        // Attribute input validation using whitelisting strategy
        $allowed_attributes = ['id', 'name', 'email', 'phone', 'cell', 'fax', 'company', 'url', 'address1', 'address2', 'city', 'state', 'zip'];
        $allowed_sort = ['ASC', 'DESC'];

        if(!in_array($attribute, $allowed_attributes) || !in_array($sort, $allowed_sort)) {
            $this->show_error("Invalid sort input.", true);
            die();
        }

        // Test if page input is a positive integer
        if(!preg_match("/^[+]?[1-9]\d*$/", $page)) {
            $this->show_error("Invalid input for page number.", true);
            die();
        }

        // Test if limit input is a positive integer
        if(!preg_match("/^[+]?[1-9]\d*$/", $limit)) {
            $this->show_error("Invalid input for sales rep per page.", true);
            die();
        }

        /**
         * Set Pagination variables
         */

        // Get the total number of rows first to calculate total number of pages
        $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM " . get_option('encodeon_sales_rep_table_name'));
        $total_pages = ceil($num_rows/$limit);

        if ($page > $total_pages) {
            die("Invalid page input");
        }

        // Calculate the offset, and check if it's viable
        $offset = ($page - 1) * $limit;

        if (($offset > $num_rows) || $offset < 0) {
            die("Invalid offset for sales rep table.");
        }

        if ($search_term != "") {
            $search_condition = " WHERE ";
        
            $table_headers = $wpdb->get_results("SHOW COLUMNS FROM " . get_option('encodeon_sales_rep_table_name'), ARRAY_A);

            foreach($table_headers as $table_header) {
                $search_condition .= $table_header['Field'] . " LIKE %s OR ";
            }

            // Remove trailing OR
            $search_condition = substr($search_condition, 0, -3);
        } else {
            $search_condition = " ";
        }

        $like_search = '%' . $wpdb->esc_like($search_term) . '%';

        $prepared_statement = "SELECT * FROM " . get_option("encodeon_sales_rep_table_name") . $search_condition . " ORDER BY {$attribute} {$sort} LIMIT {$limit} OFFSET {$offset}";

        $sales_reps = $wpdb->get_results(
            $wpdb->prepare(
                $prepared_statement, 
                $like_search, $like_search, $like_search, $like_search, $like_search, $like_search, $like_search, $like_search, $like_search, $like_search, $like_search, $like_search, $like_search
            ), ARRAY_A
        );

        if ($sales_reps === false) {
            $this->show_error("There was an error connecting to the database. This may be a temporary issue. Please try again and contact the administrator only if the issue persists.");
            die();
        } else if ($sales_reps === 0) {
            $this->show_error("Connection to the database succeeded, but no data was returned. Check your inputs and try again.");
            die();
        }

        ?>

        <div id="table-data"
            data-attribute-name="<?php echo $attribute; ?>"
            data-attribute-sort="<?php echo $sort; ?>"
            data-page="<?php echo $page; ?>"
            data-limit="<?php echo $limit; ?>"
            data-search="<?php echo $search_term; ?>"
        ></div>

        <div class="row">
            <div class="col-sm">
                <?php $this->get_pager($page, $total_pages); ?>
            </div>

            <div class="col-sm">
                <form id='sales-rep-search'>
                    <div class="input-group">
                        <input type="text" id="sales-rep-search-input"
                            class="form-control" placeholder="Enter search here"
                            value="<?php echo $search_term; ?>">
                            
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary" style="cursor: pointer;">Search</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-2">
            <div class="table-responsive">
                <?php if (count($sales_reps) > 0): ?>
                    <table id="sales-rep-manager-table" class="table table-striped table-sm">
                        <thead class="thead-inverse">
                        <?php foreach($sales_reps[0] as $key => $sales_rep): ?>
                            <th class="text-capitalize bg-primary text-nowrap" 
                                style="cursor: pointer"
                                data-attribute-name="<?php echo $key; ?>"
                                data-attribute-sort="<?php echo $sort; ?>">
                            <?php $this->get_header_icon($key); ?>
                            <?php echo $key; ?>
                            <?php $this->get_sort_icon($attribute, $key, $sort); ?>
                            </th>
                        <?php endforeach; ?>
                        </thead>
                        <?php foreach($sales_reps as $sales_rep): ?>
                        <tr>
                            <?php foreach($sales_rep as $key => $sales_rep_attribute): ?>
                            <td>                                
                                <?php if ($key == 'name'): ?>
                                    <a href="admin.php?page=sales-rep-manager-edit&id=<?php echo $sales_rep['id']; ?>"><?php echo $sales_rep_attribute; ?></a>
                                <?php else: ?>
                                    <?php echo $sales_rep_attribute; ?>
                                <?php endif; ?>
                            </td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <div class="col-sm">There are no matches with your search. Please try another search.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-sm">
                <?php $this->get_pager($page, $total_pages); ?>
            </div>
        </div>

        <?php
    }

    public function get_pager($page, $total_pages, $pager_increment = 3)
    {
        if (($page - $pager_increment) <= 1) {
            $pager_start = 1;

            if ($pager_start + 2 * $pager_increment >= $total_pages) {
                $pager_end = $total_pages;
            } else {
                $pager_end = $pager_start + 2 * $pager_increment;
            }
        } else {
            $pager_start = $page - $pager_increment;

            if (($page + $pager_increment) >= $total_pages) {
                $pager_end = $total_pages;

                if (($pager_end - 2 * $pager_increment) <= 1) {
                    $pager_start = 1;
                } else {
                    $pager_start = $total_pages - 2 * $pager_increment;
                }
            } else {
                $pager_end = $page + $pager_increment;
            }
        }
        ?>

        <nav aria-label="Sales Rep Pagination">
            <ul class="pagination">
                <li class="page-item text-nowrap <?php if ($page == 1) echo "disabled" ?>" 
                    data-page="1"
                    data-active="<?php echo ($page == 1 ? 0 : 1); ?>">
                    <a href="" class="page-link">
                        1
                        <i class="fas fa-caret-left d-none d-sm-inline"></i>
                        <i class="fas fa-caret-left d-none d-sm-inline"></i>
                    </a>
                </li>
                <li class="page-item <?php if ($page == 1) echo "disabled" ?>"
                    data-page="<?php echo ($page-1); ?>"
                    data-active="<?php echo ($page == 1 ? 0 : 1); ?>">
                    <a href="" class="page-link">
                        <i class="fas fa-caret-left"></i>
                    </a>
                </li>
                <?php for($i=$pager_start; $i<=$pager_end; $i++): ?>
                <li class="page-item <?php if ($page == $i) echo "active" ?>" 
                    data-page="<?php echo $i; ?>"
                    data-active="<?php echo ($page == $i ? 0 : 1); ?>">
                    <a href="" class="page-link"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?php if ($page == $total_pages) echo "disabled" ?>"
                    data-page="<?php echo ($page+1); ?>"
                    data-active="<?php echo ($page == $total_pages ? 0 : 1); ?>">
                    <a href="" class="page-link">
                        <i class="fas fa-caret-right"></i>
                    </a>
                </li>
                <li class="page-item text-nowrap <?php if ($page == $total_pages) echo "disabled" ?>" 
                    data-page="<?php echo $total_pages; ?>"
                    data-active="<?php echo ($page == $total_pages ? 0 : 1); ?>">
                    <a href="" class="page-link">
                        <i class="fas fa-caret-right d-none d-sm-inline"></i>
                        <i class="fas fa-caret-right d-none d-sm-inline"></i>
                        <?php echo $total_pages; ?>
                    </a>
                </li>
            </ul>
        </nav>
        <?php
    }

    public function get_sort_icon($sort_attribute, $current_attribute, $sort_by) 
    {
        if ($sort_attribute === $current_attribute) {
            if ($sort_by === 'ASC') {
                echo "<i class=\"fas fa-sort-up\"></i>";
            } else {
                echo "<i class=\"fas fa-sort-down\"></i>";
            }
        }
    }

    public function get_header_icon($attribute)
    {
        switch ($attribute) {
            case "id":
                echo "<i class=\"fas fa-id-card d-none d-lg-inline\"></i>";
                break;
            case "name":
                echo "<i class=\"fas fa-user d-none d-lg-inline\"></i>";
                break;
            case "email":
                echo "<i class=\"fas fa-envelope d-none d-lg-inline\"></i>";
                break;
            case "phone":
                echo "<i class=\"fas fa-phone d-none d-lg-inline\"></i>";
                break;
            case "cell":
                echo "<i class=\"fas fa-mobile d-none d-lg-inline\"></i>";
                break;
            case "fax":
                echo "<i class=\"fas fa-fax d-none d-lg-inline\"></i>";
                break;
            case "company":
                echo "<i class=\"fas fa-building d-none d-lg-inline\"></i>";
                break;
            case "url":
                echo "<i class=\"fas fa-link d-none d-lg-inline\"></i>";
                break;
            default:
                break;
        }
    }

    public function get_formatted_phone_number($phone_number) {
        if (strlen($phone_number) == 10 && preg_match("/^\d{10}$/", $phone_number)) {
            $formatted_phone_number = "(" . substr($phone_number, 0, 3) . ") " . substr($phone_number, 3, 3) . "-" . substr($phone_number, 6, 4);
            return $formatted_phone_number;
        } else {
            return $phone_number;
        }
    }

    public function show_error($error, $refresh = false)
    {
        $error_html = "<div class='alert alert-danger'>Error: " . $error; 
        
        if ($refresh) {
            $error_html .= " <a href='admin.php?page=sales-rep-manager'>Click here to refresh the page.</a>";
        }

        $error_html .= "</div>";
        ?>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $(".status-message").html("<?php echo $error_html; ?>");
            });
        </script>
        <?php
    }

    public function show_success($success, $after)
    {
        $success_html = "<div class='alert alert-success'>Success: " . $success . "</div>";
        ?>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $(".status-message").html("<?php echo $success_html; ?>");
                <?php echo $after; ?>
            });
        </script>
        <?php
    }
}
