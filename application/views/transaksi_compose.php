        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Tambah Invoice</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li><a href="<?=base_url("transaksi");?>">Invoice</a></li>
                            <li class="active">Tambah Invoice</li>
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
                                    echo '<option value="'.$npo->nop.'"data-id="' . $npo->id . '"data-supp="' . $npo->name. '"data-ppn="' . $npo->ppn . '"data-kdsupp="' . $npo->supplier_id.'">'.$npo->nop.'</option>
                                    ';
                                }
                            }
                                ?>
                            </select>
                            <input type="hidden" id="hidden-nop" name="nop" value="">

                        <div class="form-group">
                        <label>Supplier :</label>
                            <span id="supplier" class="form-control form-control-sm"></span>
                            <input type="hidden" id="hidden-sp" name="supplier_id" value="">
                        </div>
                        <label>No Invoice</label>
                            <input type="text" class="form-control form-control-sm" name="invoice">
                        <div class="form-group">
                        <label>PPN :</label>
                            <span id="ppn" class="form-control form-control-sm"></span>
                        </div>
                        <label>Barang :</label>
                        <table class="table table-bordered" id="purchaseForm">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th style="width:25%">Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><div class="product-name form-control form-control-sm" data-id="">--Pilih--</div></td>
                                    <td><span class="product-price form-control form-control-sm" data-id="" data-price=""></td>
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
                                    <th>No BTB</th>
                                    <th>Total</th>
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
             var ppna;
             document.addEventListener('DOMContentLoaded', function() {
                // Handle the change event of the customer select element
                $('.npo').change(function() {
                    var selectedNPO = $(this).val();
                    if (selectedNPO !== "") {
                        var selectedSupplier = $(this).find("option:selected");
                        var spname = selectedSupplier.data('supp');
                        var spkd = selectedSupplier.data('kdsupp');
                        var nopid = selectedSupplier.data('id');
                        var ppn = selectedSupplier.data('ppn');
                        // Update the visible supplier <span> and hidden input field
                        $("#supplier").text(spname);
                        $("#hidden-sp").val(spkd);
                        $("#hidden-nop").val(nopid);
                        $("#ppn").text(ppn);
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
                "ajax": {"url": "<?=base_url("transaksi/json_product");?>"}
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
                table.ajax.url("<?= base_url("transaksi/json_product"); ?>?nop=" + selectedNPO).load(); // Reload with the filter
                pickedProductIds.forEach(function (productId) {
                $("tr[data-id='" + productId + "']").hide();
                });
            }

            $(".btn-add-row").on("click", function () {

              jQuery("#purchaseForm tbody").append('<tr><td><div class="product-name form-control form-control-sm" data-id="">--Pilih--</div></td><td><span class="product-price form-control form-control-sm" data-price=""></span></td><td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-del"><i class="fa fa-times"></i></button></td></tr>');
            
            });


            $('body').on("click",".product-name",function(){
                var index = jQuery(this).parent().parent().index();

                jQuery("#selectProduct").attr("data-index",index);
                jQuery("#selectProduct").modal("toggle");
            })

            $('body').on("click",".btn-choose",function(){
                var indexAffected = parseInt(jQuery("#selectProduct").attr("data-index")) + 1;

                var id = jQuery(this).attr("data-id");
                var nbtb = jQuery(this).attr("data-nbtb");
                var price = jQuery(this).attr("data-price");

                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-name").attr("data-id",id);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-name").html(nbtb);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-price").attr("data-price",price);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-price").text(price); // Display the price
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
                var ppnValue = parseFloat($("#ppn").text());
                
                for(var i = 1;i<=countItem;i++) {
                    var item = "#purchaseForm tbody tr:nth-child("+i+")";
                    var tmp = {};
                    tmp["btb_id"] = jQuery(item+" .product-name").attr("data-id");
                    tmp["nbtb"] = jQuery(item+" .product-name").attr("data-nbtb");
                    tmp["price"] = jQuery(item + " .product-price").text();
                    total = total + parseFloat(tmp["price"]);//* tmp["qty"]); //* tmp["qty"]);

                    items.push(tmp);
                }
                var totalWithPPN = total + (total * (ppnValue / 100));
                var form = {};
                form["supplier_id"] = jQuery("#hidden-sp").val();
                form["nop"] = jQuery("#hidden-nop").val();
                form["invoice"] = jQuery("input[name=invoice]").val();
                form["total"] = totalWithPPN;
                form["details"] = items;
                

                postJson(JSON.stringify(form));

            })

            
            function postJson(json) {  
                jQuery.ajax({
                    url: "<?=base_url('transaksi/create');?>",
                    method: "POST",
                    data: json,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function(data){
                        if(data.status) {
                            jQuery("#hidden-sp").val("");
                            jQuery("#purchaseForm tbody").html('<tr><td><div class="product-name form-control form-control-sm" data-id="">--Pilih--</div></td><td><span class="product-price form-control form-control-sm" data-price=""></span></td><td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-del"><i class="fa fa-times"></i></button></td></tr>');
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