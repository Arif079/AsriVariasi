        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Retur Barang</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li><a href="<?=base_url("retur");?>">Retur</a></li>
                            <li class="active">Tambah Retur barang</li>
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
                        <label>BTB :</label>
                            <select class="form-control npo">
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($btb as $npo) {
                                    echo '<option value="'.$npo->nbtb.'"data-supp="' . $npo->name . '"data-nop="' . $npo->nop. '"data-kdsupp="' . $npo->supplier_id.'">'.$npo->nbtb.'</option>
                                    ';
                                }
                                ?>
                            </select>
                            <input type="hidden" id="hidden-nop" name="nop" value="">
                        </div>
                        <div class="form-group">
                            <label>Supplier :</label>
                            <span id="supplier" class="form-control form-control-sm"></span>
                            <input type="hidden" id="hidden-sp" name="supplier_id" value="">
                        <label>Barang :</label>
                        <table class="table table-bordered" id="purchaseForm">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th style="width:25%">Harga</th>
                                    <th style="width:25%">Qty</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><div class="product-name form-control form-control-sm" data-id="" data-stock="">--Pilih--</div></td>
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
                        var nop = selectedSupplier.data('nop');
                        // Update the visible supplier <span> and hidden input field
                        $("#supplier").text(spname);
                        $("#hidden-sp").val(spkd);
                        $("#hidden-nop").val(nop);
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
                "ajax": {"url": "<?=base_url("retur/json_product");?>"}
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
                table.ajax.url("<?= base_url("retur/json_product"); ?>?nop=" + selectedNPO).load(); // Reload with the filter
                pickedProductIds.forEach(function (productId) {
                $("tr[data-id='" + productId + "']").hide();
                });
            }

            $(".btn-add-row").on("click",function(){

                jQuery("#purchaseForm tbody").append('<tr><td><div class="product-name form-control form-control-sm" data-id="" data-stock="" data-qty="">--Pilih--</div></td><td><span class="product-price form-control form-control-sm" data-id="" data-price=""></td><td><input type="number" class="product-qty form-control form-control-sm" data-max="" name="qty"></td><td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-del"><i class="fa fa-times"></i></button></td></tr>');

            })

            $('body').on("click",".product-name",function(){
                var index = jQuery(this).parent().parent().index();

                jQuery("#selectProduct").attr("data-index",index);
                jQuery("#selectProduct").modal("toggle");
            })

            $('body').on("click",".btn-choose",function(){
                var indexAffected = parseInt(jQuery("#selectProduct").attr("data-index")) + 1;
                var id = jQuery(this).attr("data-id");
                var name = jQuery(this).attr("data-name");
                var stock = jQuery(this).attr("data-stock");
                var max = jQuery(this).attr("data-qty");
                var price = jQuery(this).attr("data-price");

                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-name").attr("data-id",id);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-name").attr("data-stock",stock);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-name").attr("data-qty",max);
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
                    tmp["product_stock"] = jQuery(item+" .product-name").attr("data-stock");
                    tmp["product_max"] = jQuery(item+" .product-qty").attr("data-max");
                    tmp["price"] = jQuery(item+" .product-price").text();
                    tmp["qty"] = jQuery(item+" input[name=qty]").val();
                    total = total + parseFloat(tmp["price"]* tmp["qty"]); //* tmp["qty"]);

                    items.push(tmp);
                }

                var form = {};
                form["supplier_id"] = jQuery("#hidden-sp").val();
                form["npo"] = jQuery("#hidden-nop").val();
                form["nop"] = jQuery(".npo").val();
                form["total"] = total;
                form["details"] = items;

                postJson(JSON.stringify(form));

            })

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
            function postJson(json) {  
                jQuery.ajax({
                    url: "<?=base_url('retur/create');?>",
                    method: "POST",
                    data: json,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function(data){
                        if(data.status) {
                            jQuery("#hidden-sp").val("");
                            //jQuery("#purchaseForm tbody").append('<tr><td><div class="product-name form-control form-control-sm" data-id="" data-stock="" data-qty="">--Pilih--</div></td><td><span class="product-price form-control form-control-sm" data-id="" data-price=""></td><td><input type="number" class="product-qty form-control form-control-sm" data-max="" name="qty"></td><td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-del"><i class="fa fa-times"></i></button></td></tr>');

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