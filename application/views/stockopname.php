<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Stock Opname</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li class="active">Stock Opname</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="card">
                <div class="card-header">
                   <!-- <button class="btn btn-success btn-sm btn-show-add" data-toggle="modal" data-target="#compose"><i class="fa fa-plus"></i> Tambah Sparepart</button>-->
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="width:20%">kdjob</th>
                                    <th style="width:20%">Nama Job</th>
                                    <th style="width:20%">kditem</th>
                                    <th style="width:40%">Nama</th>
                                    <th>Stok</th>
                                    <th style="width:20%">Aksi</th>
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
                        <h5 class="modal-title" id="largeModalLabel">Edit Stock</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="compose-form">
                       
                        <div class="form-group">
                                <label>Kode Item</label>
                                <input type="text" name="kditem" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Nama Sparepart</label>
                                <input type="text" name="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Stok Awal</label>
                                <input type="number" name="stokawal" class="form-control" id="price" value="0">
                            </div>
                            <div class="form-group">
                                <label>Satuan</label>
                                <span id="satuan" class="form-control" readonly></span>
                            </div>

                            <div class="form-group">
                                <label>Stok Opname</label>
                                <input type="number" name="stokakhir" class="form-control" id="priceqq" value="0">
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

        <div class="modal" id="update" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="largeModalLabel">update price</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="update-form">
                       
                        <div class="form-group">
                                <label>Kode Item</label>
                                <input type="text" name="kditem" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Nama Sparepart</label>
                                <input type="text" name="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>price</label>
                                <input type="number" name="price" class="form-control" id="price" value="0">
                            </div>

                            <div class="form-group">
                                <label>price QQ</label>
                                <input type="number" name="priceqq" class="form-control" id="priceqq" value="0">
                            </div>  
                           
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary btn-submit2">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
                $(".btn-show-add").on("click",function(){
                    jQuery("input[name=kdjob]").val("");
                    jQuery("input[name=namajob]").val("");
                    jQuery("input[name=kditem]").val("");
                    jQuery("input[name=name]").val("");
                    jQuery("#compose .modal-title").html("Stockopname");
                    jQuery("#compose-form").attr("action","<?=base_url("Stockopname/insert");?>");
                });

                $("#data").DataTable({
                    "processing": true,
                    "serverSide": true,
                    "autoWidth":true,
                    "order": [],
                    "ajax": {"url": "<?=base_url("Stockopname/json");?>"}
                });


                $('.btn-submit').on("click",function(){
                    var form = {
                        "kditem": jQuery("input[name=kditem]").val(),
                        "name": jQuery("input[name=name]").val(),
                        "awal": jQuery("input[name=stokawal]").val(),
                        "stock": jQuery("input[name=stokakhir]").val(),
                        "satuan": jQuery("#satuan").text(), 
                    }

                    var action = jQuery("#compose-form").attr("action");

                    jQuery.ajax({
                        url: action,
                        method: "POST",
                        data: form,
                        dataType: "json",
                        success: function(data){
                            if(data.status) {
                                jQuery("input[name=kditem]").val("");
                                jQuery("input[name=name]").val("");
                                jQuery("input[name=stokawal]").val("");
                                jQuery("input[name=stokakhir]").val("");
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

                $('.btn-submit2').on("click",function(){
                    var form = {
                        "kditem": jQuery("input[name=kditem]").val(),
                        "name": jQuery("input[name=name]").val(),
                        "price": jQuery("input[name=price]").val(),
                        "priceqq": jQuery("input[name=priceqq]").val(),
                      
                    }

                    var action = jQuery("#update-form").attr("action");

                    jQuery.ajax({
                        url: action,
                        method: "POST",
                        data: form,
                        dataType: "json",
                        success: function(data){
                            if(data.status) {
                                jQuery("input[name=kditem]").val("");
                                jQuery("input[name=name]").val("");
                                jQuery("input[name=price]").val("");
                                jQuery("input[name=priceqq]").val("");
                                jQuery("#update").modal('toggle');
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

                $("body").on("click",".btn-edit",function(){
                    var id = jQuery(this).attr("data-id");
                    var kditem = jQuery(this).attr("data-kditem");
                    var name = jQuery(this).attr("data-name");
                    var stock = jQuery(this).attr("data-stock");
                    var satuan = jQuery(this).attr("data-kecil");

                    jQuery("#compose .modal-title").html("Stock Opname");
                    jQuery("#compose-form").attr("action","<?=base_url();?>Stockopname/update/"+id);
                    jQuery("input[name=kditem]").val(kditem);
                    jQuery("input[name=name]").val(name);
                    jQuery("input[name=stokawal]").val(stock);
                    jQuery("#satuan").text(satuan);
                    jQuery("#compose").modal("toggle");
                });

                $("body").on("click",".btn-edit2",function(){
                    var id = jQuery(this).attr("data-id");
                    var kditem = jQuery(this).attr("data-kditem");
                    var name = jQuery(this).attr("data-name");
                    var price = jQuery(this).attr("data-price");
                    var priceqq = jQuery(this).attr("data-priceqq");

                    jQuery("#update .modal-title").html("Update Price");
                    jQuery("#update-form").attr("action","<?=base_url();?>Stockopname/update_price/"+id);
                    jQuery("input[name=kditem]").val(kditem);
                    jQuery("input[name=name]").val(name);
                    jQuery("input[name=price]").val(price);
                    jQuery("input[name=priceqq]").val(priceqq);
                    jQuery("#update").modal("toggle");
                });

        </script>