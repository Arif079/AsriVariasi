        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Laporan HPP</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li class="active">Laporan HPP</li>
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
                        <div class="date form-inline">
                        <button id="hppjasa" type="button" class="btn btn-danger btn-sm ml-3 btn-printjasa"><i class="fa fa-print"></i> PDF HPP Jasa</button>
                        <button id="hppvariasi"  class="btn btn-danger btn-sm ml-3 btn-printvariasi"><i class="fa fa-print"></i> PDF HPP Variasi</button>
                        <button id="hppantikarat"  class="btn btn-danger btn-sm ml-3 btn-printantikarat"><i class="fa fa-print"></i> PDF HPP Antikarat</button>
                        <button id="filterjasa" class="btn btn-info btn-sm ml-3"><i class="fa fa-calendar"></i> Jasa</button>
                        <button id="filtervariasi" class="btn btn-info btn-sm ml-3"><i class="fa fa-calendar"></i> Variasi  </button>
                        <button id="filterantikarat" class="btn btn-info btn-sm ml-3"><i class="fa fa-calendar"></i> Antikarat </button>
                        <div class="ml-3"></div> <!-- Add space between buttons and inputs -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>SPK</th>
                                    <th>Customer</th>
                                    <th>Qty</th>
                                    <th>HPP</th>
                                    <th>HARGA</th>
                                    <th>TOTAL</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script>
        const elem = document.querySelector('.date');
        const datepicker = new DateRangePicker(elem, {
            format: "yyyy-mm-dd"
        });

        $("input[name=end]").on("change",function(){
            var start = jQuery("input[name=start]").val();
            var end = jQuery("input[name=end]").val();

            jQuery("#data").DataTable().ajax.url("<?=base_url("report/hpp_json");?>/"+start+"/"+end).load();
        });

        // Function to update hpp_json with constant filter
function updateHppJsonWithFilter(filter) {
    var start = jQuery("input[name=start]").val();
    var end = jQuery("input[name=end]").val();

    // Modify the base URL according to the filter
    var baseUrl = "<?=base_url("report/hpp_json");?>/" + filter + "/";

    // If start and end dates are available, append them to the URL
    if (start && end) {
        baseUrl += start + "/" + end;
    }

    // Update the DataTables ajax URL with the new URL
    jQuery("#data").DataTable().ajax.url(baseUrl).load();
    }

    // Click event handler for Jasa button
    $("#filterjasa").on("click", function () {
        updateHppJsonWithFilter("4-0-301");
    });

    // Click event handler for Variasi button
    $("#filtervariasi").on("click", function () {
        updateHppJsonWithFilter("4-0-302");
    });

    // Click event handler for Antikarat button
    $("#filterantikarat").on("click", function () {
        updateHppJsonWithFilter("4-0-303");
    });


        $("#data").DataTable({
            "processing": true,
            "serverSide": true,
            "autoWidth":true,
            "order": [[0,"asc"]],
            "ajax": {"url": "<?=base_url("report/hpp_json");?>"},
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

    $(".btn-printjasa").on("click", function () {
    var start = jQuery("input[name=start]").val();
    var end = jQuery("input[name=end]").val();

    var baseUrl = "<?=base_url("report/hpp_jasa_pdf");?>/";

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
$(".btn-printvariasi").on("click", function () {
    var start = jQuery("input[name=start]").val();
    var end = jQuery("input[name=end]").val();

    var baseUrl = "<?=base_url("report/hpp_variasi_pdf");?>/";

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
$(".btn-printantikarat").on("click", function () {
    var start = jQuery("input[name=start]").val();
    var end = jQuery("input[name=end]").val();

    var baseUrl = "<?=base_url("report/hpp_ak_pdf");?>/";

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