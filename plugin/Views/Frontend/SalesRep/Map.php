<?php
namespace EncodeonSalesRepManager\Views\Frontend\SalesRep;
class Map
{
    public function __construct()
    {
        add_shortcode("sales-rep-map", array($this, "render"));
    }

    public function render()
    {
        ?>
        <form class="col-12 col-md-6 col-xl-4 offset-md-3 offset-xl-4">
            <?php $states = unserialize(get_option("encodeon_sales_reps_states")); ?>

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
            <div class="modal-dialog .modal-dialog-centered h-100 d-flex flex-column justify-content-center my-0" style="margin:auto" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"></div>
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

                    $("#vmap").vectorMap({
                        map: "usa_en",
                        backgroundColor: "#FFF",
                        borderColor: "#FFF",
                        borderOpacity: 0.5,
                        borderWidth: 2,
                        color: "#CCC",
                        enableZoom: false,
                        hoverColor: "#29F",
                        hoverOpacity: null,
                        normalizeFunction: "linear",
                        selectedColor: "#07E",
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

                        /**
                         * Dot loading animation that appends dots 
                         * to the end of the loading text. 
                         */
                        $(".modal-body").html("<div class='loader'></div>");

                        var loaderText = "Loading, please wait";
                        $(".loader").text(loaderText);

                        function displayLoader() {
                            var numDots = 6;
                            var loaderTextLengthWithDots = loaderText.length + numDots;
                            var currentLoaderTextLength = $(".loader").text().length;

                            if (currentLoaderTextLength < loaderTextLengthWithDots) {
                                $(".loader").append(".");
                            } else {
                                $(".loader").text(loaderText);
                            }
                        };

                        // runLoader starts the repeating animation
                        var runLoader = setInterval(function() {
                            displayLoader();
                        }, 150);

                        $.ajax({
                            url: "<?php echo admin_url('admin-ajax.php'); ?>",
                            type: "post",
                            data: form_data,
                            processData: false,
                            contentType: false,
                            success: function(data) {
                                $(".modal-body").hide().html(data).slideDown(1000);
                            },
                            error: function(xhr, desc, err) {
                                $(".modal-body").html("<div class='alert alert-danger'>Error: " + err + "</div>");
                            },
                            complete: function(xhr, status) {
                                // End the loading animation
                                clearInterval(runLoader);
                            }
                        });
                    }

                    function resize_modal() {
                        $(".modal-dialog").css("max-width", $(".page-content").width());
                        $(".modal .modal-body").css("overflow-y", "auto"); 
                        $(".modal .modal-body").css("max-height", $(window).height() * 0.3);
                        $(".modal .modal-content").css({
                            "width": $("#vmap").width(),
                            "margin": "auto"
                        });
                    }
                });
            }(jQuery));
        </script>
        <?php
    }
}
