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
        ?>
        <form class="col-12 col-md-6 col-xl-4 offset-md-3 offset-xl-4">
            <?php $states = array(
                "AL" => "Alabama", "AK" => "Alaska", "AZ" => "Arizona", "AR" => "Arkansas", "CA" => "California", "CO" => "Colorado", "CT" => "Connecticut", "DE" => "Delaware", "DC" => "District of Columbia", "FL" => "Florida", "GA" => "Georgia", "HI" => "Hawaii", "ID" => "Idaho", "IL" => "Illinois", "IN" => "Indiana", "IA" => "Iowa", "KS" => "Kansas", "KY" => "Kentucky", "LA" => "Louisiana", "ME" => "Maine", "MD" => "Maryland", "MA" => "Massachusetts", "MI" => "Michigan", "MN" => "Minnesota", "MS" => "Mississippi", "MO" => "Missouri", "MT" => "Montana", "NE" => "Nebraska", "NV" => "Nevada", "NH" => "New Hampshire", "NJ" => "New Jersey", "NM" => "New Mexico", "NY" => "New York", "NC" => "North Carolina", "ND" => "North Dakota", "OH" => "Ohio", "OK" => "Oklahoma", "OR" => "Oregon", "PA" => "Pennsylvania", "RI" => "Rhode Island", "SC" => "South Carolina", "SD" => "South Dakota", "TN" => "Tennessee", "TX" => "Texas", "UT" => "Utah", "VT" => "Vermont", "VA" => "Virginia", "WA" => "Washington", "WV" => "West Virginia", "WI" => "Wisconsin", "WY" => "Wyoming"
            ); ?>

            <div class="form-group">
                <select id="state-select" class="form-control">
                    <option value="" selected="selected">Select a state here or click on a state below</option>
                    <?php foreach ($states as $key => $state): ?>
                        <option value="<?php echo $key; ?>">
                            <?php echo $state; ?>
                        </option>
                    <?php endforeach; ?>    
                </select>
            </div>
        </form>

        <div id="vmap" class="col-12 col-xl-8 offset-xl-2"></div>

        <div class="modal" id="sales-rep-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog .modal-dialog-centered h-100 d-flex flex-column justify-content-center my-0" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer justify-content-center">
                        <div>
                            <button type="button" class="btn btn-lg btn-danger" data-dismiss="modal">
                            Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            (function($) {
                $(document).ready(function() {
                    function resize_map() {
                        $('#vmap').height($("#vmap").width()*2/3);
                    }
                    resize_map();

                    window.addEventListener("resize", function() {
                        resize_map();
                    });

                    $("#state-select").on("change", function() {
                        var state = $(this).find("option:selected").text();
                        var code = this.value;
                        if (code != "") {
                            get_state_reps(state, code);
                        }
                    })

                    $('#vmap').vectorMap({
                        map: 'usa_en',
                        backgroundColor: "#FFF",
                        borderColor: "#FFF",
                        borderOpacity: 0.5,
                        borderWidth: 2,
                        color: '#CCC',
                        enableZoom: false,
                        hoverColor: '#29F',
                        hoverOpacity: null,
                        normalizeFunction: 'linear',
                        selectedColor: '#07E',
                        selectedRegions: null,
                        showTooltip: true,
                        onRegionClick: function(element, code, region)
                        {
                            get_state_reps(region, code);
                        }
                    });

                    function get_state_reps(state, code) {
                        resize_modal();
                        window.addEventListener("resize", function() {
                            resize_modal();
                        });

                        $(".modal-title").html(state + " Sales Representatives");
                        $("#sales-rep-modal").modal("toggle");

                        var form_data = new FormData();
                        form_data.append("action", "get_state_sales_reps");
                        form_data.append(
                            "get_state_sales_reps_nonce", 
                            "<?php echo wp_create_nonce('get_state_sales_reps'); ?>"
                        );
                        form_data.append("state", code);

                        $(".modal-content").hide();
                        $.ajax({
                            url: "<?php echo admin_url('admin-ajax.php'); ?>",
                            type: "post",
                            data: form_data,
                            processData: false,
                            contentType: false,
                            success: function(data) {
                                
                                $(".modal-body").html(data);
                                $(".modal-content").fadeIn(200);
                            },
                            error: function(xhr, desc, err) {
                                $(".modal-body").html("<div class='alert alert-danger'>Error: " + err + "</div>");
                            }
                        });
                    }

                    function resize_modal() {
                        $(".modal-dialog").css("max-width", $(".page-content").width());
                        $('.modal .modal-body').css('overflow-y', 'auto'); 
                        $('.modal .modal-body').css('max-height', $(window).height() * 0.5);
                    }
                });
            }(jQuery));
        </script>
        <?php
    }
}
