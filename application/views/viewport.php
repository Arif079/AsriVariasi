        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>HPP & Jurnal</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li class="active">View</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="datahpp">
                            <thead>
                                <tr>
                                    <th style="width:10%">#</th>
                                    <th>Kode HPP</th>
                                    <th>Tanggal</th>
                                    <th>Tipe</th>
                                    <th>Transaksi</th>
                                    <th>Referensi</th>
                                    <th>KD Item</th>
                                    <th>Qty</th>
                                    <th>Qty ecer</th>
                                    <th>PPN</th>
                                    <th>Harga </th>
                                    <th>Harga Satuan</th>
                                    <th>Total</th>
                                    <th>HPP</th>
                                    <th>HPP Satuan</th>
                                 </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="datajurnal">
                            <thead>
                                <tr>
                                    <th style="width:10%">#</th>
                                    <th>Kode Jurnal</th>
                                    <th>Tanggal</th>
                                    <th>Kode Transaksi</th>
                                    <th>Tipe</th>
                                    <th>COA</th>
                                    <th>Debet</th>
                                    <th>Kredit</th>
                                    <th>Status</th>
                                 </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

      

        <script>

                $("#datahpp").DataTable({
                    "processing": true,
                    "serverSide": true,
                    "autoWidth":true,
                    "order": [1,'desc'],
                    "ajax": {"url": "<?=base_url("viewdata/json");?>"}
                });

                $("#datajurnal").DataTable({
                    "processing": true,
                    "serverSide": true,
                    "autoWidth":true,
                    "order": [2,'desc'],
                    "ajax": {"url": "<?=base_url("viewdata/jsonjurnal");?>"}
                });


        </script>