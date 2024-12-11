<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1> User</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url('dashboard');?>">Dashboard</a></li>
                            <li class="active">User</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-sm btn-success btn-add"><i class="fa fa-plus"></i> Tambah User</button>
                 </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Role ID</th>
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
                        <h5 class="modal-title" id="largeModalLabel">Tambah User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                        <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="text" name="password" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>Role</label>
                                <select name="role" id="role"class="form-control role">
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($role as $roles) {
                                       echo '<option value="'.$roles->roleid.'">'.$roles->roleid.' - '.$roles->rolename.'</option>
                                    ';
                                    }
                                ?>
                            </select>
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
                "ajax": {"url": "<?=base_url("UserInput/json");?>"}
            });

            $(".btn-add").on("click",function() {
                jQuery("#compose .modal-title").html("Tambah User");
                jQuery("#compose form").attr("action","<?=base_url("UserInput/insert");?>");
                jQuery("#compose form input,textarea").val("");
                jQuery("#compose").modal("toggle");
            })

            $("body").on("click",".btn-edit",function(){
                var id = jQuery(this).attr("data-id");
                jQuery("#compose .modal-title").html("Edit Sales");

                jQuery.getJSON("<?=base_url("UserInput/get");?>/"+id,function(data){
                    jQuery("#compose form").attr("action","<?=base_url("UserInput/edit");?>/"+id);
                    jQuery("#compose form input[name=username]").val(data.username);
                    jQuery("#compose form input[name=password]").val(data.password);
                    jQuery("#compose form input[name=telephone]").val(data.role_id);

                    jQuery("#compose").modal("toggle");
                })
            })

            $(".btn-confirm").on("click",function() {
                var form = {
                    "username": jQuery("#compose input[name=username]").val(),
                    "password": jQuery("#compose input[name=password]").val(),
                    "role": jQuery("#compose select[name=role]").val(),
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
                jQuery.getJSON("<?=base_url("UserInput/delete");?>/"+id,function(data){
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
                    var name = jQuery(this).attr("data-username");
                    jQuery("#delete .modal-body").html("Anda yakin ingin menghapus <b>"+name+"</b>");
                    jQuery("#delete").modal("toggle");

                    jQuery("#delete .btn-del-confirm").attr("onclick","deleteData("+id+")");
            })
        </script>