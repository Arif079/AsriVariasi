<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Bukti Kas Keluar</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li><a href="<?=base_url("Bkk");?>">BKK</a></li>
                            <li class="active">Tambah BKK</li>
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
                        <label>Supplier</label>
                            <select class="form-control supplier">
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($supp as $supplier) {
                                    echo '<option value="'.$supplier->id.'"data-supp="' . $supplier->name . '"data-coad="' . $supplier->coad. '"data-coak="' . $supplier->coak. '"data-kdsupp="' . $supplier->kdsupp.'">'.$supplier->name.'</option>
                                    ';
                                }
                                ?>
                            </select>
                            <input type="hidden" id="hidden-sp" name="sp" value="">
                                
                        </div>
                        <div class="form-group">
                                <label>Kode Akun</label>
                                <input type="text" class="form-control select-search" placeholder="Search...">
                                <select name="akunkredit" class="form-control coa">
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($coa as $akun) {
                                    if ($akun->isdetail == '1') {
                                    echo '<option value="'.$akun->coa.'">'.$akun->coa.' 
                                    - '.$akun->nama.'</option>
                                    ';}
                                }
                                ?>
                            </select>
                            </div>
                        <label>Transaksi :</label>
                        <table class="table table-bordered" id="purchaseForm">
                            <thead>
                                <tr>
                                    <th style="width:15%">No Transaksi</th>
                                    <th style="width:10%">Jumlah</th>
                                    <th style="width:10%">Inv</th>
                                    <th style="width:20%">Uraian</th>
                                    <th style="width:15%">Akun (Debet)</th>
                                    <th style="width:15%">Bayar</th>
                                    <th style="width:15%">Sisa</th>
                                    <th style="width:10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><div class="product-name form-control form-control-sm" data-id="" >--Pilih--</div></td>
                                    <td><span class="product-price form-control form-control-sm" data-id="" data-price=""></td>
                                    <td><span class="product-inv form-control form-control-sm" data-inv=""></td>
                                    <td><input type="text" name="uraian" class="form-control-sm form-control"></td>
                                    <td><span class="product-akun form-control form-control-sm" data-akun=""></td>
                                    <td><input type="number" name="bayar" class="product-baya form-control-sm form-control" data-sisa=""></td>
                                    <td><span class="product-sisa form-control form-control-sm"></td>
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
                                    <th>No Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>jumlah</th>
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
             var coak;
            $("#listProduct").DataTable({
                info: false,
                lengthChange: false,
                autoWidth: false,
                "processing": true,
                "serverSide": true,
                "ajax": {"url": "<?=base_url("Bkk/json_product");?>"}
            });

            $(".supplier").on("change", function() {
                var selectedNPO = $(this).val();
                var selectedSupplier = $("option:selected", this);
                if (selectedNPO !== "") {
                    refreshListProductDataTable(selectedNPO);
                    var supplierId = selectedSupplier.data("supp");
                    var debet = selectedSupplier.data("coak");
                     $("#hidden-sp").val(supplierId); // Set the supplier_id input value
                     coak = debet;
                } else {
                clearPurchaseOptions();
                }
            });

         

            // Function to refresh the listProduct DataTable with a filter
            function refreshListProductDataTable(selectedNPO) {
                var table = $("#listProduct").DataTable();
                table.clear().draw(); // Clear existing data in the table
                table.ajax.url("<?= base_url("bkk/json_product"); ?>?nop=" + selectedNPO).load(); // Reload with the filter
                pickedProductIds.forEach(function (productId) {
                $("tr[data-id='" + productId + "']").hide();
                });
            }

            $(".btn-add-row").on("click",function(){

                jQuery("#purchaseForm tbody").append('<tr><td><div class="product-name form-control form-control-sm" data-id="">--Pilih--</div></td><td><span class="product-price form-control form-control-sm" data-id="" data-price=""></td><td><input type="text" name="uraian" class="product-uraian form-control-sm form-control"></td><td><span class="product-akun form-control form-control-sm" data-akun=""></td><td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-del"><i class="fa fa-times"></i></button></td></tr>');

            })

            $('body').on("click",".product-name",function(){
                var index = jQuery(this).parent().parent().index();

                jQuery("#selectProduct").attr("data-index",index);
                jQuery("#selectProduct").modal("toggle");
            })

            $('body').on("click",".btn-choose",function(){
                var indexAffected = parseInt(jQuery("#selectProduct").attr("data-index")) + 1;
                var id = jQuery(this).attr("data-id");
                var name = jQuery(this).attr("data-ntrn");
                var price = jQuery(this).attr("data-total");
                var sisa = jQuery(this).attr("data-sisa");
                var inv = jQuery(this).attr("data-inv");
                var debet = coak;

                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-name").attr("data-id",id);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-name").html(name);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-price").attr("data-price",price);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-price").text(price);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-inv").attr("data-inv",inv);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-inv").text(inv);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-akun").text(coak);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-sisa").text(sisa);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-bayar").attr("data-sisa",sisa);
                var pickedProductId = $(this).attr("data-id");
                pickedProductIds.push(pickedProductId);

                // Hide the selected product in the list
                $(this).closest("tr").hide();
                jQuery("#selectProduct").modal("toggle");
            })
            $(".btn-save").on("click",function(){
                var items = [];
                var total = 0;
                var totalbayar = 0;
                var sisa = 0;
                var countItem = jQuery("#purchaseForm tbody tr").length;
                
                for(var i = 1;i<=countItem;i++) {
                    var item = "#purchaseForm tbody tr:nth-child("+i+")";
                    var tmp = {};
                    tmp["id"] = jQuery(item+" .product-name").data("id");
                    tmp["ntrn"] = jQuery(item+" .product-name").text();
                    tmp["price"] = jQuery(item+" .product-price").text();
                    tmp["text"] = jQuery(item+" input[name=uraian]").val();
                    tmp["debet"] = jQuery(item+" .product-akun").text();
                    tmp["bayar"] = jQuery(item+" input[name=bayar]").val();
                    tmp["sisa"] = jQuery(item+" .product-sisa").text();
                    tmp["inv"] = jQuery(item+" .product-inv").text();

                    totalbayar = totalbayar + parseFloat(tmp["bayar"]);
                    total = total + parseFloat(tmp["price"]); //* tmp["qty"]);//* tmp["qty"]);
                    sisa = tmp["sisa"] - totalbayar;
                    
                    items.push(tmp);
                }

                var form = {};
                form["supplier_id"] = jQuery(".supplier").val();
                form["kredit"] = jQuery("select[name=akunkredit]").val(),
                form["supplier"] = jQuery("#hidden-sp").val();
                form["total"] = total;
                form["bayar"] = totalbayar;
                form["sisa"] = sisa;
                form["details"] = items;

                postJson(JSON.stringify(form));

            })

            function postJson(json) {  
                jQuery.ajax({
                    url: "<?=base_url('bkk/create');?>",
                    method: "POST",
                    data: json,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function(data){
                        if(data.status) {
                            jQuery("#hidden-sp").val("");
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

            $('body').on("input", "input[name=bayar]", function() {
            // Get the parent row of the input field
            var row = $(this).closest("tr");

            // Get the sisa value for the current row
            var sisa = parseFloat(row.find(".product-sisa").text());

            // Get the inputted bayar value
            var bayar = parseFloat($(this).val());

            // Check if bayar exceeds sisa
            if (bayar > sisa) {
                // Display an error message or take appropriate action
                Swal.fire(
                    'Gagal',
                    'Jumlah Bayar tidak bisa melebihi hutang',
                    'error'
                );
                
                // Reset the input value to the maximum allowed (sisa)
                $(this).val(sisa);
            }
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