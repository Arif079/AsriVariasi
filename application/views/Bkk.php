<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Bukti Kas Keluar</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li class="active">Bukti Kas Keluar K</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="card">
                <div class="card-header">
                <h5> Bukti Pembayaran Lain</h5>
                    <button class="btn btn-success btn-sm btn-show-add" data-toggle="modal" data-target="#compose"><i class="fa fa-plus"></i> Tambah BKK</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NBL</th>
                                    <th>Vendor</th>
                                    <th>Debet</th>
                                    <th>Kredit</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
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
                    <h5>Bukti Pembayaran Vendor</h5>
                    <a href="<?=base_url("bkk/new");?>" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah Pembayaran</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data-bkk">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>BKK</th>
                                    <th>NPH</th>
                                    <th>Vendor</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Bayar</th>
                                    <th>Sisa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="compose" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="largeModalLabel">Tambah BKK</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="compose-form">
                            <div class="form-group">
                                <label>Nama Vendor</label>
                                <input type="text" name="customer" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Alamat Vendor</label>
                                <input type="text" name="alamat" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Kode Akun (debit)</label>
                                <input type="text" class="form-control select-search" placeholder="Search...">
                                <select name="akundebit" class="form-control coa">
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($coa as $akun) {
                                    if ($akun->isdetail == '1') {
                                    echo '<option value="'.$akun->coa.'">'.$akun->coa.' 
                                    - '.$akun->nama.'</option>
                                    ';}
                                }
                                ?>
                            </select>
                            </div>
                            <div class="form-group">
                                <label>Kode Akun (kredit)</label>
                                <input type="text" class="form-control select-search" placeholder="Search...">
                                <select name="akunkredit" class="form-control coa">
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($coa as $akun) {
                                    if ($akun->isdetail == '1') {
                                    echo '<option value="'.$akun->coa.'">'.$akun->coa.' 
                                    - '.$akun->nama.'</option>
                                    ';}
                                }
                                ?>
                            </select>
                            </div>
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="text" name="date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Uraian</label>
                                <input type="text" name="uraian" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Jumlah</label>
                                <input type="number" name="jumlah" class="form-control" id="jumlah" value="0">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary btn-submit">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="delete" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="largeModalLabel">Konfirmasi?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary btn-del-confirm">Hapus</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
                $(".btn-show-add").on("click",function(){
                    jQuery("input[name=customer]").val("");
                    jQuery("input[name=alamat]").val("");
                    jQuery("select[name=akundebit]").val("");
                    jQuery("select[name=akunkredit]").val("");
                    jQuery("input[name=uraian]").val("");
                    jQuery("input[name=jumlah]").val("");
                    jQuery("input[name=date]").val("");
                    jQuery("#compose .modal-title").html("Tambah BKK");
                    jQuery("#compose-form").attr("action","<?=base_url("Bkk/insert");?>");
                });

                $("#data").DataTable({
                    "processing": true,
                    "serverSide": true,
                    "autoWidth":true,
                    "order": [[0,"desc"]],
                    "ajax": {"url": "<?=base_url("Bkk/json");?>"}
                });

                $("#data-bkk").DataTable({
                    "processing": true,
                    "serverSide": true,
                    "autoWidth":true,
                    "order": [[0,"desc"]],
                    "ajax": {"url": "<?=base_url("Bkk/json_bkk");?>"}
                });

                $('.btn-submit').on("click",function(){
                    var form = {
                        "customer": jQuery("input[name=customer]").val(),
                        "alamat": jQuery("input[name=alamat]").val(),
                        "coad": jQuery("select[name=akundebit]").val(),
                        "coak": jQuery("select[name=akunkredit]").val(),
                        "uraian": jQuery("input[name=uraian]").val(),
                        "jumlah": jQuery("input[name=jumlah]").val(),
                        "date": jQuery("input[name=date]").val()
                    }

                    var action = jQuery("#compose-form").attr("action");

                    jQuery.ajax({
                        url: action,
                        method: "POST",
                        data: form,
                        dataType: "json",
                        success: function(data){
                            if(data.status) {
                                jQuery("input[name=customer]").val("");
                                jQuery("input[name=alamat]").val("");
                                jQuery("select[name=akundebit]").val("");
                                jQuery("select[name=akunkredit]").val("");
                                jQuery("input[name=uraian]").val("");
                                jQuery("input[name=jumlah]").val("");
                                jQuery("input[name=date]").val("");
    
                                jQuery("#compose").modal('toggle');
                                jQuery("#data").DataTable().ajax.reload(null,true);
    
                                Swal.fire(
                                    'Berhasil',
                                    data.msg,
                                    'success'
                                )
                            } else {
                                Swal.fire(
                                    'Gagal',
                                    data.msg,
                                    'error'
                                )
                            }
                        }
                    });
                });

                $('body').on("click",".btn-delete",function() {
                    var id = jQuery(this).attr("data-id");
                    var uraian = jQuery(this).attr("data-nbl");
                    jQuery("#delete .modal-body").html("Anda yakin ingin menghapus <b>"+uraian+"</b>");
                    jQuery("#delete").modal("toggle");

                    jQuery("#delete .btn-del-confirm").attr("onclick","deleteData("+id+")");
                })

                function deleteData(id) {
                    jQuery.getJSON("<?=base_url();?>Bkk/delete/"+id,function(data){
                        if(data.status) {
                            jQuery("#delete").modal("toggle");
                            jQuery("#data").DataTable().ajax.reload(null,true);
                            Swal.fire(
                                'Berhasil',
                                data.msg,
                                'success'
                            )
                        } else {
                            Swal.fire(
                                'Berhasil',
                                data.msg,
                                'success'
                            )
                        }
                    })
                }

                $("body").on("click",".btn-edit",function(){
                    var id = jQuery(this).attr("data-id");
                    var customer = jQuery(this).attr("data-customer");
                    var alamat = jQuery(this).attr("data-alamat");
                    var coad = jQuery(this).attr("data-coad");
                    var coak = jQuery(this).attr("data-coak");
                    var uraian = jQuery(this).attr("data-uraian");
                    var jumlah = jQuery(this).attr("data-jumlah");
                    var date = jQuery(this).attr("data-date");

                    jQuery("#compose .modal-title").html("Edit BKk");
                    jQuery("#compose-form").attr("action","<?=base_url();?>Bkk/update/"+id);
                    jQuery("input[name=customer]").val(customer);
                    jQuery("input[name=alamat]").val(alamat);
                    jQuery("select[name=akundebit]").val(coad);
                    jQuery("select[name=akunkredit]").val(coak);
                    jQuery("input[name=uraian]").val(uraian);
                    jQuery("input[name=jumlah]").val(jumlah);
                    jQuery("input[name=date]").val(date);

                    jQuery("#compose").modal("toggle");
                });

        </script>
         <script>
    // ... Your existing script ...

    // Add this datepicker initialization code
    $(document).ready(function () {
        // Add an input event listener to the search inputs
        $('.select-search').on('input', function () {
            var filterValue = $(this).val().toLowerCase();
            filterSelect($(this).siblings('select'), filterValue);
        });

        // Function to filter the options in the select element
        function filterSelect(select, filterValue) {
            var options = select.find('option');

            // Loop through each option and show/hide based on the filter
            options.each(function () {
                var option = $(this);
                var text = option.text().toLowerCase();
                if (text.includes(filterValue) || filterValue === '') {
                    option.show();
                } else {
                    option.hide();
                }
            });
         }
        });
    $(document).ready(function() {
      //  $('#coad').select2();
      //  $('#coak').select2();
        $('input[name="date"]').datepicker({
            format: 'dd-mm-yyyy', // You can change the format according to your needs
            autoclose: true,
            todayHighlight: true,
        });
    });
</script>