        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>PPN</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url('dashboard');?>">Dashboard</a></li>
                            <li class="active">PPN</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-sm btn-success btn-add"><i class="fa fa-plus"></i> Tambah PPN</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode PPN</th>
                                    <th>Uraian</th>
                                    <th>Jumlah PPN</th>
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
                        <h5 class="modal-title" id="largeModalLabel">Tambah PPN</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                        <div class="form-group">
                                <label>Kode PPN</label>
                                <input type="text" name="kdppn" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>Uraian PPN</label>
                                <input type="text" name="name" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>Jumlah PPN</label>
                                <input type="number" name="ppn" class="form-control"/>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary btn-confirm">Simpan</button>
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
            $("#data").DataTable({
                "processing": true,
                "serverSide": true,
                "autoWidth":true,
                "order": [],
                "ajax": {"url": "<?=base_url("Ppn/json");?>"}
            });

            $(".btn-add").on("click",function() {
                jQuery("#compose .modal-title").html("Tambah PPN");
                jQuery("#compose form").attr("action","<?=base_url("Ppn/insert");?>");
                jQuery("#compose form input").val("");
                jQuery("#compose").modal("toggle");
            })

            $("body").on("click",".btn-edit",function(){
                var id = jQuery(this).attr("data-id");
                jQuery("#compose .modal-title").html("Edit PPN");

                jQuery.getJSON("<?=base_url("Ppn/get");?>/"+id,function(data){
                    jQuery("#compose form").attr("action","<?=base_url("Ppn/edit");?>/"+id);
                    jQuery("#compose form input[name=kdppn]").val(data.kdppn);
                    jQuery("#compose form input[name=name]").val(data.name);
                    jQuery("#compose form input[name=ppn]").val(data.ppn);
                    jQuery("#compose").modal("toggle");
                })
            })

            $(".btn-confirm").on("click",function() {
                var form = {
                    "kdppn": jQuery("#compose input[name=kdppn]").val(),
                    "name": jQuery("#compose input[name=name]").val(),
                    "ppn": jQuery("#compose input[name=ppn]").val()
                }

                var action = jQuery("#compose form").attr("action");

                jQuery.ajax({
                    url: action,
                    method: "POST",
                    data: form,
                    dataType: "json",
                    success: function(data){
                        if(data.status) {
                            jQuery("#data").DataTable().ajax.reload(null,true);
                            jQuery("#compose").modal("toggle");
                            Swal.fire(
                                "Berhasil",
                                data.msg,
                                "success"
                            );
                        } else {
                            Swal.fire(
                                "Gagal",
                                data.msg,
                                "error"
                            );
                        }
                    }
                });
            })

            function deleteData(id) {
                jQuery.getJSON("<?=base_url("Ppn/delete");?>/"+id,function(data){
                    if(data.status) {
                        jQuery("#data").DataTable().ajax.reload(null,true);
                        jQuery("#delete").modal("toggle");
                        Swal.fire(
                            "Berhasil",
                            data.msg,
                            "success"
                        );
                    }
                });
            }

            $('body').on("click",".btn-delete",function() {
                    var id = jQuery(this).attr("data-id");
                    var name = jQuery(this).attr("data-name");
                    jQuery("#delete .modal-body").html("Anda yakin ingin menghapus <b>"+name+"</b>");
                    jQuery("#delete").modal("toggle");

                    jQuery("#delete .btn-del-confirm").attr("onclick","deleteData("+id+")");
            })
        </script>