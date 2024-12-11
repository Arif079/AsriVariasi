        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Service  Work Order</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li class="active">Service Work Order</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-success btn-sm btn-show-add" data-toggle="modal" data-target="#compose"><i class="fa fa-plus"></i> Tambah WO</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data">
                            <thead>
                                <tr>
                                    <th style="width:10%">#</th>
                                    <th>No WO</th>
                                    <th>No SPK</th>
                                    <th>Tipe</th>
                                    <th>Merk Mobil</th>
                                    <th>No Rangka</th>
                                    <th>No Mesin</th>
                                    <th>Warna</th>
                                    <th>Tahun</th>
                                    <th>Tanggal IN</th>
                                    <th>Jam In</th>
                                    <th>KM In</th>
                                    <th>Tanggal Out</th>
                                    <th>Jam Out</th>
                                    <th>KM Out</th>
                                    <th style="width:40%" >Aksi</th>
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
                    <h5> Jam Teknisi </h5>
                    <a href="<?=base_url("wo/new");?>" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah Jam Teknisi</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data-teknisi">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>No Work Order</th>
                                    <th>Nama Teknisi</th>
                                    <th>IN</th>
                                    <th>PAUSE</th>
                                    <th>OUT</th>
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
                    <h5> DAFTAR INVOICE </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data-invoice">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>No WO</th>
                                    <th>No SPK</th>
                                    <th>No Invoice</th>
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
                        <h5 class="modal-title" id="largeModalLabel">Tambah Detail WO</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="compose-form">
                        <div class="form-row col-md-6">
                            <div class="form-group">
                                <label>SPK ID</label>
                                <select class="form-control spk" id="spk-select">
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($spk as $spkid) {
                                    if ($spkid-> status == 'SPK') {
                                        echo '<option value="' . $spkid->spkid .'" data-tipe="' . $spkid->tipe .'" data-transaction_id="' . $spkid->id . '" data-merk="' . $spkid->merk . '" data-noka="' . $spkid->noka . '" data-nosin="' . $spkid->nosin . '" data-tahun="' . $spkid->tahun . '" data-warna="' . $spkid->warna .  '">' . $spkid->spkid . '</option>';
                                    }
                                }
                                ?>
                                </select>
                                <input type="text" name="spk" id="hidden-spk" style="display:none"class="form-control" readonly >
                                <input type="text" name="hidden-id" id="hidden-id" style="display:none"class="form-control" readonly>
                                <label>Tipe</label>
                                <input type="text" name="tipe" class="form-control" readonly>
                                <label>Merk</label>
                                <input type="text" name="merk" class="form-control" readonly>
                            </div></div>
                            <div class="form-row col-md-6">
                            <div class="form-group">
                                <label>No Rangka</label>
                                <input type="text" name="noka" class="form-control" readonly>
                                <label>No Mesin</label>
                                <input type="text" name="nosin" class="form-control" readonly>
                                <label>Warna</label>
                                <input type="text" name="warna" class="form-control" readonly>
                            </div></div>
                            <div class="form-group">
                            <label>Tahun</label>
                                <input type="text" name="tahun" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                            <div class="form-row col-md-6">
                                <label>Tanggal masuk</label>
                                <input type="text" name="tglmasuk" class="form-control">
                                <label>Jam masuk</label>
                                <input type="text" name="jammasuk" id="jm"class="form-control">
                                <label>KM Masuk</label>
                                <input type="number" name="kmmasuk" class="form-control">
                            </div>
                           <div class="form-row col-md-6">
                                <label>Tanggal Keluar</label>
                                <input type="text" name="tglkeluar" class="form-control">
                                <label>Jam Keluar</label>
                                <input type="text" name="jamkeluar" id="jk"class="form-control">
                                <label>KM Keluar</label>
                                <input type="number" name="kmkeluar" class="form-control">
                            </div></div>
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
                    jQuery("input[name=tipe]").val("");
                    jQuery("input[name=id]").val("");
                    jQuery("input[name=merk]").val("");
                    jQuery("input[name=noka]").val("");
                    jQuery("input[name=nosin]").val("");
                    jQuery("input[name=warna]").val("");
                    jQuery("input[name=tahun]").val("");
                    jQuery("input[name=tglmasuk]").val("");
                    jQuery("input[name=jammasuk]").val("");
                    jQuery("input[name=kmmasuk]").val("");
                    jQuery("input[name=tglkeluar]").val("");
                    jQuery("input[name=jamkeluar]").val("");
                    jQuery("input[name=kmkeluar]").val("");
                    jQuery("#compose .modal-title").html("Tambah Work Order");
                    jQuery("#compose-form").attr("action","<?=base_url("wo/insert");?>");
                });

                $("#data").DataTable({
                    "processing": true,
                    "serverSide": true,
                    "autoWidth":true,
                    "order": [],
                    "ajax": {"url": "<?=base_url("wo/json");?>"}
                });


                $('.btn-submit').on("click",function(){
                    var form = {
                        "spk": jQuery("input[name=spk]").val(),
                        "tipe": jQuery("input[name=tipe]").val(),
                        "id": jQuery("input[name=hidden-id]").val(),
                        "merk": jQuery("input[name=merk]").val(),
                        "noka": jQuery("input[name=noka]").val(),
                        "nosin": jQuery("input[name=nosin]").val(),
                        "warna": jQuery("input[name=warna]").val(),
                        "tahun": jQuery("input[name=tahun]").val(),
                        "tglmasuk": jQuery("input[name=tglmasuk]").val(),
                        "jammasuk": jQuery("input[name=jammasuk]").val(),
                        "kmmasuk": jQuery("input[name=kmmasuk]").val(),
                        "tglkeluar": jQuery("input[name=tglkeluar]").val(),
                        "jamkeluar": jQuery("input[name=jamkeluar]").val(),
                        "kmkeluar": jQuery("input[name=kmkeluar]").val(),
                    }

                    var action = jQuery("#compose-form").attr("action");

                    jQuery.ajax({
                        url: action,
                        method: "POST",
                        data: form,
                        dataType: "json",
                        success: function(data){
                            if(data.status) {
                                jQuery("input[name=tipe]").val("");
                                jQuery("input[name=hidden-id]").val("");
                                jQuery("input[name=merk]").val("");
                                jQuery("input[name=noka]").val("");
                                jQuery("input[name=nosin]").val("");
                                jQuery("input[name=warna]").val("");
                                jQuery("input[name=tahun]").val("");
                                jQuery("input[name=tglmasuk]").val("");
                                jQuery("input[name=jammasuk]").val("");
                                jQuery("input[name=kmmasuk]").val("");
                                jQuery("input[name=tglkeluar]").val("");
                                jQuery("input[name=jamkeluar]").val("");
                                jQuery("input[name=kmkeluar]").val("");
    
                                jQuery("#compose").modal('toggle');
                                jQuery("#data").DataTable().ajax.reload(null,true);
                                Swal.fire({
                                title: 'Berhasil',
                                text: data.msg,
                                icon: 'success'
                            }).then((result) => {
                                if (result.value) {
                                    // Redirect to a specific page for "Berhasil"
                                    window.location.href = '<?= base_url("wo"); ?>';
                                }
                            });
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
                    var spkid = jQuery(this).attr("data-spk");
                    jQuery("#delete .modal-body").html("Anda yakin ingin menghapus <b>"+spkid+"</b>");
                    jQuery("#delete").modal("toggle");

                    jQuery("#delete .btn-del-confirm").attr("onclick","deleteData("+id+")");
                })

                function deleteData(id) {
                    jQuery.getJSON("<?=base_url();?>wo/delete/"+id,function(data){
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
                    var tid = jQuery(this).attr("data-transaction_id");
                    var spkid = jQuery(this).attr("data-spk");
                    var tipe = jQuery(this).attr("data-tipe");
                    var merk = jQuery(this).attr("data-merk");
                    var noka = jQuery(this).attr("data-noka");
                    var nosin = jQuery(this).attr("data-nosin");
                    var warna = jQuery(this).attr("data-warna");
                    var tahun = jQuery(this).attr("data-tahun");

                    idInput.value = tid;
                    tipeInput.value = tipe;
                    merkInput.value = merk;
                    nokaInput.value = noka;
                    nosinInput.value = nosin;
                    warnaInput.value = warna;
                    tahunInput.value = tahun;

                    // Set the value of the hidden spk input
                    $("#spk-select").hide();
                    // Find the hidden input element by its ID (replace 'hidden-input' with your actual ID)
                    $("#hidden-spk").val(spkid);
                    $("#hidden-spk").show();
                    

                    
                    
                    jQuery("#compose .modal-title").html("Edit Service");
                    jQuery("#compose-form").attr("action","<?=base_url();?>wo/update/"+id);

                    var tglmasuk = jQuery(this).attr("data-tglmasuk");
                    var jammasuk = jQuery(this).attr("data-jammasuk");
                    var kmmasuk = jQuery(this).attr("data-kmmasuk");
                    var tglkeluar = jQuery(this).attr("data-tglkeluar");
                    var jamkeluar = jQuery(this).attr("data-jamkeluar");
                    var kmkeluar = jQuery(this).attr("data-kmkeluar");

                    var inputTglmasuk = document.querySelector('input[name="tglmasuk"]');
                    inputTglmasuk.value = tglmasuk;

                    var inputJammasuk = document.querySelector('input[name="jammasuk"]');
                    inputJammasuk.value = jammasuk;

                    var inputKmmasuk = document.querySelector('input[name="kmmasuk"]');
                    inputKmmasuk.value = kmmasuk;

                    var inputTglkeluar = document.querySelector('input[name="tglkeluar"]');
                    inputTglkeluar.value = tglkeluar;

                    var inputJamkeluar = document.querySelector('input[name="jamkeluar"]');
                    inputJamkeluar.value = jamkeluar;

                    var inputKmkeluar = document.querySelector('input[name="kmkeluar"]');
                    inputKmkeluar.value = kmkeluar;

                    

                    jQuery("#compose").modal("toggle");
                });
                
                const spkSelect = document.getElementById('spk-select');
                const hiddenSpkInput = document.getElementById('hidden-spk');
                const tipeInput = document.querySelector('input[name="tipe"]');
                const idInput = document.querySelector('input[name="hidden-id"]');
                const merkInput = document.querySelector('input[name="merk"]');
                const nokaInput = document.querySelector('input[name="noka"]');
                const nosinInput = document.querySelector('input[name="nosin"]');
                const warnaInput = document.querySelector('input[name="warna"]');
                const tahunInput = document.querySelector('input[name="tahun"]');

                spkSelect.addEventListener('change', function () {
                    const selectedOption = spkSelect.options[spkSelect.selectedIndex];

                    // Set values for the readonly inputs based on the selected option's data attributes
                    idInput.value = selectedOption.getAttribute('data-transaction_id') || '';
                    tipeInput.value = selectedOption.getAttribute('data-tipe') || '';
                    merkInput.value = selectedOption.getAttribute('data-merk') || '';
                    nokaInput.value = selectedOption.getAttribute('data-noka') || '';
                    nosinInput.value = selectedOption.getAttribute('data-nosin') || '';
                    warnaInput.value = selectedOption.getAttribute('data-warna') || '';
                    tahunInput.value = selectedOption.getAttribute('data-tahun') || '';
                    hiddenSpkInput.value = spkSelect.value;
                });
                $(document).ready(function() {
                $('input[name="tglmasuk"]').datepicker({
                    format: 'yyyy-mm-dd', // You can change the format according to your needs
                    autoclose: true,
                    todayHighlight: true,
                });
                $('input[name="tahun"]').datepicker({
                    format: "yyyy",
                    viewMode: "years", 
                    minViewMode: "years",
                    autoclose: true,
                    });
                $('input[name="tglkeluar"]').datepicker({
                    format: 'yyyy-mm-dd', // You can change the format according to your needs
                    autoclose: true,
                    todayHighlight: true,
                });

                $('#jm').timepicker({
                timeFormat: 'H:i'
                });

                $('#jk').timepicker({
                timeFormat: 'H:i'
                });
              
            });
            $("#data-teknisi").DataTable({
                    "processing": true,
                    "serverSide": true,
                    "autoWidth":true,
                    "order": [[0,"desc"]],
                    "ajax": {"url": "<?=base_url("wo/json_teknisi");?>"}
                });
            
            $("#data-invoice").DataTable({
                    "processing": true,
                    "serverSide": true,
                    "autoWidth":true,
                    "order": [[0,"desc"]],
                    "ajax": {"url": "<?=base_url("wo/json_invoice");?>"}
                });

                $("body").on("click",".btn-close",function(){
                        var woId = $(this).data("id");
                        $.ajax({
                            url: "wo/invoice", // Adjust the URL to match your controller's method
                            method: "POST",
                            data: { id: woId },
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

                    $("body").on("click",".btn-batal",function(){
                        var woId = $(this).data("id");
                        $.ajax({
                            url: "wo/batal", // Adjust the URL to match your controller's method
                            method: "POST",
                            data: { id: woId },
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
        </script>