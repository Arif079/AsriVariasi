        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Tambah Teknisi</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li><a href="<?=base_url("wo");?>">Teknisi</a></li>
                            <li class="active">Tambah Teknisi</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="card">
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label>No Work Order</label>
                            <select class="form-control workorder">
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($nowo as $wo) {
                                    if ($wo-> noinvoice == '') {
                                        echo '<option value="' . $wo->nowo .'"data-id="' . $wo->transaction_id .'">' . $wo->nowo . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <!--<div>
                        <span type="text" id="test" class="form-control" >
                            </div>-->
                        <label>Teknisi :</label>
                        <table class="table table-bordered" id="MekanikForm">
                            <thead>
                                <tr>
                                    <th>Teknisi</th>
                                    <th>Kode Job</th>
                                    <th>In</th>
                                    <th>Pause</th>
                                    <th>Out</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><div class="Mekanik-name form-control form-control-sm" data-kdsls="">--Pilih--</div></td>
                                    <td><div class="kdjob form-control form-control-sm" data-kdjob="">--Pilih--</div></td>
                                    <td><input type="text" id="in"class="form-control form-control-sm" name="in"></td>
                                    <td><input type="text" id="ps"class="form-control form-control-sm" name="pause"></td>
                                    <td><input type="text" id="out"class="form-control form-control-sm" name="out"></td>
                                    <td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-del"><i class="fa fa-times"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                    <button class="btn btn-success btn-sm mb-2 btn-add-row"><i class="fa fa-plus"></i> Tambah</button>
                </div>
            </div>
            <div class="text-right mb-4">
                <button class="btn btn-secondary">Cancel</button>
                <button class="btn btn-primary btn-save"><i class="fa fa-save"></i> Simpan</button>
            </div>
        </div>

        <div class="modal" id="selectProduct" data-index="">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="largeModalLabel">Pilih Mekanik</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered" id="listProduct">
                            <thead>
                                <tr>
                                    <th>Kode Mekanik</th>
                                    <th>Nama mekanik</th>
                                    <th style="width:20%">Opsi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="selectjob" data-index="">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="largeModalLabel">Pilih Job</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered" id="listjob">
                            <thead>
                                <tr>
                                    <th>Kode job</th>
                                    <th>Nama job</th>
                                    <th style="width:20%">Opsi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
             
                $('#in').timepicker({
                timeFormat: 'H:i',
                step:5,
                });

                $('#ps').timepicker({
                timeFormat: 'H:i',
                step:5,
                });

                $('#jk').timepicker({
                timeFormat: 'H:i',
                step:5,
                });

                $('#out').timepicker({
                timeFormat: 'H:i',
                step:5,
                });
            
            });
            $("#listProduct").DataTable({
                info: false,
                lengthChange: false,
                autoWidth: false,
                "processing": true,
                "serverSide": true,
                "ajax": {"url": "<?=base_url("wo/json_mekanik");?>"}
            });

            $(".workorder").on("change", function() {
                var selectedNPO = $(this).find("option:selected").attr("data-id");
                $("#test").text(selectedNPO);
                if (selectedNPO !== "") {
                    refreshListProductDataTable(selectedNPO);
                } else {
                clearPurchaseOptions();
                }
            });
            $("#listjob").DataTable({
                info: false,
                lengthChange: false,
                autoWidth: false,
                "processing": true,
                "serverSide": true,
                "ajax": {"url": "<?=base_url("wo/json_job");?>"}
            });
            
            function refreshListProductDataTable(selectedNPO) {
                var table = $("#listjob").DataTable();
                table.clear().draw(); // Clear existing data in the table
                table.ajax.url("<?= base_url("wo/json_job"); ?>?transaction_id=" + selectedNPO).load(); // Reload with the filter
                pickedProductIds.forEach(function (productId) {
                //$("tr[data-id='" + productId + "']").hide();
                });
            }

            $(".btn-add-row").on("click", function () {
                // Create a new row with unique IDs
                var newRow = '<tr>' +
                    '<td><div class="Mekanik-name form-control form-control-sm" data-kdsls="">--Pilih--</div></td>' +
                    '<td><div class="kdjob form-control form-control-sm" data-kdjob="">--Pilih--</div></td>'+
                    '<td><input type="text" class="form-control form-control-sm in" name="in"></td>' +
                    '<td><input type="text" class="form-control form-control-sm ps" name="pause"></td>' +
                    '<td><input type="text" class="form-control form-control-sm out" name="out"></td>' +
                    '<td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-del"><i class="fa fa-times"></i></button></td>' +
                    '</tr>';

                // Append the new row to the table
                $("#MekanikForm tbody").append(newRow);

                // Initialize timepicker for the newly added row
                $("#MekanikForm tbody tr:last-child .in").timepicker({
                    timeFormat: 'H:i',
                    step:5,
                });

                $("#MekanikForm tbody tr:last-child .ps").timepicker({
                    timeFormat: 'H:i',
                    step:5,
                });

                $("#MekanikForm tbody tr:last-child .out").timepicker({
                    timeFormat: 'H:i',
                    step:5,
                });
            });


            $('body').on("click",".Mekanik-name",function(){
                var index = jQuery(this).parent().parent().index();

                jQuery("#selectProduct").attr("data-index",index);
                jQuery("#selectProduct").modal("toggle");
            })

            $('body').on("click",".kdjob",function(){
                var index = jQuery(this).parent().parent().index();

                jQuery("#selectjob").attr("data-index",index);
                jQuery("#selectjob").modal("toggle");
            })

            $('body').on("click",".btn-choose",function(){
                var indexAffected = parseInt(jQuery("#selectProduct").attr("data-index")) + 1;

                var kdsls = jQuery(this).attr("data-kdsls");
                var name = jQuery(this).attr("data-name");
                

                jQuery("#MekanikForm tbody tr:nth-child("+indexAffected+") .Mekanik-name").attr("data-kdsls",kdsls);
                jQuery("#MekanikForm tbody tr:nth-child("+indexAffected+") .Mekanik-name").html(name);


                jQuery("#selectProduct").modal("toggle");
            })

            $('body').on("click",".btn-choose2",function(){
                var indexAffected = parseInt(jQuery("#selectProduct").attr("data-index")) + 1;

                var kdjob = jQuery(this).attr("data-kditem");
                var name = jQuery(this).attr("data-name");
                

                jQuery("#MekanikForm tbody tr:nth-child("+indexAffected+") .kdjob").attr("data-kdjob",kdjob);
                jQuery("#MekanikForm tbody tr:nth-child("+indexAffected+") .kdjob").html(name);


                jQuery("#selectjob").modal("toggle");
            })

            $(".btn-save").on("click",function(){
                var items = [];
                var countItem = jQuery("#MekanikForm tbody tr").length;
                
                for(var i = 1;i<=countItem;i++) {
                    var item = "#MekanikForm tbody tr:nth-child("+i+")";
                    var tmp = {};
                    tmp["kdsls"] = jQuery(item+" .Mekanik-name").attr("data-kdsls");
                    tmp["kdjob"] = jQuery(item+" .kdjob").attr("data-kdjob");
                    tmp["nama"] = jQuery(item+" .Mekanik-name").html();
                    tmp["in"] = jQuery(item+" input[name=in]").val();
                    tmp["pause"] = jQuery(item+" input[name=pause]").val();
                    tmp["out"] = jQuery(item+" input[name=out]").val();

                    items.push(tmp);
                }
                
                

                var form = {};
                form["wo"] = jQuery(".workorder").val();
                form["details"] = items;

                postJson(JSON.stringify(form));

            })

            
            function postJson(json) {  
                jQuery.ajax({
                    url: "<?=base_url('wo/create');?>",
                    method: "POST",
                    data: json,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function(data){
                        if(data.status) {
                            jQuery(".workorder").val("");
                            jQuery("#MekanikForm tbody").html('<tr>td><div class="Mekanik-name form-control form-control-sm" data-kdsls="">--Pilih--</div></td><td><input type="text" class="form-control form-control-sm" id="in" name="in"></td><td><input type="text" class="form-control form-control-sm" id="ps" name="pause"></td><td><input type="text" class="form-control form-control-sm" id="out" name="out"></td><td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-del"><i class="fa fa-times"></i></button></td></tr>');
                            Swal.fire(
                                "Berhasil",
                                data.msg,
                                "success"
                            );
                            window.history.back();
                        } else {
                            Swal.fire(
                                "Gagal",
                                data.msg,
                                "error"
                            );
                        }
                    }
                })
            }

            $("body").on("click",".btn-del",function(){
                jQuery(this).parent().parent().remove();
            })
        </script>