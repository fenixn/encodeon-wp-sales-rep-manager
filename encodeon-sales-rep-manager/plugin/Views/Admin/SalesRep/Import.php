<?php
namespace EncodeonSalesRepManager\Views\Admin\SalesRep;
class Import
{
    public function __construct()
    {
        $this->add_submenu();
    }

    public function add_submenu()
    {
        add_submenu_page( 
            'sales-rep-manager', 
            'Import', 
            'Import', 
            'manage_options', 
            'sales-rep-manager-import', 
            array($this, 'submenu_page') 
        );
    }

    public function submenu_page()
    {
        ?>
        <style>
        .custom-file-control.selected:lang(en)::after {
            content: "" !important;
        }
        </style>

        <script>
        (function($) {
            $(document).ready(function() {
                $('.custom-file-input').on('change',function(){
                    var fileName = $(this).val().split('\\').pop();
                    $(this).next('.form-control-file').addClass("selected").html(fileName);
                })
            });
        }(jQuery));
        </script>

        <main class="container-fluid mt-2">
            <h1>Sales Rep Importer</h1>
            <div class="row-fluid">
                <div class="card col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Control Panel</h4>
                            <div class="btn-toolbar" role="toolbar">
                                <div class="btn-group mr-2" role="group" aria-label="First group">
                                    <button type="button" class="btn btn-primary">Controls</button>
                                    <button type="button" class="btn btn-primary">Buttons</button>
                                </div>
                                <div class="btn-group mr-2" role="group" aria-label="Second group">
                                    <button type="button" class="btn btn-primary">5</button>
                                    <button type="button" class="btn btn-primary">6</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-md-0 mt-4">
                            <h4>Spreadsheet Upload</h4>
                            <form>
                                <label class="custom-file">
                                    <input type="file" class="custom-file-input" id="exampleInputFile" aria-describedby="fileHelp">
                                    <span class="custom-file-control form-control-file "></span>
                                </label>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row-fluid">
                <div class="card col-md-12 import-log">
                    <div class="import-log-header">
                        <h2>Import Logs</h2>
                    </div>
                    <div class="import-log-content"></div>
                </div>
            </div>

            <div class="row-fluid">
                <div class="card col-md-12 import-table">
                    <div class="import-table-header">
                        <h2>Import Preview</h2>
                    </div>
                    <div class="import-table-content"></div>
                </div>
            </div>
        </main>
        <?php
    }
}
