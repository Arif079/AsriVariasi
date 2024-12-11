<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Kacafilm</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li class="active">Kacafilm</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-success btn-sm btn-show-add" data-toggle="modal" data-target="#compose"><i class="fa fa-plus"></i> Tambah Kacafilm</button>
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
                                    <th style="width:20%">Qty Besar</th>
                                    <th style="width:20%">Satuan Besar</th>
                                    <th style="width:20%">Qty Kecil</th>
                                    <th style="width:20%">Satuan Kecil</th>
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
                        <h5 class="modal-title" id="largeModalLabel">Tambah Kacafilm</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="compose-form">
                        <div class="form-group">
                                <label>Kode Job</label>
                                <input type="text" name="kdjob" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Nama Job</label>
                                <input type="text" name="namajob" class="form-control">
                            </div>
                        <div class="form-group">
                                <label>Kode Item</label>
                                <input type="text" name="kditem" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Nama Kacafilm</label>
                                <input type="text" name="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Quantity Besar</label>
                                <input type="number" name="qtybesar" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Satuan Besar</label>
                                <select name="satuanbesar" id="satuanbesar"class="form-control satuanbesar">
                                <option value="">-- Pilih --</option>
                             <?php
                                foreach($satuan as $satu) {
                                    echo '<option value="'.$satu->kdsatuan.'">'.$satu->kdsatuan.' - '.$satu->name.'</option>
                                    ';
                                }
                                ?>
                        </select>
                            <div class="form-group">
                                <label>Quantity Kecil</label>
                                <input type="number" name="qtykecil" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Satuan Kecil</label>
                                <select name="satuankecil" id="satuankecil" class="form-control satuankecil">
                                <option value="">-- Pilih --</option>
                             <?php
                                foreach($satuan as $satu) {
                                    echo '<option value="'.$satu->kdsatuan.'">'.$satu->kdsatuan.' - '.$satu->name.'</option>
                                    ';
                                }
                                ?>
                        </select>
                            <div class="form-group">
                                <label>Harga Kacafilm</label>
                                <input type="number" name="price" class="form-control" id="price" value="0">
                            </div>
                            <div class="form-group">
                                <label>Harga Kacafilm QQ</label>
                                <input type="number" name="priceqq" class="form-control" id="priceqq" value="0">
                            </div>
                            <div class="form-group">
                                <label>Kode Akun HPP</label>
                                <input type="text" class="form-control select-search" placeholder="Search...">
                                <select name="coahpp" id="coahpp"class="form-control coahpp">
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($coa as $akun) {
                                    if ($akun->isdetail == '1') {
                                    echo '<option value="'.$akun->coa.'">'.$akun->coa.' - '.$akun->nama.'</option>
                                    ';
                                    }
                                }
                                ?>
                        </select>
                        </div>
                        <div class="form-group">
                                <label>Kode Akun Persediaan</label>
                                <input type="text" class="form-control select-search" placeholder="Search...">
                                <select name="coapersediaan" id="coapersediaan" class="form-control coapersediaan">
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($coa as $akun) {
                                    if ($akun->isdetail == '1') {
                                    echo '<option value="'.$akun->coa.'">'.$akun->coa.' - '.$akun->nama.'</option>
                                    ';
                                    }
                                }
                                ?>
                        </select>
                        </div>
                        <div class="form-group">
                                <label>Kode Akun Penjualan</label>
                                <input type="text" class="form-control select-search" placeholder="Search...">
                                <select name="coapenjualan" id="coapenjualan" class="form-control coapenjualan">
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($coa as $akun) {
                                    if ($akun->isdetail == '1') {
                                    echo '<option value="'.$akun->coa.'">'.$akun->coa.' - '.$akun->nama.'</option>
                                    ';
                                    }
                                }
                                ?>
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

        <div class="modal" id="update" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="largeModalLabel">Update Modal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <div class="form-group">
                                <label>Kode Item</label>
                                <input type="text" name="kditem" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Nama Kacafilm</label>
                                <input type="text" name="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Harga Kacafilm</label>
                                <input type="number" name="price" class="form-control" id="price" value="0">
                            </div>
                            <div class="form-group">
                                <label>Harga Kacafilm QQ</label>
                                <input type="number" name="priceqq" class="form-control" id="priceqq" value="0">
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary btn-update-confirm">Update</button>
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
                    jQuery("input[name=price]").val("");
                    jQuery("input[name=priceqq]").val("");
                    jQuery("input[name=qtybesar]").val("");
                    jQuery("input[name=qtykecil]").val("");
                    jQuery("#compose .modal-title").html("Tambah Kacafilm");
                    jQuery("#compose-form").attr("action","<?=base_url("kacafilm/insert");?>");
                });

                $("#data").DataTable({
                    "processing": true,
                    "serverSide": true,
                    "autoWidth":true,
                    "order": [],
                    "ajax": {"url": "<?=base_url("kacafilm/json");?>"}
                });


                $('.btn-submit').on("click",function(){
                    var form = {
                        "kdjob": jQuery("input[name=kdjob]").val(),
                        "namajob": jQuery("input[name=namajob]").val(),
                        "kditem": jQuery("input[name=kditem]").val(),
                        "name": jQuery("input[name=name]").val(),
                        "price": jQuery("input[name=price]").val(),
                        "priceqq": jQuery("input[name=priceqq]").val(),
                        "qtybesar": jQuery("input[name=qtybesar]").val(),
                        "qtykecil": jQuery("input[name=qtykecil]").val(),
                        "satuanbesar": jQuery("select[name=satuanbesar]").val(),
                        "satuankecil": jQuery("select[name=satuankecil]").val(),
                        "coahpp": jQuery("select[name=coahpp]").val(),
                        "coapersediaan": jQuery("select[name=coapersediaan]").val(),
                        "coapenjualan": jQuery("select[name=coapenjualan]").val(),

                    }

                    var action = jQuery("#compose-form").attr("action");

                    jQuery.ajax({
                        url: action,
                        method: "POST",
                        data: form,
                        dataType: "json",
                        success: function(data){
                            if(data.status) {
                                jQuery("input[name=kdjob]").val("");
                                jQuery("input[name=namajob]").val("");
                                jQuery("input[name=kditem]").val("");
                                jQuery("input[name=name]").val("");
                                jQuery("input[name=price]").val("");
                                jQuery("input[name=priceqq]").val("");
                                jQuery("select[name=coahpp]").val("");
                                jQuery("select[name=coapersediaan]").val("");
                                jQuery("select[name=coapenjualan]").val("");
    
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
                    var name = jQuery(this).attr("data-name");
                    jQuery("#delete .modal-body").html("Anda yakin ingin menghapus <b>"+name+"</b>");
                    jQuery("#delete").modal("toggle");

                    jQuery("#delete .btn-del-confirm").attr("onclick","deleteData("+id+")");
                })

                function deleteData(id) {
                    jQuery.getJSON("<?=base_url();?>services/delete/"+id,function(data){
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
                                'Gagal',
                                data.msg,
                                'error'
                            )
                        }
                    })
                }

                $("body").on("click",".btn-edit",function(){
                    var id = jQuery(this).attr("data-id");
                    var kdjob = jQuery(this).attr("data-kdjob");
                    var namajob = jQuery(this).attr("data-namajob");
                    var kditem = jQuery(this).attr("data-kditem");
                    var name = jQuery(this).attr("data-name");
                    var price = jQuery(this).attr("data-price");
                    var priceqq = jQuery(this).attr("data-priceqq");

                    jQuery("#compose .modal-title").html("Edit Kacafilm");
                    jQuery("#compose-form").attr("action","<?=base_url();?>kacafilm/update/"+id);
                    jQuery("input[name=kdjob]").val(kdjob);
                    jQuery("input[name=namajob]").val(namajob);
                    jQuery("input[name=kditem]").val(kditem);
                    jQuery("input[name=name]").val(name);
                    jQuery("input[name=price]").val(price);
                    jQuery("input[name=priceqq]").val(priceqq);

                    jQuery("#compose").modal("toggle");
                });

                $("body").on("click", ".btn-update-price", function () {
                var kditem = $(this).attr("data-kditem");
                var name = $(this).attr("data-name");
                var price = $(this).attr("data-price");
                var priceqq = $(this).attr("data-priceqq");

                $("#update .modal-title").html("Update Harga untuk " + name);
                $("input[name=kditem]").val(kditem);
                $("input[name=name]").val(name);
                $("input[name=price]").val(price);
                $("input[name=priceqq]").val(priceqq);

                $("#update").modal("toggle");
            });


                $("body").on("click", "#update .btn-update-confirm", function () {
                var id = $(this).data("id"); // Make sure to set the data-id attribute in your HTML
                var form = {
                    "kditem": $("input[name=kditem]").val(),
                    "name": $("input[name=name]").val(),
                    "price": $("input[name=price]").val(),
                    "priceqq": $("input[name=priceqq]").val()
                };

                var action = "<?= base_url(); ?>kacafilm/updatePrice/" + id;

                $.ajax({
                    url: action,
                    method: "POST",
                    data: form,
                    dataType: "json",
                    success: function (data) {
                        if (data.status) {
                            $("#update").modal("toggle");
                            $("#data").DataTable().ajax.reload(null, true);
                            Swal.fire(
                                'Berhasil',
                                data.msg,
                                'success'
                            );
                        } else {
                            Swal.fire(
                                'Gagal',
                                data.msg,
                                'error'
                            );
                        }
                    }
                });
                });
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
        </script>