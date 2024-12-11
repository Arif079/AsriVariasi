        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Bukti Terima Barang</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li><a href="<?=base_url("btb");?>">Bukti Terima Barang</a></li>
                            <li class="active">Tambah BTB</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="card">
                <div class="card-body">
                    <form>
                        <label>NOP :</label>
                            <select id="nop" class="form-control npo">
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($purchase as $npo) {
                                    if ($npo-> status == 'PO') {
                                    echo '<option value="'.$npo->nop.'"data-supp="' . $npo->name . '"data-kdsupp="' . $npo->supplier_id.'">'.$npo->nop.'</option>
                                    ';
                                }
                            }
                                ?>
                            </select>
                        <div class="form-group">
                            <label>Supplier :</label>
                            <span id="supplier" class="form-control form-control-sm"></span>
                            <input type="hidden" id="hidden-sp" name="supplier_id" value="">
                           
                            <label> No Surat Jalan</label>
                            <input type="text" class="form-control form-control-sm" name="surat">
                        </div>
                        <label>Barang :</label>
                        <table class="table table-bordered" id="purchaseForm">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th style="width:25%">Harga</th>
                                    <th style="width:25%">Qty</th>
                                    <th>Aksi</th>
                                    <th style="width:20%;display:none;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><div class="product-name form-control form-control-sm" data-kditem="" data-id="" data-stock="" data-qty="" data-kecil="" data-satuan="">--Pilih--</div></td>
                                    <td><span class="product-price form-control form-control-sm" data-id="" data-price=""></td>
                                    <td><input type="number" class="product-qty form-control form-control-sm" data-max="" name="qty"></td>
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
                        <h5 class="modal-title" id="largeModalLabel">Pilih Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered" id="listProduct">
                            <thead>
                                <tr>
                                    <th>Kode Item</th>
                                    <th>Nama</th>
                                    <th style="width:20%">Opsi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script>
            var pickedProductIds = [];
             document.addEventListener('DOMContentLoaded', function() {
                // Handle the change event of the customer select element
                $('.npo').change(function() {
                    var selectedNPO = $(this).val();
                    if (selectedNPO !== "") {
                        var selectedSupplier = $(this).find("option:selected");
                        var spname = selectedSupplier.data('supp');
                        var spkd = selectedSupplier.data('kdsupp');
                        // Update the visible supplier <span> and hidden input field
                        $("#supplier").text(spname);
                        $("#hidden-sp").val(spkd);
                        refreshListProductDataTable(selectedNPO);
                    } else {
                        clearPurchaseOptions();
                    }
                });
            })
            $("#listProduct").DataTable({
                info: false,
                lengthChange: false,
                autoWidth: false,
                "processing": true,
                "serverSide": true,
                "ajax": {"url": "<?=base_url("Btb/json_product");?>"}
            });

            $(".npo").on("change", function() {
                var selectedNPO = $(this).val();
                if (selectedNPO !== "") {
                    refreshListProductDataTable(selectedNPO);
                } else {
                clearPurchaseOptions();
                }
            });

            // Function to refresh the listProduct DataTable with a filter
            function refreshListProductDataTable(selectedNPO) {
                var table = $("#listProduct").DataTable();
                table.clear().draw(); // Clear existing data in the table
                table.ajax.url("<?= base_url("Btb/json_product"); ?>?nop=" + selectedNPO).load(); // Reload with the filter
                
                pickedProductIds.forEach(function (productId) {
                $("tr[data-id='" + productId + "']").hide();
                });
    }

         
            $(".btn-add-row").on("click",function(){

                jQuery("#purchaseForm tbody").append('<tr><td><div class="product-name form-control form-control-sm" data-kditem="" data-id="" data-stock="" data-qty="" data-kecil="" data-satuan="">--Pilih--</div></td><td><span class="product-price form-control form-control-sm" data-id="" data-price=""></td><td><input type="number" class="product-qty form-control form-control-sm" data-max="" name="qty"></td><td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-del"><i class="fa fa-times"></i></button></td></tr>');                

            })

            $('body').on("click",".product-name",function(){
                var index = jQuery(this).parent().parent().index();

                jQuery("#selectProduct").attr("data-index",index);
                jQuery("#selectProduct").modal("toggle");
            })
            

            $('body').on("click",".btn-choose",function(){
                var indexAffected = parseInt(jQuery("#selectProduct").attr("data-index")) + 1;
                

                var id = jQuery(this).attr("data-id");
                var kditem = jQuery(this).attr("data-kditem");
                var name = jQuery(this).attr("data-name");
                var stock = jQuery(this).attr("data-stock");
                var max = jQuery(this).attr("data-max");
                var price = jQuery(this).attr("data-price");
                var kecil = jQuery(this).attr("data-kecil");
                var satuan = jQuery(this).attr("data-satuan");

                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-name").attr("data-id",id);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-name").attr("data-kditem",kditem);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-name").attr("data-stock",stock);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-name").attr("data-qty",max);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-name").attr("data-kecil",kecil);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-name").attr("data-satuan",satuan);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-name").html(name);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-price").attr("data-price",price);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-price").text(price);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-qty").val(max);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-qty").attr("data-max",max);
                var pickedProductId = $(this).attr("data-id");
                pickedProductIds.push(pickedProductId);

                // Hide the selected product in the list
                $(this).closest("tr").hide();

                
                jQuery("#selectProduct").modal("toggle");
            })

            $(".btn-save").on("click",function(){
                var items = [];
                var total = 0;
                var countItem = jQuery("#purchaseForm tbody tr").length;
                
                for(var i = 1;i<=countItem;i++) {
                    var item = "#purchaseForm tbody tr:nth-child("+i+")";
                    var tmp = {};
                    tmp["product_id"] = jQuery(item+" .product-name").attr("data-id");
                    tmp["product_name"] = jQuery(item+" .product-name").attr("data-kditem");
                    tmp["product_stock"] = jQuery(item+" .product-name").attr("data-stock");
                    tmp["product_max"] = jQuery(item+" .product-qty").attr("data-max");
                    tmp["price"] = jQuery(item+" .product-price").text();
                    tmp["qty"] = jQuery(item+" input[name=qty]").val();
                    tmp["kecil"] = jQuery(item+" .product-name").attr("data-kecil");
                    tmp["satuan"] = jQuery(item+" .product-name").attr("data-satuan");
                    total = total + parseFloat(tmp["price"]* tmp["qty"]); //* tmp["qty"]);

                    items.push(tmp);
                }

                var form = {};
                form["supplier_id"] = jQuery("#hidden-sp").val();
                form["nop"] = jQuery(".npo").val();
                form["surat"] = jQuery("input[name=surat]").val();
                form["total"] = total;
                form["details"] = items;

                postJson(JSON.stringify(form));

            })
            
            function postJson(json) {  
                jQuery.ajax({
                    url: "<?=base_url('Btb/create');?>",
                    method: "POST",
                    data: json,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function(data){
                        if(data.status) {
                            jQuery("#hidden-sp").val("");
                            jQuery("#purchaseForm tbody").html('<tr><td><div class="product-name form-control form-control-sm" data-id="" data-stock="">--Pilih--</div></td><td><span class="product-price form-control form-control-sm" data-id="" data-price=""></td>  <td><input type="number" class="product-qty form-control form-control-sm" data-max="" name="qty"></td><td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-del"><i class="fa fa-times"></i></button></td></tr>');

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
            $('body').on("change", "input[name=qty]", function () {
            var qty = parseFloat($(this).val());
            var maxQty = parseFloat($(this).attr("data-max"));


            if (qty > maxQty) {
                Swal.fire(
                    'Gagal',
                    'Jumlah Barang Melebihi Permintaan',
                    'error'
                );
                // Reset the input field to the maximum allowed value
                $(this).val(maxQty);
            }
        })

        $('#listProduct').on('draw.dt', function () {
                // Get the index of the row affected
                var indexAffected = parseInt(jQuery("#selectProduct").attr("data-index"));

                // Disable options with data-max equal to 0
                $("#listProduct tbody tr").each(function (index) {
                    var maxQty = parseFloat($(this).find(".btn-choose").attr("data-max"));
                    if (maxQty === 0 && index === indexAffected) {
                        // If data-max is 0 for the current option and it's the selected option, show an error
                        Swal.fire(
                            'Gagal',
                            'Stok Barang Tidak Tersedia',
                            'error'
                            ).then(function () {
                            // Reload the page after the error is displayed
                            location.reload();
                        });
                        jQuery("#selectProduct").modal("toggle");
                    } else if (maxQty === 0) {
                        // If data-max is 0 for other options, disable them
                        $(this).find(".btn-choose").prop("disabled", true);
                    } else {
                        // Enable options with data-max greater than 0
                        $(this).find(".btn-choose").prop("disabled", false);
                    }
                });
            });

            $("body").on("click",".btn-del",function(){
                jQuery(this).parent().parent().remove();
            })
           
        </script>