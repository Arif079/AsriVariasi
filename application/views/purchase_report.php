        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Laporan Pembelian</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li class="active">Laporan Pembelian</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="card">
                <div class="card-header">
                    <div class="date form-inline">
                        <input type="text" name="start" class="form-control form-control-sm" placeholder="YYYY-MM-DD">
                        <span class="mx-2">-</span>
                        <input type="text" name="end" class="form-control form-control-sm" placeholder="YYYY-MM-DD">
                        <button type="button" class="btn btn-danger btn-sm ml-3 btn-print"><i class="fa fa-print"></i> PDF</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data">
                            <thead>
                                <tr>
                                    <th style="width:10%">#</th>
                                    <th>NOP</th>
                                    <th>BTB</th>
                                    <th>NTP</th>
                                    <th>Supplier</th>
                                    <th>Item</th>
                                    <th>Tanggal</th>
                                    <th>Qty</th>
                                    <th>Harga Satuan</th>
                                    <th>Ppn</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script>
         $(document).ready(function() {
        $('.date').datepicker({
            format: "yyyy-mm-dd"
        });
    });


        $("input[name=end]").on("change",function(){
            var start = jQuery("input[name=start]").val();
            var end = jQuery("input[name=end]").val();

            jQuery("#data").DataTable().ajax.url("<?=base_url("report/purchase_json");?>/"+start+"/"+end).load();
        });

        $("#data").DataTable({
            "processing": true,
            "serverSide": true,
            "autoWidth":true,
            "order": [],
            "ajax": {"url": "<?=base_url("report/purchase_json");?>"},
            "columnDefs": [
                {
                    'targets': 0,
                    'className': "text-center" 
                },
                {
                    'targets': 2,
                    'className': "text-center" 
                },
                {
                    'targets': 3,
                    'className': "text-center" 
                },
                {
                    'targets': 4,
                    'className': "text-center" 
                }
            ]
        });

        $(".btn-print").on("click", function () {
    var start = jQuery("input[name=start]").val();
    var end = jQuery("input[name=end]").val();

    var baseUrl = "<?=base_url("report/purchase_pdf");?>/";

    if (start && end) {
        var url = baseUrl + start + "/" + end;
    } else {
        var url = baseUrl;
    }

    // Open the URL in a new tab
    window.open(url, '_blank');

    // Alternatively, you can use the following line to open in the same window
    // window.location.href = url;
});
        </script>