<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Bukti Kas Masuk</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li><a href="<?=base_url("retur");?>">BKM</a></li>
                            <li class="active">Tambah BKM</li>
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
                        <label>customer</label>
                            <select name="customer" class="form-control customer">
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($cust as $customer) {
                                    echo '<option value="'.$customer->name.'"data-id="' . $customer->id . '"data-coad="' . $customer->coad. '"data-coak="' . $customer->coak. '"data-kdsupp="' . $customer->kdcust.'">'.$customer->name.'</option>
                                    ';
                                }
                                ?>
                            </select>
                            <input type="hidden" id="hidden-sp" name="sp" value="">
                                
                        </div>
                        <div class="form-group">
                        <input type="text" class="form-control select-search" placeholder="Search...">
                                <label>Kode Akun</label>
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
                                    <th style="width:30%">Uraian</th>
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
                                    <td><input type="text" name="uraian" class="product-uraian form-control-sm form-control"></td>
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
                                    <th>No Spk</th>
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
             var coad;
            $("#listProduct").DataTable({
                info: false,
                lengthChange: false,
                autoWidth: false,
                "processing": true,
                "serverSide": true,
                "ajax": {"url": "<?=base_url("Bkm/json_product");?>"}
            });

            $(".customer").on("change", function() {
                var selectedNPO = $(this).val();
                var selectedcustomer = $("option:selected", this);
                if (selectedNPO !== "") {
                    refreshListProductDataTable(selectedNPO);
                    var customerId = selectedcustomer.data("kdsupp");
                    var debet = selectedcustomer.data("coad");
                     $("#hidden-sp").val(customerId); // 
                     coad = debet;
                } else {
                clearPurchaseOptions();
                }
            });

            // Function to refresh the listProduct DataTable with a filter
            function refreshListProductDataTable(selectedNPO) {
                var table = $("#listProduct").DataTable();
                table.clear().draw(); // Clear existing data in the table
                table.ajax.url("<?= base_url("Bkm/json_product"); ?>?nop=" + selectedNPO).load(); // Reload with the filter
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
                var name = jQuery(this).attr("data-spkid");
                var price = jQuery(this).attr("data-total");
                var sisa = jQuery(this).attr("data-sisa");
                var debet = coad;

                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-name").attr("data-id",id);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-name").html(name);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-price").attr("data-price",price);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-price").text(price);
                jQuery("#purchaseForm tbody tr:nth-child("+indexAffected+") .product-akun").text(coad);
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
                    tmp["spkid"] = jQuery(item+" .product-name").text();
                    tmp["price"] = jQuery(item+" .product-price").text();
                    tmp["text"] = jQuery(item+" input[name=uraian]").val();
                    tmp["debet"] = jQuery(item+" .product-akun").text();
                    tmp["bayar"] = jQuery(item+" input[name=bayar]").val();
                    tmp["sisa"] = jQuery(item+" .product-sisa").text();
                    
                    totalbayar = totalbayar + parseFloat(tmp["bayar"]);
                    total = total + parseFloat(tmp["price"]); //* tmp["qty"]);//* tmp["qty"]);
                    sisa = tmp["sisa"] - totalbayar;

                    items.push(tmp);
                }

                var form = {};
                form["cid"] = $(".customer option:selected").data("id");
                form["kredit"] = jQuery("select[name=akunkredit] option:selected").val();
                form["customer"] = jQuery(".customer option:selected").val();
                form["total"] = total;
                form["bayar"] = totalbayar;
                form["sisa"] = sisa;
                form["details"] = items;

                postJson(JSON.stringify(form));

            })

            function postJson(json) {  
                jQuery.ajax({
                    url: "<?=base_url('Bkm/create');?>",
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