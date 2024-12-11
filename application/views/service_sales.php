        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Monitoring SPK</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li class="active">Monitoring SPK</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data">
                            <thead>
                                <tr>
                                    <th width="10%">#</th>
                                    <th>No SPK</th>
                                    <th>Tanggal</th>
                                    <th>Nama Customer</th>
                                    <th>Nama Sales</th>
                                    <th>Nopol</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="details" data-index="">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="largeModalLabel">Detail</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="table-detail">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kode Item</th>
                                        <th>Nama</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Sub</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <td colspan="4">Total</td>
                                        <td><span class="total"></span></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $("#table-detail").DataTable({
                "processing": true,
                "serverSide": true,
                autoWidth: false,
                info:false,
                filter:false,
                lengthChange:false,
                paging:false,
                "ajax": {"url": "<?=base_url("service_sales/json_details");?>/"}
                });

            $("body").on("click",".btn-view",function(){
                var id = jQuery(this).attr("data-id");
                var total = jQuery(this).attr("data-total");

                jQuery("#details .total").html("Rp "+total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
                jQuery("#table-detail").DataTable().ajax.url("<?=base_url("service_sales/json_details");?>/"+id).load();
                jQuery("#details").modal("toggle");

            })

            $("body").on("click",".btn-batal",function(){
                        var id = $(this).data("id");
                        $.ajax({
                            url: "Service_sales/batal", // Adjust the URL to match your controller's method
                            method: "POST",
                            data: { id: id },
                            success: function(response) {
                                // Handle success, maybe update the UI or display a message
                                location.reload();
                            },
                            error: function() {
                                // Handle error, if any
                            }
                        });
                        $('#data').DataTable().ajax.reload();
                    });

            $("#data").DataTable({
                "processing": true,
                "serverSide": true,
                "autoWidth":true,
                "order": [[0,"desc"]],
                "ajax": {"url": "<?=base_url("service_sales/json");?>"}
            });
        </script>