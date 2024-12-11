        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Edit PO</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li><a href="<?=base_url("Purchase");?>">Pembelian Stock</a></li>
                            <li class="active">Tambah PO</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="card">
                <div class="card-body">
                    <form>
                         <input type="hidden" name="purchase_id" value="<?php echo $purchase_id; ?>">

                        <div class="form-group">
                            <label>Supplier :</label>
                            <input type="text" class="form-control select-search" placeholder="Search...">
                            <select class="form-control supplier">
                            <option value="">-- Pilih --</option>
                            <?php foreach ($suppliers as $supplier) { ?>
                                <option value="<?= $supplier->id; ?>" <?= ($fetch->supplier_id == $supplier->id) ? 'selected' : ''; ?>>
                                    <?= $supplier->name; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <label>Tanggal Kirim</label>
                            <input type="text" name="date" class="form-control date"  value="<?= $fetch->tgl_kirim; ?>">
                            <label>Cara Bayar</label>
                            <select name="bayar" class="form-control bayar">
                            <option value="tunai" selected>Tunai</option>
                            <option value="kredit">Kredit</option>
                        </select>
                            <label>Keterangan</label>
                            <input type="text" name="keterangan" class="form-control ket"  value="<?= $fetch->ket; ?>">
                            <label>PPN :</label>
                            <select class="form-control ppn">
                            <option value="">-- Pilih --</option>
                            <?php foreach ($ppn as $vat) { ?>
                                <option value="<?= $vat->ppn; ?>" <?= ($fetch->ppn == $vat->ppn) ? 'selected' : ''; ?>>
                                    <?= $vat->name; ?>
                                </option>
                            <?php } ?>
                        </select>
                        </div>
                        <label>Barang :</label>
                        <table class="table table-bordered" id="PurchaseForm">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th style="width:25%">Harga</th>
                                    <th style="width:25%">Qty</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($details as $item) { ?>
                            <tr>
                                <td>
                                    <div class="product-name form-control form-control-sm" data-id="<?= $item->product_id; ?>" data-stock="<?= $item->stock; ?>">
                                        <?= $item->name; ?> <!-- Display the product name here -->
                                    </div>
                                </td>
                                <td><input type="number" class="form-control form-control-sm" name="price" value="<?= $item->price; ?>"></td>
                                <td><input type="number" class="form-control form-control-sm" name="qty" value="<?= $item->qty; ?>"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger btn-del"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        <?php } ?>
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
                                    <th>Kditem</th>
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
 $(document).ready(function () {
                $('input[name="date"]').datepicker({
                    format: 'dd-mm-yyyy', // You can change the format according to your needs
                    autoclose: true,
                    todayHighlight: true,
                });
                // Make an AJAX GET request to fetch data when the page loads
               

                
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
            $("#listProduct").DataTable({
                info: false,
                lengthChange: false,
                autoWidth: false,
                "processing": true,
                "serverSide": true,
                "ajax": {"url": "<?=base_url("Purchase/json_product");?>"}
            });

            $(".btn-add-row").on("click",function(){

                jQuery("#PurchaseForm tbody").append('<tr><td><div class="product-name form-control form-control-sm" data-id="" data-stock="">--Pilih--</div></td><td><input type="number" class="form-control form-control-sm" name="price"></td><td><input type="number" class="form-control form-control-sm" name="qty"></td><td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-del"><i class="fa fa-times"></i></button></td></tr>');                

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

                jQuery("#PurchaseForm tbody tr:nth-child("+indexAffected+") .product-name").attr("data-id",id);
                jQuery("#PurchaseForm tbody tr:nth-child("+indexAffected+") .product-name").attr("data-stock",stock);
                jQuery("#PurchaseForm tbody tr:nth-child("+indexAffected+") .product-name").html(name);

                jQuery("#selectProduct").modal("toggle");
            })

            $(".btn-save").on("click",function(){
                var items = [];
                var total = 0;
                var countItem = jQuery("#PurchaseForm tbody tr").length;
                
                for(var i = 1;i<=countItem;i++) {
                    var item = "#PurchaseForm tbody tr:nth-child("+i+")";
                    var tmp = {};
                    tmp["product_id"] = jQuery(item+" .product-name").attr("data-id");
                    tmp["product_stock"] = jQuery(item+" .product-name").attr("data-stock");
                    tmp["price"] = jQuery(item+" input[name=price]").val();
                    tmp["qty"] = jQuery(item+" input[name=qty]").val();
                    total = total + parseFloat(tmp["price"]* tmp["qty"]); //* tmp["qty"]);

                    items.push(tmp);
                }

                var selectedVAT = parseFloat($(".ppn").val());
                var totalWithVAT = total + (total * (selectedVAT / 100));
                var purchaseId = $('input[name="purchase_id"]').val();
                var form = {};
                form["purchase_id"] = purchaseId;
                form["supplier_id"] = jQuery(".supplier").val();
                form["ppn"] = jQuery(".ppn").val();
                form["date"] = jQuery("input[name=date]").val();
                form["bayar"] = jQuery("select[name=bayar]").val();
                form["keterangan"] = jQuery("input[name=keterangan]").val();
                form["total"] = total;
                form["details"] = items;

                postJson(JSON.stringify(form));

            })

            function postJson(json) {  
                jQuery.ajax({
                    url: "<?=base_url('Purchase/create2');?>",
                    method: "POST",
                    data: json,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function(data){
                        if(data.status) {
                            jQuery(".supplier").val("");
                            jQuery("#PurchaseForm tbody").html('<tr><td><div class="product-name form-control form-control-sm" data-id="" data-stock="">--Pilih--</div></td><td><input type="number" class="form-control form-control-sm" name="price"></td><td><input type="number" class="form-control form-control-sm" name="qty"></td><td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-del"><i class="fa fa-times"></i></button></td></tr>');

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