        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Charge Of Account</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li class="active">Charge of Account</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-success btn-sm btn-show-add" data-toggle="modal" data-target="#compose"><i class="fa fa-plus"></i> Tambah Coa</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>COA</th>
                                    <th>Nama</th>
                                    <th>Header</th>
                                    <th>Grup</th>
                                    <th>Detail</th>
                                    <th>Level </th>
                                    <th>Status</th>
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
                        <h5 class="modal-title" id="largeModalLabel">Tambah COA</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="compose-form">
                            <div class="form-group">
                                <label>Kode COA</label>
                                <input type="text" name="coa" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Nama COA</label>
                                <input type="text" name="nama" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Header COA</label>
                                <select name="header" class="form-control coa">
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($coa as $akun) {
                                    if ($akun->isdetail == '0') {
                                    echo '<option value="'.$akun->coa.'">'.$akun->nama.'</option>
                                    ';}
                                }
                                ?>
                            </select>
                            </div>
                            <div class="form-group">
                                <label>Grup</label>
                                <input type="text" name="grup" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Level COA</label>
                                <select name="level" class="form-control">
                                <option value="H">Header</option>
                                <option value="D">Detail</option>
                              </select>
                            </div>
                            <div class="form-group">
                                <label>Urutan Level</label>
                                <input type="number" name="urutan" class="form-control">
                              </select>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                <option value="Checked">Checked</option>
                                <option value="Unchecked">Unchecked</option>
                              </select>
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
                    jQuery("input[name=coa]").val("");
                    jQuery("input[name=nama]").val("");
                    jQuery("select[name=header]").val("");
                    jQuery("input[name=grup]").val("");
                    jQuery("select[name=level]").val("");
                    jQuery("input[name=urutan]").val("");
                    jQuery("select[name=status]").val("");
                    jQuery("#compose .modal-title").html("Tambah COA");
                    jQuery("#compose-form").attr("action","<?=base_url("Coa/insert");?>");
                });

                $("#data").DataTable({
                    "processing": true,
                    "serverSide": true,
                    "autoWidth":true,
                    "order": [],
                    "ajax": {"url": "<?=base_url("Coa/json");?>"}
                });


                $('.btn-submit').on("click",function(){
                    var form = {
                        "coa": jQuery("input[name=coa]").val(),
                        "nama": jQuery("input[name=nama]").val(),
                        "header": jQuery("select[name=header]").val(""),
                        "grup" : jQuery("input[name=grup]").val(""),
                        "level": jQuery("select[name=level]").val(""),
                        "urutan" : jQuery("input[name=urutan]").val(""),
                        "status": jQuery("select[name=status]").val("")
                    }
               

                    var action = jQuery("#compose-form").attr("action");

                    jQuery.ajax({
                        url: action,
                        method: "POST",
                        data: form,
                        dataType: "json",
                        success: function(data){
                            if(data.status) {
                            jQuery("input[name=coa]").val("");
                            jQuery("input[name=nama]").val("");
                            jQuery("select[name=header]").val("");
                            jQuery("input[name=grup]").val("");
                            jQuery("select[name=level]").val("");
                            jQuery("input[name=urutan]").val("");
                            jQuery("select[name=status]").val("");
    
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
                    var name = jQuery(this).attr("data-nama");
                    jQuery("#delete .modal-body").html("Anda yakin ingin menghapus <b>"+name+"</b>");
                    jQuery("#delete").modal("toggle");

                    jQuery("#delete .btn-del-confirm").attr("onclick","deleteData("+id+")");
                })

                function deleteData(id) {
                    jQuery.getJSON("<?=base_url();?>coa/delete/"+id,function(data){
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
                                'gagal',
                                data.msg,
                                'error'
                            )
                        }
                    }
                )};
                
                $("body").on("click",".btn-edit",function(){
                    var id = jQuery(this).attr("data-id");
                    var coa = jQuery(this).attr("data-coa");
                    var Nama = jQuery(this).attr("data-nama");
                    var header = jQuery(this).attr("data-header");
                    var grup = jQuery(this).attr("data-grup");
                    var level = jQuery(this).attr("data-level");
                    var urutan = jQuery(this).attr("data-urutan");
                    var status = jQuery(this).attr("data-status");

                    jQuery("#compose .modal-title").html("Edit Coa");
                    jQuery("#compose-form").attr("action","<?=base_url();?>coa/update/"+id);
                    jQuery("input[name=coa]").val(coa);
                    jQuery("input[name=nama]").val(Nama);
                    jQuery("select[name=header]").val(header),
                    jQuery("input[name=grup]").val(grup),
                    jQuery("select[name=level]").val(level),
                    jQuery("input[name=urutan]").val(urutan),
                    jQuery("select[name=status]").val(status),

                    jQuery("#compose").modal("toggle");
                 
                });

        </script>