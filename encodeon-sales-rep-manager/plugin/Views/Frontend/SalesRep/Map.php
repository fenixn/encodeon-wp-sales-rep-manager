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
        <div id="vmap" class="col-md-12"></div>

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

                    $('#vmap').vectorMap({
                        map: 'usa_en',
                        backgroundColor: "#FFF",
                        borderColor: "#FFF",
                        borderOpacity: 0.5,
                        borderWidth: 2,
                        color: '#CCC',
                        enableZoom: false,
                        hoverColor: '#39F',
                        hoverOpacity: null,
                        normalizeFunction: 'linear',
                        selectedColor: '#36F',
                        selectedRegions: null,
                        showTooltip: true,
                        onRegionClick: function(element, code, region)
                        {
                            var message = 'You clicked "'
                                + region
                                + '" which has the code: '
                                + code.toUpperCase();

                            console.log(message);
                        }
                    });
                });
            }(jQuery));
        </script>
        <?php
    }
}
