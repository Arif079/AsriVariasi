        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Saldo Awal</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li class="active">Saldo Awal</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-success btn-sm btn-show-add" data-toggle="modal" data-target="#compose"><i class="fa fa-plus"></i> Tambah GLproses</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Kode transaksi</th>
                                    <th>Coa</th>
                                    <th>Nilai Kredit</th>
                                    <th>Nilai Debet</th>
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
                <h5 class="modal-title" id="largeModalLabel">POSTING</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="compose-form">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" name="date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>COA</label>
                        <select name="coa" class="form-control coa" id="coa">
                            <option value="">-- Pilih --</option>
                            <?php
                            foreach($coa as $akun) {
                                if ($akun->isdetail == 1) {
                                    echo '<option value="'.$akun->coa.'">'.$akun->nama.'</option>
                                    ';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nilai Debet</label>
                        <input type="number" name="nilai_debet" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Nilai Kredit</label>
                        <input type="number" name="nilai_kredit" class="form-control">
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
               $(".btn-show-add").on("click", function(){
                $("#compose-form")[0].reset(); // Clear the form fields
                $("#compose .modal-title").html("POSTING"); // Set the modal title
                $("#compose-form").attr("action", "<?=base_url("GLproses/insert");?>"); // Set the form action
            });

                $("#data").DataTable({
                    "processing": true,
                    "serverSide": true,
                    "autoWidth":true,
                    "order": [],
                    "ajax": {"url": "<?=base_url("GLproses/json");?>"}
                });


                            $('.btn-submit').on("click", function(){
                var form = $("#compose-form").serialize();
                var action = $("#compose-form").attr("action");

                jQuery.ajax({
                    url: action,
                    method: "POST",
                    data: form,
                    dataType: "json",
                    success: function(data){
                        if(data.status) {
                            $("#compose").modal('toggle');
                            $("#data").DataTable().ajax.reload(null,true);

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
                    var sld = jQuery(this).attr("data-sld");
                    jQuery("#delete .modal-body").html("Anda yakin ingin menghapus <b>"+sld+"</b>");
                    jQuery("#delete").modal("toggle");

                    jQuery("#delete .btn-del-confirm").attr("onclick","deleteData("+id+")");
                })

                function deleteData(id) {
                    jQuery.getJSON("<?=base_url();?>GLproses/delete/"+id,function(data){
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

               

        </script>
         <script>
    // ... Your existing script ...

    // Add this datepicker initialization code
    $(document).ready(function() {
        
        // Datepicker initialization code (if not already added)

        // Event handler for 'debet' select element
        $('#coa-debet').change(function() {
            var selectedOption = $(this).find('option:selected');
            var nama = selectedOption.text();
            $('#namacoad').val(nama);
        });

        // Event handler for 'kredit' select element
    });
</script>